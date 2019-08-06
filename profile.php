<?php
include("includes/header.php");
include_once ("includes/classes/User.php");
include_once ("includes/classes/Post.php");


$message_obj = new Message($con, $userLoggedIn);
// RewriteRule ^([a-zA-Z0-9_-]+)$ profile.php?profile_username=$1 in htaccess
//it doesn't show but it says profile.php?profile_username='showed in url'
if(isset($_GET['profile_username'])){ 
	$username = $_GET['profile_username'];
	$user_detail_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
	$user_array = mysqli_fetch_array($user_detail_query); // get user_table

	$num_friends = (substr_count($user_array['friend_array'], ',')) - 1; // Reason to give -1 is that there is first , front
}

if(isset($_POST['remove_friend'])){
	$user = new USER($con, $userLoggedIn);
	$user->removefriend($username);
}

if(isset($_POST['add_friend'])){
	$user = new USER($con, $userLoggedIn);
	$user->sendRequest($username);
}


if(isset($_POST['respond_request'])){
	header("Location: requests.php");
}

if(isset($_POST['post_message'])){
	if(isset($_POST['message_body'])){
		$body = mysqli_real_escape_string($con, $_POST['message_body']);
		$date = date("Y-m-d H:i:s");
		$message_obj->sendMessage($username, $body, $date);
	}

	$link = '#profileTabs a[href="#messages_div"]';

	echo "<script>
			$(function(){
				$('". $link ."').tab('show');
			});
		 </script>";
}
?>

	<style type="text/css">
	.wrapper{
		margin-left: 0px;
		padding-left: 0px;
	}	

	</style>
	<div class = "row_flex">
		<div class='profile_left column_flex'>
			<img src="<?= $user_array['profile_pic']; ?>" >

			<div class="profile_info">
				<p><?= "Posts: ".$user_array['num_posts']; ?></p>
				<p><?= "Likes: ".$user_array['num_likes']; ?></p>
				<p><?= "Friends: ".$num_friends; ?></p>
			</div>
			
			<form action="<?= $username; ?>" method="POST">
				<?php 
				$profile_user_obj = new User($con, $username);
				// If user is closed status go to user_closed page
				if($profile_user_obj->isClosed()){
					header("Location: user_closed.php");
				}

				$logged_in_user_obj = new User($con, $userLoggedIn);

				if($userLoggedIn != $username){ // if logged user is not same with username

					if($logged_in_user_obj->isFriend($username)){ // if logged user is friend of target user
						echo '<input type="submit" name="remove_friend" class="danger" value="remove friend"><br/>';
					}

					else if($logged_in_user_obj->didReceiveRequest($username)){
						echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br/>';
					}

					else if($logged_in_user_obj->didSendRequest($username)){
						echo '<input type="submit" name="" class="default" value="Request Sent"><br/>';
					}

					else{
						echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br/>';
					}

				}
				?>
			</form>

			<input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something"> 

			<?php
			if($userLoggedIn != $username){
				echo '<div class="profile_info_bottom">';
					echo $logged_in_user_obj->getMutualFriends($username)." Mutual friends";
				echo '</div>';
			}

			?>
		</div>


		

		<div class="profile_main_column column column_flex">


			<ul class="nav nav-tabs" role='tablist' id='profileTabs'>
			  <li role="presentation" class="active"><a href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Newsfeed</a></li>
			  <li role="presentation"><a href="#about_div" aria-controls="about_div" role="tab" data-toggle="tab">About</a></li>
			  <li role="presentation"><a href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab" onclick='Scrollfunction()'>Messages</a></li>
			</ul>

			<div class="tab-content">
				
				<div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
					<div class='posts_area'></div>
					<img id='loading' src="assets/images/icons/loading.gif">
				</div>

				<div role="tabpanel" class="tab-pane fade" id="about_div">
				</div>

				<div role="tabpanel" class="tab-pane fade" id="messages_div">
					<?php 
					
						echo "<h4>You and <a href='".$username."'>".$profile_user_obj->getFirstAndLastName()."</a></h4><hr>";
						echo "<div class='loaded_messages' id='scroll_messages'>";
							echo $message_obj->getMessages($username);
						echo "</div>";
					?>

					<div class="message_post">
						<form action="" method="POST">
							<textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>
							<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>
						</form>
					</div>
				</div>

				<script>
					function Scrollfunction(){
						var div = document.getElementById("scroll_messages");
						div.scrollTop = div.scrollHeight;
					}
				</script>

			</div>

		</div>
	<div>

	<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Post Something!</h4>
	      </div>

	      <div class="modal-body">
	        <p>This will appear on the user's profile page and also their newsfeed for your friends to see</p>

	        <form class='profile_post' action="" method="POST">
	        	<div class='form-group'>
	        		<textarea class='form-control' name='post_body'></textarea>
	        		<input type='hidden' name='user_from' value="<?= $userLoggedIn ?>">
	        		<input type='hidden' name='user_to' value="<?= $username ?>">
	        	</div>
	        </form>
	      </div>
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" name='post_button' id="submit_profile_post">Post</button>
	      </div>
	    </div>
	  </div>
	</div>

	<script>
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';
		var profileUsername = '<?= $username ?>';

		$(document).ready(function(){

			$('#loading').show();

			//original ajax request for loading first posts
			$.ajax({
				url: "includes/handlers/ajax_load_profile_posts.php",
				type: "POST",
				data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" +profileUsername, // send page and userLoggedIn
				cache: false, //cache에 저장x

				success: function(data){ //data = return되는 값
					$('#loading').hide();
					$('.posts_area').html(data);
				}
			});

			$(window).scroll(function(){
				var height = $('.posts_area').height(); // div containing posts
				var scroll_top = $(this).scrollTop();
				var page = $('.posts_area').find('.nextPage').val();
				var noMorePosts = $('.posts_area').find('.noMorePosts').val();

				if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false'){
					$('#loading').show();

					var ajaxReq = $.ajax({
						url: "includes/handlers/ajax_load_profile_posts.php",
						type: "POST",
						data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" +profileUsername,
						cache: false,

						success: function(data){
							//Removes current .nextpage when scroll is bottom
							$('.posts_area').find('.nextPage').remove(); 
							//Removes current .no more posts when scroll is bottom
							$('.posts_area').find('.noMorePosts').remove(); 

							$('#loading').hide();
							$('.posts_area').append(data);
						}
					});
				} //End if

				return false;
			
			}); // End window.scroll(function()

		});

	</script>

	</div> <!-- div for wrapper -->
</body>
</html>

