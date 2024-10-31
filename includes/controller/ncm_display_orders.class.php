<?php

/*
* This function manage order status
*/

if( !class_exists ( 'NCM_Display_Orders' ) ) {







class NCM_Display_Orders extends WP_List_Table {



    



    function __construct(){







    }







    function ncm_disp_orders_actions() {



        global $ncm_payment_gateways, $ncm, $ncm_email;



        $ncm_order_id = isset($_REQUEST['ncm_order']) ? $_REQUEST['ncm_order'] : '';



        $ncm_action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'edit';



        $ncm_order_action = isset($_REQUEST['ncm_order_action']) ? $_REQUEST['ncm_order_action'] : '';







        if( !empty( $ncm_order_action ) ) {



            if( $ncm_order_action == 'send_order_details_admin' ) {



                $ncm_email->ncm_notifiction( 'new_order', $ncm_order_id );



            } else {



                $ncm_email->ncm_notifiction( 'customer_completed_order', $ncm_order_id );



            }



        }



        



        $ncm_status_action = (isset($_REQUEST['ncm_order_status']) && !empty($_REQUEST['ncm_order_status'])) ? $_REQUEST['ncm_order_status'] : $ncm_action;



        $manage_page_status = array( 'trash', 'completed', 'processing', 'on-hold' );



        $ncm_all_status = array_merge($manage_page_status ,array_keys( $ncm_payment_gateways->ncm_payment_status ) );



        



        if( in_array( $ncm_status_action, $ncm_all_status ) && empty( $ncm_order_action ) ) {



            $ncm_payment_gateways->ncm_update_orderstatus( $ncm_order_id, $ncm_status_action );







            // send email notification.



            switch ($ncm_status_action) {



                case 'completed':



                case 'ncm-completed':



                    do_action( "ncm_email_notification", 'new_order', $ncm_order_id );



                    break;







                case 'ncm-cancelled':



                    do_action( "ncm_email_notification", 'cancelled_order', $ncm_order_id );



                    break;







                case 'ncm-failed':



                    do_action( "ncm_email_notification", 'failed_order', $ncm_order_id );



                    break;







                case 'on-hold':



                case 'ncm-on-hold':



                    do_action( "ncm_email_notification", 'customer_on_hold_order', $ncm_order_id );



                    break;



                



                case 'processing':



                case 'ncm-processing':



                    do_action( "ncm_email_notification", 'customer_processing_order', $ncm_order_id );



                    break;







                case 'ncm-refunded':



                    $ncm_payment_gateways->ncm_refund( '', $ncm_order_id );



                    do_action( "ncm_email_notification", 'customer_refunded_order', $ncm_order_id );



                    break;



            }



        } else if ( $ncm_action == 'per_delete' ) {



            wp_delete_post( $ncm_order_id );



        }







        if( in_array( $ncm_action, array( 'edit' ) ) ) {



            $this->ncm_disp_order_details( $ncm_order_id );



        } else {



            $this->ncm_disp_orders();



        }



    }







    function ncm_disp_order_details( $ncm_order_id ) {



        global $ncm_order, $ncm_payment_gateways, $ncm_settings;



        $order_data = $ncm_order->ncm_get_order_data( $ncm_order_id );



        $order_data['order_status'] = get_post_status( $ncm_order_id );



        $order_data['narnoo_order_id'] = get_post_meta( $ncm_order_id, 'ncm_narnoo_order_id', true );



        $payment_gateways_name = $ncm_payment_gateways->get_payment_gateways();



        $ncm_order_status = $ncm_payment_gateways->ncm_payment_status; 







        extract($order_data);



        echo '<div class="wrap">';



        echo '<div id="icon-users" class="icon32"><br/></div>';



        echo '<h2>'.__('View Orders', NCM_txt_domain).'</h2>';



        echo '<form id="orders-filter" method="post">';







        if( !empty( $ncm_order_id ) && file_exists( NCM_VIEWS_DIR . 'ncm_display_order.php' ) ) {



            include( NCM_VIEWS_DIR . 'ncm_display_order.php' );



        } else {



            _e('Sorry!! Something went wrong.', NCM_txt_domain);



        }







        echo '<input type="hidden" name="page" value="'.$_REQUEST['page'].'" />';    



        echo '</form>';



        echo '</div>';



    }







    function ncm_disp_orders() {







        parent::__construct( 



            array(



                'singular'=> 'wp_list_text_link', //Singular label



                'plural' => 'wp_list_test_links', //plural label, also this well be one of the table css class



                'ajax'   => false //We won't support Ajax for this table



            ) 



        );      



        $this->ncm_prepare_items();



        



        echo '<div class="wrap">';



        echo '<div id="icon-users" class="icon32"><br/></div>';



        echo '<h2>Your Orders</h2>';



            $this->views();



        echo '<form id="orders-filter" method="post">';



            $this->extra_tablenav( 'top' );



        echo '<input type="hidden" name="page" value="'.$_REQUEST['page'].'" />';



            $this->display();



        echo '</form>';



        echo '</div>';   



    }







    // Setup bulk actions



    function get_bulk_actions() {



        $actions = array(



            'trash' => __('Move to Trash', NCM_txt_domain),



            'processing' => __('Mark processing', NCM_txt_domain),



            'on-hold' => __('Mark on-hold', NCM_txt_domain),



            'completed' => __('Mark complete', NCM_txt_domain),



        );



        return $actions;



    }







    function get_views() { 



        global $ncm;



        $page = $ncm->ncm_commerce;







        $all        = __('All', NCM_txt_domain);



        $processing = __('Processing', NCM_txt_domain);



        $on_hold    = __('On hold', NCM_txt_domain);



        $completed  = __('Completed', NCM_txt_domain);



        $cancelled  = __('Cancelled', NCM_txt_domain);



        $trash      = __('Trash', NCM_txt_domain);







        $all_link        = admin_url( 'admin.php?page='.$page );



        $processing_link = admin_url( 'admin.php?page='.$page.'&ncm_status=ncm-processing' );



        $on_hold_link    = admin_url( 'admin.php?page='.$page.'&ncm_status=ncm-on-hold' );



        $completed_link  = admin_url( 'admin.php?page='.$page.'&ncm_status=ncm-completed' );



        $cancelled_link  = admin_url( 'admin.php?page='.$page.'&ncm_status=ncm-cancelled' );



        $trash_link      = admin_url( 'admin.php?page='.$page.'&ncm_status=ncm-trash' );







        $status_links = array(



            "all"        => "<a href='".$all_link."'>".$all."</a>",



            "processing" => "<a href='".$processing_link."'>".$processing."</a>",



            "on_hold"    => "<a href='".$on_hold_link."'>".$on_hold."</a>",



            "completed"  => "<a href='".$completed_link."'>".$completed."</a>",



            "cancelled"  => "<a href='".$cancelled_link."'>".$cancelled."</a>",



            "trash"      => "<a href='".$trash_link."'>".$trash."</a>",



        );



        return $status_links;



    }







    // Setup columns



    function get_columns() {



        $columns = array(



            'cb'            => '<input type="checkbox" />',



            'order'         => __('Order', NCM_txt_domain),



            'order_status'  => __('Order Status', NCM_txt_domain),



            'date'          => __('Date', NCM_txt_domain),



            'total'         => __('Total', NCM_txt_domain),



            'action'        => __('Action', NCM_txt_domain),



        );



        return $columns;



    }







    // Setup default column



    function column_default( $item, $column_name ) {



        switch( $column_name ) {



            case 'order':



            case 'order_status':



            case 'date':



            case 'total':



            case 'action':



                return $item[ $column_name ];



            default:



                return print_r( $item, true ) ;



        }



    }







    // Setup sortable column



    function get_sortable_columns() {



        $sortable_columns = array(



            //'order_status'  => array('post_status',false),



            'date' => array('post_date',false),



        );



        return $sortable_columns;



    }







    // Displaying checkboxes!



    function column_cb($order) {



        return sprintf(



            '<input type="checkbox" name="%1$s" id="%2$s" value="%2$d" />',



            //$this->_args['singular'],



            'ncm_order[]',



            $order['order_id'] . '_status',



            $order['order_id']



        );



    }







    // Displaying actions



    function column_order( $order ) {







        $title = '<strong>' . $order['order'] . '</strong>';



        $edit = __('Edit', NCM_txt_domain);



        $Trash = __('Trash', NCM_txt_domain);

 

        $per_delete_link = __('Delete Permanently', NCM_txt_domain);





        $actions = [



            'edit' => '<a href="'.$order['edit_link'].'">'.$edit.'</a>',



            'delete' => ($order['post_status'] == 'ncm-trash') ? '<a href="'.$order['per_delete_link'].'">'.$per_delete_link.'</a>' : '<a href="'.$order['delete_link'].'">'.$Trash.'</a>'



        ];







        return $title . $this->row_actions( $actions );



    }







    function ncm_prepare_items() {



        global $wpdb, $ncm, $ncm_order, $ncm_settings, $ncm_payment_gateways;







        $order_data = array();



        $order_status_all = $ncm_payment_gateways->ncm_payment_status;







        $qs_data = $_REQUEST;



        $orderby = ( isset($qs_data['orderby']) && !empty($qs_data['orderby']) ) ? $qs_data['orderby'] : 'date';



        $order = ( isset($qs_data['order']) && !empty($qs_data['order']) ) ? $qs_data['order'] : 'desc';



        $order_status = ( isset($qs_data['ncm_status']) && !empty($qs_data['ncm_status']) ) ? $qs_data['ncm_status'] : array_keys($order_status_all);







        $args = array(



            'posts_per_page'   => -1,



            'orderby'          => $orderby,



            'order'            => $order,



            'post_status'      => $order_status,



            'post_type'        => 'ncm_order',



        );







        $posts = get_posts( $args ); 







        foreach( $posts as $order ) {



            $order_id = $order->ID;



            $order_title = $order->post_title;



            $post_status = $order->post_status;







            $bookind_data = $ncm_order->ncm_get_order_booking_data( $order_id );



            $firstname = isset( $bookind_data['First Name'] ) ? $bookind_data['First Name'].' ' : '';



            $lastname = isset( $bookind_data['Last Name'] ) ? $bookind_data['Last Name'] : '';



            $email = isset( $bookind_data['Email'] ) ? $bookind_data['Email'] : '';



            $total = get_post_meta( $order_id, 'ncm_total', true );







            $action_attr = array(



                'page' => $ncm->ncm_commerce,



                'ncm_order' => $order_id,



            );







            $edit_attr = $action_attr;



            $edit_attr['action'] = 'edit';







            $delete_attr = $action_attr;



            $delete_attr['action'] = 'trash';





            $per_delete_attr = $action_attr;



            $per_delete_attr['action'] = 'per_delete';







            $per_delete_link = '?'.http_build_query($per_delete_attr);



            $delete_link = '?'.http_build_query($delete_attr);



            $edit_link   = '?'.http_build_query($edit_attr);



            $pre_edit    = '<a href="'.$edit_link.'">';



            $post_edit   = '</a>';







            $order_content = $pre_edit.__("Order #", NCM_txt_domain).$order_id.$post_edit;



            $order_content.= __(" by ", NCM_txt_domain);



            $order_content.= $pre_edit.$firstname.$lastname."<br/>".$email.$post_edit;







            $action = '';



            if( in_array( $post_status, array( 'ncm-on-hold' ) ) ) {



                $processing_attr = $action_attr;



                $processing_attr['action'] = 'processing';



                $processing_link = '?'.http_build_query($processing_attr);



                $action.= '<a class="button ncm_order_view" href="'.$processing_link.'"><i class="ncm_fa ncm_fa-ellipsis-h"></i></a>';



            }



            if( in_array( $post_status, array( 'ncm-processing', 'ncm-on-hold' ) ) ) {



                $complete_attr = $action_attr;



                $complete_attr['action'] = 'completed';



                $complete_link = '?'.http_build_query($complete_attr);



                $action.= '<a class="button ncm_order_view" href="'.$complete_link.'"><i class="ncm_fa ncm_fa-check"></i></a>';



            }



            $action.= '<a class="button" href="'.$edit_link.'"><i class="ncm_fa ncm_fa-eye"></i></a>';







            $order_data[] = array(



                'order_id'      => $order_id,



                'order'         => $order_content,



                'post_status'   => $post_status,



                'order_status'  => isset( $order_status_all[$post_status] ) ? $order_status_all[$post_status] : '',



                'date'          => date( 'F d, Y', strtotime($order->post_date)),



                'total'         => $ncm_settings->ncm_display_price( $total ),



                'edit_link'     => $edit_link,



                'delete_link'   => $delete_link,



                'per_delete_link' => $per_delete_link,



                'action'        => $action,



            );



        }







        $columns = $this->get_columns();



        $hidden = array();



        $sortable = $this->get_sortable_columns();







       //$order_data = $this->table_data();







        //usort( $order_data, array( &$this, 'sort_data' ) );



        $perPage = 10;



        $currentPage = $this->get_pagenum();



        $totalItems = count($order_data);



        $this->set_pagination_args( array(



            'total_items' => $totalItems,



            'per_page'    => $perPage



        ) );



        $order_data = array_slice($order_data,(($currentPage-1)*$perPage),$perPage);







        $this->_column_headers = array($columns, $hidden, $sortable);



        $this->items = $order_data;



    }



}







global $ncm_display_orders;



$ncm_display_orders = new NCM_Display_Orders();







}



?>