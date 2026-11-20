<?php 

include('bootstrap.php');
include('Connect.php');
?>

<style>
    
 .hidden{
     display: none;
 }
    @media screen and (max-width: 600px){
        
        .Tracking_Form{
          
          padding: 20px;
          margin-top: 30px;
      }  
      
      .header{
          
          margin-top: 3cm;
      }  
    }
    
    
    @media screen and (min-width: 600px){
        .col-lg-4{
            border-right: 2px solid #eee;
            margin-top: 20px;
        }
      .Tracking_Form, .header{
          
          margin-top: 3cm;
      }  
 
    }
    .form-group{
        margin-bottom: 20px;
    }
</style>
<?php include('Header.php'); ?>

<div class="container">
    
  
    <div class="row">
        
            <div class="col-lg-4">
                <div class="header" align="center">
                    <h1 class="h2">Consignment Entry/Shippment Form</h1><small class="text-muted lead">Use the form below to make an order to ship your consignment to any destination</small></div>
                
                <div class="form-group Tracking_Form panel">
                    <form id="MyForm" enctype="multipart/form-data">
                     <div class="form-group"><label>Product Name</label><input type="text" class="form-control input-lg Product_Name" placeholder="Product Name" name="Product_Name"/></div>
                     <div class="form-group"><label>Product Image</label><input type="file" name="Product_Image" class="form-control input-lg Product_Image"/></div>
                     <div class="form-group"><label>Description</label><textarea type="text" class="form-control Description" placeholder="Description"name="Product_Description"></textarea></div>
                     <div class="form-group"><label>Weight</label><input type="text" class="form-control input-lg Weight" placeholder="Weight" name="Weight"/></div>
                     <div class="form-group"><label>Distance</label><input type="text" class="form-control input-lg Distance" placeholder="2000km" name="Distance"/><small class="text-muted lead">Please specify the distance in metre,miles or kilometre</small></div>
                     <br>
                        <h2>Sender's Info</h2>
                     <hr>
                 
                    <div class="form-group"><label>Sender's Name</label><input type="text" class="form-control input-lg Sender_Name" placeholder="Sender's Name" name="Sender_Name"/></div>
                    <div class="form-group"><label>Sender's Email</label><input type="text" class="form-control input-lg Sender_Email" placeholder="Sender's Email" name="Sender_Email"/></div>
                    <div class="form-group"><label>Sender's Phone</label><input type="text" class="form-control input-lg Sender_Phone" placeholder="Sender's Phone" name="Sender_Phone"/></div>
                    <div class="form-group"><label>Sender's Address</label><input type="text" class="form-control input-lg Sender_Address" placeholder="Sender's Address" name="Sender_Address"/></div>
                    
                    <br>
                     <h2>Receiver's Info</h2>
                    <hr>
                   
                    <div class="form-group"><label>Receiver's Name</label><input type="text" class="form-control input-lg Sender_Name" placeholder="Receiver's Name" name="Receiver_Name"/></div>
                    <div class="form-group"><label>Receiver's Email</label><input type="text" class="form-control input-lg Receiver_Email" placeholder="Receiver's Email" name="Receiver_Email"/></div>
                    <div class="form-group"><label>Receiver's Phone</label><input type="text" class="form-control input-lg Receiver_Phone" placeholder="Receiver's Phone" name="Receiver_Phone"/></div>
                    <div class="form-group"><label>Receiver's Address</label><input type="text" class="form-control input-lg Receiver_Address" placeholder="Receiver's Address" name="Receiver_Address"/></div>
                    <div class="form-group"><label>Receiver's Zipcode</label><input type="text" class="form-control input-lg Receiver_Zipcode" placeholder="Receivers Zipcode" name="Receiver_Zipcode"/></div>
                    <div class="form-group"><label>Mail Type</label><br>
                    
                    <input type="radio" value="Manual"class=" Manual_Main" name="mail"/> <label> Manual Mail</label><br>
                    <input type="radio" value="Automatic" class=" Automatic_Mail" name="mail"/> <label> Automatic Mail</label>
                    </div>
                    <div class="mailBox form-group hidden">
                        <textarea class="form-control MailContent" placeholder="Write mail content"></textarea>
                        
                    </div>
                    
                    
                    
                    <div class="FieldStatus"></div>
                    
                    <br>
                     <h2>Charges/Billing</h2>
                    <hr>
                    <div class="form-group"><label>Amount</label><input type="number" class="form-control input-lg Amount" placeholder="Amount" name="Amount"/></div>
                    <div class="form-group"><label>Estimated Delivery Date</label><input type="date" class="form-control input-lg Estimated_Delivery" placeholder="Delivery Date" name="Estimated_Delivery"/></div>
                    <div class="form-group" align="center"><button type="submit" class="btn btn-primary btn-lg Track">Submit Application</button></div>
                    </form>
                    
                </div>
            </div>
            
            <div class="col-lg-8">
                
                <div class="header" align="center">
                    <h1 class="h2">Consignment(s)</h1><small class="text-muted lead">Below is a list of consignment(s) on our delivery records</small></div>
                    
                    <?php 
                    
                    $sql="select * from Product";
                    $query=mysqli_query($Conn, $sql);
                    
                    $count=mysqli_num_rows($query);
                    if($count>0){
                        $sn=0;
                        $output="<div class='table-responsive'>
                        
                        <table class='table table-bordered table-striped'>
                        <thead>
                        <th>S/No</th>
                        <th>Tracking ID</th>                       
                        <th>Product Name</th>
                        <th>Senders Name</th>
                        <th>Receivers Name</th>
                        <th>Action</th>
                        <th>Query</th>

                        </thead>";
                        while($row=mysqli_fetch_array($query, MYSQLI_ASSOC)){
                            
                            $id=$row['id'];
                            $sn++;
                          $output.="
                          <tr><td>".$sn."</td><td>".$row['Product_id']."</td><td>".$row['Product_Name']."</td><td>".$row['Sender_Name']."</td><td>".$row['Receiver_Name']."</td><td><button class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#staticBackdrop".$id."'>Edit</button></td><td><button class='btn btn-sm btn-warning SendMail' id='".$row['id']."'>Send Mail</button></td><td><button class='btn btn-sm btn-danger DeleteCons' id='".$row['id']."'>Delete </button></td></tr>";
                          
                          echo '<div class="modal fade" id="staticBackdrop'.$id.'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel'.$id.'">'.$row['Product_id']." - ".$row['Product_Name'].'</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group"><img style="width: 100%; height: 300px" src="'.$row['Product_Image'].'" class="img-thumbnail"/></div>
                     <div class="form-group"><label>Product Name</label><input type="text" value="'.$row['Product_Name'].'" class="form-control input-lg Product_Name" placeholder="Product Name" name="Product_Name" disabled/></div>
                     <div class="form-group"><label>Description</label><textarea type="text" class="form-control Description" placeholder="Description"name="Product_Description" disabled>'.$row['Description'].'</textarea></div>
                     <div class="form-group"><label>Weight</label><input type="text" value="'.$row['Weight'].'" class="form-control input-lg Weight" placeholder="Weight" name="Weight" disabled/></div>
                     
                     <br>
                        <h2>Senders Info</h2>
                     <hr>
                 
                    <div class="form-group"><label>Senders Name</label><input type="text" value="'.$row['Sender_Name'].'"class="form-control input-lg Sender_Name" placeholder="Senders Name" name="Sender_Name" disabled/></div>
                    <div class="form-group"><label>Senders Email</label><input type="text" value="'.$row['Sender_Email'].'"class="form-control input-lg Sender_Email" placeholder="Senders Email" name="Sender_Email" disabled/></div>
                    <div class="form-group"><label>Senders Phone</label><input type="text" class="form-control input-lg Sender_Phone" placeholder="Senders Phone" value="'.$row['Sender_Phone'].'"name="Sender_Phone" disabled/></div>
                    <div class="form-group"><label>Senders Address</label><input type="text" class="form-control input-lg Sender_Address" placeholder="Senders Address" value="'.$row['Sender_Address'].'"name="Sender_Address" disabled/></div>
                    
                    <br>
                     <h2>Receivers Info</h2>
                    <hr>
                   
                    <div class="form-group"><label>Receivers Name</label><input type="text" value="'.$row['Receiver_Name'].'" class="form-control  input-lg Sender_Name" placeholder="Receivers Name" name="Receiver_Name" disabled/></div>
                    <div class="form-group"><label>Receivers Email</label><input type="text" value="'.$row['Receiver_Email'].'" class="form-control input-lg Receiver_Email'.$row['id'].'" placeholder="Receivers Email" name="Receiver_Email" disabled/></div>
                    <div class="form-group"><label>Receivers Phone</label><input type="text"  value="'.$row['Receiver_Phone'].'"class="form-control input-lg Receiver_Phone" placeholder="Receivers Phone" name="Receiver_Phone" disabled/></div>
                    <div class="form-group"><label>Receivers Address</label><input type="text" value="'.$row['Receiver_Address'].'"class="form-control input-lg Receiver_Address" placeholder="Receivers Address" name="Receiver_Address" disabled/></div>
                    
                    <div class="form-group"><label>Receivers Zipcode</label><input type="text" value="'.$row['Receiver_Zipcode'].'"class="form-control input-lg Receiver_Zipcode" placeholder="Receivers Zipcode" name="Receiver_Zipcode" disabled/></div>
                    
                    <br>
                     <h2>Current Location Info</h2>
                    <hr>
                    <div class="form-group"><label>Current Location</label><input type="text" value="'.$row['Current_Location'].'"class="form-control input-lg Current_Location'.$row['id'].'" placeholder="Type an Address" value="'.$row['Current_Location'].'"name="Current_Location"/></div>
                    <div class="form-group"><label>Distance</label><input type="text" class="form-control input-lg Distance" placeholder="2000km" value="'.$row['Distance'].'" name="Distance'.$row['id'].'"/></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button Tracking_id="'.$row['Product_id'].'" id="'.$row['id'].'" type="button" class="btn btn-primary Update">Update</button>
      </div>
    </div>
  </div>
</div>';
                            
                        }
                        $output.="</table></div>";
                        echo $output;
                    
                    }else{
                        
                        
                    }
                    
                    
                    ?>
            </div>
    

    </div>
</div><br>


<?php include('Footer.php'); ?>



<script>

    



$(document).on('click', '.SendMail', function(){
    
    var id=$(this).attr('id');
    var Receiver=$('.Receiver_Email'+id).val();
    window.location.href="ComposeMail.php?Email="+Receiver;
})

$(document).on('click','.DeleteCons', function(){
    
    var id=$(this).attr('id');
    $.ajax({
        url:'DeleteCons.php',
        method:'POST',
        data:{id:id},
        success:function(data){
            if(data==1){
                alert("Consignment Deleted");
                location.reload();
            }else{
                alert("Error Somewhere");
            }
        }
    })
})
$(document).on('click', '.Update', function(){
var id=$(this).attr('id');
var Tracking_id=$(this).attr('Tracking_id');
var Distance=$('.Distance'+id).val();
var Current_Location=$('.Current_Location'+id).val();
$.ajax({
    url:'updateCurrentLocation.php',
    method:'POST',
    data:{id:id,Distance:Distance, Current_Location:Current_Location},
    success:function(data){
        if(data==1){
            alert('Current Location Updated');
            location.reload();
            var SendTo=$('.Receiver_Email'+id).val();
                    var Subject="Consignment Location Alert";
                 
                    
                    $.ajax({
                        url:"SendMail.php",
                        method:'POST',
                        data:{SendTo:SendTo, Subject:Subject, htmlcontent:htmlcontent},
                        success:function(data){
                            
                        }
                    })
            
        }else{
            alert('Error Somewhere');
        }
    }
})
})
    $('#MyForm').submit(function(){
        
        var form=$(this);
        var formData=new FormData($(this)[0]);
        $.ajax({
            url:'Process.php',
            method:'POST',
            data:formData,
            enctype:'multipart/form-data',
            dataType:'json',
            cache:false,
            contentType:false,
            processData:false,
            async:false,
            success:function(response){
                if(response>0){
                    alert("Shippment booked successfully");
                    location.reload();
                    var Tracking_id=response;
                    var SendTo=$('.Receiver_Email').val();
                    var Receiver_Address=$('.Receiver_Address').val();
                    var Subject="Consignment Booked Successfully";
                   
                    
                var htmlcontent="We are pleased to inform you that your consignment with Tracking ID: <b>"+Tracking_id+"</b> has been successfully booked with Express Cargo Delivery. Your shipment details are as follows:<br><br>Consignment Number: ["+Tracking_id+"]<br>Booking Date: [Date]<br>Delivery Address: ["+Receiver_Address+"]<br><br>Our team is committed to ensuring the safe and timely delivery of your shipment to its destination. We understand the importance of your consignment, and we take every precaution to provide you with a reliable and efficient service.<br><br>Here's what you can expect from Express Cargo Delivery:<br><br>Professional Handling: Your consignment will be handled with the utmost care by our experienced team of logistics experts.<br>Real-Time Tracking: You can track the progress of your shipment in real-time using our online tracking system. We will provide you with the tracking details shortly.<br>Timely Updates: We will keep you informed throughout the shipping process, from pickup to delivery, ensuring complete transparency.<br>Delivery Confirmation: Once your shipment reaches its destination, we will send you a delivery confirmation notification.<br>Customer Support: If you have any questions or need assistance, our dedicated customer support team is here to help. You can reach us at [support@express-cargodelivery.org].<br>We want to express our gratitude for choosing Express Cargo Delivery as your shipping partner. We are confident that your consignment will reach its destination safely and on time.<br>If you have any specific requirements or if there are any special instructions related to your shipment, please do not hesitate to reach out to us.<br>Thank you for entrusting Express Cargo Delivery with your shipment. We look forward to serving you with excellence.<br><b>Warm regards.";
                    $.ajax({
                        url:"SendMail.php",
                        method:'POST',
                        data:{SendTo:SendTo, Subject:Subject, htmlcontent:htmlcontent},
                        success:function(data){
                            
                        }
                    })
                }else if(response==0){
                alert("Error Somewhere");
                }else if(response==3){
                    alert("Could not upload file");
                }
            }
        })
        return false;
    })
</script>