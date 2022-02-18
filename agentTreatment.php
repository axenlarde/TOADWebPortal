<?php
session_start(); // Création de la session
include "sessionFound.php";

/******
 * Page used to process agent changes (Add/Delete/Update)
 */
$urlToReturn = "Location: mainpage.php?page=showTask";

if((isset($_GET["action"])) && (($_GET["action"] == "add") || ($_GET["action"] == "update")))
	{
	/**
	 * We add the new entry
	 */
	$ucp = @$_POST["ucp"];
	$userid = $_POST["userid"];
	$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$agenttype = $_POST["agenttype"];
	$number = $_POST["number"];
	$devicename = $_POST["devicename"];
	$devicetype = @$_POST["devicetype"];
	$udplogin = @$_POST["udplogin"];//Check if it is a boolean
	if($udplogin == "on")
	   {
	   $udplogin = "true";
	   }
	else
	   {
	   $udplogin = "false";
	   }
	$team = $_POST["team"];
	$primarysupervisorof = @$_POST["primarysupervisorof"];
	$secondarysupervisorof = @$_POST["secondarysupervisorof"];
	$skills = $_POST["AssignedList"];
	
	$requestType = "addAgent";
	if($_GET["action"] == "update")
	   {
	   $requestType = "updateAgent";
	   $ucp = "updateAgent";
	   }
	
	//We prepare the addAgent request
	$request = "<xml>
					<request>
						<type>".$requestType."</type>
						<securitytoken>".$_SESSION['securitytoken']."</securitytoken>
						<content>
							<agent>
							    <usercreationprofile>".$ucp."</usercreationprofile>
							    <userid>".$userid."</userid>
							    <firstname>".$firstname."</firstname>
							    <lastname>".$lastname."</lastname>
							    <number>".$number."</number>
							    <type>".$agenttype."</type>
							    <devicename>".$devicename."</devicename>
							    <devicetype>".$devicetype."</devicetype>
							    <udplogin>".$udplogin."</udplogin>
							    <team>".$team."</team>
							    <primarysupervisorof>
";
		if(strcmp($agenttype,"supervisor") == 0)
	    	{
	    	foreach($_POST["primarysupervisorof"] as $teamName)
		    	{
		        $request .= "							    	<team>".$teamName."</team>
";
		    	}
	    	}
	    $request .= "							    </primarysupervisorof>
							    <secondarysupervisorof>
";
	    if(strcmp($agenttype,"supervisor") == 0)
	    	{
	    	foreach($secondarysupervisorof as $teamName)
	    		{
	        	$request .= "							    	<team>".$teamName."</team>
";
	    		}
	    	}
	    
	    $request .= "							    </secondarysupervisorof>
							    <skills>
";
	    foreach($skills as $skill)
	   		{
	   		$skillArray = explode("(",$skill);
	    	$request .= "							    	<skill>
							    		<name>".$skillArray[0]."</name>
							    		<level>".substr($skillArray[1],0,-1)."</level>
							    	</skill>
";
	    	}
	$request .= "							    </skills>
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
	$urlToReturn = $urlToReturn."&taskID=".$taskID;
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "delete"))
	{
	/**
	 * We delete the user
	 */
	$userID = $_GET["userID"];
	
	//We prepare the delete request
	$request = "<xml>
                    <request>
                    <type>deleteAgent</type>
                    <content>
                    	<agent>
                        	<userid>".$userID."</userid>
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
	$urlToReturn = $urlToReturn."&taskID=".$taskID;
	}

/**
 * Used to extract the taskID from the reply
 */
function parseReply($resp)
    {
    $reply = simplexml_load_string($resp);
    return $reply->reply->content->taskid;
    }

//To go back to the global paramaters administration page
header($urlToReturn);
exit;

?>