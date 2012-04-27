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
admin_authenticate(AT_ADMIN_PRIV_BIGBLUEBUTTON);

////////////////////////
// Initialize BigBlueButton
require_once( "bbb_api_conf.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();

if(isset($_GET['edit'])){ 
	if($_GET['aid'] == ''){
		$msg->addError('SELECT_MEETING');
	}else{
		header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/create_edit_meeting.php?meeting_id='.$_GET['aid']);
		exit;
	}
}


////////////////////////
// Delete BBB meeting after confirming
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
if($_POST['delete_confirmed'] == 'yes'){
	$delete_id = intval($_POST['delete_id']);
	$recordingsParams = array(
		'meetingId' => $delete_id, 			// OPTIONAL - comma separate if multiples
	
	);

	try {$result = $bbb->getRecordingsWithXmlResponseArray($recordingsParams);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
		
	$bbb_deleteURL = $result['0']['recordId'];
	$recordingParams = array(
		'recordId' => $bbb_deleteURL
	);
	$itsAllGood = true;
	try {$result = $bbb->deleteRecordingsWithXmlResponseArray($recordingParams);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	
	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
	exit;
}	
/////////////////////// End delete recording

//////////////////////////
// Delete meeting and any recordings associated with it

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
	exit;
} else if (isset($_POST['submit_yes'])) {
	$meetingId = intval($_POST['meetingId']);
	$sql ="DELETE from ".TABLE_PREFIX."bigbluebutton WHERE meeting_id = '$meetingId'";
	$result = mysql_query($sql,$db);
	
	// delete any recordings for this meeting
		$recordingParams = array(
			'recordId' => $_POST['meetingId']
		);
		
		// Delete the recording
		$itsAllGood = true;
		try {$result = $bbb->deleteRecordingsWithXmlResponseArray($recordingParams);}
				catch (Exception $e) {
				echo 'Caught exception: ', $e->getMessage(), "\n";
				$itsAllGood = false;
			}
		
	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
	exit;
}

///////////// END DELETE MEETING

require (AT_INCLUDE_PATH.'header.inc.php');
global $_base_href;


?>
<h3><?php echo _AT('bbb_admin_setup');  ?> </h3><br />

<div class="input-form" style="padding:.5em;">
<p><?php echo _AT('bbb_config_text'); ?></p>

<form name="form" action="<?php echo $_base_href; ?>mods/bigbluebutton/change_admin.php" method="post">
<label for="url"><?php echo _AT('bbb_url'); ?></label><br />
<input type="text" name="bbb_url" id="url" class="input" maxlength="60" size="40" value="<?php echo $_config['bbb_url']  ?>" /><br />
<label for="url"><?php echo _AT('bbb_salt'); ?></label><br />
<input type="text" name="bbb_salt" id="salt" maxlength="60" size="40"  value="<?php echo $_config['bbb_salt']  ?>" /><br />
<input type="submit" name="submit" value="<?php echo _AT('save'); ?>">
</form>

</div>
<?php


$bbb_recordURL = $result['0']['playbackFormatUrl'];
$bbb_deleteURL = $result['0']['recordId'];


$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton";
$result = mysql_query($sql, $db);

if(mysql_num_rows($result) != 0){

	$savant->assign('bbb', $bbb);
	$savant->assign('result', $result);
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
	$sql = "SELECT title FROM ".TABLE_PREFIX."courses WHERE course_id = '$course_id'";
	$result = mysql_query($sql, $db);
	$row = mysql_fetch_assoc($result);
	return $row['title'];
}

?>
<!--
<a href="<?php echo $_config['bbb_url']; ?>" target="bbb">(<?php echo _AT('bbb_open_new_win');  ?>)</a><br />               
<iframe src="<?php echo $_config['bbb_url']; ?>" width="100%" height="600">
  <p>Your browser does not support iframes.</p>
</iframe> -->
<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>