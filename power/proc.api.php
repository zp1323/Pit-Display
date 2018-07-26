<?
require_once("globals.php");
require_once("func.api.php");


// Switch statement with pre-built API calls
switch ($_GET['function']){
	case 0:
		// Power all outlets on
		$uri 		=	"/restapi/relay/outlets/all;/state/";
		$fields 	=	array("value"=>"true");
		$method 	=	"PUT";
		break;
	case 1:
		// Power all outlets off
		$uri 		=	"/restapi/relay/outlets/all;/state/";
		$fields 	=	array("value"=>"false");
		$method 	=	"PUT";
		break;
	case 2:
		// Get the name of all outlets
		$uri 		=	"/restapi/relay/outlets/all;/=name/";
		$fields 	=	false;
		$method 	=	"GET";
		break;
	case 3:
		// Get the state of all outlets
		$uri 		=	"/restapi/relay/outlets/all;/state/";
		$fields 	=	false;
		$method 	=	"GET";
		break;
	case 4:
		// Power single outlet on
		$uri 		=	"/restapi/relay/outlets/".($_GET['outlet']-1)."/state/";
		$fields 	=	array("value"=>"true");
		$method 	=	"PUT";
		break;
	case 5:
		// Power single outlet off
		$uri 		=	"/restapi/relay/outlets/".($_GET['outlet']-1)."/state/";
		$fields 	=	array("value"=>"false");
		$method 	=	"PUT";
		break;
}

$powerSwitchAPI = powerSwitchAPI($uri, $fields, $method);

if(isset($_GET['dev'])){
	print("<pre>");
	print_r($powerSwitchAPI);
	print("</pre>");
}


echo json_encode($powerSwitchAPI);

?>