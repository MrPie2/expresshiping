<?php 

include('../Connect.php');
$Tracking_id=$_POST['TrackingText'];

$sql="select * from Product where Product_id='$Tracking_id'";
$query=mysqli_query($Conn, $sql);
$row=mysqli_fetch_array($query, MYSQLI_ASSOC);
if($query){
    if($row['Status']==0){
      $Status="This product in its way";  
    }
  $output='<div class="panel panel-primary">
  <div class="panel-heading">
  
  <h1 class="panel-title">Product id: '.$row['Product_id'].' - '.$row['Product_Name'].'</h1></div>
  <div class="panel-body">
  
                <div class="mapouter">
                <div class="gmap_canvas">
                <iframe width="100%" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q='.$row['Current_Location'].'&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                
                <a href="https://fmovies-online.net"></a><br><style>.mapouter{position:relative;text-align:right;height:500px;width:100%;}</style><a href="https://www.embedgooglemap.net">embed google map on website</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:100%;}</style></div></div>
                
                <div class="list-group"><li class="list-group-item"><p>Product Name: '.$row['Product_Name'].'</p></li>
                <li class="list-group-item"><p>Description: '.$row['Description'].'</p></li>
                <li class="list-group-item"><p>Weight: '.$row['Weight'].'</p></li>
                <li class="list-group-item"><p>Receivers Name: '.$row['Receiver_Name'].'</p></li>
                <li class="list-group-item"><p>Receivers Email: '.$row['Email'].'</p></li>
                <li class="list-group-item"><p>Distance: '.$row['Distance'].'</p></li>
                <li class="list-group-item"><p>Status: '.$Status.'</p></li>
                </div>
  
  </div>
  
 
 </div>';
echo $output;

}else{
    echo "<div class='alert alert-danger text-muted'>We could not track your product. Kindly chat our support team to track your product using other means</div>";
}


?>

