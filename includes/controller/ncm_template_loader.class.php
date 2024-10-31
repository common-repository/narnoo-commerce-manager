<?php

/*
* Template loader 
*/


if( !class_exists ( 'NCM_Template_Loader' ) ) {



    class NCM_Template_Loader {



        var $ncm_product_listing = false;

        var $ncm_product_detail = false;

        function __construct(){

            $this->ncm_product_listing = false;

            $this->ncm_product_detail = false;



            if( !is_admin() ) {

                add_filter( 'template_include', array( $this, 'ncm_template_loader' ) );

            }



            add_action( 'get_header', array( $this, 'ncm_display_notice' ), 10, 1 );



            add_filter( 'body_class', array( $this, 'ncm_body_class' ) );



            add_filter( 'post_class', array( $this, 'ncm_post_class' ), 20, 3 );



            add_action( 'ncm_before_main_content', array( $this, 'ncm_before_main_content_func' ) );



            add_action( 'ncm_after_main_content', array( $this, 'ncm_after_main_content_func' ) );

            

            add_action( 'ncm_get_sidebar', array( $this, 'ncm_get_sidebar' ) );



        }



        function ncm_display_notice() {

            global $ncm_settings;

            if( !isset( $_COOKIE['NCM_Store_Notice'] ) || $_COOKIE['NCM_Store_Notice']!='hidden' ) {

                $setting_data = $ncm_settings->ncm_get_settings_func();

                if( $setting_data['ncm_setting_demo_store'] && !empty( $setting_data['ncm_setting_demo_store_notice'] ) ) {

                    echo '<p class="ncm-store-notice demo_store">';

                    echo $setting_data['ncm_setting_demo_store_notice'];

                    echo '<a href="javascritp:void(0);" id="ncm_store_notice_links" class="ncm-store-notice_dismiss-link">';

                    echo __('Dismiss', NCM_txt_domain);

                    echo '</a></p>';

                }

            }

        }



        function ncm_body_class( $classes ) {

            global $ncm_cart, $ncm_checkout, $ncm_order;

            $classes = (array) $classes;



            if ( $ncm_cart->ncm_is_cart() ) {

                $classes[] = 'ncm-cart-page';

                $classes[] = 'ncm-page';

            

            } else if( $ncm_checkout->ncm_is_checkout() ) {

                $classes[] = 'ncm-checkout-page';

                $classes[] = 'ncm-page';

            } else if( $ncm_order->ncm_is_order() ){

                $classes[] = 'ncm-order-page';

                $classes[] = 'ncm-page';

            }



            return array_unique( $classes );

        }



        function ncm_post_class( $classes, $class = '', $post_id = '' ) {

            global $ncm_cart;

            $classes[] = get_option( 'template' );



            return array_unique($classes);

        }



        function ncm_template_loader ( $template ) {

            global $ncm_template;



            if ( is_embed() ) {

                return $template;

            }



            if ( $default_file = $this->ncm_get_template_loader_default_file() ) {

                $template = ncm_template_location( $default_file );

            }

            return $template;

        }



        function ncm_get_template_loader_default_file() {

            if ( is_singular( 'narnoo_product' ) ) {

                $this->ncm_product_detail = true;

                $default_file = 'single-narnoo_product.php';

            } elseif ( is_post_type_archive( 'narnoo_product' ) /*|| is_page( wc_get_page_id( 'shop' ) )*/ ) {

                $this->ncm_product_listing = true;

                $default_file = 'archive-narnoo_product.php';

            } else {

                $default_file = '';

            }

            return $default_file;

        }



        function ncm_is_product_listing() {

            return $this->ncm_product_listing;

        }



        function ncm_is_product_detail() {

            return $this->ncm_product_detail;

        }



        function ncm_is_front_page() {

            global $ncm_shortcode;

            return ( $this->ncm_product_listing 

                || $this->ncm_product_detail 

                || $ncm_shortcode->ncm_has_shortcode('ncm_cart') 

                || $ncm_shortcode->ncm_has_shortcode('ncm_checkout') 

                || $ncm_shortcode->ncm_has_shortcode('ncm_order') 

                || $ncm_shortcode->ncm_has_shortcode('ncm_search_product') 

                || $ncm_shortcode->ncm_has_shortcode('ncm_product_search') 

                || $ncm_shortcode->ncm_has_shortcode('ncm_product_availability') 

                || is_singular( 'narnoo_attraction' )

            );

        }



        function ncm_before_main_content_func() {

            global $ncm_template;

            $template = ncm_template_location( 'section/wrapper-start.php' );

            include( $template );

        }



        function ncm_after_main_content_func() {

            global $ncm_template;

            $template = ncm_template_location( 'section/wrapper-end.php' );

            include( $template );

        }



        function ncm_get_sidebar () {

            $template = get_option( 'template' );

            if($template != 'twentyseventeen') {

                global $ncm_template;

                $template = ncm_template_location( 'section/sidebar.php' );

                include( $template );

            }

        }



    }



    global $ncm_template_loader;

    $ncm_template_loader = new NCM_Template_Loader();



}



?>