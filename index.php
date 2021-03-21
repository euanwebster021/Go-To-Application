<?php
//Open database_login.csv, storing the login details to the mysql database
//database_login.csv should contain 4 values:
//	1. host
//	2. username
//	3. password
//	4. database name
$file = fopen("database_login.csv","r");
$logindetails = fgetcsv($file);
fclose($file);

//Set up SQL connection
$connection = mysqli_connect($logindetails[0], $logindetails[1], $logindetails[2], $logindetails[3]);

if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}

//Find the page number of results to display
if(isset($_GET["page"])){
	//There's a certain page number of photos to use
	$page = $_GET["page"];
} else{
	//Default to 0 to show first results
	$page = 0;
}
?>

<!DOCTYPE html>
<html>
	<?php
	//Check if the cookies currently record the user as being logged in
	if(isset($_COOKIE["userId"])){
		$loggedin = True; //Logged in as user with the userId value
		$userId =$_COOKIE["userId"];
		$result = mysqli_query($connection, "SELECT username FROM t_user WHERE id = " . $userId);
		$username = mysqli_fetch_array($result)[0];
	} else{
		$loggedin = False;
	}
	?>
	<head>
		<title>Go-To</title>
		<!-- basic meta data for webpage -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale: 1.0">
		<meta name="Keywords" content="photos, sharing, tags, location">
		
		<!-- import style sheet -->
		<link rel="stylesheet" href="stylesheet.css">
		
<!--FontAwesome 5.7.2-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

		<!-- import javascript -->
		<script src="script.js"></script>
	</head>
	<body>
	
		<section id="logo">
			<div id="header" class="header">
				<div class="logo">
					<h1><a href="index.php" style="text-decoration: none !important">Go-To</a></h1>
					<h2>Photos on the go</h2>
				</div>
			</div>
		</section>
		
		<section id="Login">
			<div id="login" class="login">
				<?php
				if ($loggedin){
					echo "<ul> <li> <a href='profile.php'>Welcome, " . $username . "</a> </li> </ul>"; //PROFILE PAGE NEEDS A LOG OUT OPTION
				} else{
					echo "<ul> <li> <a href='login.php'>Log in / Register</a> </li> </ul>";
				}
				?>
			</div>
		</section>
		
		<section id="Upload">
			<div id="uploader" class="uploader">
				<div class="upload">
					<h3>Upload a Photo</h3>
					<form action="upload.php" method="post" enctype="multipart/form-data">
						<input type="file" name="fileToUpload" onchange="form.submit()" id="fileToUpload">
					</form>
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
					<div id="slideshow">
						<a><img src="images-slideshow\ocean.jpg" alt="ocean"></a>
						<a><img src="images-slideshow\forest.jpg" alt="forest"></a>
						<a><img src="images-slideshow\skyline.jpg" alt="skyline"></a>
					</div>
				
						<h1>Content from Database will go here.</h1>
						<?php 
							$query = "SELECT CONCAT(path, newFileName, '.', ext) AS imgname, t_user.id AS userId, t_user.username AS username FROM t_files JOIN t_user ON t_files.userId=t_user.id LIMIT 20 OFFSET " . ($page*20); //Gets 20 file names including extension
							
							//Count the results of a different query (since the previous one is limited)
							$photoCount = mysqli_num_rows(mysqli_query($connection, "SELECT CONCAT(newFileName, '.', ext) AS imgname, t_user.id AS userId, t_user.username AS username FROM t_files JOIN t_user ON t_files.userId=t_user.id"));
							
							//NOTE: Tags have NOT yet been implemented on uploading. Once the database supports it, using "SELECT CONCAT(newFileName, '.', ext) AS imgname FROM t_files WHERE [tag field name] = [desired tag name] LIMIT 20" should work. This can be copied across each of the pages on the menu at the side (i.e. for what is currently listed as Ocean, Forest, Skyline and Animals
							//Another thing to add is the ability to cycle through the rest of the images beyond the first 20. To do this, it might be best to filter them out with PHP rather than in the SQL tag?
								
								
							if($photoCount > 0){
								$result = mysqli_query($connection, $query);

								echo "<table>"; // begin table

								while($image = mysqli_fetch_array($result)){   // for each image returned
									echo "<tr> <td> <img src = '" . $image['imgname'] . "' style='max-height:600px;height:100%'>";  //$image['index'] the index here is a field name
									echo "<p> Uploaded by " . $image['username'] . "  ";
									
									if(file_exists ("avatars/" . $image["userId"] . ".png")){
										//User has a png avatar
										echo "<img src='/Go-To-Application/avatars/" . $image["userId"] . ".png' width='40' height='40'> </p> <hr> </td> </tr>";
									} elseif(file_exists ("avatars/" . $image["userId"] . ".jpg")){
										//User has a jpg avatar
										echo "<img src='avatars/" . $image["userId"] . ".jpg' width='40' height='40'> </p> <hr> </td> </tr>";
									} elseif(file_exists ("/Go-To-Application/avatars/" . $image["userId"] . ".gif")){
										//User has a gif avatar
										echo "<img src='avatars/" . $image["userId"] . ".gif' width='40' height='40'> </p> <hr> </td> </tr>";
									} else{
										//User does not have an avatar
										//Display default
										echo "<img src='avatars/default.png' width='40' height='40'> </p> <hr> </td> </tr>";
									}
								}

								echo "</table> <br>"; // end table
								
								//Have options to cycle through pages of results
								echo "<form action = '' method = 'GET'>";
								if($page > 0){
									//Not the first page - can go back any earlier!
									//Display a previous page button, setting page to the current value of page - 1
									echo "<button name='page' type='submit' value=" . ($page - 1) . ">Previous</button>";
								}
								if(($page + 1) * 20 < $photoCount){
									//There are photos remaining
									//Display a previous page button, setting page to the current value of page - 1
									echo "<button name='page' type='submit' value=" . ($page + 1) . ">Next</button>";
								}
								echo "</form>";
							}else{
								echo "<p>No photos found...</p>";
							}
							//Finished with the database
							mysqli_close($connection); ?>
						<h1> Content from Database above </h1>
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
