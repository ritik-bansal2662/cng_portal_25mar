<?php

session_start();
unset($_SESSION['loggedin']);
unset($_SESSION['user_role']);
unset($_SESSION['admin']);
unset($_SESSION['manager']);
unset($_SESSION['mgs_id']);
unset($_SESSION['dbs_id']);

header("location: login.php");
exit();



?>