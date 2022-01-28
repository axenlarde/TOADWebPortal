<?php
$sessionFound = false;
$url = 'http://127.0.0.1:8092/TOAD/';

if(isset($_SESSION['login']))
	{
	if(!empty($_SESSION['login']))
		{
		$sessionFound = true;
		}
	}

if(!$sessionFound)
	{
	//It means that there is a problem. We should go back to the login page
	$_SESSION = array();
	header("Location: mainpage.php");
	exit;
	}

//Temp
$_SESSION['securitytoken'] = "Toto";
//Temp

?>