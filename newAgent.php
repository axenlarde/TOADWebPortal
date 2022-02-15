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
		document.getElementById("agenttype").disabled = false;
		form.submit();
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
 * A simple form to get the informations to create an Agent
 */

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
    
/**
* We get the available User Creation Profile from the server
*/
$requestUCP = '<xml>
			<request>
				<type>listUCP</type>
                <securitytoken>'.$_SESSION['securitytoken'].'</securitytoken>
				<content>
				</content>
			</request>
		</xml>';
    
$contextUCP = stream_context_create(
  	array(
   		'http' => array(
   			'method' => 'POST',
   			'header' => 'Content-type: text/xml',
   			'content' => $requestUCP))
   	);
    
$respUCP = @file_get_contents($url, FALSE, $contextUCP);
    
if($respUCP === false)
    {
  	header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
   	exit;
    }
    
//Finally we open the xml content as String
$ucpSearchResult = simplexml_load_string($respUCP);
$ucpCount = count($ucpSearchResult->reply->content->ucps->ucp);
    
if($ucpCount == 0)
    {
   	header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
   	exit;
    }
    
function getVerboseDesc($desc)
    {
    if($desc == "addAgent") return '<option value="'.$desc.'" selected="selected">Agent normal</option>';
    else if($desc == "addRemoteAgent") return '<option value="'.$desc.'">Agent télétravail</option>';
    
    return '';
    }
?>

<h3>
	<div class="navibar">
	<a href="mainpage.php?page=branchMainAdmin">Retour</a>
	>
	<a href="mainpage.php?page=manageAgent">Gestion des agents</a>
	>Ajout d'un nouvel agent
	</div>
</h3>
<br>
<hr>
<h3><div class="title">Entrez les informations nécessaires :</div></h3>
<form name="NewUserForm" id="NewUserForm" method=post action="agentTreatment.php?action=add">
	<div class="newTechGuyTable">
	<table>
		<tr>
			<td>
				<table id="userForm">
					<tr>
						<td>Profile de création* : </td>
						<td>
							<select name="ucp" id="ucp">
    						<?php
    						foreach($ucpSearchResult->reply->content->ucps->ucp as $ucp)
                                {
                                echo getVerboseDesc($ucp->name);
                                }
                            ?>
                        	</select>
						</td>
					</tr>
					<tr>
						<td>Team* : </td>
						<td>
							<select name="team" id="team">
    						<?php
    						foreach($teamSearchResult->reply->content->teams->team as $team)
                                {
    						    echo '<option value="'.$team.'">'.$team.'</option>';
                                }
                            ?>
                        	</select>
						</td>
					</tr>
					<tr>
						<td>UserID : </td>
						<td><input type="text" name="userid" id="userid"></td>
					</tr>
					<tr>
						<td>Nom* : </td>
						<td><input type="text" name="lastname" id="lastname"></td>
					</tr>
					<tr>
						<td>Prénom* : </td>
						<td><input type="text" name="firstname" id="firstname"></td>
					</tr>
					<tr>
						<td>Type : </td>
						<td>
							<select name ="agenttype" id="agenttype" disabled="disabled">
                                <option value="agent" selected="selected">Agent</option>
                                <option value="supervisor">Superviseur</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Numéro de la ligne : </td>
						<td><input type="text" name="number" id="number"></td>
					</tr>
					<tr>
						<td>MAC du téléphone : </td>
						<td><input type="text" name="devicename" id="devicename"></td>
					</tr>
					<tr>
						<td>Modèle du téléphone : </td>
						<td>
							<select name ="devicetype" id="devicetype">
								<option value="7821">Cisco 7821</option>
								<option value="7841" selected="selected">Cisco 7841</option>
								<option value="7861">Cisco 7861</option>
								<option value="8811">Cisco 8811</option>
								<option value="8841">Cisco 8841</option>
								<option value="8851">Cisco 8851</option>
								<option value="8861">Cisco 8861</option>
								<option value="8865">Cisco 8865</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Connecter l'utilisateur au téléphone indiqué : </td>
						<td><input type="checkbox" name="udplogin" id="udplogin"></td>
					</tr>
					<tr>
						<td>Skill 1* : </td>
						<td>
							<select name ="skill1" id="skill1">
    						<?php
    						foreach($skillSearchResult->reply->content->skills->skill as $skill)
                                {
                                echo '<option value="'.$skill.'">'.$skill.'</option>';
                                }
                            ?>
							</select>
							<select name ="level1" id="level1">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5" selected="selected">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
							</select>
							<!--<input type="button" name="add" value="+" onclick="addNewRow()">-->
						</td>
					</tr>
					<tr>
						<td>Skill 2 : </td>
						<td>
							<select name ="skill2" id="skill2">
								<option value="noSkill" selected="selected"></option>
    						<?php
    						foreach($skillSearchResult->reply->content->skills->skill as $skill)
                                {
                                echo '<option value="'.$skill.'">'.$skill.'</option>';
                                }
                            ?>
							</select>
							</select>
							<select name ="level2" id="level2">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5" selected="selected">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Skill 3 : </td>
						<td>
							<select name ="skill3" id="skill3">
								<option value="noSkill" selected="selected"></option>
    						<?php
    						foreach($skillSearchResult->reply->content->skills->skill as $skill)
                                {
                                echo '<option value="'.$skill.'">'.$skill.'</option>';
                                }
                            ?>
							</select>
							</select>
							<select name ="level3" id="level3">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5" selected="selected">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Skill 4 : </td>
						<td>
							<select name ="skill4" id="skill4">
								<option value="noSkill" selected="selected"></option>
    						<?php
    						foreach($skillSearchResult->reply->content->skills->skill as $skill)
                                {
                                echo '<option value="'.$skill.'">'.$skill.'</option>';
                                }
                            ?>
							</select>
							</select>
							<select name ="level4" id="level4">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5" selected="selected">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
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
