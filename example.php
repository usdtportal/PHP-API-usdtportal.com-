<?php



require_once ($_SERVER['DOCUMENT_ROOT']."/LOCATION/TO/FILE/usdtportal.php");
$usdtportal = new USDT_Portal();
$usdtportal->set_access($payment["YOUR_EMAIL", "YOUR_API_KEY");
$usdtportal->set_order("USER_EMAIL", "AMOUNT IN FORMAT, 50.20", "INVOICE_ID_NUMERIC", "PAYMENT_SUCCESS_REDIRECT_URL", "PAYMENT_CANCELED_REDIRECT_URL");
$new_payment = $usdtportal->generate_link();

if ($usdtportal->http_code === 403) {
  display_error('Please whitelist your Server IP in USDT Portal Merchant Panel','danger');
  return false;
}

if($usdtportal->http_code !== 200) {
  display_error('USDT Portal server is down','danger');
  return false;
}

if (!$new_payment->auth || $new_payment->error) {
  display_error($new_payment->message,'danger');
  return false;
}

if (isset($new_payment->url)) {
  header("Location: $new_payment->url");
  return true;
} 
