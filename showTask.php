<?php
include "sessionFound.php";
?>

<script type="text/javascript">

function action(type, taskID)
	{
	window.location = "mainpage.php?page=taskTreatment&action="+type+"&taskID="+taskID;
	}

setTimeout(function(){window.location.reload(1);}, 5000);

</script>

<?php
$urlToReturn = "Location: mainpage.php?page=branchMainAdmin";

$taskID = @$_GET["taskID"];

//We contact the server to get the task data
$request = '<xml>
			<request>
				<type>getTask</type>
				<content>
					<taskid>'.$taskID.'</taskid>
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
	header($urlToReturn.'&message=unknowntaskid');
	exit;
	}

//Finally we open the xml content as String
$searchResult = simplexml_load_string($resp);
$task = $searchResult->reply->content->task;

if(empty($task->items->item))
	{
	header($urlToReturn.'&message=unknowntaskid');
	exit;
	}

function getVerboseStatus($status)
	{
	if($status == "processing") return '<div class="forwarddate">En cours de traitement</div>';
	else if($status == "waiting") return '<div class="forwarddate">En attente</div>';
	else if($status == "init") return '<div class="forwarddate">Initialisation</div>';
	else if($status == "done") return '<div class="forwardstatusok">Terminé sans erreur</div>';
	else if($status == "error") return '<div class="forwardstatusnok">Erreur</div>';
	else if($status == "deleted") return '<div class="forwardstatusok">Supprimé</div>';
	else if($status == "injected") return '<div class="forwardstatusok">OK</div>';
	else if($status == "updated") return '<div class="forwardstatusok">OK</div>';
	return '<div class="forwarddate">'.$status.'</div>';
	}
	
function getVerboseDesc($desc)
	{
	if($desc == "Reachable : true") return '<div class="forwardstatusok">Ping OK</div>';
	else if($desc == "Reachable : false") return '<div class="forwardstatusnok">Ping KO</div>';
	else if($desc == "Reachable : unknown") return '<div class="forwarddate">Ping en attente</div>';
	else if($desc == "Reachable : true, Error found") return '<div class="forwardstatusnok">Ping OK, Erreur</div>';
	else if($desc == "Reachable : false, Error found") return '<div class="forwardstatusnok">Ping KO, Erreur</div>';
	else if($desc == "Reachable : unknown, Error found") return '<div class="forwardstatusnok">Ping en attente, Erreur</div>';
	return '<div class="forwarddate">'.$desc.'</div>';
	}
?>

<h3>
	<div class="navibar">
	<a href="mainpage.php?page=branchMainAdmin">Retour</a>
	> Etat de la tâche
	</div>
</h3>
<hr>
<h3><div class="title">Détail de la tâche en cours : </div></h3>
<table>
	<tr>
		<td>Statut de la tâche : <?php echo getVerboseStatus($task->status); ?></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>Liste des éléments : </td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
<div class="forwardlist">
	<table>
		<tr>
			<td>Type</td>
			<td>Information</td>
			<td>Status</td>
			<td>Description</td>
		</tr>
		<?php
		foreach($task->items->item as $item)
			{
			echo '<tr>
					<td>'.$item->type.'</td>
                    <td>'.$item->info.'</td>
					<td>'.getVerboseStatus($item->status).'</td>
					<td>'.getVerboseDesc($item->desc).'</td>
				</tr>
				';
			}
		?>
	</table>
</div>
