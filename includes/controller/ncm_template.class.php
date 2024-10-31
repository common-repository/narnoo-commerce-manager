<?php

/*
* The required templates are included here. 
*
*/

if( !class_exists ( 'NCM_Template' ) ) {

    class NCM_Template {

        function __construct(){
            add_action( "init", array( $this, 'ncm_copy_delete_template' ) );
        }

        function ncm_template_location ( $template ) {
            $theme_dir = get_template_directory().'/ncm/';
            $template_url = false;
            if( $template != '' && file_exists( $theme_dir . $template ) ) {
                $template_url = $theme_dir . $template;
            } else if( $template != '' && file_exists( NCM_TEMPLATE_DIR . $template ) ) {
                $template_url = NCM_TEMPLATE_DIR . $template;
            } 
            return $template_url;
        }

        function ncm_copy_delete_template() {
            if( isset( $_REQUEST['template_slug'] ) && ( isset( $_REQUEST['move_template'] ) || isset( $_REQUEST['remove_template'] ) ) ) {
                $template_slug = (isset($_REQUEST['template_slug'])!='') ? $_REQUEST['template_slug'] : '';
                $move_template = (isset($_REQUEST['move_template'])!='') ? $_REQUEST['move_template'] : '';
                $remove_template = (isset($_REQUEST['remove_template'])!='') ? $_REQUEST['remove_template'] : '';
                $template_files = $this->ncm_template_files();
                if( isset( $template_files[$template_slug] ) ) {
                    $temp_theme_file = $template_files[$template_slug]['template_theme_file'];
                    $temp_file = $template_files[$template_slug]['template_file'];
                    if( $move_template == $template_slug && wp_mkdir_p( dirname( $temp_theme_file ) ) && file_exists( $temp_file ) &&!file_exists( $temp_theme_file ) ) {
                        copy( $temp_file, $temp_theme_file );
                    }
                    else if( $remove_template == $template_slug && file_exists( $temp_theme_file ) ) {
                        wp_delete_file( $temp_theme_file );
                    }
                }
            }
        }

        function ncm_edit_template( $template_slug, $template_content = '' ) {
            $template_files = $this->ncm_get_template_file_path( $template_slug, true );
            $return = false;
            if( $template_files != '' ) {
                $code = wp_unslash( $template_content );
                if ( is_writeable( $template_files ) ) {
                    $file = fopen( $template_files, 'w+' );
                    if ( false !== $file ) {
                        fwrite( $file, $code );
                        fclose( $file );
                        $return = true;
                    }
                }
            }
            return $return;
        }

        function ncm_get_template_file_path( $template_slug, $theme_file_url = false ) {
            $template_file_urls = $this->ncm_template_files();
            $file_url = '';
            if( isset( $template_file_urls[$template_slug] ) ){
                $files = $template_file_urls[$template_slug];
                if( $theme_file_url && isset( $files['template_theme_file'] ) ) {
                    $file_url = $files['template_theme_file'];
                } else if( !$theme_file_url && isset( $files['template_file'] ) ) {
                    $file_url = $files['template_file'];
                }
            }
            return $file_url;
        }

        function ncm_email_templates() {
            return array(
                'ncm_email_new_order_html' => 'emails/admin-new-order-html.php', 
                'ncm_email_new_order_text' => 'emails/admin-new-order-text.php',
                'ncm_email_cancelled_order_html' => 'emails/admin-cancelled-order-html.php',
                'ncm_email_cancelled_order_text' => 'emails/admin-cancelled-order-text.php',
                'ncm_email_failed_order_html' => 'emails/admin-failed-order-html.php',
                'ncm_email_failed_order_text' => 'emails/admin-failed-order-text.php',
                'ncm_email_customer_on_hold_order_html' => 'emails/customer-on-hold-order-html.php',
                'ncm_email_customer_on_hold_order_text' => 'emails/customer-on-hold-order-text.php',
                'ncm_email_customer_processing_order_html' => 'emails/customer-processing-order-html.php',
                'ncm_email_customer_processing_order_text' => 'emails/customer-processing-order-text.php',
                'ncm_email_customer_completed_order_html' => 'emails/customer-completed-order-html.php',
                'ncm_email_customer_completed_order_text' => 'emails/customer-completed-order-text.php',
                'ncm_email_customer_refunded_order_html' => 'emails/customer-refunded-order-html.php',
                'ncm_email_customer_refunded_order_text' => 'emails/customer-refunded-order-text.php',
                'ncm_email_customer_invoice_html' => 'emails/customer-invoice-html.php',
                'ncm_email_customer_invoice_text' => 'emails/customer-invoice-text.php',
                'ncm_email_customer_note_html' => 'emails/customer-note-html.php',
                'ncm_email_customer_note_text' => 'emails/customer-note-text.php',
                'ncm_email_customer_reset_password_html' => 'emails/customer-reset-password-html.php',
                'ncm_email_customer_reset_password_text' => 'emails/customer-reset-password-text.php',
                'ncm_email_customer_new_account_html' => 'emails/customer-new-account-html.php',
                'ncm_email_customer_new_account_text' => 'emails/customer-new-account-text.php',
            );
        }

        function ncm_email_templates_name() {
            return array(
                'ncm_email_new_order_html' => 'emails/admin-new-order-html',
                'ncm_email_new_order_text' => 'emails/admin-new-order-text',
                'ncm_email_cancelled_order_html' => 'emails/admin-cancelled-order-html',
                'ncm_email_cancelled_order_text' => 'emails/admin-cancelled-order-text',
                'ncm_email_failed_order_html' => 'emails/admin-failed-order-html',
                'ncm_email_failed_order_text' => 'emails/admin-failed-order-text',
                'ncm_email_customer_on_hold_order_html' => 'emails/customer-on-hold-order-html',
                'ncm_email_customer_on_hold_order_text' => 'emails/customer-on-hold-order-text',
                'ncm_email_customer_processing_order_html' => 'emails/customer-processing-order-html',
                'ncm_email_customer_processing_order_text' => 'emails/customer-processing-order-text',
                'ncm_email_customer_completed_order_html' => 'emails/customer-completed-order-html',
                'ncm_email_customer_completed_order_text' => 'emails/customer-completed-order-text',
                'ncm_email_customer_refunded_order_html' => 'emails/customer-refunded-order-html',
                'ncm_email_customer_refunded_order_text' => 'emails/customer-refunded-order-text',
                'ncm_email_customer_invoice_html' => 'emails/customer-invoice-html',
                'ncm_email_customer_invoice_text' => 'emails/customer-invoice-text',
                'ncm_email_customer_note_html' => 'emails/customer-note-html',
                'ncm_email_customer_note_text' => 'emails/customer-note-text',
                'ncm_email_customer_reset_password_html' => 'emails/customer-reset-password-html',
                'ncm_email_customer_reset_password_text' => 'emails/customer-reset-password-text',
                'ncm_email_customer_new_account_html' => 'emails/customer-new-account-html',
                'ncm_email_customer_new_account_text' => 'emails/customer-new-account-text',
            );
        }

        function ncm_template_files() {
            $theme_dir = get_template_directory().'/ncm/';
            $theme_folder = get_template().'/ncm/';
            $template_files = array();
            $template = $this->ncm_email_templates();
            foreach ($template as $t_key => $t_value) {
                $template_files[$t_key] = array(
                    "template_url" => NCM_PLUGIN . 'template/' . $t_value,
                    "template_theme_url" => $theme_folder . $t_value,
                    "template_file" => NCM_TEMPLATE_DIR . $t_value,
                    "template_theme_file" => $theme_dir . $t_value,
                );
            }
            return $template_files;
        }

        function ncm_remove_create_csv_file( $file_path, $content = array() ) {
            $file_path = !empty( $file_path ) ? $file_path : NCM_TEMP_DIR . 'temp.csv';
            if( file_exists( $file_path ) ) {
                unlink( $file_path );
            }
            if( !empty( $content ) && is_array( $content ) ) {
                $csv_file = fopen( $file_path, 'w+' );
                foreach ($content as $csv_key => $csv_value) {
                    fputcsv( $csv_file, $csv_value );
                }
                fclose($csv_file);
            }
        }
    }

    global $ncm_template;
    $ncm_template = new NCM_Template();
}

if ( ! function_exists( 'ncm_template_location' ) ) :
    function ncm_template_location ( $template ) {
        $theme_dir = get_template_directory().'/ncm/';
        $template_url = false;
        if( $template != '' && file_exists( $theme_dir . $template ) ) {
            $template_url = $theme_dir . $template;
        } else if( $template != '' && file_exists( NCM_TEMPLATE_DIR . $template ) ) {
            $template_url = NCM_TEMPLATE_DIR . $template;
        } 
        return $template_url;
    }
endif;

if ( ! function_exists( 'ncm_get_template' ) ) :
    function ncm_get_template ( $template, $args = array() ) {
        //echo "<pre>";print_r($args);echo "</pre>";
        if ( ! empty( $args ) && is_array( $args ) ) {
            extract( $args );
        }
        $template_name = $template.'.php';

        if( $temp_location = ncm_template_location( $template_name ) ) {
            include( $temp_location );
        }
    }
endif;

if ( ! function_exists( 'ncm_get_template_content' ) ) :
    function ncm_get_template_content ( $template, $args = array() ) {
        ob_start();
        ncm_get_template( $template, $args );
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
endif;
?>