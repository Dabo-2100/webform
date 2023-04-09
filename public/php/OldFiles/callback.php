<?php
header("Access-Control-Allow-Origin: *"); //To Allow Access From Other Servers
header("Access-Control-Allow-Methods: POST"); //To Allow POST 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$pdo = require_once 'connect.php';
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$get_string = $_SERVER["QUERY_STRING"];
parse_str($get_string, $data);
ksort($data);
$hmac = $data['hmac'];
$array = [
    "amount_cents",
    "created_at",
    "currency",
    "error_occured",
    "has_parent_transaction",
    "id",
    "integration_id",
    "is_3d_secure",
    "is_auth",
    "is_capture",
    "is_refunded",
    "is_standalone_payment",
    "is_voided",
    "order",
    "owner",
    "pending",
    "source_data_pan",
    "source_data_sub_type",
    "source_data_type",
    "success"
];
if ($data['is_standalone_payment'] == true) {
    echo
    "<div style='width: 100%; height: 100vh; display: flex; flex-direction: column; flex-wrap: nowrap; justify-content: center; align-items: center;'>"
        . "<h1 style='width:100%; font-size:30px; text-align : center;'>تمت عملية الدفع بنجاح برجاء التواصل ~من خلال الواتس اب علي الرقم التالي لتنسيق موعد الأستشارة</h1>"
        . "<a href='https://wa.me/201095090300' style='font-size:30px; color : black;'>+201095090300</a>"
        . "<a href='https://www.israaosman.com' style='font-size:30px; color : black; margin-top : 2rem;'>الصفحة الرئيسية</a>"
        . "</div>";
} else {
    $connectedString = '';
    foreach ($data as $key => $element) {
        if (in_array($key, $array)) {
            $connectedString .= $element;
        }
    }
    $secret = 'BDB97E0BA0A8947C98A453A8BEAAF157';
    $hashed = hash_hmac('sha512', $connectedString, $secret);
    if ($hashed  == $hmac) {
        if ($data['success'] == true) {
            $order_id = $data['order'];
            $sql = "UPDATE appointments SET appoint_index = 2 WHERE (order_id = :order_id)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':order_id', $order_id);
            $statement->execute();
            $sql = "SELECT * From appointments WHERE (order_id = :order_id)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':order_id', $order_id);
            try {
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $Times = ["12:00 PM", "01:00 PM", "02:00 PM", "03:00 PM", "04:30 PM", "05:30 PM"];
                $Appoint_Type = ["استشارة فردية", "استشارة الازواج", "استشارة جماعية"];
                $user_id = $result['user_id'];
                $appoint_day = $result['appoint_day'];
                $appoint_time_index = $result['appoint_time_index'];
                $time = $Times[$appoint_time_index];
                $sql = "SELECT user_email,user_name From app_users WHERE (user_id = :user_id)";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':user_id', $user_id);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $user_email = $result['user_email'];
                $user_name = $result['user_name'];
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
                    $mail->addAddress($user_email, $user_name);
                    $mail->Subject = 'Welcome ' . $user_name;
                    $mail->Body    = '<p style="font-size:20px" dir="rtl">قام : <strong>' . $user_name . '</strong> بتأكيد دفع حجز استشارة <br>' . '<p style="font-size:20px" dir="rtl">في تمام الساعة : <strong>' . $time . '</strong> بتوقيت الإمارات العربية المتحدة<p>';
                    $mail->AltBody = '<p style="font-size:20px">تم تأكيد حجز الدفع للأستشارة بتاريخ : 2020-02-02<p>';
                    $mail->send();
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
                        $mail->addAddress('appointment@israaosman.com', "Dr/Israa Osman");
                        $mail->Subject = 'Welcome Dr/Israa';
                        $mail->Body    = '<p style="font-size:20px" dir="rtl">تم تأكيد حجز الدفع للأستشارة بتاريخ : <strong>' . $appoint_day . '</strong><p><br><p style="font-size:20px" dir="rtl">في تمام الساعة : <strong>' . $time . '</strong> بتوقيت الإمارات العربية المتحدة<p>';
                        $mail->AltBody = '<p style="font-size:20px">تم تأكيد حجز الدفع للأستشارة بتاريخ : 2020-02-02<p>';
                        $mail->send();

                        header('Location: ' . "https://israaosman.com/#/accept/" . $order_id);
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            header('Location: ' . "https://israaosman.com/#/Rejected");
        }
    } else {
        echo "Not Secured";
    }
}
