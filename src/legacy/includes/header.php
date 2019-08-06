<?php
require_once 'config/config.php';
include_once('includes/classes/User.php');
include_once('includes/classes/Post.php');
include_once('includes/classes/Message.php');
include_once('includes/classes/Notification.php');

if (isset($_SESSION['username'])){
	// It is created when logged in(Check includes/form_handler/login_handler.php)
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
}
else{
	header("Location: register.php");
}
?>

<html>
<head>
	<title>Developers Planet</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src='assets/js/jquery-3.3.1.min.js'></script>
	<script src='assets/js/bootstrap.js'></script>
	<script src='assets/js/bootbox.min.js'></script>
	<script src='assets/js/demo.js'></script>	

	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css?sd">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

	<div class="top_bar">
		<div id="logo">
			<a href="index.php"><img src="assets/images/backgrounds/logo.png"><h1>&ltdevelopersPlanet/&gt</h1></a>
			
			
			</div>

		<div class="search">
			
			<form action="search.php" method="GET" name="search_form">
				<input type="text" onkeyup="getLiveSearchUsers(this.value, '<?= $userLoggedIn ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">
				
				<div class="button_holder">
					<img src="assets/images/icons/magnifying_glass.png">
				</div>

			</form>

			<div class='search_results'>
				
			</div>

			<div class='search_results_footer_empty'>
				
			</div>

		</div>

		<nav>
		<?php
			//Unread messages
			$messages = new Message($con, $userLoggedIn);
			$num_messages = $messages->getUnreadNumber();

			//Unread Notifications
			$notifications = new Notification($con, $userLoggedIn);
			$num_notifications = $notifications->getUnreadNumber();

			//Unread Notifications
			$user_obj = new User($con, $userLoggedIn);
			$num_requests = $user_obj->getNumberOfFriendRequests();
		?>
		<ul class = "snip1198">
		
		<img class = "nav_profile"src="<?=$user['profile_pic']?>"><a href="<?= $userLoggedIn ?>">
			<?= $user['first_name']; ?>
		</a>
		<li><a href='index.php'>
			<i class="fas fa-home fa-lg"></i>&nbspHome
		</a></li>
		<li><a href="javascript:void(0);" onclick="getDropdownData('<?= $userLoggedIn; ?>', 'message')">
			<i class="fas fa-envelope fa-lg"></i>&nbspMessages
			<?php
			if($num_messages > 0){
			echo '<span class="notification_badge" id="unread_message">'.$num_messages.'</span>';
			}
			?>
		</a></li>
			<li><a href="javascript:void(0);" onclick="getDropdownData('<?= $userLoggedIn; ?>', 'notification')">
				<i class="fas fa-bell fa-lg"></i>&nbspNotifications
				<?php
				if($num_notifications > 0){
				echo '<span class="notification_badge" id="unread_notification">'.$num_notifications.'</span>';
				}
				?>
			</a><li>
			<li><a href='requests.php'>
				<i class="fas fa-users fa-lg"></i>&nbspFriend Requests
				<?php
				if($num_requests > 0){
				echo '<span class="notification_badge" id="unread_requests">'.$num_requests.'</span>';
				}
				?>
			</a></li>
			<li><a href='settings.php'>
				<i class="fas fa-cog fa-lg"></i>&nbspSettings
			</a></li>
			<li><a href='includes/handlers/logout.php'>
				<i class="fas fa-sign-out-alt fa-lg"></i>&nbspLogout
			</a></li>
			</ul>
		</nav>

		<div class="dropdown_data_window" style='height:0px; border:none;'></div>
			<input type='hidden' id="dropdown_data_type" value="">
		
	</div>


	<script>
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';

		$(document).ready(function(){

			$('.dropdown_data_window').scroll(function(){
				var inner_height = $('.dropdown_data_window').innerHeight(); // div containing data
				var scroll_top = $('.dropdown_data_window').scrollTop();
				//data of what you come next page
				var page = $('.dropdown_data_window').find('.nextPageDropDownData').val();
				var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

				if ((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false'){

					var pageName; // Holds name of posts to send ajax request to
					var type = $('#dropdown_data_type').val();

					if(type == 'notification'){
						pageName= "ajax_load_notifications.php"; //Not use now, just declare
					}
					else if(type=='message'){
						pageName = "ajax_load_messages.php";
					}



					var ajaxReq = $.ajax({
						url: "includes/handlers/" + pageName,
						type: "POST",
						data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
						cache: false,

						success: function(data){
							//Removes current .nextpage when scroll is bottom
							$('.dropdown_data_window').find('.nextPageDropDownData').remove(); 
							//Removes current .no more posts when scroll is bottom
							$('.dropdown_data_window').find('.noMoreDropdownData').remove(); 

							$('.dropdown_data_window').append(data);
						}
					});
				} //End if

				return false;
			
			}); // End window.scroll(function()

		});

	</script>

	<div class='wrapper'>