<?php
session_start();
if (isset($_POST['product_id'])) {
  $url = 'https://nutech.creativibe.site/delete/'.$_POST['product_id'];
  $ch = curl_init();
  curl_setopt_array($ch, array(
      CURLOPT_CUSTOMREQUEST => "DELETE",
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => 1,

  ));

  $result = curl_exec($ch);

  curl_close ($ch);
  $result = json_decode($result, true);
    

  header('location:index.php');
  $_SESSION['success_mg'] = $result['msg'];
  $_SESSION['status'] = $result['status'];
  die();
 
} else {
  header('location:index.php');
}
