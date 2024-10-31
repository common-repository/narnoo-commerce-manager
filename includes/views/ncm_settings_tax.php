<?php
global $ncm, $ncm_settings, $ncm_tax, $ncm_controls;
$sections = $ncm_tax->ncm_get_tax_tab();
$payment_section = (isset($_REQUEST['section']) && $_REQUEST['section'] != '') ? $_REQUEST['section'] : 'tax_options';

$ncm_tax_classes = 'Reduced rate Zero rate';
$checkout_option = $ncm_settings->ncm_get_settings_func( $current_tab );
extract($checkout_option);

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

<?php if($payment_section == 'tax_options') { ?>

    <?php /************************* Tax Options Section Start *******************/ ?>

    <div class="cmrc-table">
        <table class="form-table">
            <tr>
                <th>
                    <label for="ncm_prices_include_tax">
                        <?php _e('Prices entered with tax', NCM_txt_domain); ?>
                    </label>
                </th>
                <td>
                    <fieldset>
                        <ul>
                            <li>
                                <label>
                                    <input name="ncm_prices_include_tax" value="yes" type="radio" <?php echo ('yes' == $ncm_prices_include_tax) ? 'checked="checked"' : ''; ?> /> 
                                    <?php _e('Yes, I will enter prices inclusive of tax', NCM_txt_domain); ?>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input name="ncm_prices_include_tax" value="no" type="radio" <?php echo ('no' == $ncm_prices_include_tax) ? 'checked="checked"' : ''; ?> />
                                    <?php _e('No, I will enter prices exclusive of tax', NCM_txt_domain); ?>
                                </label>
                            </li>
                        </ul>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ncm_tax_based_on"><?php _e('Calculate tax based on', NCM_txt_domain); ?></label>
                </th>
                <td>
                    <?php 
                    echo $ncm_controls->ncm_control( array(
                                "type" => "select",
                                "name" => "ncm_tax_based_on",
                                "id" => "ncm_tax_based_on",
                                "value" => $ncm_tax_based_on,
                                "class" => "ncm_select",
                                "options" => array(
                                    "shipping" => __('Customer shipping address', NCM_txt_domain),
                                    "billing" => __('Customer billing address', NCM_txt_domain),
                                    "base" => __('Shop base address', NCM_txt_domain)
                                )
                            )
                        );
                    ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ncm_shipping_tax_class"><?php _e('Shipping tax class', NCM_txt_domain); ?></label>
                </th>
                <td>
                    <?php 
                    echo $ncm_controls->ncm_control( array(
                                "type" => "select",
                                "name" => "ncm_shipping_tax_class",
                                "id" => "ncm_shipping_tax_class",
                                "value" => $ncm_shipping_tax_class,
                                "class" => "ncm_select",
                                "options" => array(
                                    "" => __('Standard', NCM_txt_domain),
                                    /*"reduced-rate" => __('Reduced rate', NCM_txt_domain),
                                    "zero-rate" => __('Zero rate', NCM_txt_domain)*/
                                )
                            )
                        );
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php _e('Rounding', NCM_txt_domain); ?></th>
                <td>
                    <fieldset>
                        <label for="ncm_tax_round_at_subtotal">
                            <input name="ncm_tax_round_at_subtotal" id="ncm_tax_round_at_subtotal" type="checkbox" value="1" <?php echo ($ncm_tax_round_at_subtotal) ? 'checked="checked"': ''; ?>> 
                            <?php _e('Round tax at subtotal level, instead of rounding per line', NCM_txt_domain); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ncm_tax_classes"><?php _e('Additional tax classes', NCM_txt_domain); ?></label>
                </th>
                <td>
                    <textarea name="ncm_tax_classes" id="ncm_tax_classes" ><?php echo $ncm_tax_classes; ?></textarea>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ncm_tax_display_shop"><?php _e('Display prices in the shop', NCM_txt_domain); ?></label>
                </th>
                <td>
                    <?php 
                    echo $ncm_controls->ncm_control( array(
                                "type" => "select",
                                "name" => "ncm_tax_display_shop",
                                "id" => "ncm_tax_display_shop",
                                "value" => $ncm_tax_display_shop,
                                "class" => "ncm_select",
                                "options" => array(
                                    "incl" => __('Including tax', NCM_txt_domain),
                                    "excl" => __('Excluding tax', NCM_txt_domain)
                                )
                            )
                        );
                    ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ncm_tax_display_cart"><?php _e('Display prices during cart and checkout', NCM_txt_domain); ?></label>
                </th>
                <td>
                    <?php 
                    echo $ncm_controls->ncm_control( array(
                                "type" => "select",
                                "name" => "ncm_tax_display_cart",
                                "id" => "ncm_tax_display_cart",
                                "value" => $ncm_tax_display_cart,
                                "class" => "ncm_select",
                                "options" => array(
                                    "incl" => __('Including tax', NCM_txt_domain),
                                    "excl" => __('Excluding tax', NCM_txt_domain)
                                )
                            )
                        );
                    ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ncm_price_display_suffix"><?php _e('Price display suffix', NCM_txt_domain); ?></label>
                </th>
                <td>
                    <?php 
                    echo $ncm_controls->ncm_control( array(
                                "type" => "text",
                                "name" => "ncm_price_display_suffix",
                                "id" => "ncm_price_display_suffix",
                                "value" => $ncm_price_display_suffix
                            )
                        );
                    ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ncm_tax_total_display"><?php _e('Display tax totals', NCM_txt_domain); ?></label>
                </th>
                <td>
                    <?php 
                    echo $ncm_controls->ncm_control( array(
                                "type" => "select",
                                "name" => "ncm_tax_total_display",
                                "id" => "ncm_tax_total_display",
                                "value" => $ncm_tax_total_display,
                                "class" => "ncm_select",
                                "options" => array(
                                    "single" => __('As a single total', NCM_txt_domain),
                                    "itemized" => __('Itemized', NCM_txt_domain)
                                )
                            )
                        );
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <?php /************************* Tax Options Section End *******************/ ?>

<?php } else { ?>

    <?php /************************* Standard tax rate Section Start *******************/ ?>

    <div class="cmrc-table">
        <table class="ncm_tax_rates widefat">
            <thead>
                <tr>
                    <th width="10%">
                        <a href="https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes" target="_blank"><?php _e('Country code', NCM_txt_domain); ?></a>
                    </th>
                    <th width="8%"><?php _e('State code', NCM_txt_domain); ?></th>
                    <th><?php _e('Postcode / ZIP', NCM_txt_domain); ?></th>
                    <th><?php _e('City', NCM_txt_domain); ?></th>
                    <th width="8%"><?php _e('Rate %', NCM_txt_domain); ?></th>
                    <th width="8%"><?php _e('Tax name', NCM_txt_domain); ?></th>
                    <th width="8%"><?php _e('Priority', NCM_txt_domain); ?></th>
                    <th width="8%"><?php _e('Compound', NCM_txt_domain); ?></th>
                    <th width="8%"><?php _e('Shipping', NCM_txt_domain); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="9">
                        <a href="#" class="button plus ncm_tax_insert">
                            <?php _e('Insert row', NCM_txt_domain); ?>
                        </a>
                        <a href="#" class="button minus remove_tax_rates">
                            <?php _e('Remove selected row(s)', NCM_txt_domain); ?>
                        </a>
                        <a href="<?php echo $ncm_tax->ncm_get_tax_rate_file_url(); ?>" class="button export">
                            <?php _e('Export CSV', NCM_txt_domain); ?>
                        </a>
                        <a href="<?php echo admin_url("?import=ncm_tax_rate_csv"); ?>" class="button import">
                            <?php _e('Import CSV', NCM_txt_domain); ?>
                        </a>
                    </th>
                </tr>
            </tfoot>
            <tbody id="rates">
                <?php echo $ncm_tax->ncm_get_stored_tax(); ?>
            </tbody>
        </table>
        <?php 
            echo $ncm_controls->ncm_control( array(
                        "type" => "hidden",
                        "name" => "ncm_section",
                        "value" => $payment_section
                    )
                );
        ?>
        <script type="text/html" id="ncm_tax_row_content">
            <?php echo $ncm_tax->ncm_get_tax_row('{{ID}}'); ?>
        </script>
    </div>

    <?php /************************* Standard tax rate Section End *******************/ ?>

<?php } ?>