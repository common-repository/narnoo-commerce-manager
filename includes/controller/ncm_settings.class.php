<?php

/*
* This is a settings class. Narnoo admin setting
*/

if( !class_exists ( 'NCM_Settings' ) ) {



if( file_exists( NCM_MODEL_DIR."ncm_settings.model.php" ) ) {

	include_once( NCM_MODEL_DIR."ncm_settings.model.php" );

}



class NCM_Settings extends NCM_DB_settings {



	function __construct(){



        add_action("in_admin_footer", array( $this, "ncm_narnoo_api_notification" ) );



		add_action("ncm_save_settings", array( $this, "ncm_save_settings_func" ), 10 , 1 );



        // filter for importing product from distributor plugin and operator connect plugin.

        add_filter("narnoo_import_only_bookalble_product", array( $this, "ncm_import_bookable_product" ) );

	}



    function ncm_import_bookable_product() {

        $import_pro = 0;

        if( is_admin() ) {

            $ncm_setting = $this->ncm_get_settings_func();

            $import_pro = isset($ncm_setting['ncm_narnoo_bookable_products']) ? $ncm_setting['ncm_narnoo_bookable_products'] : 0;

        }

        return $import_pro;

    }

    function ncm_narnoo_api_notification() {

        if( is_admin() ) {

            $ncm_setting = $this->ncm_get_settings_func();

            $narnoo_mode = isset($ncm_setting['ncm_narnoo_api_mode']) ? $ncm_setting['ncm_narnoo_api_mode'] : 0;

            if(!$narnoo_mode) {

                echo "<div style='position: fixed; text-align: center; width: auto; color: #fff; background-color: #bb0000; left: 45%; padding: 7px; top: 0; z-index: 999999;'> <span> Narnoo API is in test mode. </span> </div>";

            }

        }

    }



    function ncm_get_country_dropdown( $name="ncm_country", $id="ncm_country", $attr= array(), $inc_state=false) {

        $country_list = $this->ncm_country();

        $state_list = $this->ncm_state();

        $value = array();

        if( isset($attr['value']) && !empty( $attr['value'] ) ) {

            $value = ( is_array( $attr['value'] ) ) ? $attr['value'] : array( $attr['value'] );

            unset($attr['value']);

        }

        $country = '';

        $country .= '<select name="'.$name.'" id="'.$id.'" ';

        if( !empty($attr) ) {

            foreach ($attr as $a_key => $a_value) {

                $country .= $a_key.'="'.$a_value.'" ';

            }

        }

        $country .= '>';

        foreach ( $country_list as $c_key => $c_value ) {

            if( $inc_state && isset( $state_list[$c_key] ) && is_array( $state_list[$c_key] ) ) {

                $country .= '<optgroup label="'.$c_value.'">';

                foreach ($state_list[$c_key] as $s_key => $s_value) {

                    $cs_value = $c_key.':'.$s_key;

                    $state_select = ( !empty($value) && in_array($cs_value, $value) ) ? 'selected="selected"' : '';

                    $country .= '<option value="'.$cs_value.'" '.$state_select.'>'.$s_value.'</option>';

                }

                $country .= '</optgroup>';

            } else {

                $country_select = ( !empty($value) && in_array($c_key, $value) ) ? 'selected="selected"' : '';

                $country .= '<option value="'.$c_key.'" '.$country_select.'>'.$c_value.'</option>';

            }

        }

        $country .= '</select>';

        return $country;

    }



	function display_tab( ) {

		if( file_exists(NCM_VIEWS_DIR."ncm_settings.php") ) {

			include_once(NCM_VIEWS_DIR."ncm_settings.php");

		}

	}



	

	function ncm_get_settings_func( $tab = 'general', $section = '' ) {

		global $ncm, $ncm_settings, $ncm_payment_gateways, $ncm_email, $ncm_controls, $ncm_tax;

		if($tab != '')

		{

			switch ( $tab ) {

				case 'general':

					$ncm_default_general_option = $ncm_settings->ncm_default_general_option();

					$ncm_setting_option = get_option('ncm_setting_'.$tab);

					return shortcode_atts( $ncm_default_general_option, $ncm_setting_option );

					break;

                case 'tax':

                    $ncm_default_general_option = $ncm_tax->ncm_get_tax_default_options();

                    $ncm_setting_option = get_option('ncm_setting_'.$tab);

                    return shortcode_atts( $ncm_default_general_option, $ncm_setting_option );

                    break;

				case 'checkout':

					$ncm_default_checkout_option = $ncm_payment_gateways->ncm_default_checkout_option();

					$ncm_setting_option = get_option('ncm_setting_'.$tab);

					return shortcode_atts( $ncm_default_checkout_option, $ncm_setting_option );

					break;

                case 'emails':

                    if( !empty( $section ) ) {

                        $ncm_section_option = get_option($section);

                        $ncm_emails_temp = $ncm_email->ncm_default_email_template();

                        if( isset($ncm_emails_temp[$section]) && !empty($ncm_emails_temp[$section]) ){

                            $sec_fields = $ncm_emails_temp[$section];

                            $def_val = $ncm_controls->ncm_get_fields_default_val($sec_fields['fields']);

                            return shortcode_atts( $def_val, $ncm_section_option );

                        }

                        return $ncm_section_option;

                    } else {

                        $ncm_default_emails_option = $ncm_email->ncm_default_emails_option();

                        $ncm_setting_option = get_option('ncm_setting_'.$tab);

                        return shortcode_atts( $ncm_default_emails_option, $ncm_setting_option );

                    }

                    break;

				default:

					# code...

					break;

			}

			

		}

	}



	function ncm_save_settings_func( $params = array() ) {

        global $ncm_email, $ncm_template, $ncm_tax;

		if( isset( $params['ncm_setting'] ) && $params['ncm_setting'] != '') {

			$ncm_setting = $params['ncm_setting'];

            unset( $params['ncm_setting'] );

			unset( $params['ncm_setting_save'] );



			switch ( $ncm_setting ) {

				case 'general':

					update_option('ncm_setting_general', $params);

					break;

                case 'tax':

                    if( isset($params['ncm_section']) && !empty($params['ncm_section']) ) {

                        $ncm_tax->ncm_save_tax_rows( $params );

                    } else {

                        update_option('ncm_setting_tax', $params);

                    }

                    break;

				case 'checkout':					

					update_option('ncm_setting_checkout', $params);

					break;

                case 'emails':

                    if( isset($params['ncm_section']) && !empty($params['ncm_section']) ) {

                        $email_list = array_keys($ncm_email->ncm_default_email_template());

                        if( is_array($email_list) && in_array($params['ncm_section'], $email_list ) ) {

                            /******* update theme template file start ******/

                            if( isset($params['ncm_file_slug']) && !empty($params['ncm_file_slug']) ) {

                                foreach ($params['ncm_file_slug'] as $key => $file_slug) {

                                    if( !empty( $file_slug ) && isset( $params[$file_slug] ) ) {

                                        $ncm_template->ncm_edit_template( $file_slug, $params[$file_slug] );

                                        unset( $params[$file_slug] );

                                    }

                                }

                                unset( $params['ncm_file_slug'] );

                            }

                            /******* update theme template file start ******/



                            $option_name = $params['ncm_section'];

                            unset( $params['ncm_section'] );

                            update_option($option_name, $params);

                        }

                    } else {

                        update_option('ncm_setting_emails', $params);

                    }

                    break;

				default:

					break;

			}



			$_SESSION['ncm_msg_status'] = true;

			$_SESSION['ncm_msg'] =  $ncm_setting.' settings updated successfully.';

		}

	}



    function ncm_get_pages( $fields = array() ) {

        $args = array(

            'sort_order' => 'asc',

            'sort_column' => 'post_title',

            'hierarchical' => 1,

            'exclude' => '',

            'include' => '',

            'meta_key' => '',

            'meta_value' => '',

            'authors' => '',

            'child_of' => 0,

            'parent' => -1,

            'exclude_tree' => '',

            'number' => '',

            'offset' => 0,

            'post_type' => 'page',

            'post_status' => 'publish'

        ); 

        return get_pages($args); 

    }



    function ncm_get_pages_selectbox( $name = 'ncm_pages', $class='ncm_select', $seleted_value='0' ) {

        global $ncm_controls;

        $options = array();

        foreach ($this->ncm_get_pages() as $key => $value) {

            $options[$value->ID] = $value->post_title;

        }



        $attr = array(

            "type" => "select",

            "name" => $name,

            "id" => $name,

            "value" => $seleted_value,

            "class" => $class,

            "style" => "",

            "options" => $options

        );

        return $ncm_controls->ncm_control( $attr );

    }



    function ncm_get_currency_symbol( $currency ) {

        $ncm_currency = $this->ncm_currency_symbol();

        return ( isset( $ncm_currency[$currency] ) ) ? $ncm_currency[$currency] : $currency;

    }



    function ncm_display_price( $price = 0 ) {

        $return = $price;

        $general_setting = $this->ncm_get_settings_func();

        $currency = $general_setting['ncm_setting_currency'];

        $currency_pos = $general_setting['ncm_setting_currency_pos'];

        $price_thousand_sep = $general_setting['ncm_setting_price_thousand_sep'];

        $price_decimal_sep = $general_setting['ncm_setting_price_decimal_sep'];

        $price_num_decimals = $general_setting['ncm_setting_price_num_decimals'];



        $currency_symbol = $this->ncm_get_currency_symbol( $currency );

        $price = number_format( $price , $price_num_decimals, $price_decimal_sep , $price_thousand_sep );



        switch ($currency_pos) {

            case 'right':

                $return = $price . $currency_symbol;

                break;



            case 'right_space':

                $return = $price . ' ' . $currency_symbol;

                break;



            case 'left':

                $return = $currency_symbol . $price;

                break;



            case 'left_space':

                $return = $currency_symbol . ' ' . $price;

                break;



            default:

                $return = $price;

                break;

        }

        return $return;

    }



    function ncm_get_currency() {

        $general_setting = $this->ncm_get_settings_func();

        return $general_setting['ncm_setting_currency'];

    }



    function ncm_get_admin_users( $all_user = false, $fields = 'ID' ) {

        global $wpdb;

        $args = array(

            'role'         => 'Administrator',

            'orderby'      => 'ID',

            'order'        => 'ASC',

            'fields'       => $fields 

        );

        $users = get_users( $args );

        if( $all_user ) {

            return $users;

        } else {

            return $users[0];

        }

    }

}



global $ncm_settings;

$ncm_settings = new NCM_Settings();



}

?>