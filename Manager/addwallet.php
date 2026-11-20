<?php

include('../Connect.php');
$Product_id = $_POST['Product_id'];

$walletaddress = $_POST['walletaddress'];
$currency = $_POST['currency'];
$network = $_POST['network'];
$type="crypto";
$sql = "INSERT INTO payment_details
(Product_id, walletaddress, currency, type,  network)

VALUES

('$Product_id', '$walletaddress','$currency', '$type', '$network')";

$query = mysqli_query($Conn,$sql);

if($query){
    echo '
    <div class="alert alert-success">
        Wallet Added Successfully
    </div>
    ';

}else{

    echo '<div class="alert alert-danger">
        Failed To Save Wallet</div>
    ';

}

?>