<?php
// header("Access-Control-Allow-Origin: *"); //To Allow Access From Other Servers
header("Access-Control-Allow-Methods: POST"); //To Allow POST 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$pdo = require_once 'connect.php';
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Post_object = file_get_contents('php://input');
    $POST_data = json_decode($Post_object, true);
    $api_name = @$POST_data["api_name"];
} else {
    $api_name = @$_GET["api_name"];
    echo "Do Not Allow To Be There <br>";
}

if ($api_name == "login") {
    $user_email = htmlspecialchars(@$POST_data["user_email"]);
    $user_password = htmlspecialchars(@$POST_data["user_password"]);
    $text_password = $user_password;
    $sql = "SELECT user_password,user_id,is_verified,user_uid From app_users WHERE (user_email = :user_email)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_email', $user_email);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $hased_password = $result["user_password"];
        $user_id = $result["user_id"];
        $is_verified = $result["is_verified"];
        $user_uid = $result["user_uid"];
        if ($hased_password == $text_password) {
            if ($is_verified == 0) {
                $data = array(
                    "user_verfiy" => 0,
                    "user_id" => $user_id,
                    "user_uid" => $user_uid
                );
                echo json_encode($data);
            } else {
                $data = array(
                    "user_verfiy" => 1,
                    "user_id" => $user_id,
                    "user_uid" => $user_uid
                );
                echo json_encode($data);
            }
        } else {
            echo 0;
        }
    } else {
        echo "0";
    }
}
if ($api_name == "join") {
    $user_email     = htmlspecialchars(@$POST_data["user_email"]);
    $user_name      = htmlspecialchars(@$POST_data["user_name"]);
    $user_password  = "12345678";
    $text_password  = $user_password;
    $verification_code = rand(1000, 9999);
    $user_uid = uniqid('', true);
    // Code Start
    $sql = "SELECT user_id From app_users WHERE (user_email = :user_email)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_email', $user_email);
    try {
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (gettype($result) != 'boolean') {
            $user_id = $result['user_id'];
            echo 0;
        } else {
            $sql = "INSERT INTO app_users 
                (user_email,user_password,user_name,user_country,user_age,user_phone,user_gender,verification_code,is_verified,user_uid) 
                VALUES (:user_email,:text_password,:user_name,0,0,0,0,:verification_code,0,:user_uid)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':user_email', $user_email);
            $statement->bindParam(':text_password', $text_password);
            $statement->bindParam(':user_name', $user_name);
            $statement->bindParam(':verification_code', $verification_code);
            $statement->bindParam(':user_uid', $user_uid);
            $statement->execute();
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.hostinger.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'info@easetasks.com';
                $mail->Password   = '@Soo2taw2eet';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                $mail->setFrom('info@easetasks.com', 'Dr/Israa Osman');
                $mail->addAddress($user_email, $user_name);
                $mail->Subject = 'Welcome ' . $user_name;
                $mail->Body    = '<p style="font-size:20px">Your Verfication Code Is : <strong>' . $verification_code . '</strong></p><br> <p style="font-size:20px">Your Default Password is : 12345678 <p>';
                $mail->AltBody = 'Your Verfication Code Is : ' . $verification_code;
                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            $sql2 = "SELECT user_id FROM app_users WHERE (user_email=:user_email)";
            $statement2 = $pdo->prepare($sql2);
            $statement2->bindParam(':user_email', $user_email);
            $statement2->execute();
            $result = $statement2->fetch(PDO::FETCH_ASSOC);
            $data = array(
                "user_id" => $result['user_id'],
            );
            echo json_encode($data);
        }
    } catch (PDOException $e) {
    }
}
if ($api_name == "sendAsk") {
    $question = htmlspecialchars(@$POST_data["question"]);
    $user_uid = htmlspecialchars(@$POST_data["user_uid"]);
    $sql = "SELECT * From app_users WHERE (user_uid = :user_uid)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_uid', $user_uid);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $user_name = $result["user_name"];
        $user_email = $result["user_email"];
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'info@easetasks.com';
            $mail->Password   = '@Soo2taw2eet';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->setFrom('info@easetasks.com', 'Dr/Israa Osman');
            $mail->addAddress("ask@israaosman.com", 'TheDR');
            $mail->Subject = 'Welcome Dr/Israa';
            $mail->Body    = '<p style="font-size:20px">Your Have A New Question From : <strong>' . $user_name . '</strong></p> <br>' . '<p style="font-size:20px">The Question Is : <strong>' . $question . "</strong></p><br>" . '<p style="font-size:20px">User Email Is : <a href = "mailto: ' . $user_email . '"><strong>' . $user_email . "</strong></a></p>";
            $mail->AltBody = '<p style="font-size:20px">Your Have A New Question From : <strong>' . $user_name . '</strong></p> <br>' . '<p style="font-size:20px">The Question Is : <strong>' . $question . "</strong></p><br>" . '<p style="font-size:20px">User Email Is : <a href = "' . $user_email . '"><strong>' . $user_email . "</strong></a></p>";
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
if ($api_name == "verify") {
    $vCode = htmlspecialchars(@$POST_data["vCode"]);
    $user_id = htmlspecialchars(@$POST_data["user_id"]);
    $sql = "SELECT verification_code From app_users WHERE (user_id = :user_id)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $verification_code = $result["verification_code"];
    if ($verification_code  == $vCode) {
        $sql = "UPDATE app_users SET is_verified = 1 WHERE (user_id = :user_id)";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();
        echo 1;
    } else {
        echo 0;
    }
}
if ($api_name == "verify2") {
    $vCode = htmlspecialchars(@$POST_data["vCode"]);
    $user_email = htmlspecialchars(@$POST_data["user_email"]);
    $sql = "SELECT verification_code From app_users WHERE (user_email = :user_email)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_email', $user_email);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $verification_code = $result["verification_code"];
    if ($verification_code  == $vCode) {
        echo 1;
    } else {
        echo 0;
    }
}
if ($api_name == "checkaccept") {
    $user_uid = htmlspecialchars(@$POST_data["user_uid"]);
    $order_id = htmlspecialchars(@$POST_data["order_id"]);
    $sql = "SELECT user_id From app_users WHERE (user_uid = :user_uid)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_uid', $user_uid);
    try {
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $user_id = $result["user_id"];
        $sql = "SELECT user_id From appointments WHERE (order_id = :order_id)";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':order_id', $order_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result != false) {
            $user_id2 = $result["user_id"];
            if ($user_id  == $user_id2) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
if ($api_name == "makeappoint") {
    $user_id = htmlspecialchars(@$POST_data["user_id"]);
    $appoint_day = htmlspecialchars(@$POST_data["appoint_day"]);
    $appoint_time_index = htmlspecialchars(@$POST_data["appoint_time_index"]);
    $sql = "INSERT INTO appointments (appoint_index,user_id,appoint_day,appoint_time_index) VALUES(3,:user_id,:appoint_day,:appoint_time_index)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':appoint_day', $appoint_day);
    $statement->bindParam(':appoint_time_index', $appoint_time_index);
    $pdo->beginTransaction();
    $statement->execute();
    $appoint_id = $pdo->lastInsertId();
    $pdo->commit();
    $appid = array(
        "appoint_id" => $appoint_id
    );
    $sql = "SELECT user_email FROM app_users WHERE user_id=:user_id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $user_email = $result["user_email"];
    // "user_email" => "a_fattah_m@icloud.com"
    $appid2 = array(
        "user_email" => $user_email
    );
    $result = array_merge($appid, $appid2);
    echo json_encode($result);
}
if ($api_name == "updatepass") {
    $user_email = htmlspecialchars(@$POST_data["user_email"]);
    $user_password = htmlspecialchars(@$POST_data["user_password"]);
    $vCode = htmlspecialchars(@$POST_data["vCode"]);
    $sql = "UPDATE app_users SET user_password = :user_password WHERE (user_email = :user_email and verification_code = :vCode)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_email', $user_email);
    $statement->bindParam(':user_password', $user_password);
    $statement->bindParam(':vCode', $vCode);
    $statement->execute();
}
if ($api_name == "ResetCalendar") {
    $TodayDate = htmlspecialchars(@$POST_data["TodayDate"]);
    if (!$TodayDate) {
        $TodayDate = date("Y-m-d");
        $TodayDate = date("Y-m-d");
        $TodayDate = date("Y-m-d", strtotime($TodayDate . '+ 20 day'));
    }
    $sql = "SELECT appoint_time_index From appointments WHERE (appoint_day = :today)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':today', $TodayDate);
    $statement->execute();
    $result1 = $statement->fetchAll(PDO::FETCH_ASSOC);
    $data = array(
        "Today" => date("Y-m-d", strtotime(date("Y-m-d") . '+ 20 day')),
    );
    $result = array_merge($data, $result1);
    echo json_encode($result);
}
if ($api_name == "checkemail") {
    $user_email = htmlspecialchars(@$POST_data["user_email"]);
    $sql = "SELECT user_id,is_verified From app_users WHERE (user_email = :user_email)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_email', $user_email);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if (gettype($result) != 'boolean') {
        $data = array(
            "user_id" => $result["user_id"],
            "is_verified" => $result["is_verified"],
        );
        echo json_encode($data);
    } else {
        echo 0;
    }
}
if ($api_name == "getuserinfo") {
    $user_id = htmlspecialchars(@$POST_data["user_id"]);
    $sql = "SELECT * From app_users WHERE (user_id = :user_id)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
}
if ($api_name == "getuserid") {
    $user_uid = htmlspecialchars(@$POST_data["user_uid"]);
    $sql = "SELECT user_id From app_users WHERE (user_uid = :user_uid)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_uid', $user_uid);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
}
if ($api_name == "connect") {
    //Test Egypt IP
    // echo json_encode(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=41.69.17.36')));
    //Test Forgien IP
    // echo json_encode(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=198.145.231.245')));
    // Real IP
    echo json_encode(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . getenv("REMOTE_ADDR"))));
}
if ($api_name == "resendvCode") {
    $user_email     = htmlspecialchars(@$POST_data["user_email"]);
    $index     = htmlspecialchars(@$POST_data["index"]);
    $verification_code = rand(1000, 9999);
    if ($index == 1) {
        $Msg = '<p style="font-size:20px">Your Reset Code Is : <strong>' . $verification_code . '</strong></p>';
    } else {
        $Msg = '<p style="font-size:20px">Your Verfication Code Is : <strong>' . $verification_code . '</strong></p><br> <p style="font-size:20px">Your Default Password is : 12345678 <p>';
    }
    $sql = "UPDATE app_users SET verification_code = :verification_code WHERE (user_email = :user_email)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_email', $user_email);
    $statement->bindParam(':verification_code', $verification_code);
    $statement->execute();
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@easetasks.com';
        $mail->Password   = '@Soo2taw2eet';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom('info@easetasks.com', 'Dr/Israa Osman');
        $mail->addAddress($user_email, "");
        $mail->Subject = 'Welcome ' . "";
        $mail->Body    = $Msg;
        $mail->AltBody = 'Your Reset Code Is : ' . $verification_code;
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
if ($api_name == "updateuserinfo") {
    $user_id = htmlspecialchars(@$POST_data["user_id"]);
    $user_country = htmlspecialchars(@$POST_data["user_country"]);
    $user_phone = htmlspecialchars(@$POST_data["user_phone"]);
    $user_age = htmlspecialchars(@$POST_data["user_age"]);
    $user_gender = htmlspecialchars(@$POST_data["user_gender"]);
    $sql = "UPDATE app_users SET
        user_country = :user_country,
        user_phone = :user_phone,
        user_age = :user_age,
        user_gender = :user_gender
    WHERE (user_id = :user_id)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':user_country', $user_country);
    $statement->bindParam(':user_phone', $user_phone);
    $statement->bindParam(':user_age', $user_age);
    $statement->bindParam(':user_gender', $user_gender);
    $statement->execute();
}
if ($api_name == "updateOrderid") {
    $order_id = htmlspecialchars(@$POST_data["order_id"]);
    $appoint_id = htmlspecialchars(@$POST_data["appoint_id"]);
    $sql = "UPDATE appointments SET order_id = :order_id WHERE (appoint_id = :appoint_id)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':order_id', $order_id);
    $statement->bindParam(':appoint_id', $appoint_id);
    $statement->execute();
}

if ($api_name == "getchatid") {
    $user_1 = htmlspecialchars(@$POST_data["user_1"]);
    $user_2 = htmlspecialchars(@$POST_data["user_2"]);
    $sql = "SELECT chat_id From chats WHERE (user_1 = :user_1 and user_2 =:user_2) or (user_1 = :user_2 and user_2 =:user_1)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_1', $user_1);
    $statement->bindParam(':user_2', $user_2);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if (gettype($result) != 'boolean') {
        $data = array(
            "chat_id" => $result["chat_id"],
        );
        echo json_encode($data);
    } else {
        $sql = "INSERT INTO chats (user_1,user_2,user_1_view,user_2_view) VALUES(:user_1,:user_2,1,1)";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':user_1', $user_1);
        $statement->bindParam(':user_2', $user_2);
        $pdo->beginTransaction();
        $statement->execute();
        $chat_id = $pdo->lastInsertId();
        $pdo->commit();
        $appid = array(
            "chat_id" => $chat_id
        );
        echo json_encode($appid);
    }
}


// https://israaosman.com/php/callback.php?discount_details=%5B%5D&has_parent_transaction=false&hmac=7023a78ad65dd29dbf13facc4d60500dd66136a1ba272a0962ba4619ad1339a7f40e91762a2a66007dafa5c7718b98d776425c8e25e19ba92031f0dbd97336f7&profile_id=561496&order=93273677&error_occured=false&is_void=false&amount_cents=1000&source_data.sub_type=MasterCard&success=true&txn_response_code=APPROVED&is_capture=false&currency=EGP&owner=919948&id=79804050&integration_id=3126638&is_refunded=false&is_standalone_payment=true&merchant_commission=0&acq_response_code=00&created_at=2023-01-12T09%3A28%3A42.087498&is_voided=false&pending=false&data.message=Approved&captured_amount=0&source_data.type=card&is_refund=false&updated_at=2023-01-12T09%3A29%3A37.057775&source_data.pan=2019&is_3d_secure=true&refunded_amount_cents=0&is_auth=false