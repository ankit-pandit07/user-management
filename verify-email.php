<?php

require_once 'assets/php/session.php';

if(isset($_GET['email'])){
    $email = $_GET['email'];

    $curser->verify_email($email);
    header('location:profile.php');
    exit();
}else{
    header('location:index.php');
    exit();
}

?>