<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="w3-theme-indigo" >
<style>

a:link    {color:white; background-color:transparent; text-decoration:none}
a:visited {color:white; background-color:transparent; text-decoration:none}
a:hover   {color:white; background-color:transparent; text-decoration:none}
a:active  {color:white; background-color:transparent; text-decoration:none}
         
.wrapper {
    /* Ensure wrapper contains the columns, even when they are floated, by
       creating a new Block formatting context */
    overflow: hidden;
	

}

.column {
    /* Ensure we have some margins when the columns are collapsed, or 
       other content is displayed below. */
    margin-bottom: 2em;
	margin-left: 1%;
	margin-right: 1% ;
	 float: left ;

}

@media screen and (min-width:1000px )  {
    .wrapper .column {
        width: 30%;
        margin:1%;
    }

}


</style>
<?php 
$pwd1="";
$usr=""; 
$invalid=0;
?>
<?php 
ob_start();
if(isset($_POST['sub1']))
{
$servername = "localhost";
$username = "root";
$password = "";
$dbname="cp_users";
$pwd1=$_POST["pwd"] ;
$usr=$_POST["usr"];
$name="";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
   // die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM userdata WHERE email='$usr' AND password='$pwd1' ";

$result = $conn->query($sql);
if ($result-> num_rows > 0) {
	
	session_start();
	$row = mysqli_fetch_assoc($result);
	$name=$row['first_name'];
	$college=$row['college_name'];
	$usrride=$row['ride_opts'];
	$_SESSION['name']=$name;
	$_SESSION['college']=$college;
	$_SESSION['usrride']=$usrride;
    header("location: profile.php");
 }
 else
 {
	 $invalid=1;
 }
$conn->close();
}
ob_end_flush(); 
?>
<body>
<header id="fc" class="w3-container indigo">
  <i class="material-icons w3-opennav w3-xlarge">menu</i> 
  <a href="homepage.php">
  <h1>OUR FIRST ENDEAVOUR</h1>
  </a>
  </header>
<div class="w3-topnav w3-padding indigo">
  <a href="whycarpooling.php">What is Carpooling ?</a>
  <a href="#link1">How to</a>
  <a href="newaboutus.php">About Us</a>
  <a href="#link3">Contact Us</a>
  <a href="signup.php"> Sign Up </a>
 
</div> 
<div class=" w3-container w3-padding  wrapper " >
<div  class=" w3-card-4  w3-container column " >
<p class="w3-text-indigo" style="font-size:25px">
Weather Forcast :
<a href="http://www.accuweather.com/en/us/new-york-ny/10007/weather-forecast/349727" class="aw-widget-legal">
</a><div id="awcc1437587568894" class="aw-widget-current"  data-locationkey="" data-unit="c" data-language="en-us" data-useip="true" data-uid="awcc1437587568894"></div><script type="text/javascript" src="http://oap.accuweather.com/launch.js"></script>
<br>
<br>
<br><br><br><br>
</p>
</div>
<div class="w3-container w3-card-4 column" >
<form class=" w3-container"  method="post">
  <h2 class="w3-text-indigo">Login</h2>
  <div class="w3-group">      
    <input class="w3-input " type="text" name="usr" required>
    <label class="w3-label" id="usrname" >email</label>
  </div>
  <div class="w3-group">      
    <input class="w3-input" type="password" name="pwd" required>
    <label class="w3-label" id="usrpwd">Password</label>
  </div>
  <br><br>
  <button class="w3-btn w3-theme-indigo indigo" type="submit" name="sub1"> Log in </button>
  <br><br>
</form>
</div>
<div class=" w3-card-4 column w3-container" >
<p class="w3-text-indigo" style="font-size:19px">

<q>
The price of success is hard work, dedication to the job at hand, and the determination that whether we win or lose, we have applied the best of ourselves to the task at hand.
<br>
-Vince Lombardi
</q>
<br>
<br>
<br><br><br><br><br><br>
</p>
</div>
</div>
<div class="w3-container w3-padding wrapper">
<div class=" w3-card-4  w3-container column " >
<p class="w3-text-indigo" style="font-size:20px">
<q>
The price of success is hard work, dedication to the job at hand, and the determination that whether we win or lose, we have applied the best of ourselves to the task at hand.
<br>
-Vince Lombardi
</q>
</p>
</div>
<div class=" w3-card-4  w3-container column " >
<p class="w3-text-indigo" style="font-size:20px">
<q>
The price of success is hard work, dedication to the job at hand, and the determination that whether we win or lose, we have applied the best of ourselves to the task at hand.
<br>
-Vince Lombardi
</q>
</p>
</div>
<div class=" w3-card-4  w3-container column " >
<p class="w3-text-indigo" style="font-size:20px">
<q>
The price of success is hard work, dedication to the job at hand, and the determination that whether we win or lose, we have applied the best of ourselves to the task at hand.
<br>
-Vince Lombardi
</q>
</p>
</div>
</div>
<div class="w3-container w3-theme-light w3-text-indigo" style="position:relative">
<a href="#fc" class="w3-text-indigo w3-right" style="font-size:2em">^</a>
  <p class="w3-text-indigo">Le Gang</p>
</div>
</body>
<?php
if($invalid==1) 
echo "<script> var usr= document.getElementById(\"usrname\").style.color=\"red\" ; var pwd= document.getElementById(\"usrpwd\").style.color=\"red\" ; alert(\"Invalid username or password\");</script> " ;

?>
</html> 