<?php include('../Connect.php');
$valid = array('success' => false, 'messages' => array());

$type1 = explode('.', $_FILES['Product_Image1']['name']);
$type1 = $type1[count($type1) - 1];

	
		$Product_Image1 = 'Product_Images/'.uniqid(rand()) . '.' . $type1;

	
	if($type2 !=""){
	    		$type2 = explode('.', $_FILES['Product_Image2']['name']);
	$type2 = $type2[count($type2) - 1];
		$Product_Image2 = 'Product_Images/'.uniqid(rand()) . '.' . $type2;
	}else{
	    $Product_Image2="";
	}
	
	
		if($type3 !=""){
	    		$type3 = explode('.', $_FILES['Product_Image3']['name']);
	$type3 = $type3[count($type3) - 1];
		$Product_Image3 = 'Product_Images/'.uniqid(rand()) . '.' . $type3;
	}else{
	    $Product_Image3="";
	}
	
		if($type4 !=""){
		$type4 = explode('.', $_FILES['Product_Image4']['name']);
	$type4 = $type4[count($type4) - 1];
		$Product_Image4 = 'Product_Images/'.uniqid(rand()) . '.' . $type4;
	    	
	}else{
	    $Product_Image4="";
	}
	

	$Product_Image=$Product_Image1.",".$Product_Image2.",".$Product_Image3.",".$Product_Image4;
	
$delivery_medium=$_POST['delivery_medium'];
$Tracking_id=rand(20,99999999);
$Product_Name=$_POST['Product_Name'];
$Description=$_POST['Product_Description'];
$Weight=$_POST['Weight'];
$Distance=$_POST['Distance'];
$Amount=$_POST['Amount'];

$start=$_POST['t_latitude'].",".$_POST['t_longitude'];
$destination=$_POST['d_latitude'].",".$_POST['d_longitude'];


$Sender_Name=$_POST['Sender_Name'];
$Sender_Email=$_POST['Sender_Email'];
$Sender_Phone=$_POST['Sender_Phone'];
$Sender_Address=$_POST['Sender_Address'];

$Receiver_Name=$_POST['Receiver_Name'];
$Receiver_Email=$_POST['Receiver_Email'];
$Receiver_Phone=$_POST['Receiver_Phone'];
$Receiver_Address=$_POST['Receiver_Address'];
$Receiver_Zipcode=$_POST['Receiver_Zipcode'];

$Date= date("l d F Y");
$Amount=$_POST['Amount'];
$Status=0;



			if(move_uploaded_file($_FILES['Product_Image1']['tmp_name'], $Product_Image1)) {
				// insert into database
				$sql="INSERT INTO Product (Product_id, Product_Name, Description, Product_Image, Weight, Current_Location, Distance, Receiver_Name, Receiver_Phone, Receiver_Email, Receiver_Address, Receiver_Zipcode, Sender_Name, Sender_Phone, Sender_Email, Sender_Address, Date, Amount, delivery_medium, start, destination, Status)values('$Tracking_id', '$Product_Name', '$Description', '$Product_Image','$Weight', '$Sender_Address', '$Distance', '$Receiver_Name', '$Receiver_Phone', '$Receiver_Email', '$Receiver_Address', '$Receiver_Zipcode', '$Sender_Name', '$Sender_Phone', '$Sender_Email', '$Sender_Address', '$Date', '$Amount', '$delivery_medium', '$start', '$destination', '$Status')";
$query=mysqli_query($Conn, $sql);
$insertedid=mysqli_insert_id($Conn);
				if($query) {
				    $sql2="select * from Product where id='$insertedid'";
				    $query2=mysqli_query($Conn, $sql2);
				    $row=mysqli_fetch_array($query2, MYSQLI_ASSOC);
				    $Tracking_id=$row['Product_id'];
				    echo $Tracking_id;

				} 
				else {
					echo 0;
	
				}

			}else {
				echo 3;
			
			}

		

?>