<?php
session_start(); // Création de la session

/******
 * Page used to process migration process (update/rollback/reset)
 */

include "sessionFound.php";

$urlToReturn = "Location: mainpage.php?page=branchMainAdmin";

if((isset($_GET["action"])) && (($_GET["action"] == "update") || ($_GET["action"] == "rollback") || ($_GET["action"] == "reset")))
	{
	if(isset($_SESSION['cart']))
		{
		if(empty($_SESSION['cart']))
			{
			header($urlToReturn.'&message=generalerror');
			exit;
			}
		}
	else
		{
		header($urlToReturn.'&message=generalerror');
		exit;
		}
	
	$content = '';
	//In case of an office we have to get the associated device, so we ask them to the server
	//If the item is not an office, the result will be empty
	foreach($_SESSION['cart'] as $item)
		{
		//First we add the main item
		$content .= '<itemid>'.$item.'</itemid>
				';
		
		$request = '<xml>
			<request>
				<type>getOffice</type>
				<content>
					<officeid>'.$item.'</officeid>
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
		
		//We clear the cart
		$_SESSION['cart'] = array();
		
		$resp = @file_get_contents($url, FALSE, $context);
		
		if($resp === false)
			{
			header($urlToReturn.'&message=generalerror');
			exit;
			}
		
		$searchResult = simplexml_load_string($resp);
		
		$devices = $searchResult->reply->content->office->devices->device;
		if(!empty($devices))
			{
			foreach($devices as $item)
				{
				//We add the devices to the migration task
				$content .= '<itemid>'.$item->id.'</itemid>
						';
				}
			}
		}
		
	//Then we send the newTask request
	$request = '<xml>
			<request>
				<type>newTask</type>
				<content>
					<action>'.$_GET["action"].'</action>
					<ownerid>'.$_SESSION["login"][0].'</ownerid>
					<itemlist>
						'.$content.'
					</itemlist>
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
		header($urlToReturn.'&message=generalerror');
		exit;
		}
	
	$searchResult = simplexml_load_string($resp);
	
	$taskID = $searchResult->reply->content->taskid;
	if(!empty($taskID))
		{
		$_SESSION['tasks'] = array();
		array_push($_SESSION["tasks"], $taskID);
		
		//We go to the showTask page to see the task status
		header("Location: mainpage.php?page=showTask&taskID=".$taskID);
		exit;
		}
	else if($searchResult->reply->type == "error")
		{
		if(strpos($searchResult->reply->content->error, 'Max concurent task reached') !== false)
			{
			header($urlToReturn.'&message=maxtaskreached');
			exit;
			}
		}
	//Something went wrong
	header($urlToReturn.'&message=generalerror');
	exit;
	}
else
	{
	if(isset($_GET['taskID']))
		{
		if(empty($_GET['taskID']))
			{
			//Something went wrong
		header($urlToReturn.'&message=generalerror');
		exit;
			}
		}
	else
		{
		//Something went wrong
		header($urlToReturn.'&message=generalerror');
		exit;
		}
		
	//We create the action request
	$request = '<xml>
		<request>
			<type>setTask</type>
			<content>
				<task>
					<taskid>'.$_GET['taskID'].'</taskid>
					<action>'.$_GET['action'].'</action>
				</task>
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
		header($urlToReturn.'&message=generalerror');
		exit;
		}
	
	//Finally we open the xml content as String
	$searchResult = simplexml_load_string($resp);
	
	if($searchResult->reply->type == "success")
		{
		$_SESSION["tasks"] = array();
		array_push($_SESSION["tasks"], $taskID);
	
		header("Location: mainpage.php?page=showTask&taskID=".$_GET['taskID']);
		exit;
		}
	else if($searchResult->reply->type == "error")
		{
		if(strpos($searchResult->reply->content->error, 'Max concurent task reached') !== false)
			{
			header($urlToReturn.'&message=maxtaskreached');
			exit;
			}
		}
	//Something went wrong
	header($urlToReturn.'&message=generalerror');
	exit;
	}

//To go back to the main page
header($urlToReturn);
exit;

?>