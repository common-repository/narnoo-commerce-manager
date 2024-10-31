<?php
if( !class_exists ( 'NCM_Tax' ) ) {

if( file_exists( NCM_MODEL_DIR."ncm_tax.model.php" ) ) {
    include_once( NCM_MODEL_DIR."ncm_tax.model.php" );
}

class NCM_Tax extends NCM_DB_Tax {

    var $tax_rate_file = '';
    function __construct() {
        $this->tax_rate_file = 'tax_rates.csv';
        add_action( 'admin_init', array( $this, 'ncm_register_importers' ), 1 );
    }

    public function ncm_register_importers() {
        if ( defined( 'WP_LOAD_IMPORTERS' ) ) {
            register_importer( 
                'ncm_tax_rate_csv',
                __( 'WooCommerce tax rates (CSV)', 'woocommerce' ), 
                __( 'Import <strong>tax rates</strong> to your store via a csv file.', 'woocommerce' ), 
                array( $this, 'ncm_tax_rates_importer' ) );
        }
    }

    public function ncm_tax_rates_importer() {
        global $ncm;
        // Load Importer API
        require_once ABSPATH . 'wp-admin/includes/import.php';

        if ( ! class_exists( 'WP_Importer' ) ) {
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

            if ( file_exists( $class_wp_importer ) ) {
                require $class_wp_importer;
            }
        }

        // includes
        if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_tax_import.class.php" ) ) {
            include_once( NCM_CONTROLLER_DIR."ncm_tax_import.class.php" );
        }

        // Dispatch
        $importer = new NCM_Tax_Rate_Importer();
        $importer->dispatch();
    }

    function ncm_get_tax_rate_file_url() {
        global $ncm_template;

        if( !file_exists( NCM_TEMP_DIR . $this->tax_rate_file ) ) {
            $csv_heading = array();
            $csv_heading[] = $this->ncm_get_tax_csv_heading();
            $result =  $this->ncm_get_tax_result();
            $json  = json_encode( $result );
            $result = json_decode( $json, true );

            $content_array = array_merge( $csv_heading, $result );
            $file_path = NCM_TEMP_DIR . $this->tax_rate_file;
            $ncm_template->ncm_remove_create_csv_file( $file_path, $content_array );
        }
        return NCM_TEMP_URL . $this->tax_rate_file;
    }

    function ncm_get_tax_csv_heading() { 
        return array(
            __("Country code", NCM_txt_domain),
            __("State code", NCM_txt_domain),
            __("Postcode / ZIP", NCM_txt_domain),
            __("City", NCM_txt_domain),
            __("Rate %", NCM_txt_domain),
            __("Tax name", NCM_txt_domain),
            __("Priority", NCM_txt_domain),
            __("Compound", NCM_txt_domain),
            __("Shipping", NCM_txt_domain),
            __("Tax class", NCM_txt_domain)
        );
    }

    function ncm_save_tax_rows( $params ) {
        if( isset( $params['ncm_section'] ) && !empty( $params['ncm_section'] ) ) {
            global $wpdb, $ncm, $ncm_template;
            $content_array = array();
            $content_array[] = $this->ncm_get_tax_csv_heading();
           
            $truncate_tax_rate_location = "TRUNCATE `wp_ncm_tax_rate_location`";
            $wpdb->query( $truncate_tax_rate_location );
            $truncate_tax_rates = "TRUNCATE `wp_ncm_tax_rates`";
            $wpdb->query( $truncate_tax_rates );

            $tax_rate_class = $params['ncm_section'];
            if( !empty( $params['tax_id'] ) ) {
                foreach ($params['tax_id'] as $key => $tid) {
                    $tax_rate_country = $params['tax_rate_country'][$tid];
                    $tax_rate_state = $params['tax_rate_state'][$tid];
                    $tax_rate_postcode = $params['tax_rate_postcode'][$tid];
                    $tax_rate_city = $params['tax_rate_city'][$tid];
                    $tax_rate = $params['tax_rate'][$tid];
                    $tax_rate_name = $params['tax_rate_name'][$tid];
                    $tax_rate_priority = $params['tax_rate_priority'][$tid];
                    $tax_rate_compound = $params['tax_rate_compound'][$tid];
                    $tax_rate_shipping = $params['tax_rate_shipping'][$tid];

                    $tax_rat_row = array(
                        "tax_rate_country" => $tax_rate_country,
                        "tax_rate_state" => $tax_rate_state,
                        "tax_rate_postcode" => $tax_rate_postcode,
                        "tax_rate_city" => $tax_rate_city,
                        "tax_rate_rate" => $tax_rate,
                        "tax_rate_name" => $tax_rate_name,
                        "tax_rate_priority" => $tax_rate_priority,
                        "tax_rate_compound" => $tax_rate_compound,
                        "tax_rate_shipping" => $tax_rate_shipping,
                        "tax_rate_class" => $tax_rate_class
                    );
                    $content_array[] = array_values( $tax_rat_row );

                    /********** store to database start *******/
                    $this->ncm_save_tax($tax_rat_row);
                    /********** store to database end *******/
                }
            }

            $file_path = NCM_TEMP_DIR . $this->tax_rate_file;
            $ncm_template->ncm_remove_create_csv_file( $file_path, $content_array );
        }
    }

    function ncm_get_stored_tax() {
        global $ncm;
        $content = '';
        $result = $this->ncm_get_tax_result( );
        if( !empty( $result ) ) {
            foreach ($result as $key => $tax_row_data) {
                $content.= $this->ncm_get_tax_row( $key+1, (array) $tax_row_data );
            }
        } else {
            $content = '<tr class="no_tax_row"><td colspan="9">'.$ncm->ncm_admin_msg('no_tax').'</td></tr>';
        }
        return $content;
    }

    function ncm_get_tax_row( $row_no, $row_val=array() ) {
        global $ncm_controls;
        $tax_rate_country = isset($row_val['tax_rate_country']) ? $row_val['tax_rate_country'] : '' ;
        $tax_rate_state   = isset($row_val['tax_rate_state']) ? $row_val['tax_rate_state'] : '' ;
        $tax_rate_postcode = isset($row_val['tax_rate_postcode']) ? $row_val['tax_rate_postcode'] : '' ;
        $tax_rate_city = isset($row_val['tax_rate_city']) ? $row_val['tax_rate_city'] : '' ;
        $tax_rate = isset($row_val['tax_rate']) ? $row_val['tax_rate'] : '' ;
        $tax_rate_name = isset($row_val['tax_rate_name']) ? $row_val['tax_rate_name'] : '' ;
        $tax_rate_priority = isset($row_val['tax_rate_priority']) ? $row_val['tax_rate_priority'] : '' ;
        $tax_rate_compound = isset($row_val['tax_rate_compound']) ? $row_val['tax_rate_compound'] : '' ;
        $tax_rate_shipping = isset($row_val['tax_rate_shipping']) ? $row_val['tax_rate_shipping'] : '' ;

        $content = '';
        $content.= '<tr class="ncm_tax_row" data-id="'.$row_no.'">';
        $content.= '<td>';
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "hidden",
                            "name" => "tax_id[".$row_no."]",
                            "value" => $row_no
                        )
                    );
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "text",
                            "value" => $tax_rate_country,
                            "placeholder" => "*",
                            "name" => "tax_rate_country[".$row_no."]",
                            "autocomplete" => "off", 
                        ) 
                    );
        $content.= '</td>';

        $content.= '<td>';
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "text",
                            "value" => $tax_rate_state,
                            "placeholder" => "*",
                            "name" => "tax_rate_state[".$row_no."]",
                            "autocomplete" => "off",
                        )
                    );
        $content.= '</td>';

        $content.= '<td>';
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "text",
                            "value" => $tax_rate_postcode,
                            "placeholder" => "*",
                            "name" => "tax_rate_postcode[".$row_no."]",
                        )
                    );
        $content.= '</td>';

        $content.= '<td>';
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "text",
                            "value" => $tax_rate_city,
                            "placeholder" => "*",
                            "name" => "tax_rate_city[".$row_no."]",
                        )
                    );
        $content.= '</td>';

        $content.= '<td>';
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "text",
                            "value" => $tax_rate,
                            "placeholder" => "0",
                            "name" => "tax_rate[".$row_no."]",
                        )
                    );
        $content.= '</td>';

        $content.= '<td>';
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "text",
                            "value" => $tax_rate_name,
                            "name" => "tax_rate_name[".$row_no."]",
                        )
                    );
        $content.= '</td>';

        $content.= '<td>';
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "number",
                            "step" => "1",
                            "min" => "1",
                            "value" => $tax_rate_priority,
                            "name" => "tax_rate_priority[".$row_no."]",
                        )
                    );
        $content.= '</td>';

        $content.= '<td>';
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "checkbox",
                            "class" =>"checkbox",
                            "name" => "tax_rate_compound[".$row_no."]",
                            "options" => array("1"),
                            "value" => $tax_rate_compound,
                            "hide_label" => true
                        )
                    );
        $content.= '</td>';

        $content.= '<td>';
        $content.= $ncm_controls->ncm_control( array(
                            "type" => "checkbox",
                            "class" => "checkbox",
                            "name" => "tax_rate_shipping[".$row_no."]",
                            "options" => array("1"),
                            "value" => $tax_rate_shipping,
                            "hide_label" => true
                        )
                    );
        $content.= '</td>';
        $content.= '</tr>';
        return $content;
    }

}

global $ncm_tax;
$ncm_tax = new NCM_Tax();

}
?>