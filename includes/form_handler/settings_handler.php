<?php
	//change name and email
	if(isset($_POST['update_details'])){

		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];

		$email_check = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
		$row = mysqli_fetch_array($email_check);
		$matched_user = $row['username'];

		if($matched_user == "" || $matched_user == $userLoggedIn){
			$message = "Details update!<br><br>";

			$query = mysqli_query($con, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email' WHERE username='$userLoggedIn'");
		}
		else{
			$message = "That email is already in use<br><br>";
		}

	}
	else{
		$message = "";
	}

	//change password
	if(isset($_POST['update_password'])){
		$old_password = strip_tags($_POST['old_password']);
		$new_password_1 = strip_tags($_POST['new_password_1']);
		$new_password_2 = strip_tags($_POST['new_password_2']);

		$password_query = mysqli_query($con, "SELECT password FROM users WHERE username='$userLoggedIn'");
		$row = mysqli_fetch_array($password_query);
		$db_password = $row['password'];

		if(md5($old_password) == $db_password){

			if($new_password_1 == $new_password_2){


				if(strlen($new_password_1) <= 4){
					$password_message = "Sorry, your password must be greater than 4 characters<br><br>";
				}
				else{
					$new_password_md5 = md5($new_password_1);
					$password_query = mysqli_query($con, "UPDATE users SET password='$new_password_md5' WHERE username = '$userLoggedIn'");
					$password_message = "Password has been changed!<br><br>";
				}

			}

			else{
				$password_message = "Your two new passwords need to match!<br><br>";
			}
		}
		else{
			$password_message = "The old password is incorrect! <br><br>";
		}
	}
	else{
		$password_message = "";
	}

	
	//close account
	if(isset($_POST['close_account'])){
		header("Location: close_account.php");
	}


	//change photo
	if(isset($_POST['update_picture'])){
		if(empty($_FILES['profilePicture']['name'])){
			$picture = "<br>Nothing to change!";
		}

		else{
			$uploadOk = 1;

			$profile_pic = $_FILES['profilePicture']['name']; 

			if($profile_pic != ""){
				$targetDir = "assets/images/profile_pics/";
				$profile_pic = $targetDir.uniqid().basename($profile_pic);
				$imageFileType = pathinfo($profile_pic, PATHINFO_EXTENSION);

				if($_FILES['profilePicture']['size'] > 10000000){
					$picture = "Sorry your file is too large";
					$uploadOk = 0;
				}

				if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg"){
					$picture = "Sorry, only jpeg, jpg and png files are allowed.";
					$uploadOk = 0;
				}

				if($uploadOk){
					if(move_uploaded_file($_FILES['profilePicture']['tmp_name'], $profile_pic)){
						$uploadOk = 1;
					}
					else{
						$picture = "Sorry, We failed to move file to folder";
					}
				}
			}

			if($uploadOk){
				$query = mysqli_query($con, "UPDATE users SET profile_pic='$profile_pic' WHERE username='$userLoggedIn'");
				$picture = "";
				header("Location: settings.php");
			}

		}
	}
	else{
		$picture = "";
	}
?>