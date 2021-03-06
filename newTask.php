
<script type="text/javascript">

function addNew(id, search)
	{
	window.location = "shoppingCartTreatment.php?action=add&itemID="+id+"&search="+search;
	}

function deleteItem(id, search)
	{
	window.location = "shoppingCartTreatment.php?action=delete&itemID="+id+"&search="+search;
	}

function showItem(id)
	{
	window.location = "mainpage.php?page=showItem&id="+id;
	}

function validateSearch()
	{
	var search = document.getElementById("search").value;
	window.location = "mainpage.php?page=newTask&search="+search;
	}

function searchOnKeyPress(event)
	{
	if (event.keyCode == 13 || event.which == 13)
		{
		var search = document.getElementById("search").value;
		window.location = "mainpage.php?page=newTask&search="+search;
		}
	}

function action(type)
	{
	window.location = "mainpage.php?page=taskTreatment&action="+type;
	}

function emptyCart(search)
	{
	window.location = "shoppingCartTreatment.php?action=emptycart&search="+search;
	}

</script>


<?php
/** 
 * Page used to setup a new task
 */
include "sessionFound.php";

/****
 * We fetch the office and device list
 */
//We contact the server to get the item list

$lastSearch = "Rechercher..";
if(isset($_GET["search"]))
	{
	$searchContent = $_GET["search"];
	$request = '<xml>
				<request>
					<type>search</type>
					<content>
						<search>'.$searchContent.'</search>
					</content>
				</request>
			</xml>';
	
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => $request))
			);
	
	$resp = @file_get_contents($url, FALSE, $context);
	
	if($resp === false)
		{
		header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
		exit;
		}
	
	//Finally we open the xml content as String
	$searchResult = simplexml_load_string($resp);
	
	$lastSearch = $_GET["search"];
	}

$MaxResult = 1200;
?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Nouvelle T??che</div></h3>

<div class="search">
<table>
	<tr>
		<td><input type="text" name="search" id="search" placeholder="<?php echo $lastSearch ?>" onkeypress="searchOnKeyPress(event)"></td>
		<td><button type="submit" onclick="validateSearch()">GO</button></td>
		<td></td>
		<td></td>
	</tr>
</table>
<table>
<?php
	if(isset($_SESSION['cart']))
		{
		if(!empty($_SESSION['cart']))
			{
			echo '
				<tr>
					<td>D??marrer une t??che : </td>
					<td><button type="submit" onclick="action(\'update\')">Migration</button></td>
					<td><button type="submit" onclick="action(\'rollback\')">Retour arri??re</button></td>
					<td><button type="submit" onclick="action(\'reset\')">Reset</button></td>
					<td><button type="submit" onclick="emptyCart(\''.$_GET['search'].'\')">Vider le panier</button></td>
				</tr>
				';
			}
		}
?>
</table>
</div>

<?php
//We check if we ask for a research
if(isset($_GET["search"]))
	{
	$officeCount = count($searchResult->reply->content->offices->office);
	$deviceCount = count($searchResult->reply->content->devices->device);
	
	if($officeCount+$deviceCount > 0)
		{
		echo'<h3>R??sultat de la recherche : </h3><hr>';
		}
	
	if($officeCount > 0)
		{
		echo'<h4>Sites : </h4>
		<div class="forwardlist">
		<table>
			<tr>
				<td><b>Nom</b></td>
				<td><b>ID COMU</b></td>
				<td><b>Nom PSTR</b></td>
				<td><b>Statut</b></td>
				<td><b>Ajouter</b></td>
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
			
			$office = $searchResult->reply->content->offices->office[$index];
			
			if(isset($office))
				{
				echo '<tr>
		 				<td><div class="forwarddate">'.$office->name.'</div></td>
						<td><div class="forwarddate">'.$office->idcomu.'</div></td>
						<td><div class="forwarddate">'.$office->newname.'</div></td>
 					';
				if($office->status == "migrated")
					{
					echo '<td><div class="forwardstatusok">Migr??</div></td>';
					}
				else
					{
					echo '<td><div class="forwarddate">Pas encore migr??</div></td>';
					}
				if((isset($_SESSION['cart'])) && in_array($office->id,$_SESSION['cart']))
					{
					echo '
						<td><div class="forwardstatusnok"><input type="button" name="supprimer" value="X" title="supprimer" onclick="deleteItem(\''.$office->id.'\',\''.$_GET['search'].'\')"></div></td>
		 			</tr>
					';
					}
				else
					{
					echo '
						<td><div class="forwardaction"><input type="button" name="Ajouter" value="+" title="Ajouter" onclick="addNew(\''.$office->id.'\',\''.$_GET['search'].'\')"></div></td>
		 			</tr>
					';
					}
				}
			else
				{
				break;
				}
			$index++;
			
			//We now display the office's devices
			/*$dCount = count($office->devices->device);
			if($dCount > 0)
				{
				echo '<tr>
						<td><div class="devicelistfirst"><b>Nom</b></div></td>
						<td><div class="devicelistfirst"><b>Type</b></div></td>
						<td><div class="devicelistfirst"><b>IP</b></div></td>
						<td><div class="devicelistfirst"><b>Statut</b></div></td>
					</tr>
					';
				}
			
			$deviceIndex = 0;
			while(true)
				{
				if($deviceIndex >= $MaxResult)
					{
					$deviceIndex++;
					break;//Just a security
					}
				
				$device = $office->devices->device[$deviceIndex];
					
				if(isset($device))
					{
					echo '<tr>
			 				<div class="devicelist"><td><div class="devicelist">'.$device->name.'</div></td></div>
							<td><div class="devicelist">'.$device->type.'</div></td>
							<td><div class="devicelist">'.$device->ip.'</div></td>
							<td><div class="devicelist">'.$device->status.'</div></td>
			 			</tr>
						';
					}
				else
					{
					break;
					}
				$deviceIndex++;
				}*/
			}
		
		echo'</table>
		</div>';
		
		if($index > $MaxResult)
			{
			echo "<h4>D??sol??, il n'est pas possible d'afficher plus d'entr??e (max ".$MaxResult.")</h4>";
			}
		}
	
	echo'<br><br><hr>';
	
	if($deviceCount > 0)
		{
		echo'<h4>Devices : </h4>
		<div class="forwardlist">
		<table>
			<tr>
				<td><b>Nom</b></td>
				<td><b>Type</b></td>
				<td><b>IP</b></td>
				<td><b>ID COMU</b></td>
				<td><b>Site</b></td>
				<td><b>Statut</b></td>
				<td><b>Ajouter</b></td>
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
				
			$device = $searchResult->reply->content->devices->device[$index];
				
			if(isset($device))
				{
				echo '
		 			<tr>
		 				<td><div class="forwarddate">'.$device->name.'</div></td>
						<td><div class="forwarddate">'.$device->type.'</div></td>
						<td><div class="forwarddate">'.$device->ip.'</div></td>
						<td><div class="forwarddate">'.$device->officeid.'</div></td>
						<td><div class="forwarddate">'.$device->officename.'</div></td>
						';
				if($device->status == "migrated")
					{
					echo '<td><div class="forwardstatusok">Migr??</div></td>';
					}
				else
					{
					echo '<td><div class="forwarddate">Pas encore migr??</div></td>';
					}
				if((isset($_SESSION['cart'])) && in_array($device->id,$_SESSION['cart']))
					{
					echo '
					<td><div class="forwardstatusnok"><input type="button" name="supprimer" value="X" title="supprimer" onclick="deleteItem(\''.$device->id.'\',\''.$_GET['search'].'\')"></div></td>
	 			</tr>
				';
					}
				else
					{
					echo '
					<td><div class="forwardaction"><input type="button" name="Ajouter" value="+" title="Ajouter" onclick="addNew(\''.$device->id.'\',\''.$_GET['search'].'\')"></div></td>
	 			</tr>
				';
					}
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
			echo "<h4>D??sol??, il n'est pas possible d'afficher plus d'entr??e (max ".$MaxResult.")</h4>";
			}
		}
	
	echo'<br><br><hr>';
	}
else
	{
	//We display the default page without in progress search
	echo '<br><br>&nbspAucune recherche en cours';
	}
?>




