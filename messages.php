<?php
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['u'])){ // if specified someone who wants to text
	$user_to = $_GET['u']; // $user_to is that user
} else{ // if didn't specify anyone show user texted recently
	$user_to = $message_obj->getMostRecentUser(); //user_to is recent user
	if($user_to == false){ // if no one texted before show new
		$user_to = 'new'; //$user_to is new
	}
}

if($user_to != "new"){ //if $user_to is not new, show user
	$user_to_obj = new User($con, $user_to);
}

if(isset($_POST['post_message'])){
	if(isset($_POST['message_body'])){
		$body = mysqli_real_escape_string($con, $_POST['message_body']);
		$date = date("Y-m-d H:i:s");
		$message_obj->sendMessage($user_to, $body, $date);
	}
}
?>

<!-- user profile -->
<div class="user_details column">
	<a href="<?= $userLoggedIn ?>"><img src="<?= $user['profile_pic']; ?>"></a>

	<div class="user_details_left_right">
		<a href="<?= $userLoggedIn ?>">
			<?= $user['first_name'] . " " . $user['last_name'];	?>
		</a>
		<br/>
		<?php 
		echo "Posts: ".$user['num_posts']. "<br/>";
		echo "Likes: ".$user['num_likes'];
		 ?>
	</div>
</div>


<!-- if $user_to is not new show You and the other person -->
<div class="main_column column" id="main_column">
	<?php 
	if($user_to != "new"){
		echo "<h4>You and <a href='$user_to'>".$user_to_obj->getFirstAndLastName()."</a></h4><hr>";
		echo "<div class='loaded_messages' id='scroll_messages'>";
			echo $message_obj->getMessages($user_to);
		echo "</div>";
	}
	else{
		echo "<h4>New Message</h4>";	

	}
	?>

	<div class="message_post">
		<form action="" method="POST">
			<?php
			if($user_to == 'new'){
				echo "Select the friend you would like to message <br/><br/>"; ?>
				To:<input type='text' onkeyup='getUsers(this.value, "<?php echo $userLoggedIn;?>")' name='q' placeholder='Name' autocomplete='off' id='search_text_input'>
				<?php
				echo "<div class='results'></div>";
			}
			else{
				echo "<textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>";
				echo "<input type='submit' name='post message' class='info' id='message_submit' value='Send'>";
			}
			?>
		</form>
	</div>
</div>

<script>
	if(document.getElementById("scroll_messages")){
		var div = document.getElementById("scroll_messages");
		div.scrollTop = div.scrollHeight;
	}
</script>

<!-- recent Massage -->
<div class="user_details column" id="conversations">
	<h4>Conversations</h4>

	<div class="loaded_conversations">
		<?= $message_obj->getConvos()?>
	</div>
	<br>
	<a href="messages.php?u=new">New Message</a>
</div>

<!-- div for wrap -->
</div>


