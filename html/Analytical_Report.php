<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}



include '../CNG_API/conn.php';
// $connect = mysqli_connect("localhost", "root", "", "cng_luag");


/* Getting demo_viewer table data */
$sql = "select a.date_reading date,a.total_gas_dbs total_gas_received_at_mgs,
b.total_mass_of_gas,b.gas_sales, b.gas_sales*58.9 total_gas_sales,
a.total_gas_dbs-( b.gas_sales+b.total_mass_of_gas) total_loss_gas_dispenser , 
(a.total_gas_dbs-( b.gas_sales+b.total_mass_of_gas))/a.total_gas_dbs*100 percentage_loss_gas_dispenser 
from( select date_reading,sum(total_gas_dbs) total_gas_dbs 
from luag_transaction_master_dbs_station where total_gas_dbs <> '' group by date_reading) a ,
( select date_reading, sum(mass_of_gas) total_mass_of_gas,sum(dispenser_read) gas_sales 
from luag_transaction_dbs_dispenser_cascade group by date_reading)
 b where a.date_reading=b.date_reading group by b.date_reading";
$viewer = mysqli_query($conn, $sql);
$viewer = mysqli_fetch_all($viewer, MYSQLI_ASSOC);
$viewer = json_encode(array_column($viewer, 'percentage_loss_gas_dispenser'), JSON_NUMERIC_CHECK);


/* Getting demo_click table data */
$sql = "select a.date_reading date,a.total_gas_dbs total_gas_received_at_mgs,
b.total_mass_of_gas,b.gas_sales, b.gas_sales*58.9 total_gas_sales,
a.total_gas_dbs-( b.gas_sales+b.total_mass_of_gas) total_loss_gas_dispenser , 
(a.total_gas_dbs-( b.gas_sales+b.total_mass_of_gas))/a.total_gas_dbs*100 percentage_loss_gas_dispenser 
from( select date_reading,sum(total_gas_dbs) total_gas_dbs 
from luag_transaction_master_dbs_station where total_gas_dbs <> '' group by date_reading) a ,
( select date_reading, sum(mass_of_gas) total_mass_of_gas,sum(dispenser_read) gas_sales 
from luag_transaction_dbs_dispenser_cascade group by date_reading)
 b where a.date_reading=b.date_reading group by b.date_reading";
$click = mysqli_query($conn, $sql);
$click = mysqli_fetch_all($click, MYSQLI_ASSOC);
$click = json_encode(array_column($click, 'date '), JSON_NUMERIC_CHECK);


?>

<!DOCTYPE html>
<html lang="en">

<!-- <head> -->
    <?php 
        include('head.php'); 
    ?>
    <style>
        .head-extra {
            width: 100%;
            height: 50px;
            border: 2px solid black;
        }
    </style>
    <title>HighChart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    
<!-- </head> -->
<body>
    <?php include('header.php'); ?>
    <!-- <h1>test</h1> -->
    <div class="page-wrapper main-content">
        <div class='head-extra'></div>
        <div class="page-breadcrumb">
            <div class="text-white" style="text-align: center;">
                <h2 style="color:white;">Highcharts php mysql json example</h2>
            </div>
        </div>

        <div class="container-fluid" style="background-color: #fff999;">
            <br />
            <!-- <h2 class="text-center">Highcharts php mysql json example</h2> -->
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">Dashboard</div>
                        <div class="panel-body">
                            <div id="container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            var data_click = <?php echo $click; ?>;
            var data_viewer = <?php echo $viewer; ?>;
            $('#container').highcharts({
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Yearly Website Ratio'
                },
                xAxis: {
                    name: 'Click',
                    categories: data_click
                },
                yAxis: {
                    title: {
                        text: 'Percentage'
                    }
                },
                series: [

                    {
                        name: 'Date',
                        data: data_viewer
                    }
                ]
            });
        });
    </script>
    <?php include('footer.php'); ?>
</body>

</html>