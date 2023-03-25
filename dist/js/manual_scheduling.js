

$(document).ready(function() {
    console.log('start');
})


function approve(event) {
    // console.log(event);
    console.log('target: ', event.target);
    const data = $(event.target).data()
    console.log('data: ', data);

    const parent_siblings = $(event.target).parent().siblings()

    for(sibling of parent_siblings) {

        if(sibling.classList.contains('all-dbs')){
            console.log('all dbs ele found');
            // console.log($(sibling> '.select-dbs')[0])
            const children = $(sibling).children()[0]
            console.log(children);
            const updated_dbs = $(children).val();
            data.updated_dbs = updated_dbs
            console.log(data);
            
            if(data.updated_dbs === 'NA' || data.updated_dbs === '' || data.updated_dbs === undefined || data.updated_dbs === null) {
                
                alert('Please Select a DBS first.')
                return;

            } else if(data.allocated_dbs == data.updated_dbs) {

                alert(`Selected DBS is same as allocated DBS.`)
                return;

            } else { // update data
                // call api to update the data

                console.log('calling API \n');

                $.ajax({
                    url: '../CNG_API/rescheduling.php',
                    type: 'POST',
                    data: data,
                    success: function(response, textStatus, jqXHR) {
                        console.log('response: ', response)
                        if(response['error'] == true){
                            alert(`Error!! ${response['message']}`)
                        } else {
                            alert(`Success!! ${response['message']}`)
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log('Error ');
                        alert('Error!')
                        console.log(jqXHR);
                        console.log(textStatus, errorThrown);
                    }
                })

            }


            break;
        }
    }

}





function delay(time) {
    return new Promise(resolve => setTimeout(resolve, time));
}



function get_all_dbs() {
    console.log('get all dbs func start');
    $.ajax({
        url:'partials/get_all_dbs.php',
        type:'GET',
        success: function(response) {
            console.log('dcnlds');
            console.log(response);
            console.log($('.all-dbs'))
            $(".all-dbs").each(function(){
                $(this).html(response)
            });
        }
    })

    console.log('get all dbs func end');
}


function get_all_available_lcv(mgs) {

}
