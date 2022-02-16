<?php
include "sessionFound.php";
?>

<script type="text/javascript">

function manageHours(id)
	{
	window.location = "mainpage.php?page=manageHours&script="+id;
	}

function manageHolidays(id)
	{
	window.location = "mainpage.php?page=manageHolidays&script="+id;
	}

function manageSettings(id)
	{
	window.location = "mainpage.php?page=manageSettings&script="+id;
	}

function validateSearch()
	{
	var search = document.getElementById("search").value;
	window.location = "mainpage.php?page=manageScripts&search="+search;
	}

function searchOnKeyPress(event)
	{
	if (event.keyCode == 13 || event.which == 13)
		{
		var search = document.getElementById("search").value;
		window.location = "mainpage.php?page=manageScripts&search="+search;
		}
	}

</script>


<?php
/** 
 * Page used to manage scripts
 * We provide a search engine to find
 */
$lastSearch;
if(isset($_GET["search"]))
	{
	$lastSearch = $_GET["search"];
	$_SESSION['scriptSearch'] = $_GET["search"];
	}
else
	{
	if(isset($_SESSION['scriptSearch']))
		{
		$lastSearch = $_SESSION['scriptSearch'];
		}
	}

$searchResult = array();
if(isset($lastSearch))
	{
	/****
	 * We fetch script list
	 */
	//First we read the xml file
	$scriptList = simplexml_load_file("document/scripts.xml") or die("Error");
	
	foreach ($scriptList->scripts->script as $script)
		{
		if((is_int(stripos($script->name,$lastSearch))) || $lastSearch == '')
			{
			$searchResult[] = $script;
			}
		}
	}
else
	{
	$lastSearch = "Rechercher..";
	}

$MaxResult = 1200;
?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Gestion des scripts</div></h3>

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
if(isset($_SESSION['scriptSearch']))
	{
	$scriptCount = count($searchResult);
	
	if($scriptCount > 0)
		{
		echo'<h3>Résultat de la recherche : </h3><hr>
		<div class="forwardlist">
		<table>
			<tr>
				<td><b>Nom</b></td>
				<td><b>Description</b></td>
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
			
			$script = @$searchResult[$index];
			
			if(isset($script))
				{
				echo '<tr>
		 				<td>'.$script->name.'</div></td>
						<td>'.$script->desc.'</div></td>
                        <td><div class="forwardaction"><input type="button" name="OpeningHours" value="Horaires" title="Horaires" onclick="manageHours(\''.$script->name.'\')"></div></td>
                        <td><div class="forwardaction"><input type="button" name="PublicHoliday" value="Jours de fermeture" title="Jours de fermeture" onclick="manageHolidays(\''.$script->name.'\')"></div></td>
						<td><div class="forwardaction"><input type="button" name="Settings" value="Paramètres" title="Paramètres" onclick="manageSettings(\''.$script->name.'\')"></div></td>
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




