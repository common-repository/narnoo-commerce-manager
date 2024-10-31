
<?php get_header( 'ncm' ); ?>    
	<?php do_action( 'ncm_before_main_content' ); ?>        
		<?php while ( have_posts() ) : the_post(); ?>                
			<?php  global $ncm_narnoo_helper, $post;   ?>         
				
			<?php ncm_get_template("content-single-product"); ?>    

		<?php endwhile; ?>    

	<?php do_action( 'ncm_after_main_content' ); ?>    

	<?php do_action( 'ncm_get_sidebar' ); ?>
				
<?php get_footer( 'ncm' ); ?>