<?php do_action( 'ncm_email_header', $ncm_email_heading ); ?>

<p><?php _e( "Hello, a note has just been added to your order:", NCM_txt_domain ); ?></p>

<blockquote><?php do_action('ncm_get_note'); ?></blockquote>

<p><?php _e( "For your reference, your order details are shown below.", NCM_txt_domain ); ?></p>

<?php do_action( 'ncm_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_email_footer' ); ?>