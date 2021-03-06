<?php
/****************************************************************/
/* BigBlueButton module for ATutor                              */
/* https://github.com/nishant1000/BigBlueButton-module-for-ATutor*/
/*                                                              */
/* Author: Nishant Kumar / Greg Gay								*/
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

// 1. define relative path to `include` directory:
define('AT_INCLUDE_PATH', '../../include/');

// 2. require the `vitals` file before any others:
require (AT_INCLUDE_PATH . 'vitals.inc.php');


////////////////////////
// Initialize BigBlueButton
require_once("config.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();
require("bbb_atutor.lib.php");


////////
// A hack to redirect student to the index.php file in the module
// Resolves a known bug in BBB, but will also prevent users given BBB priveleges from accessing this page
// Test again when bbb0.8 comes out.

if($_SESSION['course_id'] == -1){
	// if the admin started the meeting, admin ends it
	if($_GET['mod'] == $_SESSION['login']){
		$meeting_id = intval($_GET['meeting_id']);
		$modpwd = "mp";
		bbb_end_meeting($meeting_id,$modpwd);
		global $response;
		// Update the db to set the meeting to ended	
		$sql = "UPDATE %sbigbluebutton set status='3' WHERE meeting_id = %d";
		$result = queryDB($sql, array(TABLE_PREFIX, $meeting_id));
		
		$msg->addFeedback('MEETING_ENDED');
	}else {
		$msg->addFeedback('MEETING_ENDED_OTHER');
	}
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
	exit;
}else if(!$_SESSION['is_admin']){

	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index.php');
	exit;
}

authenticate(AT_PRIV_BIGBLUEBUTTON);

if(isset($_GET['delete'])){
	if($_GET['aid'] == ''){
		$msg->addError('SELECT_MEETING');
	} else {
		$confirm_delete = 'true';
	}
}


if(isset($_GET['edit'])){ 
	if($_GET['aid'] == ''){
		$msg->addError('SELECT_MEETING');
	}else{
		header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/create_edit_meeting.php?meeting_id='.$_GET['aid']);
		exit;
	}
}

///////////////////////
// End and existing meeting when the instructor logs out

if($_SERVER['HTTP_REFERER'] == $_config['bbb_url']."/client/BigBlueButton.html"){
	if($_GET['mod'] == $_SESSION['login']){

		$meeting_id = $_GET['meeting_id'];
		$modpwd = "mp";
		bbb_end_meeting($meeting_id,$modpwd);
		
		global $response;
		// Update the db to set the meeting to ended	
		$sql = "UPDATE %sbigbluebutton set status='3' WHERE meeting_id = %d";
		$result = queryDB($sql, array(TABLE_PREFIX, $meeting_id));

		if(BBB_MAX_RECORDINGS > 0){
			$msg->addFeedback('MEETING_ENDED');
			$msg->addFeedback('RECORDING_IN_PROGRESS');
		}else {
			$msg->addFeedback('MEETING_ENDED');
		}
	} else {
		$msg->addFeedback('MEETING_ENDED_OTHER');
	}
}
/////////////////////// end end meeting on logout

//////////////////////////
// Delete meeting and any recordings associated with it

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
	exit;
} else if (isset($_POST['submit_yes']) && isset($_POST['meetingId'])) {
	$meetingId = intval($_POST['meetingId']);

	bbb_delete_meeting($meetingId);

	$sql ="DELETE from %sbigbluebutton WHERE meeting_id = %d";
	$result = queryDB($sql, array(TABLE_PREFIX, $meetingId));	
	
	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
	exit;
}

////////////////////////
// Delete BBB meeting recording after confirming
if($_GET['delete_meeting'] > "0"){
	$delete_meeting = intval($_GET['delete_meeting']);
	if(isset($_GET['delete_meeting'])) {
		 require (AT_INCLUDE_PATH.'header.inc.php');
	
		$hidden_vars['delete_id'] = $delete_meeting;
		$hidden_vars['delete_confirmed'] = "yes";
		$confirm = array('DELETE_RECORDING', $names_html);
		$msg->addConfirm($confirm, $hidden_vars);
		$msg->printConfirm();
		 
		 require (AT_INCLUDE_PATH.'footer.inc.php');
		 exit;
	}
}

if($_POST['delete_confirmed'] == 'yes'){
	$delete_id = $_POST['delete_id'];
	
	bbb_delete_meeting($delete_id);
	
	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
	exit;
}	

/////////////////////// End delete recording



///////////// END DELETE MEETING

require (AT_INCLUDE_PATH.'header.inc.php');

// Confirm deleting a BBB meeting before actually deleting it.

if(isset($_GET['delete']) && isset($confirm_delete)){

	if($_GET['aid'] == ''){
		$msg->addError('SELECT_MEETING');
	}else {
		$hidden_vars['meetingId'] = $_GET['aid'];
		$msg->addConfirm("BBB_DELETE_CONFIRM", $hidden_vars); 
		$msg->printConfirm();
	}
}

$_courseId=$_SESSION['course_id'];
$bbb_recordURL = $result['0']['playbackFormatUrl'];
$bbb_deleteURL = $result['0']['recordId'];

$sql = "SELECT * from %sbigbluebutton WHERE course_id = %d";
$rows_meetings = queryDB($sql, array(TABLE_PREFIX, $_course_id));

if(count($rows_meetings) != 0){
	$savant->assign('bbb', $bbb);
	$savant->assign('rows_meetings', $rows_meetings);
	$savant->assign('bbb_joinURL', $bbb_joinURL);
	$savant->assign('bbb_recordURL', $bbb_recordURL);
	$savant->assign('bbb_deleteURL', $bbb_deleteURL);
	$savant->display('templates/index_instructor.tmpl.php');

} else{

	$msg->printFeedbacks('NO_MEETINGS');
}


?>


<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>