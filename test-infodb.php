<?php

include_once 'geo.php';

$ipAddress = $api_result['ip'];
$countryCode = $api_result['country_code'];
$countryName = $api_result['country_name'];
$regionName = $api_result['region_name'];
$cityName = $api_result['city'];
$zipCode = $api_result['zip'];

$msg = "";

$msg .= "Ip Address: $ipAddress\r\n";
$msg .= "Country Code: $countryCode\r\n";
$msg .= "Country Name: $countryName\r\n";
$msg .= "Region Name: $regionName\r\n";
$msg .= "City Name: $cityName\r\n";
$msg .= "Zip Code: $zipCode\r\n";

echo $msg;