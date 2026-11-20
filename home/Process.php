<?php include('Connect.php');
$valid = array('success' => false, 'messages' => array());

$type = explode('.', $_FILES['Product_Image']['name']);
	$type = $type[count($type) - 1];
	$Product_Image = 'Product_Images/'.uniqid(rand()) . '.' . $type;

$Tracking_id=rand(20,99999999);
$Product_Name=mysqli_real_escape_string($Conn,$_POST['Product_Name']);
$Description=mysqli_real_escape_string($Conn, $_POST['Product_Description']);
$Weight=mysqli_real_escape_string($Conn,$_POST['Weight']);
$Distance=mysqli_real_escape_string($Conn,$_POST['Distance']);
$Amount=mysqli_real_escape_string($Conn,$_POST['Amount']);
$Delivery_Date=mysqli_real_escape_string($Conn, $_POST['Estimated_Delivery']);


$Sender_Name=mysqli_real_escape_string($Conn,$_POST['Sender_Name']);
$Sender_Email=mysqli_real_escape_string($Conn,$_POST['Sender_Email']);
$Sender_Phone=mysqli_real_escape_string($Conn,$_POST['Sender_Phone']);
$Sender_Address=mysqli_real_escape_string($Conn, $_POST['Sender_Address']);

$Receiver_Name=mysqli_real_escape_string($Conn,$_POST['Receiver_Name']);
$Receiver_Email=mysqli_real_escape_string($Conn,$_POST['Receiver_Email']);
$Receiver_Phone=mysqli_real_escape_string($Conn,$_POST['Receiver_Phone']);
$Receiver_Address=mysqli_real_escape_string($Conn,$_POST['Receiver_Address']);
$Receiver_Zipcode=mysqli_real_escape_string($Conn,$_POST['Receiver_Zipcode']);

$Date= date("l d F Y");
$Amount=mysqli_real_escape_string($Conn,$_POST['Amount']);
$Status=0;



			if(move_uploaded_file($_FILES['Product_Image']['tmp_name'], $Product_Image)){
				// insert into database
				$sql="INSERT INTO Product (Product_id, Product_Name, Description, Product_Image, Weight, Current_Location, Distance, Receiver_Name, Receiver_Phone, Receiver_Email, Receiver_Address, Receiver_Zipcode, Sender_Name, Sender_Phone, Sender_Email, Sender_Address, Date, Delivery_Date, Amount, Status)values('$Tracking_id', '$Product_Name', '$Description', '$Product_Image','$Weight', '$Sender_Address', '$Distance', '$Receiver_Name', '$Receiver_Phone', '$Receiver_Email', '$Receiver_Address', '$Receiver_Zipcode', '$Sender_Name', '$Sender_Phone', '$Sender_Email', '$Sender_Address', '$Date', '$Delivery_Date', '$Amount', '$Status')";
$query=mysqli_query($Conn, $sql);
$insertedid=mysqli_insert_id($Conn);
				if($query) {
				    $sql2="select * from Product where id='$insertedid'";
				    $query2=mysqli_query($Conn, $sql2);
				    $row=mysqli_fetch_array($query2, MYSQLI_ASSOC);
				    $Tracking_id=$row['Product_id'];
				    echo $Tracking_id;

				}else {
					echo 0;
	
				}

			}else {
				echo 3;
			
			}

		

?>