<?php

    global $ncm_settings;

    $current_date = strtotime($bookingDate);

    $availability = ($availability >= 0) ? $availability : 0; 

    if($availability > 0) {

        $availability_class = "ncm_availability_green";

    } else {

        $availability_class = "ncm_availability_red";

    }



    //$toottip_data = __( 'No Tour Available', NCM_txt_domain );

    $date = date('d, F Y', $current_date);


    $ncm_temp_date_day = date("D", $current_date);
    $ncm_temp_date_moye = date("d M", $current_date);

   
    $passenger_type = $price;

    
    //if( count($passenger_type) > 5 ) {
    if( count($passenger_type) == 0 ) {

        

        $can_option_list_display_in_popup = true;

        $toottip_data = 'Click to view product options';



        $data_availability_class = ($availability > 0) ? 'ncm_availability_green' : 'ncm_availability_red';

        $ncm_temp_date = date("d-m-Y H:i:s", $current_date);





        $ncm_option_list = array();

        foreach ($passenger_type as $key => $person_type) {

            $pt_label = isset( $person_type['label'] ) ? $person_type['label'] : '';

            $pt_price = isset( $person_type['price'] ) ? $person_type['price'] : 0;

            $person_type['price'] = $ncm_settings->ncm_display_price( $pt_price );

            $ncm_option_list[] = $person_type;

            $ncm_price = $pt_price + $pt_levy;

            if($new_price == '-' || strtolower($pt_label) == 'adult') {

                $new_price = '<a 

                    href="javascript:;" 

                    id="ncm_option_list_selected_date" 

                    class="'.$data_availability_class.'" 

                    data-ncm_date="'.$ncm_temp_date.'" 

                    data-ncm_booingCodes="'.$ncm_bookingcode.'"

                    data-ncm_narnoo_bdate="'.$ncm_booking_date.'"

                    >' . $ncm_settings->ncm_display_price( $ncm_price );

            }

        }



        $product_date_html = '<a 

            href="javascript:void();" 

            id="ncm_select_date" 

            class="'.$data_availability_class.'" 

            data-ncm_date="'.$ncm_temp_date.'" 

            data-ncm_booingCodes="'.$ncm_bookingcode.'"

            data-ncm_narnoo_bdate="'.$ncm_booking_date.'"

            >';

        

        $product_date_html .= $ncm_date;

        $product_date_html .= '</a>';



        $ajax_data = array();

        $ajax_data['action'] = 'ncm_calendar_option_list_popup';

        $ajax_data['product_name'] = $ncm_product_name;

        $ajax_data['product_date'] = $ncm_date;

        $ajax_data['product_date_html'] = $product_date_html;

        $ajax_data['departure'] = $ncm_time;

        $ajax_data['seats_available'] = $availability;

        $ajax_data['ncm_option_list'] =  $ncm_option_list ;

        $ajax_data['cart_attr']['id'] = "ncm_select_date";

        $ajax_data['cart_attr']['data-ncm_date'] = $ncm_temp_date;

        $ajax_data['cart_attr']['data-ncm_booingCodes'] = $ncm_bookingcode;

        $ajax_data['cart_attr']['data-ncm_narnoo_bdate'] = $ncm_booking_date;



        $new_price .= '<textarea style="display:none;">'.json_encode( $ajax_data ).'</textarea>';

        $new_price .= '</a>';

    

    } else {



        $toottip_data = '<strong>'.$ncm_sub_product_name.'</strong>';

        $toottip_data.= '<p><span>'.$date.'</span><br>';

        $toottip_data.= '<p><strong><span>'.__('Seats Available :', NCM_txt_domain).' '.$availability.'</span></strong></p>';



        $toottip_data.= '<table>';

        $toottip_data.= '<thead>';

        $toottip_data.= '<tr>';

        $toottip_data.= '<th>'.__('Passenger Type', NCM_txt_domain).'</th>';

        $toottip_data.= '<th>'.__('Sell', NCM_txt_domain).'</th>';

        $toottip_data.= '</tr>';

        $toottip_data.= '</thead>';

        $toottip_data.= '<tbody>';



        $new_price = '-';

        foreach ($passenger_type as $key => $person_type) {

            $pt_label = isset( $person_type['label'] ) ? $person_type['label'] : '';

            $pt_id    = isset( $person_type['id'] ) ? $person_type['id'] : '';

            $pt_price = isset( $person_type['price'] ) ? $person_type['price'] : 0;

            $pt_levy  = isset( $person_type['levy'] ) ? $person_type['levy'] : 0;

            //$ncm_price = $pt_price; // + $pt_levy;
            $ncm_price = $pt_price + $pt_levy;

            if($new_price == '-' || strtolower($pt_label) == 'adult') {

                $new_price_start = '<a href="javascript:;" id="ncm_select_date" '.$ncm_link_attr.' >';

                $new_price = '<a href="javascript:;" id="ncm_select_date" '.$ncm_link_attr.' >' . $new_price = $ncm_settings->ncm_display_price( $ncm_price ) . '</a>';

                $new_price_end = '</a>';

            } 

            $toottip_data.= '<tr>';

            $toottip_data.= '<td>'.$pt_label.'</td>';

            $toottip_data.= '<td>'.$ncm_settings->ncm_display_price( $ncm_price ).'</td>';

            $toottip_data.= '</tr>';

        }

        $toottip_data.= '</tbody>';

        $toottip_data.= '</table>';

    }

?>



<div class="ncm-col-lg-3 ncm-col-md-4 ncm-col-sm-4 ncm-col-xs-6 ncm_date ncm_price_model <?php echo $availability_class; ?> ">

    <?php echo $new_price_start; ?>

    <div class="product-model-availability-list" data-toggle="tooltip" data-html="true" title="<?php echo $toottip_data; ?>"> 

        <span class="product-title">
            <div class="product-header">
                <center>
                    <?php echo $ncm_temp_date_day; ?>
                </center>
            </div>
            <div class="product-body <?php echo $availability_class; ?>">
                <center>
                    <?php echo $ncm_temp_date_moye; ?>
                </center>
                <center>
                    <?php echo $ncm_time; ?> 
                </center>
            </div>
            <div class="product-footer">
                <center>
                    <?php echo $availability; ?>
                </center>
            </div>
        </span> 

    </div>

    <h6 style="display: none;" class="product-price mt5" data-toggle="tooltip" data-html="true" title="<?php echo $toottip_data; ?>">

        <center>

            <?php echo $new_price; ?>

        </center>

    </h6>

    <?php echo $new_price_end; ?>

</div>

