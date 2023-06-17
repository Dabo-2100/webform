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


function DownloadAttachment($Module_Name, $Record_ID, $pdo)
{
    $All_Attachments = json_decode(get_records("Attachments", $code = 0, $pdo), true)['data'];
    $Final_Array = [];
    foreach ($All_Attachments as $Object) {
        if (gettype($Object) == "array") {

            if ($Object['Parent_Id']['id'] == $Record_ID) {
                array_push($Final_Array, $Object['id']);
            }
        }
    }
    $Return_Array = [];
    $Folder_Name = "photos/" . $Record_ID . "/";
    if (file_exists($Folder_Name)) {
        $files = glob($Folder_Name . "*"); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file);
            }
        }
    } else {
        mkdir($Folder_Name);
    }
    if (count($Final_Array) > 0) {
        $i = 0;
        foreach ($Final_Array as $Attachment_ID) {
            $curl_pointer = curl_init();
            $curl_options = array();
            $url = "https://www.zohoapis.com/crm/v2/$Module_Name/$Record_ID/Attachments/$Attachment_ID";
            $curl_options[CURLOPT_URL] = $url;
            $curl_options[CURLOPT_RETURNTRANSFER] = true;
            $curl_options[CURLOPT_HEADER] = 1;
            $curl_options[CURLOPT_CUSTOMREQUEST] = "GET";
            $headersArray = array();
            $headersArray[] = "Authorization" . ":" . "Zoho-oauthtoken " . get_access_token(0, $pdo);
            $curl_options[CURLOPT_HTTPHEADER] = $headersArray;
            curl_setopt_array($curl_pointer, $curl_options);
            $result = curl_exec($curl_pointer);
            $responseInfo = curl_getinfo($curl_pointer);
            curl_close($curl_pointer);
            list($headers, $content) = explode("\r\n\r\n", $result, 2);
            if (strpos($headers, " 100 Continue") !== false) {
                list($headers, $content) = explode("\r\n\r\n", $content, 2);
            }
            $headerArray = (explode("\r\n", $headers, 50));
            $headerMap = array();
            foreach ($headerArray as $key) {
                if (strpos($key, ":") != false) {
                    $firstHalf = substr($key, 0, strpos($key, ":"));
                    $secondHalf = substr($key, strpos($key, ":") + 1);
                    $headerMap[$firstHalf] = trim($secondHalf);
                }
            }
            $response = $content;
            if ($response == null && $responseInfo['http_code'] != 204) {
                list($headers, $content) = explode("\r\n\r\n", $content, 2);
                $response = json_decode($content, true);
            }
            $contentDisp = $headerMap['Content-Disposition'];
            $fileName = substr($contentDisp, strrpos($contentDisp, "'") + 1, strlen($contentDisp));

            if (strpos($fileName, "=") !== false) {
                $fileName = substr($fileName, strrpos($fileName, "=") + 1, strlen($fileName));

                $fileName = str_replace(array(
                    '\'',
                    '"'
                ), '', $fileName);
            }

            $allowed = array('gif', 'png', 'jpg', 'jpeg', 'tif');
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            if (in_array($ext, $allowed)) {
                $filePath = $Folder_Name . $Record_ID . "_" . $i++ . "." . $ext;
                $fp = fopen($filePath, "w"); // $filePath - absolute path where downloaded file has to be stored.
                $stream = $response;
                fputs($fp, $stream);
                fclose($fp);
                array_push($Return_Array, $filePath);
            }
        }
        return json_encode($Return_Array, true);
    }
}
