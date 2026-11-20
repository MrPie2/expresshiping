<?php session_start();
if(isset($_SESSION['User_id'])){


}else{

header('location:login.php');

}
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
                            $receiver_email=$row['Receiver_Email'];
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
                    <div id="responsePackage"></div>
                    <div class="form-group"><label>Current Location</label><input type="text" value="'.$row['Current_Location'].'"class="form-control input-lg Current_Location'.$row['id'].'" placeholder="Type an Address" value="'.$row['Current_Location'].'"name="Current_Location"/></div>
                    <div class="form-group"><label>Distance</label><input type="text" class="form-control input-lg Distance" placeholder="2000km" value="'.$row['Distance'].'" name="Distance'.$row['id'].'"/></div>
                    
                    <div class="form-group"><label>Estimated Time</label><input type="text" value="'.$row['delivery_time'].'"class="form-control input-lg Current_Location'.$row['id'].'" placeholder="Estimated Delivery Time" value="'.$row['delivery_time'].'"name="delivery_time"/></div>
                      <div class="form-group">
                          <label>New Map Location (Latitude and Longitude)</label>
        <input type="text" class="form-control" name="t_latitude" placeholder="latitude e.g 6.2800"/>
         <input type="text" class="form-control" name="t_longitude" placeholder="longitude e.g 4.1200"/>
                    </div>
                    
                    <div class="form-group d-grid"><label>Update Package</label>
        <button Tracking_id="'.$row['Product_id'].'" id="'.$row['id'].'" type="button" class="btn btn-primary Update">Update</button></div>
        
                    <div class="form-group d-grid"><label>Cancel Package</label><button class="btn btn-danger cancelPackage" Tracking_id="'.$row['id'].'">Cancel Package</button></div>
                     <div class="form-group d-grid"><label>Reallocate Package</label><button class="btn btn-danger reassignPackage" Tracking_id="'.$row['id'].'">Reallocate Package</button></div>
                    
                    <div class="form-group d-grid"><label>Pause Package</label><button class="btn btn-success pausePackage" Tracking_id="'.$row['id'].'">Pause Package</button></div>
                    
                      
                    <div class="form-group d-grid"><label>Complete Delivery</label><button class="btn btn-success completePackage" Tracking_id="'.$row['id'].'">Delivery Complete Package</button></div>
                    <!--start of add crypto-->
                    <div class="card ">

                <div class="card-body">

                    <h3 class="mb-4 text-center">
                        Add Crypto Wallet
                    </h3>

                    <div class="mb-3">
                        <label class="form-label">
                            Wallet Address
                        </label>
                        <input type="text"
                               class="form-control walletaddress" placeholder="Wallet Address"
                               id="walletaddress'.$row['Product_id'].'">
                    </div>


                    <div class="mb-3">
                        <label class="form-label">
                            Currency
                        </label>

                        <select class="form-select currency" id="currency'.$row['Product_id'].'">
                            <option value="">Select Currency</option>
                            <option value="BTC">Bitcoin</option>
                            <option value="ETH">Ethereum</option>
                            <option value="USDT">USDT</option>
                            <option value="BNB">BNB</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Network
                        </label>

                        <input type="text"
                               class="form-control network" placeholder="Network"
                               id="network'.$row['Product_id'].'">
                    </div>


                    <button class="btn btn-dark w-100 saveWallet" Tracking_id="'.$row['Product_id'].'" id="saveWallet">
                        Save Wallet
                    </button>

                    <div class="mt-3 text-center" id="response'.$row['Product_id'].'"></div>

                </div>
            </div>
            <!---end of add crypto-->
            <div class="card">
            <div class="card-body">
                        <div class="mb-3 d-grid"><button class="btn btn-primary showInvoiceCont">Generate Billing Invoice</button></div>
                        
                        <div class="card d-none invoiceCont">
                        <div class="card-body">
                        <div class="mb-3 responseInvoice" id=" responseInvoice"></div>
                        <div class="mb-3">
                        <label>Billing Amount</label>
                        <input type="number" class="form-control input-lg inv_amount"/>
                        </div>
                        <div class="mb-3"><button class="btn btn-primary generateInvoice" Tracking_id="'.$row['Product_id'].'" Receiver_Email="'.$row['Receiver_Email'].'">Generate</button></div>
                        </div>
                        </div>

            </div>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
$(document).on('click', '.showInvoiceCont', function(){
    
    $('.invoiceCont').toggleClass("d-none");
})

$(document).on('click', '.generateInvoice', function(){
    var receiver_email=$(this).attr('Receiver_Email');
     var id=$(this).attr('Tracking_id');
     var amount=$('.inv_amount').val();
      $.ajax({
        url:"generate_invoice.php",
        type:"POST",
        data:{id:id, amount:amount},
        beforeSend:function(){
            $(".generateInvoice").html("Generating Invoice...");
        },
        success:function(response){
            var data=JSON.parse(response);
            if(data.statuss==1){
              $(".responseInvoice").html("Invoice Generated").addClass("alert alert-success");
              $(".generateInvoice").html("Invoice Generated");
              
                    var invoice_id=data.invoice;
                    var invlink="https://www.expresshiping.com/home/invoice.php?invoice_id="+invoice_id;
                    var SendTo=receiver_email;
                    var Subject="Delivery/Logistics Billing Invoice - Product ID: ["+data.product_id+"]";
                    var Support="<a style='padding: 10px; text-decoration: none; color: white; background: #203370' href='https://tawk.to/chat/6286942f7b967b11799045bf/1g3er5n2u'>Chat Live Support</a>";
                    
                    
var htmlcontent="Below is the billing invoice for your delivery packages with Tracking ID: <b>"+data.product_id+"</b> Proceed to make payment before due date so we can proceed with you package delivery. <br><br><a style='padding: 10px; ' href="+invlink+">Go to Invoice</a><br><br> For more information about this delivery, chat with our Live Support Team by clicking the link below.<br><br><br>"+Support+"<br> Thank you. <br><br> Expresshiping & Logistics.";
                    
                    $.ajax({
                        url:"SendMail.php",
                        method:'POST',
                        data:{SendTo:SendTo, Subject:Subject, htmlcontent:htmlcontent},
                        success:function(data){
                            
                        }
                    })
                    
                    setTimeout(function(){
                        
                        location.reload();
                    }, 3000)
            }
            
            
        }
})

})

$(".reassignPackage").click(function(){
       var id=$(this).attr('Tracking_id');
      $.ajax({
        url:"reallocatePackage.php",
        type:"POST",
        data:{id:id},
        beforeSend:function(){
            $(".reassignPackage").html("Reallocating Pagkage...");
        },

        success:function(data){
            $("#responsePackage").html(data);
            $(".reassignPackage").html("Package Reallocated");
        }
    });

})
$(".completePackage").click(function(){
       var id=$(this).attr('Tracking_id');
      $.ajax({
        url:"completeDelivery.php",
        type:"POST",
        data:{id:id},
        beforeSend:function(){
            $(".completePackage").html("Marking this delivery as complete...");
        },

        success:function(data){
            $("#responsePackage").html(data);
            $(".completePackage").html("Package Delivery");
        }
    });

})
$(".pausePackage").click(function(){
       var id=$(this).attr('Tracking_id');
      $.ajax({
        url:"pausePackage.php",
        type:"POST",
        data:{id:id},
        beforeSend:function(){
            $(".pausePackage").html("Pausing this Delivery...");
        },

        success:function(data){
            $("#responsePackage").html(data);
            $(".pausePackage").html("Delivery has been put on hold");
        }
    });

})
$(".cancelPackage").click(function(){
       var id=$(this).attr('Tracking_id');
      $.ajax({
        url:"cancelPackage.php",
        type:"POST",
        data:{id:id},
        beforeSend:function(){
            $(".cancelPackage").html("Terminating this Delivery...");
        },

        success:function(data){
            $("#responsePackage").html(data);
            $(".cancelPackage").html("Delivery Canceled");
        }
    });

})

$(document).on('click', '.saveWallet', function(){
        var Product_id=$(this).attr('Tracking_id');

    var walletaddress = $("#walletaddress"+Product_id).val();
    var currency = $("#currency"+Product_id).val();
    var network = $("#network"+Product_id).val();
    if(walletaddress == "" || currency == ""){
        $("#response"+Product_id).html('<div class="alert alert-danger">All required fields are needed</div>');
        return;
    }
    $.ajax({
        url:"addwallet.php",
        type:"POST",
        data:{Product_id:Product_id, walletaddress:walletaddress,currency:currency,network:network
        },
        beforeSend:function(){
            $("#saveWallet").html("Saving...");
        },

        success:function(data){
            $("#response"+Product_id).html(data);
            $("#saveWallet").html("Save Wallet");

            $("#walletaddress"+Product_id).val('');
            $("#currency"+Product_id).val('');
            $("#network"+Product_id).val('');
        }

    });

})


$('input[type="radio"]').change(function(){
    
if( $(this).is(":checked") ){ // check if the radio is checked
            var mailContent = $(this).val(); // retrieve the value
if(mailContent=="Manual"){
                $('.mailBox').toggleClass('hidden');
                var manualcontent=$('.MailContent').val();

var htmlcontent="Your consignment with Tracking ID: <b>"+Tracking_id+"</b> has successfully been lodged on our system (Parcelsend Logistics). <br><br>"+manualcontent+"<br><br> For more information about this delivery, chat with our Live Support Team by clicking the link below.<br><br><br>"+Support+"<br> Thank you. <br><br> Parcelsend.";
}else{
                $('.mailBox').toggleClass('hidden');

                var htmlcontent="Your consignment with Tracking ID: <b>"+Tracking_id+"</b> has successfully been lodged on our system (Parcelsend Logistics). <br><br>This delivery contract was initiated by:<br><br> Name: "+$('.Sender_Name').val()+"<br>Email: "+$('.Sender_Email').val()+"<br>Address: "+$('.Sender_Address').val()+"<br><br><br><br>Kindly pay up your shipment fee of <b>$"+$('.Amount').val()+"</b> to facilitate your shippment process. <br> We look forward to receive your payment.  Once that is done, a tracker will be assigned to your consignment which will notify you via email on the current location and delivery progress of your consignment pending its arrival<br><br> For more information about this delivery, chat with our Live Support Team by clicking the link below.<br><br><br>"+Support+"<br> Thank you. <br><br> Parcelsend.";
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
                    var Support="<a style='padding: 10px; text-decoration: none; color: white; background: #203370' href='https://tawk.to/chat/6286942f7b967b11799045bf/1g3er5n2u'>Chat Live Support</a>";
                    
                    $.ajax({
                        url:"SendMail.php",
                        method:'POST',
                        data:{SendTo:SendTo, Subject:Subject, htmlcontent:htmlcontent},
                        success:function(data){
                            
                        }
                    })
            
        }else{
            alert('Error Somewhere');
            alert(data);
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
                    var Support="<a style='padding: 10px; text-decoration: none; color: white; background: #203370' href='https://tawk.to/chat/6286942f7b967b11799045bf/1g3er5n2u'>Chat Live Support</a>";
                    
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