<?php
include('../Connect.php');
$sql="select * from pages where link='$link'";
$query=mysqli_query($Conn, $sql);

while($row=mysqli_fetch_array($query, MYSQLI_ASSOC)){
echo $row['Page_Contents'];
    
    
}

include('Footer.php');
?>