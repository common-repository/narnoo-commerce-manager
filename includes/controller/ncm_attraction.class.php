<?php

/*
* Manage all attraction for this class
*/

if( !class_exists ( 'NCM_Attraction' ) ) {



    class NCM_Attraction {

    

        function __construct(){

        }



        public function ncm_get_attraction ( $id = '' ) {

            $attraction = array();

            $args = array(

                'posts_per_page'   => -1,

                'offset'           => 0,

                'category'         => '',

                'category_name'    => '',

                'orderby'          => 'date',

                'order'            => 'DESC',

                'include'          => '',

                'exclude'          => '',

                'meta_key'         => '',

                'meta_value'       => '',

                'post_type'        => 'narnoo_attraction',

                'post_mime_type'   => '',

                'post_parent'      => '',

                'author'            => '',

                'author_name'      => '',

                'post_status'      => 'publish',

                'suppress_filters' => true 

            );

            $posts = get_posts( $args );

            foreach( $posts as $post ) {

                $attraction[$post->ID] = $post->post_title;

            }

            return $attraction;

        }

    }

    global $ncm_attraction;

    $ncm_attraction = new NCM_Attraction();

}

?>