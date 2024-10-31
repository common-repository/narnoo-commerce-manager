<div class="" style="padding-top:20px !important">

 <div class="grid_12">

   <?php 

    global $ncm_settings, $post;

   if ($product->have_posts()) { 

    ?>

    <h4>View Products</h4>

    <hr/>

    <?php

  ?>
 
<div class="ncm-row">  
  <div class="productlist-shortcode">
  <?php 
  while ($product->have_posts()) {

        $product->the_post(); ?>

        <?php 
          $image_path = wp_get_attachment_url( get_post_thumbnail_id( $product->ID ) );
          $image_alt = get_the_title();

          $post_id = get_the_ID();
          $price = get_post_meta($post_id, 'product_min_price', true);

          if(!empty($price)){
              $btncaption = 'Book Now';
          }else{
              $btncaption = 'More Info';
          } 
        ?>

          <div class="ncm-col-xs-12 ncm-col-sm-6 ncm-col-md-4">
            
            <div class="card card-price">
              <div class="card-img">
                <a href="<?php echo get_permalink() ?>">
                  <?php if(!empty($image_path)){ ?>
                      <img src="<?php echo $image_path ?>" class="img-responsive" alt="<?php echo $image_alt ?>">
                  <?php }else{ ?>
                      <img src="<?php echo NCM_IMAGES_URL ?>no-image.jpg" class="img-responsive" alt="<?php echo $image_alt ?>">
                  <?php } ?>
                </a>
              </div>
              <div class="card-body">
                  <?php if(!empty($price)){ ?>
                    <div class="price">$<?php echo $price ?></div>
                  <?php } ?>
                  <div class="lead"><?php echo get_the_title() ?></div>
                  <div class="details"><p><?php echo ncm_truncate(strip_tags(get_the_content()), 70, ' ') ?></p></div>
                  <a href="<?php echo get_permalink() ?>" class="btn btn-primary btn-block buy-now btn-lg"><?php echo $btncaption ?></a>
              </div>
            </div>
          </div>

      <?php }
      wp_reset_postdata();

  }
  ?>
  </div>
</div>

</div>

</div>