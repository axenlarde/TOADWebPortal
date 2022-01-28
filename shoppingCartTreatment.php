<?php
session_start(); // Création de la session

/******
 * Page used to process shopping cart changes (Add/Delete)
 */

include "sessionFound.php";

$urlToReturn = "Location: mainpage.php?page=newTask";
$search;
if(isset($_GET["search"]))
	{
	$search = $_GET["search"];
	}

if((isset($_GET["action"])) && ($_GET["action"] == "add"))
	{
	/**
	 * We add the new entry in the shopping cart
	 */
	//We get the posted value
	$itemid = $_GET["itemID"];
	
	if(isset($_SESSION['cart']))
		{
		if(empty($_SESSION['cart']))
			{
			$_SESSION['cart'] = array();
			}
		}
	else
		{
		$_SESSION['cart'] = array();
		}
	
	//We check that the item doesn't exist already
	$found = false;
	foreach($_SESSION['cart'] as $item)
		{
		if($item == $itemid)
			{
			$found = true;
			break;
			}
		}
	
	//We then add the new entry
	if(!$found)array_push($_SESSION['cart'], $itemid);
	header($urlToReturn."&search=".$search);
	exit;
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "delete"))
	{
	/**
	 * We delete the entry in the shopping cart
	 */
	//We get the posted value
	$itemid = $_GET["itemID"];
	
	if(isset($_SESSION['cart']))
		{
		if(empty($_SESSION['cart']))
			{
			$_SESSION['cart'] = array();
			}
		}
	else
		{
		$_SESSION['cart'] = array();
		}
	
	//We delete the item if it exists in the cart
	$index = array_search($itemid,$_SESSION['cart']);
	if($index !== FALSE)unset($_SESSION['cart'][$index]);
	
	header($urlToReturn."&search=".$search);
	exit;
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "update"))
	{
	/**
	 * Has to be writtent if needed
	 */
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "emptycart"))
	{
	$_SESSION['cart'] = array();
	if(empty($search))header($urlToReturn);
	else header($urlToReturn."&search=".$search);
	exit;
	}

//To go back to the global paramaters administration page
header($urlToReturn);
exit;

?>