<?php
include "sessionFound.php";
?>

<script type="text/javascript">

function verif(formulaire)
	{
	formulaire.submit();
	}

function closeThisDay(formulaire, dayName)
	{
	document.getElementById(dayName+"StartHours1").value = "00";
	document.getElementById(dayName+"StartMinutes1").value = "00";
	document.getElementById(dayName+"EndHours1").value = "00";
	document.getElementById(dayName+"EndMinutes1").value = "00";
	document.getElementById(dayName+"StartHours2").value = "00";
	document.getElementById(dayName+"StartMinutes2").value = "00";
	document.getElementById(dayName+"EndHours2").value = "00";
	document.getElementById(dayName+"EndMinutes2").value = "00";

	formulaire.submit();
	}
</script>


<?php
/** 
 * Page used to set the opening hours
 */
$OHFileName = "";
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
			$OHFileName = $customer->openinghours;
			$selectedCustomer = $customer;
			break;
			}
		}
	}

if($OHFileName == "")
	{
	header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
	exit;
	}

$OHFile = simplexml_load_file("document/xmlFiles/".$OHFileName) or die("Error");
?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>
>
<a href="mainpage.php?page=manageCustomers">Gestion des clients</a>
>Gestion des horaires d'ouverture : <?php echo $selectedCustomer->customer;?></div></h3>
<br>
<table>
	<?php
	$dayIndex = 2; //Monday in the xml file
	$dayNames = array("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
	
	for($j=0; $j<7; $j++)
		{
		//We display each day	
		
		if($dayIndex == 8)$dayIndex = 1;
		$dayName = "DAY".$dayIndex;
		
		$start1 = $OHFile->$dayName->START1;
		$end1 = $OHFile->$dayName->END1;
		$start2 = $OHFile->$dayName->START2;
		$end2 = $OHFile->$dayName->END2;
		
		$tabStart1 = explode(":", $start1);
		$start1 = $tabStart1[0].":".$tabStart1[1];
		$tabStart2 = explode(":", $start2);
		$start2 = $tabStart2[0].":".$tabStart2[1];
		$tabEnd1 = explode(":", $end1);
		$end1 = $tabEnd1[0].":".$tabEnd1[1];
		$tabEnd2 = explode(":", $end2);
		$end2 = $tabEnd2[0].":".$tabEnd2[1];
		
		if($start1 == "00:00")
			{
			echo "<tr><td><b>".$dayNames[$j]."</b> : </td><td>Fermé</td>";
			}
		else
			{
			echo "
				<tr>
					<td><b>".$dayNames[$j]."</b> :</td>
					<td>".$start1."</td>
					<td> à </td>
					<td>".$end1."</td>
				";
				
			if($start2 == "00:00")
				{
				//echo "</tr>";
				}
			else
				{
				echo "
					<td> puis de </td>
					<td>".$start2."</td>
					<td> à </td>
					<td>".$end2."</td>
					";
				}
			}
		
		echo "</tr>";
		
		$dayIndex++;
		}
	?>
</table>
<form name="openingHoursForm" id="openingHoursForm" method=post action="<?php echo "hoursTreatment.php?ohfilename=".$OHFileName."&customer=".$_GET["customer"];?>" onkeypress="submitForm(event)">
	<br>
	<br>
	<b>Nouvelles valeurs :</b>
	<table>
	
	<?php
	$dayIndex = 2; //We start with monday
	
	/**
	 * We now propose to update the value
	 */
	
	for($j=0; $j<7; $j++)
		{
		//We display each day
	
		if($dayIndex == 8)$dayIndex = 1;
		$dayName = "DAY".$dayIndex;
	
		$start1 = $OHFile->$dayName->START1;
		$end1 = $OHFile->$dayName->END1;
		$start2 = $OHFile->$dayName->START2;
		$end2 = $OHFile->$dayName->END2;
	
		$tabStart1 = explode(":", $start1);
		$tabStart2 = explode(":", $start2);
		$tabEnd1 = explode(":", $end1);
		$tabEnd2 = explode(":", $end2);
		
		echo "
			<td><b>".$dayNames[$j]."</b> :</td>
			<td><select name=\"".$dayNames[$j]."StartHours1\" id=\"".$dayNames[$j]."StartHours1\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=23; $i++)
			{
			if($i==$tabStart1[0])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td>:</td>
			<td><select name=\"".$dayNames[$j]."StartMinutes1\" id=\"".$dayNames[$j]."StartMinutes1\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=59; $i++)
			{
			if($i==$tabStart1[1])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td> à </td>
			<td><select name=\"".$dayNames[$j]."EndHours1\" id=\"".$dayNames[$j]."EndHours1\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=23; $i++)
			{
			if($i==$tabEnd1[0])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td>:</td>
			<td><select name=\"".$dayNames[$j]."EndMinutes1\" id=\"".$dayNames[$j]."EndMinutes1\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=59; $i++)
			{
			if($i==$tabEnd1[1])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td> puis de </td>
			<td><select name=\"".$dayNames[$j]."StartHours2\" id=\"".$dayNames[$j]."StartHours2\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=23; $i++)
			{
			if($i==$tabStart2[0])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td>:</td>
			<td><select name=\"".$dayNames[$j]."StartMinutes2\" id=\"".$dayNames[$j]."StartMinutes2\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=59; $i++)
			{
			if($i==$tabStart2[1])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td> à </td>
			<td><select name=\"".$dayNames[$j]."EndHours2\" id=\"".$dayNames[$j]."EndHours2\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=23; $i++)
			{
			if($i==$tabEnd2[0])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td>:</td>
			<td><select name=\"".$dayNames[$j]."EndMinutes2\" id=\"".$dayNames[$j]."EndMinutes2\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=59; $i++)
			{
			if($i==$tabEnd2[1])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
				<td><input type=\"button\" name=\"reset\" value=\"Fermer ce jour\" onclick=\"closeThisDay(this.form,'".$dayNames[$j]."')\"></td>
			";
			
		echo "</tr>";
	
		$dayIndex++;
		}
	?>
	<tr><td><input type="button" name="modifier" value="Valider" onclick="verif(this.form)"></td></tr>
	</table>
</form>



