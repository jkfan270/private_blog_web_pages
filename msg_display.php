<?php 
session_start();
$username = $_SESSION["uname"];
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Message Detail</title>
<link rel="stylesheet" type= "text/css" href="external_style.css">
</head>
<script>
function refresh(){
	xmlHttp = new XMLHttpRequest();
	xmlHttp.open("GET","msg_display.php",true);
	xmlHttp.onreadystatechange = function(){
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
			document.getElementById("body").innerHTML = xmlHttp.responseText; 
		    setTimeout("refresh()",6000);
		}
	}
	xmlHttp.send();
}
</script>
<body style="background-color:#ededed;" onload="refresh()" id=body>

<?php

if(isset($_SESSION["email"]))
{
    $email=$_SESSION["email"];
    $db = new mysqli("localhost", "fan270", "1580Fjk", "fan270");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }
    $q="SELECT * FROM Users where email = '$email'";
    $r = $db->query($q);
    $row = $r->fetch_assoc();
    

	
?>


<nav class="navigator">
<div>
<ul>
  		<li><a class="active" href="msg_list.php">Home</a></li>
  		<li><a class="active" href="#moments">Moments</a></li>
 		<li><a class="active" href="#notifications">Notifications</a></li>
 		<li><a class="active" href="#messages">Messages</a></li>
 		<li><a class="active" href="msg_creation.html">Make Post</a></li>
 		<li><input type="text" name="keyname"></li>
 		<li ><a class="active" href=#>Search</a></li>
 		<li style="float:right;"><a class="active" href="logout.php">Log out</a></li>
	</ul>

</div>
</nav>

<div class="container">

<br/>
<?php 
$con = mysqli_connect("localhost","fan270","1580Fjk","fan270");
$q2="SELECT * FROM Post ORDER BY created_at DESC";
$result=mysqli_query($con,$q2);
$counterp=0;
$row2=mysqli_fetch_assoc($result);


?>
<section id="u1" class="content">
<form id="user_2">
<h1><img src="uploads/<?php echo $row3['username']?>" alt="user" style="width:50px; height:50px; "> <?php echo $row3['username'] ?></h1>
<p><?php echo $row2['created_at']?></p>
<article>
<p>
<?php echo $row2['content']?>
</p>
</article>
<p style="font-weight:bold">Recently Viewers:</p>
<article>
		<ul style="list-style:none;">
		<?php 
		if(mysqli_num_rows($result)>0&&($counterp<20))
		{
		    
		    while(($row2 = mysqli_fetch_assoc($result)))
		    {
		        $post_uid=$row2["uid"];
		        $q3="SELECT * FROM Users WHERE uid = '$post_uid'";
		        $r3=$db->query($q3);
		        $row3=$r3->fetch_assoc();
		        
		        $post_id = $row2["pid"];
		        $q4 = "SELECT * FROM Views WHERE pid = '$post_id'";
		        $r4=$db->query($q4);
		        $row4=$r4->fetch_assoc();
		        
		        ?>
		        <li><img src="uploads/<?php echo $row3['username']?>" alt="user" style="width:20px; height:20px; "> <?php echo $row4['user_viwed'] ?>, <?php echo $row4['time_viewed'] ?></li>
		    <?php 
		    }
		}

    
?>


			<li></li>
		</ul>
</article>
</form>
<p><a href="msg_list.php">Back to main page.</a></p>
</section>
<br/>

</div>
</body>

<?php
}
else
{
    echo "<h3>Please Login or Sign up</h3>";
    echo "<a href='main.php'>Login</a> <a href='sign_up.php'>Signup</a>";
    
}
?>

</html>