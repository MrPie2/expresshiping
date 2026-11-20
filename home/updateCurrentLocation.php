<?php
include('Connect.php');

$id=$_POST['id'];
$Current_Location=$_POST['Current_Location'];
$Distance=$_POST['Distance'];
$sql="update Product SET Current_Location='$Current_Location', Distance='$Distance' where id='$id'";
$query=mysqli_query($Conn, $sql);
if($query){
    echo 1;
}else{
    echo 0;
}



?>