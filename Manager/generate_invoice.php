<?php include('../Connect.php');
$product_id=$_POST['id'];
$invoice_id="express_inv_".rand(20,99999999);
$entry_date= date("l d F Y");
$due_date= date("l d F Y", strtotime('+7 days'));
$amount=$_POST['amount'];
$status=0;
				// insert into database
				$sql="INSERT INTO invoice (invoice_id, product_id, amount, entry_date, due_date, status)values('$invoice_id', '$product_id', '$amount', '$entry_date', '$due_date', '$status')";
                    $query=mysqli_query($Conn, $sql);
				if($query){
				    $insertedid=mysqli_insert_id($Conn);
				    $sql2="select * from invoice where id='$insertedid'";
				    $query2=mysqli_query($Conn, $sql2);
				    $row=mysqli_fetch_array($query2, MYSQLI_ASSOC);
				    $Tracking_id=$row['product_id'];
				    $data=['statuss'=>1, 'invoice'=>$row['invoice_id'], 'product_id'=>$row['product_id']];
				    
				echo json_encode($data);
				} 
				else{
				echo 0;
				}

		

?>