<?php do_action( 'ncm_email_header', $ncm_email_heading ); ?>

<p><?php _e( "Hi there. Your order on {ncm_site_title} has been refunded.", NCM_txt_domain ); ?></p>

<?php do_action( 'ncm_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_email_footer' ); ?>