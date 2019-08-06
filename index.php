<?php
require_once ('config/config.php');
include_once ("includes/header.php");
include_once ("includes/classes/User.php");
include_once ("includes/classes/Post.php");

if(isset($_POST['post'])){

	$uploadOk = 1;
	// fileToUpload = input name, name = extension
	$imageName = $_FILES['fileToUpload']['name']; 
	$errorMessage = "";

	if($imageName != ""){
		$targetDir = "assets/images/posts/";
		// if $imageName is dog.png it shows assets/images/posts/sdlkfj232dog.png
		$imageName = $targetDir.uniqid().basename($imageName);
		//get type of pathinfo, if dog.png, shows png
		$imageFileType = pathinfo($imageName, PATHINFO_EXTENSION); 

		if($_FILES['fileToUpload']['size'] > 1000000){
			$errorMessage = "Sorry your file is too large";
			$uploadOk = 0;
		}

		if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg"){
			$errorMessage = "Sorry, only jpeg, jpg and png files are allowed.";
			$uploadOk = 0;
		}

		if($uploadOk){
			if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)){
				//image uploaded okay 
			}
			else{
				//image did not upload
				$uploadOk = 0;
			}
		}

	}

	if($uploadOk){
		$post = new Post($con, $userLoggedIn);
		$post->submitPost($_POST['post_text'], 'none', $imageName);
	}
	else{
		echo "<div style='text-align: center;' class='alert alert-danger'>
				$errorMessage
			</div>";
	}

	
}
?>
	<div class="user_details column "id = "user_details_column">
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

	<div class="main_column column">
		<form class="post_form " action="index.php" method="POST" enctype="multipart/form-data">
			<textarea name="post_text" class = "expand" id="post_text" placeholder="Got something to say?"></textarea>
			<input type="submit" name="post" id="post_button" value="Post">
			<input type="file" name="fileToUpload" id="fileToUpload">
			<hr>
		</form>

		<div class="posts_area"></div>
		<img id='loading' src="assets/images/icons/loading.gif">

	</div>

	<div class="user_details column" id = "user_details_column">
		<div class = "trending">
		<h4>Trending</h4>
		</div>
		<div class="trends">
			<?php
			$query = mysqli_query($con, "SELECT * FROM trends ORDER BY hits DESC LIMIT 9");

			foreach($query as $row){

				$word = $row['title'];
				$word_dot = strlen($word) >= 14 ? "..." : "";

				$trimmed_word = str_split($word, 14);
				$trimmed_word = $trimmed_word[0];

				echo "<div style='padding: 1px'>";
				echo $trimmed_word.$word_dot."<br/>";
				echo "</div>";


			}

			?>
		</div>

		

	</div>

	<script>
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';

		$(document).ready(function(){

			$('#loading').show();

			//original ajax request for loading first posts
			$.ajax({
				url: "includes/handlers/ajax_load_posts.php",
				type: "POST",
				data: "page=1&userLoggedIn=" + userLoggedIn, // send page and userLoggedIn
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
						url: "includes/handlers/ajax_load_posts.php",
						type: "POST",
						data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
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