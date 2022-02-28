<?php
session_start(); // Création de la session
include "sessionFound.php";

/******
 * Page used to treat a new parameters value
 */
$SFileName = "";
$SFile = "";

if(isset($_GET['sfilename']) && isset($_GET['customer']))
	{
	$SFileName = $_GET['sfilename'];
	$SFile = simplexml_load_file("document/xmlFiles/".$SFileName) or die("Error");
	}
else
	{
	header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
	exit;
	}

if(isset($_POST['maxwaitingtime']))
	{
	$maxwaitingtime = $_POST['maxwaitingtime'];
	$SFile->topic->waitingqueue->maxtimeinqueue = $maxwaitingtime;
	$SFile->asXML("document/xmlFiles/".$SFileName);
	}

//To go back to the public holidays administration page
header("Location: mainpage.php?page=manageSettings&customer=".$_GET['customer']);
exit;

?>