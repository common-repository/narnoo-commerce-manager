<?php

/*
* This function manage Eway payment gateways
*/

if( !class_exists ( 'NCM_Eway_Gateways' ) ) {







class NCM_Eway_Gateways {



    



    function __construct(){







    }







    function ncm_payment_eway( $data, $gateway_name, $gateway ) {



        global $ncm_settings, $ncm_checkout, $ncm, $ncm_payment_gateways;



        if( isset($gateway['ncm_eway_enabled']) && !empty($gateway['ncm_eway_enabled']) ) {



            $eway_mode = 'live';



            if( isset($gateway['ncm_eway_sandbox']) && $gateway['ncm_eway_sandbox'] == '1' ) {



                $eway_mode = 'test';



                $api_key = ( isset($gateway['ncm_eway_sandbox_api_key']) && !empty($gateway['ncm_eway_sandbox_api_key']) ) ? $gateway['ncm_eway_sandbox_api_key'] : '';



                $api_pass = ( isset($gateway['ncm_eway_sandbox_password']) && !empty($gateway['ncm_eway_sandbox_password']) ) ? $gateway['ncm_eway_sandbox_password'] : '';



            } else {



                $eway_mode = 'live';



                $api_key = ( isset($gateway['ncm_eway_api_key']) && !empty($gateway['ncm_eway_api_key']) ) ? $gateway['ncm_eway_api_key'] : '';



                $api_pass = ( isset($gateway['ncm_eway_password']) && !empty($gateway['ncm_eway_password']) ) ? $gateway['ncm_eway_password'] : '';



            }







            if( !empty( $api_key ) && !empty( $api_pass ) ) { 







                $order_id = $data['order_id'];



                $ncm_checkout->ncm_store_geteway_mode( $order_id, $gateway_name, $eway_mode);



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



                $card_holder_name = $request_data['ncm_eway_card_holder_name'];



                $card_no = $request_data['ncm_eway_credit_card'];



                $card_expiry = $request_data['ncm_eway_exp'];



                $card_expiry_arr = explode("/", $card_expiry);



                $card_exp_month = isset($card_expiry_arr['0']) ? $card_expiry_arr['0'] : 0;



                $card_exp_year = isset($card_expiry_arr['1']) ? '20'.$card_expiry_arr['1'] : 0;



                $card_cvv = $request_data['ncm_eway_cvc'];



                $currency = $ncm_settings->ncm_get_currency();







                include_once( NCM_LIB_EWAY_DIR."RapidAPI.php" );







                // Create DirectPayment Request Object



                $request = new eWAY\CreateDirectPaymentRequest();







                // Populate values for Customer Object



                // Note: TokenCustomerID is required when update an exsiting TokenCustomer



                if (!empty($data['txtTokenCustomerID'])) {



                    $request->Customer->TokenCustomerID = $data['txtTokenCustomerID'];



                }







                $request->Customer->FirstName = $user_firstname;



                $request->Customer->LastName = $user_lastname;



                $request->Customer->Country = $country;



                $request->Customer->Email = $user_email;



                $request->Customer->Phone = $phone;
                

                $request->Customer->Comment = $comment;







                $request->Customer->CardDetails->Name = $card_holder_name;



                $request->Customer->CardDetails->Number = $card_no;



                $request->Customer->CardDetails->ExpiryMonth = $card_exp_month;



                $request->Customer->CardDetails->ExpiryYear = $card_exp_year;



                $request->Customer->CardDetails->CVN = $card_cvv;







                // product info



                if( isset($data['products']) && !empty($data['products']) ) {



                    $pro_c = 0;



                    foreach($data['products'] as $product) {



                        $item = new eWAY\LineItem();



                        $item->SKU = $product['product_id'];



                        $item->Description = $product['tour_name'];



                        $request->Items->LineItem[$pro_c] = $item;



                        $pro_c++;



                    }



                }







                // Populate values for Payment Object



                $request->Payment->TotalAmount = $data['subtotal'] * 100;;



                $request->Payment->InvoiceNumber = $data['order_id'];



                $request->Payment->InvoiceDescription = __('Order ', NCM_txt_domain).$data['order_id'];



                $request->Payment->InvoiceReference = $data['order_id'];



                $request->Payment->CurrencyCode = $currency; 







                $request->Method = 'ProcessPayment';



                $request->TransactionType = 'Purchase';







                // Call RapidAPI



                $eway_params = array();



                if ($eway_mode == 'test') {



                    $eway_params['sandbox'] = true;



                }



                $service = new eWAY\RapidAPI( $api_key, $api_pass, $eway_params );



                $result = $service->DirectPayment($request);







                // $ncm->ncm_write_log("ncm logs => ".json_encode($result));







                // Check if any error returns



                if (isset($result->Errors)) {







                    // Get Error Messages from Error Code.



                    $ErrorArray = explode(",", $result->Errors);



                    $lblError = "";



                    foreach ( $ErrorArray as $error ) {



                        $error = $service->getMessage($error);



                        $lblError .= $error . "\n";



                    }







                    $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'ncm-failed' );



                    $status  =  "failed";



                    $msg = $lblError;



                    $content = $lblError;







                } else if (isset($result->TransactionStatus) && $result->TransactionStatus && (is_bool($result->TransactionStatus) || $result->TransactionStatus != "false")) {



                    $authcode = isset($result->AuthorisationCode) ? $result->AuthorisationCode : '';



                    $txn_id = isset($result->TransactionID) ? $result->TransactionID : '';





                    if( $data['ncm_is_live'] ) {

                        $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'completed' );

                    } else {

                        $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'non-confirmed' );

                    }



                    update_post_meta( $order_id, 'ncm_eway_authorisation_code', $authcode);



                    update_post_meta( $order_id, 'ncm_eway_card_expiry', $card_expiry);



                    update_post_meta( $order_id, 'ncm_transaction', $txn_id);





                    $status  =  "success";



                    $msg = __('Payment successfull.', NCM_txt_domain);



                    $content = __('Payment successfull.', NCM_txt_domain);



                }







                //print_r($result);



                



                update_post_meta( $order_id, 'ncm_gateway_response', json_encode($result));



                return array( "status" => $status, "msg" => $msg, "content" => $content );



            } else {



                $status = 'failed';



                $msg = __("sorry! something went wrong.", NCM_txt_domain);



                $content = __("eway key or password is blank.", NCM_txt_domain);



                return array( "status" => $status, "msg" => $msg, "content" => $content );



            }



        } else {



            $status = 'failed';



            $msg = __("Eway is not enabled.", NCM_txt_domain);



            $content = __("eway is not enabled.", NCM_txt_domain);



            return array( "status" => $status, "msg" => $msg, "content" => $content );



        }



        return $response;



    }







    function ncm_refund_eway( $order_id, $gateway_name, $gateway ) {



        global $ncm_settings, $ncm_checkout, $ncm, $ncm_payment_gateways, $ncm_order;



        if( isset($gateway['ncm_eway_enabled']) && !empty($gateway['ncm_eway_enabled']) ) {



            $eway_mode = 'live';



            if( $ncm_checkout->ncm_get_geteway_mode( $order_id, $gateway_name ) == 'test' ) {



                $eway_mode = 'test';



                $api_key = ( isset($gateway['ncm_eway_sandbox_api_key']) && !empty($gateway['ncm_eway_sandbox_api_key']) ) ? $gateway['ncm_eway_sandbox_api_key'] : '';



                $api_pass = ( isset($gateway['ncm_eway_sandbox_password']) && !empty($gateway['ncm_eway_sandbox_password']) ) ? $gateway['ncm_eway_sandbox_password'] : '';



            } else {



                $eway_mode = 'live';



                $api_key = ( isset($gateway['ncm_eway_api_key']) && !empty($gateway['ncm_eway_api_key']) ) ? $gateway['ncm_eway_api_key'] : '';



                $api_pass = ( isset($gateway['ncm_eway_password']) && !empty($gateway['ncm_eway_password']) ) ? $gateway['ncm_eway_password'] : '';



            }







            if( !empty( $api_key ) && !empty( $api_pass ) ) {







                $all_country = $ncm_settings->ncm_country();



                $user_data = $ncm_order->ncm_get_order_booking_data( $order_id );



                $user_firstname = isset($user_data['First Name']) ? $user_data['First Name'] : '';



                $user_lastname = isset($user_data['Last Name']) ? $user_data['Last Name'] : '';



                $user_email = isset($user_data['Email']) ? $user_data['Email'] : '';



                $phone = isset($user_data['Phone/Mobile']) ? $user_data['Phone/Mobile'] : '';

                
                $country_name = isset($user_data['Country']) ? $user_data['Country'] : '';



                $country = ( !empty( $country_name ) ) ? array_search( $country_name, $all_country ) : '';


                $comment = isset($user_data['Comment']) ? $user_data['Comment'] : '';


                $currency = $ncm_settings->ncm_get_currency();







                include_once( NCM_LIB_EWAY_DIR."RapidAPI.php" );







                // Create DirectPayment Request Object



                $request = new eWAY\CreateRefundRequest();







                $request->Customer->FirstName = $user_firstname;



                $request->Customer->LastName = $user_lastname;



                $request->Customer->Country = $country;



                $request->Customer->Email = $user_email;



                $request->Customer->Phone = $phone;
                

                $request->Customer->Comment = $comment;







                $card_expiry = get_post_meta( $order_id, 'ncm_eway_card_expiry', true );



                $card_exp_arr = explode("/", $card_expiry);



                $card_exp_month = isset($card_exp_arr['0']) ? $card_exp_arr['0'] : 0;



                $card_exp_year = isset($card_exp_arr['1']) ? '20'.$card_exp_arr['1'] : 0;



                $request->Customer->CardDetails->ExpiryMonth = $card_exp_month;



                $request->Customer->CardDetails->ExpiryYear = $card_exp_year;







                // Populate values for Payment Object



                $transaction_id = get_post_meta( $order_id, 'ncm_transaction', true );



                $subtotal =  get_post_meta( $order_id, 'ncm_subtotal', true );



                $request->Refund->TransactionID = $transaction_id;



                $request->Refund->TotalAmount = $subtotal * 100;



                $request->Refund->InvoiceNumber = $order_id;



                $request->Refund->InvoiceReference = $order_id;



                $request->Refund->CurrencyCode = $currency; 







                // Call RapidAPI



                $eway_params = array();



                if ($eway_mode == 'test') {



                    $eway_params['sandbox'] = true;



                }



                $service = new eWAY\RapidAPI( $api_key, $api_pass, $eway_params );



                $refund = $service->Refund($request);







                // Check if any error returns



                if (isset($refund->Errors)) {







                    // Get Error Messages from Error Code.



                    $ErrorArray = explode(",", $refund->Errors);



                    $lblError = "";



                    foreach ( $ErrorArray as $error ) {



                        $error = $service->getMessage($error);



                        $lblError .= $error . "\n";



                    }







                    $status  =  "failed";



                    $msg = $lblError;



                    $content = $lblError;







                } else if (isset($refund->TransactionStatus) && $refund->TransactionStatus && (is_bool($refund->TransactionStatus) || $refund->TransactionStatus != "false")) {







                    $authcode = isset($refund->AuthorisationCode) ? $refund->AuthorisationCode : '';



                    $txn_id = isset($refund->TransactionID) ? $refund->TransactionID : '';







                    update_post_meta( $order_id, 'ncm_eway_refund_id', $txn_id);







                    $status  =  "success";



                    $msg = '';



                    $content = '';



                }







                //print_r($result);



                



                update_post_meta( $order_id, 'ncm_refund_gateway_response', json_encode($refund));



                return array( "status" => $status, "msg" => $msg, "content" => $content );



            } else {



                $status = 'failed';



                $msg = __("sorry! something went wrong.", NCM_txt_domain);



                $content = __("eway key or password is blank.", NCM_txt_domain);



                return array( "status" => $status, "msg" => $msg, "content" => $content );



            }



        } else {



            $status = 'failed';



            $msg = __("Eway is not enabled.", NCM_txt_domain);



            $content = __("eway is not enabled.", NCM_txt_domain);



            return array( "status" => $status, "msg" => $msg, "content" => $content );



        }



    }



 



}







global $ncm_eway_gateways;



$ncm_eway_gateways = new NCM_Eway_Gateways();







}



?>