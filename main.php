<?php
$validateL = true;
$reg_EmailL = "/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/";
$reg_PswdL = "/^(\S*)?\d+(\S*)?$/";
$emailL = "";
$errorMsg = "";

if (isset($_POST["email"]) && $_POST["email"])
{
    $emailL = trim($_POST["email"]);
    $passwordL = trim($_POST["password"]);
    
    $db = new mysqli("localhost", "fan270", "1580Fjk", "fan270");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }
    
    
    $q = "SELECT * FROM Users where email = '$emailL' AND password = '$passwordL'";
    
    $r = $db->query($q);
    $row = $r->fetch_assoc();
    
    if($emailL != $row["email"] && $passwordL != $row["password"])
    {
        $validateL = false;
    }
    else
    {
        $emailLMatch = preg_match($reg_EmailL, $emailL);
        if($emailL == null || $emailL == "" || $emailLMatch == false)
        {
            $validateL = false;
        }
        
        $pswdLen = strlen($passwordL);
        $passwordLMatch = preg_match($reg_PswdL, $passwordL);
        if($passwordL == null || $passwordL == "" || $pswdLen < 8 || $passwordLMatch == false)
        {
            $validateL = false;
        }
    }
    
    if($validateL == true)
    {
        
        $login_log = $row["uid"];
        $user_name = $row["username"];
        $db = new mysqli("localhost", "fan270", "1580Fjk", "fan270");
        if ($db->connect_error)
        {
            die ("Connection failed: " . $db->connect_error);
        }
        
        $q2="INSERT INTO Log(uid,username) VALUES('$login_log','$user_name')";
        $r3=$db->query($q2);
        
        session_start();
        $_SESSION["email"] = $row["email"];
        $_SESSION["uname"] = $row["username"];
        
        header("Location: msg_list.php");
        $db->close();
        exit();
    }
    else
    {
        $errorMsg = "The email/password combination was incorrect. Login failed.";
        $db->close();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Begin a private discussion</title>
<link rel="stylesheet" type="text/css" href="external_style.css">
<script type="text/javascript" src="external.js"></script>  
<script>

function refresh(){
	var  xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("item3").innerHTML = this.responseText; 
		    setTimeout("refresh()",5000);
		}
	}
	xmlhttp.open("GET","main.php",true);
	xmlhttp.send(null);
}
</script>
</head>

<body>

  	<div class="nav">
  	<h1 class="title"><a style="text-decoration:none; color:white;" href="main.php">Private Messaging</a></h1>
  	  <div class="form">
  	  <form id="login" method="post" action="main.php">
  		<table style="margin:auto">
		<tr>
			<th><input type="text" placeholder="Email" name="email"></th>
			<th><input type="password" placeholder="Password" name="password"></th>
			<th><button type="submit" class="login_butt">Login</button></th>
		</tr>
		<tr>
			<th><label id="email_msg" class="err_msg"></label></th>
			<th><label id="pswd_msg" class="err_msg"><?php echo $errorMsg?></label></th>
		</tr>
		</table>
		</form>
  		</div>
  	</div>

<div class="container_1" onload="refresh()">

<section id="item4">

<table>
  	<tr><td><p>Begin a private discussion.</p></td></tr>
  	<tr><td><a href="sign_up.php"><button class="signup_butt">Sign Up Now</button></a></td></tr>
</table>
</section>

 
             <section id="item3">
 <?php
$conn = mysqli_connect("localhost","fan270","1580Fjk","fan270");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql0="SELECT * FROM Post";
$r2 = mysqli_query($conn,$sql0);
if(mysqli_num_rows($r2)<=0)
{
    echo "<p>There is no active message.</p><hr>";
}

//$num_msg = mysqli_num_rows($r2)

else if(mysqli_num_rows($r2)>0)
{
        echo "<p>Total active messages:".mysqli_num_rows($r2)." </p><hr>";
}

?>            
             
             
            
            <p>Total message views: 8 </p><hr>
            <p>Last logged in users:</p>
            <article>
            <ul style="list-style:none;">
 
 
<?php

$sql = "SELECT * FROM Log";

$result = $conn->query($sql);
$counterp=0;
$row5 = mysqli_fetch_assoc($result);

if(mysqli_num_rows($result)>0&&($counterp<=4))
{
    while(($row5 = mysqli_fetch_assoc($result))) 
        {
            $log_uid=$row5["uid"];
            $q5="SELECT * FROM Users WHERE uid = '$log_uid'";      
            $r5=$conn->query($q5);
            $row6=$r5->fetch_assoc();
            ?>
		
            <li><img src="uploads/<?php echo $row5['username']?>" style='width:20px; height:20px; '>&nbsp;<?php echo $row5['username'] ?>&nbsp;<?php echo $row5['login_timestamp'] ?></li>

            <?php
		}
}else
{
    echo"<li>Nothing. </li>";
}


?>

</ul>
</article>
</section>





</div>

<footer>

		<ul>
			<li class="footer_head">About</li>
			<li class="footer_head">Help</li>
			<li class="footer_head">Blog</li>
			<li class="footer_head">Privacy</li>
			<li class="footer_head"><a style="text-decoration:none;color:white;" href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fwww2.cs.uregina.ca%2F~fan270%2Fas2%2Flogin_page.html">Validate HTML5</a></li>
		</ul>

</footer>


<script type = "text/javascript"  src = "login-r.js" ></script>
</body>
</html>