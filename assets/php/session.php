<?php

session_start();
require_once 'auth.php';
$curser=new Auth();

if(!isset($_SESSION['user'])){
    header('location:index.php');
    die;
}

$cemail=$_SESSION['user'];

$data=$curser->currentUser($cemail);

$cid=$data['id'];
$cpass=$data['password'];
$cname=$data['name'];
$cphone=$data['phone'];
$cgender=$data['gender'];
$dob=$data['dob'];
$cphoto=$data['photo'];
$created=$data['created_at'];

$reg_on = date('d M Y', strtotime($created));

$verified=$data['verified'];

$fname=strtok($cname," ");

if($verified == 0){
    $verified = 'Not Verified!';
}else{
    $verified ='Verified!';
}

?>