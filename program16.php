<!DOCTYPE html>
<html>
<body>
<h2>Odd or Even</h2>
<form method="get">
Enter a number:
<input type="text"
name="num">
<input type="submit"
value="Check">
</form>
<?php
if(isset($_GET['num']))
{
	$n=$_GET['num'];
	if($n % 2==0)
	{
		echo"$n is an even number";
	}
	else
	{
		echo"$n is an odd number";
	}
}
?>
</body>
</html>
