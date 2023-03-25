<?php
$db_name = "luag_cng";
$mysql_username = "root";
$mysql_password = "";
$server_name = "localhost";
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
date_default_timezone_set('Asia/Kolkata');
// if ($conn) {
//     echo "Connection Successful";
// } else {
//     echo "Connection Un-Successful";
// }
