<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(1);
date_default_timezone_set('Europe/Warsaw');


$GATEWAY = [
    'email' => 'your_email_from_usdtportal',
    'callback_url_password' => 'your_callback_password',
];


$rawBody = trim(file_get_contents("php://input"));
if (!$rawBody) {
    exit("OK!");
}

if (isset($_POST['test_callback'], $_POST['email'], $_POST['callback_url_password'])) {
    if ($GATEWAY['callback_url_password'] != $_POST['callback_url_password'] || $GATEWAY['email'] != $_POST['email']) {
        $data = [ 
            'is_success' => false,
            'message' => "Credentials no match. Make sure you setup Email, Api Key and Secret Callback Password in your DHRU website from Settings>Payment Gateways>USDT Portal - Auto Crypto Payments"
        ];
        exit(json_encode($data));
    } else {
        $ip = getServerIP();
        $data = [ 
            'is_success' => true,
            'message' => "Credentials match. Callback is correctly set. We found that your Server IP should be: $ip",
            'ip' => $ip
        ];
        exit(json_encode($data));
    }
}


if (!isset($_POST['order_id'], $_POST['transaction_id'], $_POST['amount_with_commission'], $_POST['fee'], $_POST['user_email'], $_POST['txn_hash'], $_POST['received_timestamp'], $_POST['email'], $_POST['callback_url_password'])) {
    exit("What are you doing here?");
}

if($GATEWAY['callback_url_password'] != $_POST['callback_url_password'] || $GATEWAY['email'] != $_POST['email'])
{
    $data = [ 
    'is_success' => false,
    'message' => "Credentials no match"
    ];
    exit(json_encode($data));
}



//incomming data you can use for your records
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


$orderDetails = getInvoiceDetails($order_id, $user_email); //make some records in your database for double verify incomming callback
if (!$orderDetails) {
    $data = [ 
        'is_success' => false,
        'message' => "Order not found"
    ];
    exit(json_encode($data));
}

if ($orderDetails['status'] != "Unpaid") {
    $data = [ 
        'is_success' => false,
        'message' => "Order found but status is ".$orderDetails['status']
    ];
    exit(json_encode($data));
}

$db_subtotal = $orderDetails['subtotal']; // getting amount from your database or can use sent by our script, more secure from your db
addPayment($order_id, $txn_hash, $db_subtotal, $fee); // some function to add credits for user

$data = [ 
    'is_success' => true,
    'message' => "Credits Added - $db_subtotal"
];
exit(json_encode($data));

function addPayment($order_id, $txn_hash, $amount, $fee) {
    // some function to add payment
}


function getInvoiceDetails($order_id, $user_email) {
    //getting order data from database, example below
    global $config;
    return mysqli_fetch_assoc(dquery("SELECT *
    FROM tbl_invoices
    WHERE id = '$order_id'
    AND userid = (SELECT id FROM tblUsers WHERE email = '$user_email') 
    LIMIT 1"));
}

function getServerIP() {
    $ch = curl_init('https://api.ipify.org?format=json');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($response);
    return isset($json->ip) ? $json->ip:'0';
}


?>
