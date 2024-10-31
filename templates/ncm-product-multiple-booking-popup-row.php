<tr>

    <td>

        <?php echo $ncm_sub_product_name; ?>

    </td>

    <td>

        <input type="text" id="ncm_start_date" <?php ncm_set_attribute( $ncm_attr_start_date, true ); ?> class="ncm_datepicker" placeholder="dd-mm-yyyy" data-format="dd-mm-yyyy" />

    </td>

    <td>

        <input type="text" id="ncm_end_date" <?php ncm_set_attribute( $ncm_attr_end_date, true ); ?> class="ncm_datepicker" placeholder="dd-mm-yyyy" data-format="dd-mm-yyyy" />

    </td>

    <td>

        <button type="button" id="ncm_check_popup_availability" <?php ncm_set_attribute( $ncm_attr_check_availability, true ); ?> > <?php _e('Check Availability', NCM_txt_domain); ?> </button>

    </td>

</tr>   

<tr>

    <td colspan='4'>

        <div class="ncm_booking_row_result">

            <?php do_action( 'ncm_product_display_availability', $ncm_product_availability ); ?>

            <div class="ncm_loader" style="display:none;">

                <i class=" ncm_fa ncm_fa-spinner ncm_fa-spin ncm_fa-4x ncm_prc_avl"></i>

            </div>

        </div>

    </td>

</tr>
