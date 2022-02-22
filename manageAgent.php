<?php
include "sessionFound.php";
?>

<script type="text/javascript">

function updateItem(id)
	{
	window.location = "mainpage.php?page=updateAgent&userID="+id;
	}

function deleteItem(id)
	{
	window.location = "agentTreatment.php?action=delete&userID="+id;
	}

function showItem(id)
	{
	window.location = "mainpage.php?page=showAgent&userID="+id;
	}
	
function addItem()
	{
	window.location = "mainpage.php?page=newAgent";
	}

function validateSearch()
	{
	var search = document.getElementById("search").value;
	window.location = "mainpage.php?page=manageAgent&search="+search;
	}

function searchOnKeyPress(event)
	{
	if (event.keyCode == 13 || event.which == 13)
		{
		var search = document.getElementById("search").value;
		window.location = "mainpage.php?page=manageAgent&search="+search;
		}
	}

</script>


<?php
/** 
 * Page used to setup a new task
 */

/****
 * We fetch agent list
 */
//We contact the server to get the item list

$lastSearch;
if(isset($_GET["search"]))
	{
	$lastSearch = $_GET["search"];
	$_SESSION['search'] = $_GET["search"];
	}
else
	{
	if(isset($_SESSION['search']))
		{
		$lastSearch = $_SESSION['search'];
		}
	}

if(isset($lastSearch))
	{
	$request = '<xml>
				<request>
					<type>search</type>
                    <securitytoken>'.$_SESSION['securitytoken'].'</securitytoken>
					<content>
						<search>'.$lastSearch.'</search>
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
	}
else
	{
	$lastSearch = "Rechercher..";
	}

$MaxResult = 1200;
?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Gestion des agents</div></h3>

<div class="search">
<table>
	<tr>
		<td><input type="text" name="search" id="search" placeholder="<?php echo $lastSearch ?>" onkeypress="searchOnKeyPress(event)"></td>
		<td><button type="submit" onclick="validateSearch()">GO</button></td>
		<td><button type="submit" onclick="addItem()">Nouvel agent</button></td>
	</tr>
</table>
</div>

<?php
//We check if we ask for a research
if(isset($_SESSION['search']))
	{
	$AgentCount = count($searchResult->reply->content->agents->agent);
	
	if($AgentCount > 0)
		{
		echo'<h3>Résultat de la recherche : </h3><hr>
		<div class="forwardlist">
		<table>
			<tr>
				<td><b>Userid</b></td>
				<td><b>Prénom</b></td>
				<td><b>Nom</b></td>
				<td><b>Numéro</b></td>
                <td><b>Type</b></td>
				<td><b>Team</b></td>
				<td><b>Skills</b></td>
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
			
			$agent = $searchResult->reply->content->agents->agent[$index];
			
			if(isset($agent))
				{
				$skills = "";
				foreach($agent->skills->skill as $skill)
					{
					$skills .= $skill->name."(".$skill->level.") ";
					}
				
				echo '<tr>
		 				<td>'.$agent->userid.'</div></td>
						<td>'.$agent->firstname.'</div></td>
						<td>'.$agent->lastname.'</div></td>
                        <td>'.$agent->number.'</div></td>
                        <td>'.$agent->type.'</div></td>
                        <td>'.$agent->team.'</div></td>
						<td>'.$skills.'</div></td>
                        <td><div class="forwardaction"><input type="button" name="Detail" value="Detail" title="Detail" onclick="showItem(\''.$agent->userid.'\')"></div></td>
                        <td><div class="forwardaction"><input type="button" name="Update" value="Modifier" title="Update" onclick="updateItem(\''.$agent->userid.'\')"></div></td>
						<td><div class="forwardstatusnok"><input type="button" name="delete" value="X" title="delete" onclick="deleteItem(\''.$agent->userid.'\')"></div></td>
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




