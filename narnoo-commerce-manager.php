<?php
/*
Plugin Name: Narnoo Commerce Manager
Plugin URI: https://www.narnoo.com/
Description: Allows Narnoo distributors to manager their bookings via the Narnooo BYOB system.
Version: 1.6.0
Author: Narnoo Wordpress developer
Author URI: https://www.narnoo.com/
License: GPL2 or later
*/

/*  Copyright 2019  Narnoo.com  (email : info@narnoo.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// plugin definitions
define( 'NCM_PLUGIN_NM', 'Narnoo Commerce Manager');
if( file_exists(WP_PLUGIN_DIR.'/narnoo-commerce-manager-master/uninstall.php') ) {
    define( 'NCM_PLUGIN', '/narnoo-commerce-manager-master/');
} else {
    define( 'NCM_PLUGIN', '/narnoo-commerce-manager/');
}

// directory define
define( 'NCM_PLUGIN_DIR', WP_PLUGIN_DIR.NCM_PLUGIN);
define( 'NCM_INCLUDES_DIR', NCM_PLUGIN_DIR.'includes/' );
define( 'NCM_LIB_DIR', NCM_PLUGIN_DIR.'lib/' );
define( 'NCM_VIEWS_DIR', NCM_INCLUDES_DIR.'views/' );
define( 'NCM_MODEL_DIR', NCM_INCLUDES_DIR.'model/' );
define( 'NCM_CONTROLLER_DIR', NCM_INCLUDES_DIR.'controller/' );

define( 'NCM_ASSETS_DIR', NCM_PLUGIN_DIR.'assets/' );
define( 'NCM_CSS_DIR', NCM_ASSETS_DIR.'css/' );

define( 'NCM_TEMPLATE_DIR', NCM_PLUGIN_DIR.'templates/' );
define( 'NCM_TEMP_DIR', NCM_TEMPLATE_DIR.'temp_data/' );

define( 'NCM_LIB_STIRPE_DIR', NCM_LIB_DIR.'stripe/' );
define( 'NCM_LIB_EWAY_DIR', NCM_LIB_DIR.'eway/' );


// URL define
define( 'NCM_PLUGIN_URL', WP_PLUGIN_URL.NCM_PLUGIN);
define( 'NCM_ASSETS_URL', NCM_PLUGIN_URL.'assets/');
define( 'NCM_IMAGES_URL', NCM_ASSETS_URL.'images/');
define( 'NCM_CSS_URL', NCM_ASSETS_URL.'css/');
define( 'NCM_JS_URL', NCM_ASSETS_URL.'js/');

define( 'NCM_TEMPLATE_URL', NCM_PLUGIN_URL.'templates/' );
define( 'NCM_TEMP_URL', NCM_TEMPLATE_URL.'temp_data/' );

// define text domain
define( 'NCM_txt_domain', 'ncm_text_domain' );

global $ncm_version;
$ncm_version = '1.6';

class Narnoo_Commerce_Manager {

    var $tbl_tax_rate_location = '';
    var $tbl_tax_rates = '';
    var $tbl_order_item = '';
    var $tbl_order_passenger = '';

    var $ncm_commerce = '';
    var $ncm_setting = '';
    var $ncm_order_item = '';
	function __construct() {
        global $wpdb;

        $this->ncm_commerce = 'ncm_commerce';
        $this->ncm_setting = 'ncm_setting';
        $this->ncm_order_item = 'ncm_order_item';

        $this->tbl_tax_rate_location = $wpdb->prefix . "ncm_tax_rate_location";
        $this->tbl_tax_rates = $wpdb->prefix . "ncm_tax_rates";
        $this->tbl_order_item = $wpdb->prefix . "ncm_order_item";
        $this->ncm_order_booking = $wpdb->prefix . "ncm_order_booking";
        $this->tbl_order_passenger = $wpdb->prefix . "ncm_order_passenger";
        
        if( $this->ncm_plugin_active('distributor') || $this->ncm_plugin_active('operator') ) { 

    		register_activation_hook( __FILE__,  array( &$this, 'ncm_install' ) );

            register_deactivation_hook( __FILE__, array( &$this, 'ncm_deactivation' ) );

    		add_action( 'admin_menu', array( $this, 'ncm_add_menu' ) );

            add_action( 'admin_enqueue_scripts', array( $this, 'ncm_enqueue_scripts' ) );

    		add_action( 'wp_enqueue_scripts', array( $this, 'ncm_front_enqueue_scripts' ) );

            add_action('wp_head', array( $this, 'adminajaxurl' ));

            //add_action( 'wp_enqueue_scripts', array( $this, 'ncm_front_shortcode_enqueue_scripts' ) );

        } else {

            deactivate_plugins( plugin_basename( __FILE__ ) );
 
            $ncm_message = __( 'In order to operate Narnoo Commerce Manager plugin efficiently, you need to install and activate either Distributer or Operator Connect plugin.', 'NCM_txt_domain' );
         
            wp_die( $ncm_message, __( 'Require operator or distributer plugin', 'NCM_txt_domain' ), admin_url('plugins.php') );

        }
        
	}

	static function ncm_install() {

		global $wpdb, $ncm, $ncm_version;

        $charset_collate = $wpdb->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        update_option( "ncm_commerce", true );
        update_option( "ncm_version", $ncm_version );

        $table_name = $wpdb->prefix . "ncm_tax_rate_location";
        $ncm_tax_rate_location_query = "CREATE TABLE $table_name (
            location_id BIGINT(20) NOT NULL AUTO_INCREMENT,
                 VARCHAR(200) NOT NULL,
            tax_rate_id BIGINT(20) NOT NULL,
            location_type VARCHAR(40) NOT NULL,
          PRIMARY KEY  (location_id)
        ) $charset_collate;";
        dbDelta( $ncm_tax_rate_location_query );

        $table_name = $wpdb->prefix . "ncm_tax_rates";
        $ncm_tax_rates_query = "CREATE TABLE $table_name (
            tax_rate_id BIGINT(20) NOT NULL AUTO_INCREMENT,
            tax_rate_country VARCHAR(3) NOT NULL,
            tax_rate_state VARCHAR(100) NOT NULL,
            tax_rate VARCHAR(8) NOT NULL,
            tax_rate_name VARCHAR(200) NOT NULL,
            tax_rate_priority BIGINT(20) NOT NULL,
            tax_rate_compound TINYINT(1) NOT NUll,
            tax_rate_shipping TINYINT(20) NOT NULL,
            tax_rate_order BIGINT(20) NOT NULL,
            tax_rate_class VARCHAR(100) NOT NULL,
          PRIMARY KEY  (tax_rate_id)
        ) $charset_collate;";
        dbDelta( $ncm_tax_rates_query );

        $table_name = $wpdb->prefix . "ncm_order_item";
        $ncm_order_item_query = "CREATE TABLE $table_name (
            order_item_id BIGINT(20) NOT NULL AUTO_INCREMENT,
            order_id BIGINT(20) NOT NULL,
            post_id BIGINT(20) NOT NULL,
            product_id BIGINT(20) NOT NULL,
            tour_code VARCHAR(50) NOT NULL,
            booking_code VARCHAR(200) NOT NULL,
            reservation_code varchar(255) NOT NULL,
            reservation_provider VARCHAR(50) NOT NULL,
            tour_name VARCHAR(200) NOT NULL,
            travel_date VARCHAR(50) NOT NULL,
            t_date DATE,
            pickup_id VARCHAR(50) NOT NULL,
            pickup VARCHAR(200) NOT NULL,
            dropoff_id VARCHAR(50) NOT NULL,
            dropoff VARCHAR(200) NOT NULL,
            passenger TEXT NOT NULL,
            subtotal VARCHAR(50) NOT NULL,
            levy VARCHAR(50) NOT NULL,
            total VARCHAR(50) NOT NULL,
          PRIMARY KEY (order_item_id)
        ) $charset_collate;";
        dbDelta( $ncm_order_item_query );

        $table_name = $wpdb->prefix . "ncm_order_booking";
        $ncm_order_booking_query = "CREATE TABLE $table_name (
            booking_id BIGINT(20) NOT NULL AUTO_INCREMENT,
            order_id BIGINT(20) NOT NULL,
            field_label VARCHAR(100) NOT NULL,
            field_value VARCHAR(100) NOT NULL,
          PRIMARY KEY (booking_id)
        ) $charset_collate;";
        dbDelta( $ncm_order_booking_query );

        $table_name = $wpdb->prefix . "ncm_order_passenger";
        $ncm_order_passenger_query = "CREATE TABLE $table_name (
            pass_id BIGINT(20) NOT NULL AUTO_INCREMENT,
            order_id BIGINT(20) NOT NULL,
            order_item_id BIGINT(20) NOT NULL,
            passenger_id BIGINT(20) NOT NULL,
            field_label VARCHAR(100) NOT NULL,
            field_value VARCHAR(100) NOT NULL,
          PRIMARY KEY (pass_id)
        ) $charset_collate;";
        dbDelta( $ncm_order_passenger_query );
        
	}

    static function ncm_deactivation() {
        // deactivation process here
    }

    function ncm_plugin_active( $plugin_name = '' ) {    
        $return = false;
        if( !empty($plugin_name) ) {

            include_once( ABSPATH . 'wp-admin/includes/plugin.php');
            if( $plugin_name == 'distributor' ) {

                if( file_exists( WP_PLUGIN_DIR.'/narnoo-distributor/narnoo-distributor.php' ) ) {
                    if( is_plugin_active( 'narnoo-distributor/narnoo-distributor.php' ) ) {
                        $return = true;
                    }
                }

                if( file_exists( WP_PLUGIN_DIR.'/narnoo-distributor-plugin/narnoo-distributor.php' ) ) {
                    if( is_plugin_active( 'narnoo-distributor-plugin/narnoo-distributor.php' ) ) {
                        $return = true;
                    }
                }

                if( file_exists( WP_PLUGIN_DIR.'/narnoo-distributor-plugin-master/narnoo-distributor.php' ) ) {
                    if( is_plugin_active( 'narnoo-distributor-plugin-master/narnoo-distributor.php' ) ) {
                        $return = true;
                    }
                }
                
            } else if( $plugin_name == 'operator' ) {

                if( file_exists( WP_PLUGIN_DIR.'/narnoo-operator-connect/narnoo-operator-connect.php' ) ) {
                    if( is_plugin_active( 'narnoo-operator-connect/narnoo-operator-connect.php' ) ) {
                        $return = true;
                    }
                }

                if( file_exists( WP_PLUGIN_DIR.'/narnoo-operator-connect-master/narnoo-operator-connect.php' ) ) {
                    if( is_plugin_active( 'narnoo-operator-connect-master/narnoo-operator-connect.php' ) ) {
                        $return = true;
                    }
                }
            }
        }
        return $return;
    }

	function ncm_get_sub_menu() {
		$ncm_admin_menu = array(
            array(
                'name' => __('Orders', NCM_txt_domain),
                'cap'  => 'manage_options',
                'slug' => $this->ncm_commerce,
            ),
			array(
				'name' => __('Setting', NCM_txt_domain),
				'cap'  => 'manage_options',
				'slug' => $this->ncm_setting,
			),
		);
		return $ncm_admin_menu;
	}

	function ncm_add_menu() {

		$ncm_main_page_name = __('Commerce', NCM_txt_domain);
		$ncm_main_page_capa = 'manage_options';
		$ncm_main_page_slug = $this->ncm_commerce; //$this->ncm_commerce;

		$ncm_get_sub_menu   = $this->ncm_get_sub_menu();
		/* set capablity here.... Right now manage_options capability given to all page and sub pages. */
			 
		add_menu_page($ncm_main_page_name, $ncm_main_page_name, $ncm_main_page_capa, $ncm_main_page_slug, array( &$this, 'ncm_route' ),'', 11 );
		
        /* add_submenu_page(
            $ncm_main_page_slug,
            __('Your Orders', NCM_txt_domain),
            __('Orders', NCM_txt_domain),
            'manage_options',
            'noo_orders_table',
            array( &$this, 'noo_orders_table' )
            );
        
        /* add_submenu_page(
            $ncm_main_page_slug, 
            __('Shopping Cart', NCM_txt_domain),
            __('Shopping Cart', NCM_txt_domain),
            'manage_options',
            'noo_shopping_cart_table',
            array( &$this, 'noo_shopping_cart_table' )
            ); */

		foreach ($ncm_get_sub_menu as $ncm_menu_key => $ncm_menu_value) {
			add_submenu_page(
				$ncm_main_page_slug, 
				$ncm_menu_value['name'], 
				$ncm_menu_value['name'], 
				$ncm_menu_value['cap'], 
				$ncm_menu_value['slug'], 
				array( $this, 'ncm_route') 
			);	
		}
	}

	function ncm_is_activate(){
		if(get_option("ncm_commerce")) {
			return true;
		} else {
			return false;
		}
	}

	function ncm_admin_slugs() {
		$ncm_pages_slug = array(
			$this->ncm_commerce,
			$this->ncm_setting,
		);
		return $ncm_pages_slug;
	}

	function ncm_is_page() {
		if( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $this->ncm_admin_slugs() ) ) {
			return true;
		} else {
			return false;
		}
	} 

    function ncm_admin_msg( $key ) {
        $admin_msg = array(
            "no_tax" => __("No matching tax rates found.", NCM_txt_domain)
        );

        if( $key == 'script' ){
            $script = '<script type="text/javascript">';
            $script.= 'var __ncm_msg = '.json_encode($admin_msg);
            $script.= '</script>';
            return $script;
        } else {
            return isset($admin_msg[$key]) ? $admin_msg[$key] : false;
        }
    }

	function ncm_enqueue_scripts() {
		global $ncm_version;
		/* must register style and than enqueue */
		if( $this->ncm_is_page() ) {
			/*********** register and enqueue styles ***************/
            wp_register_style( 'ncm_select2_css',  NCM_CSS_URL.'ncm_select2.css', false, $ncm_version );
            wp_register_style( 'ncm_admin_style_css',  NCM_CSS_URL.'ncm_admin_style.css', false, $ncm_version );
            wp_enqueue_style( 'ncm_font_awesome_awesome_css', NCM_CSS_URL.'ncm_font_awesome.css', false, $ncm_version );
            wp_enqueue_style( 'ncm_select2_css' );
            wp_enqueue_style( 'ncm_font_awesome_css' );
            wp_enqueue_style( 'ncm_admin_style_css' );


			/*********** register and enqueue scripts ***************/
            echo $this->ncm_admin_msg( 'script' );
            wp_register_script( 'ncm_select2_js', NCM_JS_URL.'ncm_select2.js', 'jQuery', $ncm_version, true );
			wp_register_script( 'ncm_color_js', NCM_JS_URL.'ncm_color.js', 'jQuery', $ncm_version, true );
            wp_register_script( 'ncm_admin_js', NCM_JS_URL.'ncm_admin_js.js', 'jQuery', $ncm_version, true );
			wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'ncm_select2_js' );
            wp_enqueue_script( 'ncm_color_js' );
			wp_enqueue_script( 'ncm_admin_js' );

            wp_enqueue_script( 'jquery-ui-sortable' );
		}
    }

    function adminajaxurl() {

        echo '<script type="text/javascript">
               var ajaxurl = "' . admin_url('admin-ajax.php') . '";
             </script>';
    }

    function ncm_front_enqueue_scripts() {
        global $ncm_version, $ncm_template_loader,$ncm_payment_gateways;
        // need to check here if its front section than enqueue script
        // if( $ncm_template_loader->ncm_is_front_page() ) {
        /*********** register and enqueue styles ***************/

        //if(is_singular('narnoo_product')){

            wp_register_style( 
                'bootstrap_min_css',  
                NCM_CSS_URL.'bootstrap.min.css', 
                false, 
                $ncm_version 
            );

            wp_enqueue_style( 
                'ncm_font_awesome_css', 
                NCM_CSS_URL.'ncm_font_awesome.css', 
                false, 
                $ncm_version 
            );

            wp_enqueue_style( 
                'ncm_bootstrap_datepicker3_css', 
                NCM_CSS_URL.'bootstrap-datepicker3.css', 
                false, 
                $ncm_version 
            );

            wp_enqueue_style( 
                'ncm_bootstrap_datepicker3_min_css', 
                NCM_CSS_URL.'bootstrap-datepicker3.min.css', 
                false, 
                $ncm_version 
            );

            wp_register_style( 
                'ncm_select2_css',  
                NCM_CSS_URL.'ncm_select2.css', 
                false, 
                $ncm_version 
            );

            wp_register_style( 
                'ncm_timepicki_css',  
                NCM_CSS_URL.'ncm_timepicki.css', 
                false, 
                $ncm_version 
            );

            wp_register_style( 
                'ncm_front_css',  
                NCM_CSS_URL.'ncm_front.css', 
                false, 
                $ncm_version 
            );

            wp_register_style( 
                'ncm_jquery_ui',  
                NCM_CSS_URL.'ncm_jquery-ui.css', 
                false, 
                $ncm_version 
            );

            wp_register_style( 
                'ncm_lightslider_css',  
                NCM_CSS_URL.'ncm_lightslider.css', 
                false, 
                $ncm_version 
            );

            wp_enqueue_style( 'ncm_jquery_ui' );
            wp_enqueue_style( 'bootstrap_min_css' );
            wp_enqueue_style( 'ncm_font_awesome_css' );
            // datepicker css load only in product details page
            wp_enqueue_style( 'ncm_bootstrap_datepicker3_css' );
            wp_enqueue_style( 'ncm_bootstrap_datepicker3_min_css' );
            wp_enqueue_style( 'ncm_select2_css' );
            wp_enqueue_style( 'ncm_timepicki_css' );
            wp_enqueue_style( 'ncm_front_css' );
            wp_enqueue_style( 'ncm_lightslider_css' );


            /*********** Theme wise style sheet start ***************/
            
            $template = get_option( 'template' );
            if( !empty($template) ) {
                $css_file = 'ncm_front_'.$template.'.css';
                if( file_exists( NCM_CSS_DIR.$css_file ) ) {
                    wp_register_style( 
                        'ncm_front_'.$template.'_css', 
                        NCM_CSS_URL.$css_file, 
                        false, 
                        $ncm_version 
                    );
                    wp_enqueue_style( 'ncm_front_'.$template.'_css' );
                }
                

                if( file_exists( get_template_directory() ."/ncm/style.css" ) ){
                    wp_register_style( 
                        'ncm_front_theme_'.$template.'_css', 
                        get_template_directory_uri().'/ncm/style.css', 
                        false, 
                        $ncm_version 
                    );
                    wp_enqueue_style( 'ncm_front_theme_'.$template.'_css' );
                }
            }

            /*********** Theme wise style sheet End ***************/
            

            /*********** register and enqueue scripts ***************/
            //echo "<script> var ajaxurl = '".admin_url( 'admin-ajax.php' )."'; </script>";
            //echo "<script> var ncm_stripe_public_key = '".$ncm_payment_gateways->ncm_get_stripe_public_key()."'; </script>";

            wp_register_script( 
                'ncm_timepicki_js', 
                NCM_JS_URL.'ncm_timepicki.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_register_script( 
                'bootstrap_min_js', 
                NCM_JS_URL.'bootstrap.min.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_register_script( 
                'bootstrap_datepicker_js', 
                NCM_JS_URL.'bootstrap-datepicker.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_register_script( 
                'bootstrap_datepicker_min_js', 
                NCM_JS_URL.'bootstrap-datepicker.min.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_register_script( 
                'ncm_select2_js', 
                NCM_JS_URL.'ncm_select2.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_register_script( 
                'ncm_jquery_validate', 
                NCM_JS_URL.'jquery.validate.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_register_script( 
                'ncm_front_js', 
                NCM_JS_URL.'ncm_front.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_register_script( 
                'ncm_base64js', 
                NCM_JS_URL.'ncm_base64js.min.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_register_script( 
                'ncm_stripe_js', 
                'https://js.stripe.com/v1/', 
                'jQuery', 
                $ncm_version, 
                true 
            );

             wp_register_script( 
                'ncm_stripe_checkout_js', 
                'https://checkout.stripe.com/checkout.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_register_script( 
                'ncm_lightslider_js', 
                NCM_JS_URL.'ncm_lightslider.js', 
                'jQuery', 
                $ncm_version, 
                true 
            );

            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-slider' );
            wp_enqueue_script( 'bootstrap_min_js' );
            wp_enqueue_script( 'ncm_timepicki_js' );
            // datepicker js enqueue only in product detail page.
            wp_enqueue_script( 'bootstrap_datepicker_js' );
            wp_enqueue_script( 'bootstrap_datepicker_min_js' );
            wp_enqueue_script( 'ncm_select2_js' );
            wp_enqueue_script( 'ncm_jquery_validate' );
            wp_enqueue_script( 'ncm_front_js' );
            wp_enqueue_script( 'ncm_base64js' );
            // enqueue only in checkout page.
            wp_enqueue_script( 'ncm_stripe_js' );   
            wp_enqueue_script( 'ncm_stripe_checkout_js' );  
            wp_enqueue_script( 'ncm_lightslider_js' );	
        // }

        //}    
        
	}

	function ncm_route() {
		global $ncm_settings, $ncm_display_orders;
		if( isset($_REQUEST['page']) && $_REQUEST['page'] != '' ){
			switch ( $_REQUEST['page'] ) {
				case $this->ncm_commerce:
                    $ncm_order_id = isset($_REQUEST['ncm_order']) ? $_REQUEST['ncm_order'] : '';
                    $ncm_action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'edit';
                    if( !empty($ncm_order_id) && in_array( $ncm_action, array( 'edit', 'trash', 'completed', 'processing', 'on-hold', 'per_delete' ) ) ) {
                        $ncm_display_orders->ncm_disp_orders_actions();
                    } else {
                        $ncm_display_orders->ncm_disp_orders();
                    }
					break;
				case $this->ncm_setting:
					$ncm_settings->display_tab();
					break;
				default:
					_e( "Product Listing will be here", NCM_txt_domain );
					break;
			}
		}
	}

    function ncm_site_url() {
        return get_site_url( get_current_blog_id() ).'/';
    }

    function ncm_write_log( $content = '', $file_name = 'ncm_log.txt' ) {
        $file = __DIR__ . '/log/' . $file_name;    
        $file_content = "=============== Write At => " . date( "y-m-d H:i:s" ) . " =============== \r\n";
        $file_content .= $content . "\r\n\r\n";
        file_put_contents( $file, $file_content, FILE_APPEND | LOCK_EX );
    }
}


// begin!
global $ncm;
$ncm = new Narnoo_Commerce_Manager();

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_controls.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_controls.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_template.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_template.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_settings.class.php" ) ) {
	include_once( NCM_CONTROLLER_DIR."ncm_settings.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_tax.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_tax.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_payment_gateways.class.php" ) ) {
	include_once( NCM_CONTROLLER_DIR."ncm_payment_gateways.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_emails.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_emails.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_attraction.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_attraction.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_category.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_category.class.php" );
}

if( $ncm->ncm_is_activate() && !class_exists( 'WP_List_Table' ) ){
    if( file_exists( ABSPATH . "wp-admin/includes/class-wp-list-table.php" ) ) {
        require_once( ABSPATH . "wp-admin/includes/class-wp-list-table.php" );
    } else if( file_exists( NCM_CONTROLLER_DIR . "ncm_wp_list_table.class.php" ) ) {
        require_once( NCM_CONTROLLER_DIR . "ncm_wp_list_table.class.php" );
    }
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_display_orders.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_display_orders.class.php" );
}

////////////////////////////////
if( $ncm->ncm_is_activate() ) {
    if( $ncm->ncm_plugin_active( 'distributor' ) && file_exists( NCM_CONTROLLER_DIR."ncm_narnoo_helper.class.php" ) ) {
        include_once( NCM_CONTROLLER_DIR."ncm_narnoo_helper.class.php" );
    } else if( $ncm->ncm_plugin_active( 'operator' ) && file_exists( NCM_CONTROLLER_DIR."ncm_narnoo_operator_helper.class.php" ) ) {
        include_once( NCM_CONTROLLER_DIR."ncm_narnoo_operator_helper.class.php" );
    }
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_product.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_product.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_cart.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_cart.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_checkout.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_checkout.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_gateways_paypal.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_gateways_paypal.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_gateways_stripe.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_gateways_stripe.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_gateways_eway.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_gateways_eway.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_gateways_securepay.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_gateways_securepay.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_order.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_order.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_shortcode.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_shortcode.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_template_product.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_template_product.class.php" );
}

if( $ncm->ncm_is_activate() && file_exists( NCM_CONTROLLER_DIR."ncm_template_loader.class.php" ) ) {
    include_once( NCM_CONTROLLER_DIR."ncm_template_loader.class.php" );
}
