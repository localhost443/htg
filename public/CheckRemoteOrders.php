<?php
class CheckRemoteOrders
{
    /**
     * The Current URL to put your request
     * @var string
     */
    public $orderUrl;

    /**
     * Get the username 
     * @var string
     */
    public $username;

    /**
     * The password 
     * @var string
     */
    public $password;

    /**
     * Base 64 Converted username and password
     * @var string
     */
    public $auth;

    /**
     * Construct the class
     * @param string $orderUrl 
     * @param string $username 
     * @param string $password 
     * @return void 
     */
    public function __construct(
        $orderUrl = 'https://htgapi-test.azurewebsites.net/api/order',
        $username = 'u_lovelyperfume',
        $password = 'iRNlmTOFegkBtzYwMbF3'
    ) {
        $this->orderUrl = $orderUrl;
        $this->username = $username;
        $this->password = $password;
        $this->auth = base64_encode($this->username . ':' . $this->password);
        var_dump($this->auth);
    }

    /**
     * Get all the orders as array
     * @return (string|bool)[]|(string|false)[]|void 
     */
    public function getAllOrders()
    {
        return $this->requester($this->orderUrl);
    }

    /**
     * Getting a single order by ID
     * @param mixed $orderID 
     * @return void 
     */
    public function getSingleOrder($orderID)
    {
        $mainurl = $this->orderUrl . '/' . $orderID;
        return $this->requester($mainurl);
    }

    public function getOrderByReference($ref)
    {
        $mainurl = $this->orderUrl . '/?' . 'reference=' . $ref;
        return $this->requester($mainurl);
    }

    public function getAllParcelInfo() 
    {
        $mainurl = $this->orderUrl . '/?' . 'parcel=true';
        return $this->requester($mainurl);
    }

    public function getParcelinfoByOrder($orderid)
    {
        $mainurl = $this->orderUrl . '/' . $orderid . '/?' . 'parcel=true';
        return $this->requester($mainurl);
    }



    private function requester($orderURL)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $orderURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $headers = array();
        $headers[] = 'Authorization: Basic ' . $this->auth;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $err =  'Error:' . curl_error($ch);
        }
        curl_close($ch);
        if (isset($result)) return ['result' => $result, 'status' => true];
        if (isset($err)) return ['result' => $err, 'status' => false];
    }
}

$data = new CheckRemoteOrders();
// var_dump($data->getAllOrders());
var_dump($data->getSingleOrder(15508));
// var_dump($data->getOrderByReference('1882102957240349'));
// var_dump($data->getAllParcelInfo());
var_dump($data->getAllParcelInfo(15508));
