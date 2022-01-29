<?php
include "sessionFound.php";
?>

<script type="text/javascript">
var index = 2;

function checkNewInput(form)
	{
	if(document.getElementById("lastname").value == "")
		{
		alert("Le prénom est obligatoire");
		}
	else if(document.getElementById("firstname").value == "")
		{
		alert("Le nom est obligatoire");
		}
	else
		{
		form.submit();
		}
	}
	
function hide()
	{
	if(document.getElementById("agenttype").value == "agent")
		{
		document.getElementById("primarysupervisor").style.display="none";
		document.getElementById("secondarysupervisor").style.display="none";
		}
	else
		{
		document.getElementById("primarysupervisor").style.display="block";
		document.getElementById("secondarysupervisor").style.display="block";
		}
	}
	
function addNewRow()
	{
	var myTable = document.getElementById("userForm");
	var row = myTable.insertRow(-1)
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	cell1.innerHTML = "Skill "+index+" : ";
	cell2.innerHTML = "<input type=\"text\" name=\"destinationDescription"+index+"\" id=\"destinationDescription"+index+"\">";
	cell3.innerHTML = "<input type=\"text\" name=\"destination"+index+"\" id=\"destination"+index+"\">";
	index++;
	}

</script>

<?php

/**
 * A simple form to get the informations to update an Agent
 */
//We get the agent id to update
$userID = $_GET["userID"];

//We get the agent information
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
    
    
/**
 * We get the available teams from the server
 */
$requestTeam = '<xml>
				<request>
					<type>listTeam</type>
                    <securitytoken>'.$_SESSION['securitytoken'].'</securitytoken>
					<content>
					</content>
				</request>
			</xml>';

$contextTeam = stream_context_create(
    array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type: text/xml',
            'content' => $requestTeam))
    );

$respTeam = @file_get_contents($url, FALSE, $contextTeam);

if($respTeam === false)
    {
    header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
    exit;
    }

//Finally we open the xml content as String
$teamSearchResult = simplexml_load_string($respTeam);
$teamCount = count($teamSearchResult->reply->content->teams->team);

if($teamCount == 0)
    {
    header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
    exit;
    }

/**
 * We get the available skills from the server
 */

$requestSkill = '<xml>
			<request>
				<type>listSkill</type>
                <securitytoken>'.$_SESSION['securitytoken'].'</securitytoken>
				<content>
				</content>
			</request>
		</xml>';

$contextSkill = stream_context_create(
    array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type: text/xml',
            'content' => $requestSkill))
    );

$respSkill = @file_get_contents($url, FALSE, $contextSkill);

if($respSkill === false)
    {
    header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
    exit;
    }

//Finally we open the xml content as String
$skillSearchResult = simplexml_load_string($respSkill);
$skillCount = count($skillSearchResult->reply->content->skills->skill);

if($skillCount == 0)
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
	>Update d'un agent
	</div>
</h3>
<br>
<hr>
<h3><div class="title">Entrez les informations nécessaires :</div></h3>
<form name="NewUserForm" id="NewUserForm" method=post action="agentTreatment.php?action=update">
	<div class="newTechGuyTable">
	<table>
		<tr>
			<td>
				<table id="userForm">
					<tr>
						<td>Team : </td>
						<td>
							<select name="team" id="team">
    						<?php
    						foreach($teamSearchResult->reply->content->teams->team as $team)
                                {
                                if($team == $agent->team)
                                    {
                                    echo '<option value="'.$team.'" selected="selected">'.$team.'</option>';
                                    }
                                else
                                    {
                                    echo '<option value="'.$team.'">'.$team.'</option>';
                                    }
                                }
                            ?>
                        	</select>
						</td>
					</tr>
					<tr>
						<td>Nom : </td>
						<td><input type="text" name="lastname" id="lastname" value="<?php echo $agent->lastname;?>"></td>
					</tr>
					<tr>
						<td>Prénom : </td>
						<td><input type="text" name="firstname" id="firstname" value="<?php echo $agent->firstname;?>"></td>
					</tr>
					<tr>
						<td>Type : </td>
						<td>
							<select name ="agenttype" id="agenttype" onchange="hide()">
							<?php 
							if($agent->type == "agent")
                                {
                                echo '
                                <option value="agent" selected="selected">Agent</option>
                                <option value="supervisor">Superviseur</option>
                                    ';
                                }
						     else
                                {
                                echo '
                                <option value="agent">Agent</option>
                                <option value="supervisor" selected="selected">Superviseur</option>
                                ';
                                }
							?>
							</select>
						</td>
					</tr>
					<tr id="primarysupervisor">
						<td>Superviseur principal de : </td>
						<td>
							<select name="primarysupervisorof" id="primarysupervisorof" multiple>
    						<?php
    						foreach($teamSearchResult->reply->content->teams->team as $team)
                                {
                                $found = false;
                                foreach($agent->primarysupervisorof->team as $selectedTeam)
                                	{
                                	if($team == $selectedTeam)
                                		{
                                		echo '<option value="'.$team.'" selected="selected">'.$team.'</option>';
                                		$found = true;
                                		break;
                                		}
                                	}
    						    if(!$found)	echo '<option value="'.$team.'">'.$team.'</option>';
                                }
                            ?>
                        	</select>
						</td>
					</tr>
					<tr id="secondarysupervisor">
						<td>Superviseur secondaire de : </td>
						<td>
							<select name="secondarysupervisorof" id="secondarysupervisorof" multiple>
    						<?php
    						foreach($teamSearchResult->reply->content->teams->team as $team)
                                {
                                $found = false;
                                foreach($agent->secondarysupervisorof->team as $selectedTeam)
                                	{
                                	if($team == $selectedTeam)
                                		{
                                		echo '<option value="'.$team.'" selected="selected">'.$team.'</option>';
                                		$found = true;
                                		break;
                                		}
                                	}
    						    if(!$found)	echo '<option value="'.$team.'">'.$team.'</option>';
                                }
                            ?>
                        	</select>
						</td>
					</tr>
					<tr>
						<td>MAC du téléphone où connecter l'utilisateur : </td>
						<td><input type="text" name="devicename" id="devicename"></td>
					</tr>
					<tr>
						<td>Connecter l'utilisateur au téléphone indiqué : </td>
						<td><input type="checkbox" name="udplogin" id="udplogin" checked></td>
					</tr>
					<tr>
						<td>Skill 1 : </td>
						<td>
							<select name ="skill1" id="skill1">
    						<?php
    						foreach($skillSearchResult->reply->content->skills->skill as $skill)
                                {
                                if($skill == $agent->skills->skill[0])
                                	{
                                	echo '<option value="'.$skill.'" selected="selected">'.$skill.'</option>';
                                	}
                                else
                                	{
                                	echo '<option value="'.$skill.'">'.$skill.'</option>';
                                	}
                                }
                            ?>
							</select>
							<select name ="level1" id="level1">
							<?php 
							for($i=1; $i<11; $i++)
								{
								if($agent->skills->skill[0] == $i)
									{
									echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
									}
								else 
									{
									echo '<option value="'.$i.'">'.$i.'</option>';
									}
								}
							?>
							</select>
							<!--<input type="button" name="add" value="+" onclick="addNewRow()">-->
						</td>
					</tr>
					<tr>
						<td>Skill 2 : </td>
						<td>
							<select name ="skill2" id="skill2">
    						<?php
    						$selectedSkill = $agent->skills->skill[1];
    						if(isset($selectedSkill))
    							{
    							echo '<option value="noSkill"></option>';
    							}
    						else
    							{
    							echo '<option value="noSkill" selected="selected"></option>';
    							}
    						
    						foreach($skillSearchResult->reply->content->skills->skill as $skill)
                                {
                                if(isset($selectedSkill) && ($skill == $selectedSkill))
                                	{
                                	echo '<option value="'.$skill.'" selected="selected">'.$skill.'</option>';
                                	}
                                else
                                	{
                                	echo '<option value="'.$skill.'">'.$skill.'</option>';
                                	}
                                }
                            ?>
							</select>
							<select name ="level2" id="level2">
							<?php 
							$selectedLevel = $agent->skills->skill[1];
							for($i=1; $i<11; $i++)
								{
								if(isset($selectedLevel) && ($selectedLevel == $i))
									{
									echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
									}
								else 
									{
									echo '<option value="'.$i.'">'.$i.'</option>';
									}
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Skill 3 : </td>
						<td>
							<select name ="skill3" id="skill3">
							<?php
    						$selectedSkill = $agent->skills->skill[2];
    						if(isset($selectedSkill))
    							{
    							echo '<option value="noSkill"></option>';
    							}
    						else
    							{
    							echo '<option value="noSkill" selected="selected"></option>';
    							}
    						
    						foreach($skillSearchResult->reply->content->skills->skill as $skill)
                                {
                                if(isset($selectedSkill) && ($skill == $selectedSkill))
                                	{
                                	echo '<option value="'.$skill.'" selected="selected">'.$skill.'</option>';
                                	}
                                else
                                	{
                                	echo '<option value="'.$skill.'">'.$skill.'</option>';
                                	}
                                }
                            ?>
							</select>
							<select name ="level3" id="level3">
							<?php 
							$selectedLevel = $agent->skills->skill[2];
							for($i=1; $i<11; $i++)
								{
								if(isset($selectedLevel) && ($selectedLevel == $i))
									{
									echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
									}
								else 
									{
									echo '<option value="'.$i.'">'.$i.'</option>';
									}
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Skill 4 : </td>
						<td>
							<select name ="skill4" id="skill4">
							<?php
    						$selectedSkill = $agent->skills->skill[3];
    						if(isset($selectedSkill))
    							{
    							echo '<option value="noSkill"></option>';
    							}
    						else
    							{
    							echo '<option value="noSkill" selected="selected"></option>';
    							}
    						
    						foreach($skillSearchResult->reply->content->skills->skill as $skill)
                                {
                                if(isset($selectedSkill) && ($skill == $selectedSkill))
                                	{
                                	echo '<option value="'.$skill.'" selected="selected">'.$skill.'</option>';
                                	}
                                else
                                	{
                                	echo '<option value="'.$skill.'">'.$skill.'</option>';
                                	}
                                }
                            ?>
							</select>
							<select name ="level4" id="level4">
							<?php 
							$selectedLevel = $agent->skills->skill[3];
							for($i=1; $i<11; $i++)
								{
								if(isset($selectedLevel) && ($selectedLevel == $i))
									{
									echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
									}
								else 
									{
									echo '<option value="'.$i.'">'.$i.'</option>';
									}
								}
							?>
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<input type="button" name="Ajouter" value="Ajouter" onclick="checkNewInput(this.form)">
			</td>
		</tr>
	</table>
	</div>
</form>
