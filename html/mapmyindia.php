<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapMyIndia</title>
    <style> 
        html, body, #map {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        } 
    </style>
</head>
<body>
    <div id="map"></div>
    <div>asdf</div>


    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/fac149b818d7ba75db4aeee2b5e9f70b/map_load?v=1.5"></script>
    <script>
        var map=new MapmyIndia.Map("map",{ 
            center:[28.792551, 77.140258],
            zoomControl: true,
            hybrid:true 
        });
        //use map.invalidateSize(); if map is not fully loaded in page.
        
        // L.marker([28.61, 77.23]).addTo(map);
        $(document).ready(function() {
            $.ajax({
                url: "partials/lcv_position_cURL.php",
                method: "GET",
                success: function(response) {
                    const data = JSON.parse(response)
                    console.log(data['Vehicle'])
                    const vehicle_details = data['Vehicle']
                    for(var i in vehicle_details) {
                        var vehicle = vehicle_details[i]
                        var veh_lat = parseFloat(vehicle['Lat'], 10);
                        var veh_long = parseFloat(vehicle['Long'], 10);
                        L.marker([veh_lat, veh_long]).addTo(map)
                    }
                }
            })
        })
    </script>
</body>
</html>