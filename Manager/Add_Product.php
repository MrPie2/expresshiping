<?php 

include('bootstrap.php');
include('../Connect.php');
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
          
          margin-top: 1cm;
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
        
            <div class="col-lg-12">
                <div class="header" align="center">
                    <h1 class="h2">Consignment Entry/Shippment Form</h1><small class="text-muted lead">Use the form below to make an order to ship your consignment to any destination</small></div>
                
                <div class="form-group Tracking_Form panel">
                    <form id="MyForm" enctype="multipart/form-data">
                     <div class="form-group"><label>Product Name</label><input type="text" class="form-control input-lg Product_Name" placeholder="Product Name" name="Product_Name"/></div>
                     
                     
                     <div class="form-group"><label>Image/Video 1</label><input type="file" name="Product_Image1" class="form-control input-lg Product_Image2"/></div>
                     
                      <div class="form-group"><label>Image/Video 2</label><input type="file" name="Product_Image2" class="form-control input-lg Product_Image2"/></div>
                      
                       <div class="form-group"><label>Image/Video 3</label><input type="file" name="Product_Image3" class="form-control input-lg Product_Image3"/></div>
                       
                        <div class="form-group"><label>Image/Video 4</label><input type="file" name="Product_Image4" class="form-control input-lg Product_Image4"/></div>
                       
                       
                       
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
                    
                      <div class="form-group">
                          <label>Delivery Medium</label>
                        <select class="form-control delivery_medium" name="delivery_medium">
                            <option value="ocean">Choose...</option>

                            <option value="ocean">Ocean Freight</option>
                            <option value="air">Air Freight</option>
                            <option value="road">Road Freight</option>
                        </select>
                        
                    </div>
                    
                     <div class="form-group">
                          <label>Take off Location (Latitude and Longitude)</label>
        <input type="text" class="form-control" name="t_latitude" placeholder="latitude e.g 6.2800"/>
         <input type="text" class="form-control" name="t_longitude" placeholder="longitude e.g 4.1200"/>
                    </div>
                    
                    
                         <div class="form-group">
                          <label>Destination (Latitude and Longitude)</label>
                         <input type="text" class="form-control" name="d_latitude" placeholder="latitude e.g 5.1100"/>
                            <input type="text" name="d_longitude" class="form-control" placeholder="longitude e.g 3.3300"/>
                        
                    </div>
                    
                    
                    
                    <div class="FieldStatus"></div>
                    
                    <br>
                     <h2>Charges/Billing</h2>
                    <hr>
                    <div class="form-group"><label>Amount</label><input type="number" class="form-control input-lg Amount" placeholder="Amount" name="Amount"/></div>
                    <div class="form-group" align="center"><button type="submit" class="btn btn-primary btn-lg Track">Submit Application</button></div>
                    </form>
                    
                </div>
            </div>
            
     
    

    </div>
</div><br>


<?php include('Footer.php'); ?>



<script>
$('input[type="radio"]').change(function(){
    
if( $(this).is(":checked") ){ // check if the radio is checked
            window.mailContent = $(this).val(); // retrieve the value
if(mailContent=="Manual"){
                $('.mailBox').toggleClass('hidden');
               
}else{
                $('.mailBox').toggleClass('hidden');

              
}
        }  
})

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
                    var Support="<a style='padding: 10px; text-decoration: none; color: white; background: #203370' href='https://widget-page.smartsupp.com/widget/3cdee84b5549494986e3f2d32837ba6eee376b07'>Chat Live Support</a>";
                    
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
    $('#MyForm').unbind('submit').bind('submit', function(){
        
        var form=$(this);
        var formData=new FormData($(this)[0]);
        $.ajax({
            url:'Process.php',
            method:'POST',
            data:formData,
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
                    var Subject="Consignment Booked Successfully";
                    var Support="<a style='padding: 10px; text-decoration: none; color: white; background: #203370' href='href='https://widget-page.smartsupp.com/widget/3cdee84b5549494986e3f2d32837ba6eee376b07'>Chat Live Support</a>";
                    var track_link="<a style='padding: 10px; background: #0b8a78; color: white; ' href='https://expresshiping.com/home/track-package.php'>Track your Package</a>";
                    if(mailContent=="Manual"){
                         var manualcontent=$('.MailContent').val();

var htmlcontent="Your consignment with Tracking ID: <b>"+Tracking_id+"</b> has successfully been lodged on our system (Expresshiping & Logistics). <br><br>"+manualcontent+".<br><br> Track your package using the link below<br>"+track_link+"<br><br> For more information about this delivery, chat with our Live Support Team by clicking the link below.<br><br><br>"+Support+"<br> Thank you. <br><br> Parcelsend Logistics.";
                    }else{
                          var htmlcontent="Your consignment with Tracking ID: <b>"+Tracking_id+"</b> has successfully been lodged on our system (Expresshiping & Logistics). <br><br>This delivery contract was initiated by:<br><br> Name: "+$('.Sender_Name').val()+"<br>Email: "+$('.Sender_Email').val()+"<br>Address: "+$('.Sender_Address').val()+"<br><br>Track your package using the link below<br>"+track_link+"<br><br> For more information about this delivery, chat with our live support team by clicking the link below"+Support+"<br> Thank you. <br><br> Parcelsend Logistics.";
                    }
                    $.ajax({
                        url:"SendMail.php",
                        method:'POST',
                        data:{SendTo:SendTo, Subject:Subject, htmlcontent:htmlcontent},
                        success:function(data){
                            
                        }
                    })
                }else if(response==0){
                alert("Error Somewhere");
                }
            }
        })
    })
</script>