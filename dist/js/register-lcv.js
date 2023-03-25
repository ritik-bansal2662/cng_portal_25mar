$(document).ready(function(){
    // $.ajax({
    //   url:"partials/_fetch_org.php",
    //   type:"POST",
    //   success: function(data) {
    //     // console.log(data)
    //     $('#Lcv_Registered_To').html(data)
    //     // console.log($('#Lcv_Registered_To').html(data))
    //     $('#cas_lcv_registered_to').html(data)
    //   }
    // })
})


$(".gen").click(function(){
    $(this).addClass('main-active');
    $('.cas').removeClass('main-active');
    $('.gen-info').addClass('content-active');
    $('.cas-info').removeClass('content-active');
})


$(".cas").click(function(){
    $(this).addClass('main-active');
    $('.gen').removeClass('main-active');
    $('.cas-info').addClass('content-active');
    $('.gen-info').removeClass('content-active');
})

//#################################################################################################################
//storing data
//#################################################################################################################



// reset form data
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
          // alert(response)
          console.log(data)
          alert(data['message'])
          console.log(data['message'])
          if(data['error'] === false) {
              reset_form_data(form_id)
          }
      }
  })
}



// storing "LCV General Information"
$('#lcv_general_submit').click(function(){
  console.log('lcv gen sub clicked')
  let form_arr = $('#lcv_general_form').serializeArray()
  console.log(form_arr)
  var form_obj={}
  // form_obj['lcv_mgs'] = Checked_mgs()
  for(let ele of form_arr) {
    let first=ele.name
    let second = ele.value
      // if(ele.name === 'lcv_mgs') {
      //   let second = ele.name.join()
      // } else {
      // }
      form_obj[first] = second
  }
  const api_url = "../CNG_API/reg_lcv.php?apicall=insertLcvGenInfo"
  console.log(form_obj)
  store_data(form_obj, api_url, '#lcv_general_form')
})



// storing edited "LCV Cascade Information"
$('#lcv_cascade_submit').click(function(){
  console.log('Cascade info sub clicked')
  let form_arr = $('#lcv_cascade_form').serializeArray()
  console.log(form_arr)
  var form_obj={}
  for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
  }
  const api_url = "../CNG_API/reg_lcv.php?apicall=insertLcvCascadeInfo"
  console.log(form_obj)
  store_data(form_obj, api_url, '#lcv_cascade_form')
})

// $('#cas_lcv_registered_to').change(function() {
//   var org_id = $(this).val()
//   console.log(org_id);
//   if(org_id !== 'NA') {
//     $.ajax({
//       url : "partials/get_lcv_num.php",
//       type: "POST",
//       data: {
//         org : org_id
//       },
//       success: function(response) {
//         console.log(response);
//         $('#lcv_num').html(response)
//       }
//     })
//   }
// })


$('#lcv_mgs').change(function() {
  console.log($(this).val())
})

$('input[name=vehicle]:checked').change(function() {
  let output=''
  console.log($(this).val())
})





  
// var markedCheckbox = document.querySelectorAll('input[type="checkbox"]:checked');
// for (var checkbox of markedCheckbox) {
// document.body.append(checkbox.value + ' ');
// }


