<?php
// set IP address and API access key
$ip = $_SERVER['REMOTE_ADDR'];
if ($ip == '::1') {
    $ip = '193.92.63.92';
}
$access_key = include '_geo_api_key.php';

// Initialize CURL:
$ch = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$access_key.'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
$json = curl_exec($ch);
curl_close($ch);

// Decode JSON response:
$api_result = json_decode($json, true);

// Output the "capital" object inside "location"
//echo $api_result['location']['capital'];

