<?php session_start();
include('../Connect.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

   
        // Check credentials in the database
        $stmt = "SELECT * FROM admin WHERE username='$username' and password='$password'";
        $query=mysqli_query($Conn, $stmt);
        $row=mysqli_fetch_array($query, MYSQLI_ASSOC);
        $user_id=$row['id'];

        if ($user_id>0) {
            echo 1;

            $_SESSION['User_id']=$user_id;
        } else {
            echo 0;
        }
    
}