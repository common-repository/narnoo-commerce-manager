<?php do_action( 'ncm_text_email_header', $ncm_email_heading ); ?>

<?php _e("You have received an order from {ncm_user_name}. The order is as follows:",  NCM_txt_domain); ?>

<?php do_action( 'ncm_text_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_text_email_footer' ); ?>