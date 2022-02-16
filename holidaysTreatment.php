<?php
session_start(); // Création de la session
include "sessionFound.php";

/******
 * Page used to update closure days
 */
$PHFileName = "";
$PHFile = "";

if(isset($_GET['phfilename']) && isset($_GET['script']))
	{
	$PHFileName = $_GET['phfilename'];
	$PHFile = simplexml_load_file("document/xmlFiles/".$PHFileName) or die("Error");
	}
else
	{
	header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
	exit;
	}
	
	
if((isset($_POST["newDateInput"])) && ($_POST["newDateInput"] != ""))
	{
	/**
	 * We add the new date
	 */
	$newDay = $PHFile->addChild("day", $_POST["newDateInputDesc"]);
	$newDay->addAttribute("date", $_POST["newDateInput"]);
	
	$NewDoc = new DOMDocument();
	$NewDoc->preserveWhiteSpace = false;
	$NewDoc->formatOutput = true;
	
	$NewDoc->loadXML($PHFile->asXML());
	$NewDoc->save("document/xmlFiles/".$PHFileName);
	}
else if((isset($_GET["dateToRemove"])) && ($_GET["dateToRemove"] != ""))
	{
	/**
	 * From here we remove the date from the file
	 */
	$dom=dom_import_simplexml($PHFile->day[intval($_GET["dateToRemove"])]);
	$dom->parentNode->removeChild($dom);
	
	$NewDoc = new DOMDocument();
	$NewDoc->preserveWhiteSpace = false;
	$NewDoc->formatOutput = true;
	$NewDoc->loadXML($PHFile->asXML());
	$NewDoc->save("document/xmlFiles/".$PHFileName);
	}

//To go back to the public holidays administration page
header("Location: mainpage.php?page=manageHolidays&script=".$_GET['script']);
exit;

?>