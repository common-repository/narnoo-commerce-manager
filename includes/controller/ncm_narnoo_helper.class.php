<?php

/*
* This is a helper class. Narnoo API endpoints are there
*/

if( !class_exists ( 'NCM_Narnoo_Helper' ) ) {



class NCM_Narnoo_Helper {



    public $url;

    public $api_url;

    public $post_data;

    public $authen;



    public function ncm_get_option( $option_name = 'settings' ) {

        global $ncm;

        $option = '';

        if( $ncm->ncm_plugin_active( 'distributor' ) ) {

            $option = 'narnoo_distributor_'.$option_name;

        } else if( $ncm->ncm_plugin_active( 'operator' ) ) {

            $option = 'narnoo_operator_'.$option_name;

        }

        return $option;

    }



    public function init_api() {

        global $ncm_settings;



        $options = get_option( $this->ncm_get_option('settings') );

        $ncm_setting = $ncm_settings->ncm_get_settings_func();



        $narnoo_mode = isset($ncm_setting['ncm_narnoo_api_mode']) ? $ncm_setting['ncm_narnoo_api_mode'] : 0;

        if( $narnoo_mode ) {

            $this->api_url = 'https://apis.narnoo.com/api/v1/';

        } else {

            $this->api_url = 'https://apis-test.narnoo.com/api/v1/';

        }



        $this->authen = array();

        $this->post_data = array();



        // update this to include the access_key secret_key and access_token

        if ( empty($options['access_key']) || empty($options['secret_key'])  ) {

            return null;

        }



        /**

        *

        *   Store keys in a different setting option

        *

        */



        $_token   = get_option( $this->ncm_get_option('ncm_token') );



        /**

        *

        *   Check to see if we have access keys and a token.

        *

        */



        if( !empty( $options['access_key'] ) && !empty( $options['secret_key'] ) && empty($_token) ){



            $this->url = 'https://apis.narnoo.com/api/v1/'.'authenticate/token';
            //$this->url = $this->api_url.'authenticate/token';

            $this->authen = array( "API-KEY: ".$options['access_key'], "API-SECRET-KEY: ".$options['secret_key'] );



            /**

            *

            *   Call the Narnoo authentication to return our access token

            *

            */



            $requestToken = json_decode( $this->ncm_exec_curl(), true);


            $_token        = $requestToken['token'];

            if(!empty($_token)){

                /**

                *

                *   Update Narnoo access token

                *

                */

                update_option( $this->ncm_get_option('ncm_token'), $_token, 'yes' );        



            }else{

                return null;

            }

        }



        /**

        *

        *   Create authentication Header to access the API.

        *

        **/



        $api_settings = array(

            "Authorization:bearer " .$_token

        );



        $this->authen = $api_settings;



    }





    public function ncm_exec_curl() {



        $s = curl_init(); 

        curl_setopt($s, CURLOPT_URL, $this->url); 

        curl_setopt($s, CURLOPT_HTTPHEADER,$this->authen); 

        curl_setopt($s, CURLOPT_RETURNTRANSFER,true); 



        if( !empty($this->post_data) ) {

            curl_setopt($s, CURLOPT_POST, 1);

            curl_setopt($s, CURLOPT_POSTFIELDS, $this->post_data);

        }



        $webpage = curl_exec($s); 

        $status = curl_getinfo($s, CURLINFO_HTTP_CODE); 


        curl_close($s); 

        return $webpage;

    }



    public function ncm_product_details( $op_id, $product_id, $response_type = '' ) {



        global $ncm;



            $this->init_api();

            $method = 'booking/product/';

            $this->url = 'https://apis.narnoo.com/api/v1/' . $method . $op_id . '/' . $product_id;
            //$this->url = $this->api_url . $method . $op_id . '/' . $product_id;

            $result = $this->ncm_exec_curl();

           
            $result_data = json_decode($result);

            /*echo '<pre>';
            print_r($result_data);
            echo '</pre>'; die();*/

            $result = isset($result_data->data) ? json_encode($result_data->data) : json_encode($result_data);



        if( $response_type == 'array' ) {

            return json_decode( $result, true );

        } else {

            return json_decode( $result );

        }



    }







    public function ncm_product_availability( $op_id, $product_id, $start_date, $end_date, $bookingcode, $response_type='' ) {

        global $ncm;



        $temp_key = $op_id . $product_id . $start_date . $end_date . $bookingcode;

        $temp_data = $this->ncm_get_temp_data( $temp_key );



        if( $temp_data && !empty($temp_data) && $temp_data != 'null' ) {

            $result = $temp_data;

        } else {

            $this->init_api();

            $start_date = date('d-m-Y', strtotime($start_date));

            $end_date = date('d-m-Y', strtotime($end_date));



            $method = 'booking/availability/';

            $query_string = 'startDate='.$start_date.'&endDate='.$end_date.'&id='.$bookingcode; 

            $this->url = 'https://apis.narnoo.com/api/v1/' . $method . $op_id . '/' . $product_id . '?' . $query_string;
            //$this->url = $this->api_url . $method . $op_id . '/' . $product_id . '?' . $query_string;

            $result = $this->ncm_exec_curl();



            $result_data = json_decode($result);

            $result = isset($result_data->data) ? json_encode($result_data->data) : json_encode($result_data);



            $this->ncm_set_temp_data( $temp_key, $result );

        }

        

        if( $response_type == 'array' ) {

            return json_decode( $result, true );

        } else {

            return $result;

        }

    }



    public function ncm_product_booking( $op_id, $product_id, $bookingcode ) {

        global $ncm;



        $temp_key = $op_id . $product_id . $bookingcode;

        $temp_data = $this->ncm_get_temp_data( $temp_key );



        if( $temp_data && !empty($temp_data) && $temp_data != 'null' ) {

            $result = $temp_data;

        } else {

            $this->init_api();



            $method = 'booking/details/';

            $query_string = 'id='.$bookingcode; 

            $this->url = 'https://apis.narnoo.com/api/v1/' . $method . $op_id . '/' . $product_id . '?' . $query_string;
            //$this->url = $this->api_url . $method . $op_id . '/' . $product_id . '?' . $query_string;



            $result = $this->ncm_exec_curl();



            $result_data = json_decode($result);

            /*echo '<pre>';
            print_r($result_data);
            echo '</pre>';*/

            $result = isset($result_data->data) ? json_encode($result_data->data) : json_encode($result_data);



            $this->ncm_set_temp_data( $temp_key, $result );

        }



        return $result; 

    }


    public function ncm_product_promocode( $promocode, $productid, $opperatorid, $totlaamount ) {

        global $ncm;
  
        $this->init_api();

        $products = array(
                "amount"=> $totlaamount,
                "code"=> $promocode,
                "products" => array([
                    'operator' => $opperatorid,
                    'productId'  => $productid
                    ]
                ),
                
            );

        $productsjson = json_encode($products);


        $method = 'booking/promotionalcode/';
        $this->url = 'https://apis.narnoo.com/api/v1/' . $method;

        $this->post_data = json_encode( $products );
        
        $result = $this->ncm_exec_curl();


        /*echo '<pre>';
        print_r($result);
        echo '</pre>'; die();*/
        
        //$result_data = json_decode($result);

        //print_r($result); die();
        
        return $result; 

    }



    public function ncm_reservation( $data ) {

        global $ncm, $ncm_settings, $ncm_checkout;


        $contact = ( isset($data['contact']) && !empty($data['contact']) ) ? $data['contact'] : array();

        $payment = ( isset($data['payment']) && !empty($data['payment']) ) ? $data['payment'] : array();

        $booking_info = ( isset($data['booking_info']) && !empty($data['booking_info']) ) ? $data['booking_info'] : array();

        $products = ( isset($data['products']) && !empty($data['products']) ) ? $data['products'] : array();



        $products_data = array();

        foreach ($products as $key => $product) {

            $options = array();

            $participants = array();

            $product_data = array();

            

            // Set options.

            foreach ($product['passenger_types'] as $key => $passenger) {

                if( isset($passenger['value']) && $passenger['value'] > 0 ) {

                    $option = array();

                    $option["id"] = $passenger['id'];

                    $option["label"] = $passenger['label'];

                    $option["quantity"] = (int)$passenger['value'];

                    $option["price"] = $passenger['price'];

                    $option["group"] = $passenger['group'];

                    $option["maxQuantity"] = $passenger['maxQuantity'];

                    $options[] = $option;

                }

            } 



            // Set Passenger Information.

            $booking_code = isset($product['booking_code']) ? $product['booking_code'] : '';

            $booking_date = isset($product['booking_date']) ? $product['booking_date'] : '';

            $prod_post_id = isset($product['post_id']) ? $product['post_id'] : 0;



            if( isset($data['passenger_info']) && !empty($data['passenger_info']) ) {

                $passenger_info = $data['passenger_info'];

                $field_code = $prod_post_id . '_' . date('dmY_H_i_s', strtotime($booking_date));

                if( isset($passenger_info[$field_code]) && !empty($passenger_info[$field_code]) ) {

                    foreach( $passenger_info[$field_code] as $passenger_id => $passengers_values ) {

                        if( count($passengers_values) > 0 ) {

                            foreach($passengers_values as $passenger_val) {



                                $field_labels = isset($passenger_val['label']) ? $passenger_val['label'] : array();

                                $field_values = isset($passenger_val['value']) ? $passenger_val['value'] : array();

                                if( !empty($passenger_val) && !empty($field_labels) && !empty($field_values) ) {

                                    $person = array();

                                    foreach($field_labels as $key => $label ) {

                                        if( !empty($label) && isset($field_values[$key]) && !empty($field_values[$key]) ) {

                                            $person[] = array( "label" => $label, "value" => $field_values[$key] );

                                        }

                                    }

                                    $participants[] = $person;

                                }



                                // $person = array();

                                // $person[] = array( "label" => "First Name", "value" => $passenger_val['first_name'] );

                                // $person[] = array( "label" => "Last Name", "value" => $passenger_val['last_name'] );

                                // $person[] = array( "label" => "Country", "value" => $passenger_val['country'] );   



                                // $participants[] = $person;

                            }

                        }

                    }

                }

            }



            $product_data['productId'] = (int)$product['product_id'];

            $product_data['bookingCode'] = $product['booking_code'];

            $product_data['bookingDate'] = $product['narnoo_bdate'];

            $product_data['paymentMethod'] = 'FULL_AGENT';

            $product_data['option'] = $options;

            $product_data['participants'] = $participants;

            //$product_data['bookingForm'] = array( array( "label" => null, "value" => null) );
            $product_data['bookingForm'] = $booking_info;



            $products_data[] = $product_data;
            //print_r($products_data); exit();
        }

        /*echo "<pre>";
        print_r($product_data); 
        echo "</pre>"; die();*/

        // api call start

        $this->init_api();

        $this->authen[] = "Content-Type: application/json";

        $this->authen[] = "cache-control: no-cache";



        $method = 'booking/create';

        $this->url = 'https://apis-test.narnoo.com/api/v1/' . $method;
        //$this->url = $this->api_url . $method;



        $post_data = array();

        $post_data['contact'] = $contact;

        $post_data['payment'] = array( 'type' => $payment, 'currency' => $ncm_settings->ncm_get_currency() );

        $post_data['products'] = $products_data;


        $this->post_data = json_encode( $post_data );

        

        $result = $this->ncm_exec_curl();



        $ncm->ncm_write_log( " after make booking call response values => " . $result);



        return $result; 

    }



    function ncm_set_temp_data( $key, $value ) {

        global $ncm;

        if( !empty( $key ) && !empty( $value ) ) {

            $key = str_replace(":", "_", $key);

            $key = str_replace("-", "_", $key);

            $key = "ncm_".$key;

            $chk_value = json_decode( $value, true );



            if( !( isset( $chk_value['success'] ) && $chk_value['success'] == false ) ) {

                set_site_transient( $key, $value, 3600 * 4);

            }

        }

    }



    function ncm_get_temp_data( $key ) {

        global $ncm;

        $key = str_replace(":", "_", $key);

        $key = str_replace("-", "_", $key);

        $key = "ncm_".$key;

        $temp_data = get_site_transient( $key );

        return $temp_data;

    }

}



global $ncm_narnoo_helper;

$ncm_narnoo_helper = new NCM_Narnoo_Helper();



}



?>