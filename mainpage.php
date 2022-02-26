<?php
session_start(); // Création de la session
/*
if (!is_writable(session_save_path())) {
	echo 'Session path "'.session_save_path().'" is not writable for PHP!';
}
*/
?>
<script src="include/jquery-3.2.1.js"></script>
<script src="include/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript">

function changeStyle(cssname)
	{
	document.getElementById('pagestyle').setAttribute('href', cssname);
	}

function getParameterByName(name)
	{
	url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	    results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

function displayMessage()
	{
	var message = getParameterByName("message");
	if(message != null)
		{
		if(message == "unknowntaskid")alert("La tâche demandée n'existe pas");
		else if(message == "maxtaskreached")alert("Vous ne pouvez pas lancer de nouvelle tâche\r\nLe maximum en simultané a été atteint");
		else if(message == "generalerror")alert("Une erreur s'est produite\r\nVeuillez réessayer ou contactez l'administrateur");
		else if(message == "useridnotavailable")alert("Le userID mentionné existe déja");
		else if(message == "linenotavailable")alert("La ligne mentionnée existe déja");
		}
	}

</script>

<!DOCTYPE html>

<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accueil</title>
<LINK id="pagestyle" REL="stylesheet" TYPE="text/css" HREF="mainstyle.css">
<link rel="stylesheet" href="include/jquery-ui/jquery-ui.css">
</HEAD>
<body onload="displayMessage()">
<div class="contenu">
	<div class="entete">
		<table style="width: 100%">
			<tr>
				<td><img src="design/LogoSD.png" alt="Logo SD" width="80" height="80"></td>
				<td><h1>Portail d'administration du centre d'appel</h1></td>
			</tr>
<?php
//Connected user
$connected = false;
    
if(isset($_SESSION['login']))
	{
	if(!empty($_SESSION['login']))
		{
		$connected = true;
		
		echo'
			<tr>
				<td></td><td><div style="color: white; font-weight: bold; text-align: right;">Connecté avec le compte : '.$_SESSION['login'][0].'</div></td>
			</tr>
			<tr>
				<td></td><td><div style="text-align: right;"><a href="mainpage.php" style="color: white;">Se déconnecter</a></div></td>
			</tr>
		';
		}
	}

echo '</table></div>';	
echo '<div class="centre">';

$page = "connexion";
		
if($connected)
	{
	if(!empty($_GET))
		{
		$page = $_GET['page'];
		}
	else
		{
		$_SESSION = array();
		header("Location: mainpage.php");
		exit;
		}
	}
	
include $page.".php";
?>
	</div>
	
	<div class="pied">
		SPIE : UCCX web admin portal v1.0 : 2022
	</div>
	
</div>
</body>
</html>

