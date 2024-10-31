<?php do_action( 'ncm_text_email_header', $ncm_email_heading ); ?>

<?php _e("The order #{ncm_order_number} from {ncm_user_name} has been cancelled. The order was as follows: "); ?>

<?php do_action( 'ncm_text_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_text_email_footer' ); ?>