ALL TESTS CAN BE DONE AT https://developer.usdtportal.com/, remember to change url in all reqests during test to https://developer.usdtportal.com/api, after test change it back to https://usdtportal.com/api/ 


<!-- Getting Payment Link: -->
<!-- ############## ERROR REPLY ##################### -->

<!-- EMAIL OR API_KEY IS EMPTY OR INVALID FORMAT-->
{"auth":false,"error":true,"message":"Info for Website Owner: API Error - Missing\/Invalid \"email\" or \"api_key\" params"}

<!-- YOUR SERVER IP IS NOT WHITELISTED ON OUR SERVER -->
{"auth":true,"error":true,"message":"Info for Website Owner: API Error - Your Server IP not match whitelisted IP in the Profile Settings on the USDT Portal"}

<!-- YOU NOT SET WEBSITE STATUS AS ENABLED IN THE USDTPORTAL PROFILE SETTINGS -->
{"auth":true,"error":true,"message":"Info for Website Owner: Payment page disabled by seller"}

<!-- MISSING CUSTOMER PARAMS IN THE REQUEST -->
{"auth":true,"error":true,"message":"Info for Website Owner: API Error - Missing "Customer" params"}

<!-- ONE OF PARAMS IS FROM CUSTOMER SECTION IS EMPTY OR INVALID FORMAT -->
{"auth":true,"error":true,"message":"Info for Website Owner: API Error - Missing/Invalid "user_email", "amount", "network", "order_id", "redirect_paid" or "redirect_canceled" params"}

<!-- YOUR USDTPORTAL BALANCE IS OUT, NEED RECHARGE BALANCE -->
{"auth":true,"error":true,"message":"Info for Website Owner: Your account is our of balance to cover transaction fee. Please visit usdtportal.com and add balance from Profile > Add Balance"}

<!-- YOUR USDTPORTAL SUBSCRIPTION EXPIRED, NEED RENEW IT -->
{"auth":true,"error":true,"message":"Info for Website Owner: USDT Portal Subscription Expired. Please renew your subscription."}

<!-- YOU NEED TO SETUP AT LEAST ONE CRYPTO WALLET IN USDTPORTAL PROFILE SETTINGS -->
{"auth":true,"error":true,"message":"Info for Website Owner: No crypto wallet set by seller on the USDT Portal. Please login your profile and set crypto wallets"}

<!-- Getting error message: -->
<?php
if ($json->error) {
    echo $json->message;
}
?>

<!-- ############## SUCCESS REPLY ##################### -->
{"auth":true,"error":false,"url":"https://usdtportal.com/payment/network.php?id=50_654bbc7a7cc21653bbc7a7cc2b65", "transaction_id":"50_654bbc7a7cc21653bbc7a7cc2b65"}






<!-- ###################################################################### -->
<!-- ###################################################################### -->
<!-- ###################################################################### -->



<!-- Getting Payment Status: -->
<!-- ############## ERROR REPLY ##################### -->

<!-- EMAIL OR API_KEY IS EMPTY OR INVALID FORMAT-->
{"auth":false,"error":true,"message":"Info for Website Owner: API Error - Missing\/Invalid \"email\" or \"api_key\" params"}

<!-- YOUR SERVER IP IS NOT WHITELISTED ON OUR SERVER -->
{"auth":true,"error":true,"message":"Info for Website Owner: API Error - Your Server IP not match whitelisted IP in the Profile Settings on the USDT Portal"}

<!-- YOUR TRANSACTION ID IS INVALID FORMAT/LENGHT -->
{"auth":true,"error":true,"message":"Missing/Invalid "transaction_id" param"}

<!-- YOU TRY TO CHECK OLDER TRANSACTION THAN 48H OR TRANSACTION NEVER EXIST -->
{"auth":true,"error":true,"message":"No transaction found or its older than 48h"}

{"auth":true,"error":true,"message":"ERRRR"}

{"auth":true,"error":true,"message":"ERRRR"}

{"auth":true,"error":true,"message":"ERRRR"}

<!-- Getting error message: -->
<?php
if ($json->error) {
    echo $json->message;
}
?>

<!-- ############## SUCCESS REPLY ##################### -->

<!-- PAYMENT PENDING -->
{"auth":true,"error":false,"message":"Transaction found","is_found":true,"transaction_status":"pending","txn_hash":""}

<!-- PAYMENT TIMEOUT -->
{"auth":true,"error":false,"message":"Transaction found","is_found":true,"transaction_status":"timeout","txn_hash":""}

<!-- PAYMENT CANCELED -->
{"auth":true,"error":false,"message":"Transaction found","is_found":true,"transaction_status":"canceled","txn_hash":""}

<!-- PAYMENT PAID -->
{"auth":true,"error":false,"message":"Transaction found","is_found":true,"transaction_status":"paid","txn_hash":"78f4c4f88780b816fbe5f163f0b94cc4020981f7aadd7811471aeab97755a742"}


transaction_status can be:
pending - waiting payment
timeout - no payment during 60 min
canceled - user canceled payment
paid - payment received
