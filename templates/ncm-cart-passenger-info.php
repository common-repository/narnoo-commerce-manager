<tr>    
	<?php foreach ($passenger_fields as $field_label) { ?>        
		<th><input type="text" <?php echo ncm_set_attribute($field_label); ?> /></th>    
	<?php } ?>
</tr>