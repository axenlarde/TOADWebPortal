<?php
session_start();
$url = 'http://127.0.0.1:8092/TOAD/';
/****
 * Used to authenticate users once they have submited the form
 */
?>

<!DOCTYPE html>
<html>
<body>

<?php

$login = $_POST['login'];
$password = $_POST['password'];

//Some special caracters are not supported by XML so we escape them as it is common to use some in password
$password = str_replace("&", "&#38;", $password);
$password = str_replace("<", "&#60;", $password);
$password = str_replace(">", "&#62;", $password);
$password = str_replace("'", "&#39;", $password);
$password = str_replace("\"", "&#34;", $password);

//We try to authenticate using the CUCM
$request = '<xml>
				<request>
					<type>doAuthenticate</type>
					<content>
						<userid>'.$login.'</userid>
						<userpassword>'.$password.'</userpassword>
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
	header("Location: mainpage.php?authenticationError=failedToAuthenticate&login=".$login);
	exit;
	}

//We open the xml content as String
$searchResult = simplexml_load_string($resp);

//We get the securityToken
$token = @(String) $searchResult->reply->content->securitytoken;
echo $token;
if((isset($token)) && (!empty($token)))
	{
	$tab = array();
	$tab[0] = $login;
	$tab[1] = "lastname";
	$tab[2] = "firstname";
	$tab[3] = "Normale";
	$_SESSION['login'] = $tab;
	$_SESSION['securitytoken'] = $token;
	
	header("Location: mainpage.php?page=branchMainAdmin");
	exit;
	}

//If we reach this point we return to the mainpage
header("Location: mainpage.php?authenticationError=failedToAuthenticate&login=".$login);
exit;
?>

</body>
</html>