<?php do_action( 'ncm_email_header', $ncm_email_heading ); ?>

<p> <?php _e("An order has been created for you on {ncm_site_title}. To pay for this order please use the following link: {ncm_payment_link}"); ?> </p>

<?php do_action( 'ncm_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_email_footer' ); ?>