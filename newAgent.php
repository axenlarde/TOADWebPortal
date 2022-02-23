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
	else if((document.getElementById("devicename").value != "") && (!document.getElementById("devicename").value.match(/^[0-9A-F]{12}$/)))
		{
		alert("L'adresse MAC est incorrecte");
		}
	else
		{
		document.getElementById("agenttype").disabled = false;
		selectAll();
		form.submit();
		}
	}
	
function selectAll()
	{
	var list = document.getElementById('AssignedList');
	for (var i = 0; i < list.options.length; i++)
		{
    	list.options[i].selected = "true";
    	}
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
							<select size="4" style=width:250px name="AssignedList[]" id="AssignedList" tabindex="4" onchange="changeAL()" onclick="clickAL()" multiple></select>
					  </td>
					  <td width="6%">
						<a href="javascript:doAssignButton(this.form);"><</a>
					<p><a href="javascript:doNotAssignButton(this.form);">></a>
					  </td>
					  <td width="54%">
						<select size="4" style=width:250px name="NotAssignedList[]" id="NotAssignedList" tabindex="5" onchange="changeUAL()" onclick="clickUAL()" multiple>
						<?php 
							foreach($skillSearchResult->reply->content->skills->skill as $skill)
								{
								echo '<option value="'.$skill.'">'.$skill.'</option>';
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
				<input type="button" name="Ajouter" value="Ajouter" onclick="checkNewInput(this.form)">
			</td>
		</tr>
	</table>
	</div>
</form>
