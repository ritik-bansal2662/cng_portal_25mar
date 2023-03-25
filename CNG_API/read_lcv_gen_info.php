<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "cng_luag";

include "conn.php";

// $conn = new mysqli($servername, $username, $password, $dbname);

$response = array();
if ($_POST['id']) {

    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT Lcv_Registered_To, Vechicle_Type,Chassis_Num,Engine_Num,Cascade_Capacity,Lcv_Maker,Fuel_Type
    FROM reg_lcv 
    WHERE Lcv_Num = ?");
    $stmt->bind_param("s", $id);
    $result = $stmt->execute();

    if ($result == TRUE) {
        $response['error'] = false;
        $response['message'] = "Retrieval Successful!";
        $stmt->store_result();
        $stmt->bind_result(
            $Lcv_Registered_To,
            $Vechicle_Type,
            $Chassis_Num,
            $Engine_Num,
            $Cascade_Capacity,
            $Lcv_Maker,
            $Fuel_Type
        );
        $stmt->fetch();
        $response['Lcv_Registered_To'] = $Lcv_Registered_To;
        $response['Vechicle_Type'] = $Vechicle_Type;
        $response['Chassis_Num'] = $Chassis_Num;
        $response['Engine_Num'] = $Engine_Num;
        $response['Cascade_Capacity'] = $Cascade_Capacity;
        $response['Lcv_Maker'] = $Lcv_Maker;
        $response['Fuel_Type'] = $Fuel_Type;
        
    } else {

        $response['error'] = true;
        $response['message'] = "Incorrect id";
    }
} else {

    $response['error'] = true;
    $response['message'] = "Insufficient Parameters";
}

echo json_encode($response);
