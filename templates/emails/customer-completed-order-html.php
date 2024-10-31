<?php do_action( 'ncm_email_header', $ncm_email_heading ); ?>

<p> <?php _e("Hi there. Your recent order on {ncm_site_title} has been completed. Your order details are shown below for your reference: "); ?> </p>

<?php do_action( 'ncm_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_email_footer' ); ?>