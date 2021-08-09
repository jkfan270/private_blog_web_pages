<?php
$validateP = true;
session_start();
$reg_Bday = "/^\d{1,2}\/\d{1,2}\/\d{4}$/";
$dateS = "mm/dd/yyyy";
//$reg_Etime="((1[0-2]|0?[1-9]):([0-5][0-9]) ?([AaPp][Mm]))";

$errorMsg=" ";

if(isset($_SESSION["email"]))
{
    $email=$_SESSION["email"];
    
    if (isset($_POST["submittedL"]) && $_POST["submittedL"])
    {
    
    $ContentP = trim($_POST["ContentP"]);
    $accessCodeP = trim($_POST["accessCodeP"]);
    $dateS = trim($_POST["dateS"]);
    $expiryTimeP = trim($_POST["expiryTimeP"]);
    
    $db = new mysqli("localhost", "fan270", "1580Fjk", "fan270");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }
    $q1 = "SELECT * FROM Users WHERE email='$email'";
    $r1 = $db->query($q1);
    $row = $r1->fetch_assoc();
    
    $q2 = "SELECT * FROM Post where content = '$ContentP'";
    
    $r2 = $db->query($q2);
    

    
    
    
    if(strlen($ContentP) > 1000 || strlen($ContentP)<=0)
    {
        $validateP = false;

    }
    
    if(strlen($accessCodeP) > 6|| strlen($ContentP)<=0)
    {
        $validateP = false;

    }
    
    $bdayMatch = preg_match($reg_Bday, $dateS);
    if($dateS == null || $dateS == "" || $bdayMatch == false)
    {
        $validateP = false;
    }
    
    
    if($validateP == true)
    {
        
        $dateFormatS = date("Y-m-d", strtotime($dateS));
        $post_uid = $row["uid"];
        $post_user = $row["username"];
        $_SESSION["content"] = $row["content"];

        $q0 = "INSERT INTO Post(uid,post_user,content,access_code,expiry_date,expiry_time) VALUES('$post_uid','$post_user','$ContentP','$accessCodeP','$dateFormatS','$expiryTimeP')";
        
        
        $r0 = $db->query($q0);
        if ($r0 === true)
        {
            header("Location:msg_list.php");
            $db->close();
            exit();
        }
    }
    else
    {
        $errorMsg = "The content you type is invalid, please try agian.";
        $db->close();
    }
}


?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type= "text/css" href="external_style.css">
<script type="text/javascript" src="external.js"></script>  
<meta charset="UTF-8">
<title>Create Post</title>
</head>
<body style="background-color:#ededed;">

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

<section id="post" style="text-align:center;">
<h2>Create new post</h2>

<form id="textForm" method="post" action="msg_creation.php" >
<input type="hidden" name="submittedL" value="1"/>
<table style="margin:auto;">
	<tr><td><textarea onkeyup="size(this);" style="height:100px;width:320px;" placeholder="What's happending?" name="ContentP"></textarea></td></tr>
	<tr><td><label id="text_msg" class="err_msg"></label></td></tr>
	<tr><td><small class="text_msg" >You can type maximum 1000 words. Remaining <span id="check_text" class="text_msg">1000</span></small></td></tr>
<tr><td>Access code: </td></tr>
<tr><td><input type="text" name="accessCodeP" /></td></tr>
<tr><td><label class="err_msg"></label></td></tr>  
<tr><td>Expire date: </td></tr>
<tr><td><input type="text" name="dateS" placeholder="(eg. mm/dd/yyyy)"/></td></tr>
<tr><td><label class="err_msg"></label></td></tr>     
<tr><td>Expire time: </td></tr>
<tr><td><input type="text" name="expiryTimeP" placeholder="(eg. 16:12:00)"/></td></tr>
<tr><td><label class="err_msg"></label></td></tr>
<tr><td><label class="err_msg"><?php echo $errorMsg?></label></td></tr>

<tr><td><button type="submit">Post</button></td></tr>


</table>
</form>
<br/>
	<p><a href="msg_list.php">Back to the message list page.</a></p>
</section>
<br/>

</div>
  
<script type="text/javascript" src="code-r.js"></script>  
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