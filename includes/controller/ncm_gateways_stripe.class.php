<?php

/*
* This class manage Stripe payment gateways
*/

if( !class_exists ( 'NCM_Stripe_Gateways' ) ) {







class NCM_Stripe_Gateways {



    



    function __construct(){



        // for stripe checkout payment



        add_action( "init", array( $this, "ncm_stripe_response" ), 10 );



        add_action( "wp_ajax_ncm_stripe_checkout_payment", array($this, 'ncm_stripe_checkout_payment_func') );



        add_action( "wp_ajax_nopriv_ncm_stripe_checkout_payment", array($this, 'ncm_stripe_checkout_payment_func') );



    }



    function ncm_stripe_response() {



        global $wpdb, $ncm, $ncm_payment_gateways;



        if( isset( $_REQUEST['ncm_stripe'] ) && $_REQUEST['ncm_stripe'] != '' ) {



            $ncm->ncm_write_log( 'stripe Response ==> '. json_encode($_REQUEST), 'ncm_payment_log.txt' );



        }



    }



    // For Stripe Checkout



    function ncm_payment_stripe_checkout( $data, $gateway_name, $gateway ) {



        global $ncm_settings, $ncm_checkout;



        if( isset($gateway['ncm_stripe_checkout']) && !empty($gateway['ncm_stripe_checkout']) ) {



            $ajax_data = array();

            if( isset($data['all']['stripeToken']) && !empty($data['all']['stripeToken']) ) {

                return $this->ncm_stripe_checkout_payment_func( $data, $gateway_name, $gateway );

            } else {

                $ajax_data = $data['all'];

            }



            $stripe_mode = 'live';



            if( isset($gateway['ncm_stripe_testmode']) && $gateway['ncm_stripe_testmode'] == '1' ) {



                $stripe_mode = 'test';



                $primary_key = ( isset($gateway['ncm_stripe_test_publishable_key']) && !empty($gateway['ncm_stripe_test_publishable_key']) ) ? $gateway['ncm_stripe_test_publishable_key'] : '';



            } else {



                $stripe_mode = 'live';



                $primary_key = ( isset($gateway['ncm_stripe_publishable_key']) && !empty($gateway['ncm_stripe_publishable_key']) ) ? $gateway['ncm_stripe_publishable_key'] : '';



            }



            if( !empty( $primary_key ) ) { 



                $order_id = $data['order_id'];



                $ncm_checkout->ncm_store_geteway_mode( $order_id, $gateway_name, $stripe_mode);   



                $local = ( isset($gateway['ncm_stripe_checkout_locale']) && !empty($gateway['ncm_stripe_checkout_locale']) ) ? $gateway['ncm_stripe_checkout_locale'] : 'auto';



                $user_data = isset( $data['contact'] ) ? $data['contact'] : '';



                $user_info = array_combine(array_map(function($str){return str_replace(" ","_",strtolower($str));},array_keys($user_data)),array_values($user_data));



                $user_email = ( isset($user_info['email']) && !empty($user_info['email']) ) ? $user_info['email'] : ( (isset($user_info['e_mail']) && !empty($user_info['e_mail']) ) ? $user_info['e_mail'] : '' );



                $user_firstname = ( isset($user_info['first_name']) && !empty($user_info['first_name']) ) ? $user_info['first_name'] : ( (isset($user_info['firstname']) && !empty($user_info['firstname']) ) ? $user_info['firstname'] : '' );



                $user_lastname = ( isset($user_info['last_name']) && !empty($user_info['last_name']) ) ? $user_info['last_name'] : ( (isset($user_info['lastname']) && !empty($user_info['lastname']) ) ? $user_info['lastname'] : '' );



                

                $products = $data['products'];



                $product_name = implode(', ', array_column($products, 'tour_name'));;



                $total_amount = $data['subtotal'] * 100;



                $ajax_data['action'] = 'ncm_validate_checkout';

                $ajax_data['ncm_amount'] = $total_amount;

                $ajax_data['ncm_desc'] = $product_name;

                $ajax_data['ncm_email'] = $user_email;

                $ajax_data['ncm_firstname'] = $user_firstname;

                $ajax_data['ncm_lastname'] = $user_lastname;

                $ajax_data['ncm_order_id'] = $order_id;

                $ajax_data['ncm_is_live'] = $data['ncm_is_live'];

                // $ajax_data['stripeToken'] = token.id;



                $content = '';



                $content.= '<button id="ncm_stripe_checkout" style="display:none;">Purchase</button>';


                $content.= "<script type='text/javascript'>var handler = StripeCheckout.configure({  key: '".$primary_key."',  image: 'https://stripe.com/img/documentation/checkout/marketplace.png',  locale: '".$local."',  email: '".$user_email."',  token: function(token) {    jQuery('#ncm_container_loader').show();  var ajax_data = ".json_encode($ajax_data)."; ajax_data.stripeToken=token.id; console.log(ajax_data);  jQuery.ajax({    type: 'POST',    url: ajaxurl,   dataType: 'json',    data: ajax_data,    error: function(e) {    console.log(e);    },    success: function(result){   if( result.status == 'submit_form' || result.status == 'ncm_ajax_script' ) {   jQuery('body').append( result.content );    } else if( result.status == 'success' ) {   console.log( result.content );    alert( result.msg );   } else {    console.log( result.content );   alert( result.msg );    }    setTimeout( function() { jQuery('#ncm_container_loader').hide(); }, 4000);    }    });    }  });  document.getElementById('ncm_stripe_checkout').addEventListener('click', function(e) {  handler.open({  name:'".$user_email."',  description:'".$product_name."',  amount:".$total_amount." });  e.preventDefault();  });  window.addEventListener('popstate', function() {  handler.close();  });  setTimeout( function() { jQuery('#ncm_stripe_checkout').click(); }, 3000);  </script>";

                

                $response = array( 



                    "status" => "ncm_ajax_script", 



                    "msg" => __('stripe ajax script', NCM_txt_domain),



                    "content" => $content



                );



            } else {



                $response = array( 



                    "status" => "failed", 



                    "msg" => __('sorry! something went wrong.', NCM_txt_domain),



                    "content" => __('stripe publishable key is empty.', NCM_txt_domain)



                );



            }



        }



        return $response;



    }







    // For Stripe Checkout



    function ncm_stripe_checkout_payment_func( $data, $gateway_name, $gateway ) {



        global $ncm_payment_gateways, $ncm_settings;







        $status = "failed";



        $msg = __('sorry! something went wrong.', NCM_txt_domain);



        $content = __('sorry! something went wrong.', NCM_txt_domain);







        $gateway = $ncm_payment_gateways->ncm_get_active_gateways_data( 'stripe' );



        $secret_key = '';



        if( isset($gateway['ncm_stripe_testmode']) && $gateway['ncm_stripe_testmode'] == '1' ) {



            $secret_key = ( isset($gateway['ncm_stripe_test_secret_key']) && !empty($gateway['ncm_stripe_test_secret_key']) ) ? $gateway['ncm_stripe_test_secret_key'] : '';



        } else {



            $secret_key = ( isset($gateway['ncm_stripe_secret_key']) && !empty($gateway['ncm_stripe_secret_key']) ) ? $gateway['ncm_stripe_secret_key'] : '';



        }







        include_once( NCM_LIB_STIRPE_DIR."init.php" );







        \Stripe\Stripe::setApiKey( $secret_key );







        $token =  ( isset($data['all']['stripeToken']) && !empty($data['all']['stripeToken']) ) ? $data['all']['stripeToken'] : '';



        $order_id = ( isset($data['all']['ncm_order_id']) && !empty($data['all']['ncm_order_id']) ) ? $data['all']['ncm_order_id'] : '';



        $amount = ( isset($data['all']['ncm_amount']) && !empty($data['all']['ncm_amount']) ) ? $data['all']['ncm_amount'] : '';



        $description = ( isset($data['all']['ncm_desc']) && !empty($data['all']['ncm_desc']) ) ? $data['all']['ncm_desc'] : '';



        $user_email = ( isset($data['all']['ncm_email']) && !empty($data['all']['ncm_email']) ) ? $data['all']['ncm_email'] : '';



        $user_firstname = ( isset($data['all']['ncm_firstname']) && !empty($data['all']['ncm_firstname']) ) ? $data['all']['ncm_firstname'] : '';



        $user_lastname = ( isset($data['all']['ncm_lastname']) && !empty($data['all']['ncm_lastname']) ) ? $data['all']['ncm_lastname'] : '';







        $user_data = isset( $data['contact'] ) ? $data['contact'] : '';



        $user_info = array_combine(array_map(function($str){return str_replace(" ","_",strtolower($str));},array_keys($user_data)),array_values($user_data));



        $user_email = ( isset($user_info['email']) && !empty($user_info['email']) ) ? $user_info['email'] : ( (isset($user_info['e_mail']) && !empty($user_info['e_mail']) ) ? $user_info['e_mail'] : '' );



        $user_firstname = ( isset($user_info['first_name']) && !empty($user_info['first_name']) ) ? $user_info['first_name'] : ( (isset($user_info['firstname']) && !empty($user_info['firstname']) ) ? $user_info['firstname'] : '' );



        $user_lastname = ( isset($user_info['last_name']) && !empty($user_info['last_name']) ) ? $user_info['last_name'] : ( (isset($user_info['lastname']) && !empty($user_info['lastname']) ) ? $user_info['lastname'] : '' );

        



        try 



        {







            $charge = \Stripe\Charge::create(



                array(



                    "amount"        =>  $amount,



                    "currency"      =>  $ncm_settings->ncm_get_currency(),



                    "source"        =>  $token,



                    "description"   =>  $description,



                    "receipt_email" =>  $user_email,



                    "metadata"      => array(



                        "ncm_order_id" => $order_id,



                        "email"   => $user_email,



                        "first_name"  => $user_firstname,



                        "last_name"  => $user_lastname,



                    ),



                )



            );







            if( $charge->status == 'succeeded' ) {







                $charge_id = $charge->id; 



                $txn_id = $charge->balance_transaction;



                if( isset($data['all']['ncm_is_live']) && $data['all']['ncm_is_live'] ) {



                    $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'completed' );



                } else {



                    $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'non-confirmed' );

                    

                }



                update_post_meta( $order_id, 'ncm_stripe_charge_id', $charge_id);



                update_post_meta( $order_id, 'ncm_transaction', $txn_id);







                $ncm_stripe_response = $charge;



                $status  =  "success";



                $msg = __('Payment successfull.', NCM_txt_domain);



                $content = $msg;









            } else {







                $ncm_payment_gateways->ncm_update_orderstatus( $order_id, $charge->status );



                $ncm_stripe_response = $charge;



                $status  =  "success";



                $msg = __('Payment successfull.', NCM_txt_domain);



                $content = $msg;





            }



        }catch(\Stripe\CardError $e) { 



            $ncm_stripe_response = $e;



            $status  =  "failed";



            $msg = $error;



            $content = $e->getMessage();



        } catch (\Stripe\InvalidRequestError $e) {



             $ncm_stripe_response = $e;



            $status  =  "failed";



            $msg = $error;



            $content = $e->getMessage();



        } catch (\Stripe\AuthenticationError $e) {



            $ncm_stripe_response = $e;



            $status  =  "failed";



            $msg = $error;



            $content = $e->getMessage();



        } catch (\Stripe\ApiConnectionError $e) {



             $ncm_stripe_response = $e;



            $status  =  "failed";



            $msg = $error;



            $content = $e->getMessage();



        } catch (\Stripe\Error $e) {



             $ncm_stripe_response = $e;



            $status  =  "failed";



            $msg = $error;



            $content = $e->getMessage();



        } catch (Exception $e) {



            if ($e->getMessage() == "zip_check_invalid") {



                $error = "declined1";



            } else if ($e->getMessage() == "address_check_invalid") {



                $error = "decline2d";



            } else if ($e->getMessage() == "cvc_check_invalid") {



                $error = "declined3";



            } else {



                $error = $e->getMessage();



            }



            $ncm_stripe_response = $e;



            $status  =  "failed";



            $msg = $error;



            $content = $e->getMessage();



        }



    



        update_post_meta( $order_id, 'ncm_gateway_response', json_encode($ncm_stripe_response));



        return array( "status" => $status, "msg" => $msg, "content" => $content );



    }







    // For Stripe



    function ncm_payment_stripe( $data, $gateway_name, $gateway ) {



        global $ncm_settings, $ncm_checkout, $ncm, $ncm_payment_gateways;



        if( isset($gateway['ncm_stripe_enabled']) && !empty($gateway['ncm_stripe_enabled']) ) {



            $stripe_mode = 'live';



            if( isset($gateway['ncm_stripe_testmode']) && $gateway['ncm_stripe_testmode'] == '1' ) {



                $stripe_mode = 'test';



                $secret_key = ( isset($gateway['ncm_stripe_test_secret_key']) && !empty($gateway['ncm_stripe_test_secret_key']) ) ? $gateway['ncm_stripe_test_secret_key'] : '';



            } else {



                $stripe_mode = 'live';



                $secret_key = ( isset($gateway['ncm_stripe_secret_key']) && !empty($gateway['ncm_stripe_secret_key']) ) ? $gateway['ncm_stripe_secret_key'] : '';



            }







            if( !empty( $secret_key ) ) { 







                $order_id = $data['order_id'];



                $ncm_checkout->ncm_store_geteway_mode( $order_id, $gateway_name, $stripe_mode);



                



                $user_info = isset( $data['contact'] ) ? $data['contact'] : '';



                $products = $data['products'];







                $amount = $data['subtotal'] * 100;



                $description = implode(', ', array_column($products, 'tour_name'));;



                $user_email = $user_info['email'];



                $user_firstname = $user_info['firstName'];



                $user_lastname = $user_info['lastName'];







                $request_data = $data['all'];



                $card_no = $request_data['ncm_stripe_credit_card'];



                $card_expiry = $request_data['ncm_stripe_exp'];



                $card_expiry_arr = explode("/", $card_expiry);



                $card_exp_month = isset($card_expiry_arr['0']) ? $card_expiry_arr['0'] : 0;



                $card_exp_year = isset($card_expiry_arr['1']) ? '20'.$card_expiry_arr['1'] : 0;



                $card_cvv = $request_data['ncm_stripe_cvc'];







                include_once( NCM_LIB_STIRPE_DIR."init.php" );







                \Stripe\Stripe::setApiKey( $secret_key );







                try 



                {







                     $token = \Stripe\Token::create(



                        array(



                            "card" => array(



                                "number"  =>  $card_no,



                                "exp_month" =>  (int) $card_exp_month,



                                "exp_year"  =>  (int) $card_exp_year,



                                "cvc"   =>  $card_cvv



                            )



                        )



                    );







                    $charge = \Stripe\Charge::create(



                        array(



                            "amount"        =>  $amount,



                            "currency"      =>  $ncm_settings->ncm_get_currency(),



                            "source"        =>  $token,



                            "description"   =>  $description,



                            "receipt_email" =>  $user_email,



                            "metadata"      => array(



                                "ncm_order_id" => $order_id,



                                "email"   => $user_email,



                                "first_name"  => $user_firstname,



                                "last_name"  => $user_lastname,



                            ),



                        )



                    );







                    if( $charge->status == 'succeeded' ) {







                        $charge_id = $charge->id; 



                        $txn_id = $charge->balance_transaction;



                        if( $data['ncm_is_live'] ) {



                            $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'completed' );



                        } else {



                            $ncm_payment_gateways->ncm_update_orderstatus( $order_id, 'non-confirmed' );



                        }



                        update_post_meta( $order_id, 'ncm_stripe_charge_id', $charge_id);



                        update_post_meta( $order_id, 'ncm_transaction', $txn_id);







                        $ncm_stripe_response = $charge;



                        $status  =  "success";



                        $msg = __('Payment successfull.', NCM_txt_domain);



                        $content = __('Payment successfull.', NCM_txt_domain);







                    } else {







                        $ncm_payment_gateways->ncm_update_orderstatus( $order_id, $charge->status );



                        $ncm_stripe_response = $charge;



                        $status  =  "success";



                        $msg = __('Payment successfull.', NCM_txt_domain);



                        $content = __('Payment successfull.', NCM_txt_domain);







                    }



                }catch(\Stripe\CardError $e) { 



                    $ncm_stripe_response = $e;



                    $status  =  "failed";



                    $msg = $error;



                    $content = $e->getMessage();



                } catch (\Stripe\InvalidRequestError $e) {



                    $ncm_stripe_response = $e;



                    $status  =  "failed";



                    $msg = $error;



                    $content = $e->getMessage();



                } catch (\Stripe\AuthenticationError $e) {



                    $ncm_stripe_response = $e;



                    $status  =  "failed";



                    $msg = $error;



                    $content = $e->getMessage();



                } catch (\Stripe\ApiConnectionError $e) {



                     $ncm_stripe_response = $e;



                    $status  =  "failed";



                    $msg = $error;



                    $content = $e->getMessage();



                } catch (\Stripe\Error $e) {



                     $ncm_stripe_response = $e;



                    $status  =  "failed";



                    $msg = $error;



                    $content = $e->getMessage();



                } catch (Exception $e) {



                    if ($e->getMessage() == "zip_check_invalid") {



                        $error = "decline



                        d1";



                    } else if ($e->getMessage() == "address_check_invalid") {



                        $error = "decline2d";



                    } else if ($e->getMessage() == "cvc_check_invalid") {



                        $error = "declined3";



                    } else {



                        $error = $e->getMessage();



                    }



                    $ncm_stripe_response = $e;



                    $status  =  "failed";



                    $msg = $error;



                    $content = $e->getMessage();



                }



                



                update_post_meta( $order_id, 'ncm_gateway_response', json_encode($ncm_stripe_response));



                return array( "status" => $status, "msg" => $msg, "content" => $content );



            } else {



                $msg = __("sorry! something went wrong.", NCM_txt_domain);



                $content = __("Stripe secret key is blank.", NCM_txt_domain);



                return array( "status" => $status, "msg" => $msg, "content" => $content );



            }



        } else {



            $msg = __("Stripe is not enabled.", NCM_txt_domain);



            $content = __("Stripe is not enabled.", NCM_txt_domain);



            return array( "status" => $status, "msg" => $msg, "content" => $content );



        }



    }







    // Refund to user



    function ncm_refund_stripe( $order_id, $gateway_name, $gateway ) {



        global $ncm_settings, $ncm_checkout, $ncm, $ncm_payment_gateways;



        if( isset($gateway['ncm_stripe_enabled']) && !empty($gateway['ncm_stripe_enabled']) ) {



            $stripe_mode = 'live';



            if( $ncm_checkout->ncm_get_geteway_mode( $order_id, $gateway_name ) == 'test' ) {



                $stripe_mode = 'test';



                $secret_key = ( isset($gateway['ncm_stripe_test_secret_key']) && !empty($gateway['ncm_stripe_test_secret_key']) ) ? $gateway['ncm_stripe_test_secret_key'] : '';



            } else {



                $stripe_mode = 'live';



                $secret_key = ( isset($gateway['ncm_stripe_secret_key']) && !empty($gateway['ncm_stripe_secret_key']) ) ? $gateway['ncm_stripe_secret_key'] : '';



            }







            $charge_id = get_post_meta( $order_id, 'ncm_stripe_charge_id', true );



            $subtotal =  get_post_meta( $order_id, 'ncm_subtotal', true );



            



            include_once( NCM_LIB_STIRPE_DIR."init.php" );







            \Stripe\Stripe::setApiKey( $secret_key );







            $amount = $subtotal * 100;







            try 



            {



                $refund = \Stripe\Refund::create([



                    'charge' => $charge_id,



                    'amount' => $amount,



                ]);







                $refund_status = $refund->status;



                $refund_id = $refund->id;



                $status = ($refund_status == 'succeeded') ? 'success' : 'failed'; 



                $msg = '';



                $content = '';



                update_post_meta( $order_id, 'ncm_stripe_refund_id', $refund_id);



                update_post_meta( $order_id, 'ncm_refund_gateway_response', json_encode($refund));



            } catch(\Stripe\CardError $e) { 



                $ncm_stripe_response = $e;



                $status  =  "failed";



                $msg = $error;



                $content = $e->getMessage();



            }



            return array( "status" => $status, "msg" => $msg, "content" => $content );



        }



    }







}







global $ncm_stripe_gateways;



$ncm_stripe_gateways = new NCM_Stripe_Gateways();







}



?>