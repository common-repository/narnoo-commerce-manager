<?php

/*
* This class manage Securepay payment gateways
*/

if( !class_exists ( 'NCM_Securepay_Gateways' ) ) {



class NCM_Securepay_Gateways {



    function __construct() {



    }



    function ncm_payment_securepay( $data, $gateway_name, $gateway ) {

        global $ncm_settings, $ncm_checkout, $ncm, $ncm_payment_gateways;

        if( isset($gateway['ncm_securepay_enabled']) && !empty($gateway['ncm_securepay_enabled']) ) {

            $eway_mode = 'live';

            $apiUrl = ''; 

            if( isset($gateway['ncm_securepay_testmode']) && $gateway['ncm_securepay_testmode'] == '1' ) {

                $securepay_mode = 'test';

                $sMerchantId = ( isset($gateway['ncm_securepay_test_merchantid']) && !empty($gateway['ncm_securepay_test_merchantid']) ) ? $gateway['ncm_securepay_test_merchantid'] : '';

                $sPassword = ( isset($gateway['ncm_securepay_test_password']) && !empty($gateway['ncm_securepay_test_password']) ) ? $gateway['ncm_securepay_test_password'] : '';

                $api_url = 'https://test.securepay.com.au/xmlapi/payment';

            } else {

                $securepay_mode = 'live';

                $sMerchantId = ( isset($gateway['ncm_securepay_merchantid']) && !empty($gateway['ncm_securepay_merchantid']) ) ? $gateway['ncm_securepay_merchantid'] : '';

                $sPassword = ( isset($gateway['ncm_securepay_password']) && !empty($gateway['ncm_securepay_password']) ) ? $gateway['ncm_securepay_password'] : '';

                $api_url = 'https://api.securepay.com.au/xmlapi/payment';

            }



            if( !empty( $sMerchantId ) && !empty( $sPassword ) ) { 



                $order_id = $data['order_id'];



                $ncm_checkout->ncm_store_geteway_mode( $order_id, $gateway_name, $securepay_mode);



                $all_country = $ncm_settings->ncm_country();







                $user_info = isset( $data['contact'] ) ? $data['contact'] : '';



                $products = $data['products'];







                $amount = $data['subtotal'] * 100;



                $description = implode(', ', array_column($products, 'tour_name'));;



                $user_email = $user_info['email'];



                $user_firstname = $user_info['firstName'];



                $user_lastname = $user_info['lastName'];



                $phone = isset($user_info['phone/Mobile']) ? $user_info['phone/Mobile'] : '';
                

                $country_name = isset($user_info['country']) ? $user_info['country'] : '';


                $coutnry = ( !empty( $country_name ) ) ? array_search( $country_name, $all_country ) : '';


                $comment = isset($user_info['comment']) ? $user_info['comment'] : '';



                $request_data = $data['all'];



                $card_holder_name = $request_data['ncm_securepay_card_holder_name'];



                $card_no = $request_data['ncm_securepay_credit_card'];



                $card_expiry = $request_data['ncm_securepay_exp'];



                $card_expiry_arr = explode("/", $card_expiry);



                $card_exp_month = isset($card_expiry_arr['0']) ? $card_expiry_arr['0'] : 0;



                $card_exp_year = isset($card_expiry_arr['1']) ? $card_expiry_arr['1'] : 0;



                $card_cvv = $request_data['ncm_securepay_cvc'];



                $currency = $ncm_settings->ncm_get_currency();





                $timeStamp = date("YdmHisB" . "000+660");

                

                $mId = time();

                $xmlRequest = '<?xml version="1.0" encoding="UTF-8"?>

                <SecurePayMessage>

                    <MessageInfo>

                        <messageID>'.$mId.'</messageID>

                        <messageTimestamp>' . $timeStamp . '</messageTimestamp>

                        <timeoutValue>60</timeoutValue>

                        <apiVersion>xml-4.2</apiVersion>

                    </MessageInfo>

                    <MerchantInfo>

                        <merchantID>' . $sMerchantId . '</merchantID>

                        <password>' . $sPassword . '</password>

                    </MerchantInfo>

                    <RequestType>Payment</RequestType>

                    <Payment>

                    <TxnList count="1">

                        <Txn ID="1">

                        <txnType>0</txnType>

                        <txnSource>23</txnSource>

                        <amount>'.($data['subtotal'] * 100).'</amount>

                        <currency>'.$currency.'</currency>

                        <purchaseOrderNo>'.$order_id.'</purchaseOrderNo>

                        <CreditCardInfo>

                            <cardNumber>'.$card_no.'</cardNumber>

                            <expiryDate>'.$card_exp_month.'/'. $card_exp_year.'</expiryDate>

                            <cvv>'.$card_cvv.'</cvv>

                            <cardHolderName>'.$card_holder_name.'</cardHolderName>

                        </CreditCardInfo>

                        </Txn>

                    </TxnList>

                    </Payment>

                </SecurePayMessage>';





                $sresponse = wp_remote_post($api_url, array(

                        'method' => 'POST',

                        'timeout' => 45,

                        'redirection' => 5,

                        'httpversion' => '1.0',

                        'blocking' => true,

                        'headers' => array('content-type' => 'text/xml'),

                        'body' => $xmlRequest,

                        'cookies' => array()

                    )

                );





                if(!is_wp_error($sresponse) && $sresponse['response']['code'] >= 200 && $sresponse['response']['code'] < 300) {



                    $apiResp = $sresponse['body'];



                    $xml = simplexml_load_string($apiResp);



                    if(isset($xml->Status->statusCode) && $xml->Status->statusCode != '000') {

                        

                        $responsecode = $xml->Status->statusCode;

                        $responsetext = $xml->Status->statusDescription;

                        

                    } elseif(isset($xml->Payment->TxnList->Txn->approved)) {

                        

                        $responsecode = $xml->Payment->TxnList->Txn->responseCode;

                        $responsetext = $xml->Payment->TxnList->Txn->responseText;

                        $transactionId = $xml->Payment->TxnList->Txn->txnID;



                    } else {

                        $responsecode = false;

                    }





                    if ($responsecode == '00' || $responsecode == '08') {





                        //$order->add_order_note(sprintf(__('Payment is successfully completed. Transaction ID: %s.', 'woocommerce'), $transactionId));

                        if( isset($data['ncm_is_live']) && $data['ncm_is_live'] ) {

                            $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'completed' );

                        } else {

                            $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'non-confirmed' );

                        }

                        

                        update_post_meta( $order_id, 'ncm_securepay_responsecode', (string) $responsecode );



                        update_post_meta( $order_id, 'ncm_transaction', (string) $transactionId );



                        $status  =  "success";



                        $msg = __('Payment successfull. ', NCM_txt_domain) . '('.$responsetext.')';



                        $content = __('Payment successfull. ', NCM_txt_domain);



                    } else {

                        

                        $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'ncm-failed' );



                        $status  =  "failed";



                        $msg = __('Payment can not be processed ', NCM_txt_domain).'(' . $responsetext.')';



                        $content = __('Payment can not be processed ', NCM_txt_domain).'(' . $responsetext.')';

                    }



                } else {

                    $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'ncm-failed' );



                    $status  =  "failed";



                    $msg = __('Payment Gateway Error.', NCM_txt_domain);



                    $content = __('Payment Gateway Error.', NCM_txt_domain);



                } 



                update_post_meta( $order_id, 'ncm_gateway_response', json_encode($sresponse));



                $response = array( "status" => $status, "msg" => $msg, "content" => $content );



            } else {



                $status = 'failed';



                $msg = __("sorry! something went wrong.", NCM_txt_domain);



                $content = __("SecurePay merchantID or password is blank.", NCM_txt_domain);



                $response = array( "status" => $status, "msg" => $msg, "content" => $content );



            }



        } else {



            $status = 'failed';



            $msg = __("SecurePay is not enabled.", NCM_txt_domain);



            $content = __("SecurePay is not enabled.", NCM_txt_domain);



            $response = array( "status" => $status, "msg" => $msg, "content" => $content );



        }



        return $response;



    }







    function ncm_refund_securepay( $order_id, $gateway_name, $gateway ) {



        global $ncm_settings, $ncm_checkout, $ncm, $ncm_payment_gateways, $ncm_order;



        if( isset($gateway['ncm_securepay_enabled']) && !empty($gateway['ncm_securepay_enabled']) ) {



            $eway_mode = 'live';



            if( $ncm_checkout->ncm_get_geteway_mode( $order_id, $gateway_name ) == 'test' ) {



                $eway_mode = 'test';



                $sMerchantId = ( isset($gateway['ncm_securepay_test_merchantid']) && !empty($gateway['ncm_securepay_test_merchantid']) ) ? $gateway['ncm_securepay_test_merchantid'] : '';



                $sPassword = ( isset($gateway['ncm_securepay_test_password']) && !empty($gateway['ncm_securepay_test_password']) ) ? $gateway['ncm_securepay_test_password'] : '';



                $api_url = 'https://test.securepay.com.au/xmlapi/payment';



            } else {



                $eway_mode = 'live';



                $sMerchantId = ( isset($gateway['ncm_securepay_merchantid']) && !empty($gateway['ncm_securepay_merchantid']) ) ? $gateway['ncm_securepay_merchantid'] : '';



                $sPassword = ( isset($gateway['ncm_securepay_password']) && !empty($gateway['ncm_securepay_password']) ) ? $gateway['ncm_securepay_password'] : '';



                $api_url = 'https://api.securepay.com.au/xmlapi/payment';



            }







            if( !empty( $sMerchantId ) && !empty( $sPassword ) ) {







                $all_country = $ncm_settings->ncm_country();



                $user_data = $ncm_order->ncm_get_order_booking_data( $order_id );



                $user_firstname = isset($user_data['First Name']) ? $user_data['First Name'] : '';



                $user_lastname = isset($user_data['Last Name']) ? $user_data['Last Name'] : '';



                $user_email = isset($user_data['Email']) ? $user_data['Email'] : '';



                $phone = isset($user_data['Phone/Mobile']) ? $user_data['Phone/Mobile'] : '';
                
                
                $country_name = isset($user_data['Country']) ? $user_data['Country'] : '';

                $comment = isset($user_data['Comment']) ? $user_data['Comment'] : '';


                $country = ( !empty( $country_name ) ) ? array_search( $country_name, $all_country ) : '';



                $currency = $ncm_settings->ncm_get_currency();



                $transaction_id = get_post_meta( $order_id, 'ncm_transaction', true );



                $subtotal =  get_post_meta( $order_id, 'ncm_subtotal', true );





                $timeStamp = date("YdmHisB" . "000+660");

                

                $mId = time();

                $xmlRequest = '<?xml version="1.0" encoding="UTF-8"?>

                <SecurePayMessage>

                    <MessageInfo>

                        <messageID>'.$mId.'</messageID>

                        <messageTimestamp>' . $timeStamp . '</messageTimestamp>

                        <timeoutValue>60</timeoutValue>

                        <apiVersion>xml-4.2</apiVersion>

                    </MessageInfo>

                    <MerchantInfo>

                        <merchantID>' . $sMerchantId . '</merchantID>

                        <password>' . $sPassword . '</password>

                    </MerchantInfo>

                    <RequestType>Payment</RequestType>

                    <Payment>

                    <TxnList count="1">

                        <Txn ID="1">

                        <txnType>4</txnType>

                        <txnSource>23</txnSource>

                        <amount>'.($subtotal * 100).'</amount>

                        <purchaseOrderNo>'.$order_id.'</purchaseOrderNo>

                        <txnID>'.$transaction_id.'</txnID>

                        </Txn>

                    </TxnList>

                    </Payment>

                </SecurePayMessage>';



                $sresponse = wp_remote_post($api_url, array(

                        'method' => 'POST',

                        'timeout' => 45,

                        'redirection' => 5,

                        'httpversion' => '1.0',

                        'blocking' => true,

                        'headers' => array('content-type' => 'text/xml'),

                        'body' => $xmlRequest,

                        'cookies' => array()

                    )

                );



                if(!is_wp_error($sresponse) && $sresponse['response']['code'] >= 200 && $sresponse['response']['code'] < 300) {



                    $apiResp = $sresponse['body'];



                    $xml = simplexml_load_string($apiResp);



                    if(isset($xml->Status->statusCode) && $xml->Status->statusCode != '000') {

                        

                        $responsecode = $xml->Status->statusCode;

                        $responsetext = $xml->Status->statusDescription;

                        

                    } elseif(isset($xml->Payment->TxnList->Txn->approved)) {

                        

                        $responsecode = $xml->Payment->TxnList->Txn->responseCode;

                        $responsetext = $xml->Payment->TxnList->Txn->responseText;

                        $transactionId = $xml->Payment->TxnList->Txn->txnID;



                    } else {

                        $responsecode = false;

                    }



                    if ($responsecode == '00' || $responsecode == '08') {



                        update_post_meta( $order_id, 'ncm_securepay_refund_id', (string) $transactionId );



                        $status  =  "success";



                        $msg = '';



                        $content = '';



                    } else {



                        $status  =  "failed";



                        $msg = __('Payment can not be processed ', NCM_txt_domain).'(' . $responsetext.')';



                        $content = __('Payment can not be processed ', NCM_txt_domain).'(' . $responsetext.')';



                    }



                } else {



                    $status  =  "failed";



                    $msg = __('Payment Gateway Error.', NCM_txt_domain);



                    $content = __('Payment Gateway Error.', NCM_txt_domain);



                }                 



                update_post_meta( $order_id, 'ncm_refund_gateway_response', json_encode($sresponse));



                return array( "status" => $status, "msg" => $msg, "content" => $content );



            } else {



                $status = 'failed';



                $msg = __("sorry! something went wrong.", NCM_txt_domain);



                $content = __("SecurePay merchantID or password is blank.", NCM_txt_domain);



                return array( "status" => $status, "msg" => $msg, "content" => $content );



            }



        } else {



            $status = 'failed';



            $msg = __("SecurePay is not enabled.", NCM_txt_domain);



            $content = __("SecurePay is not enabled.", NCM_txt_domain);



            return array( "status" => $status, "msg" => $msg, "content" => $content );



        }



    }



}







global $ncm_securepay_gateways;



$ncm_securepay_gateways = new NCM_Securepay_Gateways();







}



?>