<?php 
function HTGWooCommerceOrderID($id) {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT remote_order FROM {$wpdb->prefix}htgorderlist WHERE local_order = $id");
    $wpdb->show_errors();
    $l = json_decode(json_encode($results), true);
    // echo count($l);
    $wpdb->flush();
    if(count($l) != 0) {
        trackHtgOrderById($l[0]['remote_order']);
    }
}
?>

<?php
function trackHtgOrderById($id)
{
    global $wpdb;
    $url = get_option("htgmainurl");
    $username = get_option("htgkey");
    $password = get_option("htgsecret");
    $auth = base64_encode($username . ':' . $password);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "{$url}/api/order/{$id}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic {$auth}",
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response, true);
    $status = $response['Orders'][0]['status'];
    $data = [
        'remote_order_status' => $status
    ]; // NULL value.
    $format = [NULL];  // Ignored when corresponding data is NULL, set to NULL for readability.
    $where = ['local_order' => $_GET['orderid']];
    $wpdb->update($wpdb->prefix . 'htgorderlist', $data, $where); // Also works in 
    $wpdb->flush();
?>
    <div class="pb-2">View Full Order: <a href="<?php echo get_admin_url() . "admin.php?page=hgt-orders&orderid=" . $_GET['HTGWooCommerceOrderID'] ?>">Here</a></div>
    <div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Name</span>
            <input type="text" class="form-control" disabled value="<?php echo $response['Orders'][0]['ShippingAddress']['name'] ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Order Number</span>
            <input type="text" class="form-control" disabled value="<?php echo $response['Orders'][0]['id'] ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Order Status</span>
            <input type="text" class="form-control" disabled value="<?php echo $response['Orders'][0]['status'] ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Order Email</span>
            <input type="text" class="form-control" disabled value="<?php echo $response['Orders'][0]['email'] ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Delivery Method</span>
            <input type="text" class="form-control" disabled value="<?php 
            $r = $response['Orders'][0]['pickup_delivery'] == "D" ?  "Delivery" : "Pickup"; echo $r; ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Shipping Company</span>
            <input type="text" class="form-control" disabled value="<?php echo $response['Orders'][0]['ShippingAddress']['shipping_company'] ?? "Not Available" ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Shipping Company</span>
            <input type="text" class="form-control" disabled value="<?php echo $response['Orders'][0]['ShippingAddress']['street'] ?? "Not Available" ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Phone Number</span>
            <input type="text" class="form-control" disabled value="<?php echo $response['Orders'][0]['ShippingAddress']['phonenumber'] ?? "Not Available" ?>">
        </div>
    </div>
<?php
}
?>