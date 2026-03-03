<?php 

session_start();

// Se não existir o id do admin no session, volta pro login

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

?>