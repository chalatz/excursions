<?php

$debug = false;

if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

include 'class.IPInfoDB.php';

$infodb_api_key = include '_infodb_key.php';

$ipinfodb = new IPInfoDB($infodb_api_key);
$results = $ipinfodb->getCity($_SERVER['REMOTE_ADDR']);

$return_to = '';
$from_page = '';
$first_last_name = '';
$e_mail = '';
$comments = '';
$ipAddress = $results['ipAddress'];
$countryCode = $results['countryCode'];
$countryName = $results['countryName'];
$regionName = $results['regionName'];
$cityName = $results['cityName'];
$zipCode = $results['zipCode'];
$timeZone = $results['timeZone'];

if(isset($_POST['return_to'])){
    $return_to = $_POST['return_to'];
}
if(isset($_POST['from_page'])){
    $from_page = $_POST['from_page'];
}
if(isset($_POST['first_last_name'])){
    $first_last_name = $_POST['first_last_name'];
}
if(isset($_POST['e_mail'])){
    $e_mail = $_POST['e_mail'];
}
if(isset( $_POST['comments'])){
    $comments = $_POST['comments'];
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

$address = "request@rhodesshoreexcursions.com";

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
$msg .= "Time Zone: $timeZone\r\n";

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

if (validated()) {
    if(passed_recaptcha() && mail($address, $e_subject, $msg, "From: $e_mail\r\nReply-To: $e_mail\r\nReturn-Path: $e_mail\r\nContent-Type: text/plain; charset=UTF-8\r\n")){
        // Email has sent successfully, echo a success page.

        header('Location: ' . $return_to . '?contact-form-sent=success');

    } else {
        header('Location: '. $return_to . '?contact-form-sent=fail-mobile');
    }
} else {
    header('Location: '. $return_to . '?contact-form-sent=validation-error');
}


?>