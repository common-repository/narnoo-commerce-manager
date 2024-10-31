<?php

/*
* The payment gateways functions are here.
*/

if( !class_exists ( 'NCM_Payment_Gateways' ) ) {







if( file_exists( NCM_MODEL_DIR."ncm_payment_gateways.model.php" ) ) {



    include_once( NCM_MODEL_DIR."ncm_payment_gateways.model.php" );



}







class NCM_Payment_Gateways extends NCM_DB_Payment_Gateways {







    var $ncm_payment_status = '';



    



	function __construct(){



    



        $this->ncm_payment_status = array(



            "ncm-pending"       => __("Pending payment", NCM_txt_domain),



            "ncm-processing"    => __("Processing", NCM_txt_domain),



            "ncm-on-hold"       => __("On hold", NCM_txt_domain),



            "ncm-non-confirmed" => __("Non Confirmed", NCM_txt_domain),



            "ncm-confirmed"     => __("Confirmed", NCM_txt_domain),



            "ncm-completed"     => __("Completed", NCM_txt_domain),



            "ncm-cancelled"     => __("Cancelled", NCM_txt_domain),



            "ncm-refunded"      => __("Refunded", NCM_txt_domain),



            "ncm-failed"        => __("Failed", NCM_txt_domain),



            "ncm-trash"         => __("Trash", NCM_txt_domain),



        );







        add_action( 'init', array( $this, 'ncm_custom_post_status' ) );







        add_filter( "ncm_make_payment", array( $this, "ncm_make_payment_func" ), 10, 3 );







        add_filter( "ncm_payment_gateways_fields", array( $this, "ncm_payment_gateways_fields_func" ), 10, 2 );







	}







    function ncm_custom_post_status(){



        foreach ($this->ncm_payment_status  as $status_key => $status_value) {



            register_post_status( $status_key, array(



                'label'                     => $status_value,



                'public'                    => false,



                'exclude_from_search'       => true,



                'show_in_admin_all_list'    => true,



                'show_in_admin_status_list' => true,



                'label_count'               => _n_noop( $status_value.' <span class="count">(%s)</span>', $status_value.' <span class="count">(%s)</span>' ),



            ) );



        }



    }







    function ncm_get_active_payment_gateways( $with_common_data = false ) {



        global $ncm_settings;



        $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );



        $gateway_names = $this->get_payment_gateways();



        $ncm_gateway_order = $checkout_opt['ncm_gateway_order'];



        $gateway = array();



        foreach( $ncm_gateway_order as $gateway_key ) {



            $array_key = 'ncm_'.$gateway_key.'_enabled';



            if( isset($checkout_opt[$array_key]) && $checkout_opt[$array_key] == 1 ) {



                if( $with_common_data && isset($gateway_names[$gateway_key]) ) {



                    $gateway_name = $gateway_names[$gateway_key];



                    $title_key = 'ncm_'.$gateway_key.'_title';



                    $desc_key = 'ncm_'.$gateway_key.'_description';



                    $gateway_title = isset($checkout_opt[$title_key]) ? $checkout_opt[$title_key] : $gateway_name;



                    $gateway_desc = isset($checkout_opt[$desc_key]) ? $checkout_opt[$desc_key] : $gateway_name;







                    $gateway[$gateway_key]['gateway_id'] = $gateway_key;



                    $gateway[$gateway_key]['gateway_name'] = $gateway_name;



                    $gateway[$gateway_key]['gateway_title'] = $gateway_title;



                    $gateway[$gateway_key]['gateway_desc'] = $gateway_desc;



                } else {



                    $gateway[] = $gateway_key;



                }



            }



        }



        return $gateway;



    }







    function ncm_get_active_gateways_data( $gateway_name = '' ) {



        global $ncm_settings;



        $active_gateway = $this->ncm_get_active_payment_gateways();



        $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );



        $gateway_data = array();



        foreach ( $active_gateway as $gateway ) {



            $function_name = 'ncm_'.$gateway.'_keys';



            $gateway_keys = $this->$function_name();



            $gateway_data[$gateway] = array_intersect_key($checkout_opt, $gateway_keys);



        }



        if( !empty($gateway_name) ) {



            return $gateway_data[$gateway_name];



        } else { 



            return $gateway_data;



        }



    }







    function ncm_is_multiple_cart() {



        if( $this->ncm_get_cart_product_type() == 'multiple' ) {



            return true;



        } else {



            return false;



        }



    }







    function ncm_get_checkout_setting_value( $key ) {



        global $ncm_settings, $ncm;



        $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );



        if( isset($checkout_opt[ $key ]) && $checkout_opt[ $key ] > 0 ){



            return get_page_link( $checkout_opt[ $key ] );



        } else {



            return $ncm->ncm_site_url();



        }



    }







    function ncm_get_cart_product_type() {



        global $ncm_settings;



        $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );



        if( isset($checkout_opt['ncm_cart_product_type']) && !empty($checkout_opt['ncm_cart_product_type']) ){



            return $checkout_opt['ncm_cart_product_type'];



        } else {



            return 'multiple';



        }



    }







    function ncm_get_cart_page_link() {



        return $this->ncm_get_checkout_setting_value( 'ncm_cart_page_id' );



    }







    function ncm_get_checkout_page_link() {



        return $this->ncm_get_checkout_setting_value( 'ncm_checkout_page_id' );



    }







	function ncm_get_thank_you_page_link() {



        return $this->ncm_get_checkout_setting_value( 'ncm_thank_you_page_id' );



    }







	function ncm_get_paypal_email() {



        global $ncm_settings;



        $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );		



        if( isset($checkout_opt['ncm_paypal_email']) ){			



            return $checkout_opt['ncm_paypal_email'];



        } else {



            return '';



        }



    }



	



    function ncm_get_stripe_public_key() {



        global $ncm_settings;



        $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );		



        if( isset($checkout_opt['ncm_stripe_publishable_key']) ){			



            return $checkout_opt['ncm_stripe_publishable_key'];



        } else {



            return '';



        }



    }



	



    function ncm_get_stripe_private_key() {



        global $ncm_settings;



        $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );		



        if( isset($checkout_opt['ncm_stripe_secret_key']) ){			



            return $checkout_opt['ncm_stripe_secret_key'];



        } else {



            return '';



        }



    }







    function ncm_check_is_active_gateway( $gateway_name ) {



        $active_gateway = $this->ncm_get_active_payment_gateways();



        return in_array($gateway_name, $active_gateway);



    }







    function ncm_make_payment_func( $response, $gateway_name, $data ) {



        global $ncm_settings, $ncm_paypal_gateways, $ncm_stripe_gateways, $ncm_eway_gateways, $ncm_securepay_gateways;



        $response = array( "status" => "failed", "msg" => __('sorry! something went wrong.', NCM_txt_domain) );



        $general_opt = $ncm_settings->ncm_get_settings_func( );



        $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );



        if( !$this->ncm_check_is_active_gateway( $gateway_name ) ) {



            $response = array( "status" => "failed", "msg" => __('sorry! something went wrong.', NCM_txt_domain), "content" => __('Payment gateway is not active', NCM_txt_domain) );



        } else {



            $gateway_data = $this->ncm_get_active_gateways_data();



            $gateway = isset($gateway_data[$gateway_name]) ? $gateway_data[$gateway_name] : array();



            $gateway_testmode_key = reset( array_intersect( array_keys( $gateway ), array( 'ncm_paypal_testmode', 'ncm_stripe_testmode', 'ncm_eway_sandbox' ) ) );



            if( isset( $general_opt['ncm_narnoo_api_mode'] ) && $general_opt['ncm_narnoo_api_mode'] && isset($gateway[$gateway_testmode_key]) && $gateway[$gateway_testmode_key] ) {



                return $response = array("status"=>"failed", "msg"=> __('Live Narnoo mode supports only live payments.', NCM_txt_domain));



            }



            if( $gateway_name == 'paypal' ) {



                $response = $ncm_paypal_gateways->ncm_payment_paypal_standard( $data, $gateway_name, $gateway );



            } else if( $gateway_name == 'stripe' ) {



                if( isset($gateway['ncm_stripe_checkout']) && $gateway['ncm_stripe_checkout'] == 1 ) {



                    $response = $ncm_stripe_gateways->ncm_payment_stripe_checkout( $data, $gateway_name, $gateway );



                } else {



                    $response = $ncm_stripe_gateways->ncm_payment_stripe( $data, $gateway_name, $gateway );



                }



            } else if( $gateway_name == 'securepay' ) {



                $response = $ncm_securepay_gateways->ncm_payment_securepay( $data, $gateway_name, $gateway );



            } else if( $gateway_name == 'eway' ) { 



                $response = $ncm_eway_gateways->ncm_payment_eway( $data, $gateway_name, $gateway );



            }



        }



        return $response;



    } 







    function ncm_update_orderstatus ( $order_id, $status ) {



        global $wpdb;



        $ncm_all_status = array_keys( $this->ncm_payment_status );



        $status_key = ( in_array( $status, $ncm_all_status ) ) ? $status : 'ncm-'.$status;



        if( !empty( $order_id ) && !empty( $status ) && in_array( $status_key, $ncm_all_status ) ) {



            if( is_array( $order_id ) ) {



                foreach ( $order_id as $order ) {



                    wp_update_post( array( 'ID' => $order, 'post_status' => $status_key ) );



                }



            } else {



                wp_update_post( array( 'ID' => $order_id, 'post_status' => $status_key ) );



            }



        } else {



            return false;



        }



    }







    function ncm_can_display_payment_fields( $gateway_name ) {



        $return = false;



        if( in_array( $gateway_name, $this->ncm_get_active_payment_gateways() ) ) {



            $gateways_data = $this->ncm_get_active_gateways_data();



            $gateway_data = $gateways_data[$gateway_name];



            $is_active = false;



            if( isset($gateway_data['ncm_stripe_checkout']) && $gateway_data['ncm_stripe_checkout'] != 1 && isset($gateway_data['ncm_stripe_enabled']) && $gateway_data['ncm_stripe_enabled'] == 1 ) {



                $is_active = true;



            }







            if( isset($gateway_data['ncm_eway_enabled']) && $gateway_data['ncm_eway_enabled'] == 1 ) {



                $is_active = true;



            }







            if( isset($gateway_data['ncm_securepay_enabled']) && $gateway_data['ncm_securepay_enabled'] == 1 ) {



                $is_active = true;



            }







            if( $is_active && in_array( $gateway_name, array( 'stripe', 'eway', 'securepay' ) ) ) {



                $return = true;



            }



        } 



        return $return;



    }







    function ncm_payment_gateways_fields_func( $content='', $gateway_name ) {



        $content = '';



        if( $this->ncm_can_display_payment_fields( $gateway_name ) ) {



            $content.= $this->ncm_credit_cart_fields_content( $gateway_name );



        } 



        return $content;



    }







    function ncm_credit_cart_fields_content( $gateway_name ) {



        global $ncm_controls;



        $fields = $this->ncm_credit_cart_fields( $gateway_name );







        $content = '';







        foreach( $fields as $field ) {



            $content.= '<div class="row">';



            $content.= '<div class="form-group">';



            //$content.= '<div class="ncm-col-md-12">';



            $content.= '<div class="ncm-col-sm-4 ncm-col-md-4 d-inline-block">';



            $content.= '<label class="control-label" for="ncm-first_name">'.$field['label'].'</label>';



            $content.= '</div>';



            $content.= '<div class="ncm-col-sm-8 ncm-col-md-8 d-inline-block">';



            $content.= '<div class="field-wrapper-50">';



            $content.= $ncm_controls->ncm_control($field['field']);



            $content.= '<p class="text-danger"></p>';



            $content.= '</div>';



            $content.= '</div>';



            //$content.= '</div>';



            $content.= '</div>';



            $content.= '</div>';



        }







        return $content;



    }



    



    function ncm_refund( $response, $order_id ) {



        global $ncm_settings, $ncm_paypal_gateways, $ncm_stripe_gateways, $ncm_eway_gateways, $ncm_securepay_gateways;



        $response = array( "status" => "failed", "msg" => __('sorry! something went wrong.', NCM_txt_domain) );



        $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );



        $gateway_name = get_post_meta( $order_id, 'ncm_gateway_name', true );



        



        if( !$this->ncm_check_is_active_gateway( $gateway_name ) ) {



            $response = array( "status" => "failed", "msg" => __('sorry! something went wrong.', NCM_txt_domain), "content" => __('Payment gateway is not active', NCM_txt_domain) );



        } else {







            $gateway_data = $this->ncm_get_active_gateways_data();



            $gateway = isset($gateway_data[$gateway_name]) ? $gateway_data[$gateway_name] : array();



            if( $gateway_name == 'paypal' ) {







                $response = $ncm_paypal_gateways->ncm_refund_paypal_standard( $order_id, $gateway_name, $gateway );







            } else if( $gateway_name == 'stripe' ) {







                $response = $ncm_stripe_gateways->ncm_refund_stripe( $order_id, $gateway_name, $gateway );



            



            } else if( $gateway_name == 'securepay' ) {







                $response = $ncm_securepay_gateways->ncm_refund_securepay( $order_id, $gateway_name, $gateway );



            



            } else if( $gateway_name == 'eway' ) { 







                $response = $ncm_eway_gateways->ncm_refund_eway( $order_id, $gateway_name, $gateway );







            }



        }



        return $response;



    }



}







global $ncm_payment_gateways;



$ncm_payment_gateways = new NCM_Payment_Gateways();







}



?>