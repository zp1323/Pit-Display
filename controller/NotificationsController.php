<?php
namespace Providers;
require_once("../app/Providers/DBProvider.php");
require_once("../app/Providers/NotificationProvider.php");
date_default_timezone_set('America/Los_Angeles');
header('Content-type: application/json');

$notification = new NotificationProvider;

if($_POST['do'] == "ack")
{
	echo json_encode($notification->ack($_POST['id']));
}
else if($_POST['do'] == "ack-all")
{
	echo json_encode($notification->ackAll());
}
else if($_POST['do'] == "pit-page")
{
	$return['message'] 	=	"We received the request but aren't ready to implement it yet! You requested event " . $_POST['id'];
	echo json_encode($return);
}
else
{
	$active 	=	$notification->getActive();
	if( $active === false)
	{
		// No pending notifications
		echo json_encode(array("active" => "false"));
	}
	else
	{
		echo json_encode($active);
	}
}


?>

