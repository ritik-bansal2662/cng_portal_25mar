// while calling api for data of LCV tracking, vehicle id and dates params are kept static for testing, will be changed later on

// api for vehicle tracking details
// https://gpsvts.vamosys.com/apiMobile/getVehicleHistory?userId=SURESHLUX&groupId=SURESHLUX&vehicleId=DL1LAA9187&fromDate=2022-12-09&fromTime=00:00:00&toDate=2022-12-09&toTime=23:59:00

//create map using MapMyIndia API
var lcv_marker = [];
var visbility = false;
var p1 = null;
var poly;
var pts = [];

// dummy data for pts
// new L.LatLng(28.69188778, 74.58885889),
// new L.LatLng(29.69186222, 75.58896889),
// new L.LatLng(30.69188778, 50.58885889),
// new L.LatLng(31.69186222, 77.58896889),
// new L.LatLng(32.69188778, 60.58885889),
// new L.LatLng(33.69186222, 65.58896889),

var polylineParam = 
{ 
    // color: 'black',
    weight: 4, // The thickness of the polyline 
    opacity: 0.8 //The opacity of the polyline colour 
};

var map = new MapmyIndia.Map("map", {
    center: [28.61, 77.23],
    zoomControl: true,
    hybrid: true,
    search: true,
    location: true
});


$(document).ready(function() {

    const current_url = window.location.href;
    const url_parameters = getParameters(current_url)
    // console.log(url_parameters);
    const fromDate = (url_parameters['fromDate']).split(' ')
    const toDate = (url_parameters['toDate']).split(' ')
    // console.log(fromDate);
    // console.log(toDate);

    
    // const LCV_API = `https://gpsvts.vamosys.com/apiMobile/getVehicleHistory?userId=SURESHLUX&groupId=SURESHLUX&vehicleId=${url_parameters['lcv_num']}&fromDate=${fromDate[0]}&fromTime=${fromDate[1]}&toDate=${toDate[0]}&toTime=${toDate[0]}`
    const LCV_API = `https://gpsvts.vamosys.com/apiMobile/getVehicleHistory?userId=SURESHLUX&groupId=SURESHLUX&vehicleId=${url_parameters['lcv_num']}&fromDate=2022-12-09&fromTime=00:00:00&toDate=2022-12-09&toTime=23:59:00`
    const locations = fetch(LCV_API)
    // console.log('fetch',locations);
    locations.then(function(data) {
        console.log('data', data);
        return data.json()
    }).then(function(data) {
        console.log(data);
        console.log('vehicle locations',data['vehicleLocations']);
        if(data['vehicleLocations'] !== null) {
            let lat = data['vehicleLocations'][0]['lat']
            let lng = data['vehicleLocations'][0]['lng'];

            //custom title for marker when trip starts
            let title = marker_title_window(url_parameters['lcv_num'], 'MGS123', 'DBS123', 'MGS123', fromDate[0], 'Trip Start')

            // add marker of trip start place on the map
            addMarker(new L.LatLng(lat, lng), title, false);
            // console.log(lat, lng);

            for(let item of data['vehicleLocations']) {
                // console.log(item['lat'], item['lng']);
                lat = item['lat']
                lng = item['lng']
                pts.push(new L.LatLng(lat, lng))
            }
            
            var poly = new L.Polyline(pts, polylineParam);
            map.addLayer(poly);

            // move the centre of map to the trip end place
            map.panTo([lat, lng])

            //custom title for marker when trip ends
            title = marker_title_window(url_parameters['lcv_num'], 'MGS123', 'DBS123', 'DBS123', toDate[0], 'Trip End')

            // add marker of trip end place on the map
            addMarker(new L.LatLng(lat, lng), title, false);
        } else {
            alert('No data Available')
            $('#error').css('display', 'flex')
        }
    }).then(function() {
        $('#loading').css('display', 'none')
    })
})

function get_lcv_data(lcv_number, from_date, to_date) {

    let lcv_location_data;

    // vehicle id and dates params are kept static for testing, will be changed later on
    $.ajax({
        url: 'https://gpsvts.vamosys.com/apiMobile/getVehicleHistory',
        type: 'GET',
        data: {
            userId : 'SURESHLUX',
            groupId : 'SURESHLUX',
            vehicleId : 'DL1LAA9187',
            fromDate : '2022-12-09',
            fromTime : '00:00:00',
            toDate : '2022-12-09',
            toTime : '23:59:00',
            // fromDate : from_date[0],
            // fromTime : from_date[1],
            // toDate : to_date[0],
            // toTime : to_date[1]
        },
        success: function(response) {
            console.log(response);
            console.log('locations',response['vehicleLocations']);
            lcv_location_data = response['vehicleLocations']
        }
    })
    return lcv_location_data
}


//to get the 'GET' parameters from the URL like fromDate, toDate, LCV number
function getParameters(urlString) {
    let paramString = urlString.split('?')[1];
    let queryString = new URLSearchParams(paramString);
    let params = {}
    for(let pair of queryString.entries()) {
        params[pair[0]] = pair[1];
        // console.log(pair);
    }
    // console.log(params);
    return params;
}

// creating a custom marker title window for specific and more details
function marker_title_window(lcv_number, from_station, to_station, current_position, date, trip_status) {
    let title_window_html =`<div>
        <h2>${trip_status}</h2>
        <h3>This Place :- ${current_position}</h3>
        <h3>Date :- ${date}</h3>
        <h3>LCV :- ${lcv_number}</h3>
        <h3>From Station :- ${from_station}</h3>
        <h3>To Station :- ${to_station}</h3>
    </div>`

    return title_window_html
}


// to add marker on the map
function addMarker(position, title, draggable) {
    // position must be instance of L.LatLng that replaces current WGS position of this object. 
    // Will always return current WGS position. 
    // define a marker with a default icon and optional parameters draggable and title 
    var mk = new L.Marker(position, {draggable: draggable, title: title}); 

    mk.bindPopup(title); 

    //Now lets add the marker to the Map 

    map.addLayer(mk); 

    //Although we.ll talk about a few things in the code segment in a moment 
    //but lets put it in here so that you have the full picture. 
    //marker events:
    mk.on("click", function (e) { 
        //your code about what you want to do on a marker click 
        console.log(e);
    }); 
    return mk; 
}
