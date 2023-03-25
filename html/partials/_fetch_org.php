<?php 
    include '../../CNG_API/conn.php';
    // $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
    
    $select_sql= 'select distinct Org_Short_Name from luag_organization_registration';
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    // echo "Number of rows : ";
    // echo $num_rows;
    $output = "<option value='NA'>Select Organization</option>";
    while($row = $result-> fetch_assoc()) {
        $output .= "<option value='".$row['Org_Short_Name']."'>".$row['Org_Short_Name']."</option>";
    }
    echo $output;
?>
    
    
    
