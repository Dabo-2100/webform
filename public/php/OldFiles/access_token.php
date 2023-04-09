<?php
function get_access_token($code = 0)
{
    $fianl = '';
    if ($code == 0) {
        $myfile = fopen("token.txt", "r") or die("Unable to open file!");
        $fianl = fread($myfile, filesize("token.txt"));
        fclose($myfile);
    } else {
        $post = [
            'code' => $code,
            'redirect_uri' => 'https://webform.designido.net',
            'client_id' => '1000.R3K41GUMKFVW5K825Z6PZ6JU1HTQ3Q',
            'client_secret' => '95853f787c210aab15a7fa69b90d565470fe0c5e75',
            'grant_type' => 'authorization_code',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $response = curl_exec($ch);
        $response = json_decode($response, true);

        if (count($response) < 3) {
            $fianl = $response['error'];
        } else {
            $fianl = $response['access_token'];
            $myfile = fopen("token.txt", "w") or die("Unable to open file!");
            fwrite($myfile, $fianl);
            fclose($myfile);
        }
    }
    return $fianl;
};
// echo get_access_token('1000.fef277a96d22c4c3618e88f9c6621c60.d303b6003215a892bc1009650cc66c33');
