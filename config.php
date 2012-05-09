<?php
// BASE CONFIGS (set these to match the values from your BBB server)
/* Public test server values from Blind Side Networks:
url: http://test-install.blindsidenetworks.com/bigbluebutton/
salt: 8cd8ef52e8e101574e400365b55e11a6
*/


if($_config['bbb_salt'] =='' || !isset($_config['bbb_salt'])){
$hide_salt = file_get_contents('https://www.atutorspaces.com/include/bbb_salt.php');

	$salt = $hide_salt;
} else{
	$salt = $_config['bbb_salt']; 
}

if($_config['bbb_url'] =='' || !isset($_config['bbb_url'])){

	$url = 'http://bbb.atutorspaces.com';
} else{
	$url = $_config['bbb_url']; 
}

if($_config['bbb_max_recordings'] =='' || !isset($_config['bbb_max_recordings'])){

	define('BBB_MAX_RECORDINGS', '0');
} else{
	define('BBB_MAX_RECORDINGS', $_config['bbb_max_recordings']); 
}


define("CONFIG_SECURITY_SALT", $salt);
define("CONFIG_SERVER_BASE_URL", $url."/bigbluebutton/");

?>