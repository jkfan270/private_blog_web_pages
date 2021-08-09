<?php
session_start();

//If nobody is logged in, display login and signup page.
if(isset($_SESSION["email"]))
{
    //If somebody is logged in, display a welcome message
    echo "Welcome, logged in as:  " .$_SESSION['email']. "<br />" ;
    echo "<a href='msg_list.php'>Go to the Message List Page. OR </a>";
    echo "<a href='Logout.php'>Logout</a>";
}

else
{
    echo "<H3>Please Login or Sign up</h3>";
    echo "<a href='main.php'>Login</a> <a href='sign_up.php'>Signup</a>";
    
}
?>
<!DOCTYPE html>
<head>
<title>Index page with SignUp and Login</title>
</head>
<body>
</body>
</html>