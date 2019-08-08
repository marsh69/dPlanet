<?php
include_once("../../config/config.php");
include_once("../classes/User.php");
include_once("../classes/Post.php");

$limit = 10; //Number of posts to be loaded per call

$posts = new Post($con, $_REQUEST['userLoggedIn']); // $_REQUEST = 내가 보낸 데이터
$posts->loadProfilePosts($_REQUEST, $limit);
?>