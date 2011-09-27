<?php
/****************************************************************/
/* BigBlueButton module for ATutor                              */
/* https://github.com/nishant1000/BigBlueButton-module-for-ATutor*/
/*                                                              */
/* This module allows to search OpenLearn for educational       */
/* content.														*/
/* Author: Greg Gay										*/
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$
if (!defined('AT_INCLUDE_PATH')) { exit; }

global $db;
global $_base_href, $msg, $_config;
$link_limit = 3;		// Number of links to be displayed on "detail view" box

require_once("bbb_api_conf.php");
require_once("bbb_api.php");

$bbb_joinURL;
$_moderatorPassword="mp";
$_attendeePassword="ap";   
$_logoutUrl= $_base_href.'index.php';
$username=get_login(intval($_SESSION["member_id"]));
$meetingID=$_SESSION['course_id'];
$bbb_welcome = _AT('bbb_welcome');
$salt = $_config['bbb_salt'];
$url = $_config['bbb_url']."/bigbluebutton/";

$response = BigBlueButton::createMeetingArray($username,$meetingID,$bbb_welcome, $_moderatorPassword,$_attendeePassword, $salt, $url,$_logoutUrl);

//Analyzes the bigbluebutton server's response
if(!$response){//If the server is unreachable
		$msg->addError("UNABLE_TO_CONNECT_TO_BBB");
}
else if( $response['returncode'] == 'FAILED' ) { //The meeting was not created
	if($response['messageKey'] == 'checksumError'){
		$msg->addError("CHECKSUM_ERROR_BBB");
	}
	else{
		$msg = $response['message'];
	}
}
else{//"The meeting was created, and the user will now be joined "
	$bbb_joinURL = BigBlueButton::joinURL($meetingID,$username,"ap", $salt, $url);
	
}
 
$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton WHERE course_id = '$meetingID'";
$result = mysql_query($sql, $db);

if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		/****
		* SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY are defined in include/lib/constance.lib.inc
		* SUBLINK_TEXT_LEN determins the maxium length of the string to be displayed on "detail view" box.
		*****/
		$list[] = '<a href="'.$bbb_joinURL.'"'.
		          (strlen(htmlentities_utf8($row['message'])) > SUBLINK_TEXT_LEN ? ' title="'.htmlentities_utf8($row['course_timing']).'"' : '') .' title="'.htmlentities_utf8($row['course_timing']).'">'. 
		          validate_length(htmlentities_utf8($row['message']), SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY) .'</a>';
	}
	return $list;	
} else {
	return 0;
}

?>