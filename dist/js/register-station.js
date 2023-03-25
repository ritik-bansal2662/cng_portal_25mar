let stationary_cascade_ids = []
let compressor_ids = []
let dispenser_ids = []

let temp_gauge_ids = []
let low_pressure_gauge_ids = []
let medium_pressure_gauge_ids = []
let high_pressure_gauge_ids = []
let mass_flow_meter_ids = []



$(document).ready(function(){
  $.ajax({
    url:"partials/_fetch_notification_approver.php",
    type:"POST",
    success: function(data) {
      // console.log(data)
      $('#gen_notification_approver_id').html(data)
    }
  })

  // const st_cas_promise = new Promise( (resolve, reject) => {
  //   resolve()
  //   console.log('fuction completed');
  //   return 'data'
  // })
  // st_cas_promise.then((value) => {
  //   console.log('promise', value);
  //   load_stationary_cascade_ids()
  // })

  // to prefetch ids of equipments for verifying that the entered id at frontend is registered or not
  get_equipments_details('stationary_cascade', stationary_cascade_ids)
  get_equipments_details('compressor', compressor_ids)
  get_equipments_details('dispenser', dispenser_ids)

  // to prefetch ids of instruments for verifying that the entered id at frontend is registered or not
  get_instruments_details('temperature_gauge', temp_gauge_ids)
  get_instruments_details('low_pressure_gauge', low_pressure_gauge_ids)
  get_instruments_details('medium_pressure_gauge', medium_pressure_gauge_ids)
  get_instruments_details('high_pressure_gauge', high_pressure_gauge_ids)
  get_instruments_details('mass_flow_meter', mass_flow_meter_ids)

  

})


// to get and store stationary cascade id, compressor id and dispenser id for quick check
function get_equipments_details(apicall, equipment_list) {
  $.ajax({
    url:"partials/get_station_equipments_details.php",
    type:"GET",
    data: {
      apicall : apicall
    },
    success: function(response) {
      // console.log(response)
      for(var element in response){
        // console.log(element);
        equipment_list.push(element)
      }
      console.log('SC - ',stationary_cascade_ids);
      console.log('C - ', compressor_ids);
      console.log('D - ', dispenser_ids);
    }
  })
}

// to get and store Temperature Gauge id, Pressure Gauge id and Mass Flow Meter id for quick check
function get_instruments_details(apicall, instrument_list) {
  $.ajax({
    url:"partials/get_station_instruments_details.php",
    type:"GET",
    data: {
      apicall : apicall
    },
    success: function(response) {
      // console.log(response)
      for(var element in response){
        // console.log(element);
        instrument_list.push(element)
      }
      console.log('tg - ',temp_gauge_ids);
      console.log('lpg - ', low_pressure_gauge_ids);
      console.log('mpg - ', medium_pressure_gauge_ids);
      console.log('hpg - ', high_pressure_gauge_ids);
      console.log('mfm - ', mass_flow_meter_ids); 
    }
  })
}

// if id of equipment or instrument is available i.e. it is not registered before then change the css of that input field
function id_available(element_selector) {
  // change border color to green which depicts that id is available
  $(element_selector).css('border', 'solid 1px green')

  const element_availability_msg = $(element_selector).siblings('.availability-msg')

  $(element_availability_msg).val('Not Registered')
  $(element_availability_msg).html('Not Registered')

  $(element_availability_msg).css({
    'display':'flex',
    'color':'green'
  })
}

// if id of equipment or instrument is not available i.e. it is already registered before then change the css of that input field
function id_not_available(element_selector) {
  // change border color to Red which depicts that id is not available
  $(element_selector).css('border', 'solid 1px red')

  const element_availability_msg = $(element_selector).siblings('.availability-msg')

  $(element_availability_msg).val('Id already Registered')
  $(element_availability_msg).html('Id already Registered')

  $(element_availability_msg).css({
    'display':'flex',
    'color':'red'
  })
}

// remove the added css if the input field is empty
function remove_css(element_selector_list){
  for(let element of element_selector_list) {
    $(element).css('border', 'solid 1px black')

    const element_availability_msg = $(element).siblings('.availability-msg')

    $(element_availability_msg).css({
      'display':'none',
    })
  }
}


$(".gen").click(function(){
    $(this).addClass('main-active');
    $('.eqp').removeClass('main-active');
    $('.ins').removeClass('main-active');
    $('.gen-info').addClass('content-active');
    $('.eqp-info').removeClass('content-active');
    $('.ins-info').removeClass('content-active');
})


$(".eqp").click(function(){
    $(this).addClass('main-active');
    $('.gen').removeClass('main-active');
    $('.ins').removeClass('main-active');
    $('.eqp-info').addClass('content-active');
    $('.gen-info').removeClass('content-active');
    $('.ins-info').removeClass('content-active');
})

$(".ins").click(function(){
    $(this).addClass('main-active');
    $('.gen').removeClass('main-active');
    $('.eqp').removeClass('main-active');
    $('.ins-info').addClass('content-active');
    $('.eqp-info').removeClass('content-active');
    $('.gen-info').removeClass('content-active');
})

function load_mgs(id) {
  console.log(id, " ")
    $.ajax({
      url: "partials/_fetch_mgs.php",
      type:"POST",
      success: function(data) {
        console.log(data)
        $(id).html(data)
      }
    })
}

function load_stationary_cascade_ids() {
  let options = `<option value='NA'>Select Stationary Cascade Id</option>`
  for(var ele in stationary_cascade_ids) {
    options += `<options value='${ele}'>${ele}</options>`
  }
  console.log('options', options);
}

function load_station_id(type, station_id_dropdown){
  console.log("station id function invoked")
  console.log(type, station_id_dropdown)
  if(type!=="0") {
    $.ajax({
      url: "partials/get_station_id.php",
      type:"GET",
      data: {
        type: type,
      },
      success: function(data) {
        console.log(data)
        $(station_id_dropdown).html(data)
      }
    })
  }
}

$('#gen-station-type').change(function(){
    station_type=(this).value
    // $('#gen-form')[0].reset()
    if(station_type==="Daughter Booster Station"){
        console.log(station_type)
        load_mgs("#gen-mgs")
        $('#gen-mgs').prop("disabled", false)
        // $('.gen-mgs').css("display", "block")
    }
    else {
        console.log(station_type)
        // $('.gen-mgs')[0].reset()
        $('#gen-mgs').prop("disabled", "disabled")
        // $('.gen-mgs').css("display", "none")
    }
})

$('#eqp-station-type').change(function(){
  var station_type=(this).value
  // table_name = 'equipment'
  console.log(station_type)
  if((this).value==="Daughter Booster Station"){
      // console.log((this).value)
      load_mgs("#eqp-mgs")
      $('#eqp-mgs').prop("disabled", false)
      $('.eqp-mgs').prop("disabled", false)
  }
  else {
      // console.log((this).value)
      // $('#eqp-mgs').reset()
      $('#eqp-mgs').prop("disabled", "disabled")
      $('.eqp-mgs').prop("disabled", "disabled")
  }
  if(station_type!=="NA"){
    load_station_id(station_type,  '#eqp_station_id')
  }
})

$('#ins-station-type').change(function(){
  var station_type=(this).value
  // table_name = 'instrument'
  if(station_type==="Daughter Booster Station"){
      // console.log((this).value)
      load_mgs("#ins-mgs")
      $('#ins-mgs').prop("disabled", false)
      $('.ins-mgs').prop("disabled", false)
  }
  else {
      // console.log((this).value)
      // $('#ins-mgs').reset()
      $('#ins-mgs').prop("disabled", "disabled")
      $('.ins-mgs').prop("disabled", "disabled")
  }
  if(station_type!=="0"){
    load_station_id(station_type, '#ins_station_id')
  }
})


// fill stationary cascade id according to selected stations
$('#ins_station_id').change(function() {
  const station_id = $(this).val()
  const loading = "<option value='NA'>Loading...</option>"
  console.log(station_id);
  if(station_id === 'NA') {
    $('#instrument_stationary_cascade_id').val = ''
  } else {
    const stationary_cascade_station_id = $('#instrument_stationary_cascade_id').data('station_id')
    console.log(stationary_cascade_station_id);
    if(stationary_cascade_station_id === '' || stationary_cascade_station_id !== station_id) {
      $('#instrument_stationary_cascade_id').html(loading)
      // console.log('addfgd');
      $.ajax({
        url:'partials/get_stationary_cascade.php',
        type: 'GET',
        data : {
          station_id: station_id
        },
        success : function(response) {
          console.log(response);
          if(response['error'] === true) {
            alert(response['message'])
            $('#instrument_stationary_cascade_id').html(response['message'])
          } else {
            let st_cascades = "<option value='NA'>Select Statinary Cascade</option>"
            for(var item of response ) {
              st_cascades += `<option value='${item}'>${item}</option>`
            }
            $('#instrument_stationary_cascade_id').html(st_cascades)
            $('#instrument_stationary_cascade_id').data('station_id', station_id)
            console.log($('#instrument_stationary_cascade_id').data());
          }
        }
      })
    }
  }
})


// check if the entered Stationary cascade id, compressor id, dispenser id,
// temperature gauge id, pressure gauge id, mass flow meter id 
// is available or not

$(`input[name= stationary_cascade_id], 
    input[name=compressor_id],
    input[name=dispenser_id],
    input[name=temperature_gauge_id],
    input[name=low_pressure_gauge_id],
    input[name=medium_pressure_gauge_id],
    input[name=high_pressure_gauge_id],
    input[name=mass_flow_meter_id]`
    ).change(function() {
  let id = $(this).val()
  let element_name = $(this).attr('name')
  console.log(element_name);
  console.log(id, $(this));

  let already_registered = false;

  switch(element_name) {
    case 'stationary_cascade_id':
      already_registered = stationary_cascade_ids.includes(id)
      break;
    case 'compressor_id':
      already_registered = compressor_ids.includes(id)
      break;
    case 'dispenser_id':
      already_registered = dispenser_ids.includes(id)
      break;
    case 'temperature_gauge_id':
      already_registered = temp_gauge_ids.includes(id)
      break;
    case 'low_pressure_gauge_id':
      already_registered = low_pressure_gauge_ids.includes(id)
      break;
    case 'medium_pressure_gauge_id':
      already_registered = medium_pressure_gauge_ids.includes(id)
      break;
    case 'high_pressure_gauge_id':
      already_registered = high_pressure_gauge_ids.includes(id)
      break;
    case 'mass_flow_meter_id':
      already_registered = mass_flow_meter_ids.includes(id)
      break;
    default:
      already_registered = false;
  }

  if(id !== ''){
    if(already_registered) {
      id_not_available(this)
    } else {
      id_available(this)
    }
  } else {
    // remove the added css if the input field is empty
    remove_css([this])
  }
})




$('#resetform').click(function(){
  var x = $('#ins_info_form input')
  Array.prototype.forEach.call(x, child => {
    console.log(child)
    child.val=""
    console.log("---" + child)
  });
  // x.forEach(element => {
  //   element.reset()
  // });
})

// function load_station_id(type, station_id_dropdown){
//   if(type!=="0") {
//     $.ajax({
//       url: "partials/_fetch_station_id.php",
//       type:"POST",
//       data: {
//         type: type
//       },
//       success: function(data) {
//         console.log(data)
//         $(station_id_dropdown).html(data)
//       }
//     })
//   }
// }

// $('#eqp-station-type').change(function() {
//   var station_type=$(this).val()
// })

// $('#ins-station-type').change(function() {
//   var station_type=$(this).val()
//   load_station_id(station_type, '#ins_station_id')
// })




// get coordinates of current location
var x = document.getElementById("loc");

function getLocation() {
  console.log("clicked")
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
    x.value="-";
    console.log("value of x: " + x.value)
  }
}

function showPosition(position) {
  x.innerHTML=  position.coords.latitude + "," + position.coords.longitude;
  x.value = position.coords.latitude + "," + position.coords.longitude;
  console.log(x.value)
}


// add compressor
function add_compressor(count) {
  // console.log("func", i)
  var compressor_fileds = `
  <div id='compressor${count}' class='position-relative'>
  <h2>Compressor ${count}</h2>
  <div class='col-12 inp-group'>
      <div class='col-lg-5 col-12 '>
          <input required name='compressor_id' placeholder='ID' type='text' class='input col-12' />
      </div>
      <div class='col-lg-5 col-12 '>
          <input required type='text' name='compressor_make' placeholder='Make' class='input col-12' />
      </div>
  </div>
  <div class='col-12 inp-group'>
      <div class='col-lg-5 col-12 '>
          <input required type='text' name='compressor_model' placeholder='Model' class='input col-12' />
      </div>
      <div class='col-lg-5 col-12 '>
          <input required type='text' name='compressor_serial_number' placeholder='Serial Number' class='input col-12' />
      </div>
  </div>
  <div class='col-12 inp-group'>
      <div class='col-lg-5 col-12 '>
          <input required type='text' name='compressor_type' placeholder='Type' class='input col-12' />
      </div>
      <div class='col-lg-5 col-12 '>
          <!-- for occupying space -->
          <input type='text' placeholder='' class='input extra col-lg-5 col-12' readonly />
      </div>
  </div>
  <input type='button' id='cmp-del-btn-${count}' value='Remove' class='del-btn'>
  <hr>
  </div>`

  $('#compressor').append(compressor_fileds)
  // console.log($('#compressor').html())

  $(`#cmp-del-btn-${count}`).click(function(){
    $(`#compressor${count}`).remove()
  })

}

// display compressors
$('#compressor_count').change(function() {
  var count = parseInt($('#compressor_count').val())
  // console.log(count, count.type)
  $('#compressor').empty()
  for(i=1; i<=count; i++) {
    // console.log(i);
    add_compressor(i)
    // console.log("done", i)
  }
})


//display Dispensers

function add_dispenser(count) {

  var dispenser_fields=`
            <div id='dispenser${count}' class='position-relative'>
              <h2>Dispenser ${count}</h2>
              <div class='col-12 inp-group'>
                  <div class='col-lg-5 col-12 '>
                      <input required name='dispenser_id' placeholder='ID' type='text' class='input col-12' />
                  </div>
                  <div class='col-lg-5 col-12 '>
                      <input required type='text' name='dispenser_make' placeholder='Make' class='input col-12' />
                  </div>
              </div>
              <div class='col-12 inp-group'>
                  <div class='col-lg-5 col-12 '>
                      <input required type='text' name='dispenser_model' placeholder='Model Number' class='input col-12' />
                  </div>
                  <div class='col-lg-5 col-12 '>
                      <input required type='text' name='dispenser_type' placeholder='Type' class='input col-12' />
                  </div>
              </div>
              
              <hr>
            </div>`
  $('#dispenser').append(dispenser_fields)
  // $(`#dis-del-btn-${count}`).click(function(){
  //   $(`#dispenser${count}`).remove()
  // })
}

function add_remove_btn(id, parent) {
  var remove_btn = `<input type='button' id='${id}' value='Remove' /> <hr>`
  // console.log("dispenser", $(parent))
  $(parent).append(remove_btn)
  console.log("dispenser", $(parent))
  console.log("button", $(`#${id}`))
}



$('#add_dispenser_btn').click(function() {
  var bays_cnt = parseInt($('#bays_count').val())
  var dis_cnt = parseInt($('#dispenser_count').val())
  // console.log(bays_cnt, dis_cnt)
  if(bays_cnt === '' || dis_cnt === '') {
    alert('Enter Bays and dispensers first!')
  }else {
    $('#dispenser').empty()
    for(i=1; i<=dis_cnt; i++) {
      add_dispenser(i)
    }
    add_remove_btn('dis-del-btn', '#dispenser')
  }
})

$('#dis-del-btn').click(function() {
  console.log("clicked")
  $(`${parent} div:last`).remove()
  console.log("dispenser", $(parent))
})  



//reset form data
function reset_form_data(form_id) {
  $(`${form_id}`)[0].reset();
}



// calling api to store form data
function store_data(form_data_object, api_url, form_id) {
  console.log('func form data', form_data_object)
  $.ajax({
      url: api_url,
      type: 'post',
      data: form_data_object,
      success: function(response) {
          console.log('response', response)
          const data = JSON.parse(response)
          console.log(data)
          alert(data['message'])
          console.log(data['message'])
          if(data['error'] === false) {
              // $(`${form_id} :input`).val('');
              reset_form_data(form_id)
              let element_list = []
              if(form_id === '#eqp-form') {
                element_list = ['input[name=stationary_cascade_id]', 'input[name=compressor_id]', 'input[name=dispenser_id]']
              } else if(form_id === '#ins_info_form') {
                element_list = ['input[name=temperature_gauge_id]', 'input[name=pressure_gauge_id]', 'input[name=mass_flow_meter_id]']
              }

              // to remove the css of availability message after form reset
              remove_css(element_list)
          }
      }
  })
}


// getting "General information" data from UI
$('#gen_reg_submit').click(function(){
  // console.log('gen reg sub clicked')
  let form_arr = $('#gen-form').serializeArray()
  // console.log(form_arr)
  var form_obj={}
  for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
  }
  const api_url = "../CNG_API/master_reg_edit.php?apicall=insertGenInfo"
  console.log(form_obj)
  store_data(form_obj, api_url, '#gen-form')
})


$('#eqp_info_submit').click(function(){
  // console.log('gen reg sub clicked')
  let form_arr = $('#eqp-form').serializeArray()
  console.log(form_arr)
  var form_obj={}
  for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
  }

  if(stationary_cascade_ids.includes(form_obj['stationary_cascade_id'])) {
    alert('Stationary Id Already Exists.')
    return;
  } else if(compressor_ids.includes(form_obj['compressor_id'])) {
    alert('Compressor Id Already Exists.')
    return;
  } else if(dispenser_ids.includes(form_obj['dispenser_id'])) {
    alert('Dispenser Id Already Exists.')
    return;
  }

  const api_url = "../CNG_API/master_reg_edit.php?apicall=insertEquipInfo"
  console.log(form_obj)
  store_data(form_obj, api_url, '#eqp-form')
})



// getting "Instrument information" data from UI
$('#ins_info_sumbit').click(function(){
  // console.log('ins info sub clicked')
  let form_arr = $('#ins_info_form').serializeArray()
  // console.log(form_arr)
  var form_obj={}
  for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
  }

  if(temp_gauge_ids.includes(form_obj['temperature_gauge_id'])) {
    alert('Temperature Gauge Id Already Exists.')
    return;
  } else if(pressure_gauge_ids.includes(form_obj['pressure_gauge_id'])) {
    alert('Pressure Gauge Id Already Exists.')
    return;
  } else if(mass_flow_meter_ids.includes(form_obj['mass_flow_meter_id'])) {
    alert('Mass Flow Meter Id Already Exists.')
    return;
  }

  const api_url = "../CNG_API/master_reg_edit.php?apicall=insertInstrumentInfo"
  // console.log(form_obj)
  store_data(form_obj, api_url, '#ins_info_form')
})



