<?php do_action( 'ncm_text_email_header', $ncm_email_heading ); ?>

<?php _e( "Hello, a note has just been added to your order:", NCM_txt_domain ); ?>

<?php do_action('ncm_get_note'); ?>

<?php _e( "For your reference, your order details are shown below.", NCM_txt_domain ); ?>

<?php do_action( 'ncm_text_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_text_email_footer' ); ?>