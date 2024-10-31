/*

Narnoo Commerce manager version : 1.0.0

Admin-End js 

*/



jQuery(document).ready(function($) {

    ncm_select();

    ncm_colorpick();

});





/************** General function Start *****************/



function ncm_colorpick() {

    if(jQuery(".ncm_colorpick").length > 0){ 

        jQuery(".ncm_colorpick").each(function(index, el) {

            console.log('test');

            jQuery(".ncm_colorpick").spectrum({

                color: "#f00"

            });

        });

    }

}



function ncm_select() {

    if(jQuery(".ncm_select").length > 0){ 

        jQuery(".ncm_select").each(function(index, el) {

            var ncm_select_config = {};

            /***** Disable search in dropdown parameter '-1' for disable or pass value that no result display *****/

            var search_result = jQuery(this).attr('data-search_result');

            if (typeof search_result !== typeof undefined && search_result !== false) {

                ncm_select_config.minimumResultsForSearch = search_result;

            }

            /*{ rtl: true },*/

            jQuery(this).select2(ncm_select_config);

        });

    }

}



/************** General function End *****************/



/*************** Setting Page General Tab Start ***************/



jQuery(document).on("change", "#ncm_allowed_countries", function() {

    if(jQuery(this).val() == 'all_except') {

        jQuery("#ncm_specific_allowed_countries").parents('tr').hide();

        jQuery("#ncm_all_except_countries").parents('tr').show();

    } else if(jQuery(this).val() == 'specific') {

        jQuery("#ncm_all_except_countries").parents('tr').hide();

        jQuery("#ncm_specific_allowed_countries").parents('tr').show();

    } else {

        jQuery("#ncm_all_except_countries").parents('tr').hide();

        jQuery("#ncm_specific_allowed_countries").parents('tr').hide();

    }

});



jQuery(document).on("change", "#ncm_ship_to_countries", function() {

    if(jQuery(this).val() == 'specific') {

        jQuery("#ncm_specific_ship_to_countries").parents('tr').show();

    } else {

        jQuery("#ncm_specific_ship_to_countries").parents('tr').hide();

    }

});



jQuery(document).on("change", "#ncm_demo_store", function() {

    if(jQuery(this).is(":checked")) {

        jQuery("#ncm_demo_store_notice").parents('tr').show();

    } else {

        jQuery("#ncm_demo_store_notice").parents('tr').hide();

    }

});



/*************** Setting Page General Tab End ***************/





/*************** Setting Page Checkout Tab Start ***************/


jQuery(document).ready(function(){
    
    jQuery("table.ncm_gateways tbody").sortable({
    
        items:"tr",
    
        cursor:"move",
    
        axis:"y",
    
        handle:"td.sort",
    
        containment: jQuery('.sortable_containment')
    
    });
    
});




jQuery(document).on("change", "#ncm_stripe_checkout", function() {

	if(jQuery(this).is(":checked")) {

		jQuery("#ncm_stripe_checkout_locale").parents('tr').show();

	} else {

		jQuery("#ncm_stripe_checkout_locale").parents('tr').hide();

	}

});



jQuery(document).on("change", "#ncm_stripe_testmode", function() {

	if(jQuery(this).is(":checked")) {

		jQuery("#ncm_stripe_publishable_key").parents('tr').hide();

		jQuery("#ncm_stripe_secret_key").parents('tr').hide();



		jQuery("#ncm_stripe_test_publishable_key").parents('tr').show();

		jQuery("#ncm_stripe_test_secret_key").parents('tr').show();

	} else {

		jQuery("#ncm_stripe_test_publishable_key").parents('tr').hide();

		jQuery("#ncm_stripe_test_secret_key").parents('tr').hide();



		jQuery("#ncm_stripe_publishable_key").parents('tr').show();

		jQuery("#ncm_stripe_secret_key").parents('tr').show();

	}

});



jQuery(document).on("change", "#ncm_eway_availability", function() {

    if(jQuery(this).val() == 'all') {

        jQuery("#ncm_eway_countries").parents('tr').hide();

    } else {

        jQuery("#ncm_eway_countries").parents('tr').show();

    }

});



jQuery(document).on("change", "#ncm_eway_sandbox", function() {

    if(jQuery(this).is(":checked")) {

        jQuery("#ncm_eway_sandbox_api_key").parents('tr').show();

        jQuery("#ncm_eway_sandbox_password").parents('tr').show();

        jQuery("#ncm_eway_sandbox_ecrypt_key").parents('tr').show();

    } else {

        jQuery("#ncm_eway_sandbox_api_key").parents('tr').hide();

        jQuery("#ncm_eway_sandbox_password").parents('tr').hide();

        jQuery("#ncm_eway_sandbox_ecrypt_key").parents('tr').hide();

    }

});



jQuery(document).on("change", "#ncm_eway_site_seal", function() {

    if(jQuery(this).is(":checked")) {

        jQuery("#ncm_eway_site_seal_code").parents('tr').show();

    } else {

        jQuery("#ncm_eway_site_seal_code").parents('tr').hide();

    }

});



jQuery(document).on("change", "#ncm_securepay_testmode", function() {

    if(jQuery(this).is(":checked")) {

        jQuery("#ncm_securepay_merchantid").parents('tr').hide();

        jQuery("#ncm_securepay_password").parents('tr').hide();



        jQuery("#ncm_securepay_test_merchantid").parents('tr').show();

        jQuery("#ncm_securepay_test_password").parents('tr').show();

    } else {

        jQuery("#ncm_securepay_test_merchantid").parents('tr').hide();

        jQuery("#ncm_securepay_test_password").parents('tr').hide();



        jQuery("#ncm_securepay_merchantid").parents('tr').show();

        jQuery("#ncm_securepay_password").parents('tr').show();

    }

});


jQuery(document).on("change", "#ncm_afterpay_testmode", function() {

    if(jQuery(this).is(":checked")) {

        jQuery("#ncm_afterpay_prod_id").parents('tr').hide();

        jQuery("#ncm_afterpay_prod_secret_key").parents('tr').hide();



        jQuery("#ncm_afterpay_test_id").parents('tr').show();

        jQuery("#ncm_afterpay_test_secret_key").parents('tr').show();

    } else {

        jQuery("#ncm_afterpay_test_id").parents('tr').hide();

        jQuery("#ncm_afterpay_test_secret_key").parents('tr').hide();



        jQuery("#ncm_afterpay_prod_id").parents('tr').show();

        jQuery("#ncm_afterpay_prod_secret_key").parents('tr').show();

    }

});



/*************** Setting Page Checkout Tab End ***************/





/*************** Setting Page Email Tab Start ***************/



/**** remaining below word transalation ****/

var view = 'View template';

var hide = 'Hide template';



jQuery( 'a.ncm_template_toggle_editor' ).text( view ).toggle( function() {

    jQuery( this ).text( hide ).closest(' .ncm_template' ).find( '.ncm_template_editor' ).slideToggle();

}, function() {

    jQuery( this ).text( view ).closest( '.ncm_template' ).find( '.ncm_template_editor' ).slideToggle();

} );



/*************** Setting Page Email Tab End ***************/





/*************** Setting Page Tax Tab Start ***************/



jQuery(document).on("click", ".ncm_tax_insert", function() {

    jQuery('.no_tax_row').remove();

    var last_row_id = parseInt(jQuery('#rates tr:last').attr('data-id'));

    var row = last_row_id+1;

    var html = jQuery("#ncm_tax_row_content").html();

    var content = html.replace(/{{ID}}/g, row);

    jQuery('#rates').append(content);

});



jQuery(document).on("click", ".ncm_tax_row", function() {

    jQuery(".ncm_tax_row").removeClass("select_row");

    jQuery(this).addClass("select_row");    

});



jQuery(document).on("click", ".remove_tax_rates", function() {

    jQuery(".select_row").remove();

    if(jQuery('.ncm_tax_row').length == 0 && jQuery('.no_tax_row').length == 0  ){

        jQuery('#rates').append('<tr class="no_tax_row"><td colspan="9">'+__ncm_msg.no_tax+'</td></tr>');

    }

});



/*************** Setting Page Tax Tab End ***************/

