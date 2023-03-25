<?php

include "conn.php";
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
$response = array();
if (isset($_GET['apicall'])) {
    session_start();
    switch ($_GET['apicall']) {
        case 'fetch_orgs': 
    
            {
                $sql="SELECT * FROM luag_organization_registration";
                $result=mysqli_query($conn,$sql);
        
                $arr1=mysqli_fetch_all($result,MYSQLI_ASSOC);
                mysqli_close($conn);
                if(count($arr1)){
                    $response['error'] = false;
                    $response['message'] = 'Data Fetched !';
                    $response['data']=$arr1;
                }
                else{
                    $response['error'] = true;
                    $response['message'] = 'No data found !';
                }
            break;

            }

        case 'fetch_emps':{
            if(isset($_GET['org_id'])){
                $org_id=$_GET['org_id'];
                $org_id = substr($org_id, 0, 10);
                $sql="SELECT * FROM luag_employee_registration WHERE Emp_Orgnization_id LIKE concat('%','$org_id','%') ";
                $result=mysqli_query($conn,$sql);
                $arr1=mysqli_fetch_all($result,MYSQLI_ASSOC);
                mysqli_close($conn);
                if(count($arr1)){
                    $response['error'] = false;
                    $response['message'] = 'Data Fetched !';
                    $response['data']=$arr1;
                }
                else{
                    $response['error'] = true;
                    $response['message'] = 'No data found !';
                }
            }
            else{
            $response['error'] = true;
            $response['message'] = 'Invalid Organisation';
            }
            break;
        }
        case 'admin_module':
            if (isTheseParametersAvailable(array(
                'emp_id', 'organization', 'user_role'
            ))) {

                $emp_id = $_POST["emp_id"];
                $organization = $_POST["organization"];
                $user_role = $_POST["user_role"];
                if(isset($_POST["note_approver_dbs"])){
                    $note_approver_dbs = $_POST["note_approver_dbs"];
                } else {
                    $note_approver_dbs = "NA";
                }
                if(isset($_POST["note_approver_mgs"])){
                    $note_approver_mgs = $_POST["note_approver_mgs"];
                } else {
                    $note_approver_mgs='NA';
                }

                if($emp_id=='NA' || $organization=='NA' || $user_role == 'NA') {
                    $response['error']=true;
                    $response['message']='Error! Employee Id, Organization and User Role cannot be NULL.';
                }
                else if($note_approver_dbs == 'NA' && $note_approver_mgs == 'NA' && ($user_role == 'Manager' || $user_role == 'Operator' )){
                    $response['error']=true;
                    $response['message']='Must Select a notification approver.';
                }
                else {
                    if($user_role=='Admin'){ //|| $user_role=='Operator'
                        $note_approver_dbs = 'NA';
                        $note_approver_mgs = 'NA';
                    }
                    // $stmt = $conn->prepare("SELECT id FROM luag_role_mapping WHERE Employee_Id LIKE concat('%',?,'%')");
                    $role= strtolower(substr($user_role,0,3));
                    $stmt = $conn->prepare("SELECT id FROM luag_role_mapping WHERE Employee_Id = ?");
                    $role_emp_id = $role.$emp_id;
                    $stmt->bind_param("s", $role_emp_id);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $response['error'] = true;
                        $response['message'] = 'This Employee is already mapped';
                        $stmt->close();
                    } else {
                        $sql_insert = "INSERT INTO luag_role_mapping(Employee_Id,Orgnization_Id,User_Role,note_approver_dbs,note_approver_mgs)
                            VALUES ('$role$emp_id','$organization','$user_role','$note_approver_dbs','$note_approver_mgs')";
                        $result = mysqli_query($conn, $sql_insert);

                        if ($result) {
                            

                            $sql = "update luag_employee_registration set Emp_num= '$role$emp_id' WHERE Emp_id = '$emp_id' ";
                            $result_emp = mysqli_query($conn, $sql);

                            if ($result_emp) {
                                $response['error'] = false;
                                $response['message'] = "Role assigned Successfully!";
                            } else {
                                $response['error'] = true;
                                $response['message'] = "Unable to assign role at this moment!";
                            }

                        } else {
                            $response['error'] = true;
                            $response['message'] = "Unable to assign role at this moment !";
                        }
                    }
                }
            }else {
                $response['error'] = true;
                $response['message'] = "Enter all mandatory fields";
            }
            break;

        case 'emp_reg':
            if (isTheseParametersAvailable(array(
                'Emp_id', 'Emp_Orgnization_id', 'Emp_Type', 'Emp_First_Name',
                'Emp_Middle_Name', 'Emp_Last_Name', 'Emp_Contact_Number', 'Emp_Email_Id', 'Emp_Age', 
                // 'status' --- this field is not in UI
            ))) {

                $Emp_id = $_POST["Emp_id"];
                $Emp_Orgnization_id = $_POST["Emp_Orgnization_id"];
                $Emp_Type = $_POST["Emp_Type"];
                $Emp_First_Name = $_POST["Emp_First_Name"];
                $Emp_Middle_Name = $_POST["Emp_Middle_Name"];
                $Emp_Last_Name = $_POST["Emp_Last_Name"];
                $Emp_Contact_Number = $_POST["Emp_Contact_Number"];
                $Emp_Email_Id = $_POST["Emp_Email_Id"];
                $Emp_Age = $_POST["Emp_Age"];
                // $status = $_POST["status"];
                $status='Inactive';

                $stmt = $conn->prepare("SELECT id FROM luag_employee_registration WHERE Emp_id = ? OR Emp_Contact_Number = ?");
                $stmt->bind_param("si", $Emp_id, $Emp_Contact_Number);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response['error'] = true;
                    $response['message'] = 'User already registered';
                    // echo "<script>alert('" . $response['message'] . "')</script>";
                    $stmt->close();
                } else {
                    $sql = "INSERT INTO luag_employee_registration(	Emp_id,Emp_Orgnization_id,Emp_Type,Emp_First_Name,Emp_Middle_Name,
                    Emp_Last_Name,Emp_Contact_Number,Emp_Email_Id,Emp_Age,status)
                 VALUES ('$Emp_id','$Emp_Orgnization_id','$Emp_Type','$Emp_First_Name','$Emp_Middle_Name'
                 ,'$Emp_Last_Name','$Emp_Contact_Number','$Emp_Email_Id','$Emp_Age','$status')";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        $response['error'] = false;
                        $response['message'] = "Employee Registered Successfully!";
                        // echo "<script>alert('" . $response['message'] . "')</script>";

                    } else {
                        $response['error'] = true;
                        $response['message'] = "Unable to register Employee at this moment!";
                        // echo "<script>alert('" . $response['message'] . "')</script>";
                    }
                }
            }else {
                $response['error'] = true;
                $response['message'] = "Enter all mandatory fields";
            }
            break;



        case 'emp_update':
            if (isTheseParametersAvailable(array(
                'Emp_id', 'Emp_Orgnization_id', 'Emp_Type', 'Emp_First_Name',
                'Emp_Middle_Name', 'Emp_Last_Name', 'Emp_Contact_Number', 'Emp_Email_Id', 'Emp_Age'
            ))) {
                $id=$_POST['id'];
                $Emp_id = $_POST["Emp_id"];
                $Emp_Orgnization_id = $_POST["Emp_Orgnization_id"];
                $Emp_Type = $_POST["Emp_Type"];
                $Emp_First_Name = $_POST["Emp_First_Name"];
                $Emp_Middle_Name = $_POST["Emp_Middle_Name"];
                $Emp_Last_Name = $_POST["Emp_Last_Name"];
                $Emp_Contact_Number = $_POST["Emp_Contact_Number"];
                $Emp_Email_Id = $_POST["Emp_Email_Id"];
                $Emp_Age = $_POST["Emp_Age"];
                // $modified_user = $_SESSION['user_id'];



                $sql = "UPDATE luag_employee_registration 
                SET
                Emp_Type='$Emp_Type',
                Emp_First_Name='$Emp_First_Name',
                Emp_Middle_Name='$Emp_Middle_Name',
                Emp_Last_Name='$Emp_Last_Name',
                Emp_Contact_Number='$Emp_Contact_Number',
                Emp_Email_Id='$Emp_Email_Id',
                Emp_Age='$Emp_Age'
                WHERE id=$id";

                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Details updated Successful!";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Unable to update details at this moment!";
                }
            }else {
                $response['error'] = true;
                $response['message'] = "Enter all mandatory fields";
            }

            break;

        case 'org_reg':
            if (isTheseParametersAvailable(array(
                'Org_id','Org_type', 'Org_Full_Name', 'Org_Short_Name', 'Org_Sector',
                'Address-l-1', 'Address-l-2', 'city', 'state', 'postal-code',
                'Org_Contact_Person', 'Org_Landline_Number', 'Org_Mobile_Number', 'Org_Location'
            ))) {
                $Org_id = $_POST['Org_id'];
                $Org_type = $_POST["Org_type"];
                $Org_Full_Name = $_POST["Org_Full_Name"];
                $Org_Short_Name = $_POST["Org_Short_Name"];
                $Org_Sector = $_POST["Org_Sector"];
                // $parent_org = $_POST['Parent_org'];
                $parent_org = '';
                // $geographical_area = $_POST['Geographical_area'];
                $geographical_area = '';
                // $Org_Full_Address = $_POST["Org_Full_Address"];
                $Org_Full_Address = $_POST['Address-l-1'] ." ". $_POST['Address-l-2'];
                if(isset($_POST['Address-l-3'])){
                    $Org_Full_Address .= " " . $_POST['Address-l-3'];
                 }
                 $Org_Full_Address .= " ".  $_POST['city'] ." ".  $_POST['state'] ." ".  $_POST['postal-code'];
                $Org_Contact_Person = $_POST["Org_Contact_Person"];
                $Org_Landline_Number = $_POST["Org_Landline_Number"];
                $Org_Mobile_Number = $_POST["Org_Mobile_Number"];
                $Org_Location = $_POST["Org_Location"];
                $create_user = $_SESSION['user_id'];

                if($Org_Short_Name == '' || $Org_id == '') {
                    $response['error'] = true;
                    $response['message'] = "Organization ID and Short Name can't be Null.";
                } else {
                    $org_short_name_sql= "select * from luag_organization_registration where Org_Short_Name='$Org_Short_Name'";
                    $short_name_result = mysqli_query($conn, $org_short_name_sql);
                    $short_name_rows=mysqli_fetch_all($short_name_result,MYSQLI_ASSOC);

                    $org_id_sql= "select * from luag_organization_registration where Org_id='$Org_id'";
                    $org_id_result = mysqli_query($conn, $org_id_sql);
                    $org_id_rows=mysqli_fetch_all($org_id_result,MYSQLI_ASSOC);

                    if(count($short_name_rows)) {
                        $response['error'] = true;
                        $response['message'] = 'Organization Short Name already exists.';
                    }else if($org_id_rows) {
                        $response['error'] = true;
                        $response['message'] = 'Organization Id already exists.';
                    }else {
                        $sql = "INSERT INTO luag_organization_registration(Org_id,Org_type,Org_Full_Name,Org_Short_Name,Org_Sector,Parent_org,Org_Full_Address,
                                Geographical_area,Org_Contact_Person,Org_Landline_Number,Org_Mobile_Number,Org_Location,Create_User_Id,Modified_User_Id)
                            VALUES ('$Org_id','$Org_type','$Org_Full_Name','$Org_Short_Name','$Org_Sector','$parent_org','$Org_Full_Address',
                            '$geographical_area','$Org_Contact_Person','$Org_Landline_Number','$Org_Mobile_Number','$Org_Location','$create_user','')";
                        $result = mysqli_query($conn, $sql);

                        if ($result) {
                            $response['error'] = false;
                            $response['message'] = "Organization Registered Successfully!";
                        } else {
                            $response['error'] = true;
                            $response['message'] = "Unable to register organization at this moment!". strval($result);
                        }
                    }
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = "Enter all mandatory fields";
            }
            break;

            // case 'org_reg':
            //     if (isTheseParametersAvailable(array(
            //         'Org_Type', 'Org_Full_Name', 'Org_Short_Name', 'Org_Sector', 'Org_Full_Address', 'Org_Contact_Person',
            //         'Org_Landline_Number', 'Org_Mobile_Number', 'Org_Location'
            //     ))) {

            //         $Org_Type = $_POST["Org_Type"];
            //         $Org_Full_Name = $_POST["Org_Full_Name"];
            //         $Org_Short_Name = $_POST["Org_Short_Name"];
            //         $Org_Sector = $_POST["Org_Sector"];
            //         $Org_Full_Address = $_POST["Org_Full_Address"];
            //         $Org_Contact_Person = $_POST["Org_Contact_Person"];
            //         $Org_Landline_Number = $_POST["Org_Landline_Number"];
            //         $Org_Mobile_Number = $_POST["Org_Mobile_Number"];
            //         $Org_Location = $_POST["Org_Location"];

            //         $sql = "INSERT INTO luag_organization_registration (Org_Type,Org_Full_Name,Org_Short_Name,Org_Sector,
            //         Org_Full_Address,Org_Contact_Person,Org_Landline_Number,Org_Mobile_Number,Org_Location) values 
            //         ('$Org_Type','$Org_Full_Name','$Org_Short_Name','$Org_Sector','$Org_Full_Address','$Org_Contact_Person'
            //         ,'$Org_Landline_Number','$Org_Mobile_Number','$Org_Location')";
            //         $result = mysqli_query($conn, $sql);

            //         if ($result) {
            //             $response['error'] = false;
            //             $response['message'] = "Data Insertion Successful!";
            //         } else {
            //             $response['error'] = true;
            //             $response['message'] = "Insertion Failed";
            //         }
            //     }
            //     break;

        default:
            $response['error'] = true;
            $response['message'] = 'There maybe some problems on our end, will be resolved soon.';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'There maybe some issues on our end, will be resolved soon.';
}
echo json_encode($response);
function isTheseParametersAvailable($params)
{
    foreach ($params as $param) {
        // echo $param . " , ";
        if (!isset($_POST[$param]) || $_POST[$param] == '') {
            return false;
        }
    }
    return true;
}
