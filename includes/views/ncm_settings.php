<?php

global $ncm, $ncm_settings, $ncm_payment_gateways;

$tabs = $ncm_settings->ncm_get_settings_tab();

$current_tab = (isset($_REQUEST['tab']) && $_REQUEST['tab']!='') ? $_REQUEST['tab'] : 'general'; 

$ncm_setting_file = 'ncm_settings_'.$current_tab.'.php';



if( isset( $_REQUEST['ncm_setting_save'] ) && isset( $_REQUEST['ncm_setting'] ) && $_REQUEST['ncm_setting'] != '' ) {

    do_action( 'ncm_save_settings', $_POST );

}



echo '<div class="wrap ncm_content">';

echo '<nav class="nav-tab-wrapper">';



    foreach( $tabs as $tab_key => $tab_value ) {

        $tab_class = 'nav-tab ';

        $tab_class.= ($current_tab == $tab_key) ? 'nav-tab-active' : '';

        $tab_url = admin_url( 'admin.php?page='.$ncm->ncm_setting.'&amp;tab='.$tab_key );

        echo '<a href="'.$tab_url.'" class="'.$tab_class.'">'.$tab_value.'</a>';

    }



echo '</nav>';



/* start display warning for payment gateway */ 

$active_gateway = $ncm_payment_gateways->ncm_get_active_payment_gateways();

$general_opt = $ncm_settings->ncm_get_settings_func();

if(!empty($active_gateway) && isset($general_opt['ncm_narnoo_api_mode']) && $general_opt['ncm_narnoo_api_mode']) {

    $checkout_opt = $ncm_settings->ncm_get_settings_func( 'checkout' );

    $testmode_gateways = array();

    foreach ($active_gateway as $gateway_name) {
        
        $gateway_data = $ncm_payment_gateways->ncm_get_active_gateways_data($gateway_name);

        $gateway_testmode_key = reset( array_intersect( array_keys( $gateway_data ), array( 'ncm_paypal_testmode', 'ncm_stripe_testmode', 'ncm_eway_sandbox' ) ) );

        if( isset($gateway_data[$gateway_testmode_key]) && $gateway_data[$gateway_testmode_key] ) {

            $testmode_gateways[] = $gateway_name;

        }

    }

    if( !empty( $testmode_gateways ) ) {

        echo '<div id="message" class="updated notice notice-success is-dismissible">';

        echo '<p>';

        echo __( 'To perform Narnoo API live payments, you need to uncheck all the given payment gateway\'s sandbox mode. The gateways having sandbox mode are <b>' ).implode(', ' , $testmode_gateways).'</b>';

        echo '</p>';

        echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.',NCM_txt_domain).'</span></button>';

        echo '</div>';

    }

}

/* end display warning for payment gateway */



if( isset($_SESSION['ncm_msg_status']) && $_SESSION['ncm_msg_status'] ) { 

    echo '<div id="message" class="updated notice notice-success is-dismissible">';

    echo '<p>';

    echo (isset($_SESSION['ncm_msg']) && $_SESSION['ncm_msg']!='') ? $_SESSION['ncm_msg'] : 'Something went wrong.';

    echo '</p>';

    echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.',NCM_txt_domain).'</span></button>';

    echo '</div>';

	unset($_SESSION['ncm_msg_status']);

	unset($_SESSION['ncm_msg']);

} 



echo '<form name="ncm_settings" id="ncm_settings" method="post" >';

    if( $ncm_setting_file != '' && file_exists(NCM_VIEWS_DIR.$ncm_setting_file) ){

		include_once( NCM_VIEWS_DIR.$ncm_setting_file );

	}

    echo '<p class="submit">';

    echo '<input type="hidden" name="ncm_setting" id="ncm_setting" value="'.$current_tab.'" />';

    echo '<input name="ncm_setting_save" class="button-primary ncm_setting_save" type="submit" value="Save changes" />';

    echo '</p>';



echo '</form>';

echo '</div>';