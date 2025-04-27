<?php

require ABSPATH .  '/vendor/autoload.php';

use Automattic\WooCommerce\Client;


class UpdateAndUpload
{
    public function fetch()
    {
        $url = get_option('htgstockurl');
        $stock = get_option('htgstockkey');
        $code = get_option('htgstockcode');

        $curl = curl_init();
        // $url = get_option("htgmainurl")
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$url}/api/{$stock}?code={$code}&type=simple",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        // die($response);
        curl_close($curl);
        $fp = fopen("resultf.php", "w");
        fwrite($fp, $response);
        return $response;
    }

    public function queryBuilder()
    {
        
        // if (file_exists('resultf.php')) {
        //     $allData = json_decode(file_get_contents('resultf.php'), true);
        // } else {
        //     $allData = json_decode($this->fetch(), true);
        // }
        $allData = json_decode($this->fetch(), true);
        // var_dump($allData);
        $shouldBreak = 0;
        $breakat = count($allData['products']) + 2; // 10;
        $sleepAt = 500;
        $counter = 0;
        $arraylen = count($allData['products']);
        $x = 1;
        $tracker = 0;
        $firstArray = [];
        // var_dump($allData['products']);
        // die();
        // $productlist = "";
        foreach ($allData['products'] as $value) {
            // var_dump($value);
            if(!isset($value['barcode']) || empty($value['barcode'])){
                $sku = "'". $value['articleNumber'] . "'";
            } else {
                $sku = "'". $value['barcode']  . "'";
            }
            $firstArray[$sku] = [
                'id' => $sku,
                'availableStock' => $value['availableStock'],
                'regular_price' => $value['price'] * 1.7
            ];
            $productlist [] = $sku;  //" " . $value['barcode'] . "" . ",";
            if ($shouldBreak == $breakat || $counter == $sleepAt || $x == $arraylen) {
                $allList = implode(", ", $productlist);
                // echo $tracker . " ";
                // $tracker++;
                // if($tracker == 8){
                //     var_dump($productlist);
                // }
                // var_dump($allList);
                $this->selectData($allList, $firstArray);
                // var_dump($productlist);
                $productlist = [];
                $counter = 0;
            }
            $shouldBreak++;
            $counter++;
            $x++;
        }
        
    }

    public function selectData($array, $firstArray)
    {
        $productlist = $array;
        global $wpdb;
        $wpdb->show_errors();
        $sql = "SELECT remote_product_id, local_product_id FROM {$wpdb->prefix}htgproductlist WHERE remote_product_id in  ({$productlist})";
        // var_dump($sql);
        $results = json_decode(json_encode($wpdb->get_results($sql)), true);
        // var_dump($results);
        if (count($results) != 0) {
            foreach ($results as $p) {
                $sendToWoo['update'][] = [
                    'id' => $p['local_product_id'],
                    'regular_price' => $firstArray[$p['remote_product_id']]['regular_price'],
                    'stock_quantity' => $firstArray[$p['remote_product_id']]['availableStock']
                ];
            }
            $this->sendDataToWoocommerce($sendToWoo);
        }
    }

    public function fullupdateData($array, $firstArray)
    {
        $productlist = $array;
        global $wpdb;
        $wpdb->show_errors();
        $sql = "SELECT remote_product_id, local_product_id FROM {$wpdb->prefix}htgproductlist WHERE remote_product_id in  ({$productlist})";
        // var_dump($sql);
        $results = json_decode(json_encode($wpdb->get_results($sql)), true);
        // var_dump($results);
        if (count($results) != 0) {
            foreach ($results as $p) {
                $sendToWoo['update'][] = [
                    'id' => $p['local_product_id'],
                    'regular_price' => $firstArray[$p['remote_product_id']]['regular_price'],
                    'stock_quantity' => $firstArray[$p['remote_product_id']]['availableStock']
                ];
            }
            $this->sendDataToWoocommerce($sendToWoo);
        }
    }


    public function sendDataToWoocommerce($sendToWoo)
    {
        $siteUrl = get_site_url();
        $wooID = get_option('hgtwoocommercekey');
        $woosec = get_option('hgtwoocommercepassword');
        $woocommerce = new Client(
            // 'https://lpsbv.de/', // Your store URL
            "{$siteUrl}",
            "{$wooID}", // Your consumer key
            "{$woosec}", // Your consumer secret
            [
                'wp_api' => true, // Enable the WP REST API integration
                'version' => 'wc/v3' // WooCommerce WP REST API version
            ]
        );
        $result = ($woocommerce->post('products/batch', $sendToWoo));
        $array = json_decode(json_encode($result), true);
        $this->updateToSql($array);
    }

    /**
     * Saving data to the wordpress database
     * @param mixed $array 
     * @return void 
     */
    public function updateToSql($array)
    {
        global $wpdb;
        $time = 0;
        foreach ($array['update'] as $values) {
            $where = ['remote_product_id' => $values['sku']];
            $data = [
                'last_sync_time' => current_time('mysql'),
                'stock' => $values['stock_quantity'],
                'price' => $values['regular_price'],
            ]; // NULL value.
            // var_dump ($data);
            try {
                $wpdb->update($wpdb->prefix . 'htgproductlist', $data, $where);
            } catch (Exception $e) {
                //Do Nothing, continue the loop;
            }
            // $wpdb->update($wpdb->prefix . 'htgproductlist', $data, $where); 
            if ($time == 300) {
                sleep(2);
                $time = 0;
            }
            $time++;
        }
        $wpdb->flush();
    }
}
