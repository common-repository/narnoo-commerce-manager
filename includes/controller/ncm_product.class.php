<?php

/*
* The availability functions are here. This is product class
*/

if( !class_exists ( 'NCM_Product' ) ) {



class NCM_Product {



    function __construct() {



        add_action( 'wp_ajax_ncm_availability', array( $this, 'ncm_check_product_availability' ) );

  

        add_action( 'wp_ajax_nopriv_ncm_availability', array( $this, 'ncm_check_product_availability' ) );



        add_action( 'wp_ajax_ncm_availability_calendar', array( $this, 'ncm_check_product_availability_calendar' ) );

  

        add_action( 'wp_ajax_nopriv_ncm_availability_calendar', array( $this, 'ncm_check_product_availability_calendar' ) );

        

        add_action( 'wp_ajax_ncm_calendar_option_list_popup', array( $this, 'ncm_calendar_option_list_popup_func' ) );

  

        add_action( 'wp_ajax_nopriv_ncm_calendar_option_list_popup', array( $this, 'ncm_calendar_option_list_popup_func' ) );



        add_action( 'wp_ajax_ncm_question', array( $this, 'ncm_question_func' ) );

  

        add_action( 'wp_ajax_nopriv_ncm_question', array( $this, 'ncm_question_func' ) );



        add_action( "NCM_Availability_StartDate", array( $this, "ncm_availability_startdate" ), 10, 1 );



        add_action( "NCM_Availability_EndDate", array( $this, "ncm_availability_enddate" ), 10, 1 );



        add_action( "NCM_Availability_Button", array( $this, "ncm_get_availability_button" ), 10, 1 );



        add_action( "ncm_product_display_availability", array( $this, "ncm_product_display_availability_func" ), 10, 1 );

    }



    function ncm_get_product_availability( $post_id, $start_date, $end_date, $booking_code='' ) {

        global $ncm_narnoo_helper, $ncm;



        $op_id =  get_post_meta( $post_id, "narnoo_operator_id", true );

        $product_id = get_post_meta( $post_id, "narnoo_booking_id", true );

        

        $product_data = $ncm_narnoo_helper->ncm_product_details( $op_id, $product_id, 'array' );

        
        $booking_data = isset($product_data['bookingData']) ? $product_data['bookingData'] : array();

        

        $booking_codes = isset($booking_data['bookingCodes']) ? $booking_data['bookingCodes'] : array();

        $product_times = isset($booking_data['productTimes']) ? $booking_data['productTimes'] : array();

        $booking_count = isset($booking_data['bookingCodesCount']) ? $booking_data['bookingCodesCount'] : array();       



        $product_time_id = isset($product_times[0]['id']) ? $product_times[0]['id'] : 'TT';



        $result = array();

        $result['post_id'] = $post_id;

        $result['operator_id'] = $op_id;

        $result['booking_id'] = $product_id;

        $result['start_date'] = $start_date;

        $result['end_date'] = $end_date;



        $availability_data = array();

        if( !empty($booking_code) && $booking_count > 0 ) {



            $availability = $ncm_narnoo_helper->ncm_product_availability( $op_id, $product_id, $start_date, $end_date, $booking_code, 'array' );

            $availability['ncm_sub_product_name'] = '';

            $availability_data[$booking_code] = $availability;

            

        } else if( $booking_count > 0 ) {

            

            foreach ($booking_codes as $booking_data) {

                if( isset( $booking_data['id'] ) && $booking_data['id'] != '' ) {

                    $booking_id = $booking_data['id'];

                    $booking_label = $booking_data['label'];

                    $booking_code = $booking_id.':'.$product_time_id;



                    $availability = $ncm_narnoo_helper->ncm_product_availability( $op_id, $product_id, $start_date, $end_date, $booking_code, 'array' );

                    $availability['ncm_sub_product_name'] = $booking_label;

                    $availability_data[$booking_code] = $availability;

                }

            }

        }



        $availabilities = array();

        foreach ($availability_data as $booking_code => $avail_data) {



            $data_code = str_replace(":", "_", $booking_code);

            $data_container = "ncm_".$data_code;

            $availability = array();

            $availability['ncm_sub_product_name'] = $avail_data['ncm_sub_product_name'];



            if( isset( $avail_data['productAvailabilityCount'] ) && $avail_data['productAvailabilityCount'] > 0 ) {



                $availability['ncm_availability_count'] = $avail_data['productAvailabilityCount'];

                $product_availability = array();



                foreach ( $avail_data['productAvailability'] as $productavailability ) {



                    if( isset($productavailability['bookingDateDisplay']) && !empty($productavailability['bookingDateDisplay']) ) {



                        $productavailability['ncm_booking_date'] = $productavailability['bookingDateDisplay'];

                        $booking_date = $productavailability['bookingDateDisplay'];


                        $date_arr = explode("+",$booking_date);

                        $booking_date = str_replace("T", " ", $date_arr[0]);

                        $productavailability['bookingDate'] = $booking_date;



                        $product_availability[$booking_date] = $productavailability;

                        $product_availability[$booking_date]['ncm_sub_product_name'] = $avail_data['ncm_sub_product_name'];

                        $product_availability[$booking_date]['ncm_bookingcode'] = $booking_code;

                    }

                }



                $availability['ncm_product_availability'] = $product_availability; 

                $availability['ncm_product_availability']['data-code'] = $data_container;



            } else {

                $availability['ncm_availability_count'] = 0;

                // if no availability

            }

            $availabilities[$booking_code] = $availability;

            $availabilities[$booking_code]['data_code'] = $data_code;

            $availabilities[$booking_code]['data_container'] = $data_container;

        }



        $result['ncm_availability'] = $availabilities;

        return $result;

    }



    function ncm_calendar_option_list_popup_func() {

        $status = 'failed';

        $content = 'Sorry! something went wrong. Please refresh the page and try again.';

        if( isset($_REQUEST['action']) && $_REQUEST['action'] = 'ncm_calendar_option_list_popup') {

            $_REQUEST['product_date_html'] = isset($_REQUEST['product_date_html']) ? stripslashes($_REQUEST['product_date_html']) : '';

            $status = 'success';



            $content = ncm_get_template_content( 'ncm-product-calendar-option-list', $_REQUEST );

        }

        echo json_encode( array( 'status'=> $status, 'content'=>$content ) );

        die;

    }



    function ncm_check_product_availability_calendar() {

        global $ncm_narnoo_helper, $ncm_settings, $ncm_payment_gateways, $ncm_controls;

        $status = '';

        $content = '';

        if( isset($_REQUEST['action']) && $_REQUEST['action']=='ncm_availability_calendar' && isset($_REQUEST['post_id']) && $_REQUEST['post_id'] > 0 ) {

            $post_id = $_REQUEST['post_id'];

            $bookingCodes = $_REQUEST['bookingCodes'];  



            $op_id =  get_post_meta( $post_id, "narnoo_operator_id", true );

            $product_name = get_the_title( $post_id );

            $product_id = get_post_meta( $post_id, "narnoo_booking_id", true );

            $productDetails = $ncm_narnoo_helper->ncm_product_details( $op_id, $product_id );



            $productTimes_arr = isset($productDetails->bookingData->productTimes) ? $productDetails->bookingData->productTimes : '';

            $time = isset($productTimes_arr[0]->time) ? substr($productTimes_arr[0]->time,0,5) : '';

            

            /************ Start Month code ************/

            if( isset($_REQUEST['current_date']) && !empty($_REQUEST['current_date']) ){

                $current_date = $_REQUEST['current_date'];

                $start_date = '01-'.$current_date;

                if( date('m') == date( 'm', strtotime($start_date) ) ) {

                    $current_date = date('d-m-Y');

                    $start_date = date( 'd-m-Y', strtotime( $current_date . "+1 days" ) );

                }

                $end_date = date( 't-m-Y', strtotime( $start_date ) );

            } else {

                $current_date = date('d-m-Y');

                $start_date = date( 'd-m-Y', strtotime( $current_date . "+1 days" ) );

                $end_date = date( 't-m-Y', strtotime( $start_date ) );

            }

            /************ End Month code ************/



            //$data = $this->ncm_get_product_availability($post_id, $start_date, $end_date);



            $result_available = $ncm_narnoo_helper->ncm_product_availability( $op_id, $product_id, $start_date, $end_date, $bookingCodes );

            $result = json_decode( $result_available, true );



            if( isset($result['productAvailabilityCount']) && $result['productAvailabilityCount'] > 0 && isset($result['productAvailability']) && count($result['productAvailability']) > 0 )

            {

                $status = "success";

                $qty = __('qty', NCM_txt_domain);



                $available_arr = array();

                foreach ($result['productAvailability'] as $key => $availability) {

                    $date = date( 'd-m-Y', strtotime( $availability['bookingDate'] ) );

                    $time = empty($time) ? date( 'h:i', strtotime( $availability['bookingDate'] ) ) : $time;

                    $available_arr[$date] = $availability;



                }



                $ncm_checkout_link = $ncm_payment_gateways->ncm_get_cart_page_link();



                $content.= $ncm_controls->ncm_control( array(

                                "type" => "hidden",

                                "name" => "ncm_link",

                                "id" => "ncm_link",

                                "value" => $ncm_checkout_link

                            ) );



                $content.= $ncm_controls->ncm_control( array(

                                "type" => "hidden",

                                "name" => "post_id",

                                "id" => "post_id",

                                "value" => $post_id

                            ) );



                /********* start display full calender ********/



                $day_name = array(

                    '1' => 'Sun',

                    '2' => 'Mon',

                    '3' => 'Tue',

                    '4' => 'Wed',

                    '5' => 'Thu',

                    '6' => 'Fri',

                    '7' => 'Sat',

                );



                $previous_month = date('m-Y', strtotime( $start_date . "-1 month" ) );

                $next_month = date('m-Y', strtotime( $start_date . "+1 month" ) );

                $previous_month_data = 'id="ncm_month_change" data-ncm_month_year="'.$previous_month.'"';

                $next_month_data = 'id="ncm_month_change" data-ncm_month_year="'.$next_month.'"';



                if( date('m')==date('m', strtotime($start_date)) && date('Y')==date('Y', strtotime($start_date)) ) {

                    $previous_month_data = '';

                }



                $content .= '<table class="table table-bordered ncm_table_availability">';

                $content .= '<thead>';

                $content .= '<tr>';

                $content .= '<td align="center">';

                $content .= '<a href="javascript:void(0);" '.$previous_month_data.'><b> &lt; </b></a>';

                $content .= '</td>';

                $content .= '<td colspan="5" align="center"><b>'.date('F Y',strtotime($start_date)).'</b></td>';

                $content .= '<td align="center">';

                $content .= '<a href="javascript:void(0);" '.$next_month_data.'><b> &gt; </b></a>';

                $content .= '</td>';

                $content .= '</tr>';

                $content .= '<tr>';

                foreach ($day_name as $d => $date_name) {

                    $content .= '<td><b> ' . $date_name . ' </b></td>';   

                }

                $content .= '</tr>';

                $content .= '</thead>';

                $content .= '<tbody>';

                $content .= '<tr>';



                $last_date = date('d', strtotime($end_date));

                $day = $date = 1;

                $day_matched = false;

                $month_start_day = date( 'D', strtotime( '1-'.date( 'm-Y', strtotime($start_date) ) ) );

                $count_td = 0;

                $count_tr = 1;

                $can_option_list_display_in_popup = false;

                while( $day <= $last_date || $date <= $last_date) {

                    $count_td++;

                    if( $month_start_day == $day_name[$day] ) {

                        $day_matched = true;

                    } else if( !$day_matched ) {

                        $content .= '<td class="inactive">&nbsp;</td>';

                    }



                    if($day_matched) {

                        $c_date = date('d-m-Y', strtotime($date.'-'.date('m-Y', strtotime($start_date))));

                        $toottip_data = __( 'No Tour Available', NCM_txt_domain );

                        $date_label = '<a href="javascript:;" class="ncm_availability_red">'.$date.'</a>';

                        if( isset( $available_arr[$c_date] ) && !empty( $available_arr[$c_date] ) ) {

                            $row_data = $available_arr[$c_date];

                            $passenger_type = isset( $row_data['price'] ) ? $row_data['price'] : '0';

                            $display_date = date( 'd, F Y', strtotime($c_date) );

                            $time = date( 'H:i', strtotime( $availability['bookingDate'] ) );

                            if( $time != '00:00') {

                                $time = '<br><span>'.__('Departure :', NCM_txt_domain).' '.$time.'</span>';

                            } else {

                                $time = '';

                            }



                            $available = 0;

                            $available = isset($availability['availability']) ? $availability['availability'] : 0;



                            if( count($passenger_type) > 5 ) {

                                $can_option_list_display_in_popup = true;

                                $toottip_data = 'Click to view product options';



                                $data_availability_class = ($available > 0) ? 'ncm_availability_green' : 'ncm_availability_red';

                                $date_label = '<a 

                                    href="javascript:;" 

                                    id="ncm_option_list_selected_date" 

                                    class="'.$data_availability_class.'" 

                                    data-toggle="tooltip" 

                                    data-html="true" 

                                    title="'.$toottip_data.'" 

                                    data-ncm_date="'.$c_date.'" 

                                    data-ncm_booingCodes="'.$bookingCodes.'"

                                    data-ncm_narnoo_bdate="'.$row_data['bookingDate'].'"

                                    >';



                                $ncm_option_list = array();

                                foreach ($passenger_type as $key => $person_type) {



                                    $pt_label = isset( $person_type['label'] ) ? $person_type['label'] : '';

                                    $pt_price = isset( $person_type['price'] ) ? $person_type['price'] : 0;

                                    $person_type['price'] = $ncm_settings->ncm_display_price( $pt_price );

                                    $ncm_option_list[] = $person_type;

                                    //$ncm_option_list[$pt_label] = $ncm_settings->ncm_display_price( $pt_price );

                                }



                                $product_date_html = '<a 

                                    href="javascript:void();" 

                                    id="ncm_select_date" 

                                    class="'.$data_availability_class.'" 

                                    data-ncm_date="'.$c_date.'" 

                                    data-ncm_booingCodes="'.$bookingCodes.'"

                                    data-ncm_narnoo_bdate="'.$row_data['bookingDate'].'"

                                    >';

                                

                                $product_date_html .= $display_date;

                                $product_date_html .= '</a>';



                                $ajax_data = array();

                                $ajax_data['action'] = 'ncm_calendar_option_list_popup';

                                $ajax_data['product_name'] = $product_name;

                                $ajax_data['product_date'] = $display_date;

                                $ajax_data['product_date_html'] = $product_date_html;

                                $ajax_data['departure'] = $time;

                                $ajax_data['seats_available'] = $available;

                                $ajax_data['ncm_option_list'] = $ncm_option_list;

                                $ajax_data['cart_attr']['id'] = "ncm_select_date";

                                $ajax_data['cart_attr']['data-ncm_date'] = $c_date;

                                $ajax_data['cart_attr']['data-ncm_booingCodes'] = $bookingCodes;

                                $ajax_data['cart_attr']['data-ncm_narnoo_bdate'] = $row_data['bookingDate'];



                                $date_label .= '<textarea style="display:none;">'.json_encode($ajax_data).'</textarea>';

                                $date_label .= $date;

                                $date_label .= '</a>';

                            } else {



                                $toottip_data = '<strong>'.$product_name.'</strong>';

                                $toottip_data.= '<p><span>'.$display_date.'</span>'.$time.'</p>';

                                $toottip_data.= '<p><strong><span>'.__('Seats Available :', NCM_txt_domain).' '.$available.'</span></strong></p>';



                                $toottip_data.= '<table>';

                                $toottip_data.= '<thead>';

                                $toottip_data.= '<tr>';

                                $toottip_data.= '<th>Passenger Type</th>';

                                $toottip_data.= '<th>Sell and Levy</th>';

                                $toottip_data.= '</tr>';

                                $toottip_data.= '</thead>';

                                $toottip_data.= '<tbody>';

                                foreach ($passenger_type as $key => $person_type) {

                                    $pt_label = isset( $person_type['label'] ) ? $person_type['label'] : '';

                                    $pt_id    = isset( $person_type['id'] ) ? $person_type['id'] : '';

                                    $ncm_price = isset( $person_type['price'] ) ? $person_type['price'] : 0;

                                    $pt_levy  = isset( $person_type['levy'] ) ? $person_type['levy'] : 0;

                                    //$ncm_price = $pt_price + $pt_levy;
                                    $ncm_price = $pt_price + $pt_levy;

                                    $toottip_data.= '<tr>';

                                    $toottip_data.= '<td>'.$pt_label.'</td>';

                                    $toottip_data.= '<td>'.$ncm_settings->ncm_display_price( $ncm_price ).'</td>';

                                    $toottip_data.= '</tr>';

                                }

                                $toottip_data.= '</tbody>';

                                $toottip_data.= '</table>';

                            



                                $data_availability_class = ($available > 0) ? 'ncm_availability_green' : 'ncm_availability_red';

                                $date_label = '<a 

                                    href="javascript:;" 

                                    id="ncm_select_date" 

                                    class="'.$data_availability_class.'" 

                                    data-toggle="tooltip" 

                                    data-html="true" 

                                    title="'.$toottip_data.'" 

                                    data-ncm_date="'.$c_date.'" 

                                    data-ncm_booingCodes="'.$bookingCodes.'"

                                    data-ncm_narnoo_bdate="'.$row_data['bookingDate'].'"

                                    >';



                                $date_label .= $date;

                                $date_label .= '</a>';

                            }

                        }

                        $content .= '<td class="inactive">';

                        $content .= $date_label;

                        $content .= '</td>';

                        $date++;

                    }

                    $day++;

                    if($count_td % 7 == 0) {

                        $content .= '</tr><tr>';

                        $count_tr++;

                    }

                }



                while( ( $count_tr * 7 ) >= $day ) {

                    $content .= '<td class="inactive">&nbsp;</td>';

                    $day++;

                }



                $content .= '</tr>';

                $content .= '</tbody>';

                $content .= '</table>';



                /********* End display full calender ********/



                $content.= '</div><div class="ncm_price_availability_loader" style="display:none;"><i class=" ncm_fa ncm_fa-spinner ncm_fa-spin ncm_fa-4x ncm_prc_avl"></i></div>';



            } else if( isset($result['productAvailabilityCount']) && $result['productAvailabilityCount'] == 0 ) {

                $status = 'error';

                $content = __('Not available for this date. Please select other date.', NCM_txt_domain);

            } else if( isset($result['success']) ) {

                $status = $result['success'];

                $content = $result['message'];

            }

        } else {

            $status = 'error';

            $message = __('Action is not set or blank.', NCM_txt_domain);

            $content = __('Sorry! Something went wrong.', NCM_txt_domain);

        }



        $return = array( "status" => $status, "msg" => $message, "content" => $content );

        echo json_encode($return);

        die();

    }



    function ncm_check_product_availability() {

        global $ncm_narnoo_helper, $ncm_settings, $ncm_payment_gateways, $ncm_controls, $ncm;

        $status = 'error';

        $message = __('Action is not set or blank.', NCM_txt_domain);

        $content = __('Sorry! Something went wrong.', NCM_txt_domain);



        if( isset($_REQUEST['action']) && $_REQUEST['action']=='ncm_availability' ) {

            if( isset($_REQUEST['post_id']) && $_REQUEST['post_id'] > 0 ) { 

                if( isset($_REQUEST['product_type']) && $_REQUEST['product_type'] == 'single' ) {



                    $post_data    = $_REQUEST;

                    $post_id      = $post_data['post_id'];

                    $bookingCodes = $post_data['bookingCodes'];

                    $startdate    = $post_data['startdate'];

                    $enddate      = $post_data['enddate'];



                    $ncm_booking_data = $this->ncm_get_product_availability( $post_id, $startdate, $enddate, $bookingCodes );



                    $status = "success";

                    foreach ($ncm_booking_data['ncm_availability'] as $booking_code => $availability_data) {

                    

                        $data = isset($availability_data['ncm_product_availability']) ? $availability_data['ncm_product_availability'] : array();

                        $data = $this->ncm_display_availability( $data );

                        $status = $data['status'];

                        $message = $data['message'];

                        $content = $data['content'];

                    

                    }



                } else if( isset($_REQUEST['product_type']) && $_REQUEST['product_type'] == 'multiple' ) {

                    $data = $this->ncm_get_multiple_booking_availability( $_REQUEST );

                    $status = $data['status'];

                    $message = $data['message'];

                    $content = $data['content'];

                } else {

                    $status = 'error';

                    $message = __('Product type is not set or blank.', NCM_txt_domain);

                    $content = __('Sorry! Something went wrong.', NCM_txt_domain);

                }

            } else {

                $status = 'error';

                $message = __('Post is not set or blank.', NCM_txt_domain);

                $content = __('Sorry! Something went wrong.', NCM_txt_domain);

            }

        }

        $return = array( "status" => $status, "msg" => $message, "content" => $content );

        echo json_encode($return);

        die();

    }



    function ncm_get_multiple_booking_availability ( $post_data ) {

        global $ncm_narnoo_helper, $ncm_payment_gateways, $ncm_controls, $ncm;

        

        $status = 'error';

        $message = __('Post is not set or blank.', NCM_txt_domain);

        $content = __('Sorry! Something went wrong.', NCM_txt_domain);

        

        if( isset($post_data['post_id']) && $post_data['post_id'] > 0 ) {

            

            $status = 'success';

            $message = __('success', NCM_txt_domain);

            $post_id = $post_data['post_id'];



            /************ Start start date and end date **********/

            $startdate = isset($post_data['startdate']) ? $post_data['startdate'] : date( 'd-m-Y', strtotime( $current_date . "+1 days" ) );

            $enddate = isset($post_data['enddate']) ? $post_data['enddate'] : date( 'd-m-Y', strtotime( $current_date . "+7 days" ) );

            $start_date = date( 'd-m-Y', strtotime( $startdate ) );

            $end_date = date( 'd-m-Y', strtotime( $enddate. "+1 days" ) );

            /************ End start date and end date **********/



            $booking_data = $this->ncm_get_product_availability( $post_id, $start_date, $enddate );





            $product_data = array();

            $product_data['ncm_post_id'] = $booking_data['post_id'];

            $product_data['ncm_operator_id'] = $booking_data['operator_id'];

            $product_data['ncm_booking_id'] = $booking_data['booking_id'];

            $product_data['ncm_product_name'] = get_the_title( $booking_data['post_id'] );



            $content = '';

            $start_date = $booking_data['start_date'];

            $end_date = $booking_data['end_date'];

            $availabilities = $booking_data['ncm_availability'];



            foreach ($availabilities as $booking_code => $availability_data) {



                $data_code = $availability_data['data_code'];

                $data_container = $availability_data['data_container'];  



                $temp_data = array();

                $temp_data['ncm_sub_product_name'] = $availability_data['ncm_sub_product_name'];



                $data = array_merge( $product_data, $temp_data, $availability_data );



                $content.= ncm_get_template_content( 'ncm-product-multiple-booking-popup', $data );

                

            }

        }

        return array( 'status' => $status, 'message' => $message, 'content' => $content );

    }



    function ncm_product_display_availability_func( $availability ) {

        $data_code = 'ncm_';

        if( isset( $availability['data-code'] ) ) {

            $data_code = $availability['data-code'];

        }



        // echo "<pre>";

        // print_r( $availability );

        // echo "</pre>";

        $result = $this->ncm_display_availability( $availability );



        $content = '<div class="ncm_booking_code '.$data_code.'">';

        $content.= $result['content'];

        $content.= '</div>';

        echo $content;

    }



    function ncm_display_availability( $availability ) {

        global $ncm;

        $content = '';

        if( isset( $availability['data-code'] ) ) {    unset($availability['data-code']);    }



        if( isset( $availability ) && !empty( $availability ) ) {

            $status = 'success';

            $message = __('success', NCM_txt_domain);

            foreach( $availability as $date => $availability_data ) {

                $link_attr = ' data-ncm_booingCodes="'.$availability_data['ncm_bookingcode'].'" ';

                $link_attr.= ' data-ncm_date="'.date("d-m-Y H:i:s", strtotime($date)).'"';

                $link_attr.= ' data-ncm_narnoo_bdate="'.$availability_data['ncm_booking_date'].'"';

                $availability_data['ncm_date'] = date('D ', strtotime( $availability_data['bookingDate'] ) ).date('d, M', strtotime( $availability_data['bookingDate'] ) );


                $ncm_time = date('H:i:s', strtotime( $availability_data['bookingDate'] ) );

                $availability_data['ncm_time'] = ($ncm_time != '00:00:00') ? date('H:i:s', strtotime( $availability_data['bookingDate'] ) ) : '';



                $availability_data['ncm_product_name'] = isset($_REQUEST['post_id']) ? get_the_title( $_REQUEST['post_id'] ) : '';

                $availability_data['ncm_link_attr'] = $link_attr;

                $content.= ncm_get_template_content( 'ncm-product-display-availability', $availability_data );

            }

        } else {

            $status = 'success';

            $message = __('Availability not found for the selected date.', NCM_txt_domain);

            $content.= ncm_get_template_content( 'ncm-product-no-availability' );

        }

        return array( 'status'=>$status, "message"=>$message, "content"=>$content );

    }



    function ncm_availability_startdate( $text_label ) {

        global $post, $ncm_controls;

        $content = '';

        if( isset( $post->narnoo_data->bookingData ) ) {

            $bookingdata = $post->narnoo_data->bookingData;

            $bookingcode_arr = isset($bookingdata->bookingCodes) ? $bookingdata->bookingCodes : '';

            $bookingCodes = isset($bookingcode_arr[0]->id) ? $bookingcode_arr[0]->id : '';

            if( !empty($bookingCodes) ) {

                $content = '<label class="ncm-label">'.$text_label.'</label>';

                $content.= $ncm_controls->ncm_control(

                    array(

                        "type" => "text",

                        "name" => "ncm_travel_date_start",

                        "id" => "ncm_travel_date_start",

                        "class" => "ncm_travel_date_start ncm_textbox ncm_datepicker",

                        "value" => date('d-m-Y', strtotime(date('d-m-Y'). ' +1 day')),

                        "placeholder" => "dd-mm-yyyy",

                        "data-format" => "dd-mm-yyyy",

                        "data-startDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 days" ) ),

                        "data-endDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 years" ) ),

                    )

                );

            }

        }

        echo $content;

    }



    function ncm_availability_enddate( $text_label ) {

        global $post, $ncm_controls;

        $content = '';

        if( isset( $post->narnoo_data->bookingData ) ) {

            $bookingdata = $post->narnoo_data->bookingData;

            $bookingcode_arr = isset($bookingdata->bookingCodes) ? $bookingdata->bookingCodes : '';

            $bookingCodes = isset($bookingcode_arr[0]->id) ? $bookingcode_arr[0]->id : '';

            if( !empty($bookingCodes) ) {

                $content = '<label class="ncm-label">'.$text_label.'</label>';

                $content.= $ncm_controls->ncm_control(

                    array(

                        "type" => "text",

                        "name" => "ncm_travel_date_end",

                        "id" => "ncm_travel_date_end",

                        "class" => "ncm_travel_date_end ncm_textbox ncm_datepicker",

                        "value" => date('d-m-Y', strtotime(date('d-m-Y'). ' +2 day')),

                        "placeholder" => "dd-mm-yyyy",

                        "data-format" => "dd-mm-yyyy",

                        "data-startDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 days" ) ),

                        "data-endDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 years" ) ),

                    )

                );

            }

        }

        echo $content;

    }

    

    function ncm_get_availability_button( $button_text ) {

        global $post;

        $content = '';



        if( isset( $post->narnoo_data->bookingData ) ) {

            $bookingdata = $post->narnoo_data->bookingData;

            $bookingcode_arr = isset($bookingdata->bookingCodes) ? $bookingdata->bookingCodes : '';

            $bookingCodes = isset($bookingcode_arr[0]->id) ? $bookingcode_arr[0]->id : '';

            if( !empty($bookingCodes) ) {

                $loader= '<span class="ncm_chk_price_display_loader ncm_display_loader"><i class="ncm_fa-li ncm_fa ncm_fa-spinner ncm_fa-spin"></i></span>';

                $content.= '<button type="submit" name="chk_price" id="chk_price" class="single_add_to_cart_button button alt chk_price btn btn-info btn-lg">'.$button_text.$loader.'</button>';

                

            }

        }

        echo $content;

    }



    function ncm_question_func() {

        $return = array('status' => 'falied', 'msg' => __('Sorry, Something went wrong.', NCM_txt_domain) );

        $post_data = $_REQUEST;

        $booking_name = $post_data['booking_name'];

        $booking_email = $post_data['booking_email'];

        $booking_phone = $post_data['booking_phone'];

        $booking_hotel = $post_data['booking_hotel'];

        $booking_comments = $post_data['booking_comments'];



        $to_email = get_bloginfo('admin_email');

        $subject = get_bloginfo('name').' - User have a question related to product.';

        $mail_content = 'Hi, 

        <br/>

        <br/>Below are the details of user and his/her question : 

        <br/>

        <br/>User Name : '.$booking_name.'

        <br/>

        <br/>Email Address : '.$booking_email.'

        <br/>

        <br/>Phone Number : '.$booking_phone.'

        <br/>

        <br/>Accommodation : '.$booking_hotel.'

        <br/>

        <br/>Question or Comments : '.$booking_comments.'

        <br/>

        <br/>

        <br/>Thanks.';



        $headers = array('From: ' . $booking_name . ' <' . $booking_email . '>');

        add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));

        if( wp_mail( $to_email, $subject, $mail_content, $headers ) ) {

            $return = array('status' => 'success', 'msg' => __('Your question has been submitted successfully. Will contact you soon.', NCM_txt_domain) );

        } else {

            if( mail($to_email, $subject, strip_tags($mail_content) ) ) {

                $return = array('status' => 'success', 'msg' => __('Your question has been submitted successfully. Will contact you soon.', NCM_txt_domain) );

            }

        }

        remove_filter('wp_mail_content_type', 'set_html_content_type');



        echo json_encode( $return );

        die;

    }

}



global $ncm_product;

$ncm_product = new NCM_Product();



}







if ( !is_admin() ) {

    

    add_action( 'the_post', 'ncm_change_post_data', 10, 1 ) ;



    function ncm_change_post_data( $post ) {



        global $ncm_narnoo_helper, $ncm;    



        $post_id = 0;

        $type = gettype ( $post );

        if( $type == 'object' ) {

            $post_id = $post->ID;

        } else if( $type == 'array' ) {

            $post_id = $post['ID'];

        } else {

            $post_id = $post;

        }



        $op_id = get_post_meta( $post->ID, 'narnoo_operator_id', true );

        $product_id = get_post_meta( $post->ID, 'narnoo_booking_id', true );



        $avg_price = get_post_meta( $post->ID, 'product_min_price', true );

        $product_gallery = get_post_meta( $post->ID, 'narnoo_product_gallery', true );



        $reservation = get_post_meta( $post->ID, 'narnoo_enable_reservation', true );

        $productDetails = '';

        if($reservation) {

            $productDetails = $ncm_narnoo_helper->ncm_product_details( $op_id, $product_id );

        } 



        $post->avg_price = $avg_price;

        $post->product_gallery = json_decode( $product_gallery );

        $post->narnoo_data = $productDetails;

        $post->narnoo_availability_data = $productDetails;



    }

}





/*********** hooks added for re-import and enable / disable single product Start ***********/



add_filter( 'page_row_actions', 'ncm_add_action_button', 10, 2 ); 

function ncm_add_action_button($actions, $post){

    if(get_post_type() === 'narnoo_product'){

        $url = add_query_arg(

            array(

              'post_id' => $post->ID,

              'action_narnoo' => 're-import',

            )

          );

        $actions['export'] = '<a href="' . esc_url( $url ) . '" >'.__('Re-import', NCM_txt_domain).'</a>';



        $reservation = get_post_meta( $post->ID, 'narnoo_enable_reservation', true );

        $url = add_query_arg(

            array(

              'post_id' => $post->ID,

              'action_narnoo' => ($reservation == 1) ? 'disable' : 'enable'

            )

          );

        if($reservation == 1) {

            $actions['narnoo_reservation'] = '<a href="' . esc_url( $url ) . '" >'.__('Disable Narnoo Bookings', NCM_txt_domain).'</a>';

        } else {

            $actions['narnoo_reservation'] = '<a href="' . esc_url( $url ) . '" >'.__('Enable Narnoo Bookings', NCM_txt_domain).'</a>';

        }

    }

    return $actions;

}



add_action( 'load-edit.php', 'ncm_narnoo_import_product', 10 );

function ncm_narnoo_import_product() {

    global $ncm;

    $post_id = ( isset($_REQUEST['post_id']) && !empty($_REQUEST['post_id']) ) ? $_REQUEST['post_id'] : '';

    $action = ( isset($_REQUEST['action_narnoo']) && !empty($_REQUEST['action_narnoo']) ) ? $_REQUEST['action_narnoo'] : '';

    if( $action == 're-import' && !empty( $post_id ) ) {



        if( $ncm->ncm_plugin_active( 'distributor' ) && class_exists ( 'Narnoo_Distributor_Helper' ) ) {

            ncm_update_distributor_operator_product( $post_id );

        } else if( $ncm->ncm_plugin_active( 'operator' ) && class_exists ( 'Narnoo_Operator_Connect_Helper' ) ) {

            ncm_update_operator_product( $post_id );

        } 



    } else if( ( $action == 'disable' || $action == 'enable' ) && !empty( $post_id ) ) {

        $res_value = ($action == 'enable') ? 1 : 0;

        update_post_meta( $post_id, 'narnoo_enable_reservation', $res_value );

    }



}



// Update product for narnoo distributer plugin.

function ncm_update_distributor_operator_product( $post_id ) {

    global $ncm;

    $user_ID        = get_current_user_id();

    $productId      = get_post_meta( $post_id, 'narnoo_product_id', true );

    $op_id          = get_post_meta( $post_id, 'narnoo_operator_id', true );



    // Fetch operator data

    $requestOperator= Narnoo_Distributor_Helper::init_api();

    $operator       = $requestOperator->business_listing( $operator_id );

    $operatorPostId = Narnoo_Distributor_Helper::get_post_id_for_imported_operator_id($op_id);

    

    // Fetch operator product data

    $requestOperator= Narnoo_Distributor_Helper::init_api('new');

    $productDetails = $requestOperator->getProductDetails( $productId, $op_id );



    if(!empty($productDetails) || !empty($productDetails->success)){

        $postData = Narnoo_Distributor_Helper::get_post_id_for_imported_product_id( $productDetails->data->productId );



        if ( !empty( $postData['id'] ) && $postData['status'] !== 'trash') {

            $post_id = $postData['id'];

            // update existing post, ensuring parent is correctly set

            $update_post_fields = array(

                'ID'            => $post_id,

                'post_title'    => $productDetails->data->title,

                'post_type'     => 'narnoo_product',

                'post_status'   => 'publish',

                'post_author'   => $user_ID,

                'post_modified' => date('Y-m-d H:i:s')

            );



            if(!empty($productDetails->data->description->summary[0]->english->text)){

                $update_post_fields['post_excerpt'] = strip_tags( $productDetails->data->description->summary[0]->english->text );

            }



            if(!empty($productDetails->data->description->description[0]->english->text)){

                $update_post_fields['post_content'] = strip_tags( $productDetails->data->description->description[0]->english->text );

            }



            wp_update_post($update_post_fields);



            update_post_meta( $post_id, 'product_description', $productDetails->data->description->description->english->text);

            update_post_meta( $post_id, 'product_excerpt',  strip_tags( $productDetails->data->description->summary->english->text ));



           // set a feature image for this post but first check to see if a feature is present



            $feature = get_the_post_thumbnail($post_id);

            if(empty($feature)){



                if( !empty( $productDetails->data->featureImage->xxlargeImage ) ){

                    $url = "https:" . $productDetails->data->featureImage->xxlargeImage;

                    $desc = $productDetails->data->title . " product image";

                    // $feature_image = media_sideload_image($url, $post_id, $desc);

                    $feature_image = media_sideload_image($url, $post_id, $desc, 'id');

                    if(!empty($feature_image)){

                        set_post_thumbnail( $post_id, $feature_image );

                        // global $wpdb;

                        // $attachment     = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $feature_image )); 

                        // if(isset($attachment[0])) {

                        //     set_post_thumbnail( $post_id, $attachment[0] );

                        // }

                    }

                }



            }



            //$response['msg'] = "Successfully re-imported product details";



            // insert/update custom fields with operator details into post

            

            if(!empty($productDetails->data->primary)){

                update_post_meta($post_id, 'primary_product',               "Primary Product");

            }else{

                update_post_meta($post_id, 'primary_product',               "Product");

            }

            

            update_post_meta($post_id, 'narnoo_operator_id',            $op_id); 

            update_post_meta($post_id, 'narnoo_operator_name',          $operator->data->profile->name);

            update_post_meta($post_id, 'parent_post_id',                $operatorPostId);

            update_post_meta($post_id, 'narnoo_booking_id',             $productDetails->data->bookingId);  

            update_post_meta($post_id, 'narnoo_product_id',             $productDetails->data->productId);

            update_post_meta($post_id, 'product_min_price',             $productDetails->data->minPrice);

            update_post_meta($post_id, 'product_avg_price',             $productDetails->data->avgPrice);

            update_post_meta($post_id, 'product_max_price',             $productDetails->data->maxPrice);

            update_post_meta($post_id, 'product_booking_link',          $productDetails->data->directBooking);

            

            update_post_meta($post_id, 'narnoo_listing_category',       $operator->data->profile->category);

            update_post_meta($post_id, 'narnoo_listing_subcategory',    $operator->data->profile->subCategory);



            if( lcfirst( $operator->data->profile->category ) == 'attraction' ){



                update_post_meta($post_id, 'narnoo_product_duration',   $productDetails->data->additionalInformation->operatingHours);

                update_post_meta($post_id, 'narnoo_product_start_time', $productDetails->data->additionalInformation->startTime);

                update_post_meta($post_id, 'narnoo_product_end_time',   $productDetails->data->additionalInformation->endTime);

                update_post_meta($post_id, 'narnoo_product_transport',  $productDetails->data->additionalInformation->transfer);

                update_post_meta($post_id, 'narnoo_product_purchase',   $productDetails->data->additionalInformation->purchases);

                update_post_meta($post_id, 'narnoo_product_health',     $productDetails->data->additionalInformation->fitness);

                update_post_meta($post_id, 'narnoo_product_packing',    $productDetails->data->additionalInformation->packing);

                update_post_meta($post_id, 'narnoo_product_children',   $productDetails->data->additionalInformation->child);

                update_post_meta($post_id, 'narnoo_product_additional', $productDetails->data->additionalInformation->additional);

                

            }

            /**

            *

            *   Import the gallery images as JSON encoded object

            *

            */

            if(!empty($productDetails->data->gallery)){

                update_post_meta($post_id, 'narnoo_product_gallery', json_encode($productDetails->data->gallery) );

            }else{

                delete_post_meta($post_id, 'narnoo_product_gallery');

            }

            /**

            *

            *   Import the video player object

            *

            */

            if(!empty($productDetails->data->featureVideo)){

                update_post_meta($post_id, 'narnoo_product_video', json_encode($productDetails->data->featureVideo) );

            }else{

                delete_post_meta($post_id, 'narnoo_product_video');

            }

            /**

            *

            *   Import the brochure object

            *

            */

            if(!empty($productDetails->data->featurePrint)){   

                update_post_meta($post_id, 'narnoo_product_print', json_encode($productDetails->data->featurePrint) );

            }else{

                delete_post_meta($post_id, 'narnoo_product_print');

            }



        }

                

    } //if success

}



// Update product for narnoo operator plugin.

function ncm_update_operator_product( $post_id ) {

    global $ncm;

    $user_ID        = get_current_user_id();

    $productId      = get_post_meta( $post_id, 'narnoo_product_id', true );

    $op_id          = get_post_meta( $post_id, 'narnoo_operator_id', true );



    $request        = Narnoo_Operator_Connect_Helper::init_api();



    // Fetch operator data

    $operator_data  = $request->getBusinessListing( $op_id ); //UPDATED THIS LINE OF CODE

    if(empty($operator_data)){ die("error notice"); }

    $operator       = $operator_data->data;

    $operatorPostId = Narnoo_Operator_Connect_Helper::get_post_id_for_imported_operator_id($op_id);

    

    // Fetch operator product data

    $productDetails = $request->getProductDetails( $productId, $op_id );



    if(!empty($productDetails) || !empty($productDetails->success)){

        $postData = Narnoo_Operator_Connect_Helper::get_post_id_for_imported_product_id( $productDetails->data->productId );



        if ( !empty( $postData['id'] ) && $postData['status'] !== 'trash') {

        

            $post_id = $postData['id'];

            // update existing post, ensuring parent is correctly set

            $update_post_fields = array(

                'ID'            => $post_id,

                'post_title'    => $productDetails->data->title,

                'post_type'     => 'narnoo_product',

                'post_status'   => 'publish',

                'post_author'   => $user_ID,

                'post_modified' => date('Y-m-d H:i:s')

            );



            if(!empty($productDetails->data->description->description)){

                foreach ($productDetails->data->description->description as $text) {

                    if( !empty( $text->english->text ) ){

                        $update_post_fields['post_content'] = $text->english->text;

                    }

                }

            }





            if(!empty($productDetails->data->description->summary)){

                foreach ($productDetails->data->description->summary as $text) {

                    if( !empty( $text->english->text ) ){

                        $update_post_fields['post_excerpt'] = strip_tags( $text->english->text );

                    }

                }

            }



            wp_update_post($update_post_fields);

            

            if(!empty($productDetails->data->description->description)){



                foreach ($productDetails->data->description->description as $text) {

                    if( !empty( $text->english->text ) ){

                        update_post_meta( $post_id, 'product_description', $text->english->text);

                    }

                }



            }





            if(!empty($productDetails->data->description->summary)){



                foreach ($productDetails->data->description->summary as $text) {

                    if( !empty( $text->english->text ) ){

                         update_post_meta( $post_id, 'product_excerpt',  strip_tags( $text->english->text ));

                    }

                }



            }



                            

            // set a feature image for this post but first check to see if a feature is present

            $feature = get_the_post_thumbnail($post_id);

            if(empty($feature)){

                if( !empty( $productDetails->data->featureImage->xxlargeImage ) ){

                    $url = "https:" . $productDetails->data->featureImage->xxlargeImage;

                    $desc = $productDetails->data->title . " product image";

                    // $feature_image = media_sideload_image($url, $post_id, $desc);

                    $feature_image = media_sideload_image($url, $post_id, $desc, 'id');

                    if(!empty($feature_image)){

                        set_post_thumbnail( $post_id, $feature_image );

                        // global $wpdb;

                        // $attachment     = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url )); 

                        // set_post_thumbnail( $post_id, $attachment[0] );

                    }

                }



            }



            //$response['msg'] = "Successfully re-imported product details";



            // insert/update custom fields with operator details into post

            if(!empty($productDetails->data->primary)){

                update_post_meta($post_id, 'primary_product',               "Primary Product");

            }else{

                update_post_meta($post_id, 'primary_product',               "Product");

            }

             

            update_post_meta($post_id, 'narnoo_operator_imported',      true);



            update_post_meta($post_id, 'narnoo_operator_id',            $op_id); 

            update_post_meta($post_id, 'narnoo_operator_name',          $operator->profile->name);

            update_post_meta($post_id, 'parent_post_id',                $operatorPostId);

            update_post_meta($post_id, 'narnoo_booking_id',             $productDetails->data->bookingId);  

            update_post_meta($post_id, 'narnoo_product_id',             $productDetails->data->productId);

            update_post_meta($post_id, 'product_min_price',             $productDetails->data->minPrice);

            update_post_meta($post_id, 'product_avg_price',             $productDetails->data->avgPrice);

            update_post_meta($post_id, 'product_max_price',             $productDetails->data->maxPrice);

            update_post_meta($post_id, 'narnoo_product_primary',        $productDetails->data->primary);

            update_post_meta($post_id, 'product_booking_link',          $productDetails->data->directBooking);

            

            update_post_meta($post_id, 'narnoo_listing_category',       $operator->profile->category);

            update_post_meta($post_id, 'narnoo_listing_subcategory',    $operator->profile->subCategory);



            if( lcfirst( $operator->profile->category ) == 'attraction' ){



                update_post_meta($post_id, 'narnoo_product_duration',   $productDetails->data->additionalInformation->operatingHours);

                update_post_meta($post_id, 'narnoo_product_start_time', $productDetails->data->additionalInformation->startTime);

                update_post_meta($post_id, 'narnoo_product_end_time',   $productDetails->data->additionalInformation->endTime);

                update_post_meta($post_id, 'narnoo_product_transport',  $productDetails->data->additionalInformation->transfer);

                update_post_meta($post_id, 'narnoo_product_purchase',   $productDetails->data->additionalInformation->purchases);

                update_post_meta($post_id, 'narnoo_product_health',     $productDetails->data->additionalInformation->fitness);

                update_post_meta($post_id, 'narnoo_product_packing',    $productDetails->data->additionalInformation->packing);

                update_post_meta($post_id, 'narnoo_product_children',   $productDetails->data->additionalInformation->child);

                update_post_meta($post_id, 'narnoo_product_additional', $productDetails->data->additionalInformation->additional);

                update_post_meta($post_id, 'narnoo_product_terms',      $productDetails->data->additionalInformation->terms);

                

            }

            /**

            *

            *   Import the gallery images as JSON encoded object

            *

            */

            if(!empty($productDetails->data->gallery)){

                update_post_meta($post_id, 'narnoo_product_gallery', json_encode($productDetails->data->gallery) );

            }else{

                delete_post_meta($post_id, 'narnoo_product_gallery');

            }

            /**

            *

            *   Import the video player object

            *

            */

            if(!empty($productDetail->datas->featureVideo)){

                update_post_meta($post_id, 'narnoo_product_video', json_encode($productDetails->data->featureVideo) );

            }else{

                delete_post_meta($post_id, 'narnoo_product_video');

            }

            /**

            *

            *   Import the brochure object

            *

            */

            if(!empty($productDetails->data->featurePrint)){   



                update_post_meta($post_id, 'narnoo_product_print', json_encode($productDetails->data->featurePrint) );

            }else{



                delete_post_meta($post_id, 'narnoo_product_print');

            }



        }

        

    } //if success*/

}



/*********** hooks added for re-import and enable / disable single product End ***********/





/*********** hooks added for enable / disable bulk product Start ***********/



add_filter( 'bulk_actions-edit-narnoo_product', 'ncm_register_product_bulk_actions' );

function ncm_register_product_bulk_actions($bulk_actions) {

    $bulk_actions['ncm_enable'] = __( 'Enable Narnoo Bookings', 'ncm_enable');

    $bulk_actions['ncm_disable'] = __( 'Disable Narnoo Bookings', 'ncm_enable');

    return $bulk_actions;

}



add_filter( 'handle_bulk_actions-edit-narnoo_product', 'ncm_product_bulk_action_handler', 10, 3 );

function ncm_product_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {

    if ( !in_array( $doaction, array( 'ncm_enable', 'ncm_disable' ) ) ) {

        return $redirect_to;

    }



    foreach ( $post_ids as $post_id ) {

        $res_value = ($doaction == 'ncm_enable') ? 1 : 0;

        $ncm_parameter = ($doaction == 'ncm_enable') ? 'ncm_bulk_product_enable_msg' : 'ncm_bulk_product_disable_msg';

        update_post_meta( $post_id, 'narnoo_enable_reservation', $res_value );

    }

    

    $redirect_to = add_query_arg( $ncm_parameter, count( $post_ids ), $redirect_to );

    return $redirect_to;

}



add_action( 'admin_notices', 'ncm_product_bulk_action_admin_notice' );

function ncm_product_bulk_action_admin_notice() {

    $msg = '';

    if( isset( $_REQUEST['ncm_bulk_product_enable_msg'] ) && !empty( $_REQUEST['ncm_bulk_product_enable_msg'] ) ) {

        $msg = $_REQUEST['ncm_bulk_product_enable_msg'].' '.__('product has been enabled successfully', NCM_txt_domain);

    } else if( isset( $_REQUEST['ncm_bulk_product_disable_msg'] ) && !empty( $_REQUEST['ncm_bulk_product_disable_msg'] ) ) {

        $msg = $_REQUEST['ncm_bulk_product_disable_msg'].' '.__('product has been disable successfully', NCM_txt_domain);

    } else if( isset( $_REQUEST['post_id'] ) && !empty( $_REQUEST['post_id'] ) ) {



        if( isset( $_REQUEST['action_narnoo'] ) && !empty( $_REQUEST['action_narnoo'] ) && !empty( $_REQUEST['post_id'] ) ) {

            if( $_REQUEST['action_narnoo'] == 'enable' ) {

                $msg = __('Product has been enable successfully', NCM_txt_domain);

            } else if( $_REQUEST['action_narnoo'] == 'disable' ) {

                $msg = __('Product has been disable successfully', NCM_txt_domain);

            } else if( $_REQUEST['action_narnoo'] == 're-import' ) {

                $msg = __('Product has been imported successfully', NCM_txt_domain);

            }

        }



    }



    if( !empty( $msg ) ) {

        echo '<div id="message" class="updated notice notice-success is-dismissible">';

        echo '<p>'.$msg.'</p>';

        echo '<button type="button" class="notice-dismiss">';

        echo '<span class="screen-reader-text">'.__('Dismiss this notice.', NCM_txt_domain).'</span>';

        echo '</button>';

        echo '</div>';

    }

}



/*********** hooks added for enable / disable bulk product End ***********/

?>