<?php
/*
* This function manage Emails Notification, Header and footer
*/
if( !class_exists ( 'NCM_Emails' ) ) {



if( file_exists( NCM_MODEL_DIR."ncm_emails.model.php" ) ) {

    include_once( NCM_MODEL_DIR."ncm_emails.model.php" );

}



class NCM_Emails extends NCM_DB_Emails {



    function __construct(){



        add_action( "wp", array( $this, "ncm_email_preview" ) );



        add_action( "ncm_email_notification", array( $this, "ncm_email_notification" ), 10, 2 );



        // Html text content hooks



        add_action( "ncm_text_email_header", array( $this, "ncm_text_email_header_func" ), 10, 1 );



        add_action( "ncm_text_set_order_content", array( $this, "ncm_text_set_order_content_func" ), 10, 1);



        add_action( "ncm_text_email_footer", array( $this, "ncm_text_email_footer_func" ) );



        // Html mail content hooks



        add_action( "ncm_email_header", array( $this, "ncm_email_header_func" ), 10, 1 );



        add_action( "ncm_set_order_content", array( $this, "ncm_set_order_content_main" ), 10, 1 );



        add_action( "ncm_email_footer", array( $this, "ncm_email_footer_func" ) );

    }



    function ncm_email_notification( $event, $id ) {

        if( !empty( $event ) ) {

            switch ( $event ) {

                case 'new_order':

                case 'customer_invoice':

                case 'customer_completed_order':

                    $this->ncm_notifiction( 'new_order', $id );

                    $this->ncm_notifiction( 'customer_completed_order', $id );

                    break;



                case 'cancelled_order':

                    $this->ncm_notifiction( 'cancelled_order', $id );

                    break;

                    

                case 'failed_order':

                    $this->ncm_notifiction( 'failed_order', $id );

                    break;



                case 'customer_on_hold_order':

                    $this->ncm_notifiction( 'customer_on_hold_order', $id );

                    break;



                case 'customer_processing_order':

                    $this->ncm_notifiction( 'customer_processing_order', $id );

                    break;



                case 'customer_refunded_order':

                    $this->ncm_notifiction( 'customer_refunded_order', $id );

                    break;



                case 'customer_note':

                    $this->ncm_notifiction( 'customer_note', $id );

                    break;



                case 'customer_reset_password':

                    $this->ncm_notifiction( 'customer_reset_password', $id );

                    break;



                case 'customer_new_account':

                    $this->ncm_notifiction( 'customer_new_account', $id );

                    break;



                default:

                    return false;

                    break;

            }

        }

    }



    function ncm_text_email_header_func( $header_text ) {

        echo " = ".$header_text." = \n\n";

    }



    function ncm_text_set_order_content_func( $order_data ) {

        $content = '';

        global $ncm_settings;

        $args = $ncm_settings->ncm_get_settings_func('emails');

        if( !empty($order_data) ) {

            $content = ncm_get_template_content( 'emails/email-html-template-text', $order_data );

        }

        echo $content;

    }



    function ncm_text_email_footer_func() {

        global $ncm_settings;

        $args = $ncm_settings->ncm_get_settings_func('emails');

        echo ( isset($args['ncm_email_footer_text']) && !empty($args['ncm_email_footer_text']) ) ? $args['ncm_email_footer_text'] : get_bloginfo('name');

    }



    function ncm_email_header_func( $header_text ) {

        global $ncm_settings;

        $args = $ncm_settings->ncm_get_settings_func('emails');

        $args['header_text'] = $header_text;

        echo ncm_get_template_content( 'emails/email-html-header', $args );

    }



    function ncm_set_order_content_main( $order_data ) {

        $content = '';

        global $ncm_settings;

        $args = $ncm_settings->ncm_get_settings_func('emails');

        if( !empty($order_data) ) {

            $content = ncm_get_template_content( 'emails/email-html-template', $order_data );

        }

        echo $content;

    }



    function ncm_email_footer_func() {

        global $ncm_settings;

        $args = $ncm_settings->ncm_get_settings_func('emails');

        $args['site_title'] = ( isset($args['ncm_email_footer_text']) && !empty($args['ncm_email_footer_text']) ) ? $args['ncm_email_footer_text'] : get_bloginfo('name');

        echo ncm_get_template_content( 'emails/email-html-footer', $args );

    }



    function ncm_get_mail_default_val( $mail_key ) {

        global $ncm_settings;

        $mail_value = $ncm_settings->ncm_get_settings_func( 'emails', $mail_key );



        $ncm_emails_temp = $this->ncm_default_email_template();

        if( isset($ncm_emails_temp[$mail_key]['fields']) && !empty($ncm_emails_temp[$mail_key]['fields']) ){

            foreach( $ncm_emails_temp[$mail_key]['fields'] as $key => $value ) {

                if( isset($value['placeholder']) && !empty($value['placeholder']) && isset($mail_value[$key]) && empty($mail_value[$key]) ) {

                    $mail_value[$key] = $value['placeholder'];

                }

            }

        }

        if( $mail_key == "ncm_email_customer_invoice" ) { $mail_value['ncm_enable_disable'] = '1'; }

        return $mail_value;

    }



    function ncm_notifiction ( $mail_type, $order_id='' ) {

        global $ncm_order, $ncm_settings, $ncm;

        $mail_key = "ncm_email_".$mail_type;

        $emails_args = $ncm_settings->ncm_get_settings_func('emails');

        $args = $this->ncm_get_mail_default_val( $mail_key );

        $return = false;

        if( isset( $args['ncm_enable_disable'] ) && $args['ncm_enable_disable'] ) {

            $order_info = $ncm_order->ncm_get_order_data( $order_id );



            $user_data = ( isset($order_info['user_data']) && !empty($order_info['user_data']) ) ? $order_info['user_data'] : '';

            $user_data = array_combine(array_map(function($str){return str_replace(" ","_",strtolower($str));},array_keys($user_data)),array_values($user_data));





            $firstname = ( isset($user_data['first_name']) && !empty($user_data['first_name']) ) ? $user_data['first_name'] : ( (isset($user_data['firstname']) && !empty($user_data['firstname']) ) ? $user_data['firstname'] : '' );



            $lastname = ( isset($user_data['last_name']) && !empty($user_data['last_name']) ) ? $user_data['last_name'] : ( (isset($user_data['lastname']) && !empty($user_data['lastname']) ) ? $user_data['lastname'] : '' );



            $user_email = ( isset($user_data['email']) && !empty($user_data['email']) ) ? $user_data['email'] : ( (isset($user_data['e_mail']) && !empty($user_data['e_mail']) ) ? $user_data['e_mail'] : '' );



            $phone = ( isset($user_data['phone\/mobile']) && !empty($user_data['phone\/mobile']) ) ? $user_data['phone\/mobile'] : ( (isset($user_data['phone']) && !empty($user_data['phone']) ) ? $user_data['phone'] : '' );



            $country = ( isset($user_data['country']) && !empty($user_data['country']) ) ? $user_data['country'] : '';

            $comment = ( isset($user_data['comment']) && !empty($user_data['comment']) ) ? $user_data['comment'] : ( (isset($user_data['comment']) && !empty($user_data['comment']) ) ? $user_data['comment'] : '' );





            $user_name = $firstname . " " . $lastname;



            $ncm_order_id = $order_info['ncm_order_id'];

            $ncm_order_date = $order_info['ncm_order_date'];

            $ncm_site_url = $ncm->ncm_site_url();



            $site_title = get_bloginfo('name');

            

            $subject = $args['ncm_subject'];

            $content_type = $args['ncm_email_type'];            



            $subject = str_replace("{site_title}", $site_title, $subject);

            $subject = str_replace("{order_number}", $ncm_order_id, $subject);

            $subject = str_replace("{order_date}", $ncm_order_date, $subject);



            $data = array();



            $order_info['ncm_first_name'] = $firstname;

            $order_info['ncm_last_name'] = $lastname;

            $order_info['ncm_email'] = $user_email;

            $order_info['ncm_phone_no'] = $phone;

            $order_info['ncm_country'] = $country;

            $order_info['ncm_comment'] = $comment;


            $data['order_info'] = $order_info;

            $data = array_merge($data, $args);

            $mail_content = $this->ncm_get_mail_content( $mail_key, $content_type, $data );



            //{ncm_site_title}

            //{ncm_user_name}

            //{ncm_login_username}

            //{ncm_order_number}

            //{ncm_site_link}

            //{ncm_payment_link}

            //{ncm_reset_password_link}



            $mail_content = str_replace("{ncm_site_title}", $site_title, $mail_content);

            $mail_content = str_replace("{ncm_user_name}", $user_name, $mail_content);

            // $mail_content = str_replace("{ncm_login_username}", '\n', $mail_content);

            $mail_content = str_replace("{ncm_order_number}", $ncm_order_id, $mail_content);

            $mail_content = str_replace("{ncm_site_link}", $ncm_site_url, $mail_content);

            // $mail_content = str_replace("{ncm_payment_link}", '\n', $mail_content);

            // $mail_content = str_replace("{ncm_reset_password_link}", '\n', $mail_content);





            if($args['ncm_email_type'] == 'plain') {

                $mail_content = html_entity_decode( str_replace("<br/>", '\n', $mail_content) );

            }

            $from_name = $emails_args['ncm_email_from_name'];

            $from_email = $emails_args['ncm_email_from_address'];



            $to_email = ( isset($args['ncm_recipient']) && !empty($args['ncm_recipient']) ) ? $args['ncm_recipient'] : $user_email;



            // echo "<br/> From Email => ".$from_email;

            // echo "<br/> To Email => ".$to_email;

            // echo "<br/> Subject => ".$subject;

            // echo "<br/> Body => ".$mail_content;

            

            // $ncm->ncm_write_log("From Email => ".$from_email);

            // $ncm->ncm_write_log("To Email => ".$to_email);

            // $ncm->ncm_write_log("Subject => ".$subject);

            // $ncm->ncm_write_log("Body => ".$mail_content);                   



            $headers = array('From: '.$from_name.' <'.$from_email.'>');

            add_filter('wp_mail_content_type', create_function('', 'return "text/'.$args['ncm_email_type'].'"; '));

            if( wp_mail( $to_email, $subject, $mail_content, $headers ) ) {

                $return = true;

            }

            remove_filter('wp_mail_content_type', 'set_html_content_type');

        }

        return $return;

    }



    function ncm_get_mail_content( $mail_key, $content_type="html", $data ) {

        global $ncm_template;

        $body = '';

        $all_templates = $ncm_template->ncm_email_templates_name();

        if( $content_type == 'html' ) {

            $template_key = $mail_key . "_html";

            if( isset($all_templates[$template_key]) && !empty($all_templates[$template_key]) ) {

                $body = $this->ncm_get_html_content( $all_templates[$template_key], $data );

            }

        } else {

            $template_key = $mail_key . "_text";

            if( isset($all_templates[$template_key]) && !empty($all_templates[$template_key]) ) {

                $body = $this->ncm_get_plain_content( $all_templates[$template_key], $data );

            }

        }

        return $body;

    }



    function ncm_get_html_content( $path, $data ) {

        return ncm_get_template_content( $path, $data );

    }



    function ncm_get_plain_content( $path, $data ) {

        return ncm_get_template_content( $path, $data );

    }



    function ncm_email_preview() {

        global $ncm_settings;

        if( isset( $_REQUEST['ncm_mail_preview'] ) && $_REQUEST['ncm_mail_preview'] == 'ncm_yes' ) {

            $args = $ncm_settings->ncm_get_settings_func('emails');

            //$this->ncm_notifiction( 'customer_completed_order', 146 );

            echo ncm_get_template_content( 'emails/email-html-preview-template', $args );

            exit;

        }

    }

}



global $ncm_email;

$ncm_email = new NCM_Emails();



}

?>