<?php
require("access_token.php");
function get_records($Module, $code = 0)
{
    $Access_Token = 0;
    if ($code == 0) {
        $Access_Token = get_access_token();
    } else {
        $Access_Token = get_access_token($code);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/" . $Module);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Zoho-oauthtoken ' . $Access_Token,
        'Content-Type: application/x-www-form-urlencoded'
    ));
    $response = curl_exec($ch);
    echo ($response);
};

// get_records("Products");
