<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "cng_luag";

// $conn = new mysqli($servername, $username, $password, $dbname);

include "conn.php";
// $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
session_start();
if(isset($_POST['slno'])) {
    if (isTheseParametersAvailable(array(
        'Org_Type', 'Org_Full_Name', 'Org_Short_Name', 'Org_Sector',
        'Org_Full_Address',
        'Org_Contact_Person', 'Org_Landline_Number', 'Org_Mobile_Number', 'Org_Location'
    ))){

            $slno = $_POST['slno'];
            $Org_Type = $_POST["Org_Type"];
            $Org_Full_Name = $_POST["Org_Full_Name"];
            $Org_Short_Name = $_POST["Org_Short_Name"];
            $Org_Sector = $_POST["Org_Sector"];
            $Org_Full_Address = $_POST["Org_Full_Address"];
            $Org_Contact_Person = $_POST["Org_Contact_Person"];
            $Org_Landline_Number = $_POST["Org_Landline_Number"];
            $Org_Mobile_Number = $_POST["Org_Mobile_Number"];
            $Org_Location = $_POST["Org_Location"];
            $Modified_User_Id = $_SESSION['user_id'];


            $sql = "UPDATE LUAG_ORGANIZATION_REGISTRATION SET Org_Type='$Org_Type' , Org_Full_Name='$Org_Full_Name' , Org_Short_Name='$Org_Short_Name' , Org_Sector='$Org_Sector', Org_Full_Address='$Org_Full_Address' , Org_Contact_Person='$Org_Contact_Person' , Org_Landline_Number='$Org_Landline_Number' , Org_Mobile_Number ='$Org_Mobile_Number' ,Org_Location = '$Org_Location',Modified_User_Id='$Modified_User_Id' WHERE Slno =$slno";

            $result = mysqli_query($conn, $sql);
            $response = array();
            if ($result) {
                $response['error'] = false;
                $response['message'] = "Organization Details updated successfully!";
            } else {
                $response['error'] = true;
                $response['message'] = "Unable to update details at this moment.";
            }
            mysqli_close($conn);
        } else {
            $response['error'] = true;
            $response['message'] = "Enter all mandatory fields";
        }
} else {
    $response['error'] = true;
    $response['message'] = "Unable to update details at this moment.";
}
// header('location: ../html/editreg.php');
echo json_encode($response);
function isTheseParametersAvailable($params)
{
    foreach ($params as $param) {
        // echo $param . " , ";
        if (!isset($_POST[$param])) {
            return false;
        }
    }
    return true;
}

?>