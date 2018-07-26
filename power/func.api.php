<?
require_once("globals.php");

function powerSwitchAPI($uri,$fields,$method){
	
	$ch 		= curl_init();
	$url 		=	"http://".USERNAME.":".PASSWORD."@".SWITCH_IP.$uri;
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	if($fields){
		// Only format fields if applicable
		$fields 	=	http_build_query($fields);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	}

	$headers = array();
	$headers[] = "X-Csrf: x";
	$headers[] = "Accept: application/json";
	$headers[] = "Content-Type: application/x-www-form-urlencoded";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result['url']			=	$url;
	$result['fields'] 		=	$fields;
	$result['result'] 		= 	curl_exec($ch);
	$result['http_code']	=	curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close ($ch);

	return $result;
}
?>