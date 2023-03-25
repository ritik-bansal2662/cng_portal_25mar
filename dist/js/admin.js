// $(document).ready(function(){
//     $.ajax({
//       url:"partials/_fetch_dbs.php",
//       type:"POST",
//       success: function(data) {
//         // console.log(data)
//         $('#edit-dbsid').html(data)
//         // $('#cas_lcv_registered_to').html(data)
//       }
//     })
//     $.ajax({
//         url:"partials/_fetch_mgs.php",
//         type:"POST",
//         success: function(data) {
//         //   console.log(data)
//           $('#edit-mgsid').html(data)
//         //   console.log("MGS")
//         //   console.log($('#mgsid').value)
//           // $('#cas_lcv_registered_to').html(data)
//         }
//     })
// })

$(document).ready(function () {

    // filling organizations list in edit tab
    $.ajax({
        url:"partials/_fetch_org.php",
        type:"POST",
        success: function(data) {
          console.log(data)
          $('#Orgnization_Id').html(data)
        },
        error: function(error) {
            console.log('Error in Fetching Organization list')
            console.log(error);
        }
    })

})



$('#role-edit').click(function(){
    $('.assign').removeClass('content-active')
    $('.edit').addClass('content-active')
})

$('#gotoassign').click(function(){
    $('.edit').removeClass('content-active')
    $('.assign').addClass('content-active')
})

// let employee_details = []

$('#org').change(function(){
    let org = (this).value
    console.log(org)
    $.ajax({
        url: 'partials/_fetch_emp_id.php',
        type:'post',
        data: {
            org:org
        },
        success: function(response){
            console.log(response)
            // const data = JSON.parse(response)
            // employee_details = data
            // console.log(data);
            // console.log(employee_details);
            // for(var i in data) {
            //     if(data[i][0] !== undefined){
            //         console.log(data[i][0], data[i][1])
            //     }
            // }
            $('#id').html(response)
        }
    })
})

$('#user-role').change(function() {
    const role = $(this).val()
    if(role === 'Admin') { // || role === 'Operator'
        //disable notification station type dropdown
        $('#notif_approver_station_type').prop('disabled', 'disabled');

        //reset notification station type dropdown
        $('#notif_approver_station_type').prop('selectedIndex', 0)

        //disable DBS dropdown
        $('#dbsid').prop('disabled', 'disabled');

        //reseting value of DBS
        $('#dbsid').prop('selectedIndex', 0)

        //disable MGS dropdown
        $('#mgsid').prop('disabled', 'disabled');

        //reset value of MGS
        $('#mgsid').prop('selectedIndex', 0)

    } else {
        //enable notification station type dropdown
        $('#notif_approver_station_type').prop('disabled', false);
    }
})


// displaying and hiding MGS and DBS on the basis of selection of notification approver station type
$('#notif_approver_station_type').change(function () {
    let station_type = $(this).val()
    console.log(station_type)
    if(station_type === 'MGS') {
        //enable MGS dropdown
        $('#mgsid').prop('disabled', false);

        //disable DBS dropdown
        $('#dbsid').prop('disabled', 'disabled');

        //reseting value of DBS
        $('#dbsid').prop('selectedIndex', 0)

    } else if(station_type === 'DBS') {
        //enable DBS dropdown
        $('#dbsid').prop('disabled', false);

        //disable MGS dropdown
        $('#mgsid').prop('disabled', 'disabled');
        // $('#mgslabel').addClass('d-none')

        //reset value of MGS
        $('#mgsid').prop('selectedIndex', 0)
    } else { // if value is 'NA'
        //hide MGS dropdown
        $('#mgsid').prop('disabled', 'disabled');

        //hide DBS dropdown
        $('#dbsid').prop('disabled', 'disabled');

        //reset value of MGS
        $('#mgsid').prop('selectedIndex', 0)

        //reseting value of DBS
        $('#dbsid').prop('selectedIndex', 0)
    }
})



$('#edit_user_role').change(function() {
    const edit_role = $(this).val()
    if(edit_role === 'Admin') { // || edit_role === 'Operator'
        //disable notification station type dropdown
        $('#notif_approver_edit_station_type').prop('disabled', 'disabled');

        //reset notification station type dropdown
        $('#notif_approver_edit_station_type').prop('selectedIndex', 0)

        //disable DBS dropdown
        $('#edit-dbsid').prop('disabled', 'disabled');

        //reseting value of DBS
        $('#edit-dbsid').prop('selectedIndex', 0)

        //disable MGS dropdown
        $('#edit-mgsid').prop('disabled', 'disabled');

        //reset value of MGS
        $('#edit-mgsid').prop('selectedIndex', 0)

    } else {
        //enable notification station type dropdown
        $('#notif_approver_edit_station_type').prop('disabled', false);
    }
})

// displaying and hiding MGS and DBS on the basis of selection of notification approver station type
$('#notif_approver_edit_station_type').change(function () {
    let edit_station_type = $(this).val()
    console.log(edit_station_type)
    if(edit_station_type === 'MGS') {
        //enable MGS dropdown
        $('#edit-mgsid').prop('disabled', false);

        //disable DBS dropdown
        $('#edit-dbsid').prop('disabled', 'disabled');

        //reseting value of DBS
        $('#edit-dbsid').prop('selectedIndex', 0)

    } else if(edit_station_type === 'DBS') {
        //enable DBS dropdown
        $('#edit-dbsid').prop('disabled', false);

        //disable MGS dropdown
        $('#edit-mgsid').prop('disabled', 'disabled');
        // $('#mgslabel').addClass('d-none')

        //reset value of MGS
        $('#edit-mgsid').prop('selectedIndex', 0)
    } else { // if value is 'NA'
        //hide MGS dropdown
        $('#edit-mgsid').prop('disabled', 'disabled');

        //hide DBS dropdown
        $('#edit-dbsid').prop('disabled', 'disabled');

        //reset value of MGS
        $('#edit-mgsid').prop('selectedIndex', 0)

        //reseting value of DBS
        $('#edit-dbsid').prop('selectedIndex', 0)
    }
})


function reset_form_data(form_id) {
    $(`${form_id}`)[0].reset();
}

function store_data(form_data_object, form_id) {
    console.log(`function form data ${form_data_object}`)
    $.ajax({
        url:"../CNG_API/admin_tab.php?apicall=admin_module",
        type: 'POST',
        data: form_data_object,
        success: function(response) {
            console.log('response', response)
            const data = JSON.parse(response)
            alert(data['message'])
            if(data['error']===false) {
                reset_form_data(form_id)
                // location.reload()
            }
            // history.go(-1)
        }
    })
}

function update_data(form_data_object, form_id) {
    console.log("update function invoked",form_data_object)
    $.ajax({
        url:'api/update_role.php',
        type: 'post',
        data: form_data_object,
        success: function(response) {
            console.log(response)
            const data = JSON.parse(response)
            alert(data['message'])
            if(data['error']===false) {
                reset_form_data(form_id)
            }
        }
    })
}

$('#admin_submit').click(function(){
    let form_arr = $('#admin_form').serializeArray()
    var form_obj={}
    for(let ele of form_arr) {
        let first=ele.name
        let second = ele.value
        form_obj[first] = second
    }
    store_data(form_obj, '#admin_form')
    console.log("form obj", form_obj)
})

$('#Orgnization_Id').change(function() {
    let org_id = $(this).val()
    // filling employee list in edit tab
    $.ajax({
        url:"partials/_fetch_emp_details.php",
        type:"POST",
        data: {
            org : org_id
        },
        success: function(data) {
          console.log(data)
          $('#Employee_id').html(data)
        },
        error: function(error) {
            console.log('Error in Fetching Employee List',error);
        }
    })
})

$('#edit_submit').click(function(){
    let edit_form_arr = $('#edit-form').serializeArray()
    console.log(edit_form_arr)
    var edit_form_obj={}
    for(let ele of edit_form_arr) {
        let first=ele.name
        let second = ele.value
        edit_form_obj[first] = second
    }
    console.log(edit_form_obj)
    if(edit_form_obj['edit_user_role'] === 'NA') {
        alert('Please Select Role for user.')
        return
    }else {
        update_data(edit_form_obj, '#edit-form')
    }
    // console.log("form obj", form_obj)
})

$('#getDetails').click(function(){
    var emp_mob = $('#Employee_id').val()
    var org_id = $('#Orgnization_Id').val()
    if(org_id === 'NA' ){
        alert("Please select Organization and then Employee Id.")
        return
    } else if(emp_mob === 'NA' ) {
        alert("Please Select Employee Id.")
        return
    }
    else {
        // console.log(mob)
        $.ajax({
            url:"partials/_fetch_role_details.php",
            type: 'POST',
            data: {
                mobile: emp_mob
            },
            success: function(data) {
                console.log(data)
                const response =JSON.parse(data)
                console.log(response)
                if(response['error']===true){
                    alert(response['message'])
                    return
                }
                else {
                    $('#emp_id').val(response['emp_id'])
                    $('#orgnization').val(response['Orgnization_Id'])
                    $('#edit_user_role option:contains("'+ response['User_Role'] +'")').prop('selected',true);
                    console.log(response['note_approver_dbs'])
                    console.log(response['note_approver_mgs'])
                    if(response['note_approver_dbs'] !== 'NA' && response['note_approver_dbs'] !== 'default' && response['note_approver_dbs'] !== '') {
                        console.log('dbs')
                        
                        // $('#edit-dbsid').css('display', 'block')
                        // $('#edit-dbslabel').css("display", "block")

                        //enable DBS dropdown
                        $('#edit-dbsid').prop('disabled', false);

                        //disable MGS dropdown
                        $('#edit-mgsid').prop('disabled', 'disabled');
                        
                        // enable station type
                        $('#notif_approver_edit_station_type').prop('disabled', false);

                        //reset value of MGS
                        $('#edit-mgsid').prop('selectedIndex', 0)
                        
                        //selecting the previous value of DBS
                        $('#edit-dbsid option:contains("'+ response['note_approver_dbs'] +'")').prop('selected',true);

                        // selecting DBS option in Station type dropdown
                        $('#notif_approver_edit_station_type option:contains("DBS")').prop('selected',true);
                    } 
                    else if(response['note_approver_mgs'] !== 'NA' &&  response['note_approver_mgs'] !== 'default' && response['note_approver_mgs'] !== ''){
                        console.log('mgs')
                        
                        //enable MGS dropdown
                        $('#edit-mgsid').prop('disabled', false);

                        //disable DBS dropdown
                        $('#edit-dbsid').prop('disabled', 'disabled');

                        // enable station type
                        $('#notif_approver_edit_station_type').prop('disabled', false);

                        //reseting value of DBS
                        $('#edit-dbsid').prop('selectedIndex', 0)

                        //selecting previous value of MGS
                        $('#edit-mgsid option:contains("'+ response['note_approver_mgs'] +'")').prop('selected',true);

                        // selecting MGS option in Station type dropdown
                        $('#notif_approver_edit_station_type option:contains("MGS")').prop('selected',true);

                    }
                    else {
                        //disable MGS dropdown
                        $('#edit-mgsid').prop('disabled', 'disabled');

                        //disable DBS dropdown
                        $('#edit-dbsid').prop('disabled', 'disabled');

                        // disable station type
                        $('#notif_approver_edit_station_type').prop('disabled', 'disabled');
                        // console.log($('#notif_approver_edit_station_type'));

                        //reset value of MGS
                        $('#edit-mgsid').prop('selectedIndex', 0)

                        //reseting value of DBS
                        $('#edit-dbsid').prop('selectedIndex', 0)

                        //reseting value of station type
                        $('#notif_approver_edit_station_type').prop('selectedIndex', 0)
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest, textStatus, errorThrown);
            }
        })
    }
})
