<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.localhost443.com
 * @since      1.0.0
 *
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/admin/partials
 * 
 * 
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
if (isset($_GET['orderid'])) {
    global $wpdb;
    $sql = "SELECT * FROM " . $wpdb->prefix . "htgorderlist WHERE local_order='" . $_GET['orderid'] . "'";
    // $wpdb->show_errors();
    $htgProducts = $wpdb->get_results($sql);
    $htgProducts = json_decode(json_encode($htgProducts), true);
    $product_list = json_decode(($htgProducts[0]['order_info']), true);
    
    if ($product_list) {
        require_once("small_inc/orderPage/order_info.php");
        require_once('small_inc/orderPage/assign_order.php'); 
    } else {
        echo "ERROR! Product ID is incorrect or item was Deleted";
    }
} else {
    /**
     * Getting The order List 
     */
    require_once('small_inc/orderPage/order_list.php');
}

?>