<?php
header("Access-Control-Allow-Origin: *"); //To Allow Access From Other Servers
header("Access-Control-Allow-Methods: POST"); //To Allow POST 
header("Access-Control-Allow-Headers: Content-Type, Authorization");
$pdo = require_once 'connect.php';
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

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
    echo ($response);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Post_object = file_get_contents('php://input');
    $POST_data = json_decode($Post_object, true);
    $api_name = @$POST_data["api_name"];
} else {
    $api_name = @$_GET["api_name"];
    // echo "Do Not Allow To Be There <br>";
}

if ($api_name == "RefreshAccessToken") {
    $code = htmlspecialchars(@$POST_data["the_code"]);
    echo get_access_token($code, $pdo);
}

if ($api_name == "SaveTaskId") {
    $task_id = htmlspecialchars(@$POST_data["the_code"]);
    $sql = "UPDATE app_info SET task_id = :task_id WHERE (record_id = 1)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':task_id', $task_id);
    $statement->execute();
    $data = array(
        "Task_Updated" => 1,
    );
    echo json_encode($data);
}

if ($api_name == "GetTaskId") {
    $sql = "SELECT task_id From app_info WHERE (record_id = 1)";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $task_id = $result["task_id"];
        $data = array(
            "task_id" => $task_id,
        );
        echo json_encode($data);
    }
}

if ($api_name == "CheckTheConnection") {
    $TheUserID = htmlspecialchars(@$POST_data["TheUserID"]);
    get_records("users", 0, $pdo);
    // $res = json_decode(SearchRecords("users", $code = 0, $pdo, "(id:equals:" . $TheUserID . ")"), true);
}

if ($api_name == "GetAllProducts") {
    get_records("Products", 0, $pdo);
}
if ($api_name == "GetRawMaterials") {
    get_records("Raw_Materials", 0, $pdo);
}

if ($api_name == "GetPaperCutSize") {
    get_records("PaperCut_Sizes", 0, $pdo);
}

if ($api_name == "GetPricingTasks") {
    $Owner_ID = 4432004000007195001;
    $final = [];
    $response2 = json_decode(SearchRecords("Price_Tasks", $code = 0, $pdo, "(Owner.id:equals:" . $Owner_ID . ")"), true);
    foreach ($response2['data'] as $Task) {
        $Created_Date = $Task['Starting_Date'];
        $Task_ID = $Task['id'];
        $Requirement_ID = $Task['What_to_price']['id'];
        $response = json_decode(SearchRecords("Deal_Requirements", $code = 0, $pdo, "(id:equals:" . $Requirement_ID . ")"), true);
        $Requirement_Details = $response['data'][0]['Requirement_Details'];
        $Quantity = $response['data'][0]['Quantity'];
        $Name = $response['data'][0]['Name'];
        $data = array(
            "Requirement_Details" => $Requirement_Details,
            "Quantity" => $Quantity,
            "Name" => $Name,
            "Created_Date" => $Created_Date,
            "Task_ID" => $Task_ID,
        );
        array_push($final, $data);
    }
    echo json_encode($final);
}

if ($api_name == "GetTaskDetails") {
    $task_id = htmlspecialchars(@$POST_data["task_id"]);
    if ($task_id == 0) {
        $sql = "SELECT task_id From app_info WHERE (record_id = 1)";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $task_id = $result["task_id"];
        }
    }
    $response = json_decode(SearchRecords("Price_Tasks", $code = 0, $pdo, "(id:equals:" . $task_id . ")"), true);
    $Requirement_ID = $response['data'][0]['What_to_price']['id'];
    $response2 = json_decode(SearchRecords("Deal_Requirements", $code = 0, $pdo, "(id:equals:" . $Requirement_ID . ")"), true);
    $Requirement_Details = $Requirement_ID = $response2['data'][0]['Requirement_Details'];
    $Quantity = $Requirement_ID = $response2['data'][0]['Quantity'];
    $data = array(
        "Requirement_Details" => $Requirement_Details,
        "Quantity" => $Quantity,
    );
    echo json_encode($data);
}

if ($api_name == "SendDataToZoho") {
    $ThePrice = htmlspecialchars(@$POST_data["ThePrice"]);
    $sql = "SELECT task_id,access_token From app_info WHERE (record_id = 1)";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $task_id = $result["task_id"];
        $access_token = $result["access_token"];
    }
    echo $task_id . "<br>";
    $curl_pointer = curl_init();
    $curl_options = array();
    $url = "https://www.zohoapis.com/crm/v2/Price_Tasks";

    $curl_options[CURLOPT_URL] = $url;
    $curl_options[CURLOPT_RETURNTRANSFER] = true;
    $curl_options[CURLOPT_HEADER] = 1;
    $curl_options[CURLOPT_CUSTOMREQUEST] = "PUT";
    $requestBody = array();
    $recordArray = array();
    $recordObject = array();
    $recordObject["id"] = $task_id;
    $recordObject["Final_Price"] = $ThePrice;
    $recordArray[] = $recordObject;

    $requestBody["data"] = $recordArray;
    $curl_options[CURLOPT_POSTFIELDS] = json_encode($requestBody);
    $headersArray = array();

    $headersArray[] = "Authorization" . ":" . "Zoho-oauthtoken " . $access_token;

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
    $jsonResponse = json_decode($content, true);
    if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
        list($headers, $content) = explode("\r\n\r\n", $content, 2);
        $jsonResponse = json_decode($content, true);
    }
    var_dump($headerMap);
    var_dump($jsonResponse);
    var_dump($responseInfo['http_code']);
}
