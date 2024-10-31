<div class="inner-box">
    <h3><?php _e("Your Order", NCM_txt_domain); ?></h3>
    <hr/>
    <div class="table-responsive checkout_table_custom">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><span class="tour_code"><?php _e('Tour Code', NCM_txt_domain); ?></span></th>
                    <th><span class="tour_name"><?php _e('Tour Name', NCM_txt_domain); ?></span></th>

                    <th><span class="tour_date"><?php _e('Travel Date', NCM_txt_domain); ?></span></th>
                    <th><span class="ncm_pickup"><?php _e('Pickup Location', NCM_txt_domain); ?></span></th>

                    <?php /*<th><span class="ncm_dropoff"><?php _e('Dropoff Location', NCM_txt_domain); ?></span></th>*/?>

                    <th><span class="ncm_passenger"><?php _e('Passenger', NCM_txt_domain); ?></span></th>

                    <th><span class="subtotal"><?php _e('Sub Total', NCM_txt_domain); ?></span></th>

                    <th><?php _e('Levy', NCM_txt_domain); ?></th>

                    <th><span class="total"><?php _e('Total', NCM_txt_domain); ?></span></th>

                </tr>

            </thead>
           <tbody>

                <?php do_action('ncm_checkout_product'); ?>
            </tbody>
        </table>

    </div>


    <div class="ncm-col-md-12">

     <table cellspacing="0" class="shop_table shop_table_responsive">
            <tbody>
               <tr class="cart-subtotal">
                    <th><?php _e("Total", NCM_txt_domain); ?></th>
                    <td data-title="Subtotal">
                        <?php ncm_cart_subtotal(); ?>

                    </td>
                </tr>

                <?php /*
                <tr class="cart-subtotal">
                    <th><?php _e("Levies", NCM_txt_domain); ?></th>
                    <td data-title="Levies">
                        <?php ncm_cart_leviestotal(); ?>
                    </td>
                </tr>
                */ ?>

                <tr class="promomain" style="display: none;">
                    <th>
                        <span class="promotext">PROMO CODE - <span class="promoval"> </span></span><br/>
                        
                    </th>
                    <td data-title="Levies">
                        <span class="discountval"> </span>
                    </td>
                </tr>

                <tr class="order-total">
                    <th><?php _e("Total Payble", NCM_txt_domain); ?></th>
                    <td data-title="Total">
                        <?php ncm_cart_total(); ?>
                   </td>
                </tr>
            </tbody>
        </table>    
    </div>

    <div class="ncm-col-md-12">
        <div class="promocode">
            <input type="text" name="promotionalcode" id="promotionalcode">
            <input type="button" name="submit" id="ncm_promocode_submit" value="Apply">

            <?php /*<div class="promomain" style="display: none;">
                <span class="promotext">PROMO CODE - <span class="promoval"> </span></span><br/>
                <span class="discountext">DISCOUNT - <span class="discountval"> </span></span>
            </div> */?>
            <span class="errorpromocode"></span>
        </div>
        
    </div>




    </div>

    <script type="text/javascript">
        
        jQuery("#ncm_promocode_submit").on("click",function(){

        var promocode = jQuery('#promotionalcode').val();   
        
        if(promocode != ''){
            //console.log('Yes');
            var form = jQuery("#ncm_payment_form");

            var data = form.serialize();

            //jQuery("#ncm_container_loader").show();
            jQuery(".promomain").hide();

            jQuery.ajax({

                type: 'POST',

                url: ajaxurl+'?action=ncm_applypromocode',

                dataType: "json",

                data: data,
                /*data: {
                    'promotionalcode' : promocode
                },*/

                error: function(e) {

                    jQuery("#ncm_container_loader").hide();
                    console.log('No');
                    console.log(e.errorMessage);

                },

                success: function(result){
                    
                    //console.log(result.success);
                    if(result.success == true){
                    //return false;
                    if(result.data['code'] != ''){
                        var codetextmain = result.data['code'];
                    }
                    if(codetextmain['code'] != ''){
                        var codeval = codetextmain['code'];
                    }
                    jQuery('.promoval').html(codeval);

                    //console.log(result.data['adjusted_amount']);
                    //console.log(result.data['discount']);
                    //Cookies.set('promocode', result.data['adjusted_amount']);

                    jQuery('.discountval').html('$'+result.data['discount'].toFixed(2));
                    jQuery('#ncm_cart_total').html('$'+result.data['adjusted_amount'].toFixed(2));


                    jQuery(".promomain").show();

                    //setTimeout( function() { jQuery("#ncm_container_loader").hide(); }, 4000);
                }else{
                    setTimeout( function() { jQuery('.errorpromocode').html(result.message); }, 2000);
                    //jQuery('.errorpromocode').html(result.message);
                }

                }


            });
        }else{
            setTimeout( function() { jQuery('.errorpromocode').html('Please enter coupon code.'); }, 2000);
            //jQuery('.errorpromocode').html('Please enter coupon code.');
        }

        

    })

    </script>