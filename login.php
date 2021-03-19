<?php
//Test, creates a cookie showing the user as being logged in using the account with id 1234
//$cookie_name = "userid";
//$cookie_value = "1234";
//setcookie($cookie_name, $cookie_value, time() + (86400 / 2), "/"); // 86400 = 1 day

//Set up SQL connection
$connection = mysqli_connect('localhost', 'root', '', 'userfiles');

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

if(isset($_COOKIE["userid"])){
	header("Location: index.php");	//Redirect back to the main index. You can't log in or register if you're already logged in
	die();
}else{
	$loggedin = False;
}

if(isset($_POST["username"]) && isset($_POST["password"])) {
	$result = mysqli_query($connection, "SELECT userId FROM t_user WHERE username = " . $_POST["username"] . " AND password = " . $_POST["password"] . " LIMIT 1");
	if (mysqli_num_rows($result) > 0){
		//Credentials are valid
		$row = mysqli_fetch_row($result);
		//Create a cookie storing the currently logged in user
		setcookie("userId", $row[0], time() + (86400 * 30), "/"); // 86400 = 1 day
		
		mysqli_free_result($result);
		
		header("Location: index.php");	//The cookie now shows that the user is logged in. Return to the main page.
		die();
	}
}

?>


<!DOCTYPE html>
<html>

<head>
	<title>Log In</title>
	<!-- basic meta data for webpage -->
	<meta charset="utf-8">
	<meta name="Keywords" content="photos, sharing, tags, location">

	<script>	
	function validateSignUp() {
		//var emailAd = document.forms["signUp"]["email"].value;
		
		//if (emailAd "") {
		//	alert("Invalid email");
		//	return false;
		//}
		return true;
	}
	</script>

	<!-- import style sheet -->
	<link rel="stylesheet" href="stylesheet.css">

	<!--FontAwesome 5.7.2-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


</head>

<body>

	<section id="logo">
		<div id="header" class="header">
			<div class="logo">
				<h1>Go-To</h1>
				<h2>Photos on the go</h2>
				<img src="images-slideshow\Go-to logo.png" alt="logo">
			</div>
		</div>
	</section>
	
	<section id="Login">
		<div id="login" class="login">
			<ul>
				<li> <a href="login.html">Log in / Register</a> </li>
			</ul>
		</div>
	</section>

	<section id="Upload">
		<div id="uploader" class="uploader">
			<div class="upload">
				<h3>Upload a Photo</h3>
				<button type="button" onclick="document.getElementById('file-input').click();">Select File</button>
				<input id="file-input" type="file" name="test" style="display: none;" />
			</div>
		</div>
	</section>

	<section id="Popular">
		<div id="menu" class="menu">
			<div class="popular">
				<h3>Popular Channels For You</h3>
				<ul>
					<li><a href="placeholder">Ocean</a></li>
					<li><a href="placeholder">Forest</a></li>
					<li><a href="placeholder">Skyline</a></li>
					<li><a href="placeholder">Animals</a></li>
				</ul>
			</div>
		</div>
	</section>
	
		<section id="content">
		<div id="pictures" class="pictures">
			<div class="content">
				
				<h1>Welcome. Sign up below!</h1>
				
				<form action = "<?php $_PHP_SELF ?>" onsubmit="return validateSignUp()" method = "POST" name = "signUp">
				Email Address: <input type = "text" placeholder="example@email.com" name = "email" required><br>
				Username: <input type = "text" placeholder="Enter Username" name = "username" required><br>
				Password: <input type = "password" placeholder="**********" name = "password" required><br>
				I have read and agreed to the <a href="tos.php" target="_blank">terms of service: <input type="checkbox" name="tos" required><br>
				<input type = "submit">
				</form>
				
				<h1>Or log in!</h1>
				
				<form action = "<?php $_PHP_SELF ?>" method = "POST" name = "signIn">
				Username: <input type = "text" placeholder="Enter Username" name = "usernameS" required><br>
				Password: <input type = "password" placeholder="**********" name = "passwordS" required><br>
				<input type = "submit">
				</form>


				<!-- CODE NOT IN USE <label for="username"><b>Username:</b></label>
				<input type="text" placeholder="Enter Username / Email" name="username" required>
				<label for="password"><b>Password:</b></label>
				<input type="password" placeholder="**********" name="password" required>
				<button type="button" onclick="alert('Log in attempt');">Submit</button>

				<h1>Or register.</h1>
				<label for="email"><b>Email address:</b></label>
				<input type="text" placeholder="Enter Email" name="email" required>
				<br> <br>

				<label for="username"><b>Username:</b></label>
				<input type="text" placeholder="Enter Username" name="username" required>
				<br> <br>

				<label for="password"><b>Password: </b></label>
				<input type="password" placeholder="**********" name="password" required>
				<br> <br>

				<input type="checkbox" id="tos" name="tos">
				<label for="tos">I have read and agreed to the <a href="placeholder" target="_blank">terms of
						service</a></label><br>
				<br> <br>

				<button type="button" onclick="alert('Register attempt');">Register</button>-->
			</div>
		</div>
	</section>
	
	<section class="footer">
		<div id="footer" class="footer">
			<i class="far fa-copyright" style="font-size:1.2em">2021 - Enterprise Development, Inc. All rights reserved</i>
			<div style="font-size:1.5em">
				<a href="https://github.com/ra91hw/Go-To-Application" title="GitHub" target="_blank" id="gh"><i class="fab fa-github"></i> Github</a> &nbsp;| &nbsp;
				<a href="http://facebook.com" title="Facebook" target="_blank" id="fb"><i class="fab fa-facebook"></i> Facebook</a>  &nbsp;| &nbsp;
				<a href="http://twitter.com" title="Twitter" target="_blank" id="tw"><i class="fab fa-twitter"></i> Twitter</a> &nbsp;| &nbsp;
				<a href="http://instagram.com" title="Instagram" target="_blank" id="insta"><i class="fab fa-instagram"></i>Instagram</a>
			</div>
		</div>
	</section>

</body>

</html>