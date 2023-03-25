<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "cng_luag";

include "conn.php";
// $conn = new mysqli($servername, $username, $password, $dbname);

$response = array();
if ($_POST['Emp_Contact_Number']) {

    $Emp_Contact_Number = $_POST['Emp_Contact_Number'];
    $stmt = $conn->prepare("SELECT id, Emp_id,Emp_Orgnization_id,Emp_Type,Emp_First_Name,
                Emp_Middle_Name,Emp_Last_Name,Emp_Contact_Number,Emp_Email_Id,Emp_Age 
                FROM luag_employee_registration 
                WHERE  Emp_Contact_Number = ?");
    $stmt->bind_param("s", $Emp_Contact_Number);
    $result = $stmt->execute();

    if ($result == TRUE) {
        $response['error'] = false;
        $response['message'] = "Retrieval Successful!";
        $stmt->store_result();
        $stmt->bind_result(
            $id,
            $Emp_id,
            $Emp_Orgnization_id,
            $Emp_Type,
            $Emp_First_Name,
            $Emp_Middle_Name,
            $Emp_Last_Name,
            $Emp_Contact_Number,
            $Emp_Email_Id,
            $Emp_Age
        );
        // $stmt->bind_result($Org_Type, $Org_Full_Name, $Org_Short_Name, $Org_Sector, $Org_Full_Address, $Org_Contact_Person, $Org_Landline_Number, $Org_Mobile_Number, $Org_Location);
        $stmt->fetch();
        $response['id']=$id;
        $response['Emp_id'] = $Emp_id;
        $response['Emp_Orgnization_id'] = $Emp_Orgnization_id;
        $response['Emp_Type'] = $Emp_Type;
        $response['Emp_First_Name'] = $Emp_First_Name;
        $response['Emp_Middle_Name'] = $Emp_Middle_Name;
        $response['Emp_Last_Name'] = $Emp_Last_Name;
        $response['Emp_Contact_Number'] = $Emp_Contact_Number;
        $response['Emp_Email_Id'] = $Emp_Email_Id;
        $response['Emp_Age'] = $Emp_Age;
    } else {

        $response['error'] = true;
        $response['message'] = "Incorrect Phone Number";
    }
} else {

    $response['error'] = true;
    $response['message'] = "Insufficient Parameters";
}

echo json_encode($response);
