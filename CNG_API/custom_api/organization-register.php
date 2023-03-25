<?php


session_start();
include '../conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type=$_POST['type'];
    $full_name=$_POST['fullname'];
    $short_name=$_POST['org-abberevation'];
    $sector=$_POST['org-sector'];
    $add1=$_POST['Address-l-1'];
    $add2=$_POST['Address-l-2'];
    $add3=$_POST['Address-l-3'];
    $city=$_POST['city'];
    $state=$_POST['state'];
    $postal_code=$_POST['postal-code'];
    $contact_person=$_POST['contact-person'];
    $mobile=$_POST['mobile'];
    $landline=$_POST['landline'];

    $sql_insert= "INSERT INTO luag_organization_registration 
        (Org_Id, Org_Type, Org_Full_Name, Org_Short_Name, Org_Sector, Parent_org, Geographical_area, Org_Full_Address, Org_Contact_Person, Org_Landline_Number, Org_Mobile_Number, Org_Location, Create_User_Id, Modified_User_Id) 
        values (120, '$type', '$full_name', '$short_name', '$sector', 'NA', '$city', '$add1.$add2.$add3.$city.$state', '$contact_person', $landline, $mobile, '$state', 'abc12', 'def123')";
    $insert_result = mysqli_query($conn, $sql_insert);
    echo $insert_result;
    // header('location: ../../index.php?content=registration');
}
?>