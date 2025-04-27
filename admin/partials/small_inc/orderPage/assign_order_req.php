<?php
function placeOrderToHTG($orderJson){
    $curl = curl_init();
    $url = get_option("htgmainurl");
    $username = get_option("htgkey");
    $password = get_option("htgsecret");
    $auth = base64_encode($username . ':' . $password);
    curl_setopt_array($curl, array(
        CURLOPT_URL => "{$url}/api/order/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "{$orderJson}",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic {$auth}",
            'Content-Type: application/json',
        ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    // $fp = fopen("result_order2.php", "w");
    // fwrite($fp, $response);
    // fclose($fp);
    return $response;
}
