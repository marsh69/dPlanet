<?php
require_once ("../form_handler/login_handler.php");
session_start();
session_destroy();
header("Location: ../../register.php");
?>