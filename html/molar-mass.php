<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <<?php include('head.php'); ?>
</head>
<body>
    <?php include('header.php'); ?>
    <div class='page-wrapper'>
        <div class="page-breadcrumb">
            <div class=" align-self-center">
                <h2 style="color:white; ">Edit/View LCV</h2>

            </div>

        </div>
        <h5>Calculate Molar Mass using Density</h5>
        <img src='./assets/meter.jpg' alt="Meter Image" /> 
        <form method='post' action='CNG_API/calculateMolarMass.php' enctype="multipart/form-data">
            <input type='file' name='density_meter_img' class='col-10 m-1' />
            <input type='number' id='molar-mass' name='molar_mass' value="3456" placeholder='Enter Mass/Volume(Kg/m&#179;)' class='col-10 m-1' />
            <div class='btn btn-primary' id='calculate'>Calculate</div>
            <p class='error-message'></p>
            <div id='density'>
                Density = <span>0</span> (kg/m&#179;)
            </div>
            <div id='molar'>
                Molar Mass = <span>0</span> g
            </div>

            <button type='submit' class='btn submit-btn btn-primary mt-2 mb-2'>Submit</button>

        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script>
        <?php include 'molar-mass.js' ?>
    </script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <?php include('footer.php'); ?>
</body>
</html>

