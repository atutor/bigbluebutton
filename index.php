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
require_once( "bbb_api_conf.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();

$bbb_joinURL;
$_courseId = $_SESSION['course_id'];
$_moderatorPassword="mp";
$_attendeePassword="ap";   
$_logoutUrl= $_base_href.'mods/bigbluebutton/index.php';
$username=get_login(intval($_SESSION["member_id"]));
$meetingID=$_SESSION['course_id'];
$bbb_welcome = _AT('bbb_welcome');
$salt = $_config['bbb_salt'];
$url = $_config['bbb_url']."/bigbluebutton/";	

/*
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
	$bbb_joinURL = BigBlueButton::joinURL($meetingID,$username,"ap", $salt, $url,$_logoutUrl);
	
}

$_courseId=$_SESSION['course_id'];
*/


$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton WHERE course_id = '$_courseId'";
$result = mysql_query($sql, $db);

if(mysql_num_rows($result) != 0 && !isset($_GET['edit'])){

	$savant->assign('result', $result);
	$savant->assign('bbb_joinURL', $bbb_joinURL);
	$savant->assign('bbb_recordURL', $bbb_recordURL);
	$savant->display('templates/index.tmpl.php');
/*
	$savant->assign('result', $result);
	$savant->assign('bbb_joinURL', $bbb_joinURL);
	$savant->display('templates/index.tmpl.php');
*/
}

 require (AT_INCLUDE_PATH.'footer.inc.php'); ?>