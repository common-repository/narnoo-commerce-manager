<?php
/*
 * Removing Plugin data using uninstall.php
 * the below function clears the database table on uninstall
 * only loads this file when uninstalling a plugin.
 */

/*
 * exit uninstall if not called by WP
 */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

/*
 * Making WPDB as global
 * to access database information.
 */
global $wpdb;

/*******************************************************************************
*
*  REMOVE TABLES AND ALL DATA FROM THE DATABASE
*  Table names are stored in the option 'narnoo_ecommerce_db_tables' as JSON.
*  We have to return this information and process it as an array. Once done
*  we can loop through the array and delete the tables. We also have to delete
*  the options after this process is completed
*
*******************************************************************************/
// table names
$tbl_tax_rate_location = $wpdb->prefix . "ncm_tax_rate_location";
$tbl_tax_rates = $wpdb->prefix . "ncm_tax_rates";
$tbl_order_item = $wpdb->prefix . "ncm_order_item";
$ncm_order_booking = $wpdb->prefix . "ncm_order_booking";
$tbl_order_passenger = $wpdb->prefix . "ncm_order_passenger";
$tbl_options = $wpdb->options;
$tbl_post = $wpdb->posts;
$tbl_postmeta = $wpdb->postmeta;


$_nooTables = get_option('narnoo_ecommerce_db_tables');
if($_nooTables){
  $nooTables = json_decode($_nooTables);
  foreach ($nooTables as $tbl) {
    $wpdb->query( "DROP TABLE IF EXISTS ".$tbl );
  }
}

$wpdb->query( "DROP TABLE IF EXISTS `".$tbl_tax_rate_location."`" );
$wpdb->query( "DROP TABLE IF EXISTS `".$tbl_tax_rates."`" );

/*  below table, post and post meta queries is related to order so not remove */

$wpdb->query( "DROP TABLE IF EXISTS `".$tbl_order_passenger."`" );

$wpdb->query( "DROP TABLE IF EXISTS `".$ncm_order_booking."`" );

$wpdb->query( "DROP TABLE IF EXISTS `".$tbl_order_item."`" );

$wpdb->query( "DELETE FROM `".$tbl_post."` WHERE `post_type`='ncm_order' ");

$wpdb->query( "DELETE FROM `".$tbl_postmeta."` WHERE `meta_key` LIKE 'ncm_%' ");



$wpdb->query( "DELETE FROM `".$tbl_options."` WHERE option_name LIKE '%ncm_%' " );

delete_option("ncm_version");
delete_option("narnoo_ecommerce_db_tables");
