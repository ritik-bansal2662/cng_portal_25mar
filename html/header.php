<?php

include '../CNG_API/conn.php';

// session_start();
$notification_query = "SELECT * 
    FROM notification 
    WHERE `Notification_Id` IN (SELECT MAX(`Notification_Id`) FROM notification GROUP BY `Notification_DBS`)";
$result = mysqli_query($conn, $notification_query);
$notifications_count = mysqli_num_rows($result);
if($notifications_count == 0) {
    $notifications_count = '';
}

?>

<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper" data-theme="dar" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md">
            <div class="navbar-header">
                <!-- This is for the sidebar toggle which is visible on mobile only -->
                <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i id='menu' class="ti-menu ti-close"></i></a>
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-brand">
                    <!-- Logo icon -->
                    <a href="index.html">
                        <b class="logo-icon">
                            <!-- Dark Logo icon -->

                            <!-- <img src="../assets/images/assetplus.jpeg"  alt="homepage" class="dark-logo" /> -->
                            <!-- Light Logo icon -->
                            <!-- <img src="../assets/images/assetplus.jpeg"  alt="homepage" class="light-logo" /> -->
                            <img src="../assets/images/apc.png"  alt="APC Logo" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->

                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Toggle which is visible on mobile only -->
                <!-- ============================================================== -->
                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav float-left mr-3 ml-3 pl-1">
                    <!-- Notification -->
                    <li class="nav-item dropdown text-white">
                        <a href="notification.php" class="nav-link dropdown-toggle pl-md-3 position-relative" href="javascript:void(0)" id="bell" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i data-feather="bell" class="svg-icon" style="color: white;"></i></span>
                            <span class="badge badge-primary notify-no rounded-circle">
                                <?php 
                                    echo $notifications_count;
                                ?>
                            </span>
                        </a>

                    </li>
                    <!-- End Notification -->
                    <!-- ============================================================== -->
                    <!-- create new -->
                    <!-- ============================================================== -->
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="settings" class="svg-icon"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li> -->
                    <!-- <li class="nav-item d-none d-md-block">
                        <a class="nav-link" href="javascript:void(0)">
                            <div class="customize-input">
                                <select class="custom-select form-control bg-white custom-radius custom-shadow border-0">
                                    <option selected>EN</option>
                                    <option value="1">AB</option>
                                    <option value="2">AK</option>
                                    <option value="3">BE</option>
                                </select>
                            </div>
                        </a>
                    </li> -->
                </ul>
                <h3 text-align="center" class=" page-title text-truncate  font-weight-medium mb-1">CNG Stock Movement and LCV Scheduling Dashboard </h3>

                <!-- ============================================================== -->
                <!-- Right side toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav float-right">
                    <!-- ============================================================== -->
                    <!-- Search -->
                    <!-- ============================================================== -->
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link" href="javascript:void(0)">
                            <form>
                                <div class="customize-input">
                                    <input class="form-control custom-shadow custom-radius border-0 bg-white" type="search" placeholder="Search" aria-label="Search">
                                    <i class="form-control-icon" data-feather="search"></i>
                                </div>
                            </form>
                        </a>
                    </li>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <!-- <img src="../assets/images/users/profile-pic.jpg" alt="user" class="rounded-circle" width="40"> -->
                            <span class="ml-2 d-none d-lg-inline-block text-white"><span>Hello,</span> <span class="text-dark"></span> <i data-feather="chevron-down" class="svg-icon"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <a class="dropdown-item" href="javascript:void(0)"><i data-feather="user" class="svg-icon mr-2 ml-1"></i>
                                My Profile</a>
                            <!-- <a class="dropdown-item" href="javascript:void(0)"><i data-feather="credit-card" class="svg-icon mr-2 ml-1"></i>
                                My Balance</a>
                            <a class="dropdown-item" href="javascript:void(0)"><i data-feather="mail" class="svg-icon mr-2 ml-1"></i>
                                Inbox</a>
                            <div class="dropdown-divider"></div> -->
                            <a class="dropdown-item" href="javascript:void(0)"><i data-feather="settings" class="svg-icon mr-2 ml-1"></i>
                                Account Setting</a>
                            <div class="dropdown-divider"></div>
                            <!-- <a class="dropdown-item" href="javascript:void(0)"><i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                                Logout</a> -->
                            <a class="dropdown-item" href="logout.php"><i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                                Logout</a>
                            <div class="dropdown-divider"></div>
                            <!-- <div class="pl-4 p-3"><a href="javascript:void(0)" class="btn btn-sm btn-info">View
                                    Profile</a></div> -->
                        </div>
                    </li>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                </ul>
            </div>
        </nav>
    </header>
    <!-- giving extra div to occupy space and the content will not go behing the topbar-->
    <div class='top-temp'></div> 
</div>


    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <aside class="left-sidebar" id='left-sidebar'>
    
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav ">
                <ul id="sidebarnav">
                    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="index.php" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a></li>
                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu h3">LCV Tracking</span></li>

                    <li class="sidebar-item"> <a class="sidebar-link" href="#" aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span class="hide-menu">At DBS
                            </span></a>
                    </li>
                    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="#" aria-expanded="false"><i data-feather="message-square" class="feather-icon"></i><span class="hide-menu">In Transit</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="#" aria-expanded="false"><i data-feather="calendar" class="feather-icon"></i><span class="hide-menu">In Halt</span></a></li>

                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu h3">Modules</span></li>
                    <?php 
                    // print_r($_SESSION); 
                    if(isset($_SESSION['admin']) && $_SESSION['admin'] == true && $_SESSION['manager'] == false) {
                        // echo "admin";
                    ?>

                    <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="registration.php" aria-expanded="false"><span class="hide-menu">Registration & Admin </span></a>
                        <ul aria-expanded="false" class="collapse  first-level base-level-line">
                            <li class="sidebar-item"><a href="registration.php" class="sidebar-link"><span class="hide-menu"> Registration
                                    </span></a>
                            </li>
                            </span></a>
                            <li class="sidebar-item"><a href="editreg.php" class="sidebar-link"><span class="hide-menu"> Edit/View Registration
                                    </span></a>
                            </li>
                            <li class="sidebar-item"><a href="admin.php" class="sidebar-link"><span class="hide-menu">
                                        Role Mapping
                                    </span></a>
                            </li>
                            <li class="sidebar-item"><a href="view_org_emp_role_mapping.php" class="sidebar-link"><span class="hide-menu">
                                        View Emp Role Mapping
                                    </span></a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- <li class="sidebar-item">

                        <a href="registration.php" class="sidebar-link"><span class="hide-menu">Registration </span></a>
                    </li> -->
                    <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="register-station.php" aria-expanded="false"><span class="hide-menu">Master Tab</span></a>
                        <ul aria-expanded="false" class="collapse  first-level base-level-line">
                            <li class="sidebar-item"><a href="#" class="sidebar-link"><span class="hide-menu"> CGS/MGS/DBS
                                </span></a>
                                <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                    <li class="sidebar-item"><a href="register-station.php" class="sidebar-link"><span class="hide-menu">Register Station</span></a>
                                    </li>                                    
                                    <li class="sidebar-item"><a href="edit-station.php" class="sidebar-link"><span class="hide-menu"> Edit Station
                                        </span></a>
                                    </li>
                                    <li class="sidebar-item"><a href="view_station.php" class="sidebar-link"><span class="hide-menu"> View Station
                                        </span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item"><a href="#" class="sidebar-link"><span class="hide-menu"> LCV</span></a>
                                <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                    <li class="sidebar-item"><a href="register-lcv.php" class="sidebar-link"><span class="hide-menu">Register LCV</span></a>
                                    </li>                                    
                                    <li class="sidebar-item"><a href="edit-lcv.php" class="sidebar-link"><span class="hide-menu">Edit LCV</span></a>
                                    </li>
                                    <li class="sidebar-item"><a href="view_lcv.php" class="sidebar-link"><span class="hide-menu">View LCV</span></a>
                                    </li>
                                    <li class="sidebar-item"><a href="allocate_lcv.php" class="sidebar-link"><span class="hide-menu">Allocate LCV to MGS</span></a>
                                    </li>
                                    <li class="sidebar-item"><a href="lcv_status.php" class="sidebar-link"><span class="hide-menu">LCV Status</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="dbs_requests.php">
                            <span class="hide-menu">DBS requests</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="scheduling_empty_lcv.php">
                            <span class="hide-menu">Scheduling Empty LCV</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="manual_scheduling.php">
                            <span class="hide-menu">Manual Re-Scheduling</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="mgs_dbs_route.php">
                            <span class="hide-menu">Route</span>
                        </a>
                    </li>
                    <!-- <li class="sidebar-item"><a href="form-input-grid.html" class="sidebar-link"><span class="hide-menu"> Gas Transaction
                            </span></a>
                    </li> -->
                    <!-- <li class="sidebar-item"><a href="molar-mass.php" class="sidebar-link"><span class="hide-menu">Molar Mass/Gas Density
                            </span></a>
                    </li> -->
                    </li>
                    <li class="sidebar-item">

                        <a href="#" class="sidebar-link"><span class="hide-menu">Tracking of LCV </span></a>
                    </li>
                    <li class="sidebar-item"><a href="#" class="sidebar-link"><span class="hide-menu"> Analytical Reports</span></a>
                        <ul aria-expanded="false" class="collapse  first-level base-level-line">
                            <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/luage_note_approve.php" target="_blank" class="sidebar-link"><span class="hide-menu"> -->
                            <li class="sidebar-item"><a href="luage_note_approve.php" target="_blank" class="sidebar-link"><span class="hide-menu">

                                 Notification Lag
                                </span></a>
                            </li>
                            <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/luag_lcv_turnaround.php" target="_blank" class="sidebar-link"><span class="hide-menu"> -->
                            <li class="sidebar-item"><a href="luag_lcv_turnaround.php" target="_blank" class="sidebar-link"><span class="hide-menu">

                                 Vehicle Turnaround Time
                                </span></a>
                            </li>
                            <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/Analytical_Report.php" target="_blank" class="sidebar-link"><span class="hide-menu"> -->
                            <li class="sidebar-item"><a href="Analytical_Report.php" target="_blank" class="sidebar-link"><span class="hide-menu">
                                 Total Gas Loss between Stations</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item"><a href="#" class="sidebar-link"><span class="hide-menu"> Gas Reconciliation</span></a>
                        <ul aria-expanded="false" class="collapse  first-level base-level-line">
                            <li class="sidebar-item"><a href="#" class="sidebar-link"><span class="hide-menu">
                                 Organzational Level
                                </span></a>
                                <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                    <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/cng_dbs_cascade_info.php" target="_blank" class="sidebar-link"><span class="hide-menu">Cascade Report</span></a> -->
                                    <li class="sidebar-item"><a href="cng_dbs_cascade_info.php" target="_blank" class="sidebar-link"><span class="hide-menu">Cascade Report</span></a>
                                    </li>
                                    <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/luag_org.php" target="_blank" class="sidebar-link"><span class="hide-menu"> Summary Report -->
                                    <li class="sidebar-item"><a href="luag_org.php" target="_blank" class="sidebar-link"><span class="hide-menu"> Summary Report
                                        </span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item"><a href="#" class="sidebar-link"><span class="hide-menu">
                                 MGS Level
                                </span></a>
                                <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                    <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/luage_msg.php" target="_blank" class="sidebar-link"><span class="hide-menu">Detailed Report</span></a> -->
                                    <li class="sidebar-item"><a href="luage_msg.php" target="_blank" class="sidebar-link"><span class="hide-menu">Detailed Report</span></a>
                                    </li>
                                    <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/summary_luag_mgs.php" target="_blank" class="sidebar-link"><span class="hide-menu"> Summary Report -->
                                    <li class="sidebar-item"><a href="summary_luag_mgs.php" target="_blank" class="sidebar-link"><span class="hide-menu"> Summary Report
                                        </span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item"><a href="#" class="sidebar-link"><span class="hide-menu">
                                 DBS Level</span></a>
                                <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                    <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/luage_dbs.php" target="_blank" class="sidebar-link"><span class="hide-menu">Detailed Report</span></a> -->
                                    <li class="sidebar-item"><a href="luage_dbs.php" target="_blank" class="sidebar-link"><span class="hide-menu">Detailed Report</span></a>
                                    </li>
                                    <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/summary_luag_dbs.php" target="_blank" class="sidebar-link" ><span class="hide-menu"> Summary Report -->
                                    <li class="sidebar-item"><a href="summary_luag_dbs.php" target="_blank" class="sidebar-link" ><span class="hide-menu"> Summary Report
                                        </span></a>
                                    </li>
                                </ul>
                            </li>
                            <!-- <li class="sidebar-item"><a href="http://182.77.57.154/LUAG/Report/luag_lcv.php" target="_blank" class="sidebar-link"><span class="hide-menu"> -->
                            <li class="sidebar-item"><a href="luag_lcv.php" target="_blank" class="sidebar-link"><span class="hide-menu">
                                 Transportation Level</span></a>
                            </li>
                        </ul>
                    </li>
                    <?php
                    // if(!($_SESSION['mgs_id'] =='NA')) { ?>
                    <li class="sidebar-item"><a href="scheduling.php" class="sidebar-link"><span class="hide-menu">
                                Scheduling
                            </span></a>
                    </li>
                    <?php //}?>
                    <li class="sidebar-item"><a href="notification.php"  class="sidebar-link"><span class="hide-menu">
                                Notification
                            </span></a>
                    </li>

                    </li>


                    <li class="list-divider"></li>
                    <!-- <li class="nav-small-cap"><span class="hide-menu">Authentication</span></li>

                    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" id='login_sidebar_btn' href="#" aria-expanded="false"><i data-feather="lock" class="feather-icon"></i><span class="hide-menu">Login
                            </span></a>
                    </li>
                    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="#" aria-expanded="false"><i data-feather="lock" class="feather-icon"></i><span class="hide-menu">Register
                            </span></a>
                    </li> -->


                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu">Extra</span></li>
                    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="#" aria-expanded="false"><i data-feather="edit-3" class="feather-icon"></i><span class="hide-menu">Documentation</span></a></li>
                    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="logout.php" aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span class="hide-menu">Logout</span></a></li>
                </ul>
            </nav>
            <!-- End Sidebar navigation -->
            
        </div>
        <!-- End Sidebar scroll-->
        
    </aside>
    
    <!-- <div id="login_popup">
        <div class='authentication' style="background-color: #fff999;">
            <div class='login-main' >
                <div class='login'>
                    <div>
                        <img src='../assets/images/assetplus.jpeg' class='login-img-logo' alt='APC logo' />
                        
                        <form id='login_form' class='login-form'>
                            <input type="text" required class='input' name='username' placeholder='Username' >
                            <input type='password' required class='input' name='password' placeholder='Password' >
                            <div class='d-flex justify-content-around'>
                                
                                <button type='button' id='login_submit'>Login</button>
                                <button type='button' id='close_popup' class='btn btn-warning btn-lg active'>Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


