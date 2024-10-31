<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <!-- /post-body-content -->
        <div id="postbox-container-1">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                <div id="ncm-order-actions" class="postbox">
                    <h2 class="hndle ui-sortable-handle">
                        <span><?php _e('Order actions', NCM_txt_domain); ?></span>
                    </h2>
                    <div class="inside table-outer-custom">
                        <ul class="order_actions submitbox">
                            <li class="wide" id="actions">
                                <select name="ncm_order_action">
                                    <option value="">
                                        <?php _e('Choose an action...', NCM_txt_domain); ?>
                                    </option>
                                    <option value="send_order_details">
                                        <?php _e('Email invoice / order details to customer', NCM_txt_domain); ?>
                                    </option>
                                    <option value="send_order_details_admin">
                                        <?php _e('Resend new order notification', NCM_txt_domain); ?>
                                    </option>
                                </select>
                                <button class="button ncm-reload"><span><?php _e('Apply', NCM_txt_domain); ?></span></button>
                            </li>
                            <li class="wide">
                                <div id="delete-action"><a class="submitdelete deletion" href="#"><?php _e('Move to trash', NCM_txt_domain); ?></a></div>
                                <input type="submit" class="button save_order button-primary" name="save" value="<?php _e('Update', NCM_txt_domain); ?>">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="postbox-container-2">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="ncm-order-data" class="postbox ">
                    <div class="panel-wrap ncm">
                        <div id="order_data" class="panel">
                            <h2>
                                <?php _e('Order #', NCM_txt_domain); ?>
                                <?php echo $ncm_order_id; ?>
                                <?php _e(' details', NCM_txt_domain); ?>
                            </h2>
                            <p class="order_number">
                                <?php echo isset($payment_gateways_name[$ncm_gateway_name]) ? __('Payment via', NCM_txt_domain)." ".$payment_gateways_name[$ncm_gateway_name].'.' : '' ; ?>
                            </p>
                            <div class="order_data_column_container">
                                <div class="order_data_column">
                                    <h3><?php _e('General Details', NCM_txt_domain); ?></h3>
                                    <p>
                                        <strong><?php _e('Narnoo Order ID :', NCM_txt_domain); ?></strong>
                                        <?php echo $narnoo_order_id; ?>
                                    </p>
                                    <p class="form-field form-field-wide">
                                        <label for="order_date">
                                            <?php _e('Order date:', NCM_txt_domain); ?>
                                        </label>
                                        <input type="text" class="date-picker hasDatepicker" name="order_date" id="order_date" value="<?php echo $ncm_order_date; ?>" >
                                    </p>
                                    <p class="form-field form-field-wide ncm-order-status">
                                        <label for="ncm_order_status">
                                            <?php _e('Order status:', NCM_txt_domain); ?>
                                        </label>
                                        <select id="ncm_order_status" name="ncm_order_status" tabindex="-1" aria-hidden="true">
                                            <?php foreach($ncm_order_status as $status_key => $status_value) {
                                                $select = ($order_status == $status_key) ? 'selected="selected"' : '';
                                                echo '<option value="'.$status_key.'" '.$select.'>'.$status_value.'</option>';
                                            } ?>
                                        </select>
                                    </p>
                                </div>
                                <div class="order_data_column">
                                    <h3><?php _e('User Details', NCM_txt_domain ); ?></h3>
                                    <div class="address">
                                        <?php foreach ($user_data as $label => $value) { ?>
                                            <p>
                                                <strong><?php echo $label; ?> : </strong>
                                                <?php echo $value; ?>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div id="ncm-order-items" class="postbox ">
                    <h2 class="hndle ui-sortable-handle"><span><?php _e('Items', NCM_txt_domain); ?></span></h2>
                    <div class="inside table-outer-custom">
                        <div class="ncm_order_items_wrapper wc-order-items-editable">
                            <table class="ncm_order_items table table-bordered" width="100%" >
                                <thead>
                                    <tr>
                                        <th class="item_cost" width="50%">
                                            <?php _e('Product Detail', NCM_txt_domain); ?>
                                        </th>
                                        <th class="item_cost" width="10%">
                                            <?php _e('Reservation Code', NCM_txt_domain); ?>
                                        </th>
                                        <th class="item_cost" width="10%">
                                            <?php _e('Reservation Provider', NCM_txt_domain); ?>
                                        </th>
                                        <th class="quantity" width="10%">
                                            <?php _e('Sub Total', NCM_txt_domain); ?>
                                        </th>
                                        
                                        <th class="line_cost" width="10%">
                                            <?php _e('Levy', NCM_txt_domain); ?>
                                        </th>
                                        <th class="line_cost" width="10%">
                                            <?php _e('Total', NCM_txt_domain); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if( is_array( $product ) && !empty( $product ) ) { ?>
                                        <?php foreach ( $product as $item ) { ?>
                                            <tr class="item">
                                                <td class="name">
                                                    <p style="margin:6px 0">
                                                        <strong><?php _e('Tour Code', NCM_txt_domain); ?></strong> :  
                                                        <?php echo $item['product_id']; ?>
                                                    </p>
                                                    <p style="margin:6px 0">
                                                        <strong><?php _e('Tour Name', NCM_txt_domain); ?></strong> : 
                                                        <?php echo $item['tour_name']; ?>
                                                    </p>
                                                    <p style="margin:6px 0">
                                                        <strong><?php _e('Tour Date', NCM_txt_domain); ?></strong> : 
                                                        <?php echo $item['travel_date']; ?>
                                                    </p>
                                                    <?php if( $item['pickup'] != 'Please Select' ) { ?>
                                                        <p style="margin:6px 0">
                                                            <strong><?php _e('Tour Pickup', NCM_txt_domain); ?></strong> : 
                                                            <?php echo $item['pickup']; ?>
                                                        </p>
                                                    <?php } ?>
                                                    <?php if( $item['dropoff'] != 'Please Select' ) { ?>
                                                        <p style="margin:6px 0">
                                                            <strong><?php _e('Tour Dropoff', NCM_txt_domain); ?></strong> : 
                                                            <?php echo $item['dropoff']; ?>
                                                        </p>
                                                    <?php } ?>
                                                    <hr>
                                                    <p style="margin:6px 0">
                                                        <strong><?php _e('Passenger Details', NCM_txt_domain); ?></strong> : 
                                                        <div style="margin-left:25px"><?php echo $item['passenger_details']; ?></div>
                                                    </p>
                                                </td>
                                                <td class="item_cost">
                                                    <div class="view">
                                                        <?php echo $item['reservation_code']; ?>
                                                    </div>
                                                </td>
                                                <td class="item_cost">
                                                    <div class="view">
                                                        <?php echo $item['reservation_provider']; ?>
                                                    </div>
                                                </td>
                                                <td class="item_cost">
                                                    <div class="view">
                                                        <?php echo $item['display_subtotal']; ?>
                                                    </div>
                                                </td>
                                                
                                                <td class="quantity">
                                                    <div class="view">
                                                        <?php echo $item['display_levy']; ?>
                                                    </div>
                                                </td> 
                                                
                                                <td class="line_cost">
                                                    <div class="view">
                                                        <?php echo $item['display_total']; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php /* 
                                    <tr>
                                        <th colspan="4" class="item_cost" align="right">
                                            <?php _e('Shipping:', NCM_txt_domain); ?>
                                        </th>
                                        <th class="line_cost" width="15%" align="left">
                                            <?php _e('0.00', NCM_txt_domain); ?>
                                        </th>
                                    </tr>
                                    */ ?>
                                    <tr>
                                        <th colspan="4" class="item_cost" align="right">
                                            <?php _e('Total:', NCM_txt_domain); ?>
                                        </th>
                                        <th class="line_cost" width="15%" align="left">
                                            <?php echo $ncm_subtotal; ?>
                                        </th>
                                    </tr>
                                    <?php /*<tr>
                                        <th colspan="5" class="item_cost" align="right">
                                            <?php _e('Levy:', NCM_txt_domain); ?>
                                        </th>
                                        <th class="line_cost" width="15%" align="left">
                                            <?php echo $ncm_levy; ?>
                                        </th>
                                    </tr>*/?>
                                    <?php if(get_post_meta( $ncm_order_id, 'ncm_adjustdiscount', true )): ?>
                                    <tr>
                                        <th colspan="4" class="item_cost" align="right">
                                            <?php _e('Discount:', NCM_txt_domain); ?>
                                        </th>
                                        <th class="line_cost" width="15%" align="left">
                                            <?php echo $ncm_discount; ?>
                                        </th>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th colspan="4" class="item_cost" align="right">
                                            <?php _e('Total Payble:', NCM_txt_domain); ?>
                                        </th>
                                        <th class="line_cost" width="15%" align="left">
                                            <?php echo $ncm_total; ?>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php /*
                        <div class="wc-order-data-row wc-order-bulk-actions wc-order-data-row-toggle">
                            <p class="add-items">
                                <button type="button" class="button refund-items" style="margin-left: 15px;">
                                    <?php _e('Refund', NCM_txt_domain); ?>
                                </button>
                                <span class="description" style="float: right; margin-right: 15px;">
                                    <?php _e('This order is no longer editable.', NCM_txt_domain); ?>
                                </span>
                            </p>
                        </div> 
                        */ ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>