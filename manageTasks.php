
<?php
/** 
 * Page used to setup a new task
 */
include "sessionFound.php";

/****
 * We fetch the office and device list
 */
//We contact the server to copy the log file

$request = '<xml>
			<request>
				<type>listTask</type>
				<securitytoken>'.$_SESSION['securitytoken'].'</securitytoken>
				<content></content>
			</request>
		</xml>';

$context = stream_context_create(
		array(
				'http' => array(
						'method' => 'POST',
						'header' => 'Content-type: text/xml',
						'content' => $request))
		);

$resp = file_get_contents($url, FALSE, $context);

//Finally we open the xml content as String
$searchResult = simplexml_load_string($resp);

$taskCount = count($searchResult->reply->content->tasks->task);

?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Afficher les tâches</div></h3>
<br>
<table class="mainmenu">
<?php 
if($taskCount == 0)
	{
	echo 'L\'historique des tâches est vide pour l\'instant';
	}
else
	{
	foreach($searchResult->reply->content->tasks->task as $task)
		{
		echo '
			<tr>
				<td><a href="mainpage.php?page=showTask&taskID='.$task->taskid.'">'.$task->desc.' : '.$task->status.'</a></td>
			</tr>
			';
		}
	}
?>
</table>





