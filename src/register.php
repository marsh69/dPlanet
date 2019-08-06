<?php
require_once 'config/config.php';
require_once 'includes/form_handler/login_handler.php';
require_once 'includes/form_handler/register_handler.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to developersPlanet</title>
	
	<script src='assets/js/jquery-3.3.1.min.js'></script>
	<script src='assets/js/register.js'></script>
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css?sd">
	<link rel="stylesheet" type="text/css" href="assets/css/stars.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
<link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
<div id='stars'></div>
<div id='stars2'></div>
<div id='stars3'></div>
<div id='title'>
  <span>	<?php 
	if(isset($_POST['register_button'])){
		echo '
		<script>

		$(document).ready(function(){
			$("#first").hide();
			$("#second").show();
		});

		</script>

		';
	}
	?>	

		

	<div class='wrapper'>

		<div class='login_box'>
		<div class="login_header">
			<img src="assets/images/backgrounds/blue.png" style = "width: 100%;"><br>
			Login or sign up below!	
	
		</div>
			<!-- <div id = "avatar_div">
				<iframe src="https://getavataaars.com/" width="609px" height="350px"></iframe>
				<h1 style = "    background-color: #21242e;color: #ffffff;font-size: 23px;border-radius: 9px;">Download the avatar first before proceeding!</h1>
				<a href='#' id='signup_avatar' class="signup_avatar">Proceed to Signup page!<br/></a>
			 </div> -->


		<div id="first">
			<form action="register.php" method="POST">
				<input type="email" name="log_email" placeholder="Email Address" value="<?php 
				if(isset($_SESSION['log_email'])){
					echo $_SESSION['log_email'];
				}
				?>" required />
				<br/>
				<input type="password" name="log_password" placeholder="Password">
				<br/>
				<input type='submit' name='login_button' value="Login">
				<br/>
				<a href='#' id='signup' class="signup">Need and account? Register here<br/></a>
				<?php if(in_array("Email or password was incorrect<br/>", $error_array)) echo "Email or password was incorrect<br/>"; ?>
			</form>
		</div>
			
		<div id='second'>
			<form action="register.php" method='POST' enctype="multipart/form-data">
				<input type='text' name='reg_fname' placeholder="First Name" value="<?php 
				if(isset($_SESSION['reg_fname'])){
					echo $_SESSION['reg_fname'];
				}
				?>" required />
				<?php if(in_array("Your first name must be between 2 and 25 characters<br/>", $error_array)){echo"<br>Your first name must be between 2 and 25 characters";}?>
				<br>
				<input type='text' name='reg_lname' placeholder="Last Name" value="<?php 
				if(isset($_SESSION['reg_lname'])){
					echo $_SESSION['reg_lname'];
				}
				?>"required />
				<br>
				<?php if(in_array("Your last name must be between 2 and 25 characters<br/>", $error_array)){echo"Your last name must be between 2 and 25 characters<br/>";}?>
				
				<input type='email' name='reg_email' placeholder="Email" value="<?php 
				if(isset($_SESSION['reg_email'])){
					echo $_SESSION['reg_email'];
				}
				?>"required />
				<br>
				<input type='email' name='reg_email2' placeholder="Confirm Email" value="<?php 
				if(isset($_SESSION['reg_email2'])){
					echo $_SESSION['reg_email2'];
				}
				?>"required />
				<br>
				<?php if(in_array("Invalid Email format<br/>", $error_array)){echo"Invalid Email format<br/>";}
				else if(in_array("Email already in use<br/>", $error_array)){echo"Email already in use<br/>";}
				else if(in_array("Email do not match<br/>", $error_array)){echo"Email do not match<br/>";}?>


				<input type='password' name='reg_password' placeholder="Password" required />
				<br>
				<input type='password' name='reg_password2' placeholder="Confirm Password" required />
				<br>
				<?php if(in_array("Your passwords do not match<br/>", $error_array)){echo"Your passwords do not match<br/>";}
				else if(in_array("Your password only contain english characters or numbers<br/>", $error_array)){echo"Your password only contain english characters or numbers<br/>";}
				else if(in_array("Your password must be between 5 and 30 characters<br/>", $error_array)){echo"Your password must be between 5 and 30 characters<br/>";}?>

				<div class="profile_box">
					<!-- <a href='#' id='avatar_id' class="avatar_class">Create Avatar<br></a> -->
					<label for="profilePicture">Upload profile picture: </label>
					<input type="file" name="profilePicture" placeholder="Please Put image" />
					
				</div>
				<br>
				<?php if(in_array("Sorry your file is too large<br/>", $error_array)){echo"Sorry your file is too large<br/>";}
				else if(in_array("Sorry, only jpeg, jpg and png files are allowed.<br/>", $error_array)){echo"Sorry, only jpeg, jpg and png files are allowed.<br/>";}
				else if(in_array("Sorry, We failed to move file to folder<br/>", $error_array)){echo"Sorry, We failed to move file to folder<br/>";}?>
				<input type='submit' name='register_button' value="Register">
				<br>
				<?php if(in_array("<span style='color: #14C800;'>You're all set! Goahead and login!</span><br/>", $error_array)){echo"<span style='color: #14C800;'>Welcome to the our planet Go ahead and signin!!</span><br/>";} ?>
				<a href='#' id='signin' class="signin">Already have an account? Sign in here!<br/></a>
				
					

			</form>
		</div>
		</div>
	</div>
	</div>
</div><!-- End of row -->
	</span>
	</div>
</body>
</html>

