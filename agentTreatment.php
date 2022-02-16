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
	$skill1 = $_POST["skill1"];
	$level1 = $_POST["level1"];
	$skill2 = $_POST["skill2"];
	$level2 = $_POST["level2"];
	$skill3 = $_POST["skill3"];
	$level3 = $_POST["level3"];
	$skill4 = $_POST["skill4"];
	$level4 = $_POST["level4"];
	
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
		        $request .= "                  <team>".$teamName."</team>";
		    	}
	    	}
	    $request .= "
                                </primarysupervisorof>
								<secondarysupervisorof>
";
	    if(strcmp($agenttype,"supervisor") == 0)
	    	{
	    	foreach($secondarysupervisorof as $teamName)
	    		{
	        	$request .= "                   <team>".$teamName."</team>";
	    		}
	    	}
	    
	    $request .= "
                                </secondarysupervisorof>
							    <skills>
							        <skill>
							            <name>".$skill1."</name>
							            <level>".$level1."</level>
							        </skill>";
	if(isset($skill2) && ($skill2 != "noSkill"))
	   {
       $request .= "                                    <skill>
                                        <name>".$skill2."</name>
                                        <level>".$level2."</level>
                                    </skill>";
	   }
   if(isset($skill3) && ($skill3 != "noSkill"))
	   {
       $request .= "                                    <skill>
                                        <name>".$skill3."</name>
                                        <level>".$level3."</level>
                                    </skill>";
	   }
   if(isset($skill4) && ($skill4 != "noSkill"))
	   {
	       $request .= "                                    <skill>
                                        <name>".$skill4."</name>
                                        <level>".$level4."</level>
                                    </skill>";
	   }
	
	
	$request .= "						</skills>
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