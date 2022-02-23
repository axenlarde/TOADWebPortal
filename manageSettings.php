<?php
include "sessionFound.php";
?>
<script type="text/javascript">

function verif(formulaire)
	{
	formulaire.submit();
	}
</script>


<?php
/** 
 * Page used to set the global parameters
 */
$SFileName = "";
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
			$SFileName = $customer->settings;
			$selectedCustomer = $customer;
			break;
			}
		}
	}

if($SFileName == "")
	{
	header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
	exit;
	}

$SFile = simplexml_load_file("document/xmlFiles/".$SFileName) or die("Error");
?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>
>
<a href="mainpage.php?page=manageCustomers">Gestion des clients</a>
>Gestion des paramètres : <?php $selectedCustomer->customer;?></div></h3>
<br>
<br>
<form name="settingsForm" id="settingsForm" method=post action="<?php echo "settingsTreatment.php?sfilename=".$SFileName."&customer=".$_GET["customer"];?>" onkeypress="submitForm(event)">
	<table>
		<tr>
			<td>Temps d'attente maximum : </td>
			<td><b><?php echo intval($SFile->branch->maxwaitingtime)?></b> secondes</td>
			<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
			<td>Nouvelle valeur : </td>
			<td><select name="maxwaitingtime" id="maxwaitingtime">
				<?php
				for($i=10; $i<=300; $i+=5)
					{
					echo "<option value=\"".$i."\">".$i."</option>";
					}
				?>
			</select></td>
			<td><input type="button" name="modifier" value="modifier" onclick="verif(this.form)"></td>
		</tr>
	</table>
</form>



