<?php

include '../../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

if(isset($_POST['table'])){
    switch($_POST['table']){
        case 'general':
            $table='luag_station_master';
            break;
        case 'equipment':
            $table='luag_station_equipment_master';
            break;
        case 'instrument':
            $table='luag_station_instrument_master';
            break;
        default:
            $table='luag_station_master';
            break;
    }
    // $table='luag_station_master';
    $station_type=$_POST['type'];

    $sql="select Station_id from $table where Station_type='$station_type'";
    $result = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($result);
    // echo "Number of rows : ";
    // echo $num_rows;
    $st='DBS';
    if($station_type=="City Gas Station"){
        $st='CGS';
    }
    else if($station_type=="Mother Gas Station"){
        $st='MGS';
    }
    $output = "<option value='0'>Select ".$st ." Station Id</option>";
    while($row = $result-> fetch_assoc()) {
        $output .= "<option value='".$row['Station_id']."'>".$row['Station_id']."</option>";
    }
    echo $output;

} else {
    echo "table not set";
}

?>