<?php do_action( 'ncm_email_header', $ncm_email_heading ); ?>

<p> 
    <?php _e("The order #{ncm_order_number} from {ncm_user_name} has been Failed. The order was as follows:",  NCM_txt_domain); ?>
</p>

<?php do_action( 'ncm_set_order_content', $order_info ); ?>

<?php do_action( 'ncm_email_footer' ); ?>