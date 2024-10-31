<?php do_action( 'ncm_email_header', $ncm_email_heading ); ?>

<p><?php _e( "Your order has been received and is now being processed. Your order details are shown below for your reference:", NCM_txt_domain ); ?></p>

<?php do_action( 'ncm_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_email_footer' ); ?>