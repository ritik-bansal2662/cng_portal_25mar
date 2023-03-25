function show_data(type, show, callback) {
    api_url='http://localhost/CNGPortal/CNGPortal/html/partials/notification_count.php'
    let result
    if(type=='') {
        $.ajax({
            url:api_url,
            type: "GET",
            success: function(data) {
                // console.log("count")
                // console.log(data['pending'])
                callback(data)
                // result = data
            },
            error : function(error){
                alert('Error while fetching data from table', error)
            }
        })
    }
    else {
        $.ajax({
            // async:false,
            url:api_url + '?type=' + type + '&show=' + show,
            type: "GET",
            success: function(data) {
                // console.log(type)
                // console.log(type + "   ---  ", data)
                callback(data)
                // result = data
            },
            error : function(error){
                alert('Error while fetching data', error)
            }
        })
    }
    // console.log('result', result)

    return result

}

function callback_data(result) {
    $('#tbody').html(result)
}

function callback_count(result) {

}

$(document).ready(function() {
    show_data('total', true, callback_data)
    show_data('', false, callback_count)
})



$('#approved').click(function() {
    // console.log('approved')
    show_data('approved', true, callback_data)
})

$('#pending').click(function() {
    // console.log('pending')
    show_data('pending', true, callback_data)
})

$('#total').click(function() {
    // console.log('total')
    show_data('total', true, callback_data)
})
