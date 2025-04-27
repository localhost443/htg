<?php


require ABSPATH .  '/vendor/autoload.php';

use Automattic\WooCommerce\Client;


// var_dump($woocommerce);


class FetchAndUpload
{


    public function fetch()
    {

        $url = get_option('htgstockurl');
        $stock = get_option('htgstockkey');
        $code = get_option('htgstockcode');
        $curl = curl_init();
        // $url = get_option("htgmainurl")
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$url}/api/{$stock}?code={$code}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $fp = fopen("f.php", "w");
        fwrite($fp, $response);
        return $response;
    }

    public function queryBuilder()
    {
        $data = 281;
        // $woocommerce = new Client(
        //     'http://localhost/htg/', // Your store URL
        //     'ck_01095f6cb6a4eab12f1a3f31f5a10870ce4098c3', // Your consumer key
        //     'cs_237d7ee4010267d160d52a0577d371979be9f067', // Your consumer secret
        //     [
        //         'wp_api' => true, // Enable the WP REST API integration
        //         'version' => 'wc/v3' // WooCommerce WP REST API version
        //     ]
        // );

        $WoocommerceProducts = ['create' => []];
        // if (file_exists('f.php')) {
        //     $allData = file_get_contents('f.php');
        // } else {
        //     $allData = json_decode($this->fetch(), true);
        // }
        $allData = json_decode($this->fetch(), true);
        $allData = json_decode(file_get_contents('f.php'), true);



        $arrays =  $allData['products'];
        // $backupNumber = $number ; //number;
        // var_dump($allData['products']);
        $breakat = count($allData['products']) + 2;
        $sleepAt = 20;
        $counterData = 0;
        foreach ($arrays as $p) {
            if(!isset($p['barcode']) || empty($p['barcode'])){
                $sku = $p['articleNumber'];
            } else {
                $sku = $p['barcode'];
            }
            $breakat--;
            // var_dump($WoocommerceProducts['create']);
            $WoocommerceProducts['create'][] = [
                'name' => $p['productDescription'],
                'type' => 'simple',
                'regular_price' => $p['price'] * 1.7,
                'status' => 'publish',
                'description' => $p['productExtendedDescription'],
                'short_description' => $p['productShortDescription'],
                'sku' => $sku,
                'manage_stock' => true,
                'stock_quantity' => $p['availableStock'],
                'weight' => $p['width'],
                'dimensions' => [
                    'length' => $p['length'],
                    'width' => $p['width'],
                    'height' => $p['height'],
                ],
                'categories' => [
                    [
                        'id' => 2018
                    ]
                ],
                'reviews_allowed' => true,
                'images' => [
                    // [
                    //     'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg'
                    // ],
                    // [
                    //     'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg'
                    // ],
                    [
                        'id' => '91299',
                    ]
                ]
            ];
            $productAtc[$p['barcode']] = $p['articleNumber'];
            
            $counterData++;
            if ($counterData == $sleepAt || $breakat == 0) {
                // var_dump($WoocommerceProducts, " ", $productAtc);
                try {
                    $this->uploadProducts($WoocommerceProducts, $productAtc);
                } catch (Exception $e) {
                    
                }
                $WoocommerceProducts['create'] = [];
                $productAtc = [];

                sleep(5);
                // flush();
                $counterData = 0;
            }
            if ($breakat == 0) {
                break;
            }
        }
    }


    public function SaveToSQL(array $created, $sku)
    {
        $shouldIExecute = false;
        global $wpdb;
        $table = $wpdb->prefix . 'htgproductlist';
        $shouldIExecute = false;
        $sql = "
        INSERT INTO {$table}(remote_product_id, remote_product_article, local_product_id, last_sync_time , status, stock, price, currency, product_url)
         VALUES 
        ";
        foreach ($created as $value) {
            if(!isset($sku[$value['sku']]) || empty($sku[$value['sku']])){
                $sku_d = $value['sku'];
            } else {
                $sku_d = $sku[$value['sku']];
            }
            if (!$value['id'] == 0) {
                $sql .= " 
                ('{$value['sku']}', 
                '".$sku_d."' ,
                '{$value['id']}', 
                '".current_time('mysql')."', 
                'published', 
                '{$value['stock_quantity']}', 
                '{$value['regular_price']}', 
                'EUR', 
                '{$value['parmlink']}'),";
                $shouldIExecute = true;
            }
        }
        if ($shouldIExecute == true) {
            $sql = substr($sql, 0, -1);
            $wpdb->show_errors();
            $wpdb->query($sql);
            $wpdb->flush();
        }
        // echo $sql;
        // $sql = substr($sql, 0, -1);
        // $wpdb->show_errors();
        // $wpdb->query($sql);
        // $wpdb->flush();
        return true;
    }

    public function uploadProducts($Products, $sku)
    {
        $siteUrl = get_site_url();
        $wooID = get_option('hgtwoocommercekey');
        $woosec= get_option('hgtwoocommercepassword');
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
        $result = ($woocommerce->post('products/batch', $Products));
        $array = json_decode(json_encode($result), true);

        try {
            $this->SaveToSQL($array['create'], $sku);
        } catch (Exception $e) {
        }


        // echo $amount;
        // $sqlData = [];
        // foreach ($array['create'] as $value) {
        //     if($value['id'] == 0) {
        //         echo 'Some Conflicts Found ';
        //     } else {
        //         echo 'All data has been saved ';
        //     }
        // }
        // var_dump($values);
    }

    public function generate()
    {
        // 1 Get the full array 


        //loop array , get 100 products
        //ready it for woocommerce
        //upload the products on woocommerce
        //ready query to save data on local table
        //save the data
        //sleed 5 seconds
        //Go to the next 100 products

    }
}
// $data = new FetchAndUpload();
// $data->queryBuilder();
// $data->test();
