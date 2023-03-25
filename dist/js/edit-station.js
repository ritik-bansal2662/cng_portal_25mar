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


$('#eqp-station-type').change(function(){
  var station_type=(this).value
  console.log(station_type);
  if((this).value==="Daughter Booster Station"){
    // console.log((this).value)
    $('#eqp_mgsId').css("display", "block")
    $('.eqp_mgsId').css("display", "block")
    
    $.ajax({
      url:"partials/_fetch_mgs.php",
      type:"POST",
      success: function(data) {
        $('#eqp_mgsId').html(data)
      }
    })
  }
  else {
    // console.log((this).value)
    $('#eqp_mgsId').css("display", "none")
    $('.eqp_mgsId').css("display", "none")
    // $('.mgs-extra').css("display", "none")
    $('#eqp_mgsId').val('')
  }
  $.ajax({
    url:"partials/_fetch_station_id.php",
    type:"POST",
    data: {
      table:'equipment',
      type:station_type
    },
    success: function(data) {
      console.log(data)
      $('#eqp-station-id').html(data)
      // $('#gen_notification_approver_id').html(data)
    }
  })
})

$('#gen-station-type').change(function(){
  var station_type=(this).value
  if((this).value==="Daughter Booster Station"){
    // console.log((this).value)
    $('#gen_mgsId').css("display", "block")
    $('.gen_mgsId').css("display", "block")
    //get the MGS IDs' from database
    $.ajax({
      url:"partials/_fetch_mgs.php",
      type:"POST",
      success: function(data) {
        $('#gen_mgsId').html(data)
      }
    })
  }
  else {
    console.log((this).value)
    $('#gen_mgsId').css("display", "none")
    $('.gen_mgsId').css("display", "none")
  }
  $.ajax({
    url:"partials/_fetch_station_id.php",
    type:"POST",
    data: {
      table:'general',
      type:station_type
    },
    success: function(data) {
      // console.log(data)
      $('#gen_station_id').html(data)
      // $('#gen_notification_approver_id').html(data)
    }
  })
})

$('#ins-station-type').change(function(){
  var station_type=(this).value
  console.log(station_type)
  if((this).value==="Daughter Booster Station"){
    // console.log((this).value)
    $('#ins_mgsId').css("display", "block")
    $('.ins_mgsId').css("display", "block")
    $.ajax({
      url:"partials/_fetch_mgs.php",
      type:"POST",
      success: function(data) {
        $('#ins_mgsId').html(data)
      }
    })
  }
  else {
    // console.log((this).value)
    $('#ins_mgsId').css("display", "none")
    $('.ins_mgsId').css("display", "none")
    $('#ins_mgsId').val('')
  }
  $.ajax({
    url:"partials/_fetch_station_id.php",
    type:"POST",
    data: {
      table:'instrument',
      type:station_type
    },
    success: function(data) {
      console.log(data)
      $('#ins-station-id').html(data)
      console.log($('#ins-station-id'))
    }
  })
})


$('#get_eqp_details').click(function() {
  var station_type = $('#eqp-station-type').val()
  var station_id = $('#eqp-station-id').val()
  console.log(station_id);
  if(station_id === 'NA') {
    alert('Please Select Station Id First.')
  } else if(station_id === null ) {
    alert('Please Select Station type then select Station Id for getting details.')
  } else {
// })
// $('#eqp-station-id').change(function(){
  // var station_id=(this).value
  // console.log(station_id)
    $.ajax({
      url:"../CNG_API/read_master_equipment_info.php",
      type:"GET",
      data: {
        id:station_id
      },    
      success: function(data) {
        console.log(data)
        const response = JSON.parse(data)
        console.log(response)
        for (var key in response) {
          console.log(key +" : " + response[key]);
          if(key==="mgsId"){
            console.log("mgs")
            $('select option:contains("'+ response[key] +'")').prop('selected',true);
          }
          else if(key!=="error" && key !=="message") {
            var name = '#'+key
            $(name).val(response[key])
            console.log($(name).val())
          }
        }
      }
    })
  }
})

$('#gen_station_id').change(function() {
  var station_id=(this).value
  console.log('station id', station_id)
  $.ajax({
    url:"../CNG_API/read_master_gen_info.php",
    method:"POST",
    data: {
      id:station_id
    },
    // dataType: 'JSON',
    success: function(data) {
      console.log('response', data)
      const response = JSON.parse(data)
      for (var key in response) {
        console.log(key +" : " + response[key]);
        if(key==="mgsId"){
          $('select option:contains("'+ response[key] +'")').prop('selected',true);
        }
        else if(key!=="error" && key !=="message") {
          var name = '#'+key
          $(name).val(response[key])
          console.log($(name).val())
        }
      }
    }
  })
})

$('#ins-station-id').change(function(){
  var station_id=(this).value
  console.log(station_id)
  $.ajax({
    url:"../CNG_API/read_master_instrument_info.php",
    type:"POST",
    data: {
      id:station_id
    },
    // dataType: 'JSON',
    success: function(data) {
      console.log(data)
      const response =JSON.parse(data)
      console.log(response)
      // const response = JSON.parse(data)
      for (var key in response) {
        console.log(key +" : " + response[key]);
        if(key==="mgsId"){
          console.log("mgs")
          $('select option:contains("'+ response[key] +'")').prop('selected',true);
        }
        else if(key!=="error" && key !=="message") {
          var name = '#'+key
          $(name).val(response[key])
          console.log($(name).val())
        }
      }
    }
  })
})

var x = document.getElementById("Latitude_Longitude");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
    x.value="-";
  }
}

function showPosition(position) {
  x.value = position.coords.latitude + "," + position.coords.longitude;
  console.log(x.value)
}


//#################################################################################################################
//storing data
//#################################################################################################################


// reset form data
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
              reset_form_data(form_id)
          }
      }
  })
}


// storing edited "General Information"
$('#gen_edit_submit').click(function(){
  // console.log('gen edit sub clicked')
  let form_arr = $('#gen_edit_form').serializeArray()
  // console.log(form_arr)
  var form_obj={}
  for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
  }
  const api_url = "../CNG_API/master_reg_edit.php?apicall=updateGenInfo"
  console.log(form_obj)
  store_data(form_obj, api_url, '#gen_edit_form')
})

$('#eqp_edit_submit').click(function(){
  let form_arr = $('#eqp_edit_form').serializeArray()
  // console.log(form_arr)
  var form_obj={}
  for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
  }
  const api_url = "../CNG_API/master_reg_edit.php?apicall=updateEquipInfo"
  console.log(form_obj)
  store_data(form_obj, api_url, '#eqp_edit_form')
})

// storing edited "Instrument Information"
$('#ins_edit_submit').click(function(){
  // console.log('ins edit sub clicked')
  let form_arr = $('#ins_edit_form').serializeArray()
  // console.log(form_arr)
  var form_obj={}
  for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
  }
  const api_url = "../CNG_API/master_reg_edit.php?apicall=updateInstrumentInfo"
  // console.log(form_obj)
  store_data(form_obj, api_url, '#ins_edit_form')
})

