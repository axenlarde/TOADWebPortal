<?php
include "sessionFound.php";
?>

<script type="text/javascript">

function manageHours(id)
	{
	window.location = "mainpage.php?page=manageHours&customer="+id;
	}

function manageHolidays(id)
	{
	window.location = "mainpage.php?page=manageHolidays&customer="+id;
	}

function manageSettings(id)
	{
	window.location = "mainpage.php?page=manageSettings&customer="+id;
	}

function validateSearch()
	{
	var search = document.getElementById("search").value;
	window.location = "mainpage.php?page=manageCustomers&search="+search;
	}

function searchOnKeyPress(event)
	{
	if (event.keyCode == 13 || event.which == 13)
		{
		var search = document.getElementById("search").value;
		window.location = "mainpage.php?page=manageCustomers&search="+search;
		}
	}

</script>


<?php
/** 
 * Page used to manage customers
 * We provide a search engine to find
 */
$lastSearch;
if(isset($_GET["search"]))
	{
	$lastSearch = $_GET["search"];
	$_SESSION['customerSearch'] = $_GET["search"];
	}
else
	{
	if(isset($_SESSION['customerSearch']))
		{
		$lastSearch = $_SESSION['customerSearch'];
		}
	}

$searchResult = array();
if(isset($lastSearch))
	{
	/****
	 * We fetch customer list
	 */
	//First we read the xml file
	$customerList = simplexml_load_file("document/customers.xml") or die("Error");
	
	foreach ($customerList->customers->customer as $customer)
		{
			if((is_int(stripos(((String)$customer->team).((String)$customer->customer),$lastSearch))) || $lastSearch == '')
			{
			$searchResult[] = $customer;
			}
		}
	}
else
	{
	$lastSearch = "Rechercher..";
	}

$MaxResult = 1200;
?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Gestion des clients</div></h3>

<div class="search">
<table>
	<tr>
		<td><input type="text" name="search" id="search" placeholder="<?php echo $lastSearch ?>" onkeypress="searchOnKeyPress(event)"></td>
		<td><button type="submit" onclick="validateSearch()">GO</button></td>
	</tr>
</table>
</div>

<?php
//We check if we ask for a research
if(isset($_SESSION['customerSearch']))
	{
	$customerCount = count($searchResult);
	
	if($customerCount > 0)
		{
		echo'<h3>Résultat de la recherche : </h3><hr>
		<div class="forwardlist">
		<table>
			<tr>
				<td><b>Team</b></td>
				<td><b>Client</b></td>
			</tr>
			';
		
		$index = 0;
		while(true)
			{
			if($index >= $MaxResult)
				{
				$index++;
				break;//Just a security
				}
			
			$customer = @$searchResult[$index];
			
			if(isset($customer))
				{
				echo '<tr>
		 				<td>'.$customer->team.'</div></td>
						<td>'.$customer->customer.'</div></td>
                        <td><div class="forwardaction"><input type="button" name="OpeningHours" value="Horaires" title="Horaires" onclick="manageHours(\''.$customer->name.'\')"></div></td>
                        <td><div class="forwardaction"><input type="button" name="PublicHoliday" value="Jours de fermeture" title="Jours de fermeture" onclick="manageHolidays(\''.$customer->name.'\')"></div></td>
						<td><div class="forwardaction"><input type="button" name="Settings" value="Paramètres" title="Paramètres" onclick="manageSettings(\''.$customer->name.'\')"></div></td>
		 			</tr>
					';
				}
			else
				{
				break;
				}
			$index++;
			}
		
		echo'</table>
		</div>';
		
		if($index > $MaxResult)
			{
			echo "<h4>Désolé, il n'est pas possible d'afficher plus d'entrée (max ".$MaxResult.")</h4>";
			}
		}
	}
else
	{
	//We display the default page without in progress search
	echo '<br><br>&nbspAucune recherche en cours';
	}
?>




