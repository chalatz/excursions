<?php

$debug = false;

if ($debug == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

include 'class.IPInfoDB.php';

$infodb_api_key = include '_infodb_key.php';

$ipinfodb = new IPInfoDB($infodb_api_key);
$results = $ipinfodb->getCity($_SERVER['REMOTE_ADDR']);

$apots = include '_apots.php';

$return_to = '';
$from_page = '';
$first_last_name = '';
$e_mail = '';
$comments = '';
$the_page = '';
$apot = '';

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
if(isset( $_POST['the_page'])){
    $the_page = $_POST['the_page'];
}
if(isset( $_POST['apot'])){
    $apot = trim($_POST['apot']);
}

if(array_key_exists($the_page, $apots)) {
    $the_apot = $apots[$the_page];
} else {
    $the_apot = 5;
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
    if(isset($_POST['apot']) && $_POST['apot'] == ''){
        return false;
    }

    return true;
}

function passed_nocaptcha($apot, $the_apot){

    if (passed()) {
        if ($apot == $the_apot) {
            return true;
        } else {
            return false;
        }
    }
}

// if ($debug == true) {
//     echo "<pre>";
//     print_r($_POST);
//     echo "</pre>";
//     echo "<br>";
//     echo "the apot: " . $the_apot;
//     echo "<br>";
//     if (passed_nocaptcha($apot, $the_apot)) {
//         echo '<br> Passed no captcha!!<br>';
//     }
//     if (validated()) {
//         if (passed()) {
//             echo 'passed!\n';
//         } else {
//             echo 'failed!\n';
//         }
//     } else {
//         echo 'validation error\n';
//     }
//     print_r($msg);
//     die();
// }

if ($debug == true) {
    if (validated()) {
        if(passed_nocaptcha($apot, $the_apot)){
            // Email has sent successfully, echo a success page.

            header('Location: ' . $return_to . '?contact-form-sent=success');

        } else {
            header('Location: '. $return_to . '?contact-form-sent=fail-mobile');
        }
    } else {
        header('Location: '. $return_to . '?contact-form-sent=validation-error');
    }
} else {
    if (validated()) {
        if(passed_nocaptcha($apot, $the_apot) && mail($address, $e_subject, $msg, "From: $e_mail\r\nReply-To: $e_mail\r\nReturn-Path: $e_mail\r\nContent-Type: text/plain; charset=UTF-8\r\n")){
            // Email has sent successfully, echo a success page.

            header('Location: ' . $return_to . '?contact-form-sent=success');

        } else {
            header('Location: '. $return_to . '?contact-form-sent=fail-mobile');
        }
    } else {
        header('Location: '. $return_to . '?contact-form-sent=validation-error');
    }
}



?>