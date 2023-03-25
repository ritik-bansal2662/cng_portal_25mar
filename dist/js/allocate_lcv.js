$(document).ready(function(){
    // get data of LCVs mapped to MGS and fill into table
    table_data()
})

// function to get data of LCVs mapped to MGS and fill into table
function table_data() {
  $.ajax({
    url:"partials/mgs_to_lcv_data.php",
    type:"GET",
    success: function(response) {
      console.log(response);
      const data = JSON.parse(response)
      var table_row = ''
      if(data['data_available'] === false) {
        table_row = '<tr><td>-</td><td> No Data Available </td><td> No Data Available </td></tr>'
      } else {
        for(var i in data) {
          console.log(i, data[i]);
          if(i === "data_available") {
            continue;
          }
          table_row += `<tr>
              <td>${parseInt(i)+1}</td>
              <td>${data[i][0]}</td>
              <td>${data[i][1]}</td>
              <td>${data[i][2]}</td>
            </tr>`
        }
      }
      $('#table_body').html(table_row)
    }
  })
}


// $('#organization_id').change(function() {
//     var org_id = $(this).val()
//     // fill LCV number when a organization is selected
//     $.ajax({
//         url:"partials/_fetch_lcv_number_from_org.php",
//         type:"POST",
//         data: {
//             org : org_id,
//             table : 'general'
//         },
//         success: function(data) {
//           console.log(data)
//           $('#lcv').html(data)
//         }
//       })
// })

$('#lcv').change(function() {
  var lcv_num = $(this).val()
  console.log(lcv_num);
  // fill LCV number when a organization is selected
  $.ajax({
      url:"partials/lcv_mapped_check_api.php",
      type:"POST",
      data: {
          lcv : lcv_num,
      },
      success: function(response) {
        console.log(response);
        const data = JSON.parse(response)
        console.log(data)
        if(data['isMapped'] === true) {
          alert(data['message'])
          
          //to select the value of mgs to which LCV is mapped
          // $('input[name=lcv_mgs]').val(data['mgs'])
          console.log('--mgs', $('input[name=lcv_mgs]').val());
        } else {
          // alert(data['message'])
        }
        // $('#lcv').html(data)
      }
    })
})


// let mgs_array = []
// let dbs_array = []
// let mgs_str=''
// let dbs_str = ''

// function get_dbs(mgs_id) {
//     $.ajax({
//         url:'partials/get_dbs.php',
//         type: 'GET',
//         data: {
//             mgsid : mgs_id
//         },
//         success: function(response) {
//             console.log(response)
//         }
//     })
// }


// function checked(vehicle_type) {
//     var mgs = $('input[name=lcv_'+vehicle_type+']:checked')
//     var resultStr=''
//     mgs_array = []
//     if(mgs.length > 0) {
//       mgs.each(function() {
//         // get_dbs($(this).val())
//         mgs_array.push($(this).val())
//         resultStr += $(this).val() + ', '
//       })
//     }
//     console.log(resultStr)
//     // console.log('array', mgs_array)
//     return resultStr
// }


//reset form
function reset_form_data(form_id) {
  $(`${form_id}`)[0].reset();
}


// store data in database
function store_data(form_obj) {
  $.ajax({
    url: 'partials/allocate_lcv_api.php',
    type: 'POST',
    data: form_obj,
    success: function(response) {
        console.log(response)
        const data = JSON.parse(response)
        console.log(data)
        alert(data['message'])
        // location.reload()
        if(data['error'] === false) {
          reset_form_data('#lcv_allocation_form')
          
          // refresh table data of LCVs mapped to MGS when a lcv is mapped or updated
          table_data()
        }
    }
  })
}


// to get all selected MGS
function getSelectedMGSArray(){
  var stationIDs = $("#station_id input:checkbox:checked").map(function(){
    return $(this).val();
  }).get(); // <---- get() method return a true array
  return stationIDs;
};



// when allocate button is clicked
$('#allocate_btn').click(function() {
  // let form_arr = $('#lcv_allocation_form').serializeArray()
    // console.log('mgs', $('input[name=lcv_mgs]').val());
    let mgs_array = getSelectedMGSArray();
    console.log(mgs_array)
    console.log(mgs_array.toString());
    let lcv_num = $('#lcv').val()
    let form_obj= {}
    form_obj['lcv'] = lcv_num
    form_obj['lcv_mgs'] = mgs_array.toString() // the Array.toString() method returns a string with all array values separated by commas:
    // mgs_str = checked('mgs')
    // dbs_str = checked('dbs')
    // form_obj['lcv_mgs'] = mgs_str
    // form_obj['lcv_dbs'] = dbs_str
    // for(let ele of form_arr) {
    //   let first=ele.name
    //   let second = ele.value
    //   form_obj[first] = second
    // }
    console.log(form_obj)
    console.log(form_obj['lcv_mgs'])
    if(form_obj['lcv']==='NA') {
        alert('Please Select LCV')
        return;
    } else if(form_obj['lcv_mgs'] === '' || form_obj['lcv_mgs'] === undefined || form_obj['lcv_mgs'] === null){
      alert('Must Select atleast one MGS')
      return;
    }else {
        store_data(form_obj)
        console.log(form_obj)
    }
})
