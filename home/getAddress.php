<?php
include('../Connect.php');

$Tracking_id = $_POST['TrackingText'] ?? '';

$stmt = $Conn->prepare("SELECT * FROM Product WHERE Product_id = ?");
$stmt->bind_param("s", $Tracking_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$count = $result->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Track Consignment</title>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<!-- QR CODE LIBRARY -->


<style>
.activ{
    background:#1476b8;
    color:white;
}

#map{
    height:450px;
    width:100%;
}

.stats{
    position:relative;
    z-index:10;
}

.stat-circle{
    width:60px;
    height:60px;
    border-radius:50%;
    background:#1476b8;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
    margin:auto;
}

.card{
    border-radius:15px;
}
.car-icon img {
    transition: transform 0.2s linear;
    transform-origin: center;
}

.car-icon {
    width: 60px;
    height: 60px;
}

.car-icon img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    transform-origin: center;
}
</style>
</head>

<body class="">
<div class=" mt-4">

<?php if($count > 0): ?>

<?php

$file = explode(",", $row['Product_Image']);

$res = "<div class='table-responsive'><table class='table'><tr>"; // initialize

for($i=0; $i < count($file); $i++){

    $ext = strtolower(pathinfo($file[$i], PATHINFO_EXTENSION));

    $arrimg = ['jpg', 'jpeg', 'png', 'gif','webp', 'bmp', 'svg'];
    $arrvid = ['mp4', 'webm', 'mov', '3gp','mpeg','mkv'];

    if(in_array($ext, $arrimg, true)){

        $res .= '<td><a style="text-decoration: none" href="../Manager/'.$file[$i].'"><img src="../Manager/'.$file[$i].'" width="250px" class="img-thumbnail m-2"></a></td>';

    }else if(in_array($ext, $arrvid, true)){

        $res .= '<td>
        <video width="250" height="240" controls class="m-2">
            <source src="../Manager/'.$file[$i].'" type="video/mp4">
        </video></td>';

    }
}

$res.="</tr></table></div>";





$Status = ($row['Status'] == 0)
    ? "This consignment with Tracking ID ({$row['Product_id']}) is on its way to {$row['Receiver_Address']}"
    : "Delivered";

$Color = ($row['Status'] == 0) ? "text-warning" : "text-success";


?>

<div class="">
    <h3 style="color: var(--fg)">Product ID: <?= $row['Product_id']; ?> - <?= $row['Product_Name']; ?></h3>
<?php

if($row['Status']==1){

}else if($row['Status']==2){
    
}else{
  echo '<div id="map" class="mb-5"></div>';

} ?>

    <div class="stats mt-3">
        <div class="card shadow p-3" style="background: var(--bg-card)">
            <div class="row text-center"  style="color: var(--bg-card)">
                <div class="col" > <div class="stat-circle"><?php if($row['Status']=="0"){echo "1"; }else{ echo "0"; } ?></div><small style="color: var(--fg)">Allocated</small></div>
                
                <div class="col" ><div class="stat-circle"><?php if($row['Status']=="0"){echo "1"; }else{ echo "0"; } ?></div><small style="color: var(--fg)">In Progress</small></div>
                
                <div class="col"><div class="stat-circle"><?php if($row['Status']=="2"){echo "1"; }else{ echo "0"; } ?></div><small style="color: var(--fg)">Delivered</small></div>
                
                 
                <div class="col"><div class="stat-circle"><?php if($row['Status']=="3"){echo "1"; }else{ echo "0"; } ?></div><small style="color: var(--fg)">Onhold</small></div>
                
                <div class="col" style="color: var(--fg)"><div class="stat-circle"><?php if($row['Status']=="1"){echo "1"; }else{ echo "0"; } ?></div><small>Cancelled</small></div>
            </div>
            <hr>
            <div class="row text-center" style="background: var(--bg-card)">
                <div class="col">
                    <strong style="color: var(--fg)">Travel Distance</strong><br>
                    <span id="distance" style="color: var(--fg)">-- km</span>
                </div>
                <div class="col">
                    <strong  style="color: var(--fg)">Estimated Time</strong><br>
                    <span style="color: var(--fg)"><?= $row['delivery_time']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <ul class="list-group mt-4" style="background: var(--bg-card)">
        <li class="list-group-item activ"><h3>Consignment Information</h3></li>
        <li class="list-group-item">
           <?= $res; ?>
        </li>
        <li class="list-group-item activ"><h4>Sender Details</h4></li>
        
        <li class="list-group-item">Sender's Name: <?= $row['Sender_Name']; ?></li>
        <li class="list-group-item">Product Name: <?= $row['Product_Name']; ?></li>
        
        <li class="list-group-item">Description: <?= $row['Description']; ?></li>
        <li class="list-group-item">Weight: <?= $row['Weight']; ?></li><br>

        <li class="list-group-item activ"><h4>Delivery Status</h4></li>
        <li class="list-group-item">
            <h5><?= $row['Current_Location']; ?></h5>
        </li><br>
                <li class="list-group-item activ"><h4>Delivery Medium</h4></li>

         <li class="list-group-item">
            <h5><?php if($row['delivery_medium']=="ocean"){
            echo "Ocean Freight";
            }else if($row['delivery_medium']=="air"){
                echo "Air Freight";
            } else{
            echo "Road Freight";
            }; ?></h5>
        </li>
    </ul>
    <br>
    <h1>Security</h1>
    <div class="mb-3 card bg-light">
        <div class="card-body">
    <p>This package is confidential and requires you to genrate a secret SafeBox token which will be used to unlock the package. Note do not disclose the secrete token to a third party</p>
    <div class="d-grid gap-2"><button class="btn btn-success btn-lg"data-bs-toggle="modal" data-bs-target="#exampleModal">Generate SafeBox Code </button></button></div>
    </div>
    </div>
    
    
    
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Generate SafeBox secret token</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Code generation not enabled for this package yet. A one time fee of <span style="font-size: 15pt">$<?php echo number_format($row['Amount'], 2, '.',','); ?> </span>is required to enable code generation<br><br>
        <h3>Choose Payment method</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-dismiss="modal"  data-bs-target="#exampleModal2">Crypto</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-dismiss="modal"  data-bs-target="#exampleModal3">Bank Deposit</button>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Pay via Cryptocurrency</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="text-align: center;">
      <?php

$type="crypto";

$sqlpe="select * from payment_details where Product_id='$Tracking_id' and type='$type'";
$querype=mysqli_query($Conn, $sqlpe);

while($rowe=mysqli_fetch_array($querype, MYSQLI_ASSOC)){

?>

<center><div id="qrcode"></div></center><br>

<script>

var value = <?= json_encode($rowe['walletaddress']); ?>;

new QRCode(document.getElementById("qrcode"), {
    text: value,
    width: 200,
    height: 200
});

function copyAddress(button) {
    const address = button.dataset.address;
    navigator.clipboard.writeText(address)
        .then(() => {
            button.innerHTML = "Copied ✓";

            setTimeout(() => {
                button.innerHTML = "Copy Address";
            }, 2000);
        })
        .catch(err => {
            console.error("Failed to copy:", err);
        });
}
</script>

<?php
echo "Address: ".$rowe['walletaddress']."<br>";
echo "Currency: ".$rowe['currency']."<br>";
if($rowe['network']!=""){
    echo "Network: ".$rowe['network']."<br>";
}
echo "<button class='btn btn-primary' id='wallet_".$rowe['id']."' onclick='copyAddress(this)'  data-address='".$rowe['walletaddress']."'>Copy Address </button>";

}


?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Pay via Bank Deposit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <?php 
          $type2="bank";
          $sqlp="select * from payment_details where Product_id='$Tracking_id' and type='$type2'";
          $queryp=mysqli_query($Conn, $sqlp);
          while($rowp=mysqli_fetch_array($queryp, MYSQLI_ASSOC)){
            echo $rowp['walletaddress']."<br>";
            echo $rowp['type']."<br>";
          if($rowp['network']==""){
              
          }else{
                      echo $rowp['network']."<br>";
  
          } 
          }
          
        
          
          ?>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</div>

<?php else: ?>

<div class="alert alert-danger">
Invalid Tracking ID. Please contact support.
</div>

<?php endif; ?>

</div>

<?php if($count > 0): ?>

<script>
var st = "<?php echo $row['start']; ?>";
var dst = "<?php echo $row['destination']; ?>";
 var medium="<?php echo $row['delivery_medium']; ?>";

var startArr = st.split(",");
var destArr = dst.split(",");

// convert to numbers
var start = [parseFloat(startArr[0]), parseFloat(startArr[1])];
var destination = [parseFloat(destArr[0]), parseFloat(destArr[1])];


var map = L.map('map').setView(start, 18);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);


var routeCoords = [];
var steps = 20;

for (let i = 0; i <= steps; i++) {

    let lat = start[0] + (destination[0] - start[0]) * (i / steps);
    let lng = start[1] + (destination[1] - start[1]) * (i / steps);

    routeCoords.push([lat, lng]);
}

var routeLine = L.polyline(routeCoords, {
    color: 'red',
    weight: 2
}).addTo(map);
var destinationIcon = L.icon({
    iconUrl: "https://cdn-icons-png.flaticon.com/512/2776/2776067.png",
    iconSize: [35, 35],      // size of icon
    iconAnchor: [17, 35],    // point of the icon that corresponds to marker location
    popupAnchor: [0, -30]
});
L.marker(destination, { icon: destinationIcon })
    .addTo(map)
    .bindPopup("Destination")
    .openPopup();

map.fitBounds(routeLine.getBounds());

    setTimeout(function () {
        map.invalidateSize();
    }, 300);
    
    
    if(medium=="ocean"){
    var carIcon = L.divIcon({
    className: "car-icon",
    html: '<img id="car" src="../pngtree-large-container-ship-at-sea-aerial-top-view-cargo-to-harbor-png-image_12516070.png">',
    iconSize: [40,40],
    iconAnchor: [20,20]
});
    }else if(medium=="air"){
        
    var carIcon = L.divIcon({
    className: "car-icon",
    html: '<img id="car" src="../airplane-rendered-in-transparent-with-a-clean-transparent-background-showcasing-an-aerial-view-of-its-design-airplane-isolated-on-transparent-background-3d-rendering-free-png.png">',
    iconSize: [40,40],
    iconAnchor: [20,20]
});    
        
    } else{
    var carIcon = L.divIcon({
    className: "car-icon",
    html: '<img id="car" src="https://png.pngtree.com/png-vector/20241116/ourmid/pngtree-white-truck-top-view-illustration-png-image_14441341.png">',
    iconSize: [40,40],
    iconAnchor: [20,20]
});
    }



    var marker = L.marker(routeCoords[0], {icon: carIcon}).addTo(map);

    let currentSegment = 0;
    let progress = 0;
    let speed = 0.0001;
    

function animateCar(){
    if(currentSegment >= routeCoords.length - 1){
        currentSegment = 0;
        progress = 0;
    }

    let start = routeCoords[currentSegment];
    let end = routeCoords[currentSegment + 1];

    let lat = start[0] + (end[0] - start[0]) * progress;
    let lng = start[1] + (end[1] - start[1]) * progress;

    marker.setLatLng([lat,lng]);

    // ðŸ”¥ Calculate angle
    let angle = Math.atan2(
        end[1] - start[1], 
        end[0] - start[0]
    ) * (180 / Math.PI);
angle += 100; 
    document.getElementById("car").style.transform = 
        "rotate(" + angle + "deg)";

    progress += speed;

    if(progress >= 1){
        progress = 0;
        currentSegment++;
    }

    requestAnimationFrame(animateCar);
}

    animateCar();

    // Calculate distance
    let total = 0;
    for(let i=0;i<routeCoords.length-1;i++){
        total += map.distance(routeCoords[i], routeCoords[i+1]);
    }
    document.getElementById("distance").innerText = (total/1000).toFixed(2) + " km";
    console.log(document.getElementById("distance"));


</script>

<?php endif; ?>

</body>
</html>
