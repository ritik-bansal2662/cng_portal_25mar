<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.php'); ?>
</head>
<body>
    
    <tr>
        <td>1</td>
        <td rowspan=1>DL1NEW2022</td>
        <td>Thu Dec 29 2022</td>
        <td>mgs003</td>
        <td>DBS12</td>
        <td>2022-12-29 16:35:46</td>
        <td>2022-12-29 16:40:00</td>
        <td>2022-12-29 16:43:26</td>
        <td>
            <button data-lcv_num='DL1NEW2022 data-fromDate=2022-12-29 16:43:26 data-toDate=2022-12-29 16:45:55 onClick="map_position(event)">Tracking History</button>
        </td><td>2022-12-29 16:45:55</td><td>2022-12-29 16:47:29</td><td>2022-12-29 16:47:29</td><td>
            <button data-lcv_num='DL1NEW2022 data-fromDate=2022-12-29 16:43:26 data-toDate=2022-12-29 16:45:55 onClick="map_position(event)">Tracking History</button>
        </td></tr><tr><td>2</td><td rowspan=1>DL1TEMP2021</td><td>Thu Dec 29 2022</td>
        <td>mgs003</td>
        <td>DBS123</td><td>2022-12-29 16:35:48</td><td> - </td><td> - </td><td>
                    No Data
                </td><td> - </td><td> - </td><td> - </td><td>
                    No Data
                </td></tr>    


</body>
</html>



<?php 
                                
                            $select_sql= 'select * from luag_organization_registration';
                            $select = mysqli_query($conn, $select_sql);
                            $num_rows = mysqli_num_rows($select);
                            // echo "Number of rows : ";
                            // echo $num_rows;
                            // while($row = $select -> fetch_assoc()) { 
                        //     print_r($row['Org_Full_Name']);  
                        ?>






<!-- lcv number in modal in index.php -->

<?php
                                            $sql = "SELECT  distinct(Lcv_Num) Lcv_Num FROM `reg_lcv` WHERE `lcv_status` in ('Halt','Transit','')";
                                            $all_categories = mysqli_query($conn, $sql);
                                            while ($category = mysqli_fetch_array(
                                                $all_categories,
                                                MYSQLI_ASSOC
                                            )) :;

                                            ?>
                                                <option value="<?php echo $category["Lcv_Num"];
                                                                ?>">
                                                    <?php echo $category["Lcv_Num"];
                                                    ?>
                                                </option>
                                            <?php
                                            endwhile;
                                            ?>
<!-- lcv number end -->



