<?php 

// include('head.php'); 
$db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");

$response=Array();

$approved = $db->prepare("SELECT * 
    FROM notification 
    WHERE `Notification_Id` IN (SELECT MAX(`Notification_Id`) FROM notification GROUP BY `Notification_DBS`) 
    and  `status` = 'Approved'
    order by Notification_Id desc");
$approved->execute();

$pending = $db->prepare("SELECT * 
    FROM notification 
    WHERE `Notification_Id` IN (SELECT MAX(`Notification_Id`) FROM notification GROUP BY `Notification_DBS`) 
    and  `status` = 'Pending'
    order by Notification_Id desc");
$pending->execute();

$stmt = $db->prepare("SELECT * 
    FROM notification 
    WHERE `Notification_Id` IN (SELECT MAX(`Notification_Id`) FROM notification GROUP BY `Notification_DBS`)
    order by Notification_Id desc");
$stmt->execute();

$response['approved']=$approved->rowCount();

$response['pending']=$pending->rowCount();
$response['total']=$stmt->rowCount();

// print_r($response);
$temp='';

if(isset($_GET['type']) && isset($_GET['show'])) {
    switch($_GET['type']) {
        case 'approved':
            $temp = $approved;
            break;
        case 'pending' :
            $temp = $pending;
            break;
        case 'total' :
            $temp=$stmt;
            break;
        default:
            $temp=$stmt;
            break;
            
    }
    $i = 1;
    while ($row = $temp->fetch()) {
        $status = $row["status"];
        if ($status == 'Pending') {
    ?>
    <tr class="alert alert-danger alert-dismissible ">
        <td>
            <?php echo $i; ?>
        </td>
        <td>
            <?php echo $row["status"]; ?>
        </td>
        <td>
            <?php echo $row["Notification_Date"]; ?>
        </td>
        <td>
            <?php echo $row["Notification_Time"]; ?>
        </td>

        <td>
            <?php echo $row["Notification_LCV"]; ?>
        </td>

        <td>
            <?php echo $row["Notification_MGS"]; ?>
        </td>

        <td>
            <?php echo $row["Notification_DBS"]; ?>
        </td>
        <td>
            <?php echo $row["Notification_Message"]; ?>
        </td>
    </tr>
<?php } else                                           
        if ($status == 'Approved') {
    echo "<tr style='background-color:powderblue;'>"; ?>
    <tr class="alert alert-success alert-dismissible ">
        <td>
            <?php echo $i; ?>
        </td>
        <td>
            <?php echo $row["status"]; ?>
        </td>
        <td>
            <?php echo $row["Notification_Date"]; ?>
        </td>
        <td>
            <?php echo $row["Notification_Time"]; ?>
        </td>

        <td>
            <?php echo $row["Notification_LCV"]; ?>
        </td>

        <td>
            <?php echo $row["Notification_MGS"]; ?>
        </td>

        <td>
            <?php echo $row["Notification_DBS"]; ?>
        </td>
        <td>
            <?php echo $row["Notification_Message"]; ?>
        </td>

    </tr>
    <?php   }
        $i++;
    }
    ?>

<?php } 
else {
    echo json_encode($response);
}


?>
