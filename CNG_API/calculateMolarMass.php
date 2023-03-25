<?php

include "conn.php";
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

$upload_url = "http://182.77.57.154/LUAG_HPCL/images/";

$molar_mass = $_POST['molar_mass'];
$density_meter_reading = $_POST['density_meter_reading'];
$density_meter_img = $_POST['density_meter_img'];

$density_mtr_img =  "IMG" . rand() . ".jpg";
$density_mtr_img_url = $upload_url . $density_mtr_img;
file_put_contents("images/" . $density_mtr_img, base64_decode($density_meter_img));

echo $molar_mass, $density_meter_reading, $density_mtr_img_url;



$sql = "INSERT into luag_molar_density (density_meter_img,density_meter_reading,molar_mass )
 values ('$density_mtr_img_url','$density_meter_reading','$molar_mass')";

$result = mysqli_query($conn, $sql);

if ($result) {
    $response['error'] = true;
    $response['message'] = 'Density and Molar Mass data inserted Successfully';
} else {
    //$response['error'] = true;
    $response['message'] = "Insertion Failed";
}
