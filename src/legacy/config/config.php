<?php
ob_start(); // Turns on output buffering and store under information inside
session_start();

$timezone = date_default_timezone_set('asia/kolkata'); //set default time zone

$con = mysqli_connect(
    getenv('DATABASE_HOSTNAME'),
    getenv('DATABASE_USER'),
    getenv('DATABASE_PASSWORD'),
    getenv('DATABASE_NAME')
);
mysqli_query($con,"set session character_set_connection=utf8;"); 
mysqli_query($con,"set session character_set_server=utf8;"); 
mysqli_query($con,"set session character_set_results=utf8;"); 
mysqli_query($con,"set session character_set_client=utf8;"); 
mysqli_query($con,"set names utf8;");


if(mysqli_connect_errno()){
	echo "Failed to connect: " . mysqli_connect_errno();
}
