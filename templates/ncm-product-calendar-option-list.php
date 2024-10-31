<div class="ncm_price_availability_loader" style="display:none;"><i class=" ncm_fa ncm_fa-spinner ncm_fa-spin ncm_fa-4x ncm_prc_avl"></i></div>



<div class="modalncm-header">

    <h4 class="modalncm-title ncm_option_list_title" >

        <?php echo $product_name; ?> - 

        <?php echo $product_date_html; ?>



        <button <?php ncm_set_attribute($cart_attr, true); ?> style="padding:2px 8px; margin: 0 10px 0 10px;" class="ncm_availability_green btn btn-info"><?php _e('Select this date', NCM_txt_domain); ?></button>



        <a href="javascript:void(0);" class="pull-right" data-dismiss="modalncm"> X </a>

    </h4>

</div>



<div class="modalncm-body table-responsive " class="ncm_option_list_content">



    <div class="ncm_option_list_container">

         <?php if($departure != '') { ?> 

        <div class="ncm_option_list">

            <div class="passenger_type"><b><?php _e('Departure', NCM_txt_domain); ?></b></div>

            <div class="sell_and_levy"><?php echo $departure; ?></div>

        </div>

        <?php } ?>

        <div class="ncm_option_list">

            <div class="passenger_type"><b><?php _e('Seats Available', NCM_txt_domain); ?></b></div>

            <div class="sell_and_levy"><?php echo $seats_available; ?></div>

        </div>

    </div>



    <div class="ncm_option_list_container">

        <div class="ncm_option_list">

            <div class="passenger_type"><b><?php _e('Passenger Type', NCM_txt_domain); ?></b></div>

            <div class="sell_and_levy"><b><?php _e('Sell and Levy', NCM_txt_domain); ?></b></div>

        </div>

        <?php foreach ($ncm_option_list as $ncm_option) { ?>

        <div class="ncm_option_list">

            <div class="passenger_type"><?php echo $ncm_option['label']; ?></div>

            <div class="sell_and_levy"><?php echo $ncm_option['price']; ?></div>

        </div>

        <?php } ?>

    </div> 



    <?php /*

    <table class="table table-bordered table-striped" style="margin-bottom: 10px;">

        <?php if($departure != '') { ?> 

            <tr>

                <th><?php _e('Departure', NCM_txt_domain); ?></th>

                <td><?php echo $departure; ?></td>

            </tr>

        <?php } ?>

        <tr>

            <th><?php _e('Seats Available', NCM_txt_domain); ?></th>

            <td><?php echo $seats_available; ?></td>

        </tr>

    </table>



    <table class="table table-bordered table-striped">

        <tr>

            <th><?php _e('Passenger Type', NCM_txt_domain); ?></th>

            <th><?php _e('Sell and Levy', NCM_txt_domain); ?></th>

        </tr>

        <?php foreach ($ncm_option_list as $option_name => $option_value) { ?>

            <tr>

                <td class="text-left" ><?php echo $option_name; ?></td>

                <td class="text-left"><?php echo $option_value; ?></td>

            </tr>

        <?php } ?>

    </table>*/ ?>

</div>