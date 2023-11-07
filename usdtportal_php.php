<?php

class USDT_Portal_Service
{
    private $gateway_url = "https://usdtportal.com/api/";
    private $orderDetails = [];
    private $args = [];
    public $http_code = 0;
    private $configarray = [
        'email' => 'your_email_from_usdtportal', // [YOUR USDTPORTAL EMAIL] your_email@gmail.com
        'api_key' => 'apiKey_from_usdtportal', // [YOUR API KEY FROM USDTPORTAL] A0FL-GKLS-9K!i-Dktk-DKt$
        'callback_url_password' => 'your_callback_password_from_usdtportal', // [YOUR CALLBACK PASSWORD FROM USDTPORTAL] kf9iaj9JJF9rg78fahn389ungri8
    ];

    public function __construct($orderDetails) {
        $this->orderDetails = $orderDetails;

        $this->args = [
            'action' => 'new', // DON'T CHANGE
            "merchant" => [
                'email' => $this->configarray['email'],  // DON'T CHANGE
                'api_key' => $this->configarray['api_key'],  // DON'T CHANGE
            ],
            "customer" => [
                'user_email' => $this->params["clientdetails"]["email"], //[Your Customer Email] example@gmail.com
                'amount' => ($this->params["amount"] - $this->params["invtax"]), //[Amount user try to add ONLY INTIGER VALUE] 10.50
                'currency' => strtoupper($params['currency']) // [ONLY USD IS ACCEPTABLE] USD
            ],
            'order_id' => $this->params["invoiceid"], //[YOUR TRANSACTION ID INTERNALLY, CAN BE INREMENTAL IN YOUR SYSTEM 1,2,3,4...1049...]
            'redirect_paid' => $this->params['systemurl'] . 'settings/statement', // [URL TO REDIRECT CUSTOMER AFTER SUCCESSFULL PAYMENT]
            'redirect_canceled' => $this->params['systemurl'] . 'main', // [URL TO REDIRECT CUSTOMER AFTER HE CANCEL PAYMENT]
        ];
    }
   
    public function generate_link() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->gateway_url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->args));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type:application/x-www-form-urlencoded;charset=UTF-8',
        ]);
        $response = curl_exec($ch);
        $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return json_decode($response);
    }
}


function usdtportal_link()
{   $orderDetails = newOrder(); // LOAD ORDER DETAILS
    $client = new USDT_Portal_Service($orderDetails); // PASS ORDER DETAILS TO USDTPORTAL CLASS
    $server_response = $client->generate_link(); // USDTPORTAL JSON RESPONSE WITH URL TO THE PAYMENT THAT YOU REDIRECT CUSTOMER OR PUT LINK UNDER BUTTON TO CLICK PAY

    if($client->http_code === 403) {
        return '<p style="color:red;">Please whitelist your Server IP from <a style="color:#FF5555;" href="https://usdtportal.com/panel" target="_blank" style="color:red;">Merchant Panel</a></p>';
    }

    if($client->http_code !== 200) {
        // usdt portal server is offline
    }

    if (!$server_response->auth || $server_response->error) {
        // usdtportal returned an error 
        echo $server_response->message;
    }

    if (isset($server_response->message) AND strlen($server_response->message) > 1) {
        // $server_response->url - payment link
        return '<a class="btn btn-success pt-3 pb-3" style="width: 100%; background-color: green!important;" href="'.$server_response->url.'">Pay Now<br><span>'.$server_response->message.'</span>';
    } else {
        return '<a class="btn btn-success pt-3 pb-3" style="width: 100%; background-color: green!important;" href="'.$server_response->url.'">'.$lng_languag["invoicespaynow"].'</a>';
    }
}



function newOrder() {
    // function for preocessing new payment in your system and passing information about order like amount, email of user etc
}
?>
