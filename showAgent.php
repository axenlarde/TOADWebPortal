
<?php
include "sessionFound.php";

$urlToReturn = "Location: mainpage.php?page=manageAgent";

$userID = $_GET["userID"];

if(isset($userID))
	{
	//We contact the server to get the user data
    $request = '<xml>
			<request>
				<type>getAgent</type>
                <securitytoken>'.$_SESSION['securitytoken'].'</securitytoken>
				<content>
					<userid>'.$userID.'</userid>
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
    $agent = $searchResult->reply->content->agent;
	}
else
	{
	header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
	exit;
	}

?>

<h3>
	<div class="navibar">
	<a href="mainpage.php?page=branchMainAdmin">Retour</a>
	>
	<a href="mainpage.php?page=manageAgent">Gestion des agents</a>
	> Détail d'un agent
	</div>
</h3>
<hr>
<h3><div class="title">Détail de l'agent : </div></h3>
<div class="newTechGuyTable">
	<table>
		<tr>
			<td>
				<table id="techGuyForm">
					<tr>
						<td>UserID : </td>
						<td><?php echo $agent->userid?></td>
					</tr>
					<tr>
						<td>Prénom : </td>
						<td><?php echo $agent->firstname?></td>
					</tr>
					<tr>
						<td>Nom : </td>
						<td><?php echo $agent->lastname?></td>
					</tr>
					<tr>
						<td>Numéro : </td>
						<td><?php echo $agent->number?></td>
					</tr>
					<tr>
						<td>Type : </td>
						<td><?php echo $agent->type?></td>
					</tr>
					<tr>
						<td>Team : </td>
						<td><?php echo $agent->team?></td>
					</tr>
					<tr>
						<td>Superviseur primaire de : </td>
						<td><?php 
						foreach ($agent->primarysupervisorof->team as $team)
						  {
						  echo $team.', ';
						  }
						?>
						</td>
					</tr>
					<tr>
						<td>Superviseur secondaire de : </td>
						<td><?php 
						foreach ($agent->secondarysupervisorof->team as $team)
						  {
						  echo $team.', ';
						  }
						?>
						</td>
					</tr>
					<?php
						foreach($agent->skills->skill as $skill)
                            {
                            echo '<tr>
                                    <td>Skill</td>
                                    <td>'.$skill->name.'('.$skill->level.')</td>
                                  </tr>';
                            }
                        foreach($agent->devices->device as $device)
                            {
                            echo '<tr>
                                <td>Terminal</td>
                                <td>'.$device->name.'</td>
                              </tr>';
                            }
                        foreach($agent->udps->udp as $udp)
                            {
                            echo '<tr>
                            <td>Profile mobile</td>
                            <td>'.$udp->name.'</td>
                            </tr>';
                            }
						?>
				</table>
			</td>
		</tr>
	</table>
</div>
