<?php

/*
* The cart functions and action are here.
*/

if( !class_exists ( 'NCM_Cart' ) ) {



class NCM_Cart{



    public $passenger_type;



    function __construct() {



        $this->passenger_type = array();



        add_action( "wp", array( $this, "ncm_cart_submit" ), 10 );



        add_action( "wp_ajax_ncm_cart", array($this, "ncm_cart_func") );

  

        add_action( "wp_ajax_nopriv_ncm_cart", array($this, "ncm_cart_func") );



        add_action( "wp_ajax_ncm_cart_calculate", array($this, "ncm_cart_calculate_func") );



        add_action( "wp_ajax_nopriv_ncm_cart_calculate", array($this, "ncm_cart_calculate_func") );

       

        add_action( "ncm_cart_items", array($this, "ncm_cart_display_items_func"), 10 );



        add_action( "ncm_cart_passenger", array($this, "ncm_cart_display_passanger_func"), 10 );



        add_action( "ncm_proceed_to_checkout", array($this, "ncm_proceed_to_checkout_func"), 10, 1 );



        add_action( "ncm_continue_shopping", array($this, "ncm_continue_shopping_func"), 10, 1 );



    }



    function ncm_string_to_div_class( $class_name = "_ncm_" ) {

        $class_name = str_replace(" ", "_", $class_name);

        $class_name = str_replace(":", "_", $class_name);

        return $class_name;

    }



    function ncm_cart_calculate_func() {

        global $ncm_settings, $ncm_controls;

        $ncm_calculate = array();

        if( isset($_POST['product']) && !empty($_POST['product']) && is_array($_POST['product'])) {

            $product_id = '';

            $product_sub_arr = array();

            $product_levy_arr = array();
            
            $product_arr = array();

            $product_passenger_fields_arr = array();

            $ncm_cart_subtotal = 0;

            $ncm_cart_levytotal = 0;

            $ncm_cart_total = 0;

            foreach ($_POST['product'] as $product) {


                //$passenger = isset($product['passenger']) ? $product['passenger'] : 0;

                $price = isset($product['price']) ? $product['price'] : 0;

                $levy = isset($product['levy']) ? $product['levy'] : 0;
                
                $pax = isset($product['pax']) ? $product['pax'] : 1;

                $group = $product['group'];
                
                $maxQuantity = $product['maxQuantity'];

                //$passenger = isset($product['passenger']) ? $product['passenger'] : 0;

                if($group == 'false'){
                    $passenger = isset($product['passenger']) ? $product['passenger'] : 0;
                }else{
                    if($product['passenger'] == 0){
                        $passenger = 0;
                    }elseif($product['passenger'] >= 1){
                        $passenger = 1;
                    }
                }

                //$passenger = isset($product['passenger']) ? $product['passenger'] : 0;

                //echo 'Price->>>'.$price;
                //echo 'Pes->>>'.$passenger;

                $pick_loc_price = isset($product['ncm_pick_loc_price']) ? $product['ncm_pick_loc_price'] : 0;

                //echo 'picklocation===>'.$pick_loc_price;

                $commission = isset($product['commission']) ? $product['commission'] : 0;

                $product_subtotal_id = isset($product['product_subtotal_id']) ? $this->ncm_string_to_div_class( $product['product_subtotal_id'] ) : 0;

                 $product_levy_id = isset($product['product_levy_id']) ? $this->ncm_string_to_div_class( $product['product_levy_id'] ) : 0;

                
                $product_total_id = isset($product['product_total_id']) ? $this->ncm_string_to_div_class( $product['product_total_id'] ) : '';

                $ncm_cart_row_id = isset($product['ncm_cart_row_id']) ? $product['ncm_cart_row_id'] : 0;

                $product_passenger_id = isset($product['product_passenger_id']) ? $product['product_passenger_id'] : 0;

                $product_passenger_label = isset($product['product_passenger_label']) ? $product['product_passenger_label'] : '';

                $product_passenger_fields = isset($product['product_passenger_fields']) ? $this->ncm_string_to_div_class( $product['product_passenger_fields'] ) : '';

                $ncm_passenger_fields = isset($product['ncm_passenger_fields']) ? $product['ncm_passenger_fields'] : '';


                
                
                $product_levy = $levy * $passenger;

                
                //if($pick_loc_price > 0 && !empty($pick_loc_price)){

                    //$pax_passenger = $pax * $passenger;
                    
                    //$picloc = $pick_loc_price * $pax_passenger;

                    //$product_price = $price + $picloc;
                    
                    /*$product_price1 = $price * $passenger;

                    $product_price = $product_price1 + $pick_loc_price;

                    $product_total = $product_levy + $product_price; */                   
                    
                //}else{

                    $product_price = $price * $passenger;
                    
                    //$product_price = $price * $passenger;

                    $product_total = $product_levy + $product_price;
                //}
                    //echo 'Product Price'.$product_price;

                // For sub total amount

                if( !empty( $product_sub_arr ) && in_array( $product_subtotal_id, $product_sub_arr ) ) {


                    $picloc = $pick_loc_price * $passenger;

                    $subtotal = $ncm_calculate[$product_subtotal_id]['amount'] + $product_price + $picloc;

                    
                    $ncm_calculate[$product_subtotal_id] = array('amount' => $subtotal, 'subtotal' => $ncm_settings->ncm_display_price($subtotal) );


                } else {

                    $picloc = $pick_loc_price * $passenger;


                    $subtotal = $product_price + $picloc;


                    $ncm_calculate[$product_subtotal_id] = array('amount' => $subtotal, 'subtotal' => $ncm_settings->ncm_display_price($subtotal) );

                }

                $ncm_cart_subtotal = $ncm_cart_subtotal + $product_price + $picloc + $product_levy;


                $product_sub_arr[] = $product_subtotal_id;



                // For levy amoount

                 if( !empty( $product_levy_arr ) && in_array( $product_levy_id, $product_levy_arr ) ) {

                    $levy_amt = $ncm_calculate[$product_levy_id]['amount'] + $product_levy;
                    //$levy_amt = $product_levy;

                    $ncm_calculate[$product_levy_id] = array('amount' => $levy_amt, 'subtotal' => $ncm_settings->ncm_display_price($levy_amt) );

                } else {

                    $levy_amt = $product_levy;

                    $ncm_calculate[$product_levy_id] = array('amount' => $levy_amt, 'subtotal' => $ncm_settings->ncm_display_price($levy_amt) );

                }

                $ncm_cart_levytotal = $ncm_cart_levytotal + $product_levy;

                $product_levy_arr[] = $product_levy_id;             


                // For total amount

                if( !empty( $product_arr ) && in_array( $product_total_id, $product_arr ) ) {

                    $picloc = $pick_loc_price * $passenger;

                    $total = $ncm_calculate[$product_total_id]['amount'] + $product_total + $picloc;

                    $ncm_calculate[$product_total_id] = array('amount' => $total, 'subtotal' => $ncm_settings->ncm_display_price($total) );

                } else {

                    $picloc = $pick_loc_price * $passenger;

                    $total = $product_total + $picloc;

                    $ncm_calculate[$product_total_id] = array('amount' => $total, 'subtotal' => $ncm_settings->ncm_display_price($total) );

                }

                $ncm_cart_total = $ncm_cart_total + $product_total + $picloc - $product_levy;

                $product_arr[] = $product_total_id;


                // For Passenger fields

                $ncm_row_id = str_replace("-", '', $ncm_cart_row_id);

                $passenger_detail_fields = '';

                if( $passenger > 0 && !empty( $ncm_passenger_fields ) ) {

                    $passenger_field = "passenger_info";



                    $ncm_passenger_fields = json_decode( stripslashes( $ncm_passenger_fields ), true );  

  

                    // set fields for no of passengers.

                    $no_of_passengers = $passenger * $pax;

                    for( $pass=0; $pass < $no_of_passengers; $pass++ ) {

                        $passenger_array = array();

                        $hidden_fields = '';

                        foreach ( $ncm_passenger_fields as $pass_key => $pass_value ) {

                            $label = isset($pass_value['label']) ? $pass_value['label'] : '';

                            $required = isset($pass_value['required']) ? $pass_value['required'] : '';

                            $name = 'ncm_passenger_'.$ncm_cart_row_id.'_'.$pass;

                            $passenger_array[] = array(

                                                    "name" => $passenger_field."[".$ncm_row_id."][".$product_passenger_id."][".$pass."][value][]",

                                                    "id" => $name,

                                                    "class" => "form-control ncm_passenger ".$name." ".$name."_".$pass_key,

                                                    "data-class" => $name."_".$pass_key,

                                                    "data-required" => $required,

                                                    "placeholder" => $label.__(' for ',NCM_txt_domain).$product_passenger_label,

                                                );



                            $hidden_fields.= $ncm_controls->ncm_control(

                                                array(

                                                    "type" => "hidden",

                                                    "name" => $passenger_field."[".$ncm_row_id."][".$product_passenger_id."][".$pass."][label][]",

                                                    "id" => "ncm_lable_".$name,

                                                    "value" => $label,

                                                )

                                            );

                        }

                        $passenger_arr = array();

                        $passenger_arr['passenger_fields'] = $passenger_array;

                        $passenger_detail_fields.= ncm_get_template_content('ncm-cart-passenger-info', $passenger_arr);

                        $passenger_detail_fields.= $hidden_fields;

                    }

                }


                if( !empty( $product_passenger_fields_arr ) && in_array( $product_passenger_fields, $product_passenger_fields_arr ) ) {

                    $passenger_detail_fields = $ncm_calculate[$product_passenger_fields]['subtotal'].$passenger_detail_fields;

                    $ncm_calculate[$product_passenger_fields] = array('amount' => 'field_content', 'subtotal' => $passenger_detail_fields );

                } else {

                    $ncm_calculate[$product_passenger_fields] = array('amount' => 'field_content', 'subtotal' => $passenger_detail_fields );

                }


                $product_passenger_fields_arr[] = $product_passenger_fields;



            }

            
            $ncm_calculate['ncm_cart_subtotal'] = array('amount' => $ncm_cart_subtotal, 'subtotal' => $ncm_settings->ncm_display_price($ncm_cart_subtotal));

            $ncm_calculate['ncm_cart_levytotal'] = array('amount' => $ncm_cart_levytotal, 'subtotal' => $ncm_settings->ncm_display_price($ncm_cart_levytotal));

            $ncm_calculate['ncm_cart_total'] = array('amount' => $ncm_cart_total, 'subtotal' => $ncm_settings->ncm_display_price($ncm_cart_total));

        }

        echo json_encode($ncm_calculate);

        die;

    }



    function ncm_ajax_container() {

        global $ncm_payment_gateways; 

        $cart_action = $ncm_payment_gateways->ncm_get_cart_page_link();

        return '<form method="post" id="ncm_cart_form" action="'.$cart_action.'"><div id="ncm_container"></div></form>';

    }



    function ncm_cart_func() {

        if( $this->ncm_get_cart_products() ) {

            ncm_get_template("ncm-cart");

        } else {

            ncm_get_template("ncm-cart-empty");

        }

        die;

    }



    function ncm_get_cart_pessenger_details() {

        global $wp_session;

        
        $ncm_data = array( 'ncm_booking' => array(), 'passenger_info' => array() , 'comment' => array());


        if(!session_id()) {

            session_start();

        }

        $passenger_detail = array();

        

        if( isset( $_SESSION['NCM_Cart_Passenger_Info'] ) ) {

            $passenger_detail = $this->ncm_cookie_decode( $_SESSION['NCM_Cart_Passenger_Info'] );

        } else if( isset( $wp_session['NCM_Cart_Passenger_Info'] ) ) {

            $passenger_detail = $this->ncm_cookie_decode( $wp_session['NCM_Cart_Passenger_Info'] );

        }



        if( isset( $passenger_detail['ncm_booking'] ) && !empty( $passenger_detail['ncm_booking'] ) ) {

            $ncm_data['ncm_booking'] = $passenger_detail['ncm_booking'];

        }



        if( isset( $passenger_detail['passenger_info'] ) && !empty( $passenger_detail['passenger_info'] ) ) {

            $ncm_data['passenger_info'] = $passenger_detail['passenger_info'];

        }

        if( isset( $passenger_detail['comment'] ) && !empty( $passenger_detail['comment'] ) ) {

            $ncm_data['comment'] = $passenger_detail['comment'];

        }


        return $ncm_data;

    }



    function ncm_cart_submit() {

        global $ncm_payment_gateways, $wp_session;

        if(!session_id()) {

            session_start();

        }



        if( $this->ncm_is_cart() && isset( $_POST['ncm_cart'] ) ) {

        

            $checkout_page = $ncm_payment_gateways->ncm_get_checkout_page_link();

            $post_data = $_POST;

            $ncm_data = array( 'ncm_booking' => array(), 'passenger_info' => array(), 'comment' => array() );

            //echo $post_data['comment']; exit();

            if( isset( $post_data['ncm_booking'] ) && !empty( $post_data['ncm_booking'] ) ) {

                $ncm_data['ncm_booking'] = json_decode( stripslashes( $post_data['ncm_booking'] ), true );

            }



            if( isset( $post_data['passenger_info'] ) && !empty( $post_data['passenger_info'] ) ) {

                $ncm_data['passenger_info'] = $post_data['passenger_info'];

            }

            if( isset( $post_data['comment'] ) && !empty( $post_data['comment'] ) ) {

                $ncm_data['comment'] = $post_data['comment'];

            }



            $cookie_val = $this->ncm_cookie_encode( $ncm_data );

            $_SESSION['NCM_Cart_Passenger_Info'] = $cookie_val;

            $wp_session['NCM_Cart_Passenger_Info'] = $cookie_val;

            wp_redirect( $checkout_page );

        } 

    }



    function ncm_cookie_encode( $ncm_value = '' ) {
        /*echo "<pre>";
        print_r($ncm_value);
        die();*/
        return base64_encode( json_encode( $ncm_value ) ); 

    }



    function ncm_cookie_decode( $ncm_value = '' ) {

        return json_decode( base64_decode( $ncm_value ), true ); 

    }



    function ncm_clear_cart_products() {

        if( isset( $_COOKIE['NCM_Cart'] ) ) {

            setcookie('NCM_Cart', null, -1, '/');
            setcookie('NCM_adjustdiscount', null, -1, '/');
            setcookie('NCM_adjustamount', null, -1, '/');

        }

        if( isset( $_SESSION['NCM_Cart_Passenger_Info'] ) ) {

            unset( $_SESSION['NCM_Cart_Passenger_Info'] );

        }

    }



    function ncm_get_cart_products() {

        global $ncm_payment_gateways;

        $ncm_cart = false;

        if( isset( $_COOKIE['NCM_Cart'] ) ) {

            $ncm_cart = $this->ncm_cookie_decode( stripslashes( $_COOKIE['NCM_Cart'] ), true );
            /*echo '<pre>';
            print_r($ncm_cart); die();*/

            if( !empty( $ncm_cart ) ) {

                $cart_items = array();

                foreach($ncm_cart as $cart_data) {

                    if( !empty( $cart_data['ncm_post_id'] ) && is_numeric( $cart_data['ncm_post_id'] ) ) {

                        $post_id = isset($cart_data['ncm_post_id']) ? $cart_data['ncm_post_id'] : 0;

                        $booking_date = isset($cart_data['ncm_booking_date']) ? $cart_data['ncm_booking_date'] : 0;

                        $narnoo_bdate = isset($cart_data['ncm_narnoo_bdate']) ? $cart_data['ncm_narnoo_bdate'] : 0;

                        $booking_code = isset($cart_data['ncm_booking_code']) ? $cart_data['ncm_booking_code'] : 0;

                        $pickup = isset($cart_data['ncm_pickup']) ? $cart_data['ncm_pickup'] : 0;

                        $dropoff = isset($cart_data['ncm_dropoff']) ? $cart_data['ncm_dropoff'] : 0;

                        $passenger = isset($cart_data['ncm_passenger']) ? $cart_data['ncm_passenger'] : 0;

                        $operator_id = get_post_meta( $post_id, 'narnoo_operator_id', true );

                        $product_id = get_post_meta( $post_id, 'narnoo_booking_id', true );

                        $comment = isset($cart_data['comment']) ? $cart_data['comment'] : '';
                        

                        $cart_item = array();

                        $cart_item['post_id']      = $post_id;

                        $cart_item['operator_id']  = $operator_id;

                        $cart_item['product_id']   = $product_id;

                        $cart_item['booking_date'] = $booking_date;

                        $cart_item['narnoo_bdate'] = $narnoo_bdate;

                        $cart_item['booking_code'] = $booking_code;

                        $cart_item['pickup']       = $pickup;

                        $cart_item['dropoff']      = $dropoff;

                        $cart_item['passenger']    = json_decode($passenger, true);

                        $cart_item['comment']    = $comment;



                        $cart_items[] = $cart_item;

                    }

                }

                if( $ncm_payment_gateways->ncm_is_multiple_cart() ) {

                    $ncm_cart = $cart_items;

                } else {

                    $ncm_cart[] = end($cart_items);

                }

            }       

        }

        return $ncm_cart;

    }



    function ncm_get_pickup_location( $booking_data = array() ) {

        $pickup_location = array("" => __("Please Select", NCM_txt_domain));

        $pickups = array();

        $price = '';

        if(isset( $booking_data['productPickUps']) && !empty($booking_data['productPickUps']) ) {

            $pickups = $booking_data['productPickUps'];

        }


        if( !empty($pickups) ) {

            foreach( $pickups as $pickup_id => $pickup ) {

                //if(!in_array($pickup_id, array( 'formName' ) ) ) {

                    $id = ( isset($pickup['id']) && !empty($pickup['id']) ) ? $pickup['id'] : $pickup_id;

                    $label = ( isset($pickup['label']) && !empty($pickup['label']) ) ? $pickup['label'] : $id;

                    $price = ( isset($pickup['price']) && !empty($pickup['price']) ) ? $pickup['price'] : $price;

                    //$pickup_location[$id] = $label;
                    $pickup_location[$id] = array($label, $price);

                //}

            }

        }

        return $pickup_location;

    }



    function ncm_get_fields( $cart_row_id, $booking_data = array() ) {

        global $ncm_controls;

        $ncm_booking_data = array();

        $ncm_passenger_data = array();

        /*echo '<pre>';
        print_r($ncm_booking_data);
        echo '</pre>';*/

        $fields = ( isset( $booking_data['bookingFields'] ) && !empty( $booking_data['bookingFields'] ) ) ? $booking_data['bookingFields'] : array();



        // get booking fields from api

        $booking_fields = ( isset( $fields['perBooking'] ) && !empty( $fields['perBooking'] ) ) ? $fields['perBooking'] : array();

        $booking_field_count = ( isset( $booking_data['bookingFieldsPerBookingCount'] ) && $booking_data['bookingFieldsPerBookingCount'] > 0 ) ? $booking_data['bookingFieldsPerBookingCount'] : 0;

        if( $booking_field_count > 0 && !empty( $booking_fields ) ) {

            $ncm_booking_data = $booking_fields;

        }



        // set the above booking fields as default

        if( isset( $fields['default'] ) && $fields['default'] ) {

            $ncm_booking_data['ncm_narnoo_default'] = $fields['default'];

        }



        // get passenger fields from api

        /*echo '<pre>';
        print_r($fields['perParticipant']);
        echo '</pre>';*/

        $passenger_fields = ( isset( $fields['perParticipant'] ) && !empty( $fields['perParticipant'] ) ) ? $fields['perParticipant'] : array();

        $pessagner_fields_count = ( isset($booking_data['bookingFieldsPerParticipantCount']) && $booking_data['bookingFieldsPerParticipantCount'] > 0 ) ? $booking_data['bookingFieldsPerParticipantCount'] : 0;

        if( $pessagner_fields_count > 0 && !empty( $passenger_fields ) ) {

            $ncm_passenger_data = $passenger_fields;

        }



        return array( 'booking_fields' => $ncm_booking_data, 'passenger_fields' => $ncm_passenger_data, );

    }



    function ncm_set_passenger_type( $cart_data, $cart_row_id, $tour_names, $ncm_booking_data = array(), $ncm_fields = array() ) {

        global $ncm_narnoo_helper, $ncm;

        /*echo '<pre>';
        print_r($ncm_booking_data);
        echo '</pre>';*/


        $passenger_type = array();

        $post_id      = isset($cart_data['post_id']) ? $cart_data['post_id'] : '0';

        $operator_id  = isset($cart_data['operator_id']) ? $cart_data['operator_id'] : '0';

        $product_id   = isset($cart_data['product_id']) ? $cart_data['product_id'] : '0';

        $booking_date = isset($cart_data['booking_date']) ? $cart_data['booking_date'] : '0';

        $booking_code = isset($cart_data['booking_code']) ? $cart_data['booking_code'] : '0';

        $pickup       = isset($cart_data['pickup']) ? $cart_data['pickup'] : '0';

        $dropoff      = isset($cart_data['dropoff']) ? $cart_data['dropoff'] : '0';

        $passenger    = isset($cart_data['passenger']) ? $cart_data['passenger'] : '0';

        //echo 'pik====>'.$pickup;
        if (is_array($ncm_booking_data['productPickUps']) || is_object($ncm_booking_data['productPickUps'])){

            foreach($ncm_booking_data['productPickUps'] as $keyp => $valuep){
                
                /*echo '<pre>';
                print_r($valuep);
                echo '</pre>';*/
                
                if($valuep['id'] == $pickup){
                    //echo 'Yes';
                    $pickuplocval = $valuep['price'];  
                }
            }
        }
        //echo 'pickval=>>>>>'.$pickuplocval;

        $passenger_info = array();

        if( !empty($booking_code) ) {



            $booking_end_date = date( 'd-m-Y', strtotime( $booking_date. "+1 days" ) );

            

            $result_available = $ncm_narnoo_helper->ncm_product_availability( $operator_id, $product_id, $booking_date, $booking_end_date, $booking_code );

            $result = json_decode( $result_available, true );


            $product_available = isset($result['productAvailability'][0]) ? $result['productAvailability'][0] : array();

            $availability = isset($product_available['availability']) ? $product_available['availability'] : 0;

            $availability_option = array_combine(range(0,$availability), range(0,$availability));

            /*echo '<pre>';
            print_r($ncm_booking_data['productPrices']);
            echo '</pre>';*/

            $ncm_product_prices = array();

            if( isset($ncm_booking_data['productPrices']) && !empty($ncm_booking_data['productPrices']) ) {

                foreach( $ncm_booking_data['productPrices'] as $price ) {

                    /*echo '<pre>';
                    print_r($price);
                    echo '</pre>';*/

                    $passenger_id = isset($price['id']) ? $price['id'] : 0;

                    $pax = isset($price['pax']) ? $price['pax'] : 1;

                    $group = !empty($price['group']) ? $price['group'] : 'false';
                    //$group = $price['group'];
                    //echo 'Group-->'.$price['minQuantity'];
                    
                    $maxQuantity = !empty($price['maxQuantity']) ? $price['maxQuantity'] : 'null';

                    $ncm_product_prices[$passenger_id] = $pax;

                }

            }

            //echo 'Group-->'.$maxQuantity;



            $subtotal = 0;

            $levy = 0;

            //$pickuplocval = 0;

            if( isset($product_available['price']) && !empty($product_available['price']) ) {



                foreach( $product_available['price'] as $type ) {


                    /*echo '<pre>';
                    print_r($type);
                    echo '</pre>';*/

                    $passengers = array();

                    $passenger_id = isset($type['id']) ? $type['id'] : 0;

                    $passengers['id'] = $passenger_id;

                    $passengers['label'] = isset($type['label']) ? $type['label'] : $passenger['id'];

                    $passengers['value'] = ( is_array($passenger) && array_key_exists( $passengers['id'], $passenger ) ) ? $passenger[$passengers['id']] : 0;

                    $passengers['price'] = isset($type['price']) ? $type['price'] : 0;

                    $passengers['pax'] = isset($ncm_product_prices[$passenger_id]) ? $ncm_product_prices[$passenger_id] : 1;

                    $passengers['levy'] = isset($type['levy']) ? $type['levy'] : 0;

                    $passengers['commission'] = isset($type['commission']) ? $type['commission'] : 0;

                    $passengers['group'] = $group;

                    $passengers['maxQuantity'] = $maxQuantity;

                    


                    $passenger_type[] = $passengers;

                    //echo 'Group Val->>>'.$group;


                    if($group == 1){
                        if($passengers['value'] == 0 ){
                            $passengers['value'] = 0;
                        }elseif($passengers['value'] >= 1){
                            $passengers['value'] = 1;
                        }
                    }elseif($group == false){
                        $passengers['value'] = $passengers['value'];
                    }else{
                        $passengers['value'] = $passengers['value'];
                    }

                    //echo 'Pas val-->'.$passengers['value'].'<br/>';

                    $levy     = $levy + ( $passengers['value'] * $passengers['levy'] );

                    $subtotal = $subtotal + ( $passengers['value'] * $passengers['price'] );

                    //$subtotal = $subtotal + $pickuplocval;

                    //echo $passengers['value'];

                    //$pickuplocval = $passengers['value'] * 25;
                    //echo 'pric==>'.$passengers['price'].'<br/>';


                }

            }
            
            

            $passenger_info['tour_name'] = $tour_names;

            $passenger_info['availability_option'] = $availability_option;

            $passenger_info['passenger_types'] = $passenger_type;

            /*echo '<pre>';
            print_r($passenger_type);
            echo '</pre>';*/

            $subpescount = 0;
            foreach($passenger_type as $key => $datap){
                //echo $datap['value'];
                if($datap['value'] >= 1){
                     $subpescount = $subpescount + $datap['value'];
                }
            }
            //echo $subpescount;

            //$passenger_info['subtotal'] = $subtotal;
            $pickuplocval = $pickuplocval * $subpescount;
            if(!empty($pickuplocval)){
                $passenger_info['subtotal'] = $subtotal + $pickuplocval;
            }else{
                $passenger_info['subtotal'] = $subtotal;
            }

            $passenger_info['levy'] = $levy;

            $passenger_info['picklocationprice'] = $pickuplocval;


            //$passenger_info['total'] = $subtotal; // + $levy;
            //$passenger_info['total'] = $subtotal + $levy;

            if(!empty($pickuplocval)){
                $passenger_info['total'] = $subtotal + $levy + $pickuplocval;
            }else{
                $passenger_info['total'] = $subtotal + $levy;
            }


            $passenger_info['ncm_res_availability'] = $result_available;

            $passenger_info['passenger_fields'] = isset($ncm_fields['passenger_fields']) ? $ncm_fields['passenger_fields'] : array();


        }

        $this->passenger_type[$cart_row_id] = $passenger_info;

    }



    function ncm_get_passenger() {

        global $ncm_controls, $ncm_settings;

        $passengers_info = $this->passenger_type;

        /*echo '<pre>';
        print_r($passengers_info);
        echo '</pre>';*/


        $passengers_data = array();

        if( !empty($passengers_info) && is_array($passengers_info) ) {

            foreach( $passengers_info as $cart_row_id => $passenger_info ) {


                $availability_option = isset($passenger_info['availability_option']) ? $passenger_info['availability_option'] : '';

                /*echo '<pre>';
                print_r($passenger_info);
                echo '</pre>';*/

                $passenger_types = isset($passenger_info['passenger_types']) ? $passenger_info['passenger_types'] : '';


                $passenger_type = array();

                $booking_info_fields = array();

                $passenger_fields_label = array();

                if( !empty($passenger_types) && is_array($passenger_types) ) {

                    foreach ($passenger_types as $key => $passenger) {

                        /*echo '<pre>';
                        print_r($passenger);
                        echo '</pre>';*/

                        $id = isset($passenger['id']) ? $passenger['id'] : 0;

                        $label = isset($passenger['label']) ? $passenger['label'] : 0;

                        $value = isset($passenger['value']) ? $passenger['value'] : 0;

                        $price = isset($passenger['price']) ? $passenger['price'] : 0;

                        $pax = isset($passenger['pax']) ? $passenger['pax'] : 0;

                        //$levy = ''; //  isset($passenger['levy']) ? $passenger['levy'] : 0;
                        $levy = isset($passenger['levy']) ? $passenger['levy'] : 0;


                        $commission = isset($type['commission']) ? $type['commission'] : 0;

                        $group = $passenger['group'];
                        
                        $maxQuantity = $passenger['maxQuantity'];

                        //echo 'Group->>>'.$group;


                        $name = 'passenger_'.$cart_row_id.'_'.$id;


                        //echo 'max qty->>'.$maxQuantity;

                        if($maxQuantity == 'null'){
                            $availability_option = $availability_option;
                        }else{
                            $ncm_maxqty = 0;
                            $maxQuantityarray = array();
                            while($ncm_maxqty <= $maxQuantity) {
                              $maxQuantityarray[] = $ncm_maxqty;
                              $ncm_maxqty++;
                            } 

                            $availability_option = $maxQuantityarray;
                        }

                        
                        $control = $ncm_controls->ncm_control(

                                    array(

                                        "type" => "select",

                                        "name" => $name,

                                        "id" => $name,

                                        "value" => $value,

                                        "class" => "ncm_select form-control ncm_passenger ncm_passenger_".$cart_row_id,

                                        "options" => $availability_option,
                                        
                                        "data-ncm_price" => $price,

                                        "data-ncm_levy" => $levy,

                                        "data-ncm_pax" => $pax,

                                        "data-ncm_commission" => $commission,

                                        "data-ncm_group" => $group,
                                        
                                        "data-ncm_maxQuantity" => $maxQuantity,

                                        "data-ncm_subtotal_id" => "product_subtotal_".$cart_row_id,

                                        "data-ncm_levy_id" => "product_levy_".$cart_row_id,

                                        "data-ncm_total_id" => "product_total_".$cart_row_id,

                                        "data-ncm_cart_row_id" => $this->ncm_string_to_div_class( $cart_row_id ),

                                        "data-passenger_id" => $id,

                                        "data-ncm_passenger_label" => $label,

                                        "data-ncm_passenger_fields" => "product_passenger_fields_".$cart_row_id,

                                    )

                                );



                        if( isset( $passenger_info['passenger_fields'] ) && !empty( $passenger_info['passenger_fields'] ) ) {

                            $control.= $ncm_controls->ncm_control(

                                        array(

                                            "type" => "textarea",

                                            "name" => "ncm_passenger_fields_".$cart_row_id,

                                            "id" => $this->ncm_string_to_div_class( "ncm_passenger_fields_".$cart_row_id ),

                                            "value" => json_encode( $passenger_info['passenger_fields'] ),

                                            "class" => "form-control ncm_passenger_fields ncm_passenger_fields".$cart_row_id,

                                            "style" => "display:none;",

                                        )

                                    );

                            $passenger_fields_label = array_column( $passenger_info['passenger_fields'], 'label' );

                            unset($passenger_info['passenger_fields']);

                        }

                        $passenger_type[] = array(

                                "label" => $label,

                                "control" => $control,

                                "price" => $ncm_settings->ncm_display_price($price),

                                "levy" => $levy
                                //"levy" => $ncm_settings->ncm_display_price($levy)

                            );


                    }

                }



                $passenger_data = array();

                $passenger_data['tour_name'] = isset($passenger_info['tour_name']) ? $passenger_info['tour_name'] : '';

                $passenger_data['fields'] = $passenger_type;

                $passenger_data['passenger_fields'] = $passenger_fields_label;

                $passenger_data['passenger_field_container'] = $this->ncm_string_to_div_class("product_passenger_fields_".$cart_row_id);

                $passengers_data[$cart_row_id] = $passenger_data;

            }

        }

        return $passengers_data;

    }



    function ncm_cart_product_info() {

        global $ncm_narnoo_helper;

        if ( ! $products = wp_cache_get( 'ncm_cart_product_info', 'ncm_data' ) ) {

            $products = array();

            if( $ncm_cart = $this->ncm_get_cart_products() ) {

                //echo '<pre>';
                //print_r($ncm_cart); die();
                
                foreach ( $ncm_cart as $cart_data ) {

                    $post_id      = $cart_data['post_id'];

                    $operator_id  = $cart_data['operator_id'];

                    $product_id   = $cart_data['product_id'];

                    $booking_date = $cart_data['booking_date'];

                    $narnoo_bdate = $cart_data['narnoo_bdate'];

                    $booking_code = $cart_data['booking_code'];

                    $pickup       = $cart_data['pickup'];

                    $dropoff      = $cart_data['dropoff'];

                    $passenger    = $cart_data['passenger'];

                    $post_data    = get_post( $post_id );

                    $tour_name    = get_the_title( $post_id );

                    $cart_row_id  = $post_id."_".$booking_date;



                    if($post_data) {

                        $ncm_product_booking = $ncm_narnoo_helper->ncm_product_booking( $operator_id, $product_id, $booking_code);



                        $ncm_product_booking_data = json_decode($ncm_product_booking, true);


                        $ncm_booking_data = isset( $ncm_product_booking_data['bookingData'] ) ? $ncm_product_booking_data['bookingData'] : array();


                        $ncm_fields = $this->ncm_get_fields($cart_row_id, $ncm_booking_data);



                        $this->ncm_set_passenger_type( $cart_data, $cart_row_id, $tour_name, $ncm_booking_data, $ncm_fields );



                        $pickup_options = $this->ncm_get_pickup_location($ncm_booking_data); 



                        if( !empty($pickup_options) ) {

                            $default_pickup_keys = array_keys($pickup_options);

                            $default_pickup_value = array_shift( $default_pickup_keys );

                            if( empty($pickup) ) {

                                $pickup = $default_pickup_value;

                            }

                            if( empty($dropoff) ) {

                                $dropoff = $default_pickup_value;

                            }

                        }

                        foreach ($pickup_options as $keypick => $valuepick) {
                            if($keypick == $pickup){
                                $pickuplocval = $valuepick[1];  
                            }
                        }

                        $ncm_is_live = isset( $ncm_booking_data['isLive'] ) ? $ncm_booking_data['isLive'] : true;



                        $products[$cart_row_id]['post_id'] = $post_id;

                        $products[$cart_row_id]['booking_date'] = $booking_date;

                        $products[$cart_row_id]['narnoo_bdate'] = $narnoo_bdate;

                        $products[$cart_row_id]['booking_code'] = $booking_code;

                        $products[$cart_row_id]['product_id'] = $product_id;

                        $products[$cart_row_id]['tour_name'] = $tour_name;

                        $products[$cart_row_id]['pickup_dropoff_option'] = $pickup_options;

                        $products[$cart_row_id]['pickup_value'] = $pickup;
                        $products[$cart_row_id]['pickup_price'] = $pickuplocval;

                        $products[$cart_row_id]['dropoff_value'] = $dropoff;

                        $products[$cart_row_id]['ncm_res_booking'] = $ncm_product_booking;

                        $products[$cart_row_id]['ncm_booking_fields'] = $ncm_fields['booking_fields'];

                        $products[$cart_row_id]['ncm_is_live'] = $ncm_is_live;

                    }

                }

            }

            wp_cache_add( 'ncm_cart_product_info', $products, 'ncm_data' );

        }

        return $products;

    }



    function ncm_cart_product_func() {

        global $ncm_narnoo_helper, $ncm_controls, $ncm_settings, $ncm_cart;

        $products_info = $this->ncm_cart_product_info();

        /*echo '<pre>';
        print_r($products_info);
        echo '</pre>';*/

        $ncm_booking_fields = array();

        if( !empty($products_info) && is_array($products_info) ) {

            foreach ( $products_info as $cart_row_id => $product ) {         


                $post_id = isset($product['post_id']) ? $product['post_id'] : '';

                $booking_date = isset($product['booking_date']) ? $product['booking_date'] : '';

                $product_id = isset($product['product_id']) ? $product['product_id'] : '';

                $tour_name = isset($product['tour_name']) ? $product['tour_name'] : '';

                $pickup = isset($product['pickup_value']) ? $product['pickup_value'] : '';

                $dropoff = isset($product['dropoff_value']) ? $product['dropoff_value'] : '';

                $levy = isset($product['levy']) ? $product['levy'] : '';

                $pickup_options = isset($product['pickup_dropoff_option']) ? $product['pickup_dropoff_option'] : '';

                $booking_fields = isset($product['ncm_booking_fields']) ? $product['ncm_booking_fields'] : array();


                //echo $product['pickup_dropoff_option']$product['pickup_value'];
                foreach ($product['pickup_dropoff_option'] as $key => $value) {
                    if($key == $pickup){
                        $pickpir = $value[1];  
                    }
                }
                //echo 'pickprice===>>'.$pickpir;
                //setcookie('NCM_pickuplocationprice', $pickpir, 0, '/');


                $tour_date = date("l dS F Y", strtotime( $booking_date ) );

                if( date("H:i:s", strtotime( $booking_date ) ) != "00:00:00" ) {

                    //echo 'booking date-'.$booking_date;
                    //$tour_date = date("l dS F Y H:i:s", strtotime( $booking_date ) );
                    $tour_date = $booking_date;

                }

                $ncm_data_val = 'data-val="Please select"';

                $pickup_location_value = $dropoff_location_value = __('Select on checkout', NCM_txt_domain);

                if( count( $pickup_options ) > 1 ) {


                    $ncm_data_val = '';

                    $pick_loc_price = "";

                    $pickup_location_value = $ncm_controls->ncm_control(

                                array(

                                    "type" => "select",

                                    "name" => "ncm_pickup_location_".$cart_row_id,

                                    "id" => "ncm_pickup_location_".$cart_row_id,

                                    "value" => $pickup,

                                    "class" => "ncm_select form-control ncm_pickup_location",

                                    "options" => $pickup_options,

                                    "data-ncm_post_id" => $post_id,

                                    "data-ncm_booking_date" => $booking_date,

                                    "data-ncm_pick_loc_price" => $pickpir,
                                    //"data-ncm_pick_loc_price" => $pick_loc_price,

                                    "data-error_required" => __("Please select pickup location.", NCM_txt_domain),

                                )

                            );

                    $dropoff_location_value = $ncm_controls->ncm_control(

                                array(

                                    "type" => "select",

                                    "name" => "ncm_dropoff_location_".$cart_row_id,

                                    "id" => "ncm_dropoff_location_".$cart_row_id,

                                    "value" => $dropoff,

                                    "class" => "ncm_select form-control ncm_dropoff_location",

                                    "options" => $pickup_options,

                                    "data-ncm_post_id" => $post_id,

                                    "data-ncm_booking_date" => $booking_date,

                                    "data-error_required" => __("Please select dropoff location.", NCM_txt_domain),

                                )

                            );

                }

                $pickup_location_value = '<span class="ncm_pickup" ' . $ncm_data_val . '>'. $pickup_location_value .'</span>';

                $dropoff_location_value = '<span class="ncm_dropoff" ' . $ncm_data_val . '>'. $dropoff_location_value .'</span>';

                

                $ncm_booking_fields[$cart_row_id] = $booking_fields;

            

                $ncm_remove_elem = $this->ncm_string_to_div_class('ncm_passenger_'.$cart_row_id);

                $rempove_link_attr = ' href="javascript:void(0);"';

                $rempove_link_attr.= ' id="ncm_remove_cart_item"';

                $rempove_link_attr.= ' class="ncm_remove_cart_item"';

                $rempove_link_attr.= ' data-ncm_post_id="'.$post_id.'"';

                $rempove_link_attr.= ' data-ncm_booking_date="'.$booking_date.'"';

                $rempove_link_attr.= ' data-ncm_remove_elem="'.$ncm_remove_elem.'"';



                $remove_cart_item_link ='<a '.$rempove_link_attr.'>';

                $remove_cart_item_link.='<i class="ncm_fa ncm_fa-times-circle-o ncm_fa-2x"></i>';

                $remove_cart_item_link.='<i class="ncm_fa ncm_fa-times-circle ncm_fa-2x"></i></a>';



                // item removed using this class

                $product_info[$cart_row_id]['attr'] = ' class="'.$ncm_remove_elem.'" ';



                $product_info[$cart_row_id]['action'] = array(

                            "label" => __("Action", NCM_txt_domain),

                            "value" => $remove_cart_item_link

                        );



                $product_info[$cart_row_id]['tour_code'] = array(

                            "label" => __("Tour Code",NCM_txt_domain),

                            "value" => $product_id

                        );

                $product_info[$cart_row_id]['tour_name'] = array(

                            "label" => __("Tour Name",NCM_txt_domain),

                            "value" => $tour_name

                        );

                $product_info[$cart_row_id]['tour_date'] = array(

                            "label" => __("Travel Date Time",NCM_txt_domain),

                            "value" => $tour_date

                        );



                $product_info[$cart_row_id]['pickup_location'] = array(

                            "label" => __('Pickup Location', NCM_txt_domain),

                            "value" => $pickup_location_value

                        );



                $product_info[$cart_row_id]['dropoff_location'] = array(

                            "label" => __('Dropoff Location', NCM_txt_domain),

                            "value" => $dropoff_location_value

                        );



                $product_subtotal_id = $this->ncm_string_to_div_class('product_subtotal_'.$cart_row_id);

                $product_info[$cart_row_id]['subtotal'] = array(

                            "label" => __('Subtotal', NCM_txt_domain),

                            "value" => '<span id="'.$product_subtotal_id.'">'.$ncm_settings->ncm_display_price().'</span>'

                        );

                $product_levy_id = $this->ncm_string_to_div_class('product_levy_'.$cart_row_id);
                $product_info[$cart_row_id]['levy'] = array(

                            "label" => __('levy', NCM_txt_domain),

                            "value" => '<span id="'.$product_levy_id.'">'.$ncm_settings->ncm_display_price().'</span>'

                        );

                $product_total_id = $this->ncm_string_to_div_class('product_total_'.$cart_row_id);
                $product_info[$cart_row_id]['total'] = array(

                            "label" => __('Total', NCM_txt_domain),

                            "value" => '<span id="'.$product_total_id.'">'.$ncm_settings->ncm_display_price().'</span>'

                        );

            }

            $product_info['ncm_booking_fields'] = $ncm_booking_fields;

        }


        return $product_info;

    }



    function ncm_cart_display_items_func() {

        global $ncm_controls;

        $products = $this->ncm_cart_product_func();

        if( isset( $products['ncm_booking_fields'] ) && !empty( $products['ncm_booking_fields'] ) ) {

            echo $ncm_controls->ncm_control(

                        array(

                            "type" => "textarea",

                            "name" => "ncm_booking",

                            "id" => "ncm_booking",

                            "value" => json_encode( $products['ncm_booking_fields'] ),

                            "class" => "form-control ncm_booking_fields",

                            "style" => "display:none;",

                        )

                    );

            unset($products['ncm_booking_fields']);

        }

        

        foreach( $products as $product ) {

            ncm_get_template("ncm-cart-item", $product);

        }

    }



    function ncm_cart_display_passanger_func() {

        $ncm_passenger = $this->ncm_get_passenger();

        /*echo '<pre>';
        print_r($ncm_passenger);
        echo '</pre>';*/

        foreach ($ncm_passenger as $row_id => $ncm_product_field) {

            if( isset($ncm_product_field['fields']) && !empty($ncm_product_field['fields']) ) {

                // item removed using this class

                $ncm_passenger_class = $this->ncm_string_to_div_class('ncm_passenger_'.$row_id);

                echo '<div class="'.$ncm_passenger_class.'">';

                ncm_get_template("ncm-cart-passenger", $ncm_product_field);

                echo '</div>';

            }

        }

    }



    function ncm_continue_shopping_func( $link_text ) {

        global $ncm_payment_gateways;

        $shop_link = get_post_type_archive_link( 'narnoo_product' );;

        echo '<a class="btn btn-primary" href="'.$shop_link.'">' . $link_text . '</a>';

    }



    function ncm_proceed_to_checkout_func( $link_text ) {

        echo '<input type="submit" class="btn btn-primary" name="ncm_cart" id="ncm_cart" value="'.$link_text.'" />';

    }



    function ncm_is_cart() {

        global $ncm_shortcode;

        return $ncm_shortcode->ncm_has_shortcode( 'ncm_cart' );

    }



    function ncm_get_subtotal() {

        global $ncm_settings;

        return '<span id="product_subtotal_'.$cart_row_id.'">'.$ncm_settings->ncm_display_price().'</span>';

    }



    function ncm_get_total() {

        global $ncm_settings;

        return '<span id="product_total_'.$cart_row_id.'">'.$ncm_settings->ncm_display_price().'</span>';

    }



}



global $ncm_cart;

$ncm_cart = new NCM_Cart();



}





if ( ! function_exists( 'ncm_cart_subtotal' ) ) :

    function ncm_cart_subtotal () {

        global $ncm_cart, $ncm_settings;

        $ncm_subtotal = array_sum(array_column($ncm_cart->passenger_type, 'subtotal'));

        $ncm_levy = array_sum(array_column($ncm_cart->passenger_type, 'levy'));
        
        $ncm_picklocation = array_sum(array_column($ncm_cart->passenger_type, 'picklocationprice'));

        //$ncm_subtotal = $ncm_subtotal + $ncm_levy + $ncm_picklocation;
        $ncm_subtotal = $ncm_subtotal + $ncm_levy;


        echo '<span class="ncm_cart_subtotal" id="ncm_cart_subtotal">'.$ncm_settings->ncm_display_price($ncm_subtotal).'</span>';

    }

endif;



if ( ! function_exists( 'ncm_cart_total' ) ) :

    function ncm_cart_total () {

        global $ncm_cart, $ncm_settings;

        $ncm_total = array_sum(array_column($ncm_cart->passenger_type, 'total'));

        $ncm_levy = array_sum(array_column($ncm_cart->passenger_type, 'levy'));

        $ncm_picklocation = array_sum(array_column($ncm_cart->passenger_type, 'picklocationprice'));



        //$ncm_total = $ncm_total + $ncm_picklocation;
        $ncm_total = $ncm_total - $ncm_levy;
        //echo 'pickprice=>>'.$ncm_picklocation.'---Total====>>'.$ncm_total;
       
        echo '<span class="ncm_cart_total" id="ncm_cart_total">'.$ncm_settings->ncm_display_price($ncm_total).'</span>';

    }

endif;

if ( ! function_exists( 'ncm_cart_leviestotal' ) ) :

    function ncm_cart_leviestotal () {

        global $ncm_cart, $ncm_settings;

        $levy_total = array_sum(array_column($ncm_cart->passenger_type, 'levy'));

        echo '<span class="ncm_cart_levytotal" id="ncm_cart_levytotal">'.$ncm_settings->ncm_display_price($levy_total).'</span>';

    }

endif;



?>