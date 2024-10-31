<?php do_action( 'ncm_email_header', $ncm_email_heading ); ?>

<p> 
    <?php _e("You have received an order from {ncm_user_name}. The order is as follows:",  NCM_txt_domain); ?>
</p>

<?php do_action( 'ncm_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_email_footer' ); ?>