<?php

/*
* The order functions are here. Manage all order functionality
*/

if( !class_exists ( 'NCM_Order' ) ) {







class NCM_Order {



    



    function __construct(){







        add_action( "wp", array( $this, "ncm_order_sessions" ), 10 );







        add_action( "ncm_order_products", array( $this, "ncm_order_products_func" ), 10 );







        add_action( "ncm_order_passenger", array( $this, "ncm_order_passenger_func" ), 10, 1 );







    }







    function ncm_is_order() {



        global $ncm_shortcode;



        return $ncm_shortcode->ncm_has_shortcode( 'ncm_order' );



    }







    function ncm_order_sessions() {



        global $ncm_cart;



        if( $this->ncm_is_order( ) ) {



            if( $this->ncm_get_order_id() ) {



                $ncm_cart->ncm_clear_cart_products();



            }



        } else if( $this->ncm_get_order_id() ){



            //setcookie('NCM_Order_Id', null, -1, '/');



        }



    }







    function ncm_set_order_id( $order_id ) {



        global $ncm_cart;



        if( !empty( $order_id ) ) {



            $order_id = $ncm_cart->ncm_cookie_encode( $order_id );



            setcookie('NCM_Order_Id', $order_id, 0, '/');



        }



    }







    function ncm_get_order_id() {



        global $ncm_cart;



        if( isset($_COOKIE['NCM_Order_Id']) && !empty($_COOKIE['NCM_Order_Id']) ) {



            return $ncm_cart->ncm_cookie_decode( $_COOKIE['NCM_Order_Id'] );



        } else {



            return false;



        }



    }







    function ncm_get_order_details( $order_id ) {



        global $ncm_settings, $ncm, $wpdb; 



        $order_data = array();



        if( !empty($order_id) ) {



            $all_country = $ncm_settings->ncm_country();


            $gateway_name = get_post_meta( $order_id, 'ncm_gateway_name', true );



            $currency = get_post_meta( $order_id, 'ncm_currency', true );



            $subtotal = get_post_meta( $order_id, 'ncm_subtotal', true );

           
            $levy = get_post_meta( $order_id, 'ncm_levy', true );



            $total = get_post_meta( $order_id, 'ncm_total', true );

            if(get_post_meta( $order_id, 'ncm_adjustdiscount', true )){
                $discount = get_post_meta( $order_id, 'ncm_adjustdiscount', true );
            }else{
                $discount = 0;
            }






            $order_data['ncm_order_id'] = $order_id;



            $order_data['ncm_order_date'] = get_the_date( '', $order_id );


            $order_data['ncm_gateway_name'] = $gateway_name;



            $order_data['ncm_currency_code'] = $currency;



            $order_data['ncm_currency_symbol'] = $ncm_settings->ncm_get_currency_symbol( $currency );



            $order_data['ncm_subtotal'] = $ncm_settings->ncm_display_price( $subtotal );



            $order_data['ncm_levy'] = $ncm_settings->ncm_display_price( $levy );



            $order_data['ncm_total'] = $ncm_settings->ncm_display_price( $total );

            $order_data['ncm_discount'] = $ncm_settings->ncm_display_price( $discount );



            $order_data['user_data'] = $this->ncm_get_order_booking_data( $order_id );



        }



        return $order_data;



    }







    function ncm_get_order_item( $order_id ) {



        global $ncm, $wpdb; 



        $order_items = array();



        if( !empty($order_id) ) {



            $order_items = $wpdb->get_results( "SELECT order_item_id, order_id, post_id, product_id, booking_code, reservation_code, reservation_provider, tour_name, travel_date, t_date, pickup, dropoff, passenger, subtotal, levy, total FROM `".$ncm->tbl_order_item."` WHERE order_id = ".$order_id, ARRAY_A );



        }



        return $order_items;



    }







    function ncm_get_order_booking_data( $order_id ) {



        global $ncm, $wpdb; 



        $order_data = array();



        if( !empty($order_id) ) {



            $order_booking = $wpdb->get_results( "SELECT booking_id, order_id, field_label, field_value FROM `".$ncm->ncm_order_booking."` WHERE order_id = ".$order_id, ARRAY_A );



            if( !empty( $order_booking ) ) {



                foreach ( $order_booking as $data ) {



                    $label = $data['field_label'];



                    $value = $data['field_value'];



                    $order_data[$label] = $value;



                }



            }



        }



        return $order_data;   



    }







    function ncm_get_order_item_passenger( $order_id ) {



        global $ncm, $wpdb; 



        $order_passenger = array();



        if( !empty($order_id) ) {  



            $order_passengers_data = $wpdb->get_results( "SELECT pass_id, order_id, order_item_id, passenger_id, field_label, field_value FROM `".$ncm->tbl_order_passenger."` WHERE order_id = ".$order_id." ORDER BY pass_id", ARRAY_A );



            $passenger_data = array();



            $order_item_id = '';



            



            foreach( $order_passengers_data as $passenger ) {



                $label = isset( $passenger['field_label'] ) ? $passenger['field_label'] : '';



                $value = isset( $passenger['field_value'] ) ? $passenger['field_value'] : '';



                



                if( !in_array( $label, array_keys( $passenger_data ) ) ) {



                    $passenger_data[$label] = $value;



                } else {



                    $order_passenger[$order_item_id][] = $passenger_data;







                    $passenger_data = array();



                    $passenger_data[$label] = $value;



                }



                $order_item_id = isset( $passenger['order_item_id'] ) ? $passenger['order_item_id'] : '';



            }



        



            if( !empty($passenger_data) ) {



                $order_passenger[$order_item_id][] = $passenger_data;



            } 



        }



        



        return $order_passenger;



    }







    function ncm_get_order_data( $order ) {



        global  $ncm_settings;



        $order_data = $this->ncm_get_order_details( $order );



        $order_items = $this->ncm_get_order_item( $order );



        $order_passenger = $this->ncm_get_order_item_passenger( $order );



        foreach ($order_items as $item) {



            $passenger_details = '';



            $order_item_id = $item['order_item_id'];



            if( isset( $order_passenger[$order_item_id] ) && !empty( $order_passenger[$order_item_id] ) ) {



                $item['ncm_passenger'] = $order_passenger[$order_item_id];



                foreach ($order_passenger[$order_item_id] as $passenger) {



                    $passenger_content = implode( " ", array_values( $passenger ) );



                    if( $passenger_details != '' ) {



                        $passenger_details.= "<br/>" . $passenger_content;



                    } else {



                        $passenger_details = $passenger_content;



                    }



                }



            }



            $item['display_subtotal'] = $ncm_settings->ncm_display_price( $item['subtotal'] );



            $item['display_levy'] = $ncm_settings->ncm_display_price( $item['levy'] );



            $item['display_total'] = $ncm_settings->ncm_display_price( $item['total'] );



            $item['passenger_details'] = $passenger_details;



            $order_data['product'][] = $item;



        }



        return $order_data;



    }







    function ncm_get_orders() {



        $content = '';



        $order = $this->ncm_get_order_id();



        if( $order ) {



            $order_data = $this->ncm_get_order_details( $order );



            $content = ncm_get_template_content( "ncm-order", $order_data );



        }



        return $content;



    }







    function ncm_order_products_func() {



        global $ncm_settings;



        $content = '';



        $order = $this->ncm_get_order_id();



        if( $order ) {



            $order_items = $this->ncm_get_order_item( $order );

            $order_passenger = $this->ncm_get_order_item_passenger( $order );


            if( !empty($order_items) ) {



                foreach ($order_items as $item) {



                    $order_item_id = $item['order_item_id'];



                    $item['subtotal'] = $ncm_settings->ncm_display_price( $item['subtotal'] );



                    $item['levy'] = $ncm_settings->ncm_display_price( $item['levy'] );



                    $item['total'] = $ncm_settings->ncm_display_price( $item['total'] );



                    $pessenger_type = json_decode($item['passenger'], true);



                    $passenger_text = '';



                    if( !empty( $pessenger_type ) ) {



                        foreach ($pessenger_type as $passenger) {



                            if( !empty($passenger['value']) && $passenger['value'] > 0 ) {



                                $passenger_text.= $passenger['label']." : ".$passenger['value']."<br/>"; 



                            }



                        }



                    }



                    $item['passenger_text'] = $passenger_text;



                    $item['ncm_passenger_fields'] = array();



                    if( isset( $order_passenger[$order_item_id] ) && !empty( $order_passenger[$order_item_id] ) ) {



                        $item['ncm_passenger'] = $order_passenger[$order_item_id];



                        if( isset( $order_passenger[$order_item_id][0] ) && !empty( $order_passenger[$order_item_id][0] ) ) {



                            $item['ncm_passenger_fields'] = array_keys( $order_passenger[$order_item_id][0] );



                        }



                    }



                    $content.= ncm_get_template_content( "ncm-order-product", $item );



                }



            }



        }



        echo $content;



    }







    function ncm_order_passenger_func( $passengers_info ) { 



        if( !empty( $passengers_info ) && is_array( $passengers_info ) ) {



            foreach ($passengers_info as $passenger_info) {



                $passenger = array();



                $passenger['passenger_info'] = $passenger_info;



                ncm_get_template("ncm-order-passenger", $passenger);



            }



        }



    }







}







global $ncm_order;



$ncm_order = new NCM_Order();







}



?>