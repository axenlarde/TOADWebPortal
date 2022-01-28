<?php
session_start(); // Création de la session

/******
 * Page used to treat a new parameters value
 */

include "branchFound.php";

$OHFileName = "";
$OHFile = "";
$AdminPage = "adminOpeningHours";
$TagName = "";
$fileFound = false;
$dayIndex = 2; //Monday in the xml file
$dayNames = array("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");


if(isset($_GET['ohfilename']))
	{
	$OHFileName = $_GET['ohfilename'];
	$OHFile = simplexml_load_file("document/xmlFiles/".$OHFileName) or die("Error");
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
	$newValues = array
					(
					array
						(//Monday
						"start1" => "",
						"end1" => "",
						"start2" => "",
						"end2" => "",
						),
					array
						(//Tuesday
						"start1" => "",
						"end1" => "",
						"start2" => "",
						"end2" => "",
						),
					array
						(//Wednesday
						"start1" => "",
						"end1" => "",
						"start2" => "",
						"end2" => "",
						),
					array
						(//Thursday
						"start1" => "",
						"end1" => "",
						"start2" => "",
						"end2" => "",
						),
					array
						(//Firday
						"start1" => "",
						"end1" => "",
						"start2" => "",
						"end2" => "",
						),
					array
						(//Saturday
						"start1" => "",
						"end1" => "",
						"start2" => "",
						"end2" => "",
						),
					array
						(//Sunday
						"start1" => "",
						"end1" => "",
						"start2" => "",
						"end2" => "",
						),
					);
		
	//We now get the values from the form
	for($i = 0; $i<7; $i++)
		{
		$newValues[$i]["start1"] = $_POST[$dayNames[$i]."StartHours1"].":".$_POST[$dayNames[$i]."StartMinutes1"].":00";
		$newValues[$i]["end1"] = $_POST[$dayNames[$i]."EndHours1"].":".$_POST[$dayNames[$i]."EndMinutes1"].":00";
		$newValues[$i]["start2"] = $_POST[$dayNames[$i]."StartHours2"].":".$_POST[$dayNames[$i]."StartMinutes2"].":00";
		$newValues[$i]["end2"] = $_POST[$dayNames[$i]."EndHours2"].":".$_POST[$dayNames[$i]."EndMinutes2"].":00";
		}
	
	//We write them down in the xml file
	$dayIndex = 2;
	for($i = 0; $i<7; $i++)
		{
		if($dayIndex == 8)$dayIndex = 1;
		$dayName = "DAY".$dayIndex;
		
		$OHFile->$dayName->START1 = $newValues[$i]["start1"];
		$OHFile->$dayName->END1 = $newValues[$i]["end1"];
		$OHFile->$dayName->START2 = $newValues[$i]["start2"];
		$OHFile->$dayName->END2 = $newValues[$i]["end2"];
		
		$dayIndex++;
		}
	
	$OHFile->asXML("document/xmlFiles/".$OHFileName);
	echo $OHFileName."yeah : Done !";
	}

//To go back to the global paramaters administration page
$TagTemp = "";
if($TagName != "")$TagTemp="&tag=".$TagName;
header("Location: mainpage.php?page=".$AdminPage.$TagTemp);
exit;

?>