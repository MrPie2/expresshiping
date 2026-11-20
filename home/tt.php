<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SwiftTrack Live Tracking</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
body{
    background:#f2f5f9;
    font-family: 'Segoe UI', sans-serif;
}

.header{
    background:#009688;
    color:white;
    padding:15px;
    text-align:center;
    font-weight:600;
}

#map{
    height:450px;
    width:100%;
}

.stats{
    margin-top:-40px;
    position:relative;
    z-index:999;
}

.stat-circle{
    width:60px;
    height:60px;
    border-radius:50%;
    background:#009688;
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
    width: 40px;
    height: 40px;
}

.car-icon img {
    width: 40px;
    height: 40px;
    object-fit: contain;
    transform-origin: center;
}
</style>
</head>
<body>

<div class="header">
    Live Shipment Tracking
</div>

<div id="map"></div>

<div class="container stats">
    <div class="card shadow p-4">
        <div class="row text-center">
            <div class="col">
                <div class="stat-circle">0</div>
                <small>Allocated</small>
            </div>
            <div class="col">
                <div class="stat-circle">0</div>
                <small>In Progress</small>
            </div>
            <div class="col">
                <div class="stat-circle">1</div>
                <small>Delivered</small>
            </div>
            <div class="col">
                <div class="stat-circle">0</div>
                <small>Cancelled</small>
            </div>
        </div>

        <hr>

        <div class="row text-center">
            <div class="col">
                <strong>Travel Distance</strong><br>
                <span id="distance">-- km</span>
            </div>
            <div class="col">
                <strong>Estimated Time</strong><br>
                <span>39 mins</span>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

// Initialize map (Los Angeles like your image)
var map = L.map('map').setView([34.0522, -118.2437], 11);

// Tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);


// Route Coordinates (Simulated GPS Path)
var routeCoords = [
    [34.1500, -118.3500],
    [34.1450, -118.3450],
    [34.1400, -118.3400],
    [34.1350, -118.3350],
    [34.1300, -118.3300],
    [34.1250, -118.3250],
    [34.1200, -118.3200],
    [34.1150, -118.3150],
    [34.1100, -118.3100],
    [34.1050, -118.3050],
    [34.1000, -118.3000],
    [34.0950, -118.2950],
    [34.0900, -118.2900],
    [34.0850, -118.2850],
    [34.0800, -118.2800],
    [34.0750, -118.2700],
    [34.0700, -118.2600],
    [34.0650, -118.2500],
    [34.0580, -118.2460],
    [34.0522, -118.2437]
];

// Draw red polyline
var routeLine = L.polyline(routeCoords, {
    color: 'red',
    weight: 5
}).addTo(map);

// Fit map to route
map.fitBounds(routeLine.getBounds());
// --- Smooth Car Animation ---

var carIcon = L.divIcon({
    className: "car-icon",
    html: '<img id="car" src="https://png.pngtree.com/png-vector/20241116/ourmid/pngtree-white-truck-top-view-illustration-png-image_14441341.png">',
    iconSize: [40,40],
    iconAnchor: [20,20]
});

var marker = L.marker(routeCoords[0], { icon: carIcon }).addTo(map);

let currentSegment = 0;
let progress = 0;
let speed = 0.002; // smaller = slower, smoother
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

    // 🔥 Calculate angle
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


// Calculate Distance
function calculateDistance(coords){
    let total = 0;
    for(let i=0;i<coords.length-1;i++){
        total += map.distance(coords[i], coords[i+1]);
    }
    return (total/1000).toFixed(2);
}

document.getElementById("distance").innerText = calculateDistance(routeCoords) + " km";

</script>

</body>
</html>
