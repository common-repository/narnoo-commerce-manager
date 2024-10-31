<?php do_action( 'ncm_text_email_header', $ncm_email_heading ); ?>

<?php _e( "Someone requested that the password be reset for the following account:", NCM_txt_domain ); ?>

<?php _e( "Username: {ncm_login_username}", NCM_txt_domain ); ?>

<?php _e( "If this was a mistake, just ignore this email and nothing will happen.", NCM_txt_domain ); ?>

<?php _e( "To reset your password, visit the following address:", NCM_txt_domain ); ?>

<?php _e( "Click here to reset your password : {ncm_reset_password_link}", NCM_txt_domain ); ?>

<?php do_action( 'ncm_text_email_footer' ); ?>