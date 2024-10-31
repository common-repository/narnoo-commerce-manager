<?php do_action( 'ncm_text_email_header', $ncm_email_heading ); ?>

<?php _e( "Your order has been received and is now being processed. Your order details are shown below for your reference:", NCM_txt_domain ); ?>

<?php do_action( 'ncm_text_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_text_email_footer' ); ?>