/*

Narnoo Commerce manager version : 1.0.0

Front-End js 

*/



// <a href="#" data-toggle="tooltip" title="Hooray!">Hover over me</a>;

function load_tooltip() {

    jQuery('[data-toggle="tooltip"]').tooltip({

        placement: "left",

        template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner large"></div></div>',

    });

}



function ncm_load_datepicker() {

    jQuery('.ncm_datepicker').each( function() {

        var format = jQuery(this).attr('data-format');

        var startDate = jQuery(this).attr('data-startDate');

        var endDate = jQuery(this).attr('data-endDate');



        item = {}

        item ["format"] = format;

        if (typeof startDate !== typeof undefined && startDate !== false) {

            item ["startDate"] = startDate;

        }

        if (typeof startDate !== typeof undefined && startDate !== false) {

            item ["endDate"] = endDate;

        }

        jQuery(this).datepicker(item);

    });



    jQuery('.ncm_timepicker').each( function() {

        jQuery(this).timepicki();

    });

}



function getCookie(cname) {

    var name = cname + "=";

    var decodedCookie = decodeURIComponent(document.cookie);

    var ca = decodedCookie.split(';');

    for(var i = 0; i <ca.length; i++) {

        var c = ca[i];

        while (c.charAt(0) == ' ') {

            c = c.substring(1);

        }

        if (c.indexOf(name) == 0) {

            return c.substring(name.length, c.length);

        }

    }

    return "";

}



function setCookie(cname, cvalue) {

    var d = new Date();

    document.cookie = cname + "=" + cvalue + ";;path=/";

}



function ncm_Base64Encode(str, encoding = 'utf-8') {

    var bytes = new (TextEncoder || TextEncoderLite)(encoding).encode(str);        

    return base64js.fromByteArray(bytes);

}



function ncm_Base64Decode(str, encoding = 'utf-8') {

    var bytes = base64js.toByteArray(str);

    return new (TextDecoder || TextDecoderLite)(encoding).decode(bytes);

}



function ncm_select() {

    if(jQuery(".ncm_select").length > 0){ 

        jQuery(".ncm_select").each(function() {

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



function ncm_call_ajax_onload() {

    if( jQuery('#ncm_post_id').length ) {

        var ncm_post_id = jQuery("#ncm_post_id").val();

        if( ncm_post_id > 0 ) {

            ncm_display_availability();

        }

    }



    if( jQuery('#ncm_page').length ){

        var page = jQuery('#ncm_page').val();

        if( page == 'checkout') {

            ncm_product_passenger();

            ncm_select_first_payment_gateway();

        }

        if( page == 'cart') {

            ncm_cart();

        }

    }

}





jQuery(document).ready(function($) {

    ncm_load_tabs();

    ncm_load_datepicker();

    ncm_call_ajax_onload();

    ncm_select();

});



/**** store notice dismiss start ****/



jQuery(document).on("click", "#ncm_store_notice_links", function() {

    jQuery(".ncm-store-notice").hide();

    setCookie("NCM_Store_Notice", "hidden");

});



/**** store notice dismiss end ****/



/* for availavility month popup start */

jQuery(document).on("click", "#ncm_month_change", function() {



    var button       = jQuery(this);

    var post_id      = jQuery('#ncm_post_id').val();

    var bookingCodes = jQuery('#ncm_bookingCodes').val();

    var current_date = button.attr('data-ncm_month_year');



    jQuery('.ncm_price_availability_loader').show();

    button.attr("disabled", "disabled");

    jQuery.ajax({

      type: 'POST',

      url: ajaxurl,

      dataType: "json",

      data: {

        'action': 'ncm_availability_calendar',

        'post_id': post_id,

        'bookingCodes': bookingCodes,

        'current_date': current_date

      },

      error: function(e) {

        jQuery('.ncm_price_availability_loader').hide();

        console.log(e);

      },

      success: function(result){

            jQuery(".ncm_price_availability_loader").hide();

            if( result.status == 'success' ) {

                jQuery("#ncm_book_my_tour").show();

                jQuery("#ncm_display_availability_content").html(result.content);

            } else {

                jQuery("#ncm_book_my_tour").hide();

                jQuery("#ncm_display_availability_content").html(result.content);

            }

            load_tooltip();

        }

    });

});



jQuery(document).on("click", "#ncm_option_list_selected_date", function() {

    var this_elem = jQuery(this);

    var selected_date_data = this_elem.find("textarea").text();

    if( this_elem.parents('.modalncm-body').find('.ncm_price_availability_loader').length > 0 ) {

        this_elem.parents('.modalncm-body').find('.ncm_price_availability_loader').show();

    }

    jQuery.ajax({

        type: 'POST',

        url: ajaxurl,

        dataType: "json",

        data: jQuery.parseJSON(selected_date_data),

        error: function(e) {

            jQuery("#ncm_option_list_conent").html(e);

            jQuery('#ncm_option_list').modalncm('show');

        },

        success: function(result){

            if( result.status == 'success' ) {

                console.log(result.content);

                jQuery("#ncm_option_list_conent").html(result.content);

                jQuery('#ncm_option_list').modalncm('show');

            } else {

                jQuery("#ncm_option_list_conent").html(result.content);

                jQuery('#ncm_option_list').modalncm('hide');

            }

            if( this_elem.parents('.modalncm-body').find('.ncm_price_availability_loader').length > 0 ) {

                this_elem.parents('.modalncm-body').find('.ncm_price_availability_loader').hide();

            }

        }

    });

});

/* for availavility month popup end */



/**** for product summary section start ****/



function ncm_display_availability() {



    var product_type = jQuery('#ncm_product_type').val();

    var post_id      = jQuery('#ncm_post_id').val();

    var startdate    = jQuery('#ncm_travel_date_start').val();

    var enddate      = jQuery('#ncm_travel_date_end').val();

    var bookingCodes = jQuery('#ncm_bookingCodes').val();

    

    jQuery.ajax({

      type: 'POST',

      url: ajaxurl,

      dataType: "json",

      data: {

        'action'      : 'ncm_availability_calendar',

        'product_type': product_type,

        'post_id'     : post_id,

        'bookingCodes': bookingCodes,

        'startdate'   : startdate,

        'enddate'     : enddate,

      },

      error: function(e) {

        console.log(e);

      },

      success: function(result){

            

            if( result.status == 'success' ) {

                jQuery("#ncm_display_availability_content").html(result.content);

            } else {

                jQuery("#ncm_display_availability_content").html(result.msg);

            }

            load_tooltip();

            ncm_load_datepicker();

        }

    });

}



jQuery(document).on("click", ".product-info #chk_price", function() {



    var button       = jQuery(this);



    var product_type = jQuery('#ncm_product_type').val();

    var post_id      = jQuery('#ncm_post_id').val();

    var startdate    = jQuery('#ncm_travel_date_start').val();

    var enddate      = jQuery('#ncm_travel_date_end').val();

    var bookingCodes = jQuery('#ncm_bookingCodes').val();



    jQuery(".ncm_chk_price_display_loader").show();

    button.attr("disabled", "disabled");

    jQuery.ajax({

      type: 'POST',

      url: ajaxurl,

      dataType: "json",

      data: {

        'action'      : 'ncm_availability',

        'product_type': product_type,

        'post_id'     : post_id,

        'bookingCodes': bookingCodes,

        'startdate'   : startdate,

        'enddate'     : enddate,

      },

      error: function(e) {

        jQuery(".ncm_chk_price_display_loader").hide();

        button.removeAttr("disabled", "disable");

        console.log(e);

      },

      success: function(result){

            jQuery(".ncm_chk_price_display_loader").hide();

            button.removeAttr("disabled", "disable");

            if( result.status == 'success' ) {

                jQuery("#ncm_book_my_tour").show();

                jQuery("#check_my_price_now_content").html(result.content);

            } else {

                jQuery("#ncm_book_my_tour").hide();

                jQuery("#check_my_price_now_content").html(result.msg);

            }

            load_tooltip();

            ncm_load_datepicker();

            jQuery('#check_my_price_now_popup').modalncm('show');

        }

    });

});



jQuery(document).on("click", "#ncm_book_my_tour", function() {

    jQuery("#ncm_book_now_frm").submit();

});



function ncm_add_to_cart( post_id, booking_date, narnoo_bdate='', booking_code='', pickup='', dropoff='', passenger='', levy='' ) {

    var ncm_cart = ncm_Base64Decode( getCookie("NCM_Cart") );

    if( ncm_cart == null || ncm_cart == '' ) {

        var ncm_cart = [ { 

            'ncm_post_id'      : post_id, 

            'ncm_booking_date' : booking_date, 

            'ncm_narnoo_bdate' : narnoo_bdate,

            'ncm_booking_code' : booking_code,

            'ncm_pickup'       : pickup,

            'ncm_dropoff'      : dropoff,

            'ncm_passenger'    : passenger,

            'ncm_levy'    : levy

        } ];

        setCookie("NCM_Cart", ncm_Base64Encode( JSON.stringify(ncm_cart) ) );

    } else {

        var ncm_cart = jQuery.parseJSON(ncm_cart);

        ncm_cart.forEach(function(e) {

            if ( post_id == e.ncm_post_id && booking_date == e.ncm_booking_date ) {

                elem_index = ncm_cart.indexOf(e);



                narnoo_bdate = (narnoo_bdate == '') ? e.ncm_narnoo_bdate : narnoo_bdate;

                booking_code = (booking_code == '') ? e.ncm_booking_code : booking_code;

                pickup       = (pickup == '') ? e.ncm_pickup : pickup;

                dropoff      = (dropoff == '') ? e.ncm_dropoff : dropoff;

                passenger    = (passenger == '') ? e.ncm_passenger : passenger;

                levy    = (levy == '') ? e.ncm_levy : levy;

                ncm_cart.splice(elem_index++, 1);

            }

        });

        ncm_cart.push( { 

            'ncm_post_id'      : post_id, 

            'ncm_booking_date' : booking_date, 

            'ncm_narnoo_bdate' : narnoo_bdate,

            'ncm_booking_code' : booking_code,

            'ncm_pickup'       : pickup,

            'ncm_dropoff'      : dropoff,

            'ncm_passenger'    : passenger,

            'ncm_levy'    : levy

        } );

        setCookie("NCM_Cart", ncm_Base64Encode( JSON.stringify(ncm_cart) ) );

    }

}



jQuery(document).on("click", "#ncm_remove_cart_item", function() {

    var post_id = jQuery(this).attr('data-ncm_post_id'); 

    var booking_date = jQuery(this).attr('data-ncm_booking_date');

    var remove_elem = jQuery(this).attr('data-ncm_remove_elem');

    var ncm_cart = ncm_Base64Decode( getCookie("NCM_Cart") );

    var ncm_cart = jQuery.parseJSON(ncm_cart);

    ncm_cart.forEach(function(e) {

        if ( post_id == e.ncm_post_id && booking_date == e.ncm_booking_date ) {

            elem_index = ncm_cart.indexOf(e);

            ncm_cart.splice(elem_index++, 1);

        }

    });

    setCookie("NCM_Cart", ncm_Base64Encode( JSON.stringify(ncm_cart) ) );

    jQuery('.'+remove_elem).remove();

    ncm_calculate_price();

});



jQuery(document).on("click", "#ncm_select_date", function() {

	jQuery('.ncm_price_availability_loader').show();

	jQuery(".product-price").tooltip('hide');



    var booking_date = jQuery(this).attr('data-ncm_date');

    var narnoo_bdate = jQuery(this).attr('data-ncm_narnoo_bdate');

    var post_id      = jQuery("#ncm_post_id").val();

    var booking_code = jQuery(this).attr('data-ncm_booingCodes'); //jQuery('#ncm_bookingCodes').val();

    var pickup       = '';

    var dropoff      = '';

    var passenger    = '';

    var levy    = '';

   

    var ncm_cart = ncm_Base64Decode( getCookie("NCM_Cart") );

    if( ncm_cart == null || ncm_cart == '' ) {

        var ncm_cart = [ { 

            'ncm_post_id'      : post_id, 

            'ncm_booking_date' : booking_date, 

            'ncm_narnoo_bdate' : narnoo_bdate,

            'ncm_booking_code' : booking_code,

            'ncm_pickup'       : pickup,

            'ncm_dropoff'      : dropoff,

            'ncm_passenger'    : passenger,
            
            'ncm_levy'    : levy

        } ];

        setCookie("NCM_Cart", ncm_Base64Encode( JSON.stringify(ncm_cart) ) );

    } else {

        var ncm_cart = jQuery.parseJSON(ncm_cart);

        ncm_cart.forEach(function(e) {

            if ( post_id == e.ncm_post_id && booking_date == e.ncm_booking_date ) {

                elem_index = ncm_cart.indexOf(e);

                ncm_cart.splice(elem_index++, 1);

            }

        });

        ncm_cart.push( { 

            'ncm_post_id'      : post_id, 

            'ncm_booking_date' : booking_date,

            'ncm_narnoo_bdate' : narnoo_bdate, 

            'ncm_booking_code' : booking_code,

            'ncm_pickup'       : pickup,

            'ncm_dropoff'      : dropoff,

            'ncm_passenger'    : passenger,

            'ncm_levy'    : levy

        } );

        setCookie("NCM_Cart", ncm_Base64Encode( JSON.stringify(ncm_cart) ) );

    }

    

    var link = jQuery("#ncm_link").val();

    setTimeout(function(){ window.location.href = link; }, 3000); 

});

/*jQuery(document).ready(function() {

    var pc = jQuery('.ncm_pickup_location option:selected').attr("data-pick-price");
    console.log(pc);

    //var pc = jQuery('.ncm_pickup_location option:selected').trigger("change");
    //console.log(pc);

});*/


jQuery(document).on("change", ".ncm_pickup_location", function() {
    //console.log('Yes');

    var pickup       = jQuery(this).val();

    var post_id      = jQuery(this).attr("data-ncm_post_id");

    var booking_date = jQuery(this).attr("data-ncm_booking_date");
    
    var data_ncm_pick_loc_price = jQuery(this).attr("data-ncm_pick_loc_price");
    //console.log('Yes'+data_ncm_pick_loc_price);
    //console.log(jQuery("select.ncm_pickup_location").length > 0);

    ncm_add_to_cart( post_id, booking_date, '', '', pickup, '', '' );

    var PickPrice = jQuery(this).find("option:selected").data("pick-price");
    //console.log(PickPrice);    

    /*if (PickPrice == 'undefined') {
        PickPrice = 0;
    }else{
        PickPrice = PickPrice;
    }*/

    var PickLocationPrice = jQuery('option:selected', this).attr('data-pick-price');
    //console.log(PickLocationPrice);
    var selectid = jQuery(this).attr('id');
    jQuery(this).attr("data-ncm_pick_loc_price", PickPrice);
    //console.log('datancm_pick_loc_price'+PickPrice);

    ncm_calculate_price();

});



jQuery(document).on("change", ".ncm_dropoff_location", function() {

    var dropoff       = jQuery(this).val();

    var post_id      = jQuery(this).attr("data-ncm_post_id");

    var booking_date = jQuery(this).attr("data-ncm_booking_date");

    ncm_add_to_cart( post_id, booking_date, '', '', '', dropoff, '' );

});



jQuery(document).on("change", ".ncm_passenger", function() {

    var control_id       = this.id.split('_');

    var ncm_post_id      = control_id[1];

    var ncm_booking_date = control_id[2];

    var levy = '';

    var passenger = {};

    jQuery(".ncm_passenger").each(function(e) {

        var control_id   = this.id.split('_');

        var post_id      = control_id[1];

        var booking_date = control_id[2];

        var levy = jQuery(this).attr("data-ncm_levy");
        //console.log(levy);

        if(ncm_post_id==post_id && ncm_booking_date==booking_date) {

            var passenger_id = jQuery(this).attr("data-passenger_id");

            passenger[passenger_id] = jQuery(this).val();

        }

    });


    ncm_add_to_cart( ncm_post_id, ncm_booking_date, '', '', '', '', JSON.stringify(passenger), levy );

});



jQuery(function(){



    jQuery("#ncm_question_form").validate({

        rules: {

            booking_name: "required",

            booking_email: {

              required: true,

              email: true

            },

            booking_phone: {

                required: true,

                number: true

            },

            booking_hotel: "required"

        },

        messages: {

            booking_name: "Please enter your name.",

            booking_email: {

                required: "Please enter your email address.",

                email: "Your email address must be in the format of name@domain.com."

            },

            booking_phone: {

                required: "Please enter your email address.",

                number: "Please enter valid phone number."

            },

            booking_hotel: "Please enter accommodation."

        },

        errorElement: "span",

        errorPlacement: function ( error, element ) {

            elem_id = element.attr('id');

            jQuery('html, body').animate({

                scrollTop: (jQuery(".ncm_error").first().offset().top)

            },500);

            error.insertAfter( element );

        },

    }); 

    

    jQuery("#ncm_question_submit").on("click",function(){

        if(jQuery("#ncm_question_form").valid())

        {

            jQuery(this).attr("disabled", "disabled");

            var form = jQuery("#ncm_question_form");

            var data = form.serialize();

            jQuery("#ncm_container_loader").show();

            jQuery.ajax({

                type: 'POST',

                url: ajaxurl+'?action=ncm_question',

                dataType: "json",

                data: data,

                error: function(e) {

                    jQuery(this).removeAttr("disabled", "disabled");

                    jQuery("#ncm_container_loader").hide();

                    console.log(e);

                },

                success: function(result){

                    jQuery(this).removeAttr("disabled", "disabled");

                    if( result.status == 'success' ) {

                        console.log( result.content );

                        alert( result.msg );

                    } else {

                        console.log( result.content );

                        alert( result.msg );

                    }

                    setTimeout( function() { jQuery("#ncm_container_loader").hide(); }, 4000);

                }

            });

        } 

    })



});



/**** for product summary section end ****/





/**** for product details description section start ****/



function ncm_load_tabs() {

    if( jQuery( ".ncm-tabs" ).length > 0 ) {

        jQuery( ".ncm-tabs .ncm-tab-content" ).hide();

        if(jQuery('ul.ncm-tabs li').hasClass('active') ) {

            jQuery( "#"+jQuery( "ul.ncm-tabs li.active" ).attr( "aria-controls" ) ).show();

        } else {

            jQuery( "ul.ncm-tabs li:first" ).addClass( 'active' );

            jQuery( "#"+jQuery( "ul.ncm-tabs li:first" ).attr( "aria-controls" ) ).show();

        }

    }

}



jQuery(document).on("click", "ul.ncm-tabs li a", function() {

    jQuery( "ul.ncm-tabs li" ).removeClass( 'active' );

    jQuery( this ).parent().addClass( 'active' );

    ncm_load_tabs();

});



/**** for product details description section end ****/



/**** for checkout page calculate the price start ****/



function ncm_cart() {

    jQuery.ajax({

        type: 'POST',

        url: ajaxurl,

        data: {

            'action' : 'ncm_cart'

        },

        error: function(e) {

            jQuery("#ncm_container_loader").hide();

            jQuery("#ncm_container").html(e);

        },

        success: function(result){

            jQuery("#ncm_container_loader").hide();

            jQuery("#ncm_container").html(result);

            ncm_select();

            if( jQuery("select.ncm_passenger").length > 0 ) {

                ncm_calculate_price();

                ncm_set_cart_validation();

            }

        

            // if no pickup then remove

            var ncm_pickup_length = jQuery('.ncm_pickup').length;

            var ncm_pickup_selectbox = 1;

            jQuery(".ncm_pickup").each(function() {

                var pickup = jQuery(this).attr('data-val');

                if ( typeof pickup !== typeof undefined && pickup !== false ) {

                    if( pickup == "Please select" ) {

                        ncm_pickup_selectbox = parseInt(ncm_pickup_selectbox) + 1;

                    }

                }

            });

            if(ncm_pickup_selectbox == ncm_pickup_length) {

                jQuery(".ncm_pickup").each(function() {

                    jQuery(this).parents('td').remove();

                    jQuery(this).parents('th').remove();

                });

            }



            // if no dropoff then remove

            var ncm_dropoff_length = jQuery('.ncm_dropoff').length;

            var ncm_dropoff_selectbox = 1;

            jQuery(".ncm_dropoff").each(function() {

                var dropoff = jQuery(this).attr('data-val');

                if ( typeof dropoff !== typeof undefined && dropoff !== false ) {

                    if( dropoff == "Please select" ) {

                        ncm_dropoff_selectbox = parseInt(ncm_dropoff_selectbox) + 1;

                    }

                }

            });

            if(ncm_dropoff_selectbox == ncm_dropoff_length) {

                jQuery(".ncm_dropoff").each(function() {

                    jQuery(this).parents('td').remove();

                    jQuery(this).parents('th').remove();

                });

            }



        }

    });

}



function ncm_product_passenger() {

    jQuery.ajax({

        type: 'POST',

        url: ajaxurl,

        data: {

            'action' : 'ncm_product_passenger'

        },

        error: function(e) {

            jQuery("#ncm_container_loader").hide();

            jQuery("#ncm_product_passenger_container").html(e);

        },

        success: function(result){

            jQuery("#ncm_container_loader").hide();

            jQuery("#ncm_product_passenger_container").html(result);

            ncm_select();

            if( jQuery("select.ncm_passenger").length > 0 ) {

                ncm_calculate_price();

            }



            // if no pickup then remove

            var ncm_pickup_length = jQuery('.ncm_pickup').length;

            var ncm_pickup_selectbox = 1;

            jQuery(".ncm_pickup").each(function() {

                var pickup = jQuery(this).attr('data-val');

                if ( typeof pickup !== typeof undefined && pickup !== false ) {

                    if( pickup == "Please select" ) {

                        ncm_pickup_selectbox = parseInt(ncm_pickup_selectbox) + 1;

                    }

                }

            });

            if(ncm_pickup_selectbox == ncm_pickup_length) {

                jQuery(".ncm_pickup").each(function() {

                    jQuery(this).parents('td').remove();

                    jQuery(this).parents('th').remove();

                });

            }



            // if no dropoff then remove

            var ncm_dropoff_length = jQuery('.ncm_dropoff').length;

            var ncm_dropoff_selectbox = 1;

            jQuery(".ncm_dropoff").each(function() {

                var dropoff = jQuery(this).attr('data-val');

                if ( typeof dropoff !== typeof undefined && dropoff !== false ) {

                    if( dropoff == "Please select" ) {

                        ncm_dropoff_selectbox = parseInt(ncm_dropoff_selectbox) + 1;

                    }

                }

            });

            if(ncm_dropoff_selectbox == ncm_dropoff_length) {

                jQuery(".ncm_dropoff").each(function() {

                    jQuery(this).parents('td').remove();

                    jQuery(this).parents('th').remove();

                });

            }



        }

    });

}



function ncm_calculate_price() {

    jQuery("#ncm_container_loader").show();

    if( jQuery("select.ncm_passenger").length > 0 ) {

        var products = [];

        

        jQuery("select.ncm_passenger").each( function () {

            var select = jQuery(this);

            var passenger = select.val();

            var price = select.attr('data-ncm_price');

            var levy = select.attr('data-ncm_levy');

            var pax = select.attr('data-ncm_pax');

            var commission = select.attr('data-ncm_commission');

            var group = select.attr('data-ncm_group');

            var maxquantity = select.attr('data-ncm_maxquantity');

            //console.log('Yes-->'+group);

            var product_subtotal_id = select.attr('data-ncm_subtotal_id');

            var product_levy_id = select.attr('data-ncm_levy_id');

            var product_total_id = select.attr('data-ncm_total_id');

            var product_passenger_fields = select.attr('data-ncm_passenger_fields');

            var product_passenger_label = select.attr('data-ncm_passenger_label');

            var product_passenger_id = select.attr('data-passenger_id');

            var ncm_cart_row_id = select.attr('data-ncm_cart_row_id');

            var ncm_passenger_fields = jQuery("#ncm_passenger_fields_"+ncm_cart_row_id).val();

            var pickppriceselect =  jQuery('.ncm_passenger_'+ncm_cart_row_id).find('.ncm_pickup_location').attr('data-ncm_pick_loc_price');
            

            products.push({ 

                'passenger' : passenger, 

                'price' : price, 

                'levy' : levy,

                'pax' : pax,

                'commission' : commission,
                
                'group' : group,
                
                'maxquantity' : maxquantity,

                'product_subtotal_id' : product_subtotal_id,

                'product_levy_id' : product_levy_id,

                'product_total_id' : product_total_id,

                'product_passenger_fields' : product_passenger_fields,

                'product_passenger_label' : product_passenger_label,

                'product_passenger_id' : product_passenger_id,

                'ncm_cart_row_id' : ncm_cart_row_id,

                'ncm_passenger_fields' : ncm_passenger_fields,
                
                'ncm_pick_loc_price' : pickppriceselect,
                                

            });

        });



        jQuery.ajax({

            type: 'POST',

            url: ajaxurl,

            dataType: "json",

            data: {

                'action': 'ncm_cart_calculate',

                'product' : products,

            },

            error: function(e) {

                jQuery("#ncm_container_loader").hide();

                console.log(e);

            },

            success: function(result){

                jQuery("#ncm_container_loader").hide();



                jQuery.each(result,function(key,value){

                    jQuery("#"+key).html(value.subtotal);

                    jQuery("#"+key).show();

                });

                ncm_set_cart_validation();



                // if no pickup then remove

                var ncm_pickup_length = jQuery('.ncm_pickup').length;

                var ncm_pickup_selectbox = 1;

                jQuery(".ncm_pickup").each(function() {

                    var pickup = jQuery(this).attr('data-val');

                    if ( typeof pickup !== typeof undefined && pickup !== false ) {

                        if( pickup == "Please select" ) {

                            ncm_pickup_selectbox = parseInt(ncm_pickup_selectbox) + 1;

                        }

                    }

                });

                if(ncm_pickup_selectbox == ncm_pickup_length) {

                    jQuery(".ncm_pickup").each(function() {

                        jQuery(this).parents('td').remove();

                        jQuery(this).parents('th').remove();

                    });

                }



                // if no dropoff then remove

                var ncm_dropoff_length = jQuery('.ncm_dropoff').length;

                var ncm_dropoff_selectbox = 1;

                jQuery(".ncm_dropoff").each(function() {

                    var dropoff = jQuery(this).attr('data-val');

                    if ( typeof dropoff !== typeof undefined && dropoff !== false ) {

                        if( dropoff == "Please select" ) {

                            ncm_dropoff_selectbox = parseInt(ncm_dropoff_selectbox) + 1;

                        }

                    }

                });

                if(ncm_dropoff_selectbox == ncm_dropoff_length) {

                    jQuery(".ncm_dropoff").each(function() {

                        jQuery(this).parents('td').remove();

                        jQuery(this).parents('th').remove();

                    });

                }

            }

        });

    } else {

        ncm_cart();

    }

}



jQuery(document).on("change", "select.ncm_passenger", function() {

    ncm_calculate_price();

});



/**** for checkout page calculate the price end ****/



/**** for page validation start ****/



jQuery(document).on("change", ".select2-offscreen", function () {

    if (!jQuery.isEmptyObject(validobj.submitted)) {

        validobj.form();

    }

});



jQuery(document).on("select2-opening", function (arg) {

    var elem = jQuery(arg.target);

    if (jQuery("#s2id_" + elem.attr("id") + " ul").hasClass("myErrorClass")) {

        jQuery(".select2-drop ul").addClass("myErrorClass");

    } else {

        jQuery(".select2-drop ul").removeClass("myErrorClass");

    }

});



jQuery(function(){



    /**** for cart page validation start ****/

    jQuery("#ncm_cart_form").validate({

        errorElement: "span",

        errorPlacement: function ( error, element ) {

            jQuery('html, body').animate({

                scrollTop: (jQuery(".ncm_error").first().offset().top)

            },500);

            if( element.next().hasClass('select2') ) {

                error.insertAfter( element.next() );

            } else {

                error.insertAfter( element );

            }

        },



        /* highlight: function (element, errorClass, validClass) {

            var elem = jQuery(element);

            if (elem.hasClass("select2-offscreen")) {

                jQuery("#s2id_" + elem.attr("id") + " ul").addClass(errorClass);

            } else {

                elem.addClass(errorClass);

            }

        },



        unhighlight: function (element, errorClass, validClass) {

            var elem = jQuery(element);

            if (elem.hasClass("select2-offscreen")) {

                jQuery("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);

            } else {

                elem.removeClass(errorClass);

            }

        } */

    });

    /**** for cart page validation end ****/





    /**** for checkout page validation start ****/

    jQuery("#ncm_payment_form").validate({

        errorElement: "span",

        errorPlacement: function ( error, element ) {

            elem_id = element.attr('id');

            jQuery('html, body').animate({

                scrollTop: (jQuery(".ncm_error").first().offset().top)

            },500);

            error.insertAfter( element );

        },

    }); 



    jQuery('.ncm_booking').each(function( index ) {

        if( jQuery(this).attr('data-required') ) {

            var elem_name = jQuery(this).attr('name');

            jQuery( "input[name*='"+elem_name+"']" ).rules( 

                "add", 

                {

                    required: true,

                    messages: {

                        required: jQuery( "input[name*='"+elem_name+"']" ).attr("data-error_required"),

                    }

                }

            );

        }

    });

    

    jQuery("#ncm_checkout_submit").on("click",function(){

        if(jQuery("#ncm_payment_form").valid())

        {
            //console.log('Yes');
            var form = jQuery("#ncm_payment_form");

            var data = form.serialize();

            jQuery("#ncm_container_loader").show();

            jQuery.ajax({

                type: 'POST',

                url: ajaxurl+'?action=ncm_validate_checkout',

                dataType: "json",

                data: data,

                error: function(e) {

                    jQuery("#ncm_container_loader").hide();
                    console.log('No');
                    console.log(e.errorMessage);

                },

                success: function(result){
                    
                    //return false;

                    if( result.status == 'submit_form' || result.status == 'ncm_ajax_script' ) {

                        jQuery("body").append( result.content );

                    } else if( result.status == 'success' ) {

                        console.log( result.content );

                        alert( result.msg );

                    } else {

                        console.log( result.content );

                        alert( result.msg );

                    }

                    setTimeout( function() { jQuery("#ncm_container_loader").hide(); }, 4000);

                }

            });

        } 

    })

    /**** for checkout page validation end ****/



});



function ncm_set_cart_validation() {

    jQuery('.ncm_pickup_location').each(function( index ) {

        if( jQuery(this).find("option").length > 1 ) {

            var elem_name = jQuery(this).attr('id');

            jQuery( "#"+elem_name ).rules( 

                "add", 

                {

                    required: true,

                    messages: {

                        required: jQuery( "#"+elem_name ).attr("data-error_required"),

                    }

                }

            );

        }

    });



    jQuery('.ncm_dropoff_location').each(function( index ) {

        if( jQuery(this).find("option").length > 1 ) {

            var elem_name = jQuery(this).attr('id');

            jQuery( "#"+elem_name ).rules( 

                "add", 

                {

                    required: true,

                    messages: {

                        required: jQuery( "#"+elem_name ).attr("data-error_required"),

                    }

                }

            );

        }

    });



    jQuery('.ncm_passenger').each(function(index, el) {

        if( jQuery(this).attr("data-required") ) {

            var elem_name = jQuery(this).attr('data-class');

            jQuery( "."+elem_name ).rules( 

                "add", 

                {

                    required: true,

                }

            );

        }

     });



    jQuery("#ncm_cart").on("click", function(){

        if(jQuery("#ncm_cart_form").valid())

        {

            jQuery("#ncm_cart_form").submit();

        }

    });

}



/**** for page validation End ****/



/**** for checkout page validation and submit data start ****/



function ncm_select_first_payment_gateway() {

    jQuery("#ncm_payment_form").find(".panel.panel-default").first().find(".panel-heading > a").click();

}



jQuery(document).on("click", ".ncm_payment_gateways_tab", function() {

    var selected_gateway = jQuery(this).attr("data-id");

    jQuery("#ncm_gateway_"+selected_gateway).click();    

});


jQuery(document).ready( function() {
jQuery(document).on("change", "input[name='ncm_gateway']", function() {

    if( jQuery(this).attr( "data-has_payment_fields" ) ) {

        var selected_gateway = jQuery("input[name='ncm_gateway']:checked").val();

        var card_holder_field = "ncm_"+selected_gateway+"_card_holder_name";

        var card_no_field = "ncm_"+selected_gateway+"_credit_card";

        var card_expiry = "ncm_"+selected_gateway+"_exp";

        var card_cvv = "ncm_"+selected_gateway+"_cvc";



        jQuery( "input[name*='"+card_holder_field+"']" ).rules( 

            "add", 

            {

                required: true,

                messages: {

                    required: jQuery( "input[name*='"+card_holder_field+"']" ).attr("data-error_required"),

                }

            }

        );



        jQuery( "input[name*='"+card_no_field+"']" ).rules( 

            "add", 

            {

                required: true,

                minlength: 16,

                messages: {

                    required: jQuery( "input[name*='"+card_no_field+"']" ).attr("data-error_required"),

                    minlength: jQuery( "input[name*='"+card_no_field+"']" ).attr("data-error_minlength")

                }

            }

        );



        jQuery( "input[name*='"+card_no_field+"']" ).rules( 

            "add", 

            {

                required: true,

                minlength: 16,

                messages: {

                    required: jQuery( "input[name*='"+card_no_field+"']" ).attr("data-error_required"),

                    minlength: jQuery( "input[name*='"+card_no_field+"']" ).attr("data-error_minlength")

                }

            }

        );



        jQuery( "input[name*='"+card_expiry+"']" ).rules( 

            "add", 

            {

                required: true,

                messages: {

                    required: jQuery( "input[name*='"+card_expiry+"']" ).attr("data-error_required"),

                }

            }

        );



        jQuery( "input[name*='"+card_cvv+"']" ).rules( 

            "add", 

            {

                required: true,

                minlength: 3,

                messages: {

                    required: jQuery( "input[name*='"+card_cvv+"']" ).attr("data-error_required"),

                    minlength: jQuery( "input[name*='"+card_cvv+"']" ).attr("data-error_minlength")

                }

            }

        );

    } else {

        jQuery( ".ncm_card_holder_name" ).rules( "remove", "min" );

        jQuery( ".ncm_credit_card" ).rules( "remove", "min" );

        jQuery( ".ncm_exp_card" ).rules( "remove" );

        jQuery( ".ncm_cvv_card" ).rules( "remove", "min" );

    }

});
});



/**** for checkout page validation and submit data end ****/



/**** for shortcode ncm_product_search of search product start ****/



jQuery(document).on("click", ".ncm_search_product", function() {

    jQuery("#ncm_container_loader").removeClass("hide");



    jQuery.ajax({

      type: 'POST',

      url: ajaxurl,

      dataType: "json",

      data: {

        'action': 'ncm_product_search',

        'ncm_search' : jQuery(".ncm_search").val(),

        'ncm_start_time' : jQuery(".ncm_start_time").val(),

        'ncm_end_time' : jQuery(".ncm_end_time").val(),

        'ncm_attractions_id' : jQuery(".ncm_attractions_id").val()

      },

      error: function(e) {

        jQuery('#ncm_product_list').hide();

        console.log(e);

      },

      success: function(result){

            if( result.status == 'success' ) {

                jQuery("#ncm_product_list").html(result.content);

            } else {

                jQuery("#ncm_product_list").html(result.content);

            }

            setTimeout( function() { jQuery("#ncm_container_loader").addClass("hide"); }, 1000);

        }

    });

});



/**** for shortcode ncm_product_search of search product end ****/



jQuery(document).ready(function($) {     

    jQuery('#myCarousel').carousel({

            interval: 5000

    });



    //Handles the carousel thumbnails

    jQuery('[id^=carousel-selector-]').click( function(){

        var id = this.id.substr(this.id.lastIndexOf("-") + 1);

        var id = parseInt(id);

        jQuery('#myCarousel').carousel(id);

    });





    // When the carousel slides, auto update the text

    jQuery('#myCarousel').on('slid.bs.carousel', function (e) {

        var id = jQuery('.item.active').data('slide-number');

    });

    jQuery('#narnoo-gallery-product').lightSlider({
        gallery:true,
        item:1,
        thumbItem:9,
        slideMargin: 0,
        speed:500,
        pause:5000,
        auto:true,
        loop:true,
        onSliderLoad: function() {
            jQuery('#narnoo-gallery-product').removeClass('cS-hidden');
        }  
    });

    // Animate
    jQuery(document).ready(function() {
      jQuery('.down-animated-arrow').on('click', function() {
        jQuery('html, body').animate({
          scrollTop: jQuery('.product-info').offset().top
        },1500);
      });
    });


});









jQuery( function() {

    if( jQuery('#ncm_price').length > 0 ){

        
    jQuery( "#slider-range" ).slider({

        range: true,

        min: 0,

        max: jQuery( "#ncm_price" ).attr("data-ncm_max_price"),

        values: jQuery.parseJSON( jQuery( "#ncm_price" ).attr("data-ncm_value") ),

        slide: function( event, ui ) {

            jQuery( "#ncm_price" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

        }

    });

    jQuery( "#ncm_price" ).val( "$" + jQuery( "#slider-range" ).slider( "values", 0 ) +

      " - $" + jQuery( "#slider-range" ).slider( "values", 1 ) );

    }


}); 

