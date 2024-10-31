<?php

global $ncm_template_product;
$thumbNail  = has_post_thumbnail( $post_id ); //Does the post have a thumbnail as backup
?>

<?php 
if( !empty($product_gallery_lightslder) ){
	echo  $product_gallery_lightslder;
}elseif( !empty($thumbNail) ){
	  $tUrl = get_the_post_thumbnail_url($post_id, 'large', true);
      $html = '<img src="'.$tUrl.'" alt="'.get_the_title().'"/>';
      echo $html;
}
?>

<?php echo $ncm_template_product->ncm_after_single_summary_func(); ?>