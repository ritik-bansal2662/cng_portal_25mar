<?php

session_set_cookie_params(0);
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}
if(!(isset($_SESSION['admin']) && $_SESSION['admin'] == true && $_SESSION['manager'] == false)) {
    header('location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include('head.php'); ?>

<style>
    #map {
        width: 100%;
        height: 440px;
        /* background: #fff; */
        z-index: 0;
        border: 2px solid #000;
    }
    #loading, .date-details {
        display: flex;
        justify-content: space-around;
    }
    #error {
        display: none;
        justify-content: space-around;
        color: red;
    }

</style>

<body>
    <?php include('header.php'); ?>
    <div class='page-wrapper main-content'>
        <div class="page-breadcrumb">
            <div class="align-self-center">
                <h3 style="color:white;">LCV Status and Live Positioning</h3>
            </div>
        </div>

        <div class="container-fluid" style="background-color: #fff999;">
            <div class='gen-info content-active'>
                <h3>
                    <?php
                        if(isset($_GET['lcv_num'])) {
                            $lcv_num = $_GET['lcv_num'];
                            echo $lcv_num;
                        }
                    ?>
                </h3>

                <div class='date-details'>
                    <span>From : <?php echo $_GET['fromDate']; ?> </span>
                    <span>To : <?php echo $_GET['toDate']; ?> </span>
                </div>

                <div id='loading'>Loading...</div>

                <div id='error'>No Data Available</div>

                <hr />
                
                <div id="map"></div>
            </div>
        </div>
    </div>





    <!-- <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin=""></script> -->
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/fac149b818d7ba75db4aeee2b5e9f70b/map_load?v=1.5"></script>

    <script>
        <?php  include '../dist/js/lcv_live_positioning.js' ?> 
    </script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <?php include('footer.php'); ?>

</body>
</html>

