<?php
include "sessionFound.php";
?>
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
	
function searchOnKeyPress(event, formulaire)
	{
	if (event.keyCode == 13 || event.which == 13)
		{
		checkNewDate(formulaire);
		}
	}
	
$(function()
	{
    $("#newDateInput").datepicker({dateFormat: 'd/m'});
    $("#newDateInput").datepicker.regional['fr'];
  	});
</script>


<?php
/** 
 * Page used to set the closure days
 */
$PHFileName = "";
if(isset($_GET["script"]) && ($_GET["script"] != ''))
	{
	//First we read the xml file
	$scriptList = simplexml_load_file("document/scripts.xml") or die("Error");
	//Then we take only the file we need for the given script
	foreach ($scriptList->scripts->script as $script)
		{
		if(strcmp($script->name,$_GET["script"]) == 0)
			{
			$PHFileName = $script->publicholiday;
			break;
			}
		}
	}

if($PHFileName == "")
	{
	header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
	exit;
	}

$PHFile = simplexml_load_file("document/xmlFiles/".$PHFileName) or die("Error");

?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>
>
<a href="mainpage.php?page=manageScripts">Gestion des scripts</a>
>Gestion des jours de fermeture : <?php echo $_GET["script"];?></div></h3>
<br>
<h4>Liste actuelle des jours de fermeture : </h4>
<form name="holidaysForm" id="holidaysForm" method=post action="<?php echo "holidaysTreatment.php?phfilename=".$PHFileName."&script=".$_GET["script"];?>">
<table>
	<?php
	/**
	 * First we display the current closure days
	 */
	$index=0;
	foreach($PHFile->day as $day)
	   {
	   	echo "
	 			<tr>
	 				<td>".$day['date']." : <b>".$day."</b></td>
					<td><div class=\"forwardstatusnok\"><input type=\"button\" name=\"removeDate\" value=\"X\" title=\"Supprimer\" onclick=\"checkRemoveDate(".$index.",this.form)\"></div></td>
					<td></td>
	 			</tr>
 				";
	   	$index++;
	   }
	?>
	<tr><td>&nbsp</td><td></td><td></td></tr>
	<tr>
		<td>Ajouter une nouvelle date : </td>
		<td><div class="search"><input type="text" name="newDateInput" id="newDateInput"></div></td>
		<td>Description : </td>
		<td><div class="search"><input type="text" name="newDateInputDesc" id="newDateInputDesc" onkeypress="searchOnKeyPress(event, this.form)"></div></td>
		<td><div class="forwardaction"><input type="button" name="Ajouter" value="Ajouter" onclick="checkNewDate(this.form)"></div></td>
	</tr>
</table>
</form>



