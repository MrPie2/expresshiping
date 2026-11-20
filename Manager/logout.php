<?php session_start();
if(isset($_SESSION['User_id'])){
    session_destroy();
    header('location:login.php');
}

?>