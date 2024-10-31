<div class="ncm-order">
	<p class="ncm-notice ncm-notice--success ncm-thankyou-order-received"><?php _e('Thank you. Your order has been received.', NCM_txt_domain); ?></p>
	<ul class="ncm-order-overview ncm-thankyou-order-details order_details order_summary_custom">
		<li class="ncm-order-overview__order order">
				<?php _e('Order Id', NCM_txt_domain); ?>: <strong><?php echo $ncm_order_id; ?></strong>        
		</li>        
		<?php foreach ($user_data as $label => $value) { ?>
				<li class="ncm-order-overview__order order">
						<?php echo $label; ?>: 
						<strong><?php echo $value; ?></strong>            
				</li>
		<?php } ?>        
		<li class="ncm-order-overview__payment-method method">            
			<?php _e('Payment method', NCM_txt_domain); ?>: 
					<strong><?php echo $ncm_gateway_name; ?></strong>        
		</li>        
		<li class="ncm-order-overview__total total">            
			<?php _e('Total', NCM_txt_domain); ?>: <strong><?php echo $ncm_subtotal; ?></strong>        
		</li>        
		<li class="ncm-order-overview__total total">            
			<?php _e('Levy', NCM_txt_domain); ?>: <strong><?php echo $ncm_levy; ?></strong>        
		</li>
		<?php if(get_post_meta( $$ncm_order_id, 'ncm_adjustdiscount', true )): ?>
		<li class="ncm-order-overview__total total">            
			<?php _e('Discount', NCM_txt_domain); ?>: <strong><?php echo $ncm_discount; ?></strong>        
		</li>  
		<?php endif; ?>      
		<li class="ncm-order-overview__total total">            
			<?php _e('Total Payble', NCM_txt_domain); ?>: <strong><?php echo $ncm_total; ?></strong>        
		</li>    
	</ul>        
<section class="ncm-order-details">        
	<h2 class="ncm-order-details__title"><?php _e('Order details', NCM_txt_domain); ?></h2>        
	<div class="table-responsive">            
		<table class="ncm-table ncm-table--order-details shop_table order_details order_summary_table table-bordered">                
			<thead>
			<tr>
				<th class="ncm-table__product-name product-name"><?php _e('Tour Code', NCM_txt_domain); ?></th>
				<th class="ncm-table__product-name product-name"><?php _e('Tour Name', NCM_txt_domain); ?></th>
				<th class="ncm-table__product-name product-name"><?php _e('Travel Date', NCM_txt_domain); ?></th>
				<th class="ncm-table__product-name product-name"><?php _e('Pickup Location', NCM_txt_domain); ?></th>
				<?php /*<th class="ncm-table__product-name product-name"><?php _e('Dropoff Location', NCM_txt_domain); ?></th>*/?>
				<th class="ncm-table__product-name product-name"><?php _e('Passenger', NCM_txt_domain); ?></th>
				<th class="ncm-table__product-name product-name"><?php _e('Sub Total', NCM_txt_domain); ?></th>
				<th class="ncm-table__product-name product-name"><?php _e('Levy', NCM_txt_domain); ?></th>
				<th class="ncm-table__product-name product-name"><?php _e('Total', NCM_txt_domain); ?></th> 
			</tr>                
		</thead>                
		<tbody>                    
			<?php do_action('ncm_order_products'); ?>                
		</tbody>            
		</table>        
	</div>    
</section>
</div>