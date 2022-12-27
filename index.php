<?php

$server_key = "SB-Mid-server-YEqaiznRRNuNf8mfAhGMprXc";

$is_production = false;

$api_url = $is_production?
'https://app.midtrans.com/snap/v1/transactions':'https://app.sandbox.midtrans.com/snap/v1/transactions';

if (!strpos($_SERVER['REQUEST_URL'], '/charge')) {
    # code...
    http_response_code(404);
    echo "wrong path, make sure it's '/charge'"; exit();

}

if ($_SERVER['REQUST_METHOD'] !== 'POST') {
    # code...
    http_response_code(404);
    echo "Page not found or wrong HTTP REQUEST method is used";

}

$request_body = file_get_contents('php://input');
header('Content-Type: application/json');

$charge_result = chargeAPI($api_url, $server_key, $request_body);

http_response_code($charge_result['http_code']);

echo $charge_result['body'];


function chargeAPI ($api_url, $server_key, $request_body) {
    $ch = curl_init();
    $curl_options = array (
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,

        CURLOPT_HTTPHEADER => array (
            'Content_Type: application/json',
            'Accept: application/json',
            'Authorization: Basic'.base64_decode($server_key)
        ),
        CURLOPT_POSTFIELDS => $request_body
    );

    curl_setopt_array($ch, $curl_options);
    $result = array(
        'body' => curl_exec($ch),
        'http_code' => curl_getinfo($sc, CURLINFO_HTTP_CODE)
    );
    return $result;

}
