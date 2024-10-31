<?php

/*
* This is a email model. 
*/

if( !class_exists ( 'NCM_DB_Emails' ) ) {



class NCM_DB_Emails{

    function __construct(){

        

    }



    function admin_email_address($emailadd) {

        $adminemail = get_option('admin_email');

        return $adminemail;         

    }



    function ncm_default_emails_option() {

        $ncm_default_option = array(

            'ncm_email_from_name' => __('admin', NCM_txt_domain),

            'ncm_email_from_address' => $this->admin_email_address($emailadd),

            //'ncm_email_header_image' => '',

            'ncm_email_footer_text' => '', 

            'ncm_email_base_color' => '#cccccc',

            'ncm_email_background_color' => '#f1f1f1',

            'ncm_email_body_background_color' => '#ffffff',

            'ncm_email_text_color' => '#3c3c3c',

        );

        return $ncm_default_option;

    }

    function ncm_enable_disable_field() {

        return array(

            "label" => __("Enable/Disable", NCM_txt_domain),

            "type" => "checkbox",

            "name" => "ncm_enable_disable",

            "id" => "ncm_enable_disable",

            "value" => "",

            "options" => array( "1" => __("Enable/Disable", NCM_txt_domain) ),

        );

    }

    function ncm_recipient_field() { 

        return array(

            "label" => __("Recipient", NCM_txt_domain),

            "type" => "text",

            "class" => "",

            "name" => "ncm_recipient",

            "id" => "ncm_recipient",

            "value" => get_bloginfo('new_admin_email')

        );

    }

    function ncm_subject_field() {

        return array(

            "label" => __("Subject", NCM_txt_domain),

            "type" => "text",

            "class" => "",

            "name" => "ncm_subject",

            "id" => "ncm_subject",

            "value" => "",

            "placeholder" => __("{site_title} New customer order ({order_number}) - {order_date}", NCM_txt_domain),

        );

    }

    function ncm_subject_paid_field() {

        return array(

            "label" => __("Subject (paid)", NCM_txt_domain),

            "type" => "text",

            "class" => "",

            "name" => "ncm_subject_paid",

            "id" => "ncm_subject_paid",

            "value" => "",

            "placeholder" => __("Your {site_title} order from {order_date}", NCM_txt_domain),

        );

    }

    function ncm_email_heading_field( $placeholder ) {

        return array(

            "label" => __("Email heading", NCM_txt_domain),

            "class" => "",

            "type" => "text",

            "name" => "ncm_email_heading",

            "id" => "ncm_email_heading",

            "value" => "",

            "placeholder" => $placeholder

        );

    }

    function ncm_email_heading_paid_field() {

        return array(

            "label" => __("Email heading (paid)", NCM_txt_domain),

            "class" => "",

            "type" => "text",

            "name" => "ncm_email_heading_paid",

            "id" => "ncm_email_heading_paid",

            "value" => "",

            "placeholder" => __("Your order details", NCM_txt_domain),

        );

    }

    function ncm_email_type_field() {

        return array(

            "label" => __("Email Type", NCM_txt_domain),

            "type" => "select",

            "name" => "ncm_email_type",

            "id" => "ncm_email_type",

            "style" => "",

            "value" => "html",

            "options" => array(

                "plain" => "Plain text",

                "html" => "HTML",

                "multipart" => "Multipart",

            ),

        );

    }

    function ncm_html_template_field() {

        $section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';

        return array(

            "label" => __("HTML Template", NCM_txt_domain),

            "type" => "template",

            "template_slug" => $section."_html",

            "copy_del_temp_theme_btn" => true,

            "view_hide_template_btn" => true,

            "email_template_design" => true,

        );

    }

    function ncm_text_template_field() {

        $section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';

        return array(

            "label" => __("Text Template", NCM_txt_domain),

            "type" => "template",

            "template_slug" => $section."_text",

            "copy_del_temp_theme_btn" => true,

            "view_hide_template_btn" => true,

            "email_template_design" => true,

        );

    }

    function ncm_subject_full_field() {

        return array(

            "label" => __("Full refund subject", NCM_txt_domain),

            "type" => "text", 

            "name" => "ncm_subject_full",

            "id" => "ncm_subject_full",  

            "value" => "", 

            "placeholder" => __("Your {site_title} order from {order_date} has been refunded", NCM_txt_domain),

        );

    }

    function ncm_subject_partial_field() {

        return array(

            "label" => __("Partial refund subject", NCM_txt_domain),

            "type" => "text",

            "name" => "ncm_subject_partial", 

            "id" => "ncm_subject_partial",

            "value" => "",

            "placeholder" => __("Your {site_title} order from {order_date} has been partially refunded", NCM_txt_domain),

        );

    }

    function ncm_heading_full_field() {

        return array(

            "label" => __("Full refund email heading", NCM_txt_domain),

            "type" => "text",

            "name" => "ncm_heading_full",

            "id" => "ncm_heading_full",

            "value" => "",

            "placeholder" => __("Order {order_number} details", NCM_txt_domain)

        );

    }



    function ncm_heading_partial_field() {

        return array(

            "label" => __("Partial refund email heading", NCM_txt_domain),

            "type" => "text",

            "name" => "ncm_heading_partial",

            "id" => "ncm_heading_partial",

            "value" => "",

            "placeholder" => __("Your order has been partially refunded", NCM_txt_domain)

        );

    }



    function ncm_default_email_template() {

        $ncm_default_email_template = array(

            'ncm_email_new_order' => array(

                "name" => __("New order", NCM_txt_domain),

                "desc" => __("New order emails are sent to chosen recipient(s) when a new order is received.", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_recipient"      => $this->ncm_recipient_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field( __("New customer order", NCM_txt_domain) ),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            'ncm_email_cancelled_order' => array(

                "name" => __("Cancelled order", NCM_txt_domain),

                "desc" => __("Cancelled order emails are sent to chosen recipient(s) when orders have been marked cancelled (if they were previously processing or on-hold).", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_recipient"      => $this->ncm_recipient_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field(  __("Cancelled order", NCM_txt_domain) ),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            'ncm_email_failed_order' => array(

                "name" => __("Failed order", NCM_txt_domain),

                "desc" => __("Failed order emails are sent to chosen recipient(s) when orders have been marked failed (if they were previously processing or on-hold).", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_recipient"      => $this->ncm_recipient_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field( __("Failed order", NCM_txt_domain) ),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            'ncm_email_customer_on_hold_order' => array(

                "name" => __("Order on-hold", NCM_txt_domain),

                "desc" => __("This is an order notification sent to customers containing order details after an order is placed on-hold.", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field( __("Thank you for your order", NCM_txt_domain) ),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            'ncm_email_customer_processing_order' => array(

                "name" => __("Processing order", NCM_txt_domain),

                "desc" => __("This is an order notification sent to customers containing order details after payment.", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field( __("Thank you for your order", NCM_txt_domain) ),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            'ncm_email_customer_completed_order' => array(

                "name" => __("Completed order", NCM_txt_domain),

                "desc" => __("Order complete emails are sent to customers when their orders are marked completed and usually indicate that their orders have been shipped.", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field( __("Your order is complete", NCM_txt_domain) ),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            'ncm_email_customer_refunded_order' => array(

                "name" => __("Refunded order", NCM_txt_domain),

                "desc" => __("Order refunded emails are sent to customers when their orders are refunded.", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field( __("Order {ncm_order_number} details", NCM_txt_domain) ),

                    // "ncm_subject_full"   => $this->ncm_subject_full_field(),

                    // "ncm_subject_partial"=> $this->ncm_subject_partial_field(),

                    // "ncm_heading_full"   => $this->ncm_heading_full_field(),

                    // "ncm_heading_partial"=> $this->ncm_heading_partial_field(),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            'ncm_email_customer_invoice' => array(

                "name" => __("Customer invoice / Order details", NCM_txt_domain),

                "desc" => __("Customer invoice emails can be sent to customers containing their order information and payment links.", NCM_txt_domain),

                "fields" => array(

                    "ncm_subject"           => $this->ncm_subject_field(),

                    "ncm_email_heading"     => $this->ncm_email_heading_field( __("Order invoice", NCM_txt_domain) ),

                    // "ncm_subject_paid"      => $this->ncm_subject_paid_field(),

                    // "ncm_email_heading_paid"=> $this->ncm_email_heading_paid_field(),

                    "ncm_email_type"        => $this->ncm_email_type_field(),

                    "ncm_html_template"     => $this->ncm_html_template_field(),

                    "ncm_text_template"     => $this->ncm_text_template_field(),

                ),

            ),

            /* 

            'ncm_email_customer_note' => array(

                "name" => __("Customer note", NCM_txt_domain),

                "desc" => __("Customer note emails are sent when you add a note to an order.", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field( __("Note added to your {site_title} order", NCM_txt_domain) ),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            'ncm_email_customer_reset_password' => array(

                "name" => __("Reset password", NCM_txt_domain),

                "desc" => __("Customer \"reset password\" emails are sent when customers reset their passwords.", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field( __("Password reset instructions", NCM_txt_domain) ),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            'ncm_email_customer_new_account' => array(

                "name" => __("New account", NCM_txt_domain),

                "desc" => __("Customer \"new account\" emails are sent to the customer when a customer signs up via checkout or account pages.", NCM_txt_domain),

                "fields" => array(

                    "ncm_enable_disable" => $this->ncm_enable_disable_field(),

                    "ncm_subject"        => $this->ncm_subject_field(),

                    "ncm_email_heading"  => $this->ncm_email_heading_field( __("Welcome to {site_title}", NCM_txt_domain) ),

                    "ncm_email_type"     => $this->ncm_email_type_field(),

                    "ncm_html_template"  => $this->ncm_html_template_field(),

                    "ncm_text_template"  => $this->ncm_text_template_field(),

                ),

            ),

            */

        );

        return $ncm_default_email_template;

    }

}

}

