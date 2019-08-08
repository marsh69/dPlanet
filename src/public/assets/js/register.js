$(document).ready(function(){
	//On click signup, hide login and show registration form
	$('#signup').click(function(){
		$("#first").fadeOut("slow", function(){
			$("#second").fadeIn("slow");
		});
	});

	$('#signup_avatar').click(function(){
		$("#avatar_div").fadeOut("slow", function(){
			$("#second").fadeIn("slow");
		});
	});


	//On click signin, hide login and show registration form
	$('#signin').click(function(){
		/*$("#avatar_div").fadeOut("slow", function(){*/
			$("#second").fadeOut("slow",function(){
				$("#first").fadeIn("slow");
			});

		});
	});
	$("#avatar_id").click(function(){
		$("#second").fadeOut("slow",function(){
  		$("#avatar_div").fadeIn("fast");
  		});
	});
/*});
*/

/*$(document).ready(function(){
	//On click signup, hide login and show registration form
	$('#signup').click(function(){
		$("#first").slideUp("fast", function(){
			$("#second").slideDown("fast");
		});
	});

	$('#signup_avatar').click(function(){
		$("#avatar_div").slideUp("fast", function(){
			$("#second").slideDown("fast");
		});
	});


	//On click signin, hide login and show registration form
	$('#signin').click(function(){
		$("#avatar_div").slideUp("fast", function(){
			$("#second").slideUp("fast",function(){
				$("#first").slideDown("fast");
			});

		});
	});
	$("#avatar_id").click(function(){
		$("#second").fadeOut("fast",function(){
  		$("#avatar_div").fadeIn("fast");
  		});
	});
});*/