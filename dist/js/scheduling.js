$(document).ready(function(){

  // L.marker([28.61, 77.23]).addTo(map);
  // addCustomMarker('./../assets/icons/truck.png', new L.LatLng(28.5628, 77.6856), false, "Custom marker sample!")
  

})


function reset_form_data(form_id) {
  $(`${form_id}`)[0].reset();
}

// calling api to store form data
function store_data(form_data_object, api_url, form_id) {
  // console.log('func form data', form_data_object)
  $.ajax({
      url: api_url,
      type: 'post',
      data: form_data_object,
      success: function(response) {
          console.log('response', response)
          const data = JSON.parse(response)
          // alert(response)
          console.log(data)
          alert(data['message'])
          // console.log(data['message'])
          if(data['error'] === false) {
              // $(`${form_id} :input`).val('');
              reset_form_data(form_id)
          }
      }
  })
}


//create map using MapMyIndia API
var mgs_marker = [];
var dbs_marker = [];
var lcv_marker = [];
var polygons = [];
var visbility = false;
var p1 = null;
var poly;
var pts;
var map=new MapmyIndia.Map("map",{ 
  center:[28.5628, 77.6856],
  zoomControl: true,
  hybrid:true
});
//use map.invalidateSize(); if map is not fully loaded in page.


// function called after every 10 seconds for showing moving LCVs
// setInterval(function() {
//   console.log("timed func")
//   $.ajax({
//       url: "partials/lcv_position_cURL.php",
//       method: "GET",
//       success: function(response) {
//           const data = JSON.parse(response)
//           console.log(data['Vehicle'])
//           const vehicle_details = data['Vehicle']
//           for(var i in vehicle_details) {
//               var vehicle = vehicle_details[i]
//               var veh_lat = parseFloat(vehicle['Lat'], 10);
//               var veh_long = parseFloat(vehicle['Long'], 10);
//               L.marker([veh_lat, veh_long]).addTo(map)
//           }
//       }
//   })  
// }, 10000);



// get lat long for station
async function get_lat_lng(station_id, lat_input) {
  // console.log('mgs coord')
  await $.ajax({
    url:'partials/_fetch_latlng.php',
    type:"POST",
    data: {
      id: station_id
    },
    success: function(data) {
      console.log("mgs coord api data", data)
      $(lat_input).val(data)
      // console.log($(lat_input).val())
    }
  })
  
}

let lcv_lat_long = []

// get latitude and longitude of LCV and mark in map
function get_lcv_lat_lng(lcv_num) {
  $.ajax({
    url:'partials/_fetch_lcv_lat_lng.php',
    type:"POST",
    data: {
      id: lcv_num
    },
    success: function(response) {
      // console.log(response);
      const data = JSON.parse(response)
      // console.log(data)
      if(data['error'] === false) {
        var latitude = Number(data['latitude'], 10)
        var longitude = Number(data['longitude'], 10)
        // lcv_lat_long.push(new L.marker([latitude, longitude]))
        console.log(latitude, longitude);
        addCustomMarker('./../assets/icons/truck.png', new L.LatLng(latitude, longitude), false, lcv_num, 'lcv')
        // L.marker([latitude, longitude]).addTo(map)

      }
      // $(lat_input).val(data)
      // console.log($(lat_input).val())
    }
  })
}

function get_distance_time(mgs, dbs, latlngdbs, latlngmgs) {
  // console.log("function invoked")
  $.ajax({
    url:'calculateDistance.php',
    type:"POST",
    data: {
      latlngDBS: latlngdbs,
      latlngMGS:latlngmgs,
      dbsid: dbs,
      mgsid: mgs
    },
    success: function(data) {
      // console.log("success")
      // console.log(data)
      dist = Math.round(data * 100)/100
      time = dist/40;
      time = Math.round(time * 100)/100
      // console.log("distance: ", dist)
      // console.log("samay: ", time)
      $('#distance').val(data)
      $('#time').val(time)
      // console.log($(lat_input).val())
    }
  })
}


// getting mass of gas left in DBS and filling the particular input field on frontend using field id
function get_mass_of_gas_left(station_id, field_id) {
  $.ajax({
    url: 'partials/_fetch_dbs_gas_left.php',
    method: 'POST',
    data: {
      station_id: station_id
    },
    success: function(response) {
      // console.log(response)
      const data = JSON.parse(response)
      // console.log(data);
      if(data['data_available'] == true) {
        $(`${field_id}`).val(data['mass_of_gas'])
      } else {
        $(`${field_id}`).val('Data Not Available')
      }
    }
  })
}


$('#mgsid').change(function(){
  let mgsId=(this).value
  console.log(mgsId, '1');
  if(mgsId!=='NA') {
    console.log('2');
    
    //get DBS of selected MGS
    $.ajax({
        url: "partials/_fetch_dbs.php?mgsid="+mgsId,
        type:"GET",
        success: function(data) {
          // console.log(data)
          $('#dbsid').html(data)
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("some error");
        }
    })
    

    const mgs_coord_promise = get_lat_lng(mgsId, '#latlngMGS')
    
     //marking selected MGS on map
    mgs_coord_promise.then( function () {
      var lat_lng_mgs = $('#latlngMGS').val().split(',')
      console.log("mgs lat lng",lat_lng_mgs)
      var mgs_lat = parseFloat(lat_lng_mgs[0], 10)
      var mgs_lng = parseFloat(lat_lng_mgs[1], 10)
      console.log(mgs_lat, mgs_lng);
      // L.marker([mgs_lat, mgs_lng]).addTo(map)
      addCustomMarker('', new L.LatLng(mgs_lat, mgs_lng), false, mgsId, 'mgs')

      //centering the map to selected MGS
      map.panTo([mgs_lat, mgs_lng])
    })
      

    //get LCV number of selected mgs
    .then(
      $.ajax({
        url:'partials/_fetch_lcv_num.php',
        type: "POST",
        data: {
          mgs: mgsId,
        },
        success: function(response) {
          console.log(response)
          const data = JSON.parse(response)
          console.log(data)
          lcv_list = ''

          if(data['data_available'] === false) { // if no LCV is mapped to selected MGS
            lcv_list = "<option value = 'NA'>No LCV mapped to selected MGS</option>"
          } else {
            lcv_list = "<option value = 'NA'>Select LCV</option>"
            for (var i in data) {
              console.log(data[i][0])
              lcv_list += `<option value="${data[i][0]}">${data[i][0]}</option>`

              // getting latitude and longitude of LCVs and marking them on map
              // get_lcv_lat_lng(data[i][0])
            }
          }
          // fill the LCV dropdown with options
          $('#lcvid').html(lcv_list)
        }
      })
    )
  }
})

$('#dbsid').change(function(){
  let dbs_id=(this).value
  let mgs_id = $('#mgsid').val()

  if(dbs_id !=='' || dbs_id !== 'NA') {

    const dbs_coord_promise = get_lat_lng(dbs_id, '#latlngDBS')

    // getting mass of gas left at the selected DBS and putting it into the input field on frontend
    get_mass_of_gas_left(dbs_id, '#gas_left_in_dbs')

    //marking selected DBS on map
    dbs_coord_promise.then(function() {
      var lat_lng_dbs = $('#latlngDBS').val().split(',')
      console.log(lat_lng_dbs)
      var dbs_lat = parseFloat(lat_lng_dbs[0], 10)
      var dbs_lng = parseFloat(lat_lng_dbs[1], 10)
      console.log(dbs_lat, dbs_lng);
      addCustomMarker('', new L.LatLng(dbs_lat, dbs_lng), false, dbs_id, 'dbs')
      map.panTo([dbs_lat, dbs_lng])
    })
  } else {
    $('#lcvid').html('')
  }
})

$('#calct_dist_time').click(function(){
  let latlngDBS = $('#latlngDBS').val()
  let latlngMGS = $('#latlngMGS').val()
  // console.log("latlng: "+latlngDBS)
  let mgs = $('#mgsid').val()
  let dbs = $('#dbsid').val()
  console.log(mgs, dbs)
  if(mgs==='NA' || dbs ==='NA') {
    alert('Please Select valid MGS or DBS id')
  }
  else {
    // Math.round((num + Number.EPSILON) * 100) / 100
    get_distance_time(mgs, dbs, latlngDBS, latlngMGS)
    
  }
})

$('#lcvid').change(function() {
  const lcv_num=$(this).val()
  $.ajax({
    url: "partials/_fetch_lcv_status.php",
    type: "POST",
    data: {
      lcvid: lcv_num
    },
    success: function(response){
      $('#lcvstatus').val(response)
    }
  })
})

$('#update_detail').click(function() {
  let form_arr = $('#schedule_form').serializeArray()
    var form_obj={}
    for(let ele of form_arr) {
        let first=ele.name
        let second = ele.value
        form_obj[first] = second
    }
    console.log("form obj", form_obj)
    const api_url = "partials/update_lcv_schedule.php"
    store_data(form_obj, api_url, '#schedule_form')
})



//add marker

function addMarker(position, icon, title, draggable) {
  /* position must be instance of L.LatLng that replaces current WGS position of this object. Will always return current WGS position.*/
  var event_div = document.getElementById("event-log");
  if (icon == '') {
    var mk = new L.Marker(position, {
      draggable: draggable,
      title: title
    }); /*marker with a default icon and optional param draggable, title */
    mk.bindPopup(title);
  } else {
    var mk = new L.Marker(position, {
      icon: icon,
      draggable: draggable,
      title: title
    }); /*marker with a custom icon */
    mk.bindPopup(title);
  }
  map.addLayer(mk); /*add the marker to the map*/
  /* marker events:*/
  mk.on("click", function(e) {
    // event_div.innerHTML = "Marker clicked<br>" + event_div.innerHTML;
  });

  return mk;
}

// custom marker

function addCustomMarker(iconPath, position, draggable, title, marker_type) {
  console.log(marker_type)
  // remove previous LCV and DBS markers if new dbs is selected
  if(marker_type === 'dbs') {
    
    mapmyindia_remove_DBS_Marker()
    // mapmyindia_remove_LCV_Marker()
  } else if(marker_type === 'mgs') {
    //removing all markers if new mgs is selected
    mapmyindia_remove_MGS_Marker()
    mapmyindia_remove_DBS_Marker()
    mapmyindia_remove_LCV_Marker()
  }
  console.log(iconPath, position, draggable, title)
  var icon = ''
  if(iconPath !== '') {
    icon = L.icon({
      iconUrl: iconPath,
      iconRetinaUrl: iconPath,
      iconSize: [30, 30],
      popupAnchor: [-3, -15]
    });
  }
  // var postion = new L.LatLng(28.5628, 77.6856); /*WGS location object*/
  var mk = addMarker(position, icon, title, draggable);
  if(marker_type === 'lcv') {
    lcv_marker.push(mk)
  } else if(marker_type === 'dbs') {
    dbs_marker.push(mk)
  } else if(marker_type === 'mgs') {
    mgs_marker.push(mk)
  }
  // marker.push(mk);
  map.setView(mk.getLatLng());
}


// removing markers
function mapmyindia_remove_MGS_Marker() {
  var mgsMarkerlength = mgs_marker.length;
  if (mgsMarkerlength > 0) {
    for (var i = 0; i < mgsMarkerlength; i++) {
      map.removeLayer(mgs_marker[i]); /* deletion of marker object from the map */
    }
  }
  delete mgs_marker;
  mgs_marker = [];
  // document.getElementById("event-log").innerHTML = "";
}

function mapmyindia_remove_DBS_Marker() {
  var dbsMarkerlength = dbs_marker.length;
  if (dbsMarkerlength > 0) {
    for (var i = 0; i < dbsMarkerlength; i++) {
      map.removeLayer(dbs_marker[i]); /* deletion of marker object from the map */
    }
  }
  delete dbs_marker;
  dbs_marker = [];
  // document.getElementById("event-log").innerHTML = "";
}

function mapmyindia_remove_LCV_Marker() {
  var lcvMarkerlength = lcv_marker.length;
  if (lcvMarkerlength > 0) {
    for (var i = 0; i < lcvMarkerlength; i++) {
      map.removeLayer(lcv_marker[i]); /* deletion of marker object from the map */
    }
  }
  delete lcv_marker;
  lcv_marker = [];
  // document.getElementById("event-log").innerHTML = "";
}


// polygons
