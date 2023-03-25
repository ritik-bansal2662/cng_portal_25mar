<?php

include('db.php');

// ##################################################################
// View Data
// if (isset($_POST["employee_id"])) {
//      $output = '';
//      $query = "SELECT * FROM files WHERE id = '" . $_POST["employee_id"] . "'";
//      $result = mysqli_query($con, $query);
//      $output .= '  
//       <div class="table-responsive">  
//            <table class="table table-bordered">';
//      while ($row = mysqli_fetch_array($result)) {
//           $output .= '  
//                 <tr>  
//                      <td width="30%"><label>Title</label></td>  
//                      <td width="70%">' . $row["title"] . '</td>  
//                 </tr>  
//                 <tr>  
//                      <td width="30%"><label>Description</label></td>  
//                      <td width="70%">' . $row["description"] . '</td>  
//                 </tr>  
//                 <tr>  
//                      <td width="30%"><label>Uploaded_on</label></td>  
//                      <td width="70%">' . $row["uploaded_on"] . '</td>  
//                 </tr>  

//            ';
//      }
//      $output .= '  
//            </table>  
//       </div>  
//       ';
//      echo $output;
// }
// ##################################################################
// Update Data
if (isset($_POST['update'])) {
     $mgsid = $_POST['mgsid'];
     $dbsid = $_POST['dbsid'];
     $distance = $_POST['distance'];
     $lcvid =  $_POST['lcvid'];
     $lcvstatus = 'Scheduled';

     mysqli_query($con, "UPDATE `reg_lcv` 
     	SET `lcv_status`='Scheduled'
         WHERE Lcv_Num='$lcvid'");
     $sql = "INSERT into `luag_schedular_trans` (mgs_id ,dbs_id ,distance_between_stations,lcv_id ,lcv_status )
    values('$mgsid','$dbsid','$distance','$lcvid','$lcvstatus')";
     if (mysqli_query($con, $sql)) {
          echo json_encode(array("statusCode" => 1));
     } else {
          echo json_encode(array("statusCode" => 2));
     }
     mysqli_close($con);
}
