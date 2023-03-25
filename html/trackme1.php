<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Me</title>
</head>

<body>
    <div id="details"></div>
    <div id="map"></div>
    <div id="map1"></div>


    <script>
        var reqcount = 0;
        const watchId = navigator.geolocation.watchPosition(successCallback, errorCallback, options);

        function successCallback(position) {
            const {
                accuracy,
                latitude,
                longitude,
                altitude,
                heading,
                speed
            } = position.coords;
            reqcount++;
            details.innerHTML = "Accuracy:" + accuracy + "<br>";
            details.innerHTML += "Latitude:" + latitude + "|Longitude:" + longitude + "<br>";
            details.innerHTML += "Altitude:" + altitude + "<br>";
            details.innerHTML += "Heading:" + heading + "<br>";

            details.innerHTML += "Speed:" + speed + "<br>";
            details.innerHTML += "reqcount:" + reqcount;

            map.innerHTML = '<iframe width="700" height="300" src="https://maps.google.com/maps?q=' + latitude + ',' + longitude + '&amp;z=15&amp;output=embed"></iframe>';
            map1.innerHTML = '<iframe width="700" height="300" src="https://www.openstreetmap.org/#map=18/' + latitude + '/' + longitude + '"></iframe>';

            // map1.innerHTML = '<iframe width="700" height="300" src="https://www.openstreetmap.org/#map=18/' + latitude + '/' + longitude "> < /iframe>'

            map1.innerHTML = ' <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=' + longitude + '%2C' + latitude + '4465%2C' + longitude + '298%2C' + latitude + '139&amp;layer=mapnik" style="border: 1px solid black"></iframe><br/><small><a href="https://www.openstreetmap.org/#map=18/' + latitude + '/' + longitude + '">View Larger Map</a></small>'
        }

        function errorCallback(error) {

        }
        var options = {
            enableHighAccuracy: false,
            timeout: 5000,
            maximumAge: 0
        }
    </script>

</body>

</html>