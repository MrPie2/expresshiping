<?php
include('Connect.php');
$id=$_POST['id'];
$sql="delete from Product where id='$id'";
$query=mysqli_query($Conn, $sql);
if($query){
    echo 1;
}else{
    echo 0;
}



?>