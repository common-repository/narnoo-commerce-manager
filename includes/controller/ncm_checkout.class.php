<?php

/*
* The cart functions and action are here.
*/


if( !class_exists ( 'NCM_Checkout' ) ) {



if( file_exists( NCM_MODEL_DIR."ncm_checkout.model.php" ) ) {

    include_once( NCM_MODEL_DIR."ncm_checkout.model.php" );

}



class NCM_Checkout extends NCM_DB_Checkout {



    public $ncm_checkout_product;



    function __construct() {



        new NCM_DB_Checkout();



        add_action( 'wp_ajax_ncm_product_passenger', array($this, 'ncm_product_passenger_detail') );

  

        add_action( 'wp_ajax_nopriv_ncm_product_passenger', array($this, 'ncm_product_passenger_detail') );



        add_action( "ncm_checkout_product", array($this, "ncm_checkout_product_func"), 10 );



        add_action( "ncm_checkout_passenger", array($this, "ncm_checkout_passenger_func"), 10, 1 );



        add_action( "ncm_checkout_product_passenger", array($this, "ncm_checkout_product_passenger"), 10 );



        add_action( "ncm_checkout_payment", array($this, "ncm_checkout_payment_func"), 10 );



        add_action( "ncm_submit_button", array($this, "ncm_submit_button_func"), 10 );



        add_action( "wp_ajax_ncm_validate_checkout", array($this, 'ncm_validate_checkout_func') );

        add_action( "wp_ajax_nopriv_ncm_validate_checkout", array($this, 'ncm_validate_checkout_func') );


        add_action( "wp_ajax_ncm_applypromocode", array($this, 'ncm_applypromocode_func') );

        add_action( "wp_ajax_nopriv_ncm_applypromocode", array($this, 'ncm_applypromocode_func') );

    }



    function ncm_checkout_fields() {

        global $ncm_cart;

        $data = $ncm_cart->ncm_get_cart_pessenger_details();

        //($data);die();

        if( isset( $data['ncm_booking'] ) && !empty( $data['ncm_booking'] ) ) {

            if( !empty( $data['ncm_booking'] ) ) {

                return $data['ncm_booking'];

            } else {

                return false;

            }

        } else {

            return false;

        }

    }



    function ncm_unique_multidim_array($array, $key) {

        global $ncm_payment_gateways;

        $temp_array = array();

        $i = 0;

        $key_array = array();

        

        foreach($array as $val) {

            if ( !in_array($val[$key], $key_array) 

                && !strpos( implode("", $key_array), $val[$key] ) 

                && empty( array_intersect( explode('/',$val[$key]), $key_array ) ) ) {



                // start to set terms and condition page link.

                $ncm_link_word = '';

                if( strpos( $val[$key], 'terms and conditions' ) !== false ) {

                    $ncm_link_word = 'terms and conditions';

                } else if( strpos( $val[$key], 'terms and condition' ) !== false ) {

                    $ncm_link_word = 'terms and conditions';

                } else if( strpos( $val[$key], 'conditions' ) !== false ) {

                    $ncm_link_word = 'conditions';

                } else if( strpos( $val[$key], 'terms' ) !== false ) {

                    $ncm_link_word = 'terms';

                }



                if( !empty($ncm_link_word) ) {

                    $page_link = $ncm_payment_gateways->ncm_get_checkout_setting_value( 'ncm_terms_page_id' );

                    $page_link_tag = '<a href="' . $page_link . '" target="_blank" id="ncm_terms_and_conditions">' . $ncm_link_word . '</a>';

                    $val[$key] = str_replace( $ncm_link_word, $page_link_tag, $val[$key] );

                }

                // start to set terms and condition page link.



                $key_array[$i] = $val[$key];

                $temp_array[$i] = $val;

            }

            $i++;

        }



        return array_reverse($temp_array);

    } 



    function ncm_checkout_uniqe_fields() {

        global $ncm_cart;

        $booking_fields = $this->ncm_checkout_user_fields();

        $ncm_fields = $this->ncm_checkout_fields();

        $ncm_cart_items = $ncm_cart->ncm_cart_product_info();



        if( !empty( $ncm_fields ) ) {

            $is_default = false;

            foreach( $ncm_fields as $ncm_cart_item_fields_key => $fields ) {

                if( isset( $fields['ncm_narnoo_default'] ) && $fields['ncm_narnoo_default'] ) {

                    if( $is_default ) { continue; }

                    $is_default = true;

                    unset( $fields['ncm_narnoo_default'] );

                }



                if( in_array( $ncm_cart_item_fields_key, array_keys( $ncm_cart_items ) ) ) {

                    $booking_fields = array_merge( $booking_fields, $fields );

                }

            }



            $booking_fields = $this->ncm_unique_multidim_array( array_reverse($booking_fields), 'label' );

        }

        return $booking_fields;

    }



    function ncm_checkout_setup_fields() {

        global $ncm_cart, $ncm_payment_gateways, $ncm_controls, $ncm_settings;

        $ncm_booking_data = array();

        $booking_fields = $this->ncm_checkout_uniqe_fields();

        

        if( !empty( $booking_fields ) ) {



            $booking_fields_data = array();

            $pass_count = 0;

            foreach( $booking_fields as $booking_value ) {

                //echo '<pre>';
                //print_r($booking_fields); die();

                $pass_count++;

                $name = 'ncm_booking['.$pass_count.']';

                //$customclass_label = isset($booking_value['customclass']) ? $booking_value['customclass'] : '';

                $html_label = isset($booking_value['label']) ? $booking_value['label'] : '';

                $label = isset($booking_value['label']) ? strip_tags($booking_value['label']) : '';

                $value = isset($booking_value['value']) ? strip_tags($booking_value['value']) : '';

                $placeholder = isset($booking_value['label']) ? strip_tags($booking_value['label']) : '';

                $type = isset($booking_value['type']) ? $booking_value['type'] : 'text';

                $option = isset($booking_value['list']) ? $booking_value['list'] : array();

                $required = isset($booking_value['required']) ? $booking_value['required'] : 'false';

                $class = "form-control ncm_booking ".$name;

               
                if( isset( $booking_value['list'] ) && !empty( $booking_value['list'] ) ) {

                    $type = 'select';

                    $class.= ' ncm_select';

                }

                if( $type == 'checkbox' ) {

                    $option = array( $label => '' );

                } else if( $type == 'country' || in_array( $label, array( 'country', 'Country' ) ) ) {

                    $type = 'select';

                    $class.= ' ncm_select';

                    $option = $this->ncm_get_countries_state();

                }



                if( $required ) {

                    $class.= ' ncm_required';

                }



                $control_value_arr = array( 

                                        "type" => $type,

                                        "name" => $name.'[value]',

                                        "id" => $name,

                                        "value" => $value,

                                        "class" => $class,

                                        "placeholder" => $placeholder,

                                        "options" => $option,

                                        "data-required" => $required,

                                    );




                $control = $ncm_controls->ncm_control( $control_value_arr );

                $control.= $ncm_controls->ncm_control(

                                    array(

                                        "type" => "hidden",

                                        "name" => $name.'[label]',

                                        "id" => "ncm_label_".$name,

                                        "value" => $label,

                                    )

                                );



                $ncm_booking_data[] = array(

                                "label" => $html_label,

                                "control" => $control,

                            );

            }

        }



        return array( 'ncm_user_fields' => $ncm_booking_data );

    }



    function ncm_checkout_main_func() {

        global $ncm_cart, $ncm_payment_gateways, $ncm_controls;

        $content = '<div id="ncm_container">';

        if( $ncm_cart->ncm_get_cart_products() ) {

            $content.= ncm_get_template_content( "ncm-checkout", $this->ncm_checkout_setup_fields() );

        } else {

            $content.= '<script> window.location.href = "'.$ncm_payment_gateways->ncm_get_cart_page_link().'" </script>';

            //wp_redirect( $ncm_payment_gateways->ncm_get_cart_page_link() );

        }

        $content.= '</div>';

        return $content;

    }



    function ncm_get_countries_state( $is_state = false ) {

        global $ncm_settings;

        $ncm_class = "form-control";

        $general_setting = $ncm_settings->ncm_get_settings_func();

        $ncm_countries_all = $ncm_countries = $ncm_settings->ncm_country();


        if( $general_setting['ncm_setting_allowed_countries'] == 'specific' ) {

            $ncm_countries = $general_setting['ncm_setting_specific_allowed_countries'];

        } else if( $general_setting['ncm_setting_allowed_countries'] == 'all_except' ) {

            $except_countries = $general_setting['ncm_setting_all_except_countries'];

            $ncm_countries = array_diff( array_keys( $ncm_countries ), $except_countries );

        }



        $ncm_dd_countries = array();

        if( $general_setting['ncm_setting_allowed_countries'] == 'all' ) {

            $ncm_dd_countries = $ncm_countries_all;

        } else {

            foreach ( $ncm_countries_all as $country_key => $country_value ) {

                if( in_array( $country_key, $ncm_countries ) ) {

                    $ncm_dd_countries[$country_value] = $country_value;

                }

            }

        }



        return $ncm_dd_countries;

    }



    function ncm_product_passenger_detail() {

        ncm_get_template("ncm-checkout-product-passenger");

        exit;

    }



    function ncm_checkout_product_passenger() {

        echo '<div class="ncm_product_passenger_container" id="ncm_product_passenger_container">';

        echo '</div>';

    }



    function ncm_checkout_payment_func() {

        global $ncm_payment_gateways;

        $active_gateway = $ncm_payment_gateways->ncm_get_active_payment_gateways( true );

        foreach ($active_gateway as $gateway_name) {

            $gateway_id = $gateway_name['gateway_id'];



            $gateway_name['radio_attr'] = ' name="ncm_gateway" id="ncm_gateway_'.$gateway_id.'" value="'.$gateway_id.'" class="ncm_payment_gateways" ';

            if( $ncm_payment_gateways->ncm_can_display_payment_fields( $gateway_id ) ) {

                $gateway_name['radio_attr'].= ' data-has_payment_fields="1" ';

            } else {

                $gateway_name['radio_attr'].= ' data-has_payment_fields="0" ';

            }

            

            $gateway_name['link_attr'] = ' href="#'.$gateway_id.'" data-id="'.$gateway_id.'" class="ncm_payment_gateways_tab"';

            $gateway_name['gateway_fields'] = apply_filters( "ncm_payment_gateways_fields", '', $gateway_id );

            

            ncm_get_template("ncm-checkout-payment", $gateway_name);

        }

    }



    function ncm_checkout_product_func() {

        global $ncm_cart, $ncm_settings;

        $cart_products = $ncm_cart->ncm_cart_product_info();

        /*echo '<pre>';
        print_r($cart_products);
        echo '</pre>';*/

        $cart_passenger = $ncm_cart->passenger_type;

        if( !empty($cart_products) && is_array($cart_products) ){

            foreach ($cart_products as $key => $product) {

               /* echo '<pre>';
                print_r($product);
                echo '</pre>';*/

                $checkout_data = array();

                $checkout_data = $product;

                $booking_code = isset($product['booking_code']) ? $product['booking_code'] : '';

                $booking_date = isset($product['booking_date']) ? date( 'mdY', strtotime($product['booking_date']) ) : '';

                $checkout_data['pickup'] = isset($product['pickup_dropoff_option'][$checkout_data['pickup_value']]) ? $product['pickup_dropoff_option'][$checkout_data['pickup_value']] : '';

                $checkout_data['dropoff'] = isset($product['pickup_dropoff_option'][$checkout_data['dropoff_value']]) ? $product['pickup_dropoff_option'][$checkout_data['dropoff_value']] : '';



                $ncm_pickup_data_val = ( $checkout_data['pickup'] == 'Please Select' ) ? 'data-val="Please select"' : '';

                $ncm_dropoff_data_val = ( $checkout_data['dropoff'] == 'Please Select' ) ? 'data-val="Please select"' : '';

                

                //echo 'pickloc===>'.$checkout_data['pickup'][1];

                $checkout_data['pickup']='<span class="ncm_pickup" ' . $ncm_pickup_data_val . '>'. $checkout_data['pickup'][0] .'</span>';

                $checkout_data['dropoff']='<span class="ncm_dropoff" ' . $ncm_dropoff_data_val . '>'. $checkout_data['dropoff'] .'</span>';


                foreach ($product['pickup_dropoff_option'] as $keypick => $valuepick) {
                    if($keypick == $checkout_data['pickup_value']){
                        $pickuplocval = $valuepick[1];  
                    }
                }

                //echo $checkout_data['pickup_price'];

                //echo $pickuplocval;


                $passenger_data = array();

                $passenger_text = '';

                /*echo '<pre>';
                print_r($cart_passenger[$key]['passenger_types']);
                echo '</pre>';*/

                if( isset($cart_passenger[$key]['passenger_types']) && !empty($cart_passenger[$key]['passenger_types']) && is_array($cart_passenger[$key]['passenger_types']) ) {

                    foreach ($cart_passenger[$key]['passenger_types'] as $passenger) {

                        if( !empty($passenger['value']) && $passenger['value'] > 0 ) {

                            $passenger_data[$passenger['label']] = $passenger['value'];

                            $passenger_text.= $passenger['label']." : ".$passenger['value']."<br/>";

                        }

                    }

                }



                $checkout_data['passenger_data'] = $passenger_data;

                $checkout_data['passenger'] = empty($passenger_text) ? '-' : $passenger_text;

                /*echo '<pre>';
                echo count($passenger_data);
                echo '</pre>';*/
                //echo $passenger['value'];
                //echo 'subttal==>'.$checkout_data['pickup_price'];
                //echo 'subtotal==>'.$cart_passenger[$key]['subtotal'];

                //$pickprice = $checkout_data['pickup_price'] * count($passenger_data);
                //echo $pickprice;

                $checkoutsubtotal = $cart_passenger[$key]['subtotal'] + $checkout_data['pickup_price'];
                $checkouttotal = $cart_passenger[$key]['total'] + $checkout_data['pickup_price'];

                //echo $cart_passenger[$key]['subtotal'];

                $checkout_data['subtotal'] = $ncm_settings->ncm_display_price($cart_passenger[$key]['subtotal']);

                //echo 'Subtotla'.$checkout_data['subtotal'];
                //$checkout_data['subtotal'] = $ncm_settings->ncm_display_price($checkoutsubtotal);


                $checkout_data['levy'] = $ncm_settings->ncm_display_price($cart_passenger[$key]['levy']);

                $checkout_data['total'] = $ncm_settings->ncm_display_price($cart_passenger[$key]['total']);

                //$checkout_data['total'] = $ncm_settings->ncm_display_price($checkouttotal);

                ncm_get_template("ncm-checkout-product", $checkout_data);

            }

        }

    }



    function ncm_checkout_passenger_func( $passengers_info ) {

        if( !empty( $passengers_info ) && is_array( $passengers_info ) ) {

            foreach ($passengers_info as $passenger_info) {

                ncm_get_template("ncm-checkout-passenger", $passenger_info);

            }

        }

    }



    function ncm_is_checkout() {

        global $ncm_shortcode;

        return $ncm_shortcode->ncm_has_shortcode( 'ncm_checkout' );

    }



    function ncm_submit_button_func() {

        echo '<button type="button" class="btn btn-lg btn-primary" name="submit" id="ncm_checkout_submit">Submit</button>';

    }



    function ncm_insert_record( $data ) {

        global $wpdb, $ncm, $ncm_settings;



        $table_name = $ncm->tbl_order_item;

        $passenger_table = $ncm->tbl_order_passenger;

        $booking_table = $ncm->ncm_order_booking;



        $post_arr = array(

            'post_author' => $ncm_settings->ncm_get_admin_users(),

            'post_content' => json_encode($data),

            'post_content_filtered' => '',

            'post_title' => '',

            'post_excerpt' => '',

            'post_status' => 'ncm-on-hold',

            'post_type' => 'ncm_order',

            'comment_status' => 'closed',

            'ping_status' => 'closed',

            'post_password' => '',

            'to_ping' =>  '',

            'pinged' => '',

            'post_parent' => 0,

            'menu_order' => 0,

            'guid' => '',

        );



        $post_id = wp_insert_post($post_arr);



        $post_update_arr = array( 'ID'=> $post_id, 'post_title' => 'Order #'.$post_id.' Details' );

        $post_update = wp_update_post( $post_update_arr );



        update_post_meta( $post_id, 'ncm_gateway_name', $data['gateway_name']);

        update_post_meta( $post_id, 'ncm_currency', $ncm_settings->ncm_get_currency());

        update_post_meta( $post_id, 'ncm_subtotal', $data['subtotal'] );

        update_post_meta( $post_id, 'ncm_levy', $data['levy'] );

        update_post_meta( $post_id, 'ncm_total', $data['total'] );

        if( isset( $_COOKIE['NCM_adjustamount'] ) ) {
            update_post_meta( $post_id, 'ncm_adjustamount', $_COOKIE['NCM_adjustamount'] );
        }

        if( isset( $_COOKIE['NCM_adjustdiscount'] ) ) {
            update_post_meta( $post_id, 'ncm_adjustdiscount', $_COOKIE['NCM_adjustdiscount'] );
        }


        // store booking information.

        if( isset($data['booking_info']) && !empty($data['booking_info']) ) {

            $booking_info = $data['booking_info'];

            if( isset($booking_info) && !empty($booking_info) ) {

                foreach( $booking_info as $booking_data ) {



                    $booking_label = isset($booking_data['label']) ? $booking_data['label'] : '';

                    $booking_value = isset($booking_data['value']) ? $booking_data['value'] : '';



                    if( !empty($booking_label) && !empty($booking_value) ) {

                        $booking_data = array( 

                            'order_id' => $post_id,

                            'field_label' => $booking_label,

                            'field_value' => $booking_value,

                        );



                        $booking_format = array( '%d', '%s', '%s', );

                        $wpdb->insert( $booking_table, $booking_data, $booking_format );

                    }

                }

            }

        }



        if( isset($data['products']) && !empty($data['products']) ) {

            

            foreach($data['products'] as $product) {

               
                $location_option = isset($product['pickup_dropoff_option'])? $product['pickup_dropoff_option']: array();

                $pickup = isset($location_option[$product['pickup_value']]) ? $location_option[$product['pickup_value']] : '';

                //echo '<pre>';
                //echo $pickup[0];
                //echo '</pre>'; die();

                $dropoff = isset($location_option[$product['dropoff_value']]) ? $location_option[$product['dropoff_value']] : '';

                $booking_code = isset($product['booking_code']) ? $product['booking_code'] : '';

                $booking_date = isset($product['booking_date']) ? $product['booking_date'] : '';

                $prod_post_id = isset($product['post_id']) ? $product['post_id'] : 0;

                $subtotalorder = $product['subtotal'] + $product['picklocationprice'];
                $totalorder = $product['total'] + $product['picklocationprice'];

                $product_data = array( 

                    'order_id' => $post_id,

                    'post_id' => $prod_post_id,

                    'product_id' => $product['product_id'],

                    'tour_code' => $product['product_id'],

                    'booking_code' => $booking_code,

                    'tour_name' => $product['tour_name'],

                    'travel_date' => $booking_date,

                    't_date' => date( 'Y-m-d', strtotime( $product['booking_date'] ) ),

                    'pickup_id' => isset($product['pickup_value']) ? $product['pickup_value'] : '',

                    //'pickup' => $pickup,
                    'pickup' => $pickup[0],

                    'dropoff_id' => isset($product['dropoff_value']) ? $product['dropoff_value'] : '',

                    'dropoff' => $dropoff,

                    'passenger' => json_encode($product['passenger_types']),

                    'subtotal' => $product['subtotal'],
                    //'subtotal' => $subtotalorder,

                    'levy' => $product['levy'],

                    'total' => $product['total'],
                    //'total' => $totalorder,

                );

                $product_format = array( 

                    '%d', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d',

                );

                $wpdb->insert( $table_name, $product_data, $product_format );

                $order_item_id = $wpdb->insert_id;



                // store passenger information.

                if( isset($data['passenger_info']) && !empty($data['passenger_info']) ) {

                    $passenger_info = $data['passenger_info'];

                    $field_code = $prod_post_id."_".date('dmY_H_i_s', strtotime($booking_date));

                    if( isset($passenger_info[$field_code]) && !empty($passenger_info[$field_code]) ) {

                        foreach( $passenger_info[$field_code] as $passenger_id => $passengers_values ) {



                            foreach($passengers_values as $passenger_val) {



                                $field_labels = isset($passenger_val['label']) ? $passenger_val['label'] : array();

                                $field_values = isset($passenger_val['value']) ? $passenger_val['value'] : array();



                                if( !empty($passenger_val) && !empty($field_labels) && !empty($field_values) ) {

                                    foreach($field_labels as $key => $label ) {

                                        if( !empty($label) && isset($field_values[$key]) && !empty($field_values[$key]) ) {

                                            $passenger_data = array( 

                                                'order_id' => $post_id,

                                                'order_item_id' => $order_item_id,

                                                'passenger_id' => $passenger_id,

                                                'field_label' => $label,

                                                'field_value' => $field_values[$key],

                                            );



                                            $product_format = array( '%d', '%d', '%d', '%s', '%s', );

                                            $wpdb->insert( $passenger_table, $passenger_data, $product_format );

                                        }

                                    }

                                }

                            }

                        }

                    }

                }



            }

        }

        return $post_id;

    }



    function ncm_store_geteway_mode( $order_id, $gateway_name, $mode ) {

        global $wpdb;

        update_post_meta( $order_id, 'ncm_'.$gateway_name, $mode );

    }



    function ncm_get_geteway_mode( $order_id, $gateway_name ) {

        global $wpdb;

        return get_post_meta( $order_id, 'ncm_'.$gateway_name, true );

    }





    function ncm_validate_checkout_func() {

        global $ncm, $wpdb, $ncm_cart, $ncm_narnoo_helper, $ncm_payment_gateways, $ncm_order;


        $order_id = '';

        $response = '';

        $status = 'failed';

        $msg = __('sorry! something went wrong.', NCM_txt_domain);

        if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'ncm_validate_checkout') {

            $_data = $_POST;

            if( !empty($_data) && isset($_data['ncm_gateway']) && !empty($_data['ncm_gateway']) ) {

                $gateway_name = $_data['ncm_gateway'];

                $cart_products = $ncm_cart->ncm_cart_product_info();

                $cart_passenger = $ncm_cart->passenger_type;

                /*echo '<pre>';
                print_r($cart_passenger);
                echo '</pre>'; die();*/

                $products = array();

                $ncm_is_live = true;



                foreach ($cart_products as $cart_row_id => $product_data) {

                    $passenger_data = isset($cart_passenger[$cart_row_id]) ? $cart_passenger[$cart_row_id] : array();

                    $products[$cart_row_id] = array_merge($product_data, $passenger_data);


                    if( ! $product_data['ncm_is_live'] ) {

                        $ncm_is_live = false;

                    }

                }



                $verr = 0;

                $booking_info = isset($_data['ncm_booking']) ? $_data['ncm_booking'] : array();

                $ncm_fields_data = $ncm_cart->ncm_get_cart_pessenger_details();

                //print_r($booking_info); exit('Booking Info');

                if( !empty($products) && !empty($booking_info) /* && $verr <= 0 */ ) {

                    $data = array();



                    $contact = array();

                    foreach ($booking_info as $booking_data) {

                        $label = isset( $booking_data['label'] ) ? $booking_data['label'] : '';

                        $value = isset( $booking_data['value'] ) ? $booking_data['value'] : '';

                        if($label == 'First Name'){
                            $label = 'firstName';
                        }
                        if($label == 'Last Name'){
                            $label = 'lastName';
                        }
                        if($label == 'Phone/Mobile'){
                            $label = 'phone';
                        }
                        if($label == 'Email'){
                            $label = 'email';
                        }
                        if($label == 'Country'){
                            $label = 'country';
                        }
                        /*if($label == 'Comment'){
                            $label = 'comment';
                        }*/


                        $contact[$label] = $value;

                    }


                    
                    $data['contact'] = $contact;

                    $data['booking_info'] = $booking_info;
                    
                    $data['passenger_info'] = $ncm_fields_data['passenger_info'];

                    $data['gateway_name'] = $gateway_name;

                    $data['payment'] = 'CREDITCARD';

                    $data['products'] = $products;

                    //$data['subtotal'] = array_sum(array_column($products, 'subtotal'));

                    $data['subtotal'] = array_sum(array_column($products, 'subtotal')) + array_sum(array_column($products, 'levy'));

                    //$data['subtotal'] = array_sum(array_column($products, 'subtotal')) + array_sum(array_column($products, 'levy')) + array_sum(array_column($products, 'picklocationprice'));

                    
                    $data['levy'] = array_sum(array_column($products, 'levy'));

                    //$data['total'] = array_sum(array_column($products, 'total'));

                    if( isset( $_COOKIE['NCM_adjustamount'] ) ) {
                        $data['total'] = $_COOKIE['NCM_adjustamount'];
                    }else{
                        $data['total'] = array_sum(array_column($products, 'total')) - array_sum(array_column($products, 'levy'));  
                        //$totalprice = array_sum(array_column($products, 'total')) + array_sum(array_column($products, 'picklocationprice'));  
                        //$data['total'] = $totalprice - array_sum(array_column($products, 'levy'));  
                    }

                   //echo 'amount====>'.$_COOKIE['NCM_adjustamount']; 
                   //echo $data['total']; exit();


                    $data['ncm_is_live'] = $ncm_is_live;

                    $data['all'] = $_data;
                    

                    if( isset($_data['ncm_order_id']) && !empty($_data['ncm_order_id']) && $ncm_order->ncm_get_order_id() ) {

                        $order_id = $ncm_order->ncm_get_order_id();

                    } else { 

                        $order_id = $this->ncm_insert_record( $data );

                        $ncm_order->ncm_set_order_id($order_id);

                    } 



                    if( !empty($order_id) ) {

                        $data['order_id'] = $order_id;



                        // make payment using payment gateway

                        $response = apply_filters('ncm_make_payment', $response, $gateway_name, $data);



                        // check payment status make process if payment success than update order status

                        $status = isset($response['status']) ? $response['status'] : '';

                        $msg = isset($response['msg']) ? $response['msg'] : '';

                        $content = isset($response['content']) ? $response['content'] : '';



                         // 12-01-19

                        // Paypal payment start

                        /*

                        if($status == 'submit_form') {



                            $result = $ncm_narnoo_helper->ncm_reservation( $data );

                            $response = json_decode( $result, true );

                            //print_r($response); exit();

                            //

                            $response_data = isset($response['data']['request']) ? $response['data']['request'] : $response;







                                if( isset($response['uniqueId']) and !empty($response['uniqueId']) ) {



                                update_post_meta($order_id, 'ncm_narnoo_order_id', $response['uniqueId']); 



                                }

                             //print_r($response_data); exit();   



                            if( isset($response_data['data']['booking']) && !empty($response_data['data']['booking']) ) {

                                

                                 //print_r($product_data); exit();    



                                foreach ($response_data['data']['booking'] as $product_data) {





                                    if( ( isset($product_data['reservationCode']) && !empty($product_data['reservationCode']) ) || ( isset($product_data['reservationProvider']) && !empty($product_data['reservationProvider']) ) ) {



                                        $reservation_code = $product_data['reservationCode'];



                                        $reservation_provider = $product_data['reservationProvider'];



                                        $booking_code = $product_data['productBookingCode'];



                                        $product_id = $product_data['productId'];



                                        $order_id = $data['order_id'];



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

                        // Paypal payment end   */







                        if($status == 'success') {



                            $result = $ncm_narnoo_helper->ncm_reservation( $data );


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

                                            $order_id = $data['order_id'];


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



                                // send email notification.

                                do_action( "ncm_email_notification", 'new_order', $order_id );



                                $status = "submit_form";

                                //echo $ncm_payment_gateways->ncm_get_thank_you_page_link(); 



                                $msg = __("Redirecting to thank you page.", NCM_txt_domain);

                                //$redurl = $ncm_payment_gateways->ncm_get_thank_you_page_link();
                                    
                                //$content = wp_redirect( $redurl );
                                //exit;   
                                //header("Location: ".$ncm_payment_gateways->ncm_get_thank_you_page_link()); 

                                //$content = $ncm_payment_gateways->ncm_get_thank_you_page_link();

                                $content = '<script type="text/javascript"> setTimeout( function() { window.location.href="'.$ncm_payment_gateways->ncm_get_thank_you_page_link().'"; }, 3000); </script>';
                                //echo "==";die();



                            } else {



                                do_action( "ncm_email_notification", 'customer_processing_order', $order_id );

                                do_action( "ncm_email_notification", 'failed_order', $order_id );

                                $status = "submit_form";

                                $msg = __("Redirecting to thank you page.", NCM_txt_domain);
                                //echo "===";die();
                                $content = '<script type="text/javascript"> setTimeout( function() { window.location.href="'.$ncm_payment_gateways->ncm_get_thank_you_page_link().'"; }, 3000); </script>';



                                /* $ncm_payment_gateways->ncm_refund( '', $order_id );

                                $status = 'failed';

                                $msg = __('Product not available your amount will be refund soon.', NCM_txt_domain);

                                $content = __('Product not booked in narnoo.', NCM_txt_domain);

                                */

                            }

                        }

                    } else {

                        $content = __("Order inserted", NCM_txt_domain);

                    }

                } else {

                    $content = __("Products or contact info are empty.", NCM_txt_domain);

                }

            } else {

                $content = __("Please select payment method.", NCM_txt_domain);

            }

        }

        echo json_encode( array( "status" => $status, "msg" => $msg, 'content' => $content ) );

        exit;

    }


    function ncm_applypromocode_func() {

        global $ncm, $wpdb, $ncm_cart, $ncm_narnoo_helper, $ncm_payment_gateways, $ncm_order;

        $promotionalcode = $_POST['promotionalcode'];



        //$order_id = '';

        //$response = '';

        //$status = 'failed';

        //$msg = __('sorry! something went wrong.', NCM_txt_domain);

        $cart_products = $ncm_cart->ncm_cart_product_info();

        $ncm_total = array_sum(array_column($ncm_cart->passenger_type, 'total'));
        $ncm_levy = array_sum(array_column($ncm_cart->passenger_type, 'levy'));
        $ncm_total = $ncm_total - $ncm_levy;

        //echo "TotlePrice=".$ncm_total;

        //echo '<pre>';
        //print_r($ncm_cart);
        //echo '</pre>'; die();

        $products = array();

        foreach ($cart_products as $cart_row_id => $product_data) {
            $products[$productId] = $product_data['product_id'];   
        }

        $productId = $products[$productId];
        $resultsoperator = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = $productId", ARRAY_A );

        $operatorID = get_post_meta( $resultsoperator[0]['post_id'], 'narnoo_operator_id', true );

        $result = $ncm_narnoo_helper->ncm_product_promocode( $promotionalcode, $productId, $operatorID, $ncm_total );

        
        $resnew = json_decode($result);
        
        //$adjuctamoount = '';
        foreach($resnew as $data){
            $discount .= $data->discount;
            $adjustedamoount .= $data->adjusted_amount; 
        }
        setcookie('NCM_adjustdiscount', $discount, 0, '/');
        setcookie('NCM_adjustamount', $adjustedamoount, 0, '/');
        

        //echo json_encode( array( 'content' => $result ) );
        echo $result;

        exit;

    }

}



global $ncm_checkout;

$ncm_checkout = new NCM_Checkout();



}

?>