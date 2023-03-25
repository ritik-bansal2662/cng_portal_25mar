$(document).ready(function(){

    // filling organizations list in employee tab
    $.ajax({
      url:"partials/_fetch_org.php",
      type:"POST",
      success: function(data) {
        // console.log(data)
        $('#Emp_Orgnization_id').html(data)
      }
    })
    // $.ajax({
    //     url:"../CNG_API/admin_tab.php?apicall=fetch_orgs",
    //     type:"GET",
    //     success: function(data) {
    //       console.log(data)
    //       console.log(data['error'])
    //       $('#Emp_Orgnization_id').html(data)
    //     }
    //   })
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
        type: 'POST',
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

  

// ###############################################################################################################
// ##############################################################################################################


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

function validateEmail(email) {

    var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
  
    if (email.match(validRegex)) {
  
    //   alert("Valid email address!");
  
    //   document.form1.text1.focus();
  
      return true;
  
    } else {
  
    //   alert("Invalid email address!");
  
    //   document.form1.text1.focus();
  
      return false;
  
    }
  
}




// storing organization registration form data
$('#org_reg_submit').click(function(){
    // console.log('org sub clicked')
    let form_arr = $('#org_reg_form').serializeArray()
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
        // console.log("form obj", form_obj)
        const api_url = "../CNG_API/admin_tab.php?apicall=org_reg"
        store_data(form_obj, api_url, '#org_reg_form')
    }
    
})

// storing emp registration form data
$('#emp_reg_submit').click(function(){
    // console.log('emp sub clicked')
    let form_arr = $('#emp_reg_form').serializeArray()
    var form_obj={}
    for(let ele of form_arr) {
        let first=ele.name
        let second = ele.value
        form_obj[first] = second
    }
    if(form_obj['Emp_Contact_Number'].length !== 10) {
        alert("Mobile number must have 10 digits.")
        return
    } else if(form_obj['Emp_Email_Id'] !== undefined && !validateEmail(form_obj['Emp_Email_Id'])){
        alert('Enter a valid Email address.')
        return;
    } else {
        // console.log("form obj", form_obj)
        const api_url = "../CNG_API/admin_tab.php?apicall=emp_reg"
        store_data(form_obj, api_url, '#emp_reg_form')
    }
    
})
