<?php	 do_action( 'ncm_before_single_product' );?>
<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="next-section-arrow text-center">
        <strong class="button-title">Availability</strong>
        <a href="javascript:;" class="down-animated-arrow"><i class="ncm_fa ncm-chevron-down bounce"></i></a> 
    </div>

	<?php do_action( 'ncm_before_single_product_summary' );	?>	

	<div class="ncm-col-md-5 ncm-col-sm-5 summary entry-summary"> 
		<?php do_action( 'ncm_single_product_summary' );		?>	
	</div>	
	
	<?php do_action( 'ncm_after_single_product_summary' );	?>
</div>
<?php do_action( 'ncm_after_single_product' ); ?>