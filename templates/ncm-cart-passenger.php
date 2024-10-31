<div class="ncm-row">    
	<div class="ncm-col-md-12">        
	<hr/>           
	<h5><?php _e('Passanger info for', NCM_txt_domain); ?> <?php echo $tour_name; ?></h5>        
	<?php foreach ($fields as $ncm_field) {

	 ?>            
		<div class="ncm-row">                
			<div class="ncm-col-sm-6 ncm-col-md-6 label-ncm-col">                    
				<label class="control-label" for="ncm-first_name"><?php echo $ncm_field['label']; ?></label>
			</div>                
			<div class="ncm-col-sm-6 ncm-col-md-6">                    
				<div class="field-wrapper-50">                        
					<?php echo $ncm_field['control'] ; ?>                        
					<div class="ncm_cart_price_custom">
						<?php echo isset($ncm_field['price']) ? $ncm_field['price'] : ''; ?>
							
					</div>          
					
					<?php 
					if($ncm_field['levy'] > 0): ?>                            
						<div class="ncm_cart_price_custom_levy">Levy: <?php echo  '$'.$ncm_field['levy']; ?>
						</div>                        
					<?php endif; ?>                    
				</div>                
			</div>            
		</div>        
		<?php } ?>            
		<div class="table-responsive ncm_field_passenger">                
			<table class="table ncm_table_passenger table-bordered table-striped">                    
				<thead>                        
					<tr>                            
						<?php foreach ($passenger_fields as $field_label) { ?>                                
							<th>
								<?php echo $field_label; ?>
							</th>                            
						<?php } ?>                        
					</tr>                    
				</thead>                    
				<tbody id="<?php echo $passenger_field_container; ?>" >                        
					<?php // fields will be get from ncm-cart-passenger-info.php and displayed here ?>                    
				</tbody>                
			</table>            
		</div>        
	</div>    
</div>
</div>