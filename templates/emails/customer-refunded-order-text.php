<?php do_action( 'ncm_text_email_header', $ncm_email_heading ); ?>

<?php _e( "Hi there. Your order on {ncm_site_title} has been refunded.", NCM_txt_domain ); ?>

<?php do_action( 'ncm_text_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_text_email_footer' ); ?>