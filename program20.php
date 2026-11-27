<?php
$conn=mysqli_connect("localhost","root","","logindb");
if(!$conn){
	die("Connection failed");
}
$message="";
if(isset($_POST['submit'])){
	$username=$_POST['username'];
	$password=$_POST['password'];
	$sql="SELECT *FROM users WHERE username='$username'";
	$result=mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_assoc($result);
		if($password==$row['password']){
			header("Location:welcomenew.php");
			exit();
		}
		else{
			$message="incorrect password";
		}
	}
	else{
		$message="incorrect username and password";
	}
}
?>
<html>
<head>
<title>Login page</title>
</head>
<body>
<h2>Login Form</h2>
<form method="post" action="">
Username:
<input type="text" name="username" required>
<br><br>
Password:
<input type="password" name="password" required>
<br><br>
<input type="submit" name="submit" value="Login">
</form>
<?php
echo $message;
?>
<body>
</html>
