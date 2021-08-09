<?php
session_start();
$username = $_SESSION["uname"];
$db = new mysqli("localhost", "fan270", "1580Fjk", "fan270");
$errorMsg="";
$validateL = true;

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Private Message Board </title>
<link rel="stylesheet" type="text/css" href="external_style.css">

<script type="text/javascript" src="external.js"></script>  
<script>
function refresh(){
	var  xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("body").innerHTML = this.responseText; 
		    setTimeout("refresh()",5000);
		}
	}
	xmlhttp.open("GET","msg_list.php",true);
	xmlhttp.send(null);
}
</script>
</head>
<body style="background-color:#ededed;"  onload="refresh()" id="body">

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


<div class='container'>

<br/>
<section id="profile">
<h2><?php echo $username; ?></h2>
<hr>
<p><img src="uploads/<?php echo $username; ?>" alt="user" style="width:100px; height:100px;"></p>
<hr>
<p>More info</p>
</section>

<section id="post">
<h3>Create post</h3>
<article>
<p>What's on your mind?</p>
<a href="msg_creation.php"><button>Share</button></a>
<br/>
</article>
</section>
<br/>

<section id="reminder" class="content">
<h4>See the latest posts below</h4>
</section>

<br/>
<?php 
$con = mysqli_connect("localhost","fan270","1580Fjk","fan270");
$q2="SELECT * FROM Post ORDER BY created_at DESC";
$result=mysqli_query($con,$q2);
$counterp=0;
$row2=mysqli_fetch_assoc($result);




if(mysqli_num_rows($result)>0&&($counterp<20))
{
	
	while(($row2 = mysqli_fetch_assoc($result)))
	{
		$post_uid=$row2["uid"];
		$q0="SELECT * FROM Users WHERE uid = '$post_uid'";
		$r0=$db->query($q0);
		$row0=$r0->fetch_assoc();
		

	
		$q3 = "SELECT * FROM Post WHERE access_code = '$accessCode'";
		$r3 = mysqli_query($con,$q3);
		$row3 = $r3->fetch_assoc();;
		
		?>
         
         <section id="u0" class="content">
         <p><img src="uploads/<?php echo $row0['username']?>" style="width:50px; height:50px;"> <?php echo $row0['username'] ?></p>
                <p><?php echo $row2['created_at'] ?></p>
                <form id="user_1" method="post" action="msg_list.php">
                <input type="hidden" value="id">
                <table>
                <tr>
                <th><input type="password" name="accessCode" placeholder="access code(6 digits)"></th>
                <th><button type="submit">submit</button></th>
                </tr>
                <tr>
                <th><label id="code_msg" class="err_msg"><?php echo $errorMsg?></label></th>
                </tr>
                </table>
                </form>
				<article>
				<?php 
				if(isset($_POST["accessCode"]))
				{
				    $accessCode = trim($_POST["accessCode"]);
				    if($_POST["accessCode"]==$accessCode && $post_uid== $row0["uid"])
				    {
				
				    ?>
				    <p><a href='msg_display.php'><?php echo $row2['content'] ?></a></p>
				    <?php 
				    
				    $view_pid = $row2["pid"];
				    $view_uid = $row0["uid"];
				    $view_uname = $row0["username"];
				    $q4="INSERT INTO Views(pid,uid,user_viewed,time_viewed) VALUES('$view_pid','$view_uid','$view_uname')";
				    $r4=$db->query($q4);
				    /*
				    session_start();
				    $_SESSION["email"] = $row["email"];
				    $_SESSION["content"] = $row["content"];
				    $db->close();
				    */
				    
				    }		else
				    {
				        $errorMsg = "The access code is invalid!";
				    }
		}


				?>
				
                        </article>
                </section>
                <br/>

               
    <?php         
	}
}
       else
       {
       	echo"<section id='u0' class='content'><p>Nothing's posted here.</p></section>";
       }

?>

</div>


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
<script type = "text/javascript"  src = "code-r.js" ></script>
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