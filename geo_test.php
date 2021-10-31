<?php
// set IP address
$ip = $_SERVER['REMOTE_ADDR'];
if ($ip == '::1') {
    $ip = '212.251.18.65';
}
$url = 'http://ip-api.com/json/' .$ip. '?fields=status,message,country,countryCode,region,regionName,city,zip,query';

$json = file_get_contents($url);
$api_result = json_decode($json, TRUE);
