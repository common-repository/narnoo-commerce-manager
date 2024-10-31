<tr>
	<td><?php echo $product_id; ?></td>
	<td><?php echo $tour_name; ?></td>
	<td><?php echo $travel_date; ?></td>
	<td><?php echo $pickup; ?></td>   
	<?php /*<td><?php echo $dropoff; ?></td>*/?>
	<td><?php echo $passenger_text; ?></td>
	<td><?php echo $subtotal; ?></td>
	<td><?php echo $levy; ?></td>
	<td><?php echo $total; ?></td>
</tr>
<?php if( !empty( $ncm_passenger ) && !empty( $ncm_passenger_fields ) ) { ?>
	<tr>
			<td colspan="9">
			<h5><?php _e('Passenger Information', NCM_txt_domain); ?></h5>
				<table>
					<thead>
							<tr>
								<?php foreach( $ncm_passenger_fields as $label ) { ?>
										<th><?php echo $label; ?></th>                        
								<?php } ?>                    
							</tr>                
					</thead>                
					<tbody>                    
						<?php do_action( "ncm_order_passenger", $ncm_passenger ); ?>                
					</tbody>            
				</table>        
			</td>    
		</tr>
	<?php } ?>