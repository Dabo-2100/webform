<?php
    function get_access_token($code = 0, $pdo)
    {
        $fianl = '';
        if ($code == 0) {
            $sql = "SELECT access_token From app_info WHERE (record_id = 1)";
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $fianl = $result["access_token"];
            }
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
                $sql = "UPDATE app_info SET access_token = :access_token WHERE (record_id = 1)";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':access_token', $fianl);
                $statement->execute();
            }
        }
        return $fianl;
    };

    function get_records($Module, $code = 0, $pdo)
    {
        $Access_Token = 0;
        if ($code == 0) {
            $Access_Token = get_access_token(0, $pdo);
        } else {
            $Access_Token = get_access_token($code, $pdo);
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
        return $response;
    };

    function SearchRecords($Module, $code = 0, $pdo, $SearchCritera)
    {
        $Access_Token = 0;
        if ($code == 0) {
            $Access_Token = get_access_token(0, $pdo);
        } else {
            $Access_Token = get_access_token($code, $pdo);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/" . $Module . "/" . "search?criteria=(" . $SearchCritera . ")");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Zoho-oauthtoken ' . $Access_Token,
            'Content-Type: application/x-www-form-urlencoded'
        ));
        $response = curl_exec($ch);
        return $response;
    };
