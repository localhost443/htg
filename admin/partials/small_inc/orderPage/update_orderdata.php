<?php 
/**
 * After Getting the order, I will update the order in local database 
 * to use it later , for tracking and other info
 * 
 * version 1.0
 */
function update_orderdata_htg_local($response){
    global $wpdb;
    $order_update_time = $response['orderdate'];
    $pickup_delivery_date = $response['pickup_delivery_date'];
    if($response['pickup_delivery'] == "D"){
        $is_p = "Delivery";
    } else {
        $is_p = "Pick UP";
    }
    $status = $response['status'];
    $data = [ 
        'remote_order' => $response['id'],
        'remote_order_status' => $status,
        'order_update_time' => $order_update_time,
        'pickup_delivery_date' => $pickup_delivery_date,
        'remote_order_info' => json_encode($response),
        'PD' => $is_p
    ]; // NULL value.
    $format = [ NULL ];  // Ignored when corresponding data is NULL, set to NULL for readability.
    $where = [ 'local_order' => $_GET['orderid'] ];
    // var_dump($_GET['orderid']); // NULL value in WHERE clause.
    $wpdb->update( $wpdb->prefix . 'htgorderlist', $data, $where ); // Also works in this case.
    // echo "<br> <br> <br>";
    // $wpdb->show_errors();
    // var_dump($data);
    $wpdb->flush();
    $url = get_admin_url() . "/admin.php?page=hgt-orders&orderid=" . $_GET['orderid'] ;
    wp_redirect("$url");
}   