<?php



/*

* The shortcodes are created here in this class.

*/





if( !class_exists ( 'NCM_Shortcode' ) ) {







    class NCM_Shortcode {







        function __construct(){



        



            add_action( 'pre_get_posts', array( $this, 'ncm_product_post_query') );



        



            add_shortcode( "ncm_search_product", array( $this, "ncm_search_product_func" ) ); 



            add_shortcode( "ncm_product_list", array( $this, "ncm_product_list_func" ) );



            add_shortcode( "ncm_featured_product", array( $this, "ncm_featured_product_func" ) );



            add_shortcode( "ncm_category_product", array( $this, "ncm_category_product_func" ) );



            add_shortcode( "ncm_product_availability", array( $this, "ncm_product_availability_func" ) );





            add_shortcode( "ncm_availability", array( $this, "ncm_availability_func" ) );





            add_shortcode( "ncm_availability_button", array( $this, "ncm_availability_button_func" ) );





            add_shortcode( "ncm_cart", array( $this, "ncm_cart_html_func") );







            add_shortcode( "ncm_checkout", array( $this, "ncm_checkout_html_func") );







            add_shortcode( "ncm_order", array( $this, "ncm_order_html_func") );



        



            add_shortcode( "ncm_cart_item", array( $this, "ncm_cart_item_func") );







            add_shortcode( "ncm_product_search", array( $this, "ncm_product_search_func" ) );







            add_action( "wp_ajax_ncm_product_search", array($this, "ncm_product_search_result") );







            add_action( "wp_ajax_nopriv_ncm_product_search", array($this, "ncm_product_search_result") );

            



            //add_action( "admin_init", array($this, "ncm_truncate") );







        }







        function ncm_product_search_result( ) {







            global $wpdb;



            $post = $wpdb->prefix . 'posts';



            $post_meta = $wpdb->prefix . 'postmeta';







            $select = "SELECT ".$post.".ID FROM ".$post;



            $where = " WHERE co_posts.post_type = 'narnoo_product' ";







            if( isset($_REQUEST['ncm_search']) && !empty($_REQUEST['ncm_search']) ) {



                $keyword = $_REQUEST['ncm_search'];



                $where.= " AND post_title like '%".$keyword."%' ";



                $where.= " AND post_excerpt like '%".$keyword."%' ";



            }







            if( isset($_REQUEST['ncm_attractions_id']) && !empty($_REQUEST['ncm_attractions_id']) ) {



                $attraction_id = $_REQUEST['ncm_attractions_id'];



                $select.= " INNER JOIN ".$post_meta." as m1 ON ( co_posts.ID = m1.post_id )";



                $where.= " AND ( m1.meta_key='parent_post_id' AND m1.meta_value='".$attraction_id."' ) ";



            }







            /* For match exact start and end time 



            if( isset($_REQUEST['ncm_start_time']) && !empty($_REQUEST['ncm_start_time']) ) {



                $start_time = date("H:i:s", strtotime($_REQUEST['ncm_start_time']));



                $select.= " INNER JOIN ".$post_meta." as m2 ON ( co_posts.ID = m2.post_id )";



                $where.= " AND ( m2.meta_key='narnoo_product_start_time' AND m2.meta_value<='".$start_time."' ) ";



            }







            if( isset($_REQUEST['ncm_end_time']) && !empty($_REQUEST['ncm_end_time']) ) {



                $end_time = date("H:i:s", strtotime($_REQUEST['ncm_end_time']));



                $select.= " INNER JOIN ".$post_meta." as m3 ON ( co_posts.ID = m3.post_id )";



                $where.= " AND ( m3.meta_key='narnoo_product_end_time' AND m3.meta_value>='".$end_time."' ) ";



            } */











            if( ( isset($_REQUEST['ncm_start_time']) && !empty($_REQUEST['ncm_start_time']) ) || ( isset($_REQUEST['ncm_end_time']) && !empty($_REQUEST['ncm_end_time']) ) ) {



                $start_time = date("H:i:s", strtotime($_REQUEST['ncm_start_time']));



                $end_time = date("H:i:s", strtotime($_REQUEST['ncm_end_time']));







                $time_where = ( !empty($_REQUEST['ncm_start_time']) ) ? " AND TIME(m2.meta_value) >= '".$start_time."'" : '';



                $time_where.= ( !empty($_REQUEST['ncm_end_time']) ) ? " AND TIME(m2.meta_value) <= '".$end_time."'" : '';







                $select.= " INNER JOIN ".$post_meta." as m2 ON ( co_posts.ID = m2.post_id )";



                $where.= " AND ( m2.meta_key='narnoo_product_start_time' " . $time_where . " ) ";



            }







            $query = $select . $where . " GROUP BY co_posts.ID ORDER BY co_posts.post_title ASC";



            $data = $wpdb->get_col( $query );







            if( !empty($data) ) {



                $args = array(



                        'post_type'      => 'narnoo_product',



                        'posts_per_page' => -1,



                        'orderby'        => 'title',



                        'order'          => 'ASC',



                        'post__in'       => $data



                    );







                $field_data = array();



                $field_data['product'] = new WP_Query($args);







                $content = ncm_get_template_content("ncm-product-search-result", $field_data);







                $data = array( "status" => "success", "content" => $content);



                echo json_encode($data);



                die;



            } else {



                $content = '<div class="ncm_no_product msg"><center> No products found.</center></div>';



                $data = array( "status" => "success", "content" => $content);



                echo json_encode($data);



                die;



            }



        }







        function ncm_product_search_func( $atts = array(), $content = "" ) {

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();

            global $ncm_controls;







            $atts = shortcode_atts( array(



                'search' => 'true',



                'date' => 'true',



            ), $atts );











            $attractions = get_queried_object();



            $attraction_id = $attractions->ID;







            $field_data = array();







            if( $atts['search'] == 'true' ) {



                $field_data['ncm_search'] = $ncm_controls->ncm_control(



                                        array(



                                            "type" => "text",



                                            "value" => "",



                                            "placeholder" => "",



                                            "name" => "ncm_search",



                                            "class" => "ncm_search form-control",



                                            "style" => "padding: 6px 5px;"



                                        )



                                    );



            }







            if( $atts['date'] == 'true' ) {



                $field_data['ncm_start_time'] = $ncm_controls->ncm_control(



                                        array(



                                            "type" => "text",



                                            "value" => "",



                                            "placeholder" => "",



                                            "name" => "ncm_time",



                                            "class" => "ncm_start_time form-control ncm_timepicker",



                                            "style" => "padding: 6px 5px;"



                                        )



                                    );



                $field_data['ncm_end_time'] = $ncm_controls->ncm_control(



                                        array(



                                            "type" => "text",



                                            "value" => "",



                                            "placeholder" => "",



                                            "name" => "ncm_time",



                                            "class" => "ncm_end_time form-control ncm_timepicker",



                                            "style" => "padding: 6px 5px;"



                                        )



                                    );



            }







            $args = array(



                    'post_type' => 'narnoo_product',



                    'posts_per_page' => -1,



                    'orderby' => 'title',



                    'order'   => 'ASC',



                    'meta_query' => array(



                        array(



                            'key' => 'parent_post_id',



                            'value' => $attraction_id



                        )



                    )



                );



            $products = new WP_Query($args);







            $product_data = array();



            $product_data['product'] = $products;







            $content.= '<div class="ncm_container" style="position: relative;">';



            if( $products->post_count > 7 ) { // condition for check if no of product more than 7 then display filter fileds 



                $content.= '<input type="hidden" name="ncm_attractions_id" id="ncm_attractions_id" class="ncm_attractions_id" value="'.$attraction_id.'">';



                $content.= ncm_get_template_content("ncm-product-search", $field_data);



            }



            $content.= '<div class="ncm_product_list" id="ncm_product_list" >';



            $content.= ncm_get_template_content("ncm-product-search-result", $product_data);



            $content.= '</div>';



            



            $content.= '<div id="ncm_container_loader" class="hide" >';



            $content.= '<div class="ncm_container_loader"><i class="ncm_fa ncm_fa-spinner ncm_fa-spin ncm_fa-4x ncm_prc_avl"></i></div>';



            $content.= '</div>';







            $content.= '</div>';











            return $content;



        }







        function ncm_cart_item_func( $atts = array(), $content = "" ) {

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();
            
            global $ncm_cart, $ncm_payment_gateways;



            $atts = shortcode_atts( array(



                'type' => 'count',







                'link_text' => '',



                'if_cart_empty' => 'true'







            ), $atts );        



            $cart_total_item = count( $ncm_cart->ncm_get_cart_products() );



            if( $atts['type'] == 'link' ) {



                $cart_link = $ncm_payment_gateways->ncm_get_cart_page_link();







                $link_content = '<a href="' . $cart_link . '" class="ncm_cart_item_link">' . html_entity_decode( $atts['link_text'] );



                $link_content.= (!empty($cart_total_item)) ? '<span class="ncm_cart_total" >' . $cart_total_item . '</span>' : '';



                $link_content.= '</a>';



                if( $atts['if_cart_empty'] == 'true' ) {



                    $content.= $link_content; 



                } else if( $ncm_cart->ncm_get_cart_products() ) {



                    $content.= $link_content;



                }







            } else if( $atts['type'] == 'count' ) {







                $content = $cart_total_item;







            }



            return $content;



        }







        function ncm_product_post_query( $query ) {


            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();




            global $wpdb;



            $post = $wpdb->prefix . 'posts';







            // For shortcode of ncm_search_product search 



            if(isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'narnoo_product' /*&& get_post_type() == 'narnoo_product' */) {







                $query_research = isset($query->query_vars['s']) ? $query->query_vars['s'] : '';



                $query_meta_query = isset($query->query_vars['meta_query']) ? $query->query_vars['meta_query'] : '';







                $meta_query = array();







                $attraction_query = array();



                if( isset($_REQUEST['ncm_category']) && !empty($_REQUEST['ncm_category']) ) {



                    foreach ($_REQUEST['ncm_category'] as $value) {



                        $select = "SELECT ".$post.".ID FROM ".$post;



                        $where = " WHERE co_posts.post_type = 'narnoo_".$value."' ";



                        $parent_post_id = $wpdb->get_col( $select . $where );



                        if( !empty($parent_post_id) ) {



                            $attraction_query[] = array(



                                'key' => 'parent_post_id',



                                'value' => $parent_post_id,



                                'compare'   => 'IN',



                            );



                        }



                    }



                }







                if( isset($_REQUEST['ncm_attraction']) && !empty($_REQUEST['ncm_attraction']) ) {



                    foreach ($_REQUEST['ncm_attraction'] as $value) {



                        $attraction_query[] = array(



                            'key' => 'parent_post_id',



                            'value' => $value,



                            'compare' => '='



                        );



                    }



                }







                if( !empty($attraction_query) ){



                    $meta_query[] = array( 'relation' => 'OR', $attraction_query );



                }







                



                if( isset($_REQUEST['ncm_start_time']) && !empty($_REQUEST['ncm_start_time']) ) {



                    $meta_query[] = array(



                        array(



                            'key' => 'narnoo_product_start_time',



                            'value' => date("H:i:s", strtotime($_REQUEST['ncm_start_time'])),



                            'compare' => '>='



                        )



                    );



                }







                if( isset($_REQUEST['ncm_end_time']) && !empty($_REQUEST['ncm_end_time']) ) {



                    $meta_query[] = array(



                        array(



                            'key' => 'narnoo_product_start_time',



                            'value' => date("H:i:s", strtotime($_REQUEST['ncm_end_time'])),



                            'compare' => '<='



                        )



                    );



                }                 







                /* For match exact start and end time 







                if( isset($_REQUEST['ncm_start_time']) && !empty($_REQUEST['ncm_start_time']) ) {



                    $meta_query[] = array(



                        array(



                            'key' => 'narnoo_product_start_time',



                            'value' => date("H:i:s", strtotime($_REQUEST['ncm_start_time'])),



                            'compare' => '='



                        )



                    );



                }







                if( isset($_REQUEST['ncm_end_time']) && !empty($_REQUEST['ncm_end_time']) ) {



                    $meta_query[] = array(



                        array(



                            'key' => 'narnoo_product_end_time',



                            'value' => date("H:i:s", strtotime($_REQUEST['ncm_end_time'])),



                            'compare' => '='



                        )



                    );



                } */







                if( isset($_REQUEST['ncm_price']) && !empty($_REQUEST['ncm_price']) ) {



                    $price_arr = explode('-', $_REQUEST['ncm_price']);



                    $min_price = str_replace('$', '', $price_arr[0]);



                    $max_price = str_replace('$', '', $price_arr[1]);



                    $meta_query[] = array(



                        array(



                            'key' => 'product_min_price',



                            'value' => $min_price,



                            'type' => 'numeric',



                            'compare' => '>='



                        )



                    );



                    $meta_query[] = array(



                        array(



                            'key' => 'product_min_price',



                            'value' => $max_price,



                            'type' => 'numeric',



                            'compare' => '<='



                        )



                    );



                }







                // set search field



                if( isset( $_REQUEST['ncm_search'] ) && !empty( $_REQUEST['ncm_search'] ) && empty($query_research) ) {



                    $query->set( 's', $_REQUEST['ncm_search'] );



                }







                // set search meta



                if( !empty( $meta_query ) ) {



                    $query->set( 'meta_query', $meta_query );



                }



            }



        }







        function ncm_search_product_func ( $atts = array(), $content = "" ) {

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();

            global $wpdb, $ncm_controls, $ncm_attraction, $ncm_template_loader;







            $action = $field = '';



            $attractions = $ncm_attraction->ncm_get_attraction();



            $ncm_categroies = get_option( 'narnoo_custom_post_types', array() );



            $ncm_max_price = $wpdb->get_var("SELECT max(cast(meta_value as unsigned)) FROM `".$wpdb->prefix ."postmeta` where meta_key = 'product_min_price'" ); 







            //if( !$ncm_template_loader->ncm_product_listing ) {



                $action = get_site_url();



                $field = $ncm_controls->ncm_control(



                            array(



                                "type" => "hidden",



                                "value" => "narnoo_product",



                                "name" => "post_type",



                            )



                        );



            //}







            $defaule_atts = array(



                "date_picker" => true,



                "category" => true,



                "attraction" => true,



            );







            $field_data = array();







            $field_data['ncm_search'] = $ncm_controls->ncm_control(



                                    array(



                                        "type" => "text",



                                        "value" => ( isset($_REQUEST['ncm_search']) && !empty($_REQUEST['ncm_search']) ) ? $_REQUEST['ncm_search'] : '',



                                        "placeholder" => "",



                                        "name" => "ncm_search",



                                        "class" => "ncm_search form-control",



                                        "style" => "padding: 6px 5px;"



                                    )



                                );







            $field_data['ncm_start_time'] = $ncm_controls->ncm_control(



                                    array(



                                        "type" => "text",



                                        "value" => ( isset($_REQUEST['ncm_start_time']) && !empty($_REQUEST['ncm_start_time']) ) ? $_REQUEST['ncm_start_time'] : '',



                                        "placeholder" => "",



                                        "name" => "ncm_start_time",



                                        "class" => "ncm_start_time form-control ncm_timepicker",



                                        "style" => "padding: 6px 5px;"



                                    )



                                );



            



            $field_data['ncm_end_time'] = $ncm_controls->ncm_control(



                                    array(



                                        "type" => "text",



                                        "value" => ( isset($_REQUEST['ncm_end_time']) && !empty($_REQUEST['ncm_end_time']) ) ? $_REQUEST['ncm_end_time'] : '',



                                        "placeholder" => "",



                                        "name" => "ncm_end_time",



                                        "class" => "ncm_end_time form-control ncm_timepicker",



                                        "style" => "padding: 6px 5px;"



                                    )



                                );







            $ncm_value = '[0,'.$ncm_max_price.']';



            $price = '$0 - '.$ncm_max_price;



            if( isset($_REQUEST['ncm_price']) && !empty($_REQUEST['ncm_price']) ){



                $price = $_REQUEST['ncm_price'];



                $price_arr = explode('-', $_REQUEST['ncm_price']);



                $min_price = str_replace('$', '', $price_arr[0]);



                $max_price = str_replace('$', '', $price_arr[1]);   



                $ncm_value = '['.$min_price.','.$max_price.']';



            }



            $ncm_price.= '<input type="text" id="ncm_price" name="ncm_price" value="'.$price.'" readonly data-ncm_max_price = "'.$ncm_max_price.'" data-ncm_value="'.$ncm_value.'">';



            $ncm_price.= '<div id="slider-range"></div>';



            $field_data['ncm_price'] = $ncm_price;











            $selected_category = isset($_REQUEST['ncm_category']) ? $_REQUEST['ncm_category'] : '';



            $ncm_categroies_html = '';



            foreach ($ncm_categroies as $cat_key => $cat_value) {



                $slug = isset( $cat_key ) ? $cat_key : $cat_key;



                $checked = ( !empty($selected_category) && in_array($slug, $selected_category) ) ? 'checked="checked"' : '';



                $ncm_categroies_html.= '<br/><label for="ncm_category_'.$slug.'">';



                $ncm_categroies_html.= '<input type="checkbox" value="'.$slug.'" name="ncm_category[]" id="ncm_category_'.$slug.'" class="ncm_category" '.$checked.' /> '.ucfirst($cat_key);



                $ncm_categroies_html.= '</label>';



            }



            $field_data['ncm_categroies'] = $ncm_categroies_html;











            $selected_attractions = isset($_REQUEST['ncm_attraction']) ? $_REQUEST['ncm_attraction'] : '';



            $ncm_attraction = '';



            foreach( $attractions as $key => $value ) {



                $checked = ( !empty($selected_attractions) && in_array($key, $selected_attractions) ) ? 'checked="checked"' : '';



                $ncm_attraction.= '<label for="ncm_attraction_'.$key.'">';



                $ncm_attraction.= '<input type="checkbox" value="'.$key.'" name="ncm_attraction[]" id="ncm_attraction_'.$key.'" class="ncm_attraction" '.$checked.' /> '.$value;



                $ncm_attraction.= '</label>';



            }



            $field_data['ncm_attraction'] = $ncm_attraction;







            $content.= '<div class="ncm_container">';



            $content.= '<form action="'.$action.'" class="" id="" method="GET">';



            $content.= $field;



            $content.= ncm_get_template_content("ncm-search-product", $field_data);



            $content.= '</form>';



            $content.= '</div>';



            return $content;



        }





        function ncm_product_list_func($atts = array(), $content = ""){

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();

            global $ncm_settings, $post;


            if(!empty($atts['col'])){
                $col = $atts['col'];
            }

            //$posts_per_page = $atts['posts_per_page'];



            if(!empty($atts['posts_per_page'])){

                $perpage = $atts['posts_per_page'];

            }else{

                $perpage = 12;

            }



            $products = new WP_Query( array('posts_per_page'=>$perpage,

                            'post_type'=>'narnoo_product',

                            'post_status' => 'publish',

                            'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 

                        ); 

                   

            

            if($col == '3'){

                $colclass = "ncm-col-xs-12 ncm-col-sm-6 ncm-col-md-4 grid-3";

            }elseif ($col == '4') {

                $colclass = "ncm-col-xs-12 ncm-col-sm-6 ncm-col-md-4 ncm-col-lg-3 grid-4";

            }elseif ($col == '2') {

                $colclass = "ncm-col-xs-12 ncm-col-sm-12 ncm-col-md-6";

            }else{

                $colclass = "ncm-col-xs-12 ncm-col-sm-6 ncm-col-md-4 grid-3";

            }            



            $content = '';



            //$content .= '<div class="container">';

            $content .= '<div class="productlist-shortcode">';

                $content .= '<div class="ncm-row">';

                    while ( $products->have_posts() ) : $products->the_post(); 

                        $image_path = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );

                        $image_alt = get_the_title();



                        

                        $productprice = get_post_meta( $post->ID, 'product_min_price', true );

                        if(!empty($productprice)){

                            $price = $ncm_settings->ncm_display_price($productprice);

                        }



                        if(!empty($productprice)){

                            $btncaption = 'Book Now';

                        }else{

                            $btncaption = 'More Info';

                        }



                        $content .= '<div class="'.$colclass.'">';

                          

                          $content .= '<div class="card card-price">';

                            $content .= '<div class="card-img">';

                              $content .= '<a href="'.get_permalink().'">';

                                if(!empty($image_path)){

                                    $content .= '<img src="'.$image_path.'" class="img-responsive" alt="'.$image_alt.'">';

                                }else{

                                    $content .= '<img src="'.NCM_IMAGES_URL.'no-image.jpg" class="img-responsive" alt="'.$image_alt.'">';

                                }

                              $content .= '</a>';

                            $content .= '</div>';

                            $content .= '<div class="card-body">';

                                if(!empty($productprice)){

                                  $content .= '<div class="price">'.$price.'</div>';

                                }

                                $content .= '<div class="lead">'.get_the_title().'</div>';

                                $content .= '<div class="details"><p>'.ncm_truncate(strip_tags(get_the_content()), 70, ' ').'</p></div>';

                                $content .= '<a href="'.get_permalink().'" class="btn btn-primary btn-block buy-now btn-lg">'.$btncaption.'</a>';

                            $content .= '</div>';

                          $content .= '</div>';

                        $content .= '</div>';

                    endwhile;    

                    wp_reset_postdata();



                    $content .= '<div class="nrm-pagination">';

                        

                        $big = 999999999; // need an unlikely integer

                         $content .= paginate_links( array(

                            'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),

                            'format' => '?paged=%#%',

                            'current' => max( 1, get_query_var('paged') ),

                            'total' => $products->max_num_pages

                        ) );

                    

                    $content .= '</div>';



                $content .= '</div>';

            $content .= '</div>';              

            //$content .= '</div>';



            return $content;



        }





        function ncm_featured_product_func($atts = array(), $content = ""){

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();

            global $ncm_settings, $post;

            
            if(!empty($atts['col'])){
                $col = $atts['col'];
            }

            
            //$posts_per_page = $atts['posts_per_page'];

            if(!empty($atts['posts_per_page'])){

                $perpage = $atts['posts_per_page'];

            }else{

                $perpage = 12;

            }



            /*$products = new WP_Query( array('posts_per_page'=>$perpage,

                            'post_type'=>'narnoo_product',

                            'post_status' => 'publish',

                            'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 

                        );*/

            $featuredargs = array(

                    'post_type' => 'narnoo_product', 

                    'post_status' => 'publish', 

                    'posts_per_page' => $perpage, 

                    'order' => 'DESC',

                    'meta_query' => array(

                        array(

                            'key' => 'narnoo_featured_product',

                            'value' => 'on'

                        )

                    ) 

                );

            $featuredloop = new WP_Query($featuredargs);             

            

            

            if($col == '3'){

                $colclass = "ncm-col-xs-12 ncm-col-sm-6 ncm-col-md-4 grid-3";

            }elseif ($col == '4') {

                $colclass = "ncm-col-xs-12 ncm-col-sm-6 ncm-col-md-4 ncm-col-lg-3 grid-4";

            }elseif ($col == '2') {

                $colclass = "ncm-col-xs-12 ncm-col-sm-12 ncm-col-md-6";

            }else{

                $colclass = "ncm-col-xs-12 ncm-col-sm-6 ncm-col-md-4 grid-3";

            }            



            $content = '';



            //$content .= '<div class="container">';

            $content .= '<div class="productlist-shortcode">';

                $content .= '<div class="ncm-row">';

                    while ( $featuredloop->have_posts() ) : $featuredloop->the_post(); 

                        $image_path = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );

                        $image_alt = get_the_title();



                        

                        $productprice = get_post_meta( $post->ID, 'product_min_price', true );

                        if(!empty($productprice)){

                            $price = $ncm_settings->ncm_display_price($productprice);

                        }



                        if(!empty($productprice)){

                            $btncaption = 'Book Now';

                        }else{

                            $btncaption = 'More Info';

                        }



                        $content .= '<div class="'.$colclass.'">';

                          

                          $content .= '<div class="card card-price">';

                            $content .= '<div class="card-img">';

                              $content .= '<a href="'.get_permalink().'">';

                                if(!empty($image_path)){

                                    $content .= '<img src="'.$image_path.'" class="img-responsive" alt="'.$image_alt.'">';

                                }else{

                                    $content .= '<img src="'.NCM_IMAGES_URL.'no-image.jpg" class="img-responsive" alt="'.$image_alt.'">';

                                }

                              $content .= '</a>';

                            $content .= '</div>';

                            $content .= '<div class="card-body">';

                                if(!empty($productprice)){

                                  $content .= '<div class="price">'.$price.'</div>';

                                }

                                $content .= '<div class="lead">'.get_the_title().'</div>';

                                $content .= '<div class="details"><p>'.ncm_truncate(strip_tags(get_the_content()), 70, ' ').'</p></div>';

                                $content .= '<a href="'.get_permalink().'" class="btn btn-primary btn-block buy-now btn-lg">'.$btncaption.'</a>';

                            $content .= '</div>';

                          $content .= '</div>';

                        $content .= '</div>';

                    endwhile;    

                    wp_reset_postdata();



                $content .= '</div>';

            $content .= '</div>';              

            //$content .= '</div>';



            return $content;



        }





        function ncm_category_product_func($atts = array(), $content = ""){

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();

            global $ncm_settings, $post;



            $cat_id = $atts['cat_id'];

            //$cat_id = 18;



            $args = array(

                'post_type' => 'narnoo_product',

                'posts_per_page' => -1,

                'orderby' => 'title',

                'order'   => 'ASC',

                'meta_query' => array(

                  array(

                    'key' => 'parent_post_id',

                    'value' => $cat_id

                    )

                  )

            );

            $product = new WP_Query($args);



            //$content .= '<div class="ncm-row">';

              $content .= '<div class="productlist-shortcode">';

              

              while ($product->have_posts()) {



                    $product->the_post(); 



                    

                      $image_path = wp_get_attachment_url( get_post_thumbnail_id( $product->ID ) );

                      $image_alt = get_the_title();



                      $post_id = get_the_ID();

                      $price = get_post_meta($post_id, 'product_min_price', true);



                      if(!empty($price)){

                          $btncaption = 'Book Now';

                      }else{

                          $btncaption = 'More Info';

                      } 

                    



                    $content .= '<div class="ncm-col-xs-12 ncm-col-sm-6 ncm-col-md-4">';

                        

                        $content .= '<div class="card card-price">';

                          $content .= '<div class="card-img">';

                            $content .= '<a href="'. get_permalink() .'">';

                               if(!empty($image_path)){ 

                                  $content .= '<img src="'. $image_path .'" class="img-responsive" alt="'.$image_alt.'">';

                                }else{ 

                                  $content .= '<img src="'.NCM_IMAGES_URL .'no-image.jpg" class="img-responsive" alt="'.$image_alt .'">';

                                } 

                            $content .= '</a>';

                          $content .= '</div>';

                          $content .= '<div class="card-body">';

                               if(!empty($price)){ 

                                $content .= '<div class="price">'.$ncm_settings->ncm_display_price($price).'</div>';

                               }

                              $content .= '<div class="lead">'.get_the_title().'</div>';

                              $content .= '<div class="details"><p>'. ncm_truncate(strip_tags(get_the_content()), 70, ' ') .'</p></div>';

                              $content .= '<a href="'.get_permalink().'" class="btn btn-primary btn-block buy-now btn-lg">'.$btncaption.'</a>';

                          $content .= '</div>';

                        $content .= '</div>';

                      $content .= '</div>';



                   }

                wp_reset_postdata();   

             

              

              $content .= '</div>';

            //$content .= '</div>';



            return $content;





        }





        function ncm_product_availability_func( $atts = array(), $content = "" ) {

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();
            
            global $ncm_template_product;



            ob_start();



            $ncm_template_product->ncm_single_product_summary_func();



            $content.= ob_get_contents();



            ob_end_clean();



            return $content;



        }



        function ncm_availability_func( $atts = array(), $content = "" ) {

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();



            global $wpdb, $post, $ncm_controls, $ncm_settings, $ncm_payment_gateways, $ncm_template_product, $ncm_narnoo_helper;


            $btnsize = '';
            $enquiry = '';


            $ncm_setting_general = get_option( 'ncm_setting_general' );

            




            if(!empty($atts['operator'])){
                $operator_id = $atts['operator'];
            }

            if(!empty($atts['product'])){
                $booking_id = $atts['product'];
            }

            if(!empty($atts['enquiry'])){
                $enquiry = $atts['enquiry'];
            }

            if(!empty($atts['bgcolor'])){
                $bgcolor = $atts['bgcolor'];
            }

            if(!empty($atts['color'])){
                $textcolor = $atts['color'];
            }


            if(!empty($atts['btnsize'])){
                $btnsize = $atts['btnsize'];
            }



            if($btnsize == 'small'){

                $buttonsize = 'btn-sm';

            }elseif ($btnsize == 'large') {

                $buttonsize = 'btn-lg';

            }elseif ($btnsize == 'medium') {

                $buttonsize = 'btn-md';

            }else{

                $buttonsize = 'btn-md';

            }





            $shortcoderes =  $ncm_narnoo_helper->ncm_product_details($operator_id, $booking_id);



            $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = $booking_id", ARRAY_A );



            $postid = $results[0]['post_id'];



            if(!empty($bgcolor) || !empty($textcolor)){

                $shortcodestyle = 'style="background:'.$bgcolor.';color:'.$textcolor.'"';

            }else{

                $shortcodestyle = '';

            }

            

            $productprice = get_post_meta( $postid, 'product_min_price', true );



            if(  !get_post_meta( $postid, 'narnoo_enable_reservation', true ) ) { 

                return false;

            }

            $args = array();

            $args['ncm_notice_islive_false'] = '';

            if( isset($shortcoderes->bookingData->isLive) && !$shortcoderes->bookingData->isLive ) {

                $ncm_general_data = $ncm_settings->ncm_get_settings_func();

                $ncm_notice_islive_false = isset($ncm_general_data['ncm_setting_notice_islive_false']) ? $ncm_general_data['ncm_setting_notice_islive_false'] : '';

                $args['ncm_notice_islive_false'] = '<span><i class="ncm_fa ncm_fa-info-circle"></i>'.$ncm_notice_islive_false.'</span>';

            } 

            $post_id = $postid;

            //$post_id = $post->ID;

            

            $hidden_fields = '';

            $popup_content = '';

            $ncm_bookingCodes = '';

            $ncm_product_type = 'multiple';

            if( isset( $shortcoderes->bookingData ) ) {



                $bookingdata = $shortcoderes->bookingData;

                //print_r( $bookingdata );

                if( $bookingdata->bookingCodesCount > 1 ) {

                    $ncm_bookingCodes = '';

                    $ncm_product_type = 'multiple';

                } else {

                    $bookingcode_arr = isset($bookingdata->bookingCodes) ? $bookingdata->bookingCodes : '';

                    $bookingCodes = isset($bookingcode_arr[0]->id) ? $bookingcode_arr[0]->id : '';

                    $productTimes_arr = isset($bookingdata->productTimes) ? $bookingdata->productTimes : '';



                    if(count($productTimes_arr) > 1){

                        $productTimesID = '';

                        foreach($productTimes_arr as $time){

                            if($time->default == 1 ){

                                $productTimesID .= isset($time->id) ? ':'.$time->id : ':TT';

                            }

                        }

                        //echo $productTimesID;

                        if(!empty($productTimesID)){

                            $productTimes = $productTimesID;

                        }else{

                            $productTimes = isset($productTimes_arr[0]->id) ? ':'.$productTimes_arr[0]->id : ':TT';

                        }

                    }else{

                        $productTimes = isset($productTimes_arr[0]->id) ? ':'.$productTimes_arr[0]->id : ':TT';    

                    }

                    

                    $ncm_bookingCodes = $bookingCodes.$productTimes;

                    $ncm_product_type = 'single';

                }

            }

           

            $hidden_fields.= $ncm_controls->ncm_control(

                                array(

                                    "type" => "hidden",

                                    "name" => "ncm_product_type",

                                    "id" => "ncm_product_type",

                                    "value" => $ncm_product_type,

                                )

                            );

            $hidden_fields.= $ncm_controls->ncm_control(

                                array(

                                    "type" => "hidden",

                                    "name" => "ncm_post_id",

                                    "id" => "ncm_post_id",

                                    "value" => $postid,

                                    

                                )

                            );

            $hidden_fields.= $ncm_controls->ncm_control(

                                array(

                                    "type" => "hidden",

                                    "name" => "ncm_bookingCodes",

                                    "id" => "ncm_bookingCodes",

                                    "value" => $ncm_bookingCodes,

                                )

                            );

            $hidden_fields.= $ncm_controls->ncm_control(

                                array(

                                    "type" => "hidden",

                                    "name" => "ncm_link",

                                    "id" => "ncm_link",

                                    "value" => $ncm_payment_gateways->ncm_get_cart_page_link(),

                                )

                            );

            $post->ncm_popup_content = $popup_content;

            $content .= "$hidden_fields";

            

            //echo '<div class="product-info">';

            //ncm_get_template("summery-availability-shortcode", $args);

            //echo '</div>';



            $content .='<div class="summary entry-summary">';



                /*$content .='<p class="price">TODAY\'S RATES FROM '.$productprice.'';

                    $content .='<br/><small>Price is per adult and includes all levies, fees & taxes</small>';

                $content .='</p>';*/





            $content .= '<div class="product-info">';



                // Availability modalncm window checkbox un-checked generel setting

                if($ncm_setting_general['ncm_narnoo_availability_modalncm_window'] != '1' ){



                        if( isset( $shortcoderes->bookingData ) ) {



                        $bookingdata = $shortcoderes->bookingData;

                        $productTimes_arr = isset($bookingdata->productTimes) ? $bookingdata->productTimes : '';



                            if(count($productTimes_arr) > 1){ 

                                $content .='<div class="ncm_product_time_select">';

                                    $content .='<label class="ncm-label">Pick Product Time</label>';

                                    $content .='<select name="pruduct_time" id="pruduct_time">';

                                      foreach($productTimes_arr as $time){ 

                                        if($time->default == 1 ){

                                            $selected = "selected='selected'";

                                        }else{

                                            $selected = "";

                                        } 

                                        $content .='<option value="'.$time->id .'" '.$selected.' >'. $time->time .'</option>';

                                      } 

                                    $content .='</select>';

                                $content .='<div>';

                            }

                        }



                        $content .='<p class="ncm-date">';

                        $content .= '<label class="ncm-label">Start Date</label>';



                            $content.= $ncm_controls->ncm_control(



                                array(



                                    "type" => "text",



                                    "name" => "ncm_travel_date_start",



                                    "id" => "ncm_travel_date_start",



                                    "class" => "ncm_travel_date_start ncm_textbox ncm_datepicker",



                                    "value" => date('d-m-Y', strtotime(date('d-m-Y'). ' +1 day')),



                                    "placeholder" => "dd-mm-yyyy",



                                    "data-format" => "dd-mm-yyyy",



                                    "data-startDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 days" ) ),



                                    "data-endDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 years" ) ),



                                )



                            );

                        $content .='</p>';    



                         $content .='<p class="ncm-date">';

                         $content .= '<label class="ncm-label">End Date</label>';



                            $content.= $ncm_controls->ncm_control(



                                array(



                                    "type" => "text",



                                    "name" => "ncm_travel_date_end",



                                    "id" => "ncm_travel_date_end",



                                    "class" => "ncm_travel_date_end ncm_textbox ncm_datepicker",



                                    "value" => date('d-m-Y', strtotime(date('d-m-Y'). ' +2 day')),



                                    "placeholder" => "dd-mm-yyyy",



                                    "data-format" => "dd-mm-yyyy",



                                    "data-startDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 days" ) ),



                                    "data-endDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 years" ) ),



                                )



                            ); 

                            $content .='</p>';  



                            $content .='<p class="ncm-date">';

                            $loader= '<span class="ncm_chk_price_display_loader ncm_display_loader"><i class="ncm_fa-li ncm_fa ncm_fa-spinner ncm_fa-spin"></i></span>';

                            $content.= '<button type="submit" name="chk_price" id="chk_price" class="single_add_to_cart_button alt chk_price btn btn-info '.$buttonsize.'" '.$shortcodestyle.'>Check Availability'.$loader.'</button>'; 

                            $content .='</p>';  

                   



                    if($enquiry == 'true'){        

                       $content .='<p class="ncm-date">';

                       $content .='<button type="button" class="btn btn-info btn-lg" data-toggle="modalncm" data-target="#have_a_question">Have A Question?</button>';

                       $content .='</p>';

                    }   



                   //modalncm For display price check_my_price_now_popup 



                    $content .='<div id="check_my_price_now_popup" class="modalncm fade" role="dialog">';

                        $content .='<div class="modalncm-dialog">';

                            $content .='<div class="modalncm-content">';

                                $content .='<div class="modalncm-header">';

                                    $content .='<button type="button" class="close" data-dismiss="modalncm">&times;</button>';



                                    $content .='<h4 class="modalncm-title">Product Availability And Prices</h4>';



                                $content .='</div>';

                                $content .='<div class="modalncm-body check_my_price_now_content" >';

                                    $content .='<div class="ncm_loader ncm_price_availability_loader" style="display:none;">';

                                        $content .='<i class="ncm_fa ncm_fa-spinner ncm_fa-spin ncm_fa-4x ncm_prc_avl"></i>';

                                    $content .='</div>';

                                    $content .='<div class="ncm-row modalncm-container">';

                                        $content .='<div class="ncm-ncm-col-md-12" id="check_my_price_now_content">'.$post->ncm_popup_content.'</div>';

                                    $content .='</div>';

                                $content .='</div>';

                                $content .='<div class="modalncm-footer">';

                                    $content .='<button type="button" class="btn btn-default" data-dismiss="modalncm">Close</button>';

                                $content .='</div>';

                            $content .='</div>';

                        $content .='</div>';

                    $content .='</div>';



                    //echo ncm_get_template("have-a-question");



                    // Have a Question popup

                    if($enquiry == 'true'){

                        $content .='<div id="have_a_question" class="modalncm fade" role="dialog">';

                            $content .='<div class="modalncm-dialog">';

                                

                                $content .='<div class="modalncm-content">';

                                    $content .='<div class="modalncm-header">';

                                        $content .='<button type="button" class="close" data-dismiss="modalncm">&times;</button>';

                                        $content .='<h4 class="modalncm-title">Have A Question?</h4>';

                                    $content .='</div>';

                                    $content .='<div class="modalncm-body">';

                                        $content .='<form id="ncm_question_form" method="post" _lpchecked="1">';

                                            $content .='<div class="ncm-row">';

                                                $content .='<div class="ncm-col-lg-12">';

                                                    $content .='<div class="ncm-col-lg-12 alert alert-danger alert-dismissible hidden" id="hidden-error">';

                                                        $content .='<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>

                                                        Whoops! There was a problem with your payment. Please enter your payment details again.<br>

                                                        <i class="fa fa-asterisk"></i><em id="error_message"></em> ';

                                                    $content .='</div>';

                                                    $content .='<div class="ncm-col-lg-6 ncm-col-md-4 ncm-col-sm-6 ncm-col-xs-12">';

                                                        $content .='<div class="form-group">';

                                                            $content .='<label class="product-title">Your Name<small style="color: #00416e"> * </small>

                                                            </label>';

                                                            $content .='<input type="text" name="booking_name" id="booking_name" class="full-width" value="" />';

                                                        $content .='</div>';

                                                    $content .='</div>';

                                                    $content .='<div class="ncm-col-lg-6 ncm-col-md-4 ncm-col-sm-6 ncm-col-xs-12">';

                                                        $content .='<div class="form-group">';

                                                            $content .='<label class="product-title">Email Address <small style="color: #00416e"> * </small>

                                                            </label>';

                                                            $content .='<input type="email" name="booking_email" id="booking_email" class="full-width" value="" />';

                                                        $content .='</div>';

                                                    $content .='</div>';

                                                    $content .='<div class="ncm-col-lg-6 ncm-col-md-4 ncm-col-sm-6 ncm-col-xs-12">';

                                                        $content .='<div class="form-group">';

                                                            $content .='<label class="product-title">Phone Number<small style="color: #00416e"> * </small>

                                                            </label>';

                                                            $content .='<input type="text" name="booking_phone" id="booking_phone" class="full-width" value="" />';

                                                        $content .='</div>';

                                                    $content .='</div>';

                                                    $content .='<div class="ncm-col-lg-6 ncm-col-md-4 ncm-col-sm-6 ncm-col-xs-12">';

                                                        $content .='<div class="form-group">';

                                                            $content .='<label class="product-title">Accommodation<small style="color: #00416e"> * </small>

                                                            </label>';

                                                            $content .='<input type="text" name="booking_hotel" id="booking_hotel" class="full-width" value="" />';

                                                        $content .='</div>';

                                                    $content .='</div>';

                                                    $content .='<div class="ncm-col-lg-12">';

                                                        $content .='<div class="form-group">';

                                                            $content .='<label class="product-title"> Questions or Comments</label>';

                                                            $content .='<textarea name="booking_comments" class="full-width"></textarea>';

                                                        $content .='</div>';

                                                    $content .='</div>';

                                                $content .='</div>';

                                            $content .='</div>';

                                        $content .='</form>';

                                    $content .='</div>';

                                    $content .='<div class="modalncm-footer">';

                                        $content .='<button type="button" class="btn btn-primary" name="submit" id="ncm_question_submit">Submit</button>';

                                            

                                        $content .='<button type="button" class="btn btn-default" data-dismiss="modalncm">Close</button>';

                                    $content .='</div>';

                                $content .='</div>';

                            $content .='</div>';

                        $content .='</div> ';

                    }



                // Availability modalncm window checkbox uchecked generel setting    

                }else{



                    $content .='<p class="ncm-date">';

                  

                    

                    $content .='<button type="button" class="btn btn-info btn-lg button" data-toggle="modalncm" data-target="#check_availability" '.$shortcodestyle.'>Check Availability</button>';

                    $content .='</p>'; 



                    $content .='<div id="check_availability" class="modalncm fade check-availability-window" role="dialog">';

                        $content .='<div class="modalncm-dialog">';

                            $content .='<div class="modalncm-content">';

                                $content .='<div class="modalncm-header">';

                                    $content .='<button type="button" class="close" data-dismiss="modalncm">&times;</button>';



                                    $content .='<h4 class="modalncm-title">Select your dates for travel.</h4>';



                                $content .='</div>';

                                $content .='<div class="modalncm-body check_my_price_now_content" >';

                                    

                                    $content .='<div class="ncm-row modalncm-container">';

                                        $content .='<div class="ncm-ncm-col-md-12" id="">';



                                        $content .='<div class="ncm-row">';



                                        if( isset( $shortcoderes->bookingData ) ) {



                                            $bookingdata = $shortcoderes->bookingData;

                                            $productTimes_arr = isset($bookingdata->productTimes) ? $bookingdata->productTimes : '';



                                                if(count($productTimes_arr) > 1){ 

                                                    $content .='<div class="ncm_product_time_select">';

                                                        $content .='<label class="ncm-label">Pick Product Time</label>';

                                                        $content .='<select name="pruduct_time" id="pruduct_time">';

                                                          foreach($productTimes_arr as $time){ 

                                                            if($time->default == 1 ){

                                                                $selected = "selected='selected'";

                                                            }else{

                                                                $selected = "";

                                                            } 

                                                            $content .='<option value="'.$time->id .'" '.$selected.' >'. $time->time .'</option>';

                                                          } 

                                                        $content .='</select>';

                                                    $content .='<div>';

                                                }

                                            }

                                        $content .='<div class="ncm-col-md-6">';    

                                        $content .='<p class="ncm-date">';

                                        $content .= '<label class="ncm-label">Start Date</label>';



                                            $content.= $ncm_controls->ncm_control(



                                                array(



                                                    "type" => "text",



                                                    "name" => "ncm_travel_date_start",



                                                    "id" => "ncm_travel_date_start",



                                                    "class" => "ncm_travel_date_start ncm_textbox ncm_datepicker",



                                                    "value" => date('d-m-Y', strtotime(date('d-m-Y'). ' +1 day')),



                                                    "placeholder" => "dd-mm-yyyy",



                                                    "data-format" => "dd-mm-yyyy",



                                                    "data-startDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 days" ) ),



                                                    "data-endDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 years" ) ),



                                                )



                                            );

                                        $content .='</p>';    

                                        $content .='</div>';

                                        $content .='<div class="ncm-col-md-6">';

                                         $content .='<p class="ncm-date">';

                                         $content .= '<label class="ncm-label">End Date</label>';



                                            $content.= $ncm_controls->ncm_control(



                                                array(



                                                    "type" => "text",



                                                    "name" => "ncm_travel_date_end",



                                                    "id" => "ncm_travel_date_end",



                                                    "class" => "ncm_travel_date_end ncm_textbox ncm_datepicker",



                                                    "value" => date('d-m-Y', strtotime(date('d-m-Y'). ' +2 day')),



                                                    "placeholder" => "dd-mm-yyyy",



                                                    "data-format" => "dd-mm-yyyy",



                                                    "data-startDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 days" ) ),



                                                    "data-endDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 years" ) ),



                                                )



                                            ); 

                                            $content .='</p>';

                                            $content .='</div>';



                                            $content .='<div class="ncm-col-md-12">';

                                            $content .='<p class="ncm-date">';

                                            $loader= '<span class="ncm_chk_price_display_loader ncm_display_loader"><i class="ncm_fa-li ncm_fa ncm_fa-spinner ncm_fa-spin"></i></span>';

                                            $content.= '<button type="submit" name="chk_price" id="chk_price" class="single_add_to_cart_button button alt chk_price btn btn-info btn-lg" '.$shortcodestyle.'>Check Dates'.$loader.'</button>'; 

                                            $content .='</p>';

                                            $content .='</div>';  



                                        $content .='</div>';    

                                        $content .='</div>';

                                    $content .='</div>';

                                $content .='</div>';



                                $content .='<div class="modalncm-footer">';

                                    $content .='<button type="button" class="btn btn-default" data-dismiss="modalncm">Close</button>';

                                $content .='</div>';

                            $content .='</div>';

                        $content .='</div>';

                    $content .='</div>';



                    //modalncm For display price check_my_price_now_popup 



                    $content .='<div id="check_my_price_now_popup" class="modalncm fade" role="dialog">';

                        $content .='<div class="modalncm-dialog">';

                            $content .='<div class="modalncm-content">';

                                $content .='<div class="modalncm-header">';

                                    $content .='<button type="button" class="close" data-dismiss="modalncm">&times;</button>';



                                    $content .='<h4 class="modalncm-title">Product Availability And Prices</h4>';



                                $content .='</div>';

                                $content .='<div class="modalncm-body check_my_price_now_content" >';

                                    $content .='<div class="ncm_loader ncm_price_availability_loader" style="display:none;">';

                                        $content .='<i class="ncm_fa ncm_fa-spinner ncm_fa-spin ncm_fa-4x ncm_prc_avl"></i>';

                                    $content .='</div>';

                                    $content .='<div class="ncm-row modalncm-container">';

                                        $content .='<div class="ncm-col-md-12" id="check_my_price_now_content">'.$post->ncm_popup_content.'</div>';

                                    $content .='</div>';

                                $content .='</div>';

                                $content .='<div class="modalncm-footer">';

                                    $content .='<button type="button" class="btn btn-default" data-dismiss="modalncm">Close</button>';

                                $content .='</div>';

                            $content .='</div>';

                        $content .='</div>';

                    $content .='</div>';



                }



            $content .='</div>';



            $content .='</div>';



            $content .='

<script type="text/javascript">

jQuery(document).ready(function($) {  

    

    var getHidVal = jQuery("#ncm_bookingCodes").val();

    var getHidFinal = getHidVal.split(":");

    //console.log(getHidFinal);



    jQuery("#pruduct_time").on("change", function (e){

        var selectTime =  jQuery(this).val();

        getHidFinal.pop();

        getHidFinal.push(selectTime);

        var inputVal = getHidFinal.join(":");

        //console.log(inputVal);

        jQuery("#ncm_bookingCodes").val(inputVal);

    });



});    

</script>';



            return $content;



        }





        function ncm_availability_button_func( $atts = array(), $content = "" ) {

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();

            global $wpdb, $post, $ncm_controls, $ncm_settings, $ncm_payment_gateways, $ncm_template_product, $ncm_narnoo_helper;

            $btnsize = '';

            if(!empty($atts['operator'])){
                $operator_id = $atts['operator'];
            }

            if(!empty($atts['product'])){
                $booking_id = $atts['product'];
            }

            if(!empty($atts['enquiry'])){
                $enquiry = $atts['enquiry'];
            }

            if(!empty($atts['bgcolor'])){
                $bgcolor = $atts['bgcolor'];
            }

            if(!empty($atts['color'])){
                $textcolor = $atts['color'];
            }

            if(!empty($atts['btnsize'])){
                $btnsize = $atts['btnsize'];
            }    


            if($btnsize == 'small'){

                $buttonsize = 'btn-sm';

            }elseif ($btnsize == 'large') {

                $buttonsize = 'btn-lg';

            }elseif ($btnsize == 'medium') {

                $buttonsize = 'btn-md';

            }else{

                $buttonsize = 'btn-md';

            }

            





            if(!empty($bgcolor) || !empty($textcolor)){

                $shortcodestyle = 'style="background:'.$bgcolor.';color:'.$textcolor.'"';

            }else{

                $shortcodestyle = '';

            }



            $shortcoderes =  $ncm_narnoo_helper->ncm_product_details($operator_id, $booking_id);



            $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = $booking_id", ARRAY_A );



            $postid = $results[0]['post_id'];



            $productprice = get_post_meta( $postid, 'product_min_price', true );



            if(  !get_post_meta( $postid, 'narnoo_enable_reservation', true ) ) { 

                return false;

            }

            $args = array();

            $args['ncm_notice_islive_false'] = '';

            if( isset($shortcoderes->bookingData->isLive) && !$shortcoderes->bookingData->isLive ) {

                $ncm_general_data = $ncm_settings->ncm_get_settings_func();

                $ncm_notice_islive_false = isset($ncm_general_data['ncm_setting_notice_islive_false']) ? $ncm_general_data['ncm_setting_notice_islive_false'] : '';

                $args['ncm_notice_islive_false'] = '<span><i class="ncm_fa ncm_fa-info-circle"></i>'.$ncm_notice_islive_false.'</span>';

            } 

            $post_id = $postid;

            //$post_id = $post->ID;

            

            $hidden_fields = '';

            $popup_content = '';

            $ncm_bookingCodes = '';

            $ncm_product_type = 'multiple';

            if( isset( $shortcoderes->bookingData ) ) {



                $bookingdata = $shortcoderes->bookingData;

                //print_r( $bookingdata );

                if( $bookingdata->bookingCodesCount > 1 ) {

                    $ncm_bookingCodes = '';

                    $ncm_product_type = 'multiple';

                } else {

                    $bookingcode_arr = isset($bookingdata->bookingCodes) ? $bookingdata->bookingCodes : '';

                    $bookingCodes = isset($bookingcode_arr[0]->id) ? $bookingcode_arr[0]->id : '';

                    $productTimes_arr = isset($bookingdata->productTimes) ? $bookingdata->productTimes : '';



                    if(count($productTimes_arr) > 1){

                        $productTimesID = '';

                        foreach($productTimes_arr as $time){

                            if($time->default == 1 ){

                                $productTimesID .= isset($time->id) ? ':'.$time->id : ':TT';

                            }

                        }

                        //echo $productTimesID;

                        if(!empty($productTimesID)){

                            $productTimes = $productTimesID;

                        }else{

                            $productTimes = isset($productTimes_arr[0]->id) ? ':'.$productTimes_arr[0]->id : ':TT';

                        }

                    }else{

                        $productTimes = isset($productTimes_arr[0]->id) ? ':'.$productTimes_arr[0]->id : ':TT';    

                    }

                    

                    $ncm_bookingCodes = $bookingCodes.$productTimes;

                    $ncm_product_type = 'single';

                }

            }

           



            $content .='<div class="summary entry-summary">';



            $content .= '<div class="product-info">';



            $hidden_fields.= $ncm_controls->ncm_control(

                                array(

                                    "type" => "hidden",

                                    "name" => "ncm_product_type",

                                    "id" => "ncm_product_type",

                                    "value" => $ncm_product_type,

                                )

                            );

            $hidden_fields.= $ncm_controls->ncm_control(

                                array(

                                    "type" => "hidden",

                                    "name" => "ncm_post_id",

                                    "id" => "ncm_post_id",

                                    "value" => $postid,

                                    

                                )

                            );

            $hidden_fields.= $ncm_controls->ncm_control(

                                array(

                                    "type" => "hidden",

                                    "name" => "ncm_bookingCodes",

                                    "id" => "ncm_bookingCodes",

                                    "value" => $ncm_bookingCodes,

                                )

                            );

            $hidden_fields.= $ncm_controls->ncm_control(

                                array(

                                    "type" => "hidden",

                                    "name" => "ncm_link",

                                    "id" => "ncm_link",

                                    "value" => $ncm_payment_gateways->ncm_get_cart_page_link(),

                                )

                            );

            $post->ncm_popup_content = $popup_content;

            $content .= "$hidden_fields";



            $content .='<p class="ncm-date">';

          

          

            $content .='<button type="button" class="btn btn-info '.$buttonsize.'" data-toggle="modalncm" data-target="#check_availability" '.$shortcodestyle.'>Check Availability</button>';

            $content .='</p>'; 



            $content .='<div id="check_availability" class="modalncm fade check-availability-window " role="dialog">';

                $content .='<div class="modalncm-dialog">';

                    $content .='<div class="modalncm-content">';

                        $content .='<div class="modalncm-header">';

                            $content .='<button type="button" class="close" data-dismiss="modalncm">&times;</button>';



                            $content .='<h4 class="modalncm-title">Select your dates for travel.</h4>';



                        $content .='</div>';

                        $content .='<div class="modalncm-body check_my_price_now_content" >';

                            

                            $content .='<div class="ncm-row modalncm-container">';

                                $content .='<div class="ncm-col-md-12" id="">';



                                $content .='<div class="ncm-row">';



                                if( isset( $shortcoderes->bookingData ) ) {



                                    $bookingdata = $shortcoderes->bookingData;

                                    $productTimes_arr = isset($bookingdata->productTimes) ? $bookingdata->productTimes : '';



                                        if(count($productTimes_arr) > 1){ 

                                            $content .='<div class="ncm_product_time_select">';

                                                $content .='<label class="ncm-label">Pick Product Time</label>';

                                                $content .='<select name="pruduct_time" id="pruduct_time">';

                                                  foreach($productTimes_arr as $time){ 

                                                    if($time->default == 1 ){

                                                        $selected = "selected='selected'";

                                                    }else{

                                                        $selected = "";

                                                    } 

                                                    $content .='<option value="'.$time->id .'" '.$selected.' >'. $time->time .'</option>';

                                                  } 

                                                $content .='</select>';

                                            $content .='</div>';

                                        }

                                    }



                                $content .='<div class="ncm-col-md-6" id="">';    

                                $content .='<p class="ncm-date">';

                                $content .= '<label class="ncm-label">Start Date</label>';



                                    $content.= $ncm_controls->ncm_control(



                                        array(



                                            "type" => "text",



                                            "name" => "ncm_travel_date_start",



                                            "id" => "ncm_travel_date_start",



                                            "class" => "ncm_travel_date_start ncm_textbox ncm_datepicker",



                                            "value" => date('d-m-Y', strtotime(date('d-m-Y'). ' +1 day')),



                                            "placeholder" => "dd-mm-yyyy",



                                            "data-format" => "dd-mm-yyyy",



                                            "data-startDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 days" ) ),



                                            "data-endDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 years" ) ),



                                        )



                                    );

                                $content .='</p>'; 

                                $content .='</div>';    



                                $content .='<div class="ncm-col-md-6" id="">'; 

                                 $content .='<p class="ncm-date">';

                                 $content .= '<label class="ncm-label">End Date</label>';



                                    $content.= $ncm_controls->ncm_control(



                                        array(



                                            "type" => "text",



                                            "name" => "ncm_travel_date_end",



                                            "id" => "ncm_travel_date_end",



                                            "class" => "ncm_travel_date_end ncm_textbox ncm_datepicker",



                                            "value" => date('d-m-Y', strtotime(date('d-m-Y'). ' +2 day')),



                                            "placeholder" => "dd-mm-yyyy",



                                            "data-format" => "dd-mm-yyyy",



                                            "data-startDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 days" ) ),



                                            "data-endDate" => date("d-m-Y", strtotime( date("d-m-Y")." +1 years" ) ),



                                        )



                                    ); 

                                    $content .='</p>';

                                    $content .='</div>'; 



                                    $content .='<div class="ncm-col-md-12" id="">'; 

                                    $content .='<p class="ncm-date">';

                                    $loader= '<span class="ncm_chk_price_display_loader ncm_display_loader"><i class="ncm_fa-li ncm_fa ncm_fa-spinner ncm_fa-spin"></i></span>';

                                    $content.= '<button type="submit" name="chk_price" id="chk_price" class="single_add_to_cart_button '.$buttonsize.' alt chk_price btn btn-info" '.$shortcodestyle.'>Check Dates'.$loader.'</button>'; 

                                    $content .='</p>';  

                                    $content .='</div>'; 



                                $content .='</div>';    

                                $content .='</div>';

                            $content .='</div>';

                        $content .='</div>';



                        $content .='<div class="modalncm-footer">';

                            $content .='<button type="button" class="btn btn-default" data-dismiss="modalncm">Close</button>';

                        $content .='</div>';

                    $content .='</div>';

                $content .='</div>';

            $content .='</div>';



            //modalncm For display price check_my_price_now_popup 



            $content .='<div id="check_my_price_now_popup" class="modalncm fade" role="dialog">';

                $content .='<div class="modalncm-dialog">';

                    $content .='<div class="modalncm-content">';

                        $content .='<div class="modalncm-header">';

                            $content .='<button type="button" class="close" data-dismiss="modalncm">&times;</button>';



                            $content .='<h4 class="modalncm-title">Product Availability And Prices</h4>';



                        $content .='</div>';

                        $content .='<div class="modalncm-body check_my_price_now_content" >';

                            $content .='<div class="ncm_loader ncm_price_availability_loader" style="display:none;">';

                                $content .='<i class="ncm_fa ncm_fa-spinner ncm_fa-spin ncm_fa-4x ncm_prc_avl"></i>';

                            $content .='</div>';

                            $content .='<div class="ncm-row modalncm-container">';

                                $content .='<div class="ncm-col-md-12" id="check_my_price_now_content">'.$post->ncm_popup_content.'</div>';

                            $content .='</div>';

                        $content .='</div>';

                        $content .='<div class="modalncm-footer">';

                            $content .='<button type="button" class="btn btn-default" data-dismiss="modalncm">Close</button>';

                        $content .='</div>';

                    $content .='</div>';

                $content .='</div>';

            $content .='</div>';



            $content .= '</div>';

            $content .= '</div>';



            $content .='

<script type="text/javascript">

jQuery(document).ready(function($) {  

    

    var getHidVal = jQuery("#ncm_bookingCodes").val();

    var getHidFinal = getHidVal.split(":");

    //console.log(getHidFinal);



    jQuery("#pruduct_time").on("change", function (e){

        var selectTime =  jQuery(this).val();

        getHidFinal.pop();

        getHidFinal.push(selectTime);

        var inputVal = getHidFinal.join(":");

        //console.log(inputVal);

        jQuery("#ncm_bookingCodes").val(inputVal);

    });



});    

</script>';



            return $content;

        }





        function ncm_before_shortcode( $pagename ) {



            global $ncm_controls;



            $attr = '';



            if( $pagename == 'order' ) { $attr = ' style="display:none;"'; }



            $content = '<div class="ncm_main_content">';



            $content.= '<div class="ncm_main_container_loader">';



            $content.= $ncm_controls->ncm_control( array( "type"=>"hidden", "id"=>"ncm_page", "value"=>$pagename ) );







            $content.= '<div id="ncm_container_loader" '.$attr.'>';



            $content.= '<div class="ncm_container_loader"><i class="ncm_fa ncm_fa-spinner ncm_fa-spin ncm_fa-4x ncm_prc_avl"></i></div>';



            $content.= '</div>';



            return $content;



        }







        function ncm_after_shortcode() {



            $content = '</div>';



            $content.= '</div>';



            return $content;



        }







        function ncm_cart_html_func ( $atts = array(), $content = "" ) {

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();

            global $ncm_cart;



            $content.= $this->ncm_before_shortcode('cart');



            $content.= $ncm_cart->ncm_ajax_container();



            $content.= $this->ncm_after_shortcode();



            return $content;



        }







        function ncm_checkout_html_func( $atts = array(), $content = "" ) {

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();

    		global $ncm_payment_gateways,$ncm_post_id, $ncm_checkout;







            $content.= $this->ncm_before_shortcode('checkout');



            $content.= '<form action="" method="post" id="ncm_payment_form">';



            $content.= $ncm_checkout->ncm_checkout_main_func();



            $content.= '</form>';



            $content.= $this->ncm_after_shortcode();



            return $content;



        }







    	function ncm_order_html_func( $atts = array(), $content = "" ){

            //Narnoo_Commerce_Manager::ncm_front_shortcode_enqueue_scripts();

    		global $ncm, $ncm_cart, $ncm_order;



            $content.= $this->ncm_before_shortcode('order');



            $content.= $ncm_order->ncm_get_orders();



            $content.= $this->ncm_after_shortcode();



            return $content;



    	}







        function ncm_has_shortcode( $shortcode ) {



            global $post, $wp_query;



            // print_r( $wp_query->get_queried_object() );







            $return = false;



            if( !empty( $shortcode) ) {



                $post_content = isset($post->post_content) ? $post->post_content : '';



                if ( !empty($post_content) && has_shortcode( $post_content, $shortcode ) ) {



                    $return = true;



                } 



            }



            return $return;



        }



        



    }



    global $ncm_shortcode;



    $ncm_shortcode = new NCM_Shortcode();



}



function ncm_truncate($string, $limit, $break = ".", $pad = "...") {

    // return with no change if string is shorter than $limit    

    if (strlen($string) <= $limit)

        return $string;



    // is $break present between $limit and the end of the string?    

    if (false !== ($breakpoint = strpos($string, $break, $limit))) {

        if ($breakpoint < strlen($string) - 1) {

            $string = substr($string, 0, $breakpoint) . $pad;

        }

    }

    return $string;

}



?>