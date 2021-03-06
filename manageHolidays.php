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
$selectedCustomer;
if(isset($_GET["customer"]) && ($_GET["customer"] != ''))
	{
	//First we read the xml file
	$customerList = simplexml_load_file("document/customers.xml") or die("Error");
	//Then we take only the file we need for the given customer
	foreach ($customerList->customers->customer as $customer)
		{
		if(strcmp($customer->name,$_GET["customer"]) == 0)
			{
			$PHFileName = $customer->publicholiday;
			$selectedCustomer = $customer;
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
<a href="mainpage.php?page=manageCustomers">Gestion des Client</a>
>Gestion des jours de fermeture : <?php echo $selectedCustomer->customer;?></div></h3>
<br>
<h4>Liste actuelle des jours de fermeture : </h4>
<form name="holidaysForm" id="holidaysForm" method=post action="<?php echo "holidaysTreatment.php?phfilename=".$PHFileName."&customer=".$_GET["customer"];?>">
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



