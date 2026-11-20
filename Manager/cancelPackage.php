<?php include('../Connect.php'); 

$id=$_POST['id'];
$status="1";

$sql="update Product set Status='$status' where id='$id'";
$query=mysqli_query($Conn, $sql);
if($query){
    echo 1;
}else{
    echo 0;
    echo mysqli_error($Conn);
}



?>