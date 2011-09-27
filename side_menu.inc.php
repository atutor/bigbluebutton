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

/* start output buffering: */
ob_start(); 


global $db, $_base_href, $_base_path, $msg, $_config, $savant;
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
else{  //"The meeting was created, and the user will now be joined "
	$bbb_joinURL = BigBlueButton::joinURL($meetingID,$username,"ap", $salt, $url);
	
}

$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton WHERE course_id = '$meetingID'";
$result = mysql_query($sql, $db);


if (mysql_num_rows($result) > 0) {
	echo "<ul>";
	while ($row = mysql_fetch_assoc($result)) {
		/****
		* SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY are defined in include/lib/constance.lib.inc
		* SUBLINK_TEXT_LEN determins the maxium length of the string to be displayed on "detail view" box.
		*****/
		echo '<li><a href="'.$bbb_joinURL.'"'.
		          (strlen($row['message']) > SUBLINK_TEXT_LEN ? ' title="'.$row['course_timing'].'"' : '') .' title="'.$row['course_timing'].'">'. 
		          validate_length(htmlentities_utf8($row['message']), SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY) .'</a></li>';
	}
		echo "</ul>";

}

$savant->assign('dropdown_contents', ob_get_contents());
ob_end_clean();

$savant->assign('title', _AT('BigBlueButton')); // the box title
$savant->display('include/box.tmpl.php');

?>