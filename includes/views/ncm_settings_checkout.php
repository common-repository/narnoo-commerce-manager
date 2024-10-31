<?php

global $ncm, $ncm_settings, $ncm_payment_gateways;

$sections = $ncm_payment_gateways->get_payment_gateways();

$payment_section = (isset($_REQUEST['section']) && $_REQUEST['section'] != '') ? $_REQUEST['section'] : 'checkout';



$checkout_option = $ncm_settings->ncm_get_settings_func( $current_tab );

extract($checkout_option);



$stripe_lang = $ncm_payment_gateways->ncm_stripe_language();



?>



<ul class="subsubsub ncm_settings_subsubsub">

	<?php 

		foreach( $sections as $sec_key => $sec_value ) {

			$sec_class = ($payment_section == $sec_key) ? 'current' : '';

			$sec_url   = admin_url( 'admin.php?page='.$ncm->ncm_setting.'&amp;tab='.$current_tab.'&amp;section='.$sec_key );

			?>

				<li><a href="<?php echo $sec_url; ?>" class="<?php echo $sec_class; ?>"><?php echo $sec_value; ?></a></li>

			<?php

		}

	?>

</ul>



<h2><?php echo $sections[$payment_section]; ?></h2>



<div class="cmrc-table">



    <?php /********************* Chceckout Process Start ********************/ ?>

    <div class="ncm_checkout_section" style="<?php echo ($payment_section!='checkout') ? 'display:none;' : ''; ?>" > 

	    <table class="form-table">

	        <tr valign="top" class="">

	            <th scope="row" class="titledesc"><?php _e("Checkout process", NCM_txt_domain); ?></th>

	            <td class="forminp forminp-checkbox">

	                <?php /*

	                <fieldset> 

	                    <label for="ncm_enable_guest_checkout">

	                        <input name="ncm_enable_guest_checkout" id="ncm_enable_guest_checkout" type="checkbox" value="1" <?php echo ($ncm_enable_guest_checkout == 1) ? 'checked="checked"' : ''; ?>> <?php _e("Enable guest checkout", NCM_txt_domain); ?>

	                    </label> 

	                    <p class="description"><?php _e("Allows customers to checkout without creating an account.", NCM_txt_domain); ?></p>

	                </fieldset>

	                */ ?>

	                <fieldset class="show_options_if_checked">

	                    <label for="ncm_force_ssl_checkout">

	                        <input name="ncm_force_ssl_checkout" id="ncm_force_ssl_checkout" type="checkbox"  value="1" <?php echo ($ncm_force_ssl_checkout == 1) ? 'checked="checked"' : ''; ?>> <?php _e("Force secure checkout", NCM_txt_domain); ?>                 

	                    </label>

	                    <p class="description">

	                        <?php _e("Force SSL (HTTPS) on the checkout pages", NCM_txt_domain); ?> (

	                        <a href="https://docs.ncm.com/document/ssl-and-https/#section-3" target="_blank">

	                            <?php _e("an SSL Certificate is required", NCM_txt_domain); ?>

	                        </a>).

	                    </p>

	                </fieldset>

	                <fieldset class="hidden_option" style="display: block;">

	                    <label for="ncm_unforce_ssl_checkout">

	                        <input name="ncm_unforce_ssl_checkout" id="ncm_unforce_ssl_checkout" type="checkbox" value="1" <?php echo ($ncm_unforce_ssl_checkout == 1) ? 'checked="checked"' : ''; ?>> <?php _e("Force HTTP when leaving the checkout", NCM_txt_domain); ?>

	                    </label>

	                </fieldset>

	            </td>

	        </tr>

	    </table>



	    <h3><?php _e('Checkout pages', NCM_txt_domain); ?></h3>

	    <p><?php _e('These pages need to be set so that Narnoo Commerce knows where to send users to checkout.', NCM_txt_domain); ?></p>

	    <table class="form-table">

	        <tr>

	            <th><?php _e('Add Product to Cart', NCM_txt_domain); ?></th>

	            <td>

	                <select name="ncm_cart_product_type" id="ncm_cart_product_type" class="ncm_select" data-search_result = '-1'>

	                    <option value="multiple" <?php echo ($ncm_cart_product_type == 'multiple') ? 'selected="selected"' : ''; ?>><?php _e('Multiple', NCM_txt_domain); ?></option>

	                    <option value="single" <?php echo ($ncm_cart_product_type == 'single') ? 'selected="selected"' : ''; ?>><?php _e('Single', NCM_txt_domain); ?></option>

	                </select>

	            </td>

	        </tr>

	        <tr>

	            <th><?php _e('Cart page', NCM_txt_domain); ?></th>

	            <td>

	                <?php echo $ncm_settings->ncm_get_pages_selectbox('ncm_cart_page_id', 'ncm_select', $ncm_cart_page_id); ?>

	            </td>

	        </tr>

	        <tr>

	            <th><?php _e('Checkout page', NCM_txt_domain); ?></th>

	            <td>

	                <?php echo $ncm_settings->ncm_get_pages_selectbox('ncm_checkout_page_id', 'ncm_select', $ncm_checkout_page_id); ?>

	            </td>

	        </tr>

	        <tr>

	            <th><?php _e('Terms and conditions', NCM_txt_domain); ?></th>

	            <td>

	                <?php echo $ncm_settings->ncm_get_pages_selectbox('ncm_terms_page_id', 'ncm_select', $ncm_terms_page_id); ?>

	            </td>

	        </tr>

			<tr>

	            <th><?php _e('Order Received Page', NCM_txt_domain); ?></th>

	            <td>

	                <?php echo $ncm_settings->ncm_get_pages_selectbox('ncm_thank_you_page_id', 'ncm_select', $ncm_thank_you_page_id); ?>

	            </td>

	        </tr>

	    </table>



	    <h3><?php _e('Payment gateways', NCM_txt_domain); ?></h3>

	    <p><?php _e('Installed gateways are listed below. Drag and drop gateways to control their display order on the frontend.', NCM_txt_domain); ?></p>

	    <table class="form-table sortable_containment">

	        <tr>

	            <th><?php _e('Gateway display order', NCM_txt_domain); ?></th>

	            <td>

	                <table class="ncm_gateways widefat" cellspacing="0">

	                    <thead>

	                        <tr>

	                            <th class="sort"></th>

	                            <th>Gateway</th>

	                            <th>Gateway ID</th>

	                            <th>Enabled</th>

	                        </tr>

	                    </thead>

	                    <tbody>

	                        <?php 

	                        if( empty( $ncm_gateway_order ) ) {  

	                            unset($sections['checkout']);

	                            $ncm_gateway_order = array_keys( $sections );

	                        }

	                        foreach ($ncm_gateway_order as $g_key => $g_value) {

	                            $is_enable = 'ncm_'.$g_value.'_enabled';

	                            ?>

	                            <tr>

	                                <td class="sort ncm_fa ncm_fa-bars">

	                                    <input type="hidden" name="ncm_gateway_order[]" class="" value="<?php echo $g_value; ?>">

	                                </td>

	                                <td>

	                                    <a href="#"><?php echo isset($sections[$g_value]) ? $sections[$g_value] : $g_value; ?></a>

	                                </td>

	                                <td><?php echo $g_value; ?></td>

	                                <td><?php echo ($$is_enable) ? __('Yes',NCM_txt_domain) : '-'; ?></td>

	                            </tr>

	                            <?php

	                        }

	                        ?>

	                    </tbody>

	                </table>

	            </td>

	        </tr>

	    </table>

    </div>

    <?php /********************* Chceckout Process End ********************/ ?>





	<?php /********************* Paypal Section Start ********************/ ?>

	<div class="ncm_paypal_section" style="<?php echo ($payment_section!='paypal') ? 'display:none;' : ''; ?>" > 

		<table class="form-table">

			<tbody>

				<tr>

					<th><label for="ncm_paypal_enabled"><?php _e('Enable/Disable', NCM_txt_domain); ?></label></th>

					<td>

						<label for="ncm_paypal_enabled">

							<input type="checkbox" name="ncm_paypal_enabled" id="ncm_paypal_enabled" value="1" <?php echo ($ncm_paypal_enabled == 1) ? 'checked="checked"' : ''; ?>>

							<?php _e('Enable PayPal Standard', NCM_txt_domain); ?>

						</label><br>

					</td>

				</tr>



				<tr>

					<th><label for="ncm_spaypal_title"><?php _e('Title', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_paypal_title" id="ncm_spaypal_title" value="<?php echo $ncm_paypal_title; ?>" placeholder="<?php _e('Please entere title for paypal', NCM_txt_domain); ?>">

					</td>

				</tr>

				

				<tr>

					<th><label for="ncm_paypal_description"><?php _e('Description', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_paypal_description" id="ncm_paypal_description" value="<?php echo stripslashes($ncm_paypal_description); ?>" placeholder="<?php _e('Please enter description for paypal', NCM_txt_domain); ?>">

					</td>

				</tr>



				<tr>

					<th><label for="ncm_paypal_email"><?php _e('PayPal email', NCM_txt_domain); ?></label></th>

					<td>

						<input type="email" name="ncm_paypal_email" id="ncm_paypal_email" value="<?php echo $ncm_paypal_email; ?>" placeholder="<?php _e('you@youremail.com', NCM_txt_domain); ?>">

					</td>

				</tr>



				<tr>

					<th><label for="ncm_paypal_testmode"><?php _e('PayPal sandbox', NCM_txt_domain); ?></label></th>

					<td>

						<label for="ncm_paypal_testmode">

							<input type="checkbox" name="ncm_paypal_testmode" id="ncm_paypal_testmode" value="1" <?php echo ($ncm_paypal_testmode == 1) ? 'checked="checked"' : ''; ?>> <?php _e('Enable PayPal sandbox', NCM_txt_domain); ?>

						</label>

						<p class="description">

							<?php _e('PayPal sandbox can be used to test payments. Sign up for a', NCM_txt_domain); ?>&nbsp;

							<a href="https://developer.paypal.com/"><?php _e('developer account', NCM_txt_domain); ?></a>

						</p>

					</td>

				</tr>

			</tbody>

		</table>

					

		<h3><?php _e('API credentials', NCM_txt_domain); ?></h3>

		<table class="form-table">

			<tbody>

				<tr>

					<th><label for="ncm_paypal_api_username"><?php _e('API username', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_paypal_api_username" id="ncm_paypal_api_username" value="<?php echo $ncm_paypal_api_username; ?>" placeholder="<?php _e('Optional', NCM_txt_domain); ?>">

					</td>

				</tr>

			

				<tr>

					<th><label for="ncm_paypal_api_password"><?php _e('API password', NCM_txt_domain); ?></label></th>

					<td>

						<input type="password" name="ncm_paypal_api_password" id="ncm_paypal_api_password"  value="<?php echo $ncm_paypal_api_password; ?>" placeholder="<?php _e('Optional', NCM_txt_domain); ?>">

					</td>

				</tr>

				

				<tr>

					<th><label for="ncm_paypal_api_signature"><?php _e('API signature', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_paypal_api_signature" id="ncm_paypal_api_signature" value="<?php echo $ncm_paypal_api_signature; ?>" placeholder="<?php _e('Optional', NCM_txt_domain); ?>">

					</td>

				</tr>

			</tbody>

		</table>

	</div>

	<?php /********************* Paypal Section End **********************/ ?>





	<?php /********************* Stripe Section Start ********************/ ?>

	<div class="ncm_stripe_section" style="<?php echo ($payment_section!='stripe') ? 'display:none;' : ''; ?>">

		<table class="form-table">		

			<tbody>

				<tr>

					<th><label for="ncm_stripe_enabled"><?php _e('Enable/Disable', NCM_txt_domain); ?></label></th>

					<td>

						<label for="ncm_stripe_enabled">

							<input type="checkbox" name="ncm_stripe_enabled" id="ncm_stripe_enabled" value="1" <?php echo ($ncm_stripe_enabled == 1) ? 'checked="checked"' : ''; ?>> <?php _e('Enable Stripe', NCM_txt_domain); ?>

						</label><br>

					</td>

				</tr>



				<tr>

					<th><label for="ncm_stripe_title"><?php _e('Title', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_stripe_title" id="ncm_stripe_title" placeholder="" value="<?php echo $ncm_stripe_title; ?>">

					</td>

				</tr>

			

				<tr>

					<th><label for="ncm_stripe_description"><?php _e('Description', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_stripe_description" id="ncm_stripe_description" value="<?php echo $ncm_stripe_description; ?>" placeholder="">

					</td>

				</tr>

				

				<tr>

					<th><label for="ncm_stripe_testmode"><?php _e('Test mode', NCM_txt_domain); ?></label></th>

					<td>

						<label for="ncm_stripe_testmode">

							<input type="checkbox" name="ncm_stripe_testmode" id="ncm_stripe_testmode" value="1" <?php echo ($ncm_stripe_testmode == 1) ? 'checked="checked"' : ''; ?> > <?php _e('Enable Test Mode', NCM_txt_domain); ?>

						</label><br>

					</td>

				</tr>

				

				<tr style="<?php echo ($ncm_stripe_testmode != 1) ? 'display: none;' : ''; ?>">

					<th><label for="ncm_stripe_test_publishable_key"><?php _e('Test Publishable Key', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_stripe_test_publishable_key" id="ncm_stripe_test_publishable_key" value="<?php echo $ncm_stripe_test_publishable_key; ?>" placeholder="">

					</td>

				</tr>

				

				<tr style="<?php echo ($ncm_stripe_testmode != 1) ? 'display: none;' : ''; ?>">

					<th><label for="ncm_stripe_test_secret_key"><?php _e('Test Secret Key', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_stripe_test_secret_key" id="ncm_stripe_test_secret_key" value="<?php echo $ncm_stripe_test_secret_key; ?>" placeholder="">

					</td>

				</tr>

				

				<tr style="<?php echo ($ncm_stripe_testmode == 1) ? 'display: none;' : ''; ?>">

					<th><label for="ncm_stripe_publishable_key"><?php _e('Live Publishable Key', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_stripe_publishable_key" id="ncm_stripe_publishable_key" value="<?php echo $ncm_stripe_publishable_key; ?>" placeholder="">

					</td>

				</tr>

				

				<tr style="<?php echo ($ncm_stripe_testmode == 1) ? 'display: none;' : ''; ?>">

					<th><label for="ncm_stripe_secret_key"><?php _e('Live Secret Key', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_stripe_secret_key" id="ncm_stripe_secret_key" value="<?php echo $ncm_stripe_secret_key; ?>" placeholder="">

					</td>

				</tr>

				

				<tr>

					<th><label for="ncm_stripe_statement_descriptor"><?php _e('Statement Descriptor', NCM_txt_domain); ?></label></th>

					<td>

						<input type="text" name="ncm_stripe_statement_descriptor" id="ncm_stripe_statement_descriptor" value="<?php echo $ncm_stripe_statement_descriptor; ?>" placeholder="">

					</td>

				</tr>

				

				<tr>

					<th><label for="ncm_stripe_capture"><?php _e('Capture', NCM_txt_domain); ?></label></th>

					<td>

						<label for="ncm_stripe_capture">

							<input type="checkbox" name="ncm_stripe_capture" id="ncm_stripe_capture" value="1" <?php echo ($ncm_stripe_capture == 1) ? 'checked="checked"' : ''; ?>><?php _e('Capture charge immediately', NCM_txt_domain); ?>

						</label><br>

					</td>

				</tr>

				

				<tr>

					<th><label for="ncm_stripe_checkout"><?php _e('Stripe Checkout', NCM_txt_domain); ?></label></th>

					<td>

						<label for="ncm_stripe_checkout">

							<input type="checkbox" name="ncm_stripe_checkout" id="ncm_stripe_checkout" value="1" <?php echo ($ncm_stripe_checkout == 1) ? 'checked="checked"' : ''; ?>> <?php _e('Enable Stripe Checkout', NCM_txt_domain); ?>

						</label><br>

					</td>

				</tr>

			

				<tr style="display: none;">

					<th><label for="ncm_stripe_checkout_locale"><?php _e('Stripe Checkout locale', NCM_txt_domain); ?></label></th>

					<td>

						<select name="ncm_stripe_checkout_locale" id="ncm_stripe_checkout_locale" class="ncm_select" data-search_result = '-1'>

							<?php foreach ($stripe_lang as $stripe_key => $stripe_value) { ?>

								<option value="<?php echo $stripe_key; ?>"><?php echo $stripe_value; ?></option>

							<?php } ?>

						</select>

					</td>

				</tr>

			</tbody>

		</table>

	</div>

	<?php /********************* Stripe Section End **********************/ ?>





    <?php /********************* eWAY Section Start ********************/ ?>

    <div class="ncm_stripe_section" style="<?php echo ($payment_section!='eway') ? 'display:none;' : ''; ?>">

        <table class="form-table">

            <tbody>

                <tr>

                    <th><label for="ncm_eway_enabled"><?php _e('Enable/Disable', NCM_txt_domain); ?></label></th>

                    <td>

                        <label for="ncm_eway_enabled">

                           <input type="checkbox" name="ncm_eway_enabled" id="ncm_eway_enabled" value="1" <?php echo ($ncm_eway_enabled == 1) ? 'checked="checked"' : ''; ?>> <?php _e('enable eWAY credit card payment', NCM_txt_domain); ?>

                       </label>

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_title"><?php _e('Method Title', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_eway_title" id="ncm_eway_title" value="<?php echo $ncm_eway_title; ?>">

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_description"><?php _e('Description', NCM_txt_domain); ?></label></th>

                    <td>

                        <textarea rows="3" cols="20" type="textarea" name="ncm_eway_description" id="ncm_eway_description" style="" placeholder=""><?php echo $ncm_eway_description; ?></textarea>

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_availability"><?php _e('Method availability', NCM_txt_domain); ?></label></th>

                    <td>

                        <select class="ncm_select" name="ncm_eway_availability" id="ncm_eway_availability">

                            <option value="all" <?php echo ($ncm_eway_availability == 'all') ? 'selected="selected"' : ''; ?>><?php _e('All allowed countries', NCM_txt_domain); ?></option>

                            <option value="specific" <?php echo ($ncm_eway_availability == 'specific') ? 'selected="selected"' : ''; ?>><?php _e('Specific Countries', NCM_txt_domain); ?></option>

                        </select>

                    </td>

                </tr>

                <tr <?php if($ncm_eway_availability!='specific') { echo 'style="display:none;"'; } ?>>

                    <th><label for="ncm_eway_countries"><?php _e('Specific Countries', NCM_txt_domain); ?></label></th>

                    <td>

                        <?php echo $ncm_settings->ncm_get_country_dropdown( 

                                    "ncm_eway_countries[]", 

                                    "ncm_eway_countries", 

                                    array( 

                                        "multiple"=>"multiple", 

                                        "class" => "ncm_select",

                                        "value" => $ncm_eway_countries

                                    ) 

                                ); 

                        ?>

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_api_key"><?php _e('API key', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_eway_api_key" id="ncm_eway_api_key" value="<?php echo $ncm_eway_api_key; ?>">

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_password"><?php _e('API password', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_eway_password" id="ncm_eway_password" value="<?php echo $ncm_eway_password; ?>" />

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_ecrypt_key"><?php _e('Client Side Encryption key', NCM_txt_domain); ?></label></th>

                    <td>

                        <textarea rows="3" cols="20" type="textarea" name="ncm_eway_ecrypt_key" id="ncm_eway_ecrypt_key" autocorrect="off" autocapitalize="off" spellcheck="false"><?php echo $ncm_eway_ecrypt_key; ?></textarea>

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_customerid"><?php _e('Customer ID', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_eway_customerid" id="ncm_eway_customerid" value="<?php echo $ncm_eway_customerid; ?>">

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_sandbox"><?php _e('Sandbox mode', NCM_txt_domain); ?></label></th>

                    <td>

                        <label for="ncm_eway_sandbox">

                            <input type="checkbox" name="ncm_eway_sandbox" id="ncm_eway_sandbox" value="1" <?php echo ($ncm_eway_sandbox == 1) ? 'checked="checked"' : ''; ?>> <?php _e('enable sandbox (testing) mode', NCM_txt_domain); ?>

                        </label>

                    </td>

                </tr>

                <tr <?php if($ncm_eway_sandbox!=1) { echo 'style="display:none;"'; } ?>>

                    <th><label for="ncm_eway_sandbox_api_key"><?php _e('Sandbox API key', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_eway_sandbox_api_key" id="ncm_eway_sandbox_api_key" value="<?php echo $ncm_eway_sandbox_api_key; ?>" autocorrect="off" autocapitalize="off" spellcheck="false">

                    </td>

                </tr>

                <tr <?php if($ncm_eway_sandbox!=1) { echo 'style="display:none;"'; } ?>>

                    <th><label for="ncm_eway_sandbox_password"><?php _e('Sandbox API password', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_eway_sandbox_password" id="ncm_eway_sandbox_password" value="<?php echo $ncm_eway_sandbox_password; ?>" autocorrect="off" autocapitalize="off" spellcheck="false">

                    </td>

                </tr>

                <tr <?php if($ncm_eway_sandbox!=1) { echo 'style="display:none;"'; } ?>>

                    <th><label for="ncm_eway_sandbox_ecrypt_key"><?php _e('Sandbox Client Side Encryption key', NCM_txt_domain); ?></label></th>

                    <td>

                        <textarea rows="3" cols="20" type="textarea" name="ncm_eway_sandbox_ecrypt_key" id="ncm_eway_sandbox_ecrypt_key" style="height: 6em" placeholder="" autocorrect="off" autocapitalize="off" spellcheck="false"><?php echo $ncm_eway_sandbox_ecrypt_key; ?></textarea>

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_stored"><?php _e('Payment Method', NCM_txt_domain); ?></label></th>

                    <td>

                        <?php _e('Capture', NCM_txt_domain); ?>

                        <?php /*

                        <select class="ncm_select" name="ncm_eway_stored" id="ncm_eway_stored" tabindex="-1" aria-hidden="true">

                            <option value="no" <?php echo ($ncm_eway_stored == 'no') ? 'selected="selected"' : ''; ?>><?php _e('Capture', NCM_txt_domain); ?></option>

                            <option value="yes" <?php echo ($ncm_eway_stored == 'yes') ? 'selected="selected"' : ''; ?>><?php _e('Authorize', NCM_txt_domain); ?></option>

                        </select>

                        ?>

                    </td>

                </tr>

                <?php /*

                <tr>

                    <th><label for="ncm_eway_logging"><?php _e('Logging', NCM_txt_domain); ?></label></th>

                    <td>

                        <select class="ncm_select" name="ncm_eway_logging" id="ncm_eway_logging" tabindex="-1" aria-hidden="true">

                            <option value="off" <?php echo ($ncm_eway_logging == 'off') ? 'selected="selected"' : ''; ?>><?php _e('Off', NCM_txt_domain); ?></option>

                            <option value="info" <?php echo ($ncm_eway_logging == 'info') ? 'selected="selected"' : ''; ?>><?php _e('All messages', NCM_txt_domain); ?></option>

                            <option value="error" <?php echo ($ncm_eway_logging == 'error') ? 'selected="selected"' : ''; ?>><?php _e('Errors only', NCM_txt_domain); ?></option>

                        </select>

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_card_form"><?php _e('Credit card fields', NCM_txt_domain); ?></label></th>

                    <td>

                        <label for="ncm_eway_card_form">

                        <input type="checkbox" name="ncm_eway_card_form" id="ncm_eway_card_form" value="1" <?php echo ($ncm_eway_card_form == 1) ? 'checked="checked"' : ''; ?>> <?php _e('use WooCommerce standard credit card fields', NCM_txt_domain); ?></label>

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_card_msg"><?php _e('Credit card message', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_eway_card_msg" id="ncm_eway_card_msg" value="<?php echo $ncm_eway_card_msg; ?>" placeholder="">

                    </td>

                </tr>

                <tr>

                    <th><label for="ncm_eway_site_seal"><?php _e('Show eWAY Site Seal', NCM_txt_domain); ?></label></th>

                    <td>

                        <label for="ncm_eway_site_seal">

                            <input type="checkbox" name="ncm_eway_site_seal" id="ncm_eway_site_seal" style="" value="1" <?php echo ($ncm_eway_site_seal == 1) ? 'checked="checked"' : ''; ?>> <?php _e('show the eWAY site seal after the credit card fields', NCM_txt_domain); ?>

                        </label>

                    </td>

                </tr>

                <tr <?php if($ncm_eway_site_seal!=1) { echo 'style="display:none;"'; } ?>>

                    <th>

                        <label for="ncm_eway_site_seal_code">&nbsp;</label>

                    </th>

                    <td>

                        <textarea rows="3" cols="20" type="textarea" name="ncm_eway_site_seal_code" id="ncm_eway_site_seal_code" placeholder=""><?php echo $ncm_eway_site_seal_code; ?></textarea>

                        <p class="description"><a href="https://www.eway.com.au/features/tools-site-seal" target="_blank"><?php _e('Generate your site seal on the eWAY website, and paste it here', NCM_txt_domain); ?></a></p>

                    </td>

                </tr>

                */ ?>

            </tbody>

        </table>

    </div>

    <?php /********************* Stripe Section End **********************/ ?>



    <?php /********************* SecurePay Section Start ********************/ ?>

    <div class="ncm_securepay_section" style="<?php echo ($payment_section!='securepay') ? 'display:none;' : ''; ?>">

        <table class="form-table">

            <tbody>



                <tr>

                    <th><label for="ncm_securepay_enabled"><?php _e('Enable/Disable', NCM_txt_domain); ?></label></th>

                    <td>

                        <label for="ncm_securepay_enabled">

                           <input type="checkbox" name="ncm_securepay_enabled" id="ncm_securepay_enabled" value="1" <?php echo ($ncm_securepay_enabled == 1) ? 'checked="checked"' : ''; ?>> <?php _e('Enable SecurePay payment gateway', NCM_txt_domain); ?>

                       </label>

                    </td>

                </tr>



                <tr>

                    <th><label for="ncm_securepay_title"><?php _e('Title', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_securepay_title" id="ncm_securepay_title" value="<?php echo $ncm_securepay_title; ?>">

                    </td>

                </tr>



                <tr>

                    <th><label for="ncm_securepay_description"><?php _e('Description', NCM_txt_domain); ?></label></th>

                    <td>

                        <textarea rows="3" cols="20" type="textarea" name="ncm_securepay_description" id="ncm_securepay_description" style="" placeholder=""><?php echo $ncm_securepay_description; ?></textarea>

                    </td>

                </tr>



                <tr>

                    <th><label for="ncm_securepay_testmode"><?php _e('Test mode', NCM_txt_domain); ?></label></th>

                    <td>

                        <label for="ncm_securepay_testmode">

                            <input type="checkbox" name="ncm_securepay_testmode" id="ncm_securepay_testmode" value="1" <?php echo ($ncm_securepay_testmode == 1) ? 'checked="checked"' : ''; ?> > <?php _e(' Enable SecurePay test environment (Please untick for Live transaction)', NCM_txt_domain); ?>

                        </label><br>

                    </td>

                </tr>

                

                <tr style="<?php echo ($ncm_securepay_testmode != 1) ? 'display: none;' : ''; ?>">

                    <th><label for="ncm_securepay_test_merchantid"><?php _e('Merchant ID (test)', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_securepay_test_merchantid" id="ncm_securepay_test_merchantid" value="<?php echo $ncm_securepay_test_merchantid; ?>" placeholder="">

                    </td>

                </tr>

                

                <tr style="<?php echo ($ncm_securepay_testmode != 1) ? 'display: none;' : ''; ?>">

                    <th><label for="ncm_securepay_test_password"><?php _e('Password (test)', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_securepay_test_password" id="ncm_securepay_test_password" value="<?php echo $ncm_securepay_test_password; ?>" placeholder="">

                    </td>

                </tr>



                <tr style="<?php echo ($ncm_securepay_testmode == 1) ? 'display: none;' : ''; ?>">

                    <th><label for="ncm_securepay_merchantid"><?php _e('Merchant ID (live)', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_securepay_merchantid" id="ncm_securepay_merchantid" value="<?php echo $ncm_securepay_merchantid; ?>" placeholder="">

                    </td>

                </tr>

                

                <tr style="<?php echo ($ncm_securepay_testmode == 1) ? 'display: none;' : ''; ?>">

                    <th><label for="ncm_securepay_password"><?php _e('Password (live)', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_securepay_password" id="ncm_securepay_password" value="<?php echo $ncm_securepay_password; ?>" placeholder="">

                    </td>

                </tr>

                

                

                

            </tbody>

        </table>

    </div>

    <?php /********************* SecurePay Section End **********************/ ?>



	<?php /********************* Afterpay Section Start ********************/ ?>

    <div class="ncm_afterpay_section" style="<?php echo ($payment_section!='afterpay') ? 'display:none;' : ''; ?>">

        <table class="form-table">

            <tbody>



                <tr>

                    <th><label for="ncm_afterpay_enabled"><?php _e('Enable/Disable', NCM_txt_domain); ?></label></th>

                    <td>

                        <label for="ncm_afterpay_enabled">

                           <input type="checkbox" name="ncm_afterpay_enabled" id="ncm_afterpay_enabled" value="1" <?php echo ($ncm_afterpay_enabled == 1) ? 'checked="checked"' : ''; ?>> <?php _e('enable Afterpay', NCM_txt_domain); ?>

                       </label>

                    </td>

                </tr>



                <tr>

                    <th><label for="ncm_afterpay_title"><?php _e('Title', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_afterpay_title" id="ncm_afterpay_title" value="<?php echo $ncm_afterpay_title; ?>">

                    </td>

                </tr>



                <tr>

                    <th><label for="ncm_afterpay_description"><?php _e('Description', NCM_txt_domain); ?></label></th>

                    <td>

                        <textarea rows="3" cols="20" type="textarea" name="ncm_afterpay_description" id="ncm_afterpay_description" style="" placeholder=""><?php echo $ncm_afterpay_description; ?></textarea>

                    </td>

                </tr>



                <tr>

                    <th><label for="ncm_afterpay_testmode"><?php _e('Test mode', NCM_txt_domain); ?></label></th>

                    <td>

                        <label for="ncm_afterpay_testmode">

                            <input type="checkbox" name="ncm_afterpay_testmode" id="ncm_afterpay_testmode" value="1" <?php echo ($ncm_afterpay_testmode == 1) ? 'checked="checked"' : ''; ?> > <?php _e('Enable Test Mode', NCM_txt_domain); ?>

                        </label><br>

                    </td>

                </tr>

                

                <tr style="<?php echo ($ncm_afterpay_testmode != 1) ? 'display: none;' : ''; ?>">

                    <th><label for="ncm_afterpay_test_id"><?php _e('Afterpay ID (test)', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_afterpay_test_id" id="ncm_afterpay_test_id" value="<?php echo $ncm_afterpay_test_id; ?>" placeholder="">

                    </td>

                </tr>

                

                <tr style="<?php echo ($ncm_afterpay_testmode != 1) ? 'display: none;' : ''; ?>">

                    <th><label for="ncm_afterpay_test_secret_key"><?php _e('Secret key (test)', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_afterpay_test_secret_key" id="ncm_afterpay_test_secret_key" value="<?php echo $ncm_afterpay_test_secret_key; ?>" placeholder="">

                    </td>

                </tr>



                <tr style="<?php echo ($ncm_afterpay_testmode == 1) ? 'display: none;' : ''; ?>">

                    <th><label for="ncm_afterpay_prod_id"><?php _e('Afterpay ID (live)', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_afterpay_prod_id" id="ncm_afterpay_prod_id" value="<?php echo $ncm_afterpay_prod_id; ?>" placeholder="">

                    </td>

                </tr>

                

                <tr style="<?php echo ($ncm_afterpay_testmode == 1) ? 'display: none;' : ''; ?>">

                    <th><label for="ncm_afterpay_prod_secret_key"><?php _e('Secret key (live)', NCM_txt_domain); ?></label></th>

                    <td>

                        <input type="text" name="ncm_afterpay_prod_secret_key" id="ncm_afterpay_prod_secret_key" value="<?php echo $ncm_afterpay_prod_secret_key; ?>" placeholder="">

                    </td>

                </tr>

                

                

                

            </tbody>

        </table>

    </div>

    <?php /********************* Afterpay Section End **********************/ ?>



</div>