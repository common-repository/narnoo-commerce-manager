<?php

if( !class_exists ( 'NCM_DB_Tax' ) ) {

class NCM_DB_Tax{



    function __construct(){



    }



    function ncm_get_tax_tab() {

        return array(

            "tax_options" => __("Tax options", NCM_txt_domain),

            "standard_rates" => __("Standard rates", NCM_txt_domain)

        );

    }



    function ncm_get_tax_default_options() {

        return array(

            'ncm_prices_include_tax' => 'yes',

            'ncm_tax_based_on' => '',

            'ncm_shipping_tax_class' => '',

            'ncm_tax_round_at_subtotal' => '',

            'ncm_tax_classes' => '',

            'ncm_tax_display_shop' => '',

            'ncm_tax_display_cart' => '',

            'ncm_price_display_suffix' => '',

            'ncm_tax_total_display' => '',

        );

    }



    function ncm_save_tax( $tax_row ) {

        global $ncm, $wpdb;

        if( is_array( $tax_row ) ) {

            extract( $tax_row );

        }

        $data = array(

            "tax_rate_country"  => $tax_rate_country,

            "tax_rate_state"    => $tax_rate_state,

            "tax_rate"          => $tax_rate_rate,

            "tax_rate_name"     => $tax_rate_name,

            "tax_rate_priority" => $tax_rate_priority,

            "tax_rate_compound" => $tax_rate_compound,

            "tax_rate_shipping" => $tax_rate_shipping,

            "tax_rate_class"    => $tax_rate_class

        );



        $formate = array(

            '%s',

            '%s',

            '%s',

            '%s',

            '%d',

            '%d',

            '%d',

            '%s'

        );



        $wpdb->insert( $ncm->tbl_tax_rates, $data, $formate );



        $tax_rate_id = $wpdb->insert_id;

        $tax_rate_location_formate = array( '%s', '%d', '%s' );

        $tax_rate_postcode_arr = explode( ',', $tax_rate_postcode );

        foreach ( $tax_rate_postcode_arr as $key => $postcode ) {

            if( !empty( $postcode ) ) {

                $postcode_data = array( 

                    "location_code" => $postcode,

                    "tax_rate_id" => $tax_rate_id,

                    "location_type" => 'postcode',

                );

                $wpdb->insert( $ncm->tbl_tax_rate_location, $postcode_data, $tax_rate_location_formate );

            }

        }



        $tax_rate_city_arr = explode( ',', $tax_rate_city );

        foreach ( $tax_rate_city_arr as $key => $city) {

            if( !empty( $city ) ) {

                $city_data = array( 

                    "location_code" => $city, 

                    "tax_rate_id" => $tax_rate_id, 

                    "location_type" => 'city',

                );

                $wpdb->insert( $ncm->tbl_tax_rate_location, $city_data, $tax_rate_location_formate );

            }

        }

    }



    function ncm_get_tax_result() {

        global $wpdb, $ncm;



        $tax_query = "SELECT tax_rate_country, tax_rate_state,

                    (

                        SELECT GROUP_CONCAT(location_code SEPARATOR ', ') 

                        FROM `".$ncm->tbl_tax_rate_location."` AS trl

                        WHERE trl.tax_rate_id = tr.tax_rate_id

                        AND trl.location_type = 'postcode'

                    ) AS tax_rate_postcode, 

                    (

                        SELECT GROUP_CONCAT(location_code SEPARATOR ', ') 

                        FROM `".$ncm->tbl_tax_rate_location."` AS trl

                        WHERE trl.tax_rate_id = tr.tax_rate_id

                        AND trl.location_type = 'city'

                    ) AS tax_rate_city,

                    tax_rate,

                    tax_rate_name,

                    tax_rate_priority,

                    tax_rate_compound,

                    tax_rate_shipping,

                    tax_rate_class

                FROM ".$ncm->tbl_tax_rates." AS tr";

        

        $result = $wpdb->get_results( $tax_query );

        return $result;

    }



}

}

