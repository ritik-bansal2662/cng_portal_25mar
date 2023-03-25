<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">


    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
    <title>Reconciliation Data at Organization Level</title>
    <?php include('head.php'); ?>
    <style>
        .container {
            margin: 50px 50px;
        }

        thead {
            color: white;

        }

        tbody {
            color: black;

        }

        tfoot {
            color: red;
        }

        table,
        th,
        td {
            border: 1px solid white;
        }
        table th {
            background-color: gray;
        }

        th, td{
            padding: 5px;
            text-align: center;
        }

        .ddtf-processed th.option-item>select {
            display: none;
        }

        .ddtf-processed th.option-item>div {
            display: block !important;
        }

        .dbs_content, .mgs_content {
            /* margin: 20px; */
            color: black;
            display: none;
            /* border: 2px solid black; */
            /* border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 30px; */
        }

        .content-active {
            display: block;
        }
    </style>


<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">
        <div class="page-breadcrumb">
            <div class="align-self-center">
                <h2 style="color:white;">Reconciliation Data at Organization Level</h2>
            </div>
        </div>

        <div class='reg-main'>
            <div class='mgs_tab main-active'>MGS</div>
            <div class='dbs_tab'>DBS</div>
        </div>
        
        <div class="container-fluid" style="background-color: #fff999;">
            <div class=''>
                <div class='mgs_content content-active'>
                    <div id='download_excel_div'>
                        <button id='download_excel1' class='btn btn-primary'>Download Report</button>
                    </div>
                    <div class="table_div">
                        <table id="mytable1" class="w3-table-all w3-small">
                            <thead>
                                <tr>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                    <th bgcolor="#02603E" class="header" scope="col"><strong> LCV Number </strong></th>
                                    <th bgcolor="#02603E" class="header" scope="col"><strong> Mother Gas Station </strong></th>
                                    <th bgcolor="#02603E" class="header" scope="col"><strong> Daughter Booster Station </strong></th> 
                                    <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Amount of Gas filled at MGS (Kg)</strong></th>
                                    <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Amount of Gas delivered at DBS (Kg) </strong></th>
                                    <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Gas Loss during Transportation (Kg) </strong></th>

                                </tr>
                            </thead>
                            <tbody >
                                <?php
                                $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");

                                $stmt = $db->prepare("SELECT *, 
                                sum(after_filling_at_mgs_mfm_value_read-before_filing_at_mgs_mass_cng) as total_gas_mgs_mfm, 
                                sum(after_filling_at_mgs_mass_cng-before_filing_at_mgs_mass_cng) as total_gas_mgs, 
                                sum(before_empty_at_db_mass_cng-after_empty_at_dbs_mass_cng) as total_gas_dbs,
                                sum(after_filling_at_mgs_mfm_value_read-before_empty_at_db_mass_cng) as total_gas_dbs_mfm , 
                                sum(after_filling_at_mgs_mfm_value_read - before_filing_at_mgs_mass_cng - 
                                (before_empty_at_db_mass_cng - after_empty_at_dbs_mass_cng)) gas_loss 
                                ,
                                
                                
                                sum(after_filling_at_mgs_mfm_value_read + before_filing_at_mgs_mass_cng - 
                                before_empty_at_db_mass_cng ) mfm_gas_loss 
                                from luag_transaction_master_dbs_station 
                                group by date_reading ORDER BY sl_no desc;");
                                $stmt->execute();
                                while ($row = $stmt->fetch()) {
                                ?>
                                    <tr>
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["date_reading"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["lcv_id"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["station_id"]; ?>
                                        </td>
                                        
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["dbs_station_id"]; ?>
                                        </td>

                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">

                                            <?php
                                            if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                                echo round($row["total_gas_mgs"], 2);
                                            } else {

                                                echo round($row["total_gas_mgs_mfm"], 2);
                                            } ?>

                                        </td>

                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php

                                            echo round($row["total_gas_dbs"], 2);

                                            ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php
                                            if($row["date_reading"]>='2022-02-15'){
                                                    echo round($row["mfm_gas_loss"], 2);
                                            }else{
                                            if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                                echo round($row["total_gas_mgs"] - $row["total_gas_dbs"], 2);
                                            } else {
                                                echo round($row["gas_loss"], 2);
                                            }} ?>
                                            <?php  ?>
                                        </td>

                                    </tr>
                                <?php
                                    $decimalHours = 0;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class='dbs_content'>
                    <div id='download_excel_div'>
                        <button id='download_excel3' class='btn btn-primary'>Download Report</button>
                    </div>
                    <div class="table_div">
                        <table id="mytable3" class="w3-table-all w3-small">
                            <thead >
                            <tr>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                <th bgcolor="#02603E" class="header" scope="col"><strong> LCV Number </strong></th>
                                
                                <th bgcolor="#02603E" class="header" scope="col"><strong> Daughter Booster Station </strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Amount of Gas received at DBS (Kg)</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Mass of Gas in Stationary Cascade (Kg)</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Amount of Gas sold at DBS through Dispenser (Kg) </strong></th>
                                

                                </tr>

                            </thead>
                            
                            <tbody style="background-color: #d5c47a; ">
                                <?php
                                try {
                                    $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");
                                    class Customers
                                    {
                                        private $servername = "localhost";
                                        private $username   = "root";
                                        private $password   = "";
                                        private $dbname     = "cng_luag";
                                        public  $con;

                                        // Database Connection 
                                        public function __construct()
                                        {
                                            try {
                                                $this->con = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
                                            } catch (Exception $e) {
                                                echo $e->getMessage();
                                            }
                                        }

                                        public function updateData()
                                        {
                                            $query = "Insert ignore  into luag_dispenser_reading 
                                        (reading_timestamp,date_reading,dbs_station_id,cascade_mass,dispenser_id,dispenser_read,diff_dispenser_reading)
                                        SELECT create_date,date(create_date),station_id,`mass_of_gas`,dispenser_id,dispenser_read, 
                                        ((dispenser_read) - LAG(dispenser_read) OVER (PARTITION BY dispenser_id ORDER BY sl_no ))
                                            dispenser_diff FROM `luag_transaction_dbs_dispenser_cascade`      
                                        GROUP by dispenser_id,date_reading";
                                            $sql = $this->con->query($query);
                                            if ($sql == true) {
                                                return true;
                                            } else {
                                                return false;
                                            }
                                        }
                                    }
                                    $customerObj = new Customers();
                                    $customers = $customerObj->updateData();
                            // already commented
                                    // $stmt = $db->prepare("select lcv_id,t1.station_id,t1.dbs_station_id,t1.date_reading,create_date, total_gas_mgs_mfm,
                                    // total_gas_mgs_calc,total_gas_dbs_calc,cascade_mass,total_sale
                                    //  from (SELECT lcv_id,station_id,dbs_station_id,date_reading,create_date, 
                                    // sum(after_filling_at_mgs_mfm_value_read-before_filing_at_mgs_mass_cng) as total_gas_mgs_mfm, 
                                    // sum(after_filling_at_mgs_mass_cng-before_filing_at_mgs_mass_cng) as total_gas_mgs_calc, 
                                    // sum(before_empty_at_db_mass_cng-after_empty_at_dbs_mass_cng) as total_gas_dbs_calc 
                                    // from luag_transaction_master_dbs_station group by date_reading ORDER BY sl_no desc) t1 ,
                                    // (SELECT `date_reading`,`cascade_mass`,`dbs_station_id`, sum(`diff_dispenser_reading`) as total_sale 
                                    // FROM `luag_dispenser_reading` GROUP by date(`date_reading`),`dbs_station_id`) t2 
                                    // where t1.dbs_station_id=t2.dbs_station_id and t1.date_reading=t2.date_reading");
                                    // $stmt = $db->prepare("CALL `dispenser_reading`()");
                                    // $stmt->execute();
                            // till here
                                    $sql = "CALL dispenser_reading()";
                                    $stmt = $db->query($sql);
                                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    die("Error occurred:" . $e->getMessage());
                                }
                                while ($row = $stmt->fetch()) {
                                ?>
                                    <tr>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo $row["date_reading"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo $row["lcv_id"]; ?>
                                        </td>
                                        
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo $row["dbs_station_id"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo round($row["total_gas_dbs_calc"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo $row["cascade_mass"]; ?>
                                        </td>

                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo round($row["total_sale"], 2); ?>
                                        </td>
                                        

                                    </tr>
                                <?php
                                    $decimalHours = 0;
                                }
                                ?>
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="ddtf.js"></script>
    
    <script>
        $("#mytable1").ddTableFilter();
        </script>

    <script>
        $("#mytable3").ddTableFilter();
    </script>

    <script src="../dist/js/table2excel.js"></script>

    <script>
        $('#download_excel').click(function() {
            var table2excel = new Table2Excel();
            table2excel.export($("#mytable1"));
        })
    </script>

    <script>
        $(".mgs_tab").click(function(){
            console.log("mgs_tab vlicked")
            $(this).addClass('main-active');
            $('.dbs_tab').removeClass('main-active');
            $('.mgs_content').addClass('content-active');
            $('.dbs_content').removeClass('content-active');
        })


        $(".dbs_tab").click(function(){
            console.log("dbs_tab vlicked")
            $(this).addClass('main-active');
            $('.mgs_tab').removeClass('main-active');
            $('.dbs_content').addClass('content-active');
            $('.mgs_content').removeClass('content-active');
        })
    </script>

    <?php include('footer.php'); ?>


</body>

</html>