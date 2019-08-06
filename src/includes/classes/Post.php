<?php
header("Content-Type:text/html;charset=utf-8");


class Post {
	//to make accesibility only inside of class(e.g)function), not allowed call var
	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	public function submitPost($body, $user_to, $imageName){
		$body = strip_tags($body);
		//make string safe
		$body = mysqli_real_escape_string($this->con, $body);
		//check if blank, delete
		$check_empty = preg_replace('/\s+/', '', $body);
		
		if($check_empty != ""){

			//if whitespace(1 or more together) split with whitespace)
			$body_array =  preg_split("/\s+/", $body);

			foreach($body_array as $key => $value){

				if(strpos($value, "www.youtube.com/watch?v=") !== false){

					$link = preg_split("!&!", $value); // if video is in youtube playlist
					$value =  preg_replace("!watch\?v=!", "embed/", $value);
					$value = "<br><iframe width=\'420\' height=\'315\' src=\'".$value."\'></iframe><br>";
					//add this iframe value in key&value of $body_array
					$body_array[$key] = $value;
				}

			}

			//if there is no iframe(youtube) it doesn't matter
			$body = implode(" ", $body_array);

			//current date(Year-Month-Day Hour-minutes-seconds)
			$date_added = date("Y-m-d H:i:s");
			//Get username
			$added_by = $this->user_obj->getUsername();

			//If user is on own profile, user_to is 'none'
			if($user_to == $added_by){
				$user_to == 'none';
			}

			//insert post
			$query = mysqli_query($this->con, "INSERT INTO posts VALUES(NULL, '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0', '$imageName')");
			$returned_id = mysqli_insert_id($this->con);

			//Insert notification
			if($user_to != 'none'){ // except for case when user posted userself
				$notification = new Notification($this->con, $added_by);
				$notification->insertNotification($returned_id, $user_to, "profile_post");
			}


			//Update post count for user
			$num_posts = $this->user_obj->getNumPosts(); // get original num_posts
			$num_posts++; // add 1
			$update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
			

			$stopWords = "a about above across after again against all almost alone along already
			 also although always among am an and another any anybody anyone anything anywhere are 
			 area areas around as ask asked asking asks at away b back backed backing backs be became
			 because become becomes been before began behind being beings best better between big 
			 both but by c came can cannot case cases certain certainly clear clearly come could
			 d did differ different differently do does done down down downed downing downs during
			 e each early either end ended ending ends enough even evenly ever every everybody
			 everyone everything everywhere f face faces fact facts far felt few find finds first
			 for four from full fully further furthered furthering furthers g gave general generally
			 get gets give given gives go going good goods got great greater greatest group grouped
			 grouping groups h had has have having he her here herself high high high higher
		     highest him himself his how however i im if important in interest interested interesting
			 interests into is it its itself j just k keep keeps kind knew know known knows
			 large largely last later latest least less let lets like likely long longer
			 longest m made make making man many may me member members men might more most
			 mostly mr mrs much must my myself n necessary need needed needing needs never
			 new new newer newest next no nobody non noone not nothing now nowhere number
			 numbers o of off often old older oldest on once one only open opened opening
			 opens or order ordered ordering orders other others our out over p part parted
			 parting parts per perhaps place places point pointed pointing points possible
			 present presented presenting presents problem problems put puts q quite r
			 rather really right right room rooms s said same saw say says second seconds
			 see seem seemed seeming seems sees several shall she should show showed
			 showing shows side sides since small smaller smallest so some somebody
			 someone something somewhere state states still still such sure t take
			 taken than that the their them then there therefore these they thing
			 things think thinks this those though thought thoughts three through
	         thus to today together too took toward turn turned turning turns two
			 u under until up upon us use used uses v very w want wanted wanting
			 wants was way ways we well wells went were what when where whether
			 which while who whole whose why will with within without work
			 worked working works would x y year years yet you young younger
			 youngest your yours z lol haha omg hey ill iframe wonder else like 
             hate sleepy reason for some little yes bye choose welcome to swirlfeed 
             i'm an administrator of this website if you have any questions feel free to message to me";



			//Check trending
			//stopwords, split with whitespace or comma and put in Array 
			$stopWords = preg_split("/[\s,]+/", $stopWords);

			//if $body has none alphabet or number change to ""
			$no_punctuation = preg_replace("/[^a-zA-Z 0-9]+/", "", $body);
		
		    //Check if it is not url
			if(strpos($no_punctuation, "height") === false && strpos($no_punctuation, "width") === false
				&& strpos($no_punctuation, "http") === false){

				//split with whitespace or comma and put in Array 
				$no_punctuation = preg_split("/[\s,]+/", $no_punctuation);

				foreach($stopWords as $value){
					foreach($no_punctuation as $key => $value2){
						//If no_punctuation is in stopWords change key to "";
						if(strtolower($value) == strtolower($value2)){
							$no_punctuation[$key] = "";
						}
					}
				}

				//execute function(INSERT INTO database)
				foreach($no_punctuation as $value){
					$this->calculateTrend(ucfirst($value));
				}

			} 
		}
	}

	public function calculateTrend($term){

		//If $term is not '' and Rn INSERT or UPDATE trendwords
		if($term != '' && $term != 'Rn'){
			$query = mysqli_query($this->con, "SELECT * FROM trends WHERE title='$term'");
		
			if(mysqli_num_rows($query) == 0){
				$insert_query = mysqli_query($this->con, "INSERT INTO trends(title, hits) VALUES('$term', '1')");
			} else{
				$insert_query = mysqli_query($this->con, "UPDATE trends SET hits=hits+1 WHERE title='$term'");
			}
		}
	}

	public function loadPostsFriends($data, $limit){

		$page = $data['page'];
		$userLoggedIn = $this->user_obj->getUsername();

		//added for scrolling
		if($page == 1){
			$start = 0;
		} else{
			$start = ($page - 1) * $limit;
		}

		$str = ""; //String to return
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0){

			//added for scrolling	
			$num_iterations = 0; //Number of results checked(not necessarily posted), 반복
			$count = 1;

			while($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image'];
				$like = mysqli_query($this->con, "SELECT * FROM likes WHERE post_id='$id' AND username='$userLoggedIn'");
				if(mysqli_num_rows($like)){
					$like_check = "unlike";
				}
				else{
					$like_check = "like";
				}
				$numlike = mysqli_query($this->con, "SELECT * FROM likes WHERE post_id='$id'");
				$numlike = mysqli_num_rows($numlike);

				//Prepare user_to string so it can be included even if not posted to a user
				//user가 자기한테 쓰는 거 아니고, 남의 뉴스피드에 글 쓰는거면 바꾸기
				if($row['user_to'] == "none" or $row['user_to'] == $userLoggedIn){
					$user_to ='';
				}

				else{
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='".$row['user_to']."'>".$user_to_name."</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()){
					continue; // skip load this data, execute next while
				}

				$user_logged_obj = new User($this->con, $userLoggedIn);
				if($user_logged_obj->isFriend($added_by)){ //Commenting this line so that everyone can see the post even those who aren't friends

					//it makes not load previous newsfeed
					if($num_iterations++ < $start){
						continue; 
					}

					//Once 10 posts have been loaded, break
					if($count > $limit){
						break;
					} else{
						$count++;
					}

					if($userLoggedIn == $added_by){
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					} else{
						$delete_button = "";
					}

					$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
					$user_row = mysqli_fetch_array($user_details_query);
					$first_name = $user_row['first_name'];
					$last_name = $user_row['last_name'];
					$profile_pic = $user_row['profile_pic'];
					?>

					<script>
						function toggle<?= $id ?>(event){
							var target = $(event.target);
							if(!target.is('button')&&!target.is('a')){
								var element = document.getElementById("toggleComment<?= $id; ?>");
								if(element.style.display == "block"){
									element.style.display = "none";
								} else{
									element.style.display = "block";
								}
							}
						}

						function likeHandler(postid){
							$.ajax({
								type: "POST",
								url: "includes/handlers/ajax_like_handler.php",
								data: {postid:postid, userLoggedIn:"<?= $userLoggedIn; ?>"},

								success: function(data){
									var liketext = $('a#like_button'+postid).text();
									if(liketext == 'like'){
										$('a#like_button'+postid).html('unlike');
										$('#num_like'+postid).text(data);
									}
									else{
										$('a#like_button'+postid).html('like');
										$('#num_like'+postid).text(data);
									}
									
								},
								error: function(data){
									alert('Failure');
								}
							})
						}
					</script>

					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);
					//Timeframes
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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

					if($imagePath != ""){
						$imageDiv = "<div class='postedImage'>
										<img src='$imagePath'>
									</div>";
					}
					else{
						$imageDiv = "";
					}

					$str .= "<div class='status_post' onClick='javascript:toggle$id(event)'>
								<div class='post_profile_pic'>
									<img src='$profile_pic' width='50'>
								</div>

								<div class='posted_by' style='color:#ACACAC;'>
									<a href='$added_by'> 
										$first_name $last_name 
									</a> 
									$user_to &nbsp;&nbsp;&nbsp;&nbsp; $time_message
									$delete_button
								</div>
								<div id='post_body'>
									$body
									<br>
									$imageDiv
									<br>
									<br>
								</div>

								<div class='newsfeedPostOptions'>
									Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
									<a href='javascript:void(0)' onclick='likeHandler($id)' id='like_button$id'>$like_check</a> <span style='color: black;' class='num_like' id='num_like$id'>  $numlike</span>
								</div>
							</div>							
							<div class='post_comment' id='toggleComment$id' style='display:none;'>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0' scrolling='yes'></iframe>
							</div>
							<hr>";
				}

			?>

			<script>
				$(document).ready(function(){
					$('#post<?= $id; ?>').on('click', function(){
						bootbox.confirm("Are you sure you want to delete this post?", function(result){

							//send this method, and inside of var result insert argument result, and send it
							$.post("includes/form_handler/delete_post.php?post_id=<?= $id; ?>", {result:result});

							if(result){
								setTimeout(function(){
									location.reload();
								}, 300);
							}
						});
					});
				});
			</script>

			<?php
			} //End while loop

			//added for scrolling
			if($count > $limit){ //it means $count is loaded already
				$str.= "<input type='hidden' class='nextPage' value='".($page + 1)."'>
				<input type='hidden' class='noMorePosts' value='false'>";
			} else{ // $count is under $limit
				$str.= "<input type='hidden' class='noMorePosts' value='true'>
						<p style='text-align: center;'>
							No more posts to show
						</p>"; 
			}
		}

		echo $str;	
	}


	public function loadProfilePosts($data, $limit){

		$page = $data['page'];
		$profileUser = $data['profileUsername'];
		$userLoggedIn = $this->user_obj->getUsername();

		//added for scrolling
		if($page == 1){
			$start = 0;
		} else{
			$start = ($page - 1) * $limit;
		}

		$str = ""; //String to return
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND ((added_by='$profileUser' AND user_to='none') OR user_to='$profileUser') ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0){

			//added for scrolling	
			$num_iterations = 0; //Number of results checked(not necessarily posted), 반복
			$count = 1;

			while($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$like = mysqli_query($this->con, "SELECT * FROM likes WHERE post_id='$id' AND username='$userLoggedIn'");
				if(mysqli_num_rows($like)){
					$like_check = "unlike";
				}
				else{
					$like_check = "like";
				}
				$numlike = mysqli_query($this->con, "SELECT * FROM likes WHERE post_id='$id'");
				$numlike = mysqli_num_rows($numlike);


				//it makes not load previous newsfeed
				if($num_iterations++ < $start){
					continue; 
				}

				//Once 10 posts have been loaded, break
				if($count > $limit){
					break;
				} else{
					$count++;
				}

				if($userLoggedIn == $added_by){
					$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
				} else{
					$delete_button = "";
				}

				$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
				$user_row = mysqli_fetch_array($user_details_query);
				$first_name = $user_row['first_name'];
				$last_name = $user_row['last_name'];
				$profile_pic = $user_row['profile_pic'];
				?>

				<script>
					function toggle<?= $id ?>(event){
						var target = $(event.target);
						if(!target.is('a')){
							var element = document.getElementById("toggleComment<?= $id; ?>");
							if(element.style.display == "block"){
								element.style.display = "none";
							} else{
								element.style.display = "block";
							}
						}
					}

					function likeHandler(postid){
						$.ajax({
							type: "POST",
							url: "includes/handlers/ajax_like_handler.php",
							data: {postid:postid, userLoggedIn:"<?= $userLoggedIn; ?>"},

							success: function(data){
								var liketext = $('a#like_button'+postid).text();
								if(liketext == 'like'){
									$('a#like_button'+postid).html('unlike');
									$('#num_like'+postid).text(data);
								}
								else{
									$('a#like_button'+postid).html('like');
									$('#num_like'+postid).text(data);
								}
								
							},
							error: function(data){
								alert('Failure');
							}
						})
					}
				</script>

				<?php

				$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
				$comments_check_num = mysqli_num_rows($comments_check);
				//Timeframes
				$date_time_now = date("Y-m-d H:i:s");
				$start_date = new DateTime($date_time); //Time of post
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

				$str .= "<div class='status_post' onClick='javascript:toggle$id(event)'>
							<div class='post_profile_pic'>
								<img src='$profile_pic' width='50'>
							</div>

							<div class='posted_by' style='color:#ACACAC;'>
								<a href='$added_by'> 
									$first_name $last_name 
								</a> 
								&nbsp;&nbsp;&nbsp;&nbsp; $time_message
								$delete_button
							</div>
							<div id='post_body'>
								$body
								<br>
								<br>
								<br>
							</div>

							<div class='newsfeedPostOptions'>
								Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
								<a href='javascript:void(0)' onclick='likeHandler($id)' id='like_button$id'>$like_check</a> <span style='color: black;' class='num_like' id='num_like$id'>  $numlike</span>
							</div>
						</div>							
						<div class='post_comment' id='toggleComment$id' style='display:none;'>
							<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0' scrolling='yes'></iframe>
						</div>
						<hr>";
			?>

			<script>
				$(document).ready(function(){
					$('#post<?= $id; ?>').on('click', function(){
						bootbox.confirm("Are you sure you want to delete this post?", function(result){

							//send this method, and inside of var result insert argument result, and send it
							$.post("includes/form_handler/delete_post.php?post_id=<?= $id; ?>", {result:result});

							if(result){
								setTimeout(function(){
									location.reload();
								}, 300);
							}
						});
					});
				});
			</script>

			<?php
			} //End while loop

			//added for scrolling
			if($count > $limit){ //it means $count is loaded already
				$str.= "<input type='hidden' class='nextPage' value='".($page + 1)."'>
				<input type='hidden' class='noMorePosts' value='false'>";
			} else{ // $count is under $limit
				$str.= "<input type='hidden' class='noMorePosts' value='true'>
						<p style='text-align: center;'>
							No more posts to show
						</p>"; 
			}
		}

		echo $str;	
	}

	public function getSinglePost($post_id){
		
		$userLoggedIn = $this->user_obj->getUsername();

		//Check if user checked this post
		$opened_query =  mysqli_query($this->con, "UPDATE notifications SET opened='yes' WHERE user_to='$userLoggedIn' AND link LIKE '%=$post_id'");

		$str = ""; //String to return
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND id='$post_id'");

		if(mysqli_num_rows($data_query) > 0){

			$row = mysqli_fetch_array($data_query);
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$like = mysqli_query($this->con, "SELECT * FROM likes WHERE post_id='$id' AND username='$userLoggedIn'");
				if(mysqli_num_rows($like)){
					$like_check = "unlike";
				}
				else{
					$like_check = "like";
				}
				
				$numlike = mysqli_query($this->con, "SELECT * FROM likes WHERE post_id='$id'");
				$numlike = mysqli_num_rows($numlike);

				//Prepare user_to string so it can be included even if not posted to a user
				//user가 자기한테 쓰는 거 아니고, 남의 뉴스피드에 글 쓰는거면 바꾸기
				if($row['user_to'] == "none" or $row['user_to'] == $userLoggedIn){
					$user_to ='';
				}

				else{
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='".$row['user_to']."'>".$user_to_name."</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()){
					return; // skip load this data, execute next while
				}

				$user_logged_obj = new User($this->con, $userLoggedIn);
				if($user_logged_obj->isFriend($added_by)){


					if($userLoggedIn == $added_by){
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					} else{
						$delete_button = "";
					}

					$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
					$user_row = mysqli_fetch_array($user_details_query);
					$first_name = $user_row['first_name'];
					$last_name = $user_row['last_name'];
					$profile_pic = $user_row['profile_pic'];
					?>

					<script>
						function toggle<?= $id ?>(event){
							var target = $(event.target);
							if(!target.is('a')){
								var element = document.getElementById("toggleComment<?= $id; ?>");
								if(element.style.display == "block"){
									element.style.display = "none";
								} else{
									element.style.display = "block";
								}
							}
						}

						function likeHandler(postid){
							$.ajax({
								type: "POST",
								url: "includes/handlers/ajax_like_handler.php",
								data: {postid:postid, userLoggedIn:"<?= $userLoggedIn; ?>"},

								success: function(data){
									var liketext = $('a#like_button'+postid).text();
									if(liketext == 'like'){
										$('a#like_button'+postid).html('unlike');
										$('#num_like'+postid).text(data);
									}
									else{
										$('a#like_button'+postid).html('like');
										$('#num_like'+postid).text(data);
									}
									
								},
								error: function(data){
									alert('Failure');
								}
							})
						}
					</script>

					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);
					//Timeframes
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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

					$str .= "<div class='status_post' onClick='javascript:toggle$id(event)'>
								<div class='post_profile_pic'>
									<img src='$profile_pic' width='50'>
								</div>

								<div class='posted_by' style='color:#ACACAC;'>
									<a href='$added_by'> 
										$first_name $last_name 
									</a> 
									$user_to &nbsp;&nbsp;&nbsp;&nbsp; $time_message
									$delete_button
								</div>
								<div id='post_body'>
									$body
									<br>
									<br>
									<br>
								</div>

								<div class='newsfeedPostOptions'>
									Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
									<a href='javascript:void(0)' onclick='likeHandler($id)' id='like_button$id'>$like_check</a> <span style='color: black;' class='num_like' id='num_like$id'>  $numlike</span>
								</div>
							</div>							
							<div class='post_comment' id='toggleComment$id' style='display:none;'>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0' scrolling='yes'></iframe>
							</div>
							<hr>";
				
				?>

			<script>
				$(document).ready(function(){
					$('#post<?= $id; ?>').on('click', function(){
						bootbox.confirm("Are you sure you want to delete this post?", function(result){

							//send this method, and inside of var result insert argument result, and send it
							$.post("includes/form_handler/delete_post.php?post_id=<?= $id; ?>", {result:result});

							if(result){
								setTimeout(function(){
									location.reload();
								}, 300);
							}
						});
					});
				});
			</script>

			<?php
			}
			else{
					echo "<p>You cannot see this post because you are not friends with this user.</p>	";
					return;
			}
		}
		else{
			echo "<p>No post found. If you clicked a link, it may be broken.</p>";
			return;
		}

		echo $str;	
	}
}
?>