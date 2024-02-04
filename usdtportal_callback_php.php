<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once ($_SERVER['DOCUMENT_ROOT']."/LOCATION/TO/FILE/usdtportal.php");

$cridentials = [
        "email" => "YOUR_EMAIL",
        "api_key" => "YOUR API KEY",
        "callback_url_password" => "YOUR_CALLBACK_SECRET_PASSWORD"
    ];

// SETUP TESTERS
if (isset($_POST['test_callback'], $_POST['email'], $_POST['callback_url_password'])) {
  if ($cridentials['api_secret_callback_password'] != $_POST['callback_url_password'] || $cridentials['api_email'] != $_POST['email']) {
      $data = [ 
          'is_success' => false,
          'message' => "Credentials no match. Make sure you setup Email, Api Key and Secret Callback Password in your DHRU website from Settings>Payment Gateways>USDT Portal - Auto Crypto Payments"
      ];
      exit(json_encode($data));
  } else {
      $ip = getServerIP();
      $ipv6 = getServerIPv6();
      $data = [ 
          'is_success' => true,
          'message' => "Credentials match. Callback is correctly set.<br>IPv4: $ip<br>IPv6: $ipv6",
          'ip' => $ip,
          'ipv6' => $ipv6
      ];
      exit(json_encode($data));
  }
}



// PAYMENT CALLBACK PART
if (!isset($_POST['order_id'], $_POST['transaction_id'], $_POST['amount_with_commission'], $_POST['fee'], $_POST['user_email'], $_POST['txn_hash'], $_POST['received_timestamp'], $_POST['email'], $_POST['callback_url_password'])) {
  exit("What are you doing here?");
}

if($cridentials['api_secret_callback_password'] != $_POST['callback_url_password'] || $cridentials['api_email'] != $_POST['email'])
{
    $data = [ 
    'is_success' => false,
    'message' => "Credentials no match"
    ];
    exit(json_encode($data));
}

$order_id = $_POST['order_id'];
$transaction_id = $_POST['transaction_id'];
$user_email = $_POST['user_email'];
$amount = $_POST['amount'];
$amount_with_commission = $_POST['amount_with_commission'];
$fee = $_POST['fee'];
$currency = $_POST['currency'];
$network = $_POST['network'];
$target_wallet = $_POST['target_wallet'];
$txn_hash = $_POST['txn_hash'];
$initiated_timestamp = $_POST['initiated_timestamp'];
$received_timestamp = $_POST['received_timestamp'];
$status = $_POST['status'];
$creation_ip = $_POST['creation_ip'];

//ADD SOME CHECK IN YOUR SYSTEM TO CONFIRM INVOICE IS NOT MARK AS PAID YET


$usdtportal = new USDT_Portal();
$usdtportal->set_access($cridentials['api_email'], $cridentials['api_key']);
if ($usdtportal->verify_payment($transaction_id)) {
    if (FUNCTION TO MARK AS PAID WITH RESULT TRUE IF DONE) {
      $data = [ 
          'is_success' => true,
          'message' => "Credits Added - $amount"
      ];
      exit(json_encode($data));
  } else {
      $data = [ 
          'is_success' => false,
          'message' => 'Marking as paid failed'
      ];
      exit(json_encode($data));
  }
} else {
  $data = [ 
    'is_success' => false,
    'message' => 'Payment not done by customer!'
  ];
  exit(json_encode($data));
}





function getServerIP() {
    $ch = curl_init('https://api.ipify.org?format=json');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($response);
    return isset($json->ip) ? $json->ip:'0';
}

function getServerIPv6() {
    $ch = curl_init('https://api64.ipify.org?format=json');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($response);
    return isset($json->ip) ? $json->ip:'0';
}
