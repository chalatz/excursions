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
$first_name = '';
$last_name = '';
$first_last_name = '';
$city_state_zip = '';
$phone_fax = '';
$e_mail = '';
$date_month = '';
$date_date = '';
$date_year = '';
$cruise_ship = '';
$speaking_language = '';
$party_num = '';
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
if(isset($_POST['first_name'])){
    $first_name = test_input($_POST['first_name']);
}
if(isset($_POST['last_name'])){
    $last_name = test_input($_POST['last_name']);
}
if(isset($_POST['first_last_name'])){
    $first_last_name = test_input($_POST['first_last_name']);
}
if(isset($_POST['city_state_zip'])){
    $city_state_zip = test_input($_POST['city_state_zip']);
}
if(isset($_POST['phone_fax'])){
    $phone_fax = test_input($_POST['phone_fax']);
}
if(isset($_POST['e_mail'])){
    $e_mail = test_input($_POST['e_mail']);
}
if(isset($_POST['date_month'])){
    $date_month = test_input($_POST['date_month']);
}
if(isset($_POST['date_date'])){
    $date_date = test_input($_POST['date_date']);
}
if(isset($_POST['date_year'])){
    $date_year = test_input($_POST['date_year']);
}
if(isset($_POST['cruise_ship'])){
    $cruise_ship = test_input($_POST['cruise_ship']);
}
if(isset($_POST['speaking_language'])){
    $speaking_language = test_input($_POST['speaking_language']);
}
if(isset($_POST['party_num'])){
    $party_num = test_input($_POST['party_num']);
}
if(isset( $_POST['comments'])){
    $comments = test_input($_POST['comments']);
}

function passed(){

    // if(isset($_POST['meli_tria'])){
    //     $meli_tria_passed = false;
    // } else {
    //     $meli_tria_passed = true;
    // }
    // if ($_POST['meli_ena'] == '' && $_POST['meli_dio'] == '' && $meli_tria_passed){
    //     return true;
    // } else {
    //     return false;
    // }

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

$msg = "Details:\r\n\n";

$msg .= "From: $from_page\r\n";

if ($first_last_name == '') {
    $msg .= "First Name: $first_name\r\n";
    $msg .= "Last Name: $last_name\r\n";
} else {
    $msg .= "Name: $first_last_name\r\n";
}
$msg .= "City/State/Country: $city_state_zip\r\n";
$msg .= "Phone/Fax: $phone_fax\r\n";
$msg .= "E-mail: $e_mail\r\n";
$msg .= "Shore Excursion Date: $date_month - $date_date - $date_year\r\n";
$msg .= "Cruise Ship: $cruise_ship\r\n";
$msg .= "Language: $speaking_language\r\n";
$msg .= "Number in party: $party_num\r\n";
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
    if (passed()) {
        echo 'passed!';
    } else {
        echo 'failed!';
    }
    print_r($msg);
    die();
}

$headers = "Content-Type: text/html; charset=UTF-8";

// include_once('_keys.php');

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

if(passed_recaptcha() && $mail->send()){
    // Email has sent successfully, echo a success page.

    header('Location: ' . $return_to . '?contact-form-sent=success');

} else {
    header('Location: '. $return_to . '?contact-form-sent=fail');
}

?>