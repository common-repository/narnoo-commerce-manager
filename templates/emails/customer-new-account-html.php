<?php do_action( 'ncm_email_header', $ncm_email_heading ); ?>

<p> <?php _e("Thanks for creating an account on My blog. Your username is {ncm_login_username}", NCM_txt_domain); ?> </p>

<p> <?php _e("You can access your account area to view your orders and change your password here: {ncm_site_link}.", NCM_txt_domain); ?> </p>

<?php do_action( 'ncm_email_footer' ); ?>