<?php



global $ncm, $ncm_settings;



$currency = $ncm_settings->ncm_currency();



$currency_sbl = $ncm_settings->ncm_currency_symbol();







$general_option = $ncm_settings->ncm_get_settings_func( $current_tab );

extract($general_option);


?>



<div class="cmrc-table">







    <?php /********************* Store narnoo mode Section Start ********************/ ?>



    <h2><?php _e('Narnoo API', NCM_txt_domain); ?></h2>



    <table class="form-table">



        <tbody>



            <tr>



                <th><label for="ncm_narnoo_api_mode"><?php _e('API mode', NCM_txt_domain); ?></label></th>



                <td>



                    <fieldset class="show_options_if_checked">



                    <label for="ncm_narnoo_api_mode">



                        <input name="ncm_narnoo_api_mode" id="ncm_narnoo_api_mode" type="checkbox"  value="1" <?php echo ($ncm_narnoo_api_mode == 1) ? 'checked="checked"' : ''; ?>> <?php _e("Live mode", NCM_txt_domain); ?>                 



                    </label>



                </fieldset>



                </td>



            </tr>



            <tr>



                <th><label for="ncm_narnoo_bookable_products"><?php _e('Import Product', NCM_txt_domain); ?></label></th>



                <td>



                    <fieldset class="show_options_if_checked">



                    <label for="ncm_narnoo_bookable_products">



                        <input name="ncm_narnoo_bookable_products" id="ncm_narnoo_bookable_products" type="checkbox"  value="1" <?php echo ($ncm_narnoo_bookable_products == 1) ? 'checked="checked"' : ''; ?>> <?php _e("Import only bookable products", NCM_txt_domain); ?>                 



                    </label>



                </fieldset>



                </td>



            </tr>



            <?php /*<tr>



                <th><label for="ncm_narnoo_display_calendar"><?php _e('Display calendar', NCM_txt_domain); ?></label></th>



                <td>



                    <fieldset class="show_options_if_checked">



                    <label for="ncm_narnoo_display_calendar">



                        <input name="ncm_narnoo_display_calendar" id="ncm_narnoo_display_calendar" type="checkbox"  value="1" <?php echo ($ncm_narnoo_display_calendar == 1) ? 'checked="checked"' : ''; ?>> <?php _e("Display calendar on product detail page", NCM_txt_domain); ?>                 



                    </label>



                </fieldset>



                </td>



            </tr>*/?>

            <tr>



                <th><label for="ncm_narnoo_availability_modal_window"><?php _e('Availability Window', NCM_txt_domain); ?></label></th>



                <td>



                    <fieldset class="show_options_if_checked">



                    <label for="ncm_narnoo_availability_modal_window">



                        <input name="ncm_narnoo_availability_modal_window" id="ncm_narnoo_availability_modal_window" type="checkbox"  value="1" <?php echo ($ncm_narnoo_availability_modal_window == 1) ? 'checked="checked"' : ''; ?>> <?php _e("Show availability selector in a modal window", NCM_txt_domain); ?>                 



                    </label>



                </fieldset>



                </td>



            </tr>



        </tbody>



    </table>



    <?php /********************* Store narnoo mode Section end ********************/ ?>











	<?php /********************* Store Address Section Start ********************/ ?>



	<h2><?php _e('Store Address', NCM_txt_domain); ?></h2>



	<table class="form-table">



		<tbody>



			<tr>



				<th><label for="ncm_store_address"><?php _e('Address line 1', NCM_txt_domain); ?></label></th>



				<td>



					<input name="ncm_setting_store_address" id="ncm_store_address" type="text" value="<?php echo $ncm_setting_store_address; ?>" />



				</td>



			</tr>







			<tr>



				<th><label for="ncm_store_address_2"><?php _e('Address line 2', NCM_txt_domain); ?></label></th>



				<td>



					<input name="ncm_setting_store_address_2" id="ncm_store_address_2" type="text" value="<?php echo $ncm_setting_store_address_2; ?>" />



				</td>



			</tr>







			<tr>



				<th><label for="ncm_store_city"><?php _e('City', NCM_txt_domain); ?></label></th>



				<td>



					<input name="ncm_setting_store_city" id="ncm_store_city" type="text" value="<?php echo $ncm_setting_store_city; ?>" />		



				</td>



			</tr>







			<tr>



				<th><label for="ncm_default_country"><?php _e('Country / State', NCM_txt_domain); ?></label></th>



				<td>



                    <?php echo $ncm_settings->ncm_get_country_dropdown(



                                "ncm_setting_default_country", 



                                "ncm_default_country", 



                                array( 



                                    "class" => "ncm_select", 



                                    "data-disable_search" => "10",



                                    "value" => $ncm_setting_default_country



                                ), 



                                true



                            ); 



                    ?>

                    <br/><small>&nbsp;<?php _e('Search will be based on specific area. For example : Queensland', NCM_txt_domain); ?></small>



				</td>



			</tr>







			<tr>



				<th><label for="ncm_store_postcode"><?php _e('Postcode / ZIP', NCM_txt_domain); ?></label></th>



				<td>



					<input name="ncm_setting_store_postcode" id="ncm_store_postcode" type="text" value="<?php echo $ncm_setting_store_postcode; ?>" />



				</td>



			</tr>



		</tbody>



	</table>



	<?php /********************* Store Address Section End **********************/ ?>















	<?php /********************* General options Section Start **********************/ ?>



	<h2><?php _e('General options', NCM_txt_domain); ?></h2>



	<table class="form-table">



		<tbody>



			<tr>



				<th><label for="ncm_allowed_countries"><?php _e('Selling location(s)', NCM_txt_domain); ?></label></th>



				<td>



					<select name="ncm_setting_allowed_countries" id="ncm_allowed_countries" class="ncm_select" data-search_result = '-1'>



						<option value="all" <?php echo ($ncm_setting_allowed_countries == 'all') ? 'selected="selected"' : ''; ?>><?php _e('Sell to all countries', NCM_txt_domain); ?></option>



						<option value="all_except" <?php echo ($ncm_setting_allowed_countries == 'all_except') ? 'selected="selected"' : ''; ?>><?php _e('Sell to all countries, except for…', NCM_txt_domain); ?></option>



						<option value="specific" <?php echo ($ncm_setting_allowed_countries == 'specific') ? 'selected="selected"' : ''; ?>><?php _e('Sell to specific countries', NCM_txt_domain); ?></option>



					</select>



				</td>



			</tr>



			<tr style="<?php echo ($ncm_setting_allowed_countries=='all_except')?'':'display:none;'; ?>" >



				<th><label for="ncm_all_except_countries"><?php _e('Sell to all countries, except for…', NCM_txt_domain); ?></label></th>



				<td>



                    <?php echo $ncm_settings->ncm_get_country_dropdown( 



                                "ncm_setting_all_except_countries[]", 



                                "ncm_all_except_countries", 



                                array( 



                                    'multiple'=>"multiple", 



                                    "class" => "ncm_select",



                                    "value" => $ncm_setting_all_except_countries



                                ) 



                            ); 



                    ?>



				</td>



			</tr>



			<tr style="<?php echo ($ncm_setting_allowed_countries=='specific')?'':'display:none;'; ?>" >



				<th><label for="ncm_specific_allowed_countries"><?php _e('Sell to specific countries', NCM_txt_domain); ?></label></th>



				<td>



                    <?php echo $ncm_settings->ncm_get_country_dropdown( 



                                "ncm_setting_specific_allowed_countries[]", 



                                "ncm_specific_allowed_countries", 



                                array( 



                                    "multiple"=>"multiple", 



                                    "class" => "ncm_select",



                                    "value" => $ncm_setting_specific_allowed_countries



                                ) 



                            ); 



                    ?>



				</td>



			</tr>



            <?php /*



			<tr>



				<th><label for="ncm_ship_to_countries"><?php _e('Shipping location(s)', NCM_txt_domain); ?></label></th>



				<td>



					<select name="ncm_setting_ship_to_countries" id="ncm_ship_to_countries" class="ncm_select" data-search_result = '-1' >



						<option value=""><?php _e('Ship to all countries you sell to', NCM_txt_domain); ?></option>



						<option value="all" <?php echo ($ncm_setting_ship_to_countries == 'all') ? 'selected="selected"' : ''; ?>><?php _e('Ship to all countries', NCM_txt_domain); ?></option>



						<option value="specific" <?php echo ($ncm_setting_ship_to_countries == 'specific') ? 'selected="selected"' : ''; ?>><?php _e('Ship to specific countries only', NCM_txt_domain); ?></option>



						<option value="disabled" <?php echo ($ncm_setting_ship_to_countries == 'disabled') ? 'selected="selected"' : ''; ?>><?php _e('Disable shipping &amp; shipping calculations', NCM_txt_domain); ?></option>



					</select>



				</td>



			</tr>







			<tr style="<?php echo ($ncm_setting_ship_to_countries=='specific')?'':'display:none;'; ?>" >



				<th><label for="ncm_specific_ship_to_countries"><?php _e('Ship to specific countries', NCM_txt_domain); ?></label></th>



				<td>



                    <?php echo $ncm_settings->ncm_get_country_dropdown( 



                                "ncm_setting_specific_ship_to_countries[]", 



                                "ncm_specific_ship_to_countries", 



                                array( 



                                    "multiple"=>"multiple", 



                                    "class" => "ncm_select",



                                    "value" => $ncm_setting_specific_ship_to_countries



                                ) 



                            ); 



                        ?>



				</td>



			</tr>



            */ ?>



			<tr>



				<th><label for="ncm_default_customer_address"><?php _e('Default customer location', NCM_txt_domain); ?></label></th>



				<td>



					<select name="ncm_setting_default_customer_address" id="ncm_default_customer_address" class="ncm_select" data-search_result = '-1'>



						<option value=""><?php _e('No location by default', NCM_txt_domain); ?></option>



						<option value="base" <?php echo ($ncm_setting_default_customer_address == 'base') ? 'selected="selected"' : ''; ?>><?php _e('Shop base address', NCM_txt_domain); ?></option>



						<option value="geolocation" <?php echo ($ncm_setting_default_customer_address == 'geolocation') ? 'selected="selected"' : ''; ?>><?php _e('Geolocate', NCM_txt_domain); ?></option>



						<option value="geolocation_ajax" <?php echo ($ncm_setting_default_customer_address == 'geolocation_ajax') ? 'selected="selected"' : ''; ?>><?php _e('Geolocate (with page caching support)', NCM_txt_domain); ?></option>



					</select>



				</td>



			</tr>



			<?php /* 



            <tr>



				<th><?php _e('Enable taxes', NCM_txt_domain); ?></th>



				<td>



					<fieldset>



						<label for="ncm_calc_taxes">



							<input name="ncm_setting_calc_taxes" id="ncm_calc_taxes" type="checkbox" value="1" <?php echo ($ncm_setting_calc_taxes == 1) ? 'checked="checked"' : ''; ?> /> 



                            <?php _e('Enable taxes and tax calculations', NCM_txt_domain); ?>



						</label>



					</fieldset>



				</td>



			</tr>



            */ ?>



			<tr>



				<th><?php _e('Store notice', NCM_txt_domain); ?></th>



				<td>



					<fieldset>



						<label for="ncm_demo_store">



							<input name="ncm_setting_demo_store" id="ncm_demo_store" type="checkbox" value="1" <?php echo ($ncm_setting_demo_store == 1) ? 'checked="checked"' : ''; ?> /> 



                            <?php _e('Enable site-wide store notice text', NCM_txt_domain); ?>



						</label>



					</fieldset>



				</td>



			</tr>



			<tr>



				<th><?php _e('Notice for "isLive" is false', NCM_txt_domain); ?></th>



				<td>



					<fieldset>



						<label for="ncm_demo_store">



							<textarea name="ncm_setting_notice_islive_false" id="ncm_setting_notice_islive_false" ><?php echo $ncm_setting_notice_islive_false; ?></textarea>



						</label>



					</fieldset>



				</td>



			</tr>



			<tr style="<?php echo (!$ncm_setting_demo_store) ? 'display:none;' : ''; ?>">



				<th><label for="ncm_demo_store_notice"><?php _e('Store notice text', NCM_txt_domain); ?></label></th>



				<td>



					<textarea name="ncm_setting_demo_store_notice" id="ncm_demo_store_notice" placeholder=""><?php echo $ncm_setting_demo_store_notice; ?></textarea>



				</td>



			</tr>



		</tbody>



	</table>



	<?php /********************* General options Section Start **********************/ ?>















	<?php /********************* Currency options Section Start **********************/ ?>



	<h2><?php _e('Currency options', NCM_txt_domain); ?></h2>



	<table class="form-table">



		<tbody>



			<tr>



				<th><label for="ncm_currency"><?php _e('Currency', NCM_txt_domain); ?></label></th>



				<td>



                    <select name="ncm_setting_currency" id="ncm_currency" class="ncm_select" >



                    <?php 



                    foreach ($currency as $ckey => $cvalue) {



                        $currency_select = ($ncm_setting_currency == $ckey) ? 'selected="selected"' : '';



                        if( isset( $currency_sbl[$ckey] ) && !empty( $currency_sbl[$ckey] ) ) {



                            echo '<option value="'.$ckey.'" '.$currency_select.'>'.$cvalue.' ('.$currency_sbl[$ckey].')</option>';



                        } else {



                            echo '<option value="'.$ckey.'" '.$currency_select.'>'.$cvalue.' ('.$ckey.')</option>';



                        }



                    }



                    ?>



					</select>



				</td>



			</tr>



			



			<tr>



				<th><label for="ncm_currency_pos"><?php _e('Currency position', NCM_txt_domain); ?></label></th>



				<td>



					<select name="ncm_setting_currency_pos" id="ncm_currency_pos" class="ncm_select" data-search_result = '-1'>



						<option value="left" <?php echo ($ncm_setting_currency_pos == 'left') ? 'selected="selected"' : '' ?>><?php _e('Left ($99.99)', NCM_txt_domain); ?></option>



						<option value="right" <?php echo ($ncm_setting_currency_pos == 'right') ? 'selected="selected"' : '' ?>><?php _e('Right (99.99$)', NCM_txt_domain); ?></option>



						<option value="left_space" <?php echo ($ncm_setting_currency_pos == 'left_space') ? 'selected="selected"' : '' ?>><?php _e('Left with space ($ 99.99)', NCM_txt_domain); ?></option>



						<option value="right_space" <?php echo ($ncm_setting_currency_pos == 'right_space') ? 'selected="selected"' : '' ?>><?php _e('Right with space (99.99 $)', NCM_txt_domain); ?></option>



					</select>



				</td>



			</tr>







			<tr>



				<th><label for="ncm_price_thousand_sep"><?php _e('Thousand separator', NCM_txt_domain); ?></label></th>



				<td>



					<input name="ncm_setting_price_thousand_sep" id="ncm_price_thousand_sep" type="text" value="<?php echo $ncm_setting_price_thousand_sep; ?>" class="extra_small" />



				</td>



			</tr>



			



			<tr>



				<th><label for="ncm_price_decimal_sep"><?php _e('Decimal separator', NCM_txt_domain); ?></label></th>



				<td>



					<input name="ncm_setting_price_decimal_sep" id="ncm_price_decimal_sep" type="text" value="<?php echo $ncm_setting_price_decimal_sep; ?>" class="extra_small" />



				</td>



			</tr>



			



			<tr>



				<th><label for="ncm_price_num_decimals"><?php _e('Number of decimals', NCM_txt_domain); ?></label></th>



				<td>



					<input name="ncm_setting_price_num_decimals" id="ncm_price_num_decimals" type="number" value="<?php echo $ncm_setting_price_num_decimals; ?>" min="0" step="1" class="extra_small" />



				</td>



			</tr>



		</tbody>



	</table>



	<?php /********************* Currency options Section End ************************/ ?>







</div>