<?php
/****************************************************************/
/* BigBlueButton module for ATutor                              */
/* https://github.com/nishant1000/BigBlueButton-module-for-ATutor*/
/*                                                              */
/* Author: Nishant Kumar										*/
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_BIGBLUEBUTTON);


require_once( "config.php");
if(CONFIG_SECURITY_SALT ==''){
 $msg->printFeedbacks('NOT_CONFIGURED');
 require (AT_INCLUDE_PATH.'footer.inc.php');
 exit;
}

////////////////////////
// Initialize BigBlueButton

require_once("bbb-api.php");
$bbb = new BigBlueButton();
require_once("bbb_atutor.lib.php");

if(isset($_REQUEST['delete'])){

	if($_REQUEST['aid'] == ''){

		$msg->addError('SELECT_MEETING');
	} else {
		$confirm_delete = 'true';
	}
}

if(isset($_REQUEST['edit'])){ 
	if($_REQUEST['aid'] == ''){
		$msg->addError('SELECT_MEETING');
	}else{
		header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/create_edit_meeting_admin.php?meeting_id='.$_REQUEST['aid']);
		exit;
	}
}
//////////////////////////
// Delete meeting and any recordings associated with it

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
	exit;
} else if (isset($_POST['submit_yes']) && isset($_POST['meetingId'])) {
	$meetingId = intval($_POST['meetingId']);

	bbb_delete_meeting($meetingId);

	$sql ="DELETE from %sbigbluebutton WHERE meeting_id = %d";
	$result = queryDB($sql, array(TABLE_PREFIX, $meetingId));	

	if($result > 0){
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	    header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
	    exit;
	} else{
	    $msg->addError('BBB_ACTION_FAILED');
	    header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
	    exit;
	}
}

////////////////////////
// Delete BBB meeting recording after confirming

if($_REQUEST['delete_meeting'] > "0"){
	$delete_meeting = intval($_REQUEST['delete_meeting']);
	if(isset($_REQUEST['delete_meeting'])) {
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

	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
	exit;
}	

/////////////////////// End delete recording



///////////// END DELETE MEETING

$bbb_recordURL = $result['0']['playbackFormatUrl'];
$bbb_deleteURL = $result['0']['recordId'];


require (AT_INCLUDE_PATH.'header.inc.php');

global $_base_href;


if(isset($_REQUEST['delete']) && isset($confirm_delete)){

	if($_REQUEST['aid'] == ''){
		$msg->addError('SELECT_MEETING');
	}else {
		$hidden_vars['meetingId'] = $_REQUEST['aid'];
		$msg->addConfirm("BBB_DELETE_CONFIRM", $hidden_vars); 
		$msg->printConfirm();
				 
		 require (AT_INCLUDE_PATH.'footer.inc.php');
		 exit;
	}
}

?>



<?php

$sql = "SELECT * from %sbigbluebutton";
$rows_meetings = queryDB($sql, array(TABLE_PREFIX));

if($rows_meetings > 0){
	$savant->assign('bbb', $bbb);
	$savant->assign('rows_meetings', $rows_meetings);
	$savant->assign('response', $response);
	$savant->assign('bbb_joinURL', $bbb_joinURL);
	$savant->assign('bbb_recordURL', $bbb_recordURL);
	$savant->assign('bbb_deleteURL', $bbb_deleteURL);
	$savant->display('templates/index_admin.tmpl.php');

} else{

	$msg->printFeedbacks('NO_MEETINGS');
}

function get_course_title($course_id){
	global $db;
	$sql = "SELECT title FROM %scourses WHERE course_id = %d";
	$row = queryDB($sql, array(TABLE_PREFIX, $course_id), TRUE);
	return $row['title'];
}

?>

<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>