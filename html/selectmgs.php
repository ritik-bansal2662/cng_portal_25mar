<?php
//select.php  
if (isset($_POST["dbsid"])) {
    $output = '';
    $connect = mysqli_connect("localhost", "root", "", "cng_luag");
    $query = "SELECT Station_Id,mgsId FROM `luag_station_master` WHERE Station_Id = '" . $_POST["dbsid"] . "'";
    $result = mysqli_query($connect, $query);

    while ($row = mysqli_fetch_array($result)) {
        $output .= $row["mgsId"];
    }
    echo $output;
}
