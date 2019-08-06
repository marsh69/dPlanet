<?php
include_once("../../config/config.php");
include_once("../classes/User.php");
include_once("../classes/Post.php");
include_once("../classes/Notification.php");


// get user detail of post
$post_id = $_REQUEST['postid'];
$userLoggedIn = $_REQUEST['userLoggedIn'];
$getpost = mysqli_query($con, "SELECT * FROM likes WHERE post_id='$post_id' AND username='$userLoggedIn'");
$user_liked = mysqli_query($con, "SELECT * FROM posts WHERE id='$post_id'");
$user_liked = mysqli_fetch_array($user_liked);
$user_liked = $user_liked['added_by'];
$row = mysqli_num_rows($getpost);

if($row){
	$delete_user = mysqli_query($con, "DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
	$update_like = mysqli_query($con, "UPDATE users SET num_likes=num_likes - 1 WHERE username='$user_liked'");
}
else{
	$insert_user = mysqli_query($con, "INSERT INTO likes VALUES(NULL, '$userLoggedIn', '$post_id')");
	if($user_liked != $userLoggedIn){
		$notification = new Notification($con, $userLoggedIn);
		$notification->insertNotification($post_id, $user_liked, "like");
	}
	$update_like = mysqli_query($con, "UPDATE users SET num_likes=num_likes + 1 WHERE username='$user_liked'");
}

$get_post_like = mysqli_query($con, "SELECT * FROM likes WHERE post_id='$post_id'");
$num_post_like = mysqli_num_rows($get_post_like);

echo $num_post_like;
?>