<?php
session_start(); // Création de la session

/******
 * Page used to process agent changes (Add/Delete/Update)
 */

include "sessionFound.php";

$urlToReturn = "Location: mainpage.php?page=showTask";

if((isset($_GET["action"])) && ($_GET["action"] == "add"))
	{
	/**
	 * We add the new entry
	 */
	$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$agenttype = $_POST["agenttype"];
	$devicename = $_POST["devicename"];
	$devicetype = $_POST["devicetype"];
	$udplogin = $_POST["udplogin"];
	$team = $_POST["team"];
	$primarysupervisorof = $_POST["primarysupervisorof"];
	$secondarysupervisorof = $_POST["secondarysupervisorof"];
	$skill1 = $_POST["skill1"];
	$level1 = $_POST["level1"];
	$skill2 = $_POST["skill2"];
	$level2 = $_POST["level2"];
	$skill3 = $_POST["skill3"];
	$level3 = $_POST["level3"];
	$skill4 = $_POST["skill4"];
	$level4 = $_POST["level4"];
	
	
	
	//We prepare the update request
	$request = "<xml>
					<request>
						<type>addAgent</type>
                        <securitytoken>".$_SESSION['securitytoken']."</securitytoken>
						<content>
							<agent>
								<firstname>".$firstname."</firstname>
								<lastname>".$lastname."</lastname>
								<type>".$agenttype."</type>
								<devicename>".$devicename."</devicename>
								<devicetype>".$devicetype."</devicetype>
								<udplogin>".$udplogin."</udplogin>
								<team>".$team."</team>
								<primarysupervisorof>
                                    <team>".$primarysupervisorof."</team>
                                </primarysupervisorof>
								<secondarysupervisorof>";

	foreach($secondarysupervisorof as $teamName)
	   {
	   $request += "<team>".$teamName."</team>";
	   }
                                
	$request += "
                                </secondarysupervisorof>
                                <skills>
                                    <skill>
                                        <name>".$skill1."</name>
                                        <level>".$level1."</level>
                                    <skill>

";
	if(isset($skill2))
	   {
       $request += "
                                    <skill>
                                        <name>".$skill2."</name>
                                        <level>".$level2."</level>
                                    <skill>
        ";
	   }
   if(isset($skill3))
	   {
       $request += "
                                    <skill>
                                        <name>".$skill3."</name>
                                        <level>".$level3."</level>
                                    <skill>
        ";
	   }
   if(isset($skill4))
	   {
	       $request += "
                                    <skill>
                                        <name>".$skill4."</name>
                                        <level>".$level4."</level>
                                    <skill>
        ";
	   }
	
	
	$request += "
                                
								</skills>
							</agent>
						</content>
					</request>
				</xml>";
	
	//Then we send it
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => $request))
			);
	
	$resp = file_get_contents($url, FALSE, $context);
	
	//Parse the response to get the taskID
	$taskID = parseReply($resp);
	$urlToReturn += $urlToReturn."&taskID=".$taskID;
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "delete"))
	{
	/**
	 * We delete the user
	 */
	$id = $_GET["id"];
	
	//We prepare the delete request
	$request = "<xml><request><type>deleteUser</type><content><user><id>".$id."</id></user></content></request></xml>";
	
	//Then we send it
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => $request))
			);
	
	$resp = file_get_contents($url, FALSE, $context);
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "update"))
	{
	/**
	 * We update the user
	 */
	$id = $_GET["id"];
	$extension = $_POST["extension"];
	$defaultbrowser = $_POST["defaultbrowser"];
	$browseroptions = $_POST["browseroptions"];
	$incomingcallpopup = $_POST["incomingcallpopup"];
	$reverselookup = $_POST["reverselookup"];
	$emailreminder = $_POST["emailreminder"];
	
	$incomingcallpopup = ($incomingcallpopup == "on")?"true":"false";
	$reverselookup = ($reverselookup == "on")?"true":"false";
	$emailreminder = ($emailreminder == "on")?"true":"false";
	
	//We prepare the update request
	$request = "<xml><request><type>updateUser</type><content><user><id>".$id."</id><extension>".$extension."</extension><defaultbrowser>".$defaultbrowser."</defaultbrowser><browseroptions>".$browseroptions."</browseroptions><incomingcallpopup>".$incomingcallpopup."</incomingcallpopup><reverselookup>".$reverselookup."</reverselookup><emailreminder>".$emailreminder."</emailreminder></user></content></request></xml>";
	
	//Then we send it
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => $request))
			);
	
	$resp = file_get_contents($url, FALSE, $context);
	}

/**
 * Used to extract the taskID from the reply
 */
function parseReply($reply)
    {
    return $reply->reply->content->taskid;
    }

//To go back to the global paramaters administration page
header($urlToReturn);
exit;

?>