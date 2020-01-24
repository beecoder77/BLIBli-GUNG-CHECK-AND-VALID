<?php
function generateRandomString($length = 5) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


echo "Jumlah voucher : ";
$jumlahVoucher = trim(fgets(STDIN));
for($i = 0; $i < $jumlahVoucher; $i++){
    $voucher = 'SQBLIBLI'.generateRandomString();
    fwrite(fopen("voucher.txt", "a+"), "$voucher\n");
}