
<script type="text/javascript">

function checkNewDate(formulaire)
	{
	if(document.getElementById("newDateInput").value == "")
		{
		alert("Veuillez entrer une date");
		}
	else
		{
		formulaire.submit();
		}
	}

function checkRemoveDate(index, formulaire)
	{
	//We add the date to remove index to the url
	var url = document.getElementById("holidaysForm").action;
	document.getElementById("holidaysForm").action = url+"&dateToRemove="+index;
	
	formulaire.submit();
	}
	
</script>


<?php
/** 
 * Page used to set the closure days
 */
include "branchFound.php";

/****
 * We get the xml parameters to display
 */
//First we read the xml file
$xml = simplexml_load_file("document/xmlFiles/OverallSettings.xml") or die("Error");

//Then we find the good branch to display
$branchIndex = 1;
$branchName = "";

try 
	{
	//The limit is 10 branches
	for($i = 0; $i<10; $i++)
		{
		$branchName = "branch".$branchIndex;
		if(($xml->$branchName->name) == $_SESSION['login'][3])
			{
			break;
			}
		$branchIndex++;
		}
	}
catch(Exception $exc)
	{
	//The branch doesn't exist
	header("Location: mainpage.php");
	exit;
	}

//Then we get the holidays file name
$holidaysFileName = $xml->$branchName->publicholiday;
$PTFileName = $xml->$branchName->prioritytag;

//Finally we open this file
$holidaysFile = simplexml_load_file("document/xmlFiles/".$holidaysFileName) or die("Error");
$PTFile = simplexml_load_file("document/xmlFiles/".$PTFileName) or die("Error");

//We detect here if there are other opening hours files to use in the tag file
$PublicHolidayFileNameTag = "";
$OtherScheduleFound = false;

try
	{
	//The limit is 20 tags
	for($i = 1; $i<20; $i++)
		{
		$TagName = "tag".$i;
		$PublicHolidayFileNameTag = $PTFile->$TagName->publicholiday;
		if($PublicHolidayFileNameTag == null || ($PublicHolidayFileNameTag == ""))
			{
			//Nothing found
			}
		else
			{
			$OtherScheduleFound = true;
			}
		}
	}
catch(Exception $exc)
	{
	//The tag doesn't exist
	header("Location: mainpage.php");
	exit;
	}


?>
<h3><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Administration des jours de fermeture</h3>
<br>
<?php 
if($OtherScheduleFound)
	{
	echo"<div style=\"text-align: right\">Pour gérer les jours de fermeture des autres catégorie cliquer 
	<a href=\"mainpage.php?page=selectionPublicHolidaysTag\">ici</a>
	</div>";
	}
else
	{
	echo"<br>";
	}
?>
<h4>Liste actuelle des jours de fermeture : </h4>
<form name="holidaysForm" id="holidaysForm" method=post action="holidaysTreatment.php?holidaysfilename=<?php echo $holidaysFileName?>">
	<table>
	<?php
	/**
	 * First we display the current closure days
	 */
	$index = 1;
	while(true)
		{
		$closureDayName = "PUBLICHOLIDAY".$index;
		$closureday = $holidaysFile->$closureDayName;
		
		if(isset($holidaysFile->$closureDayName))
			{
			echo "
	 			<tr>
	 				<td>Jour ".$index." : <b>".$closureday."</b></td>
					<td><input type=\"button\" name=\"removeDate\" value=\"-\" title=\"Supprimer\" onclick=\"checkRemoveDate(".$index.",this.form)\"></td>
	 			</tr>
 				";
			}
		else
			{
			break;
			}
		
		if($index>500)break;//Just a security	
		$index++;
		}
	
	?>
	<tr><td>&nbsp</td></tr>
	<tr>
		<td>Ajouter une nouvelle date : </td>
		<td><input type="text" name="newDateInput" id="newDateInput"><input type="button" name="Ajouter" value="Ajouter" onclick="checkNewDate(this.form)"></td>
	</tr>
</table>
</form>



