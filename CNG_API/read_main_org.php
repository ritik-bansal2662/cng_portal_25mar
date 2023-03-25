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
    // selecting org details using org mobile number
    // $stmt = $conn->prepare("SELECT Slno, Org_Type,Org_Full_Name,Org_Short_Name,Org_Sector,Org_Full_Address,Org_Contact_Person,Org_Landline_Number,Org_Mobile_Number,Org_Location FROM LUAG_ORGANIZATION_REGISTRATION WHERE Org_Mobile_Number = ?");

    // selecting org details using org short name(case sensitive)
    $stmt = $conn->prepare("SELECT Slno,Org_id, Org_Type,Org_Full_Name,Org_Short_Name,Org_Sector,Org_Full_Address,Org_Contact_Person,Org_Landline_Number,Org_Mobile_Number,Org_Location FROM LUAG_ORGANIZATION_REGISTRATION WHERE binary Org_Short_Name = ?");
    $stmt->bind_param("s", $id);
    $result = $stmt->execute();

    if ($result == TRUE) {
        $response['error'] = false;
        $response['message'] = "Retrieval Successful!";
        $stmt->store_result();
        $stmt->bind_result($Slno, $Org_id, $Org_Type, $Org_Full_Name, $Org_Short_Name, $Org_Sector, $Org_Full_Address, $Org_Contact_Person, $Org_Landline_Number, $Org_Mobile_Number, $Org_Location);
        $stmt->fetch();
        $response['slno']=$Slno;
        $response['Org_id'] = $Org_id;
        $response['Org_Type'] = $Org_Type;
        $response['Org_Full_Name'] = $Org_Full_Name;
        $response['Org_Short_Name'] = $Org_Short_Name;
        $response['Org_Sector'] = $Org_Sector;
        $response['Org_Full_Address'] = $Org_Full_Address;
        $response['Org_Contact_Person'] = $Org_Contact_Person;
        $response['Org_Landline_Number'] = $Org_Landline_Number;
        $response['Org_Mobile_Number'] = $Org_Mobile_Number;
        $response['Org_Location'] = $Org_Location;
    } else {

        $response['error'] = true;
        $response['message'] = "Incorrect id";
    }
} else {

    $response['error'] = true;
    $response['message'] = "Insufficient Parameters";
}

// header("location: ../html/editreg.php");

echo json_encode($response);
