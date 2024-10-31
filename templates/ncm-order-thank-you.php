<div class="ncm-order">    
	<p class="ncm-notice ncm-notice--success ncm-thankyou-order-received">Thank you. Your order has been received.</p>    
	<ul class="ncm-order-overview ncm-thankyou-order-details order_details order_summary_custom">        
		<li class="ncm-order-overview__order order">            First Name: <strong>James</strong>        </li>        
		<li class="ncm-order-overview__date date">            Last Name: <strong>Wells</strong>        </li>                
		<li class="ncm-order-overview__email email">            Email: <strong>tester@gmail.com</strong>        </li>                
		<li class="ncm-order-overview__total total">            Mobile Phone: <strong>9876543210</strong>        </li>        
		<li class="ncm-order-overview__total total">            Total: <strong>$55.00</strong>        </li>       
		<li class="ncm-order-overview__payment-method method">            Payment method: <strong>Stripe</strong>        </li>    
	</ul>        
	<section class="ncm-order-details">        
		<h2 class="ncm-order-details__title">Order details</h2>        
		<div class="table-responsive">            
			<table class="ncm-table ncm-table--order-details shop_table order_details table-bordered">                
				<thead>                    
					<tr>                        
						<th class="ncm-table__product-name product-name"><?php _e('Tour Code', NCM_txt_domain); ?></th>                        
						<th class="ncm-table__product-name product-name"><?php _e('Tour Name', NCM_txt_domain); ?></th>                        
						<th class="ncm-table__product-name product-name"><?php _e('Travel Date', NCM_txt_domain); ?></th>                        
						<th class="ncm-table__product-name product-name"><?php _e('Pickup Location', NCM_txt_domain); ?></th>                        
						<th class="ncm-table__product-name product-name"><?php _e('Dropoff Location', NCM_txt_domain); ?></th>                        
						<th class="ncm-table__product-name product-name"><?php _e('Passenger', NCM_txt_domain); ?></th>                        
						<th class="ncm-table__product-name product-name"><?php _e('Sub Total', NCM_txt_domain); ?></th>                        
						<th class="ncm-table__product-name product-name"><?php _e('Levy', NCM_txt_domain); ?></th>                        
						<th class="ncm-table__product-name product-name"><?php _e('Total', NCM_txt_domain); ?></th>                    
					</tr>                
				</thead>                
				<tbody>                    
					<?php do_action('ncm_checkout_product'); ?>                
				</tbody>            
			</table>        
		</div>    
	</section>
</div>