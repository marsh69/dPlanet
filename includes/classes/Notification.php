<?php
class Notification{
	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	public function getUnreadNumber(){
		$userLoggedIn = $this->user_obj->getUsername();
		$query = mysqli_query($this->con, "SELECT * FROM notifications WHERE viewed='no' AND user_to='$userLoggedIn'");
		return mysqli_num_rows($query);
	}

	public function getNotifications($data, $limit){
		$page = $data['page']; // page=1&userLoggedIn=" + user
		$userLoggedIn = $this->user_obj->getUsername();
		$return_string = "";

		if($page == 1){
			$start = 0;
		}
		else{
			$start = ($page - 1) * $limit;
		}

		//change messages's viewed of userLoggedIn to yes when click navbar message icon
		$set_viewed_query = mysqli_query($this->con, "UPDATE notifications SET viewed='yes' WHERE user_to='$userLoggedIn'");

		// Order by latest talker
		$query = mysqli_query($this->con, "SELECT * FROM notifications WHERE user_to='$userLoggedIn' ORDER BY id DESC");

		if(mysqli_num_rows($query) == 0){
			echo "You have no notifications";
			return;
		}


		$num_iterations = 0; // Number of messages checked
		$count = 1; // Number of messages posted

		while($row = mysqli_fetch_array($query)){

			if($num_iterations ++ < $start){
				continue;
			}

			if($count > $limit){
				break;
			}
			else{
				$count++;
			}

			$user_from = $row['user_from'];

			$user_data_query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$user_from'");
			$user_data = mysqli_fetch_array($user_data_query);

			//Timeframes
			$date_time_now = date("Y-m-d H:i:s");
			$start_date = new DateTime($row['datetime']); //Time of post
			$end_date = new DateTime($date_time_now); //Current time
			$interval = $start_date->diff($end_date); //Difference between dates
			if($interval->y >= 1){
				if($interval == 1){
					$time_message = $interval->y." year ago"; //1 year ago
				} else{
					$time_message = $interval->y." years ago"; //1+ year ago
				}
			} 
			else if($interval->m >= 1){
				if($interval->d == 0){
					$days = " ago";
				} else if($interval->d == 1){
					$days = $interval->d." day ago";
				} else{
					$days = $interval->d." days ago";
				}

				if($interval->m == 1){
					$time_message = $inverval->m." month".$days;
				} else{
					$time_message = $inverval->m." month".$days;
				}
			}
			else if($interval->d >= 1){
				if($interval->d == 1){
					$time_message = "Yesterday";
				} else{
					$time_message = $interval->d." days ago";
				}
			}
			else if($interval->h >= 1){
				if($interval->h == 1){
					$time_message = $interval->h." hour ago";
				} else{
					$time_message = $interval->h." hours ago";
				}
			}
			else if($interval->i >= 1){
				if($interval->i == 1){
					$time_message = $interval->i." minute ago";
				} else{
					$time_message = $interval->i." minutes ago";
				}
			}
			else {
				if($interval->s < 30){
					$time_message = "Just now";
				} else{
					$time_message = $interval->s." seconds ago";
				}
			}



			$opened = $row['opened'];
			//if you didn't message open yet, give background color
			$style = ($row['opened'] == 'no') ? "background-color: #DDEDFF;" : "";

			$return_string .= "<a href='".$row['link']."'>
								   <div class='resultDisplay resultDisplayNotification' style='".$style."'>
									   <div class='notificationsProfilePic'>
									   		<img src='".$user_data['profile_pic']. "'>
									   </div>
									   <p class='timestamp_smaller' id='grey'>". $time_message ."</p>" .$row['message']."
								   </div>
							   </a>";
								
		} //and of foreach

		//If posts were loaded, for infinite scrolling
		if($count > $limit){
			$return_string .= "<input type='hidden' class='nextPageDropdownData' value=' ".($page + 1)."'><input type='hidden' class='noMoreDropdownData' value='false'>";
		}

		else{
			$return_string .= "<input type='hidden' class='nextPageDropdownData' value=' ".($page + 1)."'><input type='hidden' class='noMoreDropdownData' value='true'><p style = 'text-align: center; margin-top: 8px;'>No more notifications to read!</p>";
		}

		return $return_string;
	}

	public function insertNotification($post_id, $user_to, $type){
		$userLoggedIn = $this->user_obj->getUsername();
		$userLoggedInName = $this->user_obj->getFirstAndLastName();

		$date_time = date("Y-m-d H:i:s");

		switch($type){
			case 'comment':
				$message = $userLoggedInName." commented on your post";
				break;
			case 'like':
				$message = $userLoggedInName." liked your post";
				break;
			case 'profile':
				$message = $userLoggedInName. " posted on your profile";
				break;
			case 'profile_post':
				$message = $userLoggedInName. " commented on a post you commented on";
				break;
			case 'profile_comment':
				$message = $userLoggedInName. " commented on a profile post";
				break;
		}

		$link = "post.php?id=".$post_id;

		$insert_query = mysqli_query($this->con, "INSERT INTO notifications VALUES(NULL, '$user_to', '$userLoggedIn', '$message', '$link', '$date_time', 'no', 'no')");
	}

}

?>