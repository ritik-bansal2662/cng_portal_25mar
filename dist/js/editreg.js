// #############################################################################################################

//editing organization and employee details (editreg.php file)

// ##############################################################################################################

$(document).ready(function () {

    // filling organizations list in employee tab
    $.ajax({
        url:"partials/_fetch_org.php",
        type:"POST",
        success: function(data) {
          // console.log(data)
          $('#Emp_Org_id').html(data)
          $('#organization_id').html(data)
        }
    })

})

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
            // console.log(data)
            alert(data['message'])
            // console.log(data['message'])
            if(data['error'] === false) {
                // $(`${form_id} :input`).val('');
                reset_form_data(form_id)
            }
        }
    })
}


// for showing organization form by clicking 'organization' tab
$(".org").click(function(){
    $(this).addClass('main-active');
    $('.emp').removeClass('main-active');
    $('.organization').addClass('content-active');
    $('.employee').removeClass('content-active');
})

// for showing employee form by clicking 'employee' tab
$(".emp").click(function(){
    $(this).addClass('main-active');
    $('.org').removeClass('main-active');
    $('.employee').addClass('content-active');
    $('.organization').removeClass('content-active');
})

// getting location coordinates
var x = document.getElementById("Org_Location");

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
    x.innerHTML=  position.coords.latitude + ", " + position.coords.longitude;
    x.value = position.coords.latitude + "," + position.coords.longitude;
    console.log(x.value)
}






// // getting organization data from database
// $('#orgMobile').click(function(){

//     console.log('org sub clicked')
//     // let form_arr = $('#org_reg_form').serializeArray()
//     const org_mobile_number = $('#id').value
//     console.log('number', org_mobile_number)
//     $.ajax({
//         url: '../CNG_API/read_main_org.php',
//         type: 'POST',
//         data: {
//             id: org_mobile_number
//         },
//         success: function(response) {
//             console.log('response', response)
//             const data = JSON.parse(response)
//             // alert(response)
//             console.log(data)
//             // alert(data['message'])
//             // console.log(data['message'])
//             // if(data['error'] === false) {
//                 // $(`${form_id} :input`).val('');
//                 // reset_form_data(form_id)
//             // }
//         }
//     })
    
// })


// fetching organizations list
// $('#org_type').change(function () {
//     let org_type = $(this).val()
//     console.log(org_type)
//     $.ajax({
//         url: "partials/_fetch_org_filter_type.php",
//         data: {
//             org_type: org_type
//         }, 
//         method: 'GET',
//         success : function(response) {
//             console.log(response)
//             $('#organization_id').html(response)
//         }
//     })
// })



//fetching data using org mobile number for editing organization details
// $('#orgMobile').click(function(){
//     // console.log('org sub clicked')
//     const org_mobile_number = $('#orgMobileInp').val()
//     if(org_mobile_number==='') {
//         alert('Enter Mobile Number first.')
//         return
//     }
//     // console.log('number', org_mobile_number)
//     $.ajax({
//         url:"../CNG_API/read_main_org.php",
//         type:"POST",
//         data: {
//             id : org_mobile_number
//         },
//         success: function(response) {
//             // console.log('org data function invoked')
//             console.log('response', response)
//             const data = JSON.parse(response)
//             // alert(response)
//             console.log('data', data)
//             if(data['error']===true) {
//                 alert(data['message'])
//             } else {
//                 for(var key in data) {
//                     if(key==='error' || key === 'message') {
//                         continue
//                     } else {
//                         var element_id = `#${key}`
//                         // console.log(element_id)
//                         $(element_id).val(data[key])
//                     }
//                 }
//             }
//         }
//     })
// })


// fetching data using org short name(case sensitive) for editing organization details
$('#orgMobile').click(function() {
    let org_id = $('#organization_id').val()
    if(org_id ==='NA') {
        alert('Select Organization Id')
        return
    }
    // console.log('number', org_mobile_number)
    $.ajax({
        url:"../CNG_API/read_main_org.php",
        type:"POST",
        data: {
            id : org_id
        },
        success: function(response) {
            // console.log('org data function invoked')
            console.log('response', response)
            const data = JSON.parse(response)
            // alert(response)
            console.log('data', data)
            if(data['error']===true) {
                alert(data['message'])
            } else {
                alert(data['message'])
                for(var key in data) {
                    if(key==='error' || key === 'message') {
                        continue
                    } else {
                        var element_id = `#${key}`
                        // console.log(element_id)
                        $(element_id).val(data[key])
                    }
                }
            }
        }
    })
})


// storing edited organization details
$('#org_edit_submit').click(function(){
    // console.log('org edit sub clicked')
    let form_arr = $('#org_edit_form').serializeArray()
    // console.log(form_arr)
    var form_obj={}
    for(let ele of form_arr) {
        let first=ele.name
        let second = ele.value
        form_obj[first] = second
    }
    if(form_obj['Org_Mobile_Number'].length !== 10) {
        alert("Mobile number must have 10 digits.")
        return
    } else {
        const api_url = "../CNG_API/update_main_org.php"
        console.log(form_obj)
        store_data(form_obj, api_url, '#org_edit_form')
    }
})



$('#Emp_Org_id').change(function() {
    let org_id = $(this).val()
    // filling employee list in employee tab
    $.ajax({
        url:"partials/_fetch_emp_details.php",
        type:"POST",
        data: {
            org : org_id
        },
        success: function(data) {
          console.log(data)
          $('#Employee_id').html(data)
        }
    })
})



// fetch employee details for editing
$('#empMobile').click(function(){
    const emp_mob_number = $('#Employee_id').val()
    if(emp_mob_number === null ) {
        alert('Select Organization first and then select Employee Id.')
        return
    } else if(emp_mob_number === 'NA') {
        alert('Select Employee Id.')
        return
    }
    // console.log('number', emp_mobile_number)
    $.ajax({
        url:"../CNG_API/read_emp.php",
        type:"POST",
        data: {
            Emp_Contact_Number : emp_mob_number
        },
        success: function(response) {
            // console.log('emp data function invoked')
            console.log('response', response)
            const data = JSON.parse(response)
            console.log('data', data)
            if(data['error']===true) {
                alert(data['message'])
            } else {
                alert(data['message'])
                for(var key in data) {
                    if(key==='error' || key === 'message') {
                        continue
                    } else {
                        var element_id = `#${key}`
                        // console.log(element_id)
                        $(element_id).val(data[key])
                    }
                }
            }
        }
    })
})


// storing edited organization details
$('#emp_edit_submit').click(function(){
    // console.log('emp edit sub clicked')
    let form_arr = $('#emp_edit_form').serializeArray()
    // console.log(form_arr)
    var form_obj={}
    for(let ele of form_arr) {
        let first=ele.name
        let second = ele.value
        form_obj[first] = second
    }
    if(form_obj['Emp_Contact_Number'].length !== 10) {
        alert("Mobile number must have 10 digits.")
        return
    } else {
        const api_url = "../CNG_API/admin_tab.php?apicall=emp_update"
        console.log(form_obj)
        store_data(form_obj, api_url, '#emp_edit_form')
    }
})
