<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
    <title>DBS Stationary Cascade Gas Info</title>
    <?php include('head.php'); ?>
    <style>
        .container {
            margin: 50px 50px;
        }
        table{
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            
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
    </style>
</head>

<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">
        <div class="page-breadcrumb">
            <div class="align-self-center">
                <h2 style="color:white;">DBS Stationary Cascade Gas Info</h2>
            </div>
        </div>
        <div class="container-fluid" style="background-color: #fff999;">
            <div class="w3-container ">
                <div id='download_excel_div'><button id='download_excel' class='btn btn-primary'>Download Report</button></div>
                <div class="w3-responsive">
                    <!-- <h2 align="center">DBS Stationary Cascade Gas Info</h2> -->
                    <!-- <p>*BRC: Before Refilling of Cascade,*MFMR: Mass Flow Meter Readings,*AFC: After Refilling of Cascade
                    </p> -->
                    <!-- <table class="w3-table-all w3-small" style="width: 110%;">
                        <thead>
                            <tr>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 39.5%;" colspan="4"><strong></strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 15%;" colspan="4"><strong>Before Refilling of Cascade</strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 22.5%;" colspan="3"><strong>Mass Flow Meter Readings</strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 18.5%;" colspan="4"><strong>After Refilling of Cascade</strong></th>
                                <th bgcolor=" #02603E" class="header option-item" style="width: 16%;"><strong>Gas filled at MGS</strong></th>
                            </tr>
                        </thead>
                    </table> -->
                    <!-- w3-table-all w3-small -->
                    <!-- table table-striped table-bordered -->
                    <table id="mytable" class="w3-table-all w3-small table table-bordered no-wrap">
                        <thead>
                            
                            <tr>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Slno</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Time</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Station ID</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Gas in Cascade</strong></th>
                            </tr>

                        </thead>
                        <tbody style="background-color: #E9C006; ">
                            <?php
                            $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");
                            function decimalHours($time)
                            {
                                $hms = explode(":", $time);
                                return ($hms[0] + ($hms[1] / 60) + ($hms[2] / 3600));
                            }
                            $stmt = $db->prepare("SELECT date(create_date) cas_date,time(create_date) cas_time,station_id, mass_of_gas FROM luag_schedular WHERE `sl_no` IN (SELECT MAX(`sl_no`) FROM luag_schedular GROUP BY `station_id`)");
                            $stmt->execute();
                            $i=1;
                            while ($row = $stmt->fetch()) {
                                $mass_of_gas=$row["mass_of_gas"];
                                if($mass_of_gas<'300'){
                                    // echo "<tr style='background-color:red;'>"; ?>
                                    <tr class='alert alert-danger alert-dismissible fade show'>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $row["cas_date"]; ?>
                                    </td>
                                    <td >
                                        <?php echo $row["cas_time"]; ?>
                                    </td>

                                    <td >
                                        <?php echo $row["station_id"]; ?>
                                    </td>
                                    
                                    <td >
                                        <?php echo $row["mass_of_gas"]; ?>
                                    </td>

                                </tr>
                                <?php }
                                else if($mass_of_gas<'500' && $mass_of_gas>'300'){
                                    // echo "<tr style='background-color:#FFBF00;'>"; ?>
                                    <!-- <tr style='background-color:#FFBF00;'> -->
                                    <tr class="alert alert-success alert-dismissible fade show">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $row["cas_date"]; ?>
                                        </td>
                                        <td>
                                            <?php echo $row["cas_time"]; ?>
                                        </td>
                                        <td>
                                            <?php echo $row["station_id"]; ?>
                                        </td>
                                        <td>
                                            <?php echo $row["mass_of_gas"]; ?>
                                        </td>
                                    </td>




                                </tr>
                                <?php }
                                else if ($mass_of_gas>'500'){
                                    // echo "<tr style='background-color:powderblue;'>"; ?>
                                    <!-- <tr style='background-color:green;'> -->
                                    <tr class="alert alert-success alert-dismissible  fade show">
                                    <td >
                                        <?php echo $i; ?>
                                    </td>
                                    <td >
                                        <?php echo $row["cas_date"]; ?>
                                    </td>
                                    <td >
                                        <?php echo $row["cas_time"]; ?>
                                    </td>

                                    <td >
                                        <?php echo $row["station_id"]; ?>
                                    </td>
                                    
                                    <td >
                                        <?php echo $row["mass_of_gas"]; ?>
                                    </td>
                                    

                                    </td>




                                </tr>
                                <?php   }
                            
                                
                            
                                $decimalHours = 0;
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="ddtf.js"></script>
    <script>
        $("#mytable").ddTableFilter();
    </script>
    <script src="../dist/js/table2excel.js"></script>

    <script>
        $('#download_excel').click(function() {
            var table2excel = new Table2Excel();
            table2excel.export($("#mytable"));
        })
    </script>

    <?php include('footer.php'); ?>

</body>

</html>