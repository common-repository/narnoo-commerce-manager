<?php get_header( 'ncm' ); ?>

    <?php do_action( 'ncm_before_main_content' ); ?>

    <div class="entry">
        <div class="ncm-container">
            <div class="ncm-row">
                <div class="ncm-col-md-12">
                    <header class="">
                        <h1 class="page-title"><?php _e('shops', NCM_txt_domain); ?></h1>
                    </header>
                </div>
                
                <div class="">

                    <div style = "min-width:100%; display:inline-block;" >
                        <div class="ncm-col-md-3">
                            <div class="productsearch-leftbar" style="">
                                <?php echo do_shortcode( '[ncm_search_product]' ); ?>
                            </div>
                        </div>
                    
                        <div class="ncm-col-md-9">
                            <div class="ncm_product_list productlist-shortcode">  
                                <?php 
                                    $products = new WP_Query( array('posts_per_page'=>12,
                                        'post_type'=>'narnoo_product',
                                        'post_status' => 'publish',
                                        'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 
                                    ); 
                                    while ( $products->have_posts() ) : $products->the_post(); 
                        
                                        $image_path = wp_get_attachment_url( get_post_thumbnail_id( $products->ID ) );
                                        $image_alt = get_the_title();

                                        $post_id = get_the_ID();
                                        $price = get_post_meta($post_id, 'product_min_price', true);

                                        if(!empty($price)){
                                            $btncaption = 'Book Now';
                                        }else{
                                            $btncaption = 'More Info';
                                        } ?>
                                

                                        <div class="ncm-col-xs-12 ncm-col-sm-6 ncm-col-md-6 grid-2">
                                            
                                            <div class="card card-price">
                                                <div class="card-img">
                                                    <a href="<?php the_permalink(); ?>">
                                                   <?php if(!empty($image_path)){ ?>
                                                      <img src="<?php echo $image_path; ?>" class="img-responsive" alt="<?php echo $image_alt; ?>">
                                                    <?php }else{ ?>
                                                      <img src="<?php echo NCM_IMAGES_URL; ?>no-image.jpg" class="img-responsive" alt="<?php echo $image_alt; ?>">
                                                    <?php } ?>
                                                </a>
                                              </div>
                                              <div class="card-body">
                                                   <?php if(!empty($price)){ ?>
                                                    <div class="price"><?php echo $ncm_settings->ncm_display_price($price); ?> </div>
                                                   <?php } ?>
                                                  <div class="lead"><?php the_title(); ?></div>
                                                  <div class="details"><p><?php echo ncm_truncate(strip_tags(get_the_content()), 70, ' '); ?></p></div>
                                                  <a href="<?php the_permalink() ?>" class="btn btn-primary btn-block buy-now btn-lg"><?php echo $btncaption ?></a>
                                              </div>
                                            </div>
                                          </div>

                                <?php endwhile;
                                wp_reset_postdata(); ?>
                                <div class="nrm-pagination">
                                    <?php
                                    $big = 999999999; // need an unlikely integer
                                     echo paginate_links( array(
                                        'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
                                        'format' => '?paged=%#%',
                                        'current' => max( 1, get_query_var('paged') ),
                                        'total' => $products->max_num_pages
                                    ) );
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>    
        </div>    
    </div>
    
    <?php do_action( 'ncm_after_main_content' ); ?>

    <?php do_action( 'ncm_get_sidebar' ); ?>
    
<?php get_footer( 'ncm' ); ?>