<?php
include "sessionFound.php";
?>

<script type="text/javascript">
var index = 2;

function checkNewInput(form)
	{
	if(document.getElementById("lastname").value == "")
		{
		alert("Le nom est obligatoire");
		}
	else if((document.getElementById("devicename").value != "") && (!document.getElementById("devicename").value.match(/^[0-9A-F]{12}$/)))
		{
		alert("L'adresse MAC est incorrecte");
		}
	else
		{
		document.getElementById("agenttype").disabled = false;
		document.getElementById("userid").disabled = false;
		document.getElementById("number").disabled = false;
		document.getElementById("team").disabled = false;
		selectAll();
		form.submit();
		}
	}

function selectAll()
	{
	var list = document.getElementById('AssignedList');
	for (var i = 0; i < list.options.length; i++)list.options[i].selected = "true";
    list = document.getElementById('primarysupervisorof');
	for (var i = 0; i < list.options.length; i++)list.options[i].selected = "true";
	list = document.getElementById('secondarysupervisorof');
	for (var i = 0; i < list.options.length; i++)list.options[i].selected = "true";
	}
	
function doAssignButton(t)
	{
	var selectedItems = $('select[id=NotAssignedList]').find(":selected");

	if (selectedItems.length === 0)
		{
		alert ("Une compétence doit être sélectionnée");
		return;
		}
	if (selectedItems.length > 50){
		alert ("Il n'est pas possible d'associer plus de 50 compétences");
		return;
		}	
	
	$.each(selectedItems, function (i, item) {
		var skill_level = $('select[id=csdCL]').val();
		var substring = item.text + "(" + skill_level +")";
		$('select[id=AssignedList]').append($('<option>', { 
			value: substring,
			text: substring
		}));		
	});
	
	//Removes all selected items
	selectedItems.remove();
	}
	
function doAssignPrimaryButton(t)
	{
	var selectedItems = $('select[id=notAssignedTeams]').find(":selected");

	if (selectedItems.length === 0)
		{
		alert ("Une équipe doit être sélectionnée");
		return;
		}
	if (selectedItems.length > 50){
		alert ("Il n'est pas possible d'associer plus de 50 équipes");
		return;
		}	
	
	$.each(selectedItems, function (i, item)
		{
		var txt = item.text;
		$('select[id=primarysupervisorof]').append($('<option>', { 
			value: txt,
			text: txt
		}));		
	});
	
	//Removes all selected items
	selectedItems.remove();
	}
	
function doAssignSecondaryButton(t)
	{
	var selectedItems = $('select[id=notAssignedTeams]').find(":selected");

	if (selectedItems.length === 0)
		{
		alert ("Une équipe doit être sélectionnée");
		return;
		}
	if (selectedItems.length > 50){
		alert ("Il n'est pas possible d'associer plus de 50 équipes");
		return;
		}	
	
	$.each(selectedItems, function (i, item)
		{
		var txt = item.text;
		$('select[id=secondarysupervisorof]').append($('<option>', { 
			value: txt,
			text: txt
		}));		
	});
	
	//Removes all selected items
	selectedItems.remove();
	}

function deselectNotAssigned()
	{
	document.NewUserForm.NotAssignedList.selectedIndex=-1;
	}

function doNotAssignButton(t)
	{
	var selectedItems = $('select[id=AssignedList]').find(":selected");

	if (selectedItems.length === 0)
		{
		alert ("Une compétence doit être sélectionnée");
		return;
		}
	
	$.each(selectedItems, function (i, item) {
		var txt = item.text;
		var idx = txt.indexOf("(");
		if(idx !== -1){
			txt = txt.substring(0,idx);
		}
		$('select[id=NotAssignedList]').append($('<option>', { 
			value: txt,
			text: txt
		}));		
	});
	
	//Removes all selected items
	selectedItems.remove();	
	}
	
function doNotAssignPrimaryButton(t)
	{
	var selectedItems = $('select[id=primarysupervisorof]').find(":selected");

	if (selectedItems.length === 0)
		{
		alert ("Une team doit être sélectionnée");
		return;
		}
	
	$.each(selectedItems, function (i, item) {
		var txt = item.text;
		$('select[id=notAssignedTeams]').append($('<option>', { 
			value: txt,
			text: txt
		}));		
	});
	
	//Removes all selected items
	selectedItems.remove();	
	}
	
function doNotAssignSecondaryButton(t)
	{
	var selectedItems = $('select[id=secondarysupervisorof]').find(":selected");

	if (selectedItems.length === 0)
		{
		alert ("Une team doit être sélectionnée");
		return;
		}
	
	$.each(selectedItems, function (i, item) {
		var txt = item.text;
		$('select[id=notAssignedTeams]').append($('<option>', { 
			value: txt,
			text: txt
		}));		
	});
	
	//Removes all selected items
	selectedItems.remove();	
	}

function deselectAssigned()
	{
	document.NewUserForm.AssignedList.selectedIndex=-1;
	}

function changeCL()
	{
	var skill_level = document.NewUserForm.csdCL.value;
	var sindex = document.NewUserForm.AssignedList.selectedIndex;
	if(sindex == -1)
		{
		return;
		}

	var txt = document.NewUserForm.AssignedList.options[sindex].text;

	var idx = txt.indexOf("(");
	txt = txt.substring(0,idx);


	var substring = txt + "(" + skill_level +")";

	document.NewUserForm.AssignedList.options[sindex].text = substring;
	document.NewUserForm.AssignedList.options[sindex].value = substring;
	document.NewUserForm.AssignedList.selectedIndex=sindex;
	}


function clickUAL()
	{
	deselectAssigned();
	}

function changeUAL()
	{
	var sindex = document.NewUserForm.NotAssignedList.selectedIndex;
	if(sindex == -1)
		{
		return;
		}

	document.NewUserForm.csdCL.value = 5;
	}


function clickAL()
	{
	deselectNotAssigned();
	}

function changeAL()
	{
	var sindex = document.NewUserForm.AssignedList.selectedIndex;
	if(sindex == -1)
		{
		return;
		}

	var txt = document.NewUserForm.AssignedList.options[sindex].text;

	var idx1 = txt.indexOf("(");
	var idx2 = txt.indexOf(")");
	var slevel = txt.substring(idx1+1,idx2);

	document.NewUserForm.csdCL.value = slevel;
	}

function hide()
	{
	if(document.getElementById("agenttype").value == "agent")
		{
		document.getElementById("supervisorteamselector").style.display="none";
		document.getElementById("supervisorteamselectortitle").style.display="none";
		}
	else
		{
		document.getElementById("supervisorteamselector").style.display="compact";
		document.getElementById("supervisorteamselectortitle").style.display="compact";
		}
	}

window.onload = hide;
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
							<select name="team" id="team" disabled="disabled">
    						<?php
    						foreach($teamSearchResult->reply->content->teams->team as $team)
                                {
                                if(strcmp($team, $agent->team) == 0)
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
						<td>UserID : </td>
						<td><input type="text" name="userid" id="userid" value="<?php echo $agent->userid;?>" disabled="disabled"></td>
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
							<select name ="agenttype" id="agenttype" disabled="disabled">
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
					<tr>
						<td>Numéro de la ligne : </td>
						<td><input type="text" name="number" id="number" value="<?php echo $agent->number;?>" disabled="disabled"></td>
					</tr>
					<tr id="supervisorteamselectortitle">
						<td>Superviseur principal de : </td>
						<td></td>
						<td>Equipes disponibles :</td>
						<td></td>
						<td>Superviseur secondaire de : </td>
					</tr>
					<tr id="supervisorteamselector">
						<td>
							<select style=width:200px name="primarysupervisorof[]" id="primarysupervisorof" multiple size = "4">
    						<?php
    						foreach($agent->primarysupervisorof->team as $team)
    							{
    							echo '<option value="'.$team.'">'.$team.'</option>';
    							}
                            ?>
                        	</select>
						</td>
						<td>
							<a href="javascript:doAssignPrimaryButton(this.form);"><</a>
							<!--<p><a href="javascript:doNotAssignPrimaryButton(this.form);">></a>-->
						</td>
						<td>
							<select style=width:200px name="notAssignedTeams" id="notAssignedTeams" multiple size = "4">
    						<?php
    						foreach($teamSearchResult->reply->content->teams->team as $team)
    							{
    							$found = false;
    							
	    						foreach($agent->primarysupervisorof->team as $agentTeam)
	    							{
    								if(strcmp($agentTeam,$team) == 0)
    									{
    									$found = true;
    									break;
    									}
	    							}
	    						if(!$found)
	    							{
    								foreach($agent->secondarysupervisorof->team as $agentTeam)
		    							{
	    								if(strcmp($agentTeam,$team) == 0)
		    								{
	    									$found = true;
	    									break;
		    								}
		    							}
	    							}
	    							
    							if(!$found)echo '<option value="'.$team.'">'.$team.'</option>';
    							}
                            ?>
                        	</select>
                        </td>
						<td><a href="javascript:doAssignSecondaryButton(this.form);">></a>
					<p><a href="javascript:doNotAssignSecondaryButton(this.form);"><</a></td>
						<td>
							<select style=width:200px name="secondarysupervisorof[]" id="secondarysupervisorof" multiple size = "4">
    						<?php
    						foreach($agent->secondarysupervisorof->team as $team)
    							{
    							echo '<option value="'.$team.'">'.$team.'</option>';
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
						<td><input type="checkbox" name="udplogin" id="udplogin"></td>
					</tr>
					<tr>
					  <td>
						Compétences assignées (Skill)
					  </td>
					  <td></td>
					  <td width="54%">
						Compétences disponibles (Skill)
					  </td>
					</tr>
					<tr>
					  <td width="40%">
							<select size="4" style=width:300px name="AssignedList[]" id="AssignedList" tabindex="4" onchange="changeAL()" onclick="clickAL()" multiple>
							<?php 
							foreach($agent->skills->skill as $skill)
								{
								$displaySkill = $skill->name."(".$skill->level.")";
								echo '<option value="'.$displaySkill.'">'.$displaySkill.'</option>';
								}
							?>
							</select>
					  </td>
					  <td width="6%">
						<a href="javascript:doAssignButton(this.form);"><</a>
					<p><a href="javascript:doNotAssignButton(this.form);">></a>
					  </td>
					  <td width="54%">
						<select size="4" style=width:300px name="NotAssignedList[]" id="NotAssignedList" tabindex="5" onchange="changeUAL()" onclick="clickUAL()" multiple>
						<?php 
							foreach($skillSearchResult->reply->content->skills->skill as $skill)
								{
								$found = false;
								foreach($agent->skills->skill as $agentSkill)
									{
									if(strcmp($agentSkill->name,$skill) == 0)
										{
										$found = true;
										break;
										}
									}
								if(!$found)
									{
									echo '<option value="'.$skill.'">'.$skill.'</option>';
									}
								}
							?>
						</select>
					  </td>
					</tr>
					<tr>
						<td>Niveau de compétence : </td>
						<td>
							<select size="1" name="csdCL" id="csdCL" onchange="changeCL()">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option selected="selected">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
							</select>
						</td>
						<td>(1-Débutant, 10-Expert)</td>
					</tr>
				</table>
			</td>
			<td>
				<input type="button" name="Modifier" value="Modifier" onclick="checkNewInput(this.form)">
			</td>
		</tr>
	</table>
	</div>
</form>
