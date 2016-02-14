<?php

include_once "../../config.php";

$t                = optional_param('t', NULL, PARAM_TEXT);
$input            = optional_param('input', NULL, PARAM_TEXT);

$data = get_records($t, array(), '`category`', 'DISTINCT `category`');

$len = strlen($input);

$aResults = array();

$c = 0;

if ($len) 
	while(list($key,$value)=each($data)) {
		if (strtolower(substr(utf8_decode($value->category),0,$len)) == strtolower($input))
			$aResults[] = array("category"=>htmlspecialchars($value->category));
	}
else
	while(list($key,$value)=each($data)) {
		$c++;
		if ($c < 10)
			$aResults[] = array("category"=>htmlspecialchars($value->category));
	}


header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Content-Type: application/json");

$res = "{\"results\": [";
$arr = array();
$j = 0;
for ($i=0;$i<count($aResults);$i++){
	$j++;
	$arr[] = "{\"id\": \"".$i."\",\"value\": \"".$aResults[$i]['category']."\"}";
}
$res .= implode(", ", $arr);
$res .= "]}";

if (!empty($j))
  echo $res;