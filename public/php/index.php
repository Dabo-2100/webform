<?php
header("Access-Control-Allow-Origin: *"); //To Allow Access From Other Servers
header("Access-Control-Allow-Methods: POST"); //To Allow POST 
header("Access-Control-Allow-Headers: Content-Type, Authorization");
require("get_records.php");
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
    get_access_token($code);
}

if ($api_name == "SaveTaskId") {
    $final = htmlspecialchars(@$POST_data["the_code"]);
    // $myfile = fopen("taskid.txt", "w") or die("Unable to open file!");
    // fwrite($myfile, $final);
    // fclose($myfile);
    echo $final;
}

if ($api_name == "GetTaskId") {
    $myfile = fopen("taskid.txt", "r") or die("Unable to open file!");
    $fianl = fread($myfile, filesize("taskid.txt"));
    fclose($myfile);
    $data = array(
        "task_is_here" => 0,
        "task_id" => $fianl,
    );
    echo json_encode($data);
}
if ($api_name == "GetAllProducts") {
    get_records("Products");
}
if ($api_name == "GetRawMaterials") {
    get_records("Raw_Materials");
}

if ($api_name == "GetPaperCutSize") {
    get_records("PaperCut_Sizes");
}
