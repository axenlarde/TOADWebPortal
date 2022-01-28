<?php
session_start(); // Création de la session

/******
 * Page used to treat a new parameters value
 */

include "branchFound.php";

$holidaysFileName = "";
$holidaysFile = "";
$AdminPage = "adminHolidays";
$TagName = "";
$fileFound = false;

if(isset($_GET['holidaysfilename']))
	{
	$holidaysFileName = $_GET['holidaysfilename'];
	$holidaysFile = simplexml_load_file("document/xmlFiles/".$holidaysFileName) or die("Error");
	$fileFound = true;
	}
	
if(isset($_GET['adminpage']))
	{
	$AdminPage = $_GET['adminpage'];
	}
	
if(isset($_GET['tag']))
	{
	$TagName = $_GET['tag'];
	}
	
if($fileFound)
	{
	/**
	 * We find the last index and we extract all the closure days
	 */
	$index = 1;
	$closureDayName = "";
	$closureDays = array();
	
	while(true)
		{
		$closureDayName = "PUBLICHOLIDAY".$index;
		$closureday = $holidaysFile->$closureDayName;
	
		if(isset($holidaysFile->$closureDayName))
			{
			$closureDays[$index-1] = $holidaysFile->$closureDayName;
			}
		else
			{
			break;
			}
	
		if($index>500)break;//Just a security
		$index++;
		}	
	
	if((isset($_POST["newDateInput"])) && ($_POST["newDateInput"] != ""))
		{
		/**
		 * We add the new date
		 */
		$holidaysFile->addChild($closureDayName, $_POST["newDateInput"]);
		
		$NewDoc = new DOMDocument();
		$NewDoc->preserveWhiteSpace = false;
		$NewDoc->formatOutput = true;
		
		$NewDoc->loadXML($holidaysFile->asXML());
		$NewDoc->save("document/xmlFiles/".$holidaysFileName);
		}
	else if((isset($_GET["dateToRemove"])) && ($_GET["dateToRemove"] != ""))
		{
		/**
		 * From here we write a brand new XML file without the removed date
		 */
		$xmlstr = "<PUBLICHOLIDAYS></PUBLICHOLIDAYS>";
		$newXMLFile = new SimpleXMLElement($xmlstr);
		$index = 1;
		
		for($i=0; $i<count($closureDays); $i++)
			{
			if(($i+1) != $_GET["dateToRemove"])
				{
				$newXMLFile->addChild("PUBLICHOLIDAY".$index, $closureDays[$i]);
				$index++;
				}
			}
		
		$NewDoc = new DOMDocument();
		$NewDoc->preserveWhiteSpace = false;
		$NewDoc->formatOutput = true;
		
		$NewDoc->loadXML($newXMLFile->asXML());
		
		if(count($closureDays)<=1)
			{
			//We do not delete the last entry
			}
		else
			{
			$NewDoc->save("document/xmlFiles/".$holidaysFileName);
			}
		//$NewDoc->save($holidaysFileName);
		}
	}

//To go back to the global paramaters administration page
$TagTemp = "";
if($TagName != "")$TagTemp="&tag=".$TagName;
header("Location: mainpage.php?page=".$AdminPage.$TagTemp);
exit;

?>