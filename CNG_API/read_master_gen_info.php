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
    $stmt = $conn->prepare("SELECT mgsId,Station_Name,Station_Address,Station_In_Charge_Name,
    Station_In_Charge_Contact_Number,Number_Filling_Bays,Number_Dispenser_Per_Bay,Latitude_Longitude 
    FROM luag_station_master 
    WHERE Station_Id = ?");
    $stmt->bind_param("s", $id);
    $result = $stmt->execute();

    if ($result == TRUE) {
        $response['error'] = false;
        $response['message'] = "Retrieval Successful!";
        $stmt->store_result();
        $stmt->bind_result(
            $mgsId,
            $Station_Name,
            $Station_Address,
            $Station_In_Charge_Name,
            $Station_In_Charge_Contact_Number,
            $Number_Filling_Bays,
            $Number_Dispenser_Per_Bay,
            $Latitude_Longitude
        );
        $stmt->fetch();
        $response['mgsId'] = $mgsId;
        $response['Station_Name'] = $Station_Name;
        $response['Station_Address'] = $Station_Address;
        $response['Station_In_Charge_Name'] = $Station_In_Charge_Name;
        $response['Station_In_Charge_Contact_Number'] = $Station_In_Charge_Contact_Number;
        $response['Number_Filling_Bays'] = $Number_Filling_Bays;
        $response['Number_Dispenser_Per_Bay'] = $Number_Dispenser_Per_Bay;
        $response['Latitude_Longitude'] = $Latitude_Longitude;
    } else {

        $response['error'] = true;
        $response['message'] = "Incorrect id";
    }
} else {

    $response['error'] = true;
    $response['message'] = "Insufficient Parameters";
}

echo json_encode($response);
