<?php

class USDT_Portal
{
    private $gateway_url = "https://usdtportal.com/api/";
    private $args = [];
    public $http_code = 0;
	private $email;
	private $api_key;
	public $err;

	public function set_access($email, $api_key) {
		$this->email = $email;
		$this->api_key = $api_key;
	}

	public function set_order($userEmail, $amount, $order_id, $redirect_paid, $redirect_canceled) {
		$this->args = [
			'action' => 'new',
            "merchant" => [
                'email' => $this->email,  // DON'T CHANGE
                'api_key' => $this->api_key,  // DON'T CHANGE
            ],
            "customer" => [
                'user_email' => $userEmail, //[Your Customer Email] example@gmail.com
                'amount' => $amount, //[Amount user try to add ONLY INTIGER VALUE] 10.50
                'currency' => strtoupper("USD") // [ONLY USD IS ACCEPTABLE] USD
            ],
            'order_id' => $order_id, //[YOUR TRANSACTION ID INTERNALLY, CAN BE INREMENTAL IN YOUR SYSTEM 1,2,3,4...1049...]
            'redirect_paid' => $redirect_paid, // [URL TO REDIRECT CUSTOMER AFTER SUCCESSFULL PAYMENT]
            'redirect_canceled' => $redirect_canceled, // [URL TO REDIRECT CUSTOMER AFTER HE CANCEL PAYMENT]
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

	public function verify_payment($transaction_id) {
		$args = [
			"action" => "status",
			"merchant" => [
				"email" => $this->email,
				"api_key" => $this->api_key
			],
			"transaction_id" => $transaction_id,
		];
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://usdtportal.com/api/");
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($args));
		curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type:application/x-www-form-urlencoded;charset=UTF-8"]);
		$response = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		$transaction_details = json_decode($response);
		$this->err = $response;
		if (isset($transaction_details->transaction_status) && $transaction_details->transaction_status == "paid") {
			return true;
		} else {
			return false;
		}
	}


}
