<?php
session_start();
$url = 'https://nutech.creativibe.site/add';



if(!$_FILES['product_picture']['error']){
    $upload_file = $_FILES['product_picture'];

    $postfields["product_picture"] = curl_file_create(
        $upload_file['tmp_name'],
        $upload_file['type'],
        $upload_file['name']
    );
}

    $postfields['product_name'] = $_POST['product_name'];
    $postfields['product_stock'] = $_POST['product_stock'];
    $postfields['product_price'] = $_POST['product_price'];
    $postfields['product_sale'] = $_POST['product_sale'];


$ch = curl_init();
$headers = array("Content-Type:multipart/form-data");
curl_setopt_array($ch, array(
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POST => 1,
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => 1,
    CURLINFO_HEADER_OUT => 1,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_POSTFIELDS => $postfields
));

$result = curl_exec($ch);

curl_close ($ch);
$result = json_decode($result, true);
   

if ($result['status'] == 300) {
    header('location:index.php');
    $_SESSION['success_mg'] = $result['msg'];
    $_SESSION['status'] = $result['status'];
    die();
} else {
    header('location:index.php');
    $_SESSION['success_mg'] = $result['msg'];
    $_SESSION['status'] = $result['status'];
    die();
};
