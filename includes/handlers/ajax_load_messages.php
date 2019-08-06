<?php
include_once("../../config/config.php");
include_once("../classes/User.php");
include("../classes/Message.php");

$limit = 7;

$message = new Message($con, $_REQUEST['userLoggedIn']);
echo $message->getConvosDropdown($_REQUEST, $limit);
?>