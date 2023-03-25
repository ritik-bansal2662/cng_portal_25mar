$(document).ready(function () {
    $.ajax({
        url:'partials/_fetch_mgs.php',
        type:'GET',
        success: function(response) {
            console.log(response);
            $('#mgs_id').html(response)
        }
    })

    $.ajax({
        url:'partials/get_all_dbs.php',
        type:'GET',
        success: function(response) {
            console.log(response);
            $('#dbs_id').html(response)
        }
    })
})


$('#dbs_id').change(function() {
    var dbs = $(this).val()
    console.log(dbs)

    if(dbs === 'NA') {
        $("input[name=end_coordinates]").val('')
    } else {
        var dbs_data = $(this).find(':selected').data();
        console.log(dbs_data)
        $("input[name=end_coordinates]").val(dbs_data['coordinates'])
    }
})

$('#mgs_id').change(function() {
    var mgs = $(this).val()
    console.log(mgs)

    if(mgs === 'NA') {
        $("input[name=start_coordinates]").val('')
    } else {    
        var mgs_data = $(this).find(':selected').data();
        console.log(mgs_data)
        $("input[name=start_coordinates]").val(mgs_data['coordinates'])
    }
})

function check_params(form_obj, mandatory_keys_arr) {
    let not_available = []
    for(let ele of mandatory_keys_arr) {
        console.log(ele, form_obj[ele]);
        if(form_obj[ele] === undefined) {
            not_available.push(ele)
        }
    }
    return not_available
}

function reset_form_data(form_id) {
    console.log($(`${form_id}`)[0]);
    $(`${form_id}`)[0].reset();
}

// calling api to store form data
function store_data(form_data_object, api_url, form_id) {
    console.log('func form data', form_data_object)
    $.ajax({
        url: api_url,
        type: 'POST',
        data: form_data_object,
        success: function(data) {
            console.log('response', data)
            // const data = JSON.parse(response)
            alert(data['message'])
            console.log(data['message'])
            if(data['error'] === false) {
                reset_form_data(form_id)
            }
        }
    })
  }

$('#route_details_submit').click(function(){
    // console.log('ins info sub clicked')
    let form_arr = $('#route_details_form').serializeArray()
    const mandatory_keys = ['mgs_id', 'dbs_id', 'route_id', 'time_slot', 'distance', 'duration']
    // console.log(form_arr)
    
    var form_obj={}
    for(let ele of form_arr) {
        let first=ele.name
        let second = ele.value
        form_obj[first] = second
    }
    console.log(form_obj)

    let via_coord = $("input[name=via_coordinates")
    let via_coordinates = ''

    // making string of via coordinates
    for(let field of via_coord) {
        console.log(field.value);
        if(field.value !== '') {
            via_coordinates += field.value + ';'
        }
    }
    console.log('via ',via_coordinates);
    
    form_obj['via_coordinates'] = via_coordinates
    console.log(form_obj)

    let unset_keys = check_params(form_obj, mandatory_keys)

    if(unset_keys.length !== 0) {
        alert('Enter all Mandatory Fields.\n All ( * ) marked fields are mandatroy.')
    } else {
        const api_url = "../CNG_API/insert_mgs_dbs_route.php"
        
        store_data(form_obj, api_url, '#route_details_form')
    }
  
  })

