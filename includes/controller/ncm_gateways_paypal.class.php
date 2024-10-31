<?php

/*
* This class manage Paypal payment gateways
*/

if( !class_exists ( 'NCM_Paypal_Gateways' ) ) {







class NCM_Paypal_Gateways {



    



    function __construct(){







        add_action( "init", array( $this, "ncm_paypal_response" ), 10 );







    }







    function ncm_paypal_response() {



        global $wpdb, $ncm, $ncm_payment_gateways, $ncm_order, $ncm_narnoo_helper;



        if( isset( $_REQUEST['ncm_gateway'] ) && $_REQUEST['ncm_gateway'] == 'ncm_paypal' ) {



            if( isset( $_REQUEST['custom'] ) && $_REQUEST['custom'] > '' ) {



                // $ncm->ncm_write_log(json_encode($_REQUEST)); 



                $order_id = $_REQUEST['custom'];



                $txn_id = isset($_REQUEST['txn_id']) ? $_REQUEST['txn_id'] : '';



                $payment_status = 'Completed';

                //$payment_status = isset($_REQUEST['payment_status']) ? $_REQUEST['payment_status'] : ''; 



                update_post_meta( $order_id, 'ncm_gateway_response_'.$payment_status, json_encode($_REQUEST));



                if( $payment_status == 'Completed' && !empty($txn_id) ) {



                    if( get_post_meta( $order_id, 'ncm_paypal_narnoo_is_live', true ) ) {

                        $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'completed' );

                    } else {

                        $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'non-confirmed' );

                    }



                    update_post_meta( $order_id, 'ncm_transaction', $txn_id);



                    /** reservation in narnoo Using API start **/

                    $ncm_order_data = get_post_meta( $order_id, 'ncm_paypal_custom_narnoo_data', true );

                    

                    // $ncm->ncm_write_log(json_encode($ncm_order_data)); 



                    $result = $ncm_narnoo_helper->ncm_reservation( $ncm_order_data );



                    // $ncm->ncm_write_log($result); 



                    $response = json_decode( $result, true );



                    if( $response['success'] ){

                        $response_data = isset($response['data']['request']) ? $response['data']['request'] : $response;



                        if( isset($response['uniqueId']) and !empty($response['uniqueId']) ) {

                        update_post_meta($order_id, 'ncm_narnoo_order_id', $response['uniqueId']); 

                        }



                        if( isset($response_data['data']['booking']) && !empty($response_data['data']['booking']) ) {

                            foreach ($response_data['data']['booking'] as $product_data) {



                                if( ( isset($product_data['reservationCode']) && !empty($product_data['reservationCode']) ) || ( isset($product_data['reservationProvider']) && !empty($product_data['reservationProvider']) ) ) {



                                    $reservation_code = $product_data['reservationCode'];

                                    $reservation_provider = $product_data['reservationProvider'];

                                    $booking_code = $product_data['productBookingCode'];

                                    $product_id = $product_data['productId'];



                                    $wpdb->update( 

                                        $table_name = $ncm->tbl_order_item, 

                                        array( 

                                            'reservation_code' => $reservation_code, 

                                            'reservation_provider' => $reservation_provider,

                                        ), 

                                        array( 

                                            'order_id' => $order_id, 

                                            'product_id' => $product_id,

                                            'booking_code' => $booking_code,

                                        ), 

                                        array( '%s', '%s' ), 

                                        array( '%d', '%d', '%d', ) 

                                    );



                                }

                            }

                        }

                    }

                    /** reservation in narnoo Using API start **/



                    // send email notification.

                    do_action( "ncm_email_notification", 'new_order', $order_id );



                } else if( $payment_status == 'Pending' ) {



                    $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'pending' );



                } else if( $payment_status == 'Denied' ) {



                    $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'failed' );



                } else if( $payment_status == 'Refunded' ) {



                    update_post_meta( $order_id, 'ncm_paypal_refund_id', json_encode($txn_id));



                }



            } else {



                $ncm->ncm_write_log( 'Paypal Response ==> '. json_encode($_REQUEST), 'ncm_payment_log.txt' );



            }



        }



    }







    function ncm_payment_paypal_standard( $data, $gateway_name, $gateway ) {



        global $ncm_settings, $ncm_checkout, $ncm_payment_gateways;



        if( isset($gateway['ncm_paypal_email']) && !empty($gateway['ncm_paypal_email']) ) {



            $paypal_mode = 'live';



            if( isset($gateway['ncm_paypal_testmode']) && $gateway['ncm_paypal_testmode'] == '1' ) {



                $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';



                $paypal_mode = 'test';



            } else {



                $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';



                $paypal_mode = 'live';



            }



            $user_data = isset( $data['contact'] ) ? $data['contact'] : '';



            $user_info = array_combine(array_map(function($str){return str_replace(" ","_",strtolower($str));},array_keys($user_data)),array_values($user_data));



            $user_email = ( isset($user_info['email']) && !empty($user_info['email']) ) ? $user_info['email'] : ( (isset($user_info['e_mail']) && !empty($user_info['e_mail']) ) ? $user_info['e_mail'] : '' );



            $user_firstname = ( isset($user_info['first_name']) && !empty($user_info['first_name']) ) ? $user_info['first_name'] : ( (isset($user_info['firstname']) && !empty($user_info['firstname']) ) ? $user_info['firstname'] : '' );



            $user_lastname = ( isset($user_info['last_name']) && !empty($user_info['last_name']) ) ? $user_info['last_name'] : ( (isset($user_info['lastname']) && !empty($user_info['lastname']) ) ? $user_info['lastname'] : '' );





            $order_id = $data['order_id'];



            $business_email = $gateway['ncm_paypal_email'];



            $ncm_checkout->ncm_store_geteway_mode( $order_id, $gateway_name, $paypal_mode);



            update_post_meta( $order_id, 'ncm_paypal_narnoo_is_live', $data['ncm_is_live']);



            $custom_data = array();

            $custom_data['contact'] = $data['contact'];

            $custom_data['payment'] = $data['payment'];

            $custom_data['products'] = $data['products'];

            $custom_data['passenger_info'] = $data['passenger_info'];

            update_post_meta( $order_id, 'ncm_paypal_custom_narnoo_data', $custom_data);



            $form_id = 'ncm_paypal_'.$order_id;



            $ncm_site_url = $ncm_payment_gateways->ncm_get_checkout_setting_value( 'ncm_home' );



            $ncm_return_url = $ncm_payment_gateways->ncm_get_thank_you_page_link();



            $ncm_cancel_return_url = $ncm_payment_gateways->ncm_get_checkout_page_link();



            $ncm_notify_url = $ncm_site_url.'?ncm_gateway=ncm_paypal';











            $content = '';



            $content.= '<form id="'.$form_id.'" name="'.$form_id.'" action="'.$paypal_url.'" method="post" enctype="multipart/form-data">';



            $content.= '<input type="hidden" name="cmd" value="_cart" />';



            $content.= '<input type="hidden" name="upload" value="1" />';



            $content.= '<input type="hidden" name="rm" value="1" />';



            $content.= '<input type="hidden" name="bn" value="ncm_cart" />';



            $content.= '<input type="hidden" name="no_note" value="1" />';



            $content.= '<input type="hidden" name="charset" value="utf-8" />';



            $content.= '<input type="hidden" name="paymentaction" value="sale" />';



            $content.= '<input type="hidden" name="invoice" value="ncm_'.$order_id.'" />';



            $content.= '<input type="hidden" name="no_shipping" value="1" />';



            $content.= '<input type="hidden" name="custom" value="'.$order_id.'" />';



            $content.= '<input type="hidden" name="return" value="'.$ncm_return_url.'" />';



            $content.= '<input type="hidden" name="cancel_return" value="'.$ncm_cancel_return_url.'" />';



            $content.= '<input type="hidden" name="notify_url" value="'.$ncm_notify_url.'" />';



            $content.= '<input type="hidden" name="business" value="'.$business_email.'" />';



            $content.= '<input type="hidden" name="currency_code" value="'.$ncm_settings->ncm_get_currency().'" />';







            // product info



            if( isset($data['products']) && !empty($data['products']) ) {



                $pro_c = 1;



                foreach($data['products'] as $product) {



                    $content.= '<input type="hidden" name="item_name_'.$pro_c.'" value="'.$product['tour_name'].'" />';



                    $content.= '<input type="hidden" name="item_number_'.$pro_c.'" value="'.$product['product_id'].'"/>';



                    $content.= '<input type="hidden" name="amount_'.$pro_c.'" value="'.$product['subtotal'].'" />';



                    $content.= '<input type="hidden" name="quantity_'.$pro_c.'" value="1" />';



                    $pro_c++;



                }



            }







            // user info



            $contact = isset($data['contact']) ? $data['contact'] : array();



            $content.= '<input type="hidden" name="first_name" value="'.$user_firstname.'" />';



            $content.= '<input type="hidden" name="last_name" value="'.$user_lastname.'" />';



            $content.= '<input type="hidden" name="email" value="'.$user_email.'" />';



            $content.= '<input type="hidden" name="submit1" />';



            $content.= '</form>';



            $content.= '<script type="text/javascript"> setTimeout( function() { jQuery("#'.$form_id.'").submit(); }, 3000); </script>';







            $response = array(



                "status" => "submit_form",



                "msg" => __("Paypal Form", NCM_txt_domain),



                "content" => $content



            );



        } else {



            $response = array( 



                "status" => "failed", 



                "msg" => __('sorry! something went wrong.', NCM_txt_domain),



                "content" => __('paypal standard email address not inserted.', NCM_txt_domain)



            );



        }



        return $response;



    }







    function ncm_refund_paypal_standard( $order_id, $gateway_name, $gateway ) {



        global $ncm_settings, $ncm_checkout, $ncm_payment_gateways, $ncm;



        $response = array( 



                "status" => "failed", 



                "msg" => __('sorry! something went wrong.', NCM_txt_domain),



                "content" => __('sorry! something went wrong.', NCM_txt_domain)



            );



        if( isset($gateway['ncm_paypal_email']) && !empty($gateway['ncm_paypal_email']) ) {



            $paypal_mode = 'live';



            if( $ncm_checkout->ncm_get_geteway_mode( $order_id, $gateway_name ) == 'test' ) {



                $paypal_url = 'https://api-3t.sandbox.paypal.com/nvp';



                $paypal_mode = 'test';



            } else {



                $paypal_url = 'https://api-3t.paypal.com/nvp';



                $paypal_mode = 'live';



            }







            $paypalapiusername = isset($gateway['ncm_paypal_api_username']) ? $gateway['ncm_paypal_api_username'] : '';



            $paypalapipassword = isset($gateway['ncm_paypal_api_password']) ? $gateway['ncm_paypal_api_password'] : '';



            $paypalapisignature = isset($gateway['ncm_paypal_api_signature']) ? $gateway['ncm_paypal_api_signature'] : '';



    



            if(!empty($paypalapiusername) && !empty($paypalapipassword) && !empty($paypalapisignature)) {







                $method = 'RefundTransaction';



                $version = 94;



                $transactionid = get_post_meta( $order_id, 'ncm_transaction', true );



                



                if( !empty($transactionid) ) {







                    $requestParams = array(



                                'METHOD' => $method,



                                'VERSION' => $version,



                                'USER' => $paypalapiusername,



                                'PWD' => $paypalapipassword,



                                'SIGNATURE' => $paypalapisignature,



                                'TRANSACTIONID' => $transactionid,



                                'REFUNDTYPE' => 'Full'



                            );



                    $args = array(



                                'method'      => 'POST',



                                'body'        => http_build_query($requestParams),



                                'timeout'     => 70,



                            );



                    $refund = wp_remote_post( $paypal_url, $args );



                    if(isset($refund['reponse']) && $refund['reponse'] == 200 && !empty($refund['body']) ) {



                        $ncm->ncm_write_log('paypal reponse body => '.$refund['body']);



                        $parameter = explode("&", $refund['body']);



                        $response = array();



                        foreach( $parameter as $elem ) {



                            $elem_arr = explode( "=", $elem );



                            $elem_key = isset($elem_arr[0]) ? $elem_arr[0] : '';



                            $elem_val = isset($elem_arr[1]) ? $elem_arr[1] : '';



                            if( !empty($elem_key) && !empty($elem_val) ) {



                                $response[$elem_key] = $elem_val;



                            }



                        }







                        if( isset( $response['ACK'] ) && $response['ACK'] == $response['ACK'] && !empty( $response['REFUNDTRANSACTIONID'] ) ) {



                            update_post_meta( $order_id, 'kncm_paypal_refund_id', $response['REFUNDTRANSACTIONID']);



                            update_post_meta( $order_id, 'kncm_refund_gateway_response', $refund['body']);







                            $reponse = array( 



                                "status" => "success", 



                                "msg" => __("You can't refund because order is not completed.", NCM_txt_domain),



                                "content" => __("You can't refund because order is not completed.", NCM_txt_domain)



                            );







                        } else {



                            $msg = isset($response['L_LONGMESSAGE0']) ? $response['L_LONGMESSAGE0'] : __('sorry! something went wrong.', NCM_txt_domain);



                            $reponse = array( 



                                "status" => "failed", 



                                "msg" => $msg,



                                "content" => $msg



                            );



                        }



                    }



                } else {



                    $reponse = array( 



                        "status" => "failed", 



                        "msg" => __("You can't refund because order is not completed.", NCM_txt_domain),



                        "content" => __("You can't refund because order is not completed.", NCM_txt_domain)



                    );



                }



            } else {



                $response = array( 



                    "status" => "failed", 



                    "msg" => __('Please enter PayPal API credentials in checkout > paypal section under setting page then, you can able to refund paypal payments.', NCM_txt_domain),



                    "content" => __('Please enter PayPal API credentials in checkout > paypal section under setting page then, you can able to refund paypal payments', NCM_txt_domain)



                );



            }



        } else {



            $response = array( 



                "status" => "failed", 



                "msg" => __('sorry! something went wrong.', NCM_txt_domain),



                "content" => __('paypal standard email address not inserted.', NCM_txt_domain)



            );



        }



        return $response;



    }







}







global $ncm_paypal_gateways;



$ncm_paypal_gateways = new NCM_Paypal_Gateways();







}



?>