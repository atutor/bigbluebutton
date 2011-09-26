<?php
/****************************************************************/
/* BigBlueButton module for ATutor                              */
/* https://github.com/nishant1000/BigBlueButton-module-for-ATutor*/
/*                                                              */
/* This module allows to search OpenLearn for educational       */
/* content.														*/
/* Author: Nishant Kumar										*/
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

$_custom_css = $_base_path . 'mods/bigbluebutton/module.css'; // use a custom stylesheet
require (AT_INCLUDE_PATH.'header.inc.php');

require "bbb_api_conf.php";
require "bbb_api.php";

$bbb_joinURL;
$_moderatorPassword="mp";
$_attendeePassword="ap";   
$_logoutUrl= $_base_href.'mods/bigbluebutton/index.php';
$username=get_login(intval($_SESSION["member_id"]));
$meetingID=$_SESSION['course_id'];
$response = BigBlueButton::createMeetingArray($username,$meetingID,"Welcome to the Classroom", $_moderatorPassword,$_attendeePassword, $salt, $url,$_logoutUrl);

//Analyzes the bigbluebutton server's response
if(!$response){//If the server is unreachable
	$msg = 'Unable to join the meeting. Please check the url of the bigbluebutton server AND check to see if the bigbluebutton server is running.';
}
else if( $response['returncode'] == 'FAILED' ) { //The meeting was not created
	if($response['messageKey'] == 'checksumError'){
		$msg = 'A checksum error occured. Make sure you entered the correct salt.';
	}
	else{
		$msg = $response['message'];
	}
}
else{//"The meeting was created, and the user will now be joined "
	$bbb_joinURL = BigBlueButton::joinURL($meetingID,$username,"ap", $salt, $url);
	
}

$_courseId=$_SESSION['course_id'];
 
$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton WHERE course_id = '$_SESSION[course_id]'";
$result = mysql_query($sql, $db);

if(mysql_num_rows($result) != 0 && !isset($_GET['edit'])){

	$savant->assign('result', $result);
	$savant->assign('bbb_joinURL', $bbb_joinURL);
	$savant->display('templates/index.tmpl.php');
}

 require (AT_INCLUDE_PATH.'footer.inc.php'); ?>