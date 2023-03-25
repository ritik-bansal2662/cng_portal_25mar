// while calling api for data of LCV tracking, vehicle id and dates params are kept static for testing, will be changed later on
// change time to 5000 of setTimeInterval for calling mark_live_route() function after every 5 seconds
// change dates in button HTML in create_table_row() function
// change Live Tracking to '${transit_details_list[0][2]}' in last button of every table row



// api for vehicle tracking details
// https://gpsvts.vamosys.com/apiMobile/getVehicleHistory?userId=SURESHLUX&groupId=SURESHLUX&vehicleId=DL1LAA9187&fromDate=2022-12-09&fromTime=00:00:00&toDate=2022-12-09&toTime=23:59:00

// api for live tracking
// https://api.vamosys.com/mobile/getGrpDataForTrustedClients?providerName=SUMITGUPTA&fcode=wom


var today_date; // global variable to store todays date
let date; // global variable to store filter date
let lcv_number; // global variable to store filter LCV

let interval;


$(document).ready(function(){
    // date must be in YYYY-MM-DD format
    today_date = new Date().toISOString().slice(0, 10)
    // console.log(today_date);

    // getting data on web page starts or refreshes
    // data must be of tadays date
    // get_data_according_to_date('2022-12-29')
    get_data_according_to_date(today_date)
    // mark_live_route('DL1NOV2019', 'live')

    start_loading('#loading_table')

})

//loading
function start_loading(element_selector) {
    $(element_selector).addClass('active')
}

function stop_loading(element_selector) {
    $(element_selector).removeClass('active')
}

//error
function show_error(element_selector) {
    $(element_selector).addClass('active')
}

function hide_error(element_selector) {
    $(element_selector).removeClass('active')
}



// creating tabel row for a particular trip
function create_table_row(data, lcv_number, trip, trips_count, row_number) {
    table_row = ''
    table_row +=`<tr><td>${row_number}</td>`
    if(trip == 1 || trip === 'trip1') {
        table_row += `<td rowspan=${trips_count-1}>${lcv_number}</td>`
    }

    table_row += `<td>${convert_date(data[lcv_number][trip][0]['create_date'])}</td>
        <td>${data[lcv_number][trip][0]['Notification_MGS']}</td>`

    const transit_details_list = transit_details(data, lcv_number, trip)
    // console.log('trans', transit_details_list);

    // console.log('create_table_row data trip', trip);

    let dbs = ''
    let table_row_data = ''

    for(var stage = 0; stage<6; stage++) {
        // console.log('create_table_row data',data[lcv_number][trip][stage]);
        if(data[lcv_number][trip][stage] !== undefined) {
            // if(dbs === '') {
            //     dbs = data[lcv_number][trip][stage]['Notification_DBS']
            // }
            let status;
            if(data[lcv_number][trip][stage]['status'] == 'Pending') {
                status = "<span class='text-danger'>Approval Pending</span>"
            } else {
                status = "<span class='text-success'>Approved</span>"
            }

            dbs = data[lcv_number][trip][stage]['Notification_DBS']
            table_row_data += `<td>${data[lcv_number][trip][stage]['create_date']} <br> ${status}</td>`
        } else {
            table_row_data += '<td> - </td>'
        }

        if(stage===2){
            // console.log('mgs',transit_details_list[0]);
            if(transit_details_list[0] !== undefined){
                // table_row += `<td>
                //     <a href='#' onclick='window.open("lcv_live_positioning.php?lcv_num=${lcv_number}&fromDate=${transit_details_list[0][0]}&toDate=${transit_details_list[0][1]}");return false;'>${transit_details_list[0][2]}</a>
                // </td>`
                table_row_data += `<td>
                    <button 
                        class='btn btn-primary'
                        data-lcv_num='${lcv_number}' 
                        data-from_date='${transit_details_list[0][0]}' 
                        data-to_date='${transit_details_list[0][1]}' 
                        data-from_station ='${data[lcv_number][trip][0]['Notification_MGS']}'
                        data-to_station = '${data[lcv_number][trip][0]['Notification_DBS']}'
                        data-tracking_status = '${transit_details_list[0][2]}'
                        onClick="map_tracking(event)">
                            ${transit_details_list[0][2]}
                    </button>
                </td>`
            } else {
                // console.log(transit);
                table_row_data += `<td>
                    No Data
                </td>`
            }
        }
        
        if (stage===5){
            console.log('dbs',transit_details_list[1]);
            // console.log(transit_details_list[1] !== undefined);
            // console.log('dbs',(transit_details_list[1]).length);
            if(transit_details_list[1] !== undefined && (transit_details_list[1]).length !== 0) {
            // table_row += `<td>
            //     <a href='#' onclick='window.open("lcv_live_positioning.php?lcv_num=${lcv_number}&fromDate=${transit_details_list[1][0]}&toDate=${transit_details_list[1][1]}");return false;'>${transit_details_list[1][2]}</a>
            // </td>`
            // '${transit_details_list[0][2]}'
                table_row_data += `<td>
                    <button 
                        class='btn btn-primary'
                        data-lcv_num='${lcv_number}' 
                        data-from_date='${transit_details_list[1][0]}'
                        data-to_date='${transit_details_list[1][1]}' 
                        data-to_station ='${data[lcv_number][trip][0]["Notification_MGS"]}'
                        data-from_station = '${data[lcv_number][trip][0]["Notification_DBS"]}'
                        data-tracking_status = '${transit_details_list[1][2]}'
                        onClick="map_tracking(event)">
                        ${transit_details_list[1][2]}
                    </button>
                </td>`
            } else {
                table_row_data += `<td>
                    No Data
                </td>`
            }
        }
    }
    table_row += `<td>${dbs}</td>${table_row_data}</tr>`

    // console.log('create table row ROW', table_row);

    return table_row
}


// extra

function table_data() {
    $.ajax({
        url: '../CNG_API/get_lcv_stage_details.php?apicall=all',
        type: 'GET',
        success: function(response) {
            const data = JSON.parse(response)

            let table_data = ''
            var j=1;
            for(var lcv in data) {
                if(lcv === '0') {
                    continue;
                }
                row_object = {}
                row_object['lcv_num'] = lcv
                for(var i=1; i<=6; i++) {
                    if(data[lcv][i] !== undefined && data[lcv][i]['flag'] !== undefined && data[lcv][i]['flag'] == i) {
                        row_object['stage'+i] = data[lcv][i]['update_date']
                    } else {
                        row_object['stage'+i] = ' - '
                    }
                }
                row_object['Sno'] = j;
                j+=1;

                table_data += create_table_row(row_object)
            }

            $('#table_body').html(table_data)
        }
    })
}

//extra close


//for converting to standard date format
function convert_date(date) {
    if(date === undefined || date === ' - ') {
        return ' - '
    }
    return new Date(date).toDateString();
}


// get organized transit details list
function transit_details(data, lcv_number, trip) {
    var mgs_to_dbs_transit = []
    var dbs_to_mgs_transit = []

    let transit_details = []

    // console.log(data);
    // console.log(trip);
    // console.log(lcv_number);
    // console.log(data[lcv_number]);
    // console.log(data[lcv_number][trip]);
    // console.log(data[lcv_number][trip][2]);
    
    const time_now = new Date().toLocaleTimeString('en-US', { hour12: false, 
        hour: "numeric", 
        minute: "numeric"});
    
    //for mgs to dbs transit details
    if(data[lcv_number][trip] !== undefined && data[lcv_number][trip][2] !== undefined) { // check if vehicle is done with stage 3
        mgs_to_dbs_transit.push(data[lcv_number][trip][2]['create_date'])

        if(data[lcv_number][trip][3] !== undefined) { // if stage 3 is done then it will check for stage 4

            mgs_to_dbs_transit.push(data[lcv_number][trip][3]['create_date'])
            mgs_to_dbs_transit.push('Tracking History')

            if(data[lcv_number][trip][5] !== undefined) { //  check if vehicle is done with stage 6
                dbs_to_mgs_transit.push(data[lcv_number][trip][2]['create_date'])
                
                if(data[lcv_number][trip+1] !== undefined && data[lcv_number][trip+1][0] !== undefined) { // if stage 6 is done then check if the vehicle reached at mgs
                    console.log('checking if another trip exists or not');
                    console.log(lcv_number, trip+1 ,data[lcv_number][trip+1]);
                    dbs_to_mgs_transit.push(data[lcv_number][trip+1][0]['create_date'])
                    dbs_to_mgs_transit.push('Tracking History')
                } else { // if vehicle not reached at mgs again then it will be tacked live
                    dbs_to_mgs_transit.push(`${today_date} ${time_now}`)
                    dbs_to_mgs_transit.push('Live Tracking')
                }
            } else {
                dbs_to_mgs_transit = []
                // transit_details.push([])
            }

        } else {
            mgs_to_dbs_transit.push(`${today_date} ${time_now}`)
            mgs_to_dbs_transit.push('Live Tracking')
        }
    } else {
        return []
    }

    transit_details.push(mgs_to_dbs_transit)
    transit_details.push(dbs_to_mgs_transit)

    console.log('function transit details', transit_details);

    return transit_details

}

// filling data according to selected date
function data_according_to_date(data, date) {
    // console.log(data);
    let row_number=1;
    let table_rows = '';
    for(let lcv in data) {
        // console.log(lcv);
        const trips_count = Object.keys(data[lcv]).length
        if(lcv === '0') {
            continue;
        } else {
            for(let trip in data[lcv]) {
                if(trip === '0') {
                    continue;
                }
                if((data[lcv][trip]['0']['create_date']).includes(date)){
                    table_rows += create_table_row(data, lcv, trip, trips_count, row_number)
                    row_number++;
                    // table_row += `<tr><td>${sno++}</td>`
                    // if(trip === 'trip1' || trip == 1) {
                    //     table_row += `<td rowspan=${trips_count - 1}>${lcv}</td>`
                    // }

                    // table_row += `<td>${convert_date(data[lcv][trip][0]['create_date'])}</td>
                    //     <td>${data[lcv][trip][0]['Notification_MGS']}</td>
                    //     <td>${data[lcv][trip][0]['Notification_DBS']}</td>`
                    
                    // const transit_details_list = transit_details(data, lcv, trip)
                    // console.log('trans', transit_details_list);
                    
                    // for(var i =0; i<6; i++) {
                    //     if(data[lcv][trip][i]) {
                    //         table_row += `<td>${data[lcv][trip][0]['create_date']}</td>`
                    //     } else {
                    //         table_row += '<td> - </td>'
                    //     }

                    //     if(i===2){
                    //         // console.log('mgs',transit_details_list[0]);
                    //         if(transit_details_list[0] !== undefined){
                    //             table_row += `<td>
                    //                 <a href='#' onclick='window.open("lcv_live_positioning.php?lcv_num=${lcv}&fromDate=${transit_details_list[0][0]}&toDate=${transit_details_list[0][1]}");return false;'>${transit_details_list[0][2]}</a>
                    //             </td>`
                    //         } else {
                    //             // console.log(transit);
                    //             table_row += `<td>
                    //                 No Data
                    //             </td>`
                    //         }
                    //     }
                        
                    //     if (i===5){
                    //         console.log('dbs',transit_details_list[1]);
                    //         // console.log(transit_details_list[1] !== undefined);
                    //         // console.log('dbs',(transit_details_list[1]).length);
                    //         if(transit_details_list[1] !== undefined && (transit_details_list[1]).length !== 0) {
                    //         table_row += `<td>
                    //             <a href='#' onclick='window.open("lcv_live_positioning.php?lcv_num=${lcv}&fromDate=${transit_details_list[1][0]}&toDate=${transit_details_list[1][1]}");return false;'>${transit_details_list[1][2]}</a>
                    //         </td>`
                    //         // table_row += `<td>
                    //         //     <button onClick="map_position(event)">Track</button>
                    //         // </td>`
                    //         } else {
                    //             table_row += `<td>
                    //                 No Data
                    //             </td>`
                    //         }
                    //     }
                    // }
                    // table_row += `</tr>`

                    // table_row += `
                    //     <td>${data[lcv][trip][0]['create_date']}</td>
                    //     <td>${data[lcv][trip][1]['create_date']}</td>
                    //     <td>${data[lcv][trip][2]['create_date']}</td>
                    //     <td>Live Tracking</td>
                    //     <td>${data[lcv][trip][3]['create_date']}</td>
                    //     <td>${data[lcv][trip][4]['create_date']}</td>
                    //     <td>${data[lcv][trip][5]['create_date']}</td>
                    //     <td>Live Tracking</td>
                    // </tr>`
                }
            }
        }
    }

    // console.log('data acc to date table rows', table_rows);

    return table_rows;
}

function get_data_according_to_date(date) {
    $.ajax({
        url:'../CNG_API/get_lcv_stage_details.php',
        type: 'GET',
        data: {
            date: date,
            apicall : 'date'
        },
        success: function(data) {
            // const data = JSON.parse(response)
            let table_data = ''
            if(data['data_available'] === false) {
                show_error('#error_table')
                alert(data['message'])
                table_data = `<tr>
                <td>1</td>
                <td> - </td>
                <td>${date}</td>
                <td colspan=10>${data['message']}</td></td></tr>`
            }else {
                hide_error('#error_table')
                table_data = data_according_to_date(data, date)
            }
            // console.log('get_data_acc_to_date Table Data', table_data);
            $('#table_body').html(table_data)
        }
    }).then(function() {
        stop_loading('#loading_table')
    })
}


function data_according_to_lcv(data, lcv_number) {

    const trips_count = Object.keys(data[lcv_number]).length

    let table_rows = ''
    let row_number = 1
    for(let trip in data[lcv_number]) {

        if(trip == 0) {
            continue
        }

        table_rows += create_table_row(data, lcv_number, trip, trips_count, row_number)
        row_number++

        // table_rows +=`<tr><td>${trip}</td>`
        // if(trip == 1) {
        //     table_rows += `<td rowspan=${trips_count-1}>${lcv_number}</td>`
        // }

        // table_rows += `<td>${convert_date(data[lcv_number][trip][0]['create_date'])}</td>
        //     <td>${data[lcv_number][trip][0]['Notification_MGS']}</td>
        //     <td>${data[lcv_number][trip][0]['Notification_DBS']}</td>`

        // const transit_details_list = transit_details(data, lcv_number, trip)
        // console.log('trans', transit_details_list);

        // for(var i = 0; i<6; i++) {

        //     if(data[lcv_number][trip][i]) {
        //         table_rows += `<td>${data[lcv_number][trip][i]['create_date']}</td>`
        //     } else {
        //         table_rows += '<td> - </td>'
        //     }

        //     // if(i===2 || i===5){
        //     //     table_rows += `<td>Live Tracking</td>`
        //     // }
        //     if(i===2){
        //         // console.log('mgs',transit_details_list[0]);
        //         if(transit_details_list[0] !== undefined){
        //             table_rows += `<td>
        //                 <a href='#' onclick='window.open("lcv_live_positioning.php?lcv_num=${lcv_number}&fromDate=${transit_details_list[0][0]}&toDate=${transit_details_list[0][1]}");return false;'>${transit_details_list[0][2]}</a>
        //             </td>`
        //         } else {
        //             // console.log(transit);
        //             table_rows += `<td>
        //                 No Data
        //             </td>`
        //         }
        //     }
            
        //     if (i===5){
        //         console.log('dbs',transit_details_list[1]);
        //         // console.log(transit_details_list[1] !== undefined);
        //         // console.log('dbs',(transit_details_list[1]).length);
        //         if(transit_details_list[1] !== undefined && (transit_details_list[1]).length !== 0) {
        //         table_rows += `<td>
        //             <a href='#' onclick='window.open("lcv_live_positioning.php?lcv_num=${lcv_number}&fromDate=${transit_details_list[1][0]}&toDate=${transit_details_list[1][1]}");return false;'>${transit_details_list[1][2]}</a>
        //         </td>`
        //         // table_rows += `<td>
        //         //     <button onClick="map_position(event)">Track</button>
        //         // </td>`
        //         } else {
        //             table_rows += `<td>
        //                 No Data
        //             </td>`
        //         }
        //     }
        // }
        // table_rows += `</tr>`

    }

    return table_rows;
}

function get_data_according_to_lcv(lcv_number) {
    $.ajax({
        url:'../CNG_API/get_lcv_stage_details.php',
        type: 'GET',
        data: {
            lcv_num: lcv_number,
            apicall : 'lcv'
        },
        success: function(response) {

            if(response['data_available'] === false) {
                alert(response['message'])
                show_error('#error_table')
            }else {
                hide_error('#error_table')
                const table_row = data_according_to_lcv(response, lcv_number)

                $('#table_body').html(table_row)
            }

        }
    }).then(function() {
        stop_loading('#loading_table')
    })
}

function data_according_to_lcv_and_date(lcv_number, date, data) {

    const trips_count = Object.keys(data[lcv_number]).length

    let table_rows='';
    let row_number = 1
    for(var trip in data[lcv_number]) {
        if(trip == 0){
            continue;
        }

        table_rows += create_table_row(data, lcv_number, trip, trips_count, row_number)
        row_number++

        // table_rows +=`<tr><td>${trip}</td>`
        // if(trip == 1) {
        //     table_rows += `<td rowspan=${trips_count-1}>${lcv_number}</td>`
        // }

        // table_rows += `<td>${convert_date(data[lcv_number][trip][0]['create_date'])}</td>
        //     <td>${data[lcv_number][trip][0]['Notification_MGS']}</td>
        //     <td>${data[lcv_number][trip][0]['Notification_DBS']}</td>`

        // const transit_details_list = transit_details(data, lcv_number, trip)
        // console.log('trans', transit_details_list);

        // for(var i = 0; i<6; i++) {

        //     if(data[lcv_number][trip][i]) {
        //         table_rows += `<td>${data[lcv_number][trip][i]['create_date']}</td>`
        //     } else {
        //         table_rows += '<td> - </td>'
        //     }

        //     // if(i===2 || i===5){
        //     //     table_rows += `<td>Live Tracking</td>`
        //     // }

        //     if(i===2){
        //         // console.log('mgs',transit_details_list[0]);
        //         if(transit_details_list[0] !== undefined){
        //             // table_rows += `<td>
        //             //     <a href='#' onclick='window.open("lcv_live_positioning.php?lcv_num=${lcv_number}&fromDate=${transit_details_list[0][0]}&toDate=${transit_details_list[0][1]}");return false;'>${transit_details_list[0][2]}</a>
        //             // </td>`
        //             table_rows += `<td>
        //                 <button data-lcv_num='${lcv_number} data-fromDate=${transit_details_list[0][0]} data-toDate=${transit_details_list[0][1]} onClick="map_position(event)">${transit_details_list[0][2]}</button>
        //             </td>`
        //         } else {
        //             // console.log(transit);
        //             table_rows += `<td>
        //                 No Data
        //             </td>`
        //         }
        //     }
            
        //     if (i===5){
        //         console.log('dbs',transit_details_list[1]);
        //         // console.log(transit_details_list[1] !== undefined);
        //         // console.log('dbs',(transit_details_list[1]).length);
        //         if(transit_details_list[1] !== undefined && (transit_details_list[1]).length !== 0) {
        //         // table_rows += `<td>
        //         //     <a href='#' onclick='window.open("lcv_live_positioning.php?lcv_num=${lcv_number}&fromDate=${transit_details_list[1][0]}&toDate=${transit_details_list[1][1]}");return false;'>${transit_details_list[1][2]}</a>
        //         // </td>`
        //             table_rows += `<td>
        //                 <button data-lcv_num='${lcv_number} data-fromDate=${transit_details_list[0][0]} data-toDate=${transit_details_list[0][1]} onClick="map_position(event)">${transit_details_list[0][2]}</button>
        //             </td>`
        //         } else {
        //             table_rows += `<td>
        //                 No Data
        //             </td>`
        //         }
        //     }
        // }
        // table_rows += `</tr>`
    }
    return table_rows

}

function get_data_according_to_lcv_and_date(lcv_number, date) {
    $.ajax({
        url:'../CNG_API/get_lcv_stage_details.php',
        type: 'GET',
        data: {
            lcv_num: lcv_number,
            apicall : 'all',
            date: date
        },
        success: function(response) {

            if(response['data_available'] === false) {
                alert(response['message'])
                show_error('#error_table')
            }else {
                hide_error('#error_table')
                const table_row = data_according_to_lcv_and_date(lcv_number, date, response)
                //  data_according_to_lcv(response, lcv_number)
                $('#table_body').html(table_row)

            }

        }
    }).then(function() {
        stop_loading('#loading_table')
    })
}


function get_data_using_filter(date, lcv_number){
    
    start_loading('#loading_table')
    hide_error('#error_table')

    if((date === ''  || date === undefined) && (lcv_number === 'NA'  || lcv_number === undefined) ){ // no filter selected
        // alert('Select atleast one filter!')
        // console.log('2');
        // if no filter selected then show todays data
        get_data_according_to_date(today_date)
    } else if((date !== '' && date !== undefined) && (lcv_number !== 'NA' && lcv_number !== undefined)){ // both filter selected
        console.log('1');
        get_data_according_to_lcv_and_date(lcv_number, date)
    } else if(date === ''  || date === undefined){ // lcv is selected and date is not selected
        console.log('3');
        get_data_according_to_lcv(lcv_number)
    } else if(lcv_number === 'NA'  || lcv_number === undefined){ // only date is selected
        console.log('4');
        get_data_according_to_date(date)
    } else {
        alert('Invalid filter')
    }
}



$('#lcv_status_filter').click(function() {
    date = $('#lcv_status_date').val()
    lcv_number = $('#lcv_number').val()

    get_data_using_filter(date, lcv_number);
})


// this will show today's data
$('#today-btn').click(function() {
    get_data_according_to_date(today_date)
})


// when refresh button is clicked, 
// the table shows the refreshed data of table and shows today's date data
$('#refresh-btn').click(function() {
    get_data_according_to_date(today_date)
    // get_data_using_filter(date, lcv_number);
})


// download table

// $('#download-table').click(function() {
//     var table2excel = new Table2Excel();
//     table2excel.export($("#lcv-table-data"));
// })

// $("#download-table").ddTableFilter();

function html_table_to_excel(type) {
    var data = $('#lcv-table-data')
    var file = XLSX.utils.table_to_book(data, {sheet : 'sheet1'})
    XLSX.write(file, { bookType: type, bookSST: true, type: 'base64'})
    XLSX.writeFile(file, 'file.' + type)
}

// $('#download-table').click(function() {
//     // ExportToExcel('xlsx')
//     html_table_to_excel('xlsx')
// })

function ExportToExcel(type, fn, dl) {
    var elt = document.getElementById('lcv-table-data');
    var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
    return dl ?
      XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
      XLSX.writeFile(wb, fn || ('LCV_staging_data.' + (type || 'xlsx')));
 }


// map popup on button click
function map_tracking(event) {
    console.log(event);
    console.log(event.target)
    var data = $(event.target).data();
    console.log(data);

    for(let item in data) {
        // console.log(item);
        // console.log(data[item]);
        var element_selector = '#' + item
        $(element_selector).val(data[item])
        $(element_selector).html(data[item])
        // console.log(element_selector);
        // console.log($(element_selector));
    }

    get_lcv_vendor(data['lcv_num'], '#lcv-vendor')
    get_station_address(data['from_station'], '#from-station-address')
    get_station_address(data['to_station'], '#to-station-address')

    start_loading('#loading-map')
    hide_error('#error_table')

    mark_lcv_route(data)

    tooglePopup();
    
}


// toggle popup
function tooglePopup() {
    console.log('includes class active', $('#map-popup').attr("class").split(/\s+/).includes('active'));
    console.log(interval);
    if($('#map-popup').attr("class").split(/\s+/).includes('active') && interval !== undefined) {
        clearInterval(interval)
        remove_Marker([end_marker, start_marker])

        // console.log('polylines list before',polylines_list);
        remove_ploylines()
        // console.log('poly lines list after',polylines_list);
    }
    $('#map-popup').toggleClass('active')
    $('#vehicle-moving-status').css('display', 'none')
}


// call api to get lcv vendor
function get_lcv_vendor(lcv_num, element_selector) {
    $.ajax({
        url: 'partials/get_lcv_vendor.php',
        type: "GET",
        data: {
            lcv_num:lcv_num
        },
        success: function(response) {
            console.log(response);
            let vendor = ''
            if(response['error']) {
                vendor = response['message']
            } else {
                vendor = response['vendor_name']
            }
            $(element_selector).val(vendor)
            $(element_selector).html(vendor)
        }
    })
}

// call api to get station address
function get_station_address(station_id, element_selector) {
    $.ajax({
        url: 'partials/get_station_address.php',
        type: "GET",
        data: {
            station_id: station_id
        },
        success: function(response) {
            console.log(response);
            let station_address=''
            if(response['error'] === true) {
                station_address = response['message'];
            } else {
                station_address = response['station_address'];
            }
            $(element_selector).val(station_address)
            $(element_selector).html(station_address)
        }
    })
}


//create map using MapMyIndia API
var lcv_marker = [];
var visbility = false;
var p1 = null;
var poly;
var poly1;
var polylines_list = new Array();
var pts= [];

// stores the marker of start point of trip
var start_marker
// stores the coordinates of start point of trip
var start_marker_coordinates

// stores the marker of end point of trip
var end_marker
// stores the coordinates of end point of trip
var end_marker_coordinates

let location_status


var map = new MapmyIndia.Map("map", {
    center: [28.61, 77.23],
    zoomControl: true,
    hybrid: true,
    search: true,
    location: true
});


// while calling api for data of LCV tracking, vehicle id and dates params are kept static for testing, will be changed later on

// api for vehicle tracking details
// https://gpsvts.vamosys.com/apiMobile/getVehicleHistory?userId=SURESHLUX&groupId=SURESHLUX&vehicleId=DL1LAA9187&fromDate=2022-12-09&fromTime=00:00:00&toDate=2022-12-09&toTime=23:59:00


// dummy data for pts
// new L.LatLng(28.69188778, 74.58885889),
// new L.LatLng(29.69186222, 75.58896889),
// new L.LatLng(30.69188778, 50.58885889),
// new L.LatLng(31.69186222, 77.58896889),
// new L.LatLng(32.69188778, 60.58885889),
// new L.LatLng(33.69186222, 65.58896889),

var polylineParam = 
{ 
    // color: 'black',
    weight: 4, // The thickness of the polyline 
    opacity: 0.8 //The opacity of the polyline colour 
};



function mark_lcv_route(lcv_data) {

    console.log(lcv_data);
    const fromDate = (lcv_data['from_date']).split(' ')
    const toDate = (lcv_data['to_date']).split(' ')
    // console.log(fromDate);
    // console.log(toDate);

    
    // const LCV_API = `https://gpsvts.vamosys.com/apiMobile/getVehicleHistory?userId=SURESHLUX&groupId=SURESHLUX&vehicleId=${url_parameters['lcv_num']}&fromDate=${fromDate[0]}&fromTime=${fromDate[1]}&toDate=${toDate[0]}&toTime=${toDate[0]}`
    const LCV_HISTORY_API = `https://gpsvts.vamosys.com/apiMobile/getVehicleHistory?userId=SURESHLUX&groupId=SURESHLUX&vehicleId=${lcv_data['lcv_num']}&fromDate=2022-12-09&fromTime=00:00:00&toDate=2022-12-09&toTime=23:59:00`
    const locations = fetch(LCV_HISTORY_API)

    // console.log('fetch',locations);

    locations.then(function(data) {
        console.log('data', data);
        return data.json()
    }).then(function(data) {
        console.log(data);
        console.log('vehicle locations',data['vehicleLocations']);
        if(data['vehicleLocations'] !== null) {
            let lat = data['vehicleLocations'][0]['lat']
            let lng = data['vehicleLocations'][0]['lng'];

            //custom title for marker when trip starts
            location_status = 'Trip Start'
            let title = marker_title_window(lcv_data['lcv_num'], lcv_data['from_station'], lcv_data['to_station'], lcv_data['from_station'], fromDate[0], location_status)

            // add marker of trip start place on the map
            start_marker_coordinates = [lat, lng]
            start_marker = addMarker(new L.LatLng(lat, lng), title, false);
            // console.log(lat, lng);

            for(let item of data['vehicleLocations']) {
                // console.log(item['lat'], item['lng']);
                lat = item['lat']
                lng = item['lng']
                pts.push(new L.LatLng(lat, lng))
            }
            
            // adding polyline on the map
            poly = new L.Polyline(pts, polylineParam);
            polylines_list.push(poly)
            map.addLayer(poly);


            // move the centre of map to the trip end place
            map.panTo([lat, lng])

            location_status = 'Trip End'

            //custom title for marker when trip ends
            location_status = 'Trip End'
            title = marker_title_window(lcv_data['lcv_num'], lcv_data['from_station'], lcv_data['to_station'], lcv_data['from_station'], toDate[0], location_status)

            // add marker of trip end place on the map
            end_marker_coordinates = [lat, lng]
            end_marker = addMarker(new L.LatLng(lat, lng), title, false);
            // console.log('start', start_marker, 'end', end_marker);

            if(lcv_data['tracking_status'] === 'Live Tracking') {

                //get live coordinates after every 5 seconds and mark the path if the vehicle has moved
                interval = setInterval(function() {

                    // custom title window for marker of live location
                    location_status = 'Live Location'
                    title = marker_title_window(lcv_data['lcv_num'], lcv_data['from_station'], lcv_data['to_station'], lcv_data['from_station'], toDate[0], location_status)

                    mark_live_route(lcv_data['lcv_num'], title)
                }, 3000);

                $('#vehicle-moving-status').css('display', 'flex')
            }


        } else {
            alert('No Tracking Data Available')
            // $('#error').css('display', 'flex')
            show_error('#error-map')
        }
    }).then(function() {
        // $('#loading-map').css('display', 'none')
        stop_loading('#loading-map')
    })
}

function mark_live_route(lcv_num, marker_title) {

    const LCV_LIVE_API = `https://api.vamosys.com/mobile/getGrpDataForTrustedClients?providerName=SURESHLUX&fcode=wom`
    const locations = fetch(LCV_LIVE_API)

    // console.log('fetch',locations);

    locations.then(function(data) {
        // console.log('data', data);
        return data.json()
    }).then(function(data) {
        // console.log(data);
        for(var row of data) {
            // console.log(row);
            if(row['vehicleId'] === lcv_num) {
                console.log(' - - found - - ');
                lat = row['latitude']
                lng = row['longitude']
                console.log('last coord', end_marker_coordinates, 'current coord', [lat, lng]);
                if(end_marker_coordinates[0] === lat && end_marker_coordinates[1] === lng) {
                    console.log('coordinates same');

                    $('#vehicle-moving-status').val('Vehicle At Halt')
                    $('#vehicle-moving-status').html('Vehicle At Halt')

                    return;
                } else {

                    $('#vehicle-moving-status').val('Vehicle Moving')
                    $('#vehicle-moving-status').html('Vehicle Moving')

                    console.log('coordinates not same');
                    pts.push(new L.LatLng(lat, lng))

                    // adding polyline on the map from last point to the current point
                    // last point coordinates are stored in end_marker_coordinates list
                    poly1 = new L.Polyline([new L.LatLng(end_marker_coordinates[0], end_marker_coordinates[1]), new L.LatLng(lat, lng)], polylineParam);
                    polylines_list.push(poly1)
                    map.addLayer(poly1);

                    // move the centre of map to the trip end place
                    map.panTo([lat, lng])

                    //remove previous end marker
                    remove_Marker([end_marker])

                    // add marker of trip end place on the map
                    end_marker_coordinates = [lat, lng]
                    end_marker = addMarker(new L.LatLng(lat, lng), marker_title, false);
                    
                }
            }

        }
    })
}



// creating a custom marker title window for specific and more details
function marker_title_window(lcv_number, from_station, to_station, current_position, date, trip_status) {
    let title_window_html =`<div>
        <h2>${trip_status}</h2>
        <h3 class='marker-details'>This Place :- ${current_position}</h3>
        <h3 class='marker-details'>Date :- ${date}</h3>
        <h3 class='marker-details'>LCV :- ${lcv_number}</h3>
        <h3 class='marker-details'>From Station :- ${from_station}</h3>
        <h3 class='marker-details'>To Station :- ${to_station}</h3>
    </div>`

    return title_window_html
}


// to add marker on the map
function addMarker(position, title, draggable) {
    // position must be instance of L.LatLng that replaces current WGS position of this object. 
    // Will always return current WGS position. 
    // define a marker with a default icon and optional parameters draggable and title 
    var mk = new L.Marker(position, {draggable: draggable, title: title}); 

    mk.bindPopup(title); 

    //Now lets add the marker to the Map 

    map.addLayer(mk); 

    //Although we.ll talk about a few things in the code segment in a moment 
    //but lets put it in here so that you have the full picture. 
    //marker events:
    mk.on("click", function (e) { 
        //your code about what you want to do on a marker click 
        console.log(e);
    }); 
    return mk; 
}


function remove_Marker(marker_list) {
    console.log(marker_list);
    for(var item in marker_list) {
        console.log(marker_list[item]);
        map.removeLayer(marker_list[item])
    }
    // delete marker_list;
    marker_list = []
    return marker_list;
}

function remove_ploylines() {
    // console.log(polylines_list);
    var polylength = polylines_list.length;
    if (polylength > 0) {
        for(var item in polylines_list) {
            // console.log(polylines_list[item]);
            map.removeLayer(polylines_list[item])
        }
        pts = new Array()
        polylines_list = new Array();
    }
}

