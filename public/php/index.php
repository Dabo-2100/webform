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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Post_object = file_get_contents('php://input');
    $POST_data = json_decode($Post_object, true);
    $api_name = @$POST_data["api_name"];
} else {
    $api_name = @$_GET["api_name"];
    echo "Do Not Allow To Be There <br>";
}

if ($api_name == "RefreshAccessToken") {
    $code = htmlspecialchars(@$POST_data["the_code"]);
    echo get_access_token($code, $pdo);
}

if ($api_name == "AddNewExpense") {
    $Due_Task_ID = htmlspecialchars(@$POST_data["Due_Task_ID"]);
    $Expense_Name = htmlspecialchars(@$POST_data["Expense_Name"]);
    $Expense_Value = htmlspecialchars(@$POST_data["Expense_Value"]);
    $Task_Type = htmlspecialchars(@$POST_data["Task_Type"]);
    $curl_pointer = curl_init();
    $curl_options = array();
    $url = "https://www.zohoapis.com/crm/v2/Task_Expenses";
    $curl_options[CURLOPT_URL] = $url;
    $curl_options[CURLOPT_RETURNTRANSFER] = true;
    $curl_options[CURLOPT_HEADER] = 1;
    $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
    $requestBody = array();
    $recordArray = array();
    $recordObject = array();
    if ($Task_Type == null) {
        $recordObject['Due_Task']['id'] = $Due_Task_ID;
    } else {

        $recordObject['Operation_Task']['id'] = $Due_Task_ID;
    }
    $recordObject["Name"] = $Expense_Name;
    $recordObject["Expense_Value"] = $Expense_Value;
    $recordArray[] = $recordObject;
    $requestBody["data"] = $recordArray;
    $curl_options[CURLOPT_POSTFIELDS] = json_encode($requestBody);
    $headersArray = array();
    $headersArray[] = "Authorization" . ":" . "Zoho-oauthtoken " .  get_access_token(0, $pdo);
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
    echo get_records("users", 0, $pdo);
}

if ($api_name == "GetAllProducts") {
    echo get_records("Products", 0, $pdo);
}
if ($api_name == "GetRawMaterials") {
    echo get_records("Raw_Materials", 0, $pdo);
}

if ($api_name == "GetPaperCutSize") {
    echo get_records("PaperCut_Sizes", 0, $pdo);
}

if ($api_name == "GetPricingTasks") {
    $Owner_ID = htmlspecialchars(@$POST_data["Zoho_ID"]);
    $final = [];

    if ($Owner_ID == 0) {;
        $response2 = json_decode(get_records("Price_Tasks", 0, $pdo), true);
    } else {
        $response2 = json_decode(SearchRecords("Price_Tasks", $code = 0, $pdo, "(Owner.id:equals:" . $Owner_ID . ")"), true);
    }

    if ($response2 != null) {
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
                "Pricing_Way" => 0,
                "Final_Unit_Price" => 0,
            );
            array_push($final, $data);
        }
    }
    echo json_encode($final);
}

if ($api_name == "GetOpenProjects") {
    $Owner_ID = htmlspecialchars(@$POST_data["Zoho_ID"]);
    $final = [];
    if ($Owner_ID == 0) {;
        $response2 = json_decode(get_records("Work_Tasks", 0, $pdo), true);
    } else {
        $response2 = json_decode(SearchRecords("Work_Tasks", $code = 0, $pdo, "(Owner.id:equals:" . $Owner_ID . ")"), true);
    }

    if ($response2 != null) {
        foreach ($response2['data'] as $Task) {
            $Task_ID = $Task['id'];
            $Task_Name = $Task['Name'];
            $Task_Done = $Task['Task_Done'];
            $Task_Stage_ID = $Task['Task_Stage']['id'];
            $Task_Stage_Name = $Task['Task_Stage']['name'];
            $Task_Details = $Task['Task_Details'];
            $Starting_Date = $Task['Starting_Date'];
            $Deadline_Date = $Task['Deadline_Date'];
            $Requirement_ID = $Task['Deal_Requiremenet']['id'];
            //Get The Task Stages
            $Task_Type = $Task['Task_Type'];
            $response2 = json_decode(SearchRecords("Task_Stages", $code = 0, $pdo, "(Project_Type:equals:" . $Task_Type . ")"), true);
            $All_Stages = [];
            if ($response2 != null) {
                foreach ($response2['data'] as $Stage) {
                    $Stage_ID = $Stage['id'];
                    $Stage_Name = $Stage['Name'];
                    $Arabic_Name = $Stage['Arabic_Name'];
                    $Stage_Order = $Stage['Stage_Order'];
                    $data = array(
                        "Stage_ID" => $Stage_ID,
                        "Stage_Name" => $Stage_Name,
                        "Arabic_Name" => $Arabic_Name,
                        "Stage_Order" => $Stage_Order,
                    );
                    array_push($All_Stages, $data);
                }
            }
            $data = array(
                "Task_ID" => $Task_ID,
                "Task_Name" => $Task_Name,
                "Task_Stage_ID" => $Task_Stage_ID,
                "Task_Details" => $Task_Details,
                "Starting_Date" => $Starting_Date,
                "Deadline_Date" => $Deadline_Date,
                "Requirement_ID" => $Requirement_ID,
                "TaskStages" => $All_Stages,
                "Task_Done" => $Task_Done
            );
            array_push($final, $data);
        }
    }
    echo json_encode($final);
}
if ($api_name == "GetDeliveryTasks") {
    echo SearchRecords("Operation_Tasks", $code = 0, $pdo, "(Task_Type.id:equals:Delivery)");
}

if ($api_name == "GetTaskExpenses") {
    $Task_ID = htmlspecialchars(@$POST_data["Task_ID"]);
    $Task_Type = htmlspecialchars(@$POST_data["Task_Type"]);
    if ($Task_Type == null) {
        $res = json_decode(SearchRecords("Task_Expenses", $code = 0, $pdo, "(Due_Task.id:equals:" . $Task_ID . ")"), true);
    } else {
        $res = json_decode(SearchRecords("Task_Expenses", $code = 0, $pdo, "(Operation_Task.id:equals:" . $Task_ID . ")"), true);
    }
    $AllExpenses = [];
    if ($res != null) {
        foreach ($res['data'] as $Expense) {
            $Expense_ID = $Expense['id'];
            $Expense_Name = $Expense['Name'];
            $Expense_Value = $Expense['Expense_Value'];
            if ($Task_Type == null) {
                $Due_Task_ID = $Expense['Due_Task']['id'];
            } else {
                $Due_Task_ID = $Expense['Operation_Task']['id'];
            }
            $Last_Update = $Expense['Modified_Time'];
            $data = array(
                "Expense_ID" => $Expense_ID,
                "Expense_Name" => $Expense_Name,
                "Expense_Value" => $Expense_Value,
                "Due_Task_ID" => $Due_Task_ID,
                "Last_Update" => $Last_Update,
            );
            array_push($AllExpenses, $data);
        }
    }
    echo json_encode($AllExpenses);
}

if ($api_name == "GetServerDate") {
    $TheDate = date('Y-m-d');
    $data = array(
        "Server_Date" => $TheDate,
    );
    echo json_encode($data);
}

if ($api_name == "UpdateTaskStage") {
    $Task_ID = htmlspecialchars(@$POST_data["Task_ID"]);
    $Task_Stage_ID = htmlspecialchars(@$POST_data["NewVal"]);
    $curl_pointer = curl_init();
    $curl_options = array();
    $url = "https://www.zohoapis.com/crm/v2/Work_Tasks";
    $curl_options[CURLOPT_URL] = $url;
    $curl_options[CURLOPT_RETURNTRANSFER] = true;
    $curl_options[CURLOPT_HEADER] = 1;
    $curl_options[CURLOPT_CUSTOMREQUEST] = "PUT";
    $requestBody = array();
    $recordArray = array();
    $recordObject = array();
    $recordObject["id"] = $Task_ID;
    $recordObject["Task_Stage"]["id"] = $Task_Stage_ID;
    $recordArray[] = $recordObject;
    $requestBody["data"] = $recordArray;
    $curl_options[CURLOPT_POSTFIELDS] = json_encode($requestBody);
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
    $jsonResponse = json_decode($content, true);
    if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
        list($headers, $content) = explode("\r\n\r\n", $content, 2);
        $jsonResponse = json_decode($content, true);
    }
    var_dump($headerMap);
    var_dump($jsonResponse);
    var_dump($responseInfo['http_code']);
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
    $task_id = htmlspecialchars(@$POST_data["Task_ID"]);
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
    $jsonResponse = json_decode($content, true);
    if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
        list($headers, $content) = explode("\r\n\r\n", $content, 2);
        $jsonResponse = json_decode($content, true);
    }
    var_dump($headerMap);
    var_dump($jsonResponse);
    var_dump($responseInfo['http_code']);
}

if ($api_name == "UpdateTaskDone") {
    $Task_ID = htmlspecialchars(@$POST_data["Task_ID"]);
    $NewVal = htmlspecialchars(@$POST_data["NewVal"]);
    $curl_pointer = curl_init();
    $curl_options = array();
    $url = "https://www.zohoapis.com/crm/v2/Work_Tasks";
    $curl_options[CURLOPT_URL] = $url;
    $curl_options[CURLOPT_RETURNTRANSFER] = true;
    $curl_options[CURLOPT_HEADER] = 1;
    $curl_options[CURLOPT_CUSTOMREQUEST] = "PUT";
    $requestBody = array();
    $recordArray = array();
    $recordObject = array();
    $recordObject["id"] = $Task_ID;
    $recordObject["Task_Done"] = $NewVal;
    $recordArray[] = $recordObject;
    $requestBody["data"] = $recordArray;
    $curl_options[CURLOPT_POSTFIELDS] = json_encode($requestBody);
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
    $jsonResponse = json_decode($content, true);
    if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
        list($headers, $content) = explode("\r\n\r\n", $content, 2);
        $jsonResponse = json_decode($content, true);
    }
    var_dump($headerMap);
    var_dump($jsonResponse);
    var_dump($responseInfo['http_code']);
}

if ($api_name == "RemoveExpense") {
    $Expense_ID = htmlspecialchars(@$POST_data["Expense_ID"]);
    $curl_pointer = curl_init();

    $curl_options = array();
    $url = "https://www.zohoapis.com/crm/v2/Task_Expenses?";
    $parameters = array();
    $parameters["ids"] = $Expense_ID;
    foreach ($parameters as $key => $value) {
        $url = $url . $key . "=" . $value . "&";
    }
    $curl_options[CURLOPT_URL] = $url;
    $curl_options[CURLOPT_RETURNTRANSFER] = true;
    $curl_options[CURLOPT_HEADER] = 1;
    $curl_options[CURLOPT_CUSTOMREQUEST] = "DELETE";
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
    $jsonResponse = json_decode($content, true);
    if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
        list($headers, $content) = explode("\r\n\r\n", $content, 2);
        $jsonResponse = json_decode($content, true);
    }
    var_dump($headerMap);
    var_dump($jsonResponse);
    var_dump($responseInfo['http_code']);
}

if ($api_name == "UpdateRawMaterialPrice") {
    $Material_ID = htmlspecialchars(@$POST_data["raw_material_id"]);
    $NewVal = htmlspecialchars(@$POST_data["raw_material_price"]);
    $curl_pointer = curl_init();
    $curl_options = array();
    $url = "https://www.zohoapis.com/crm/v2/Raw_Materials";
    $curl_options[CURLOPT_URL] = $url;
    $curl_options[CURLOPT_RETURNTRANSFER] = true;
    $curl_options[CURLOPT_HEADER] = 1;
    $curl_options[CURLOPT_CUSTOMREQUEST] = "PUT";
    $requestBody = array();
    $recordArray = array();
    $recordObject = array();
    $recordObject["id"] = $Material_ID;
    $recordObject["Price_Per_Unit"] = $NewVal;
    $recordArray[] = $recordObject;
    $requestBody["data"] = $recordArray;
    $curl_options[CURLOPT_POSTFIELDS] = json_encode($requestBody);
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
    $jsonResponse = json_decode($content, true);
    if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
        list($headers, $content) = explode("\r\n\r\n", $content, 2);
        $jsonResponse = json_decode($content, true);
    }
    var_dump($headerMap);
    var_dump($jsonResponse);
    var_dump($responseInfo['http_code']);
}


if ($api_name == "GetAllUsers") {
    $curl_pointer = curl_init();
    $curl_options = array();
    $url = "https://www.zohoapis.com/crm/v2/users?";
    $parameters = array();
    $parameters["scope"] = "ZohoCRM.users.ALL";
    $parameters["type"] = "AllUsers";
    $parameters["page"] = "1";
    $parameters["per_page"] = "50";
    foreach ($parameters as $key => $value) {
        $url = $url . $key . "=" . $value . "&";
    }
    $curl_options[CURLOPT_URL] = $url;
    $curl_options[CURLOPT_RETURNTRANSFER] = true;
    $curl_options[CURLOPT_HEADER] = 1;
    $curl_options[CURLOPT_CUSTOMREQUEST] = "GET";
    $headersArray = array();
    $headersArray[] = "Authorization" . ":" . "Zoho-oauthtoken " . get_access_token(0, $pdo);
    $headersArray[] = "If-Modified-Since" . ":" . "2020-05-15T12:00:00+05:30";
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

if ($api_name == "Login") {
    $username = htmlspecialchars(@$POST_data["username"]);
    $password = htmlspecialchars(@$POST_data["password"]);
    $sql = "SELECT email,token,user_type From app_users WHERE (username = :username) and (password = :password)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':password', $password);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $error = "No Error";
    $Zoho_ID = $email = $token = $user_type = $Login_Error = $Zoho_Error = false;
    if ($result) {
        $email = $result["email"];
        $token = $result["token"];
        $user_type = $result["user_type"];
    } else {
        $Login_Error = true;
    }
    $res = json_decode(SearchRecords("users", $code = 0, $pdo, "(email:equals:" . $email . ")"), true);
    @$Zoho_ID = $res['users'][0]['id'];
    if ($Zoho_ID == null) {
        $Zoho_Error = true;
    }
    $data = array(
        "Login_Error" => $Login_Error,
        "Zoho_Error" => $Zoho_Error,
        "email" => $email,
        "token" => $token,
        "user_type" => $user_type,
        "Zoho_ID" => $Zoho_ID,
    );
    echo json_encode($data);
}

if ($api_name == "CheckToken") {
    $token = htmlspecialchars(@$POST_data["token"]);
    $email = htmlspecialchars(@$POST_data["email"]);
    $sql = "SELECT user_id,user_type From app_users WHERE (token = :token) and (email = :email)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':token', $token);
    $statement->bindParam(':email', $email);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $user_id = $result["user_id"];
        $user_type = $result["user_type"];
    }
    $data = array(
        "user_id" => $user_id,
        "user_type" => $user_type,
    );
    echo json_encode($data);
}
