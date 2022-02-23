
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
				<type>copyLogFile</type>
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

$ok = false;

if(!isset($searchResult->reply->content->error))
	{
	$ok = true;
	}

?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Afficher les logs</div></h3>
<br>
<table class="mainmenu">
<?php 
if(!$ok)
	{
	echo 'Aucun log à afficher';
	}
else
	{
	echo '
		<tr>
			<td><a href="log/TOAD_LogFile.txt">Fichier 1</a></td>
		</tr>
		<tr>
			<td><a href="log/TOAD_LogFile.txt.1">Fichier 2</a></td>
		</tr>
		';
	}
?>
</table>





