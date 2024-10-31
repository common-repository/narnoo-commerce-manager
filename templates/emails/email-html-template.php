<h2 style="display:block; font-family:Helvetica Neue ,Helvetica,Roboto,Arial,sans-serif; font-size:18px; font-weight:bold; line-height:127%; margin:0 0 18px; text-align:left">

    <?php _e("Order", NCM_txt_domain); ?> #<?php echo $ncm_order_id; ?> (<?php echo $ncm_order_date; ?>)

</h2>



<table border="0" cellpadding="20" cellspacing="0" width="100%" style="width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">

    <tr><td style="padding: 6px;"><?php echo $ncm_first_name." ".$ncm_last_name; ?></td></tr>

    <tr><td style="padding: 6px;"><?php echo $ncm_email; ?></td></tr>

    <tr><td style="padding: 6px;"><?php echo $ncm_phone_no; ?></td></tr>

    <tr><td style="padding: 6px;"><?php echo $ncm_country; ?></td></tr>

    <tr><td style="padding: 6px;"><?php echo $ncm_comment; ?></td></tr>

</table>



<p></p>



<table cellspacing="0" cellpadding="6" style="width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border:1px solid #e5e5e5" border="1">

    <thead>

        <tr>

            <th style="text-align:left; border:1px solid #e5e5e5; padding:12px;">

                <?php _e('Product Detail', NCM_txt_domain); ?>

            </th>

            <th style="text-align:left; border:1px solid #e5e5e5; padding:12px;">

                <?php _e('Sub Total', NCM_txt_domain); ?>

            </th>

            <th style="text-align:left; border:1px solid #e5e5e5; padding:12px;">

                <?php _e('Levy', NCM_txt_domain); ?>

            </th>

            <th style="text-align:left; border:1px solid #e5e5e5; padding:12px;">

                <?php _e('Total', NCM_txt_domain); ?>

            </th>

        </tr>

    </thead>

    <tbody>

        <?php foreach( $product as $tour ) { ?>

            <tr>

                <td style="text-align:left;vertical-align:middle;border:1px solid #eee;padding:12px">

                    <p style="margin: 6px 0;">

                        <strong><?php _e('Tour Code', NCM_txt_domain); ?> </strong>: 

                        <?php echo $tour['product_id']; ?>

                    </p>

                    <p style="margin: 6px 0;">

                        <strong><?php _e('Tour Name', NCM_txt_domain); ?> </strong>: 

                        <?php echo $tour['tour_name']; ?>

                    </p>

                    <p style="margin: 6px 0;">

                        <strong><?php _e('Tour Date', NCM_txt_domain); ?> </strong>: 

                        <?php echo $tour['travel_date']; ?>

                    </p>

                    <p style="margin: 6px 0;">

                        <strong><?php _e('Tour Pickup', NCM_txt_domain); ?> </strong>: 

                        <?php echo $tour['pickup']; ?>

                    </p>

                    <p style="margin: 6px 0;">

                        <strong><?php _e('Tour Dropoff', NCM_txt_domain); ?> </strong>: 

                        <?php echo $tour['dropoff']; ?>

                    </p>

                    <hr style="border-color: #fff;">

                    <p style="margin: 6px 0;">

                        <strong><?php _e('Passenger Details', NCM_txt_domain); ?> </strong>: 

                        <div style="margin-left: 25px;"><?php echo $tour['passenger_details']; ?></div>

                    </p>

                </td>

                <td style="text-align:left;vertical-align:middle;border:1px solid #eee;padding:12px">

                    <?php echo $tour['display_subtotal']; ?>

                </td>

                <td style="text-align:left;vertical-align:middle;border:1px solid #eee;padding:12px">

                    <?php echo $tour['display_levy']; ?>

                </td>

                <td style="text-align:left;vertical-align:middle;border:1px solid #eee;padding:12px">

                    <?php echo $tour['display_total']; ?>

                </td>

            </tr>

        <?php } ?>

    </tbody>

    <tfoot>

        <tr>

            <th colspan="3" style="text-align:right;border-top-width:4px;border:1px solid #e5e5e5;padding:12px">

                <?php _e('Subtotal', NCM_txt_domain); ?>:

            </th>

            <td style="text-align:left;border-top-width:4px;border:1px solid #e5e5e5;padding:12px">

                <?php echo $ncm_subtotal; ?>

            </td>

        </tr>

        <tr>

            <th colspan="3" style="text-align:right;border-top-width:4px;border:1px solid #e5e5e5;padding:12px">

                <?php _e('Levy', NCM_txt_domain); ?>:

            </th>

            <td style="text-align:left;border-top-width:4px;border:1px solid #e5e5e5;padding:12px">

                <?php echo $ncm_levy; ?>

            </td>

        </tr>

        <tr>

            <th colspan="3" style="text-align:right;border:1px solid #e5e5e5;padding:12px">

                <?php _e('Payment method', NCM_txt_domain); ?>:

            </th>

            <td style="text-align:left;border:1px solid #e5e5e5;padding:12px">

                <?php echo $ncm_gateway_name; ?>

            </td>

        </tr>

        <tr>

            <th colspan="3" style="text-align:right;border:1px solid #e5e5e5;padding:12px">

                <?php _e('Total', NCM_txt_domain); ?>:

            </th>

            <td style="text-align:left;border:1px solid #e5e5e5;padding:12px">

                <?php echo $ncm_total; ?>

            </td>

        </tr>

    </tfoot>

</table>