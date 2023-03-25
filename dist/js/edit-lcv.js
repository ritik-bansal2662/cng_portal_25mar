
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
            console.log(data)
            alert(data['message'])
            console.log(data['message'])
            if(data['error'] === false) {
                reset_form_data(form_id)
            }
        }
    })
}




$(document).ready(function() {
    // for lcv general info
    // $.ajax({
    //     url: "partials/_fetch_lcv_org.php",
    //     type: "POST",
    //     data: {
    //         table: 'reg_lcv',
    //     },
    //     success: function(response) {
    //         console.log(response)
    //         $('#Lcv_Registered_To').html(response)
    //     }
    // })


    // for lcv cascade info
    // $.ajax({
    //     url: "partials/_fetch_lcv_org.php",
    //     type: "POST",
    //     data: {
    //         table: 'reg_instrument_lcv',
    //     },
    //     success: function(response) {
    //         console.log(response)
    //         $('#lcv_registered_to').html(response)
    //     }
    // })
})



// for lcv general info

// $('#Lcv_Registered_To').change(function() {
//     let org = $(this).val()
//     console.log(org)
//     $.ajax({
//         url: "partials/_fetch_lcv_number_from_org.php",
//         type: "POST",
//         data: {
//             org: org,
//             table: 'general'
//         },
//         success: function(response) {
//             console.log(response)
//             $('#Lcv_Num').html(response)
//         }
//     })
// })

$('#Lcv_Num').change(function() {
    let lcv_num = $(this).val()
    console.log(lcv_num)
    if(lcv_num !== 'NA') {
        $.ajax({
            url: "../CNG_API/read_lcv_gen_info.php",
            type: "POST",
            data: {
                id: lcv_num
            },
            success: function(response) {
                console.log(response)
                // $('#Lcv_Num').html(response)
                const data = JSON.parse(response)
                console.log(data)
                alert(data['message'])
                if(data['error'] === false){
                    for(key in data) {
                        if(key==='error' || key === 'message') {
                            continue
                        }
                        console.log(key, data[key])
                        $(`#${key}`).val(data[key])
                    }
                }
            }
        })
    }
})

$('#lcv_gen_edit_submit').click(function(){
    let form_arr = $('#lcv_gen_edit_form').serializeArray()
    console.log(form_arr)
    var form_obj={}
    for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
    }
    const api_url = "../CNG_API/reg_lcv.php?apicall=updateLcvGenInfo"
    console.log(form_obj)
    store_data(form_obj, api_url, '#lcv_gen_edit_form')
})



//for lcv cascade info


// $('#lcv_registered_to').change(function() {
//     let org = $(this).val()
//     console.log(org)
//     $.ajax({
//         url: "partials/_fetch_lcv_number_from_org.php",
//         type: "POST",
//         data: {
//             org: org,
//             table: 'cascade'
//         },
//         success: function(response) {
//             console.log(response)
//             $('#lcv_num').html(response)
//         }
//     })
// })

$('#lcv_num').change(function() {
    let lcv_num = $(this).val()
    console.log(lcv_num)
    if(lcv_num !== 'NA'){
        $.ajax({
            url: "../CNG_API/read_lcv_instrument_info.php",
            type: "POST",
            data: {
                id: lcv_num
            },
            success: function(response) {
                console.log(response)
                const data = JSON.parse(response)
                console.log(data)
                alert(data['message'])
                if(data['error'] === false){
                    for(key in data) {
                        if(key==='error' || key === 'message') {
                            continue
                        }
                        console.log(key, data[key])
                        $(`#${key}`).val(data[key])
                    }
                }
            }
        })
    }
})

$('#lcv_instrument_edit_submit').click(function(){
    let form_arr = $('#lcv_instrument_edit_form').serializeArray()
    console.log(form_arr)
    var form_obj={}
    for(let ele of form_arr) {
      let first=ele.name
      let second = ele.value
      form_obj[first] = second
    }
    const api_url = "../CNG_API/reg_lcv.php?apicall=updateLcvCascadeInfo"
    console.log(form_obj)
    store_data(form_obj, api_url, '#lcv_instrument_edit_form')
})








//#####################################################################################
//switching between General INformation and cascade information tabs.


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

