<?php

/*
* This is a payment gateways model. 
*/

if( !class_exists ( 'NCM_DB_Payment_Gateways' ) ) {



class NCM_DB_Payment_Gateways{







    function __construct(){







    }







    function get_payment_gateways() {



        return array(



            'checkout' => __('Checkout options', NCM_txt_domain),



            'paypal' => __('Paypal', NCM_txt_domain),



            'stripe' => __('Stripe', NCM_txt_domain),



            'eway' => __('eWAY', NCM_txt_domain),



            'securepay' => __('SecurePay', NCM_txt_domain),



            /* 'afterpay' => __('Afterpay', NCM_txt_domain) */



        );



    }







    function ncm_default_checkout_option() {



        $ncm_default_option = array();



        foreach($this->get_payment_gateways() as $key => $gateway_name) {



            $function_name = 'ncm_'.$key.'_keys';



            $gateway_keys = $this->$function_name();



            $ncm_default_option = array_merge($ncm_default_option, $gateway_keys);



        }



        return $ncm_default_option;



    }







    function ncm_checkout_keys() {



        return array(



            'ncm_enable_guest_checkout' => '0',



            'ncm_force_ssl_checkout' => '0',



            'ncm_unforce_ssl_checkout' => '0',



            'ncm_cart_product_type' => 'multiple',



            'ncm_cart_page_id' => '0',



            'ncm_checkout_page_id' => '0',



            'ncm_terms_page_id' => '0',



            'ncm_thank_you_page_id' => '0',



            'ncm_gateway_order' => array(),



        );



    }







    function ncm_stripe_keys() {



        return array(



            'ncm_stripe_enabled' => '',



            'ncm_stripe_title' => __('Credit Card (Stripe)', NCM_txt_domain),



            'ncm_stripe_description' => __('Pay with your credit card via Stripe.', NCM_txt_domain),



            'ncm_stripe_testmode' => '',



            'ncm_stripe_test_publishable_key' => '',



            'ncm_stripe_test_secret_key' => '',



            'ncm_stripe_publishable_key' => '',



            'ncm_stripe_secret_key' => '',



            'ncm_stripe_publishable_key' => '',



            'ncm_stripe_secret_key' => '',



            'ncm_stripe_statement_descriptor' => __('admin', NCM_txt_domain),



            'ncm_stripe_capture' => '',



            'ncm_stripe_checkout' => '',



            'ncm_stripe_checkout_locale' => 'en',



        );



    }







    function ncm_stripe_language() {



        return array( 



              '0' => __('Auto', NCM_txt_domain),



              'zh' => __('Simplified Chinese', NCM_txt_domain),



              'da' => __('Danish', NCM_txt_domain),



              'nl' => __('Dutch', NCM_txt_domain),



              'en' => __('English', NCM_txt_domain),



              'fi' => __('Finnish', NCM_txt_domain),



              'fr' => __('French', NCM_txt_domain),



              'de' => __('German', NCM_txt_domain),



              'it' => __('Italian', NCM_txt_domain),



              'ja' => __('Japanese', NCM_txt_domain),



              'no' => __('Norwegian', NCM_txt_domain),



              'es' => __('Spanish', NCM_txt_domain),



              'sv' => __('Swedish', NCM_txt_domain),



        );



    }







    function ncm_paypal_keys() {



        return array( 



            'ncm_paypal_enabled' => '',



            'ncm_paypal_title' => __('Paypal', NCM_txt_domain),



            'ncm_paypal_description' => __('Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.', NCM_txt_domain),



            'ncm_paypal_email' => '', 



            'ncm_paypal_testmode' => '',



            'ncm_paypal_api_username' => '',



            'ncm_paypal_api_password' => '',



            'ncm_paypal_api_signature' => '',



        );



    }







    function ncm_eway_keys() {



        return array(



            'ncm_eway_enabled' => '',



            'ncm_eway_title' => __('Credit card (eWAY)', NCM_txt_domain),



            'ncm_eway_description' => __('Pay with your credit card using eWAY secure checkout', NCM_txt_domain),



            'ncm_eway_availability' => 'all',



            'ncm_eway_countries' => '',



            'ncm_eway_api_key' => '',



            'ncm_eway_password' => '',



            'ncm_eway_ecrypt_key' => '',



            'ncm_eway_customerid' => '',



            'ncm_eway_sandbox' => '',



            'ncm_eway_sandbox_api_key' => '',



            'ncm_eway_sandbox_password' => '',



            'ncm_eway_sandbox_ecrypt_key' => '',



            'ncm_eway_stored' => '',



            'ncm_eway_logging' => '',



            'ncm_eway_card_form' => '',



            'ncm_eway_card_msg' => '',



            'ncm_eway_site_seal' => '',



            'ncm_eway_site_seal_code' => '',



        );



    }







    function ncm_securepay_keys() {



        return array(



            'ncm_securepay_enabled' => '',



            'ncm_securepay_title' => __('SecurePay Payment', NCM_txt_domain),



            'ncm_securepay_description' => __('Enter your credit card details below.', NCM_txt_domain),



            'ncm_securepay_testmode' => '',



            'ncm_securepay_merchantid' => '',



            'ncm_securepay_password' => '',



            'ncm_securepay_test_merchantid' => '',



            'ncm_securepay_test_password' => '',



        );



    }







    function ncm_afterpay_keys() {



        return array(



            'ncm_afterpay_enabled' => '',



            'ncm_afterpay_title' => __('Pay with Afterpay', NCM_txt_domain),



            'ncm_afterpay_description' => __('Pay with Afterpay.', NCM_txt_domain),



            'ncm_afterpay_testmode' => '',



            'ncm_afterpay_prod_id' => '',



            'ncm_afterpay_prod_secret_key' => '',



            'ncm_afterpay_test_id' => '',



            'ncm_afterpay_test_secret_key' => '',



        );



    }







    function ncm_credit_cart_fields( $gateway_name ) {



        global $ncm_settings, $ncm_shortcode;



        $ncm_class = "form-control";







        $ncm_fields[] = array(



            "label" => __('Card Holder Name', NCM_txt_domain),



            "field" => array( 



                "type" => "text",



                "class" => $ncm_class." ncm_".$gateway_name."_card_holder_name ncm_card_holder_name",



                "name" => "ncm_".$gateway_name."_card_holder_name",



                "id" => "ncm_".$gateway_name."_card_holder_name",



                "value" => "",
                "required" => "1",



                "data-error_required" => __('Please enter card holder name.', NCM_txt_domain),



            )



        );







        $ncm_fields[] = array(



            "label" => __('Credit Card', NCM_txt_domain),



            "field" => array( 



                "type" => "text",



                "class" => $ncm_class." ncm_".$gateway_name."_credit_card ncm_credit_card",



                "name" => "ncm_".$gateway_name."_credit_card",



                "id" => "ncm_".$gateway_name."_credit_card",



                "value" => "",
                "required" => "1",



                "maxlength" => "16",



                "data-error_required" => __('Please enter credit card number.', NCM_txt_domain),



                "data-error_minlength" => __('Please enter valid card number.', NCM_txt_domain),



            )



        );







        $ncm_fields[] = array(



            "label" => __('Expiry (MM/YY)', NCM_txt_domain),



            "field" => array( 



                "type" => "text",



                "class" => $ncm_class." ncm_".$gateway_name."_exp ncm_exp_card",



                "name" => "ncm_".$gateway_name."_exp",



                "id" => "ncm_".$gateway_name."_exp",



                "value" => "",
                "required" => "1",



                "placeholder" => "MM/YY",



                "data-error_required" => __('Please enter expiry date.', NCM_txt_domain),



            )



        );







        $ncm_fields[] = array(



            "label" => __('CVC', NCM_txt_domain),



            "field" => array( 



                "type" => "text",



                "class" => $ncm_class." ncm_".$gateway_name."_cvc ncm_cvv_card",



                "name" => "ncm_".$gateway_name."_cvc",



                "id" => "ncm_".$gateway_name."_cvc",



                "value" => "",
                "required" => "1",



                "maxlength" => "4",



                "data-error_required" => __('Please enter CVC number.', NCM_txt_domain),



                "data-error_minlength" => __('Please enter valid CVC number.', NCM_txt_domain),



            )



        );







        return $ncm_fields;



    }







}



}



