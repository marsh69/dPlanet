<?php
include_once("../../config/config.php");
include_once("../classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query); // if reece kenny [0] = reece, [1] = kenny


//if query contains an underscore, assume user is searching for usernames
if(strpos($query, "_") !== false){
	// LIKE => if reec% -> it finds reece, reecsdfsdfsdf
	$usersReturned = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
}
//If there are two words, assumes they are first and last names respectively
else if(count($names) == 2){
	$usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[1]%') AND user_closed='no' LIMIT 8");
}
//If query has one word only, search first names and last names
else{
	$usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' OR last_name LIKE '%$names[0]%') AND user_closed='no' LIMIT 8");
}

if($query != ""){
	while($row = mysqli_fetch_array($usersReturned)){
		$user = new User($con, $userLoggedIn);
		if ($row['username'] != $userLoggedIn){
			$mutual_friends = $user->getMutualFriends($row['username'])." friends in common";
		}
		else{
			$mutual_friends = '';
		}

		echo "<div class='resultDisplay'>
			    <a href='".$row['username']."' style='color: #1485BD'>
				    <div class='liveSearchProfilePic'>
				   		<img src='".$row['profile_pic']."'>
				 	</div>

				 	<div class='liveSearchText'>
				 		".$row['first_name']." ".$row['last_name']."
				 		<p style='margin: 0;'>".$row['username']."</p>
				 		<p id = 'grey'>".$mutual_friends."</p>
				 	</div>
			 	</a>
			 </div>";
	}
}
?>