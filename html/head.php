<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>CNG Dashboard</title>
    <!-- Custom CSS -->
    <!-- <link href="../assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" /> -->
    <!-- Custom CSS -->
    <!-- <link href="../dist/css/style.min.css" rel="stylesheet"> -->

    <!-- <link href="../dist/css/registration.css" rel="stylesheet" /> -->
    <!-- <link href="../dist/css/register-station.css" rel="stylesheet" /> -->
    <!-- <link href="../dist/css/edit-station.css" rel="stylesheet" /> -->
    <!-- <link href="../dist/css/edit-lcv.css" rel="stylesheet" /> -->
    <!-- <link href="../dist/css/admin.css" rel="stylesheet" /> -->
    <!-- <link href="../dist/css/scheduling.css" rel="stylesheet" /> -->


    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />


    <Script src="https://code.jquery.com/jquery-1.12.3.js" type="text/javascript"></Script>
    <Script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></Script>
    <Script src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js" type="text/javascript"></Script>
    <Script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></Script>
    <Script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js" type="text/javascript"></Script>
    <style>
        <?php 
            include '../dist/css/registration.css';
            include '../dist/css/register-station.css';
            include '../dist/css/edit-station.css';
            include '../dist/css/edit-lcv.css';
            include '../dist/css/admin.css';
            include '../dist/css/scheduling.css';
            include '../dist/css/style.min.css';
        ?>
        body {
            background-color: #E9C006;
        }

        .test {
            border: 1px solid black;
        }

        .logo-icon img {
            width: 200px;
            height: auto;
            /* border: 2px solid blue; */
        }

        .left-sidebar {
            background-color: #E9C006;
            /* background-color: #b6fdc0; */
            color: black;
        }

        /* .toogle-show {
            display: block !important;
        } */

        .topbar {
            background-color: #E9C006;
            /* background-color: #b6fdc0; */
            color: black;

        }

        .topbar h3 {
            margin-left:auto;
            margin-right:auto;
        }

        .page-wrapper {
            background: #02603E;
            position: relative;
            display: none;
            box-shadow: 0 3px 9px 0 rgba(162, 176, 190, .15);
            border-top-left-radius: 5px;
            border-top-right-radius: 5px
        }

        .card {
            background-color: #fff999;
            color: black;
            font-size: x-large;
            font-weight: 500;

        }

        .top-temp {
            width:100%;
            height: 130px;
        }

        .top-temp-extra {
            height: 60px;
            width: 100%;
        }

        .side-temp {
            width: 260px;
            height: 100%;
            margin:0;
            z-index: 0;
        }

        .sidebar-nav #sidebarnav .sidebar-item .sidebar-link {
            color: #02603E;
            font-weight: 600;
            transform: 
        }

        .sidebar-nav #sidebarnav .nav-small-cap {
            color: brown;
            font-size: larger;
            font-weight: 800;
        }

        .page-title {
            color: brown
        }

        .sidebar-nav {
            position: absolute;
        }

        .sidebar-nav #sidebarnav .sidebar-item.selected>.sidebar-link {
            border-radius: 0px 60px 60px 0px;
            color: #fff !important;
            /* background: linear-gradient(to right, #02603b, #0b5839, #134f33, #1c472e, #243f29, #2d3624, #352e1e, #3e2519, #461d14, #4f150f, #570c09, #600404); */
            /* background: linear-gradient(to right, #8971ea, #7f72ea, #7574ea, #6a75e9, #5f76e8); */
            background-color: #fff;
            box-shadow: 0px 7px 12px 0px rgba(95, 118, 232, 0.21);
            opacity: 1;
        }

        label {
            color: brown;
        }

        .modal-body {
            background-color: #fff999;
            color: brown;

        }

        .modal-content {
            background-color: #02603E;
            color: #fff999;
            border-radius: 6px;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
        }

        label.modal-body {
            color: black;
        }

        span {
            color: brown;
        }

        .modal-footer {
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
            -webkit-border-bottom-left-radius: 6px;
            -webkit-border-bottom-right-radius: 6px;
            -moz-border-radius-bottomleft: 6px;
            -moz-border-radius-bottomright: 6px;
        }

        .modal-header {
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
            -webkit-border-top-left-radius: 6px;
            -webkit-border-top-right-radius: 6px;
            -moz-border-radius-topleft: 6px;
            -moz-border-radius-topright: 6px;
        }

        #login_popup{
            background: rgba(0,0,0,0.6);
            width: 100vw;
            height: 100vh;
            position: absolute;
            /* border: 2px solid #000; */
            top: 0;
            display: none;
            /* display:flex; */
            justify-content: center;
            align-items: center;
            z-index: 10;
        }

        #close_popup {
            /* color: #000; */
        }

        .authentication {
            margin: 20px;
            color: black;
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 30px;
            width: 500px;
            height: auto;
        }

        .login-main {
            display: flex;
            justify-content: center;
            align-items: center;
            /* width: 415px; */
            height: 415px;
            margin: 20px;
        }
        .login {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
            width: 400px;
            border: 2px solid black;
            border-radius: 6px;
            padding: 10px;
        }
        .login-img-logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }
        .login-form {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
        }

        .login-form button {
            width: 150px;
            height: 50px;
            margin: auto;
            background-color: green;
            border: none;
            border-radius: 5px;
            font-size: 20px;
            color: white;
        }
        @media screen and (max-width: 991px) {
            .lcv_tracking_table {
                margin-bottom: 25px;
            }
        }
        @media screen and (min-width: 768px) {
            .hide {
                display: block;
            }
        }
        @media screen and (max-width: 767px) {
            .hide {
                display: none;
            }

            /* .logo-icon {
                width: 150px;
                border: 2px solid black;
            } */

            
            /* .left-sidebar {
                left: -260px;
            } */
        } 
    </style>

</head>


