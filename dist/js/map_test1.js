// $('#map').html("asdf")

$.ajax({
    url:'https://ctyf.co.in/api/companyvehiclelatestinfo?token=3B81B50C57',
    method:"GET",
    success: function(response) {
      console.log(response);
      // const data = JSON.parse(response)
      // console.log(data)
      // if(data['error'] === false) {
      //   var latitude = Number(data['latitude'], 10)
      //   var longitude = Number(data['longitude'], 10)
        // locations.append['DL1LY8262', latitude, longitude, 4]
        // console.log('locations', locations)
        // show_map(latitude, longitude)
      }
      // $(lat_input).val(data)
      // console.log($(lat_input).val())
  })

// let mapOptions = {
//   center: [51.958, 9.141],
//   zoom: 10
// }

// let map = L.map('map', mapOptions)

// let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
// map.addLayer(layer)

// let marker = new L.Marker([51.958, 9.141])
// let marker2 = new L.Marker([51.958, 9.141])
// marker.addTo(map)
// marker2.addTo(map)

// const lcv = https://ctyf.co.in/api/companyvehiclelatestinfo?token=3B81B50C57


{
  const map = L.map('map').setView([28.829398, 77.13032], 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      minZoom: 0,
      maxZoom: 20,
      attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
  }).addTo(map);
  const leafletMarkers = L.layerGroup([
    new L.marker([28.829398, 77.13032]),
    new L.marker([28.846798, 76.859093]),
    new L.marker([28.830404, 77.130009])
  ]);
  leafletMarkers.addTo(map);
}
