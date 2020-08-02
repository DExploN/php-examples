<?php

declare(strict_types=1);

function request($client_id, $token, $jwt)
{
    $data = [
        'client_id' => $client_id,
        'client_secret' => $jwt,
        'code' => $token,
        'grant_type' => 'authorization_code'
    ];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://appleid.apple.com/auth/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [

        'Content-Type: application/x-www-form-urlencoded',
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $serverOutput = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$http_status, $serverOutput];
}