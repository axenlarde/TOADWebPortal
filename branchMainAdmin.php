<?php
/******
 * Page used to display the main admin menu
 */

include "sessionFound.php";
?>

<table class="mainmenu">
	<tr><td><a href="mainpage.php?page=manageAgent">Gestion des agents</a></td></tr>
	<tr><td><a href="mainpage.php?page=manageScript">Gestion des scripts</a></td></tr>
	<?php
	
	//If the user is an admin we display the "user admin menu"
	$group = $_SESSION['login'][3];
	
	if($group == "Admin")
		{
		//This user is an admin
		echo '
			<tr><td><a href="mainpage.php?page=adminUsers">Gestion des administrateurs</a></td></tr>
            <tr><td><a href="mainpage.php?page=displayLog">Afficher les logs</a></td></tr>
			';
		}
	?>
</table>


