<?php
$validate = true;
$reg_Email = "/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/";
$reg_Pswd = "/^(?![^a-zA-Z]+$)(?!\D+$).{8}$/";
$reg_Bday = "/^\d{1,2}\/\d{1,2}\/\d{4}$/";
$email = "";
$date = "mm/dd/yyyy";
$errorMsg="";
$error="";


if (isset($_POST["email"]) && $_POST["email"])
{
    $email = trim($_POST["email"]);
    $date = trim($_POST["date"]);
    $password = trim($_POST["password"]);
    $username = trim($_POST["username"]);
    $db = new mysqli("localhost", "fan270", "1580Fjk", "fan270");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }
    
    

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $errorMsg= "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $errorMsg= "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        $errorMsg= "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $errorMsg= "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $errorMsg= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $errorMsg= "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $errorMsg= "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                $filename= $_FILES['fileToUpload']['name'];
                //$name=explode(".",$filename);
                $newname="uploads/".$username;
                $oldname="uploads/".$filename;
                rename($oldname,$newname);

            } else {
                $errorMsg= "Sorry, there was an error uploading your file.";
            }
        }
   
        
        if ($uploadOk == 1)
        {
            $validate = true;
            
        }
        
        $q1 = "SELECT * FROM Users WHERE email = '$email'";
        $r1 = $db->query($q1);
        
        if($r1->num_rows > 0)
        {
            $validate = false;
        }
        else
        {
            $emailMatch = preg_match($reg_Email, $email);
            if($email == null || $email == "" || $emailMatch == false)
            {
                $validate = false;
            }
            
            
            $pswdMatch = preg_match($reg_Pswd, $password);
            if($password == null || $password == "" || $pswdMatch == false)
            {
                $validate = false;
            }
            
            $bdayMatch = preg_match($reg_Bday, $date);
            if($date == null || $date == "" || $bdayMatch == false)
            {
                $validate = false;
            }
        }
        
        if($validate == true)
        {
            $dateFormat = date("Y-m-d", strtotime($date));
            
            $q2 = "INSERT INTO Users(username,password,email,DOB,image_URL) VALUES('$username','$password','$email','$dateFormat','$newname')";
            $r2 = $db->query($q2);
            
            if ($r2 === true)
            {
                header("Location:main.php");
                $db->close();
                exit();
            }
        }
        else
        {
            $error = "email address is not available. Signup failed.";
            $db->close();
        }
        
}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
<meta name="viewport" content="initial-scale=1, user-scalable=0, minimal-ui">
<title>Begin a private discussion</title>
<link rel="stylesheet" type="text/css" href="external_style.css">
<script type="text/javascript" src="external.js"></script>  
</head>
<body>


  	<nav class="nav">
  	<h1 class="title"><a style="text-decoration:none; color:white;" href="main.php">Private Messaging</a></h1>
  	  <div class="form">
  		<table style="margin:auto">
		<tr>
			<th><p class="login_msg">Already registered?</p></th>
			<th><a href="main.php"><button class="login_butt">Login</button></a></th>
		</tr>
		</table>
  		</div>
  	</nav>


  <div class="frame">
  <p class="signup_head">Sign up now to see what's coming next.</p>
	<form id="signup" method="post" action="sign_up.php" enctype="multipart/form-data">
	<table style="margin:auto">
		<tr><td><input type="text" placeholder="Username" name="username"></td></tr>
		<tr><td><label id="uname_msg" class="err_msg"></label></td></tr>
		<tr><td><input type="text" placeholder="Email address" name="email"></td></tr>
		<tr><td><label id="email_msg" class="err_msg"></label></td></tr>
		<tr><td><input type="text" placeholder="Birthday (mm/dd/yyyy)" name="date"></td></tr>
		<tr><td><label id="date_msg" class="err_msg"></label></td></tr>
		<tr><td><input type="password" placeholder="Password" name="password"></td></tr>
		<tr><td><label id="pswd_msg" class="err_msg"></label></td></tr>
		<tr><td><input type="password" placeholder="Confirm password"></td></tr>
		<tr><td><label id="pswdr_msg" class="err_msg"><?php echo $error?></label></td></tr>
		<tr><td><p class="avatar_head">Choose your avatar</p></td></tr>
		<tr><td><input type="file" id="fileToUpload" name="fileToUpload"></td></tr>
		<tr><td><label id="photo_msg" class="err_msg"><?php echo $errorMsg?></label></td></tr>
	</table>
	<br/>
		<button type="submit" class="signup_butt">Get started</button>
	</form>
	</div>
<br/>


<!-- 
<footer>
		<ul>
			<li class="footer_head">About</li>
			<li class="footer_head">Help</li>
			<li class="footer_head">Blog</li>
			<li class="footer_head">Privacy</li>
			<li class="footer_head"><a style="text-decoration:none;color:white;" href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fwww2.cs.uregina.ca%2F~fan270%2Fas2%2Flogin_page.html">Validate HTML5</a></li>
		</ul>
</footer>
-->
<script type = "text/javascript"  src = "signup-r.js" ></script>
</body>
</html>