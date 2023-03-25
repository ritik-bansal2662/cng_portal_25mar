<?php
    include '../conn.php';
    echo 1;
    $resposne = array();
    $station_id = $_POST['station_id'];
    $stationary_cascade_id = $_POST['stationary_cascade_id'];
    $stmt = $conn->prepare("SELECT a.station_id, a.stationary_cascade_id, a.stationary_cascade_reorder_point, 
        a.compressor_id, a.dispenser_id, b.temperature_gauge_id, b.low_pressure_gauge_id, 
        b.medium_pressure_gauge_id, b.high_pressure_gauge_id, b.mass_flow_meter_id
        FROM (luag_station_equipment_master a INNER JOIN luag_station_instrument_master b 
        ON b.station_id = a.station_id 
        and b.stationary_cascade_id = a.stationary_cascade_id)
        WHERE a.station_id=? and a.stationary_cascade_id = ?");
    $stmt->bind_param("ss", $station_id, $stationary_cascade_id);
    $result = $stmt->execute();

    if ($result == TRUE) {
        $response['error'] = false;
        $response['message'] = "Retrieval Successful!";
        $stmt->store_result();
        $stmt->bind_result(
            $station_id,
            $stationary_cascade_id,
            $stationary_cascade_reorder_point,
            $compressor_id,
            $dspenser_id,
            $temp_gauge_id,
            $lp,
            $mp,
            $hp,
            $mfm
        );
        $stmt->fetch();
        $response['station_id'] = $station_id;
        $response['stationary_cascade_id'] = $stationary_cascade_id;
        $response['stationary_cascade_reorder_point'] = $stationary_cascade_reorder_point;
        $response['compressor_id']=$compressor_id;
        $response['dspenser_id']=$dspenser_id;
        $response['temp_gauge_id']=$temp_gauge_id;
        $response['lp']=$lp;
        $response['mp']=$mp;
        $response['hp']=$hp;
        $response['mf']=$mfm;
    } else {

        $response['error'] = true;
        $response['message'] = "Incorrect id";
    }

    echo json_encode($response);
?>