<?php

include '../conn.php';

// $check_res['error'] = true;
// echo $check_res['error'], "- - -\n";

echo microtime(), " - micro time -\n";
// $now = Now();
// $date_p = date();
$date = date('mdYHis');
echo date("Y-m-d H:i:s"), " - - - \n";
echo $date, " - date -\n";
// echo date('mdYHis'), " - now -\n";


$array_one = array (
    'count' => 1
);

$arr = array(
    'a' => array(
        'b'=>1,
        'c'=>2
    ),
    'd' => array(),
    'f'
);

$array_two = array (
    'a' => 1987,
    'd' => 12,
    'e' => 22,
    'f' => array(
        'g' => 5
    )
);

$res = array_merge($array_two, $arr);

// echo "arr: ", json_encode($arr), "\n";
// echo "arr2: ", json_encode($array_two), "\n";
// echo "res: ", json_encode($res), "\n";

$a = 13;
$b = 2;
$c = $a/$b;
$d = intdiv($a,$b);

// echo $c;

// $request_insert_query = "INSERT INTO luag_dbs_request(Request_id, Reading_id, DBS, STATUS, Operator_id)
//     values ('test_4', 'rds4', 'DBStest', 'test request', 'test')";
// $request_result = mysqli_query($conn, $request_insert_query);

// var_dump($request_result);
// $response = array();

// if($request_result) {
//     $response['req_error'] = false;
//     $response['req_message'] = 'Request generated successfully.';
//     $last_id = $conn->insert_id;
//     $response['req_insert_id'] = $last_id;

// } else {
//     $response['req_error'] = false;
//     $response['req_message'] = 'Reorder point hit but unable to generate request.';
// }

// echo "\n - - response - - \n";
// echo json_encode($response), "\n";



// asort($array_one);

// echo "\n - - sorted arr 1 - - \n";
// echo json_encode($array_one), "\n";
// foreach($array_one as $arr => $val)  {
//     echo json_encode($arr), "- > ", json_encode($val), "\n";
// }

// f1();

// while($array_one['count'] < 10) {
    // echo "\n - - while loop - - \n";
    // echo "\n - - count : ", $array_one['count'],  " - -  \n";
    // $array_one = func();
    // echo "\n - - 1 count : ", $array_one['count'],  " - -  \n";
// }

// for($i=0; $i<10; $i += 1) {
//     echo $i, "\n";
//     if($i>5) {
//         echo "greater than 5 \n";
//         if($i>6) break;
//     }
// }


// echo "\n - - arr_one - - \n", json_encode($array_one), "\n";

function func() {
    echo "\n - - func start - - \n";
    global $array_one;
    if($array_one['count'] > 5) {
        return;
    }
    $arr = $array_one;
    $arr['count'] += 1;
    echo "\n - - arr - - \n", json_encode($arr), "\n";
    echo "\n - - arr_one - - \n", json_encode($array_one), "\n";
    $array_one = $arr;
    echo "\n - - arr_one updateds - - \n", json_encode($array_one), "\n";
    echo "\n -  func end - -\n";
    // func();
    return $array_one;
}

function f1() {
    echo "\n  - - func f1 start - - \n";

    global $array_one;

    while($array_one['count'] < 10) {
        echo "\n - - while loop - - \n";
        echo "\n - - count : ", $array_one['count'],  " - -  \n";
        $array_one = func();
        echo "\n - - 1 count : ", $array_one['count'],  " - -  \n";
    }
    echo "\n  - - func f1 end - - \n";
}



// if(array_key_exists('d', $array_one) && $array_one['d']['count'] == 1) {
//     echo "\n - -exists - -\n";
// } else {
//     echo "\n - - does not exist - - \n";
// }

// $iterator = new MultipleIterator ();
// $iterator->attachIterator (new ArrayIterator ($array_one));
// $iterator->attachIterator (new ArrayIterator ($array_two));

// $it1 = new ArrayIterator ($array_one);
// $it2 = new ArrayIterator ($array_two);

// while($it1->valid() && $it2->valid()) {
//     echo "\n - - \n";
//     // echo json_encode($it1), json_encode($it2);
//     $c1 = $it1->current();
//     $c2 = $it2->current();
//     echo json_encode($c1), "- - ", json_encode($c2);
//     $it1->next();
//     $it2->next();
// }

// while($it1->valid()) {
//     echo "\n- - \n";
//     echo $it1->key(), "- - ";
//     $it1->next();
// }

// echo "\n - - iterator - - \n";
// echo json_encode($iterator);

// while ($iterator->valid())
// {
//     echo "\n - - item - - \n";
//     echo json_encode($iterator->current());
//     $iterator->next();
// }   
echo "\n - - -\n";
// $arr = array(array(1,2,3),array(1,2,3),array(1,2,3));



// print_r($arr);



// $mgs = 'MGS123';

// $allocated_lcv_query = "SELECT * from luag_lcv_allocation_to_dbs_request where MGS = '$mgs' and Status = 'Scheduled'";
// $allocated_lcv_result = mysqli_query($conn, $allocated_lcv_query);

// $data = array();

// echo "\n - - allocated lcv - - \n";

// $temp_alloc = array();

// while($allocated_lcv_row = $allocated_lcv_result->fetch_assoc()){
//     echo "\n - - ",json_encode($allocated_lcv_row), " - - \n";
//     $data[$allocated_lcv_row['LCV_Num']] = $allocated_lcv_row;
//     $temp_alloc[$allocated_lcv_row['LCV_Num']] = 1;
// }



// $all_lcv_query = "WITH ranked_lcv AS (
//     SELECT m.*, ROW_NUMBER() OVER (PARTITION BY Notification_LCV ORDER BY Notification_Id DESC) AS rn
//     FROM notification AS m
//   ) SELECT * FROM ranked_lcv WHERE rn = 1 and Notification_MGS = '$mgs' and flag in (1,2)";
// $all_lcv_result = mysqli_query($conn, $all_lcv_query);

// echo "\n - - all lcv - - \n";
// $temp_all = array();

// while($all_lcv_row = $all_lcv_result->fetch_assoc()){
//     echo "\n - - ",json_encode($all_lcv_row), " - - \n";
//     $temp = array(
//         'Notification_Id' => $all_lcv_row['Notification_Id'],
//         'Request_id' => 'NA',
//         'MGS' => $all_lcv_row['Notification_MGS'],
//         'DBS' => $all_lcv_row['Notification_DBS'],
//         'Stage' => $all_lcv_row['flag'],
//         'Date' => $all_lcv_row['create_date'],
//         'Status' => 'Not allocated'
//     );
    
//     $temp_all[$all_lcv_row['Notification_LCV']] = 1;

//     if(array_key_exists($all_lcv_row['Notification_LCV'], $data)) {
//         echo "\n - - exists in data - - \n";
//         $data[$all_lcv_row['Notification_LCV']]['Stage'] = $all_lcv_row['flag'];
//         $data[$all_lcv_row['Notification_LCV']]['Date'] = $all_lcv_row['create_date'];

//         echo " - - ",json_encode($data[$all_lcv_row['Notification_LCV']]), " - - \n";
//     } else {
//         $data[$all_lcv_row['Notification_LCV']] = $temp;
//     }
// }

// echo "\n temp allocated: ", json_encode($temp_alloc), "\n";
// echo "\n temp all lcv: ", json_encode($temp_all), "\n";
// echo "\n data: ", json_encode($data), "\n";
// $i=1;
// foreach($data as $lcv => $lcv_details){
//     $dt = new DateTime($lcv_details['Date']);
//     $date = $dt->format('d M Y');
//     $time = $dt->format('H:i:s');
//     $dbs = $lcv_details["DBS"];
//     $stage = $lcv_details['Stage'];
//     $status = $lcv_details['Status'];
//     echo "<tr class='alert alert-success alert-dismissible fade show'>
//         <td> $i </td>
//         <td> $lcv </td>
//         <td> $date </td>
//         <td> $time </td>
//         <td> $mgs </td>
//         <td> $dbs </td>
//         <td> $stage </td>
//         <td> $status </td>
//     </tr>";
//     $i++;
// }



?>