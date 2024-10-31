<?php global $post, $ncm_controls; ?>
<h3><?php echo $post->post_title; ?></h3>
<p class="ncm-date">    
	<?php do_action( "NCM_Availability_StartDate", __('Start Date', NCM_txt_domain) ); ?>
</p>
<p class="ncm-date">    
	<?php do_action( "NCM_Availability_EndDate", __('End Date', NCM_txt_domain) ); ?>
		
</p>
<button type="submit" name="" id="ncm_get_price" class="ncm-button button">    
	<?php _e('Check My Price Now', NCM_txt_domain); ?>
</button>
<span class="ncm_chk_price_display_loader ncm_display_loader">    <i class="ncm_fa-li ncm_fa ncm_fa-spinner ncm_fa-spin"></i></span> 