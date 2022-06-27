<?php
session_start();
var_dump($_POST['keyword']);
$url = 'http://localhost:3002/cari';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);

var_dump($result);