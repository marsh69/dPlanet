<?php
include_once("../../config/config.php");
include_once("../classes/User.php");
include("../classes/Notification.php");

$limit = 7;

$notification = new Notification($con, $_REQUEST['userLoggedIn']);
echo $notification->getNotifications($_REQUEST, $limit);
?>