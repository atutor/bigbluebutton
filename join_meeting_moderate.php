<?php
/****************************************************************/
/* BigBlueButton module for ATutor                              */
/* https://github.com/nishant1000/BigBlueButton-module-for-ATutor*/
/*                                                              */
/* This module allows to search OpenLearn for educational       */
/* content.														*/
/* Author:Greg Gay										*/
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

// 1. define relative path to `include` directory:
define('AT_INCLUDE_PATH', '../../include/');

// 2. require the `vitals` file before any others:
require (AT_INCLUDE_PATH . 'vitals.inc.php');

// A hack to redirect student to the index.php file in the module
// Resolves a known bug in BBB, but will also prevent users given BBB priveleges from accessing this page
// Test again when bbb0.8 comes out.

//if(!$_SESSION['is_admin'] && admin_authenticate(AT_ADMIN_PRIV_ADMIN, FALSE)){
//	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index.php');
//	exit;
//}
////////////////////////
// Initialize BigBlueButton
require_once( "config.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();
require("bbb_atutor.lib.php");
//$bbb_joinURL;


$_courseId=$_SESSION['course_id'];
$_courseTiming=$_POST['course_timing'];
$_courseMessage=$_POST['course_message'];
$_moderatorPassword="mp";
$_attendeePassword="ap";   
$_logoutUrl= $_base_href.'mods/bigbluebutton/index_instructor.php';
if($_SESSION['course_id'] == "-1"){
	$username=$_SESSION['login'];
}else{
	$username=get_login(intval($_SESSION['member_id']));
}

$meetingID=$_GET['meetingId'];
$bbb_welcome = _AT('bbb_welcome');
if (authenticate(AT_PRIV_BIGBLUEBUTTON) || admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)){
	//alls well
}else{
	require (AT_INCLUDE_PATH.'header.inc.php'); 
	$msg->printInfos('NO_PERMISSION');
	require (AT_INCLUDE_PATH.'footer.inc.php'); 
	exit;
}
if(isset($_GET['record'])){
	// Check to see if max recordings has been reached
	$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton WHERE course_id = '$_courseId'";
	$result = mysql_query($sql,$db);
	$this_recordings = '';
	$recordings = '0';
	while($row = mysql_fetch_assoc($result)){

		$this_recording = bbb_get_recordings($row['meeting_id']);
		if(preg_match("/http/",$this_recording)){
			$recordings = ($recordings+1);
		}
	}
	if($recordings >= BBB_MAX_RECORDINGS){
		$record = 'false';
		$msg->addInfo("MAX_REACHED");
	}else{
		// If not max recording reached, allow to record meeting
		$record = 'true';
	}
}else{
	$record = 'false';
}

require (AT_INCLUDE_PATH.'header.inc.php'); 
//debug($_REQUEST);


//$salt = $_config['bbb_salt'];
//$url = $_config['bbb_url']."/bigbluebutton/";
//debug($_SERVER['HTTP_HOST']);
?>
<div>
<p style="border:1px solid #cccccc; padding:1em;border-radius:.3em;background-color:#eeeeee;"><?php echo _AT('bbb_continue_text'); ?></p>

<?php

$bbb_joinURL = bbb_join_meeting_moderate($_attendeePassword,$_moderatorPassword,$_logoutUrl,$meetingID, $record, $username);


/*
	// If recording
	$creationParams = array(
		'meetingId' => $meetingID, 					// REQUIRED
		'meetingName' => 'Course Meeting', 	// REQUIRED
		'attendeePw' => $_attendeePassword, 					// Match this value in getJoinMeetingURL() to join as attendee.
		'moderatorPw' => $_moderatorPassword, 					// Match this value in getJoinMeetingURL() to join as moderator.
		'welcomeMsg' => '', 	// ''= use default. Change to customize.
		'dialNumber' => '', 					// The main number to call into. Optional.
		'voiceBridge' => '', 					// PIN to join voice. Optional.
		'webVoice' => '', 						// Alphanumeric to join voice. Optional.
		'logoutUrl' => $_logoutUrl."?meeting_id=".$meetingID, // Default in bigbluebutton.properties. Optional.
		'maxParticipants' => '-1', 				// Optional. -1 = unlimitted. Not supported in BBB. [number]
		'record' => $record, 					// New. 'true' will tell BBB to record the meeting.
		'duration' => '0', 						// Default = 0 which means no set duration in minutes. [number]
		//'meta_category' => '', 				// Use to pass additional info to BBB server. See API docs.
	);

	$itsAllGood = true;
	//debug($creationParams);
	//debug($bbb->createMeetingWithXmlResponseArray($creationParams));
	try {$response = $bbb->createMeetingWithXmlResponseArray($creationParams);}
		catch (Exception $e) {
			echo 'Caught exception1: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	
	if ($itsAllGood == true) {
		// If it's all good, then we've interfaced with our BBB php api OK:
		if ($response == null) {
			// If we get a null response, then we're not getting any XML back from BBB.
			echo "Failed to get any response. Maybe we can't contact the BBB server.";
		} 
	}

	$joinParams = array(
		'meetingId' => $meetingID, 				// REQUIRED - We have to know which meeting to join.
		'username' => $username,		// REQUIRED - The user display name that will show in the BBB meeting.
		'password' => $_moderatorPassword,	// REQUIRED - Must match either attendee or moderator pass for meeting.
		'createTime' => '',					// OPTIONAL - string
		'userId' => $_SESSION['member_id'],	// OPTIONAL - string
		'webVoiceConf' => ''				// OPTIONAL - string
	);
	//debug($joinParams);
	$itsAllGood = true;
	try {$bbb_joinURL = $bbb->getJoinMeetingURL($joinParams);}
		catch (Exception $e) {
			echo 'Caught exception2: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
*/
?>
<p> <?php echo _AT('bbb_continue_yes'); ?>  <a href="<?php echo $bbb_joinURL;?>"><?php echo _AT('bbb_yes_join');  ?></a> <?php echo _AT('or'); ?> <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><?php echo _AT('bbb_no_cancel');  ?></a>


</div>

<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>