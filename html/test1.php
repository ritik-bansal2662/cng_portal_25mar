<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<script async defer src="https://maps.googleapis.com/maps/api/jskey=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

<script>
  function initialize() {
    var locations = [
      ['Swetha', 13.0574908,77.6041306, 3],
      ['Sumit Sir', 28.5698496,77.3855492, 2],
      ['Ratna', 20.2967607,72.982454, 1]
    ];
    // var lcv = https://ctyf.co.in/api/companyvehiclelatestinfo?token=3B81B50C57

    // $.ajax( {
    //     url: "https://ctyf.co.in/api/companyvehiclelatestinfo?token=3B81B50C57",
    //     method: 'GET',
    //     success: function(response) {
    //         console.log(response)
    //     }
    // })

    // const userAction = async () => {
    //     const response = await fetch('https://ctyf.co.in/api/companyvehiclelatestinfo?token=3B81B50C57', {
    //         method: 'GET',
            //body: myBody, // string or object
            // headers: {
            // 'Content-Type': 'application/json'
            // }
        // });
        // console.log(response)
        // const myJson = await response.json(); //extract JSON from the http response
        // do something with myJson
    // }
    // userAction()

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 5,
      center: new google.maps.LatLng(13.0574908,77.6041306),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  }

  function loadScript() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' + 'callback=initialize';
    script.src='https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&'+'callback=initialize';
    document.body.appendChild(script);
  }

  window.onload = loadScript;
</script>

<div id="map" style="width: 900px; height: 700px;"></div>