<?php do_action( 'ncm_email_header', $ncm_email_heading ); ?>

<p><?php _e( "Your order is on-hold until we confirm payment has been received. Your order details are shown below for your reference:", NCM_txt_domain ); ?></p>

<?php do_action( 'ncm_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_email_footer' ); ?>