<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer();

$smtp_key = include '_smtp_key.php';

$mail->isSMTP();
$mail->Host = 'mail.rhodesprivatetours.com';
$mail->SMTPAuth = true;
$mail->Username = 'smtp@rhodesshoreexcursions.com';
$mail->Password = $smtp_key;
//$mail->SMTPSecure = 'tls';
$mail->Port = 25;

$debug = false;

if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

include_once 'geo.php';

$return_to = '';
$from_page = '';
$first_last_name = '';
$e_mail = '';
$comments = '';
$ipAddress = $api_result['query'];
$countryCode = $api_result['countryCode'];
$countryName = $api_result['country'];
$regionName = $api_result['region'];
$cityName = $api_result['city'];
$zipCode = $api_result['zip'];

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if(isset($_POST['return_to'])){
    $return_to = test_input($_POST['return_to']);
}
if(isset($_POST['from_page'])){
    $from_page = test_input($_POST['from_page']);
}
if(isset($_POST['first_last_name'])){
    $first_last_name = test_input($_POST['first_last_name']);
}
if(isset($_POST['e_mail'])){
    $e_mail = test_input($_POST['e_mail']);
}
if(isset( $_POST['comments'])){
    $comments = test_input($_POST['comments']);
}

function passed(){

    if(isset($_POST['meli_tria'])){
        $meli_tria_passed = false;
    } else {
        $meli_tria_passed = true;
    }
    if ($_POST['meli_dio'] == '' && $meli_tria_passed){
        return true;
    } else {
        return false;
    }

}

$e_subject = 'New request from ' . $e_mail . '.';

$msg = "MOBILE FORM - Details:\r\n\n";

$msg .= "From: $from_page\r\n";

$msg .= "Name: $first_last_name\r\n";
$msg .= "E-mail: $e_mail\r\n";
$msg .= "Comments:\r\n $comments\r\n";

$msg .= "Ip Address: $ipAddress\r\n";
$msg .= "Country Code: $countryCode\r\n";
$msg .= "Country Name: $countryName\r\n";
$msg .= "Region Name: $regionName\r\n";
$msg .= "City Name: $cityName\r\n";
$msg .= "Zip Code: $zipCode\r\n";

if ($debug) {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    if (validated()) {
        if (passed()) {
            echo 'passed!\n';
        } else {
            echo 'failed!\n';
        }
    } else {
        echo 'validation error\n';
    }
    print_r($msg);
    die();
}

$headers = "Content-Type: text/html; charset=UTF-8";

// include_once('_keys.php');

function validated() {
    if(isset($_POST['first_last_name']) && $_POST['first_last_name'] == ''){
        return false;
    }
    if(isset($_POST['e_mail']) && $_POST['e_mail'] == ''){
        return false;
    }
    if(isset($_POST['comments']) && $_POST['comments'] == ''){
        return false;
    }

    return true;
}

function passed_recaptcha(){

    if (passed()) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $key = include '_recaptcha_key.php';

        $response = file_get_contents($url."?secret=".$key."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);

        $data = json_decode($response);

        if(isset($data->success) && $data->success == true){
            return true;
        } else {
            return false;
        }

    }

    // if (passed()) {
    //     return true;
    // } else {
    //     return false;
    // }

}

$address = "request@rhodesshoreexcursions.com";
$from_address = "smtp@rhodesshoreexcursions.com";

$mail->Sender= $from_address;
$mail->SetFrom($e_mail, $e_mail, FALSE);

$mail->addReplyTo($e_mail, $e_mail);

$mail->addAddress($address);

$mail->isHTML(false);
$mail->Subject = $e_subject;
$mail->Body = $msg;

if (validated()) {
    if(passed_recaptcha() && $mail->send()){
        // Email has sent successfully, echo a success page.

        header('Location: ' . $return_to . '?contact-form-sent=success');

    } else {
        header('Location: '. $return_to . '?contact-form-sent=fail-mobile');
    }
} else {
    header('Location: '. $return_to . '?contact-form-sent=validation-error');
}


?>