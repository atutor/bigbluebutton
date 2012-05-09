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
	if($_REQUEST['meetingId'] == ''){
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


////////////////////////
// Delete BBB meeting recording after confirming

if($_REQUEST['delete_meeting'] > "0"){
	$delete_meeting = intval($_REQUEST['delete_meeting']);
	//echo $delete_meeting;
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

//////////////////////////
// Delete meeting and any recordings associated with it

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
	exit;
} else if (isset($_POST['submit_yes'])) {
	$meetingId = intval($_POST['meetingId']);

	bbb_delete_meeting($meetingId);

	$sql ="DELETE from ".TABLE_PREFIX."bigbluebutton WHERE meeting_id = '$meetingId'";
	$result = mysql_query($sql,$db);
	
	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
	exit;
}

///////////// END DELETE MEETING

$bbb_recordURL = $result['0']['playbackFormatUrl'];
$bbb_deleteURL = $result['0']['recordId'];


require (AT_INCLUDE_PATH.'header.inc.php');
global $_base_href;


if(isset($_REQUEST['delete']) && isset($confirm_delete)){

	if($_REQUEST['meetingId'] == ''){
		$msg->addError('SELECT_MEETING');
	}else {
		$hidden_vars['meetingId'] = $_REQUEST['meetingId'];
		$msg->addConfirm("BBB_DELETE_CONFIRM", $hidden_vars); 
		$msg->printConfirm();
				 
		 require (AT_INCLUDE_PATH.'footer.inc.php');
		 exit;
	}
}

?>


<div class="input-form" style="padding:.5em;">
<p><?php echo _AT('bbb_config_text'); ?></p>

<form name="form" action="<?php echo $_base_href; ?>mods/bigbluebutton/change_admin.php" method="post">
<label for="url"><?php echo _AT('bbb_url'); ?></label><br />
<input type="text" name="bbb_url" id="url" class="input" maxlength="60" size="40" value="<?php echo $_config['bbb_url']  ?>" /><br />
<label for="salt"><?php echo _AT('bbb_salt'); ?></label><br />
<input type="text" name="bbb_salt" id="salt" maxlength="60" size="40"  value="<?php echo $_config['bbb_salt'];  ?>" /><br />
<label for="bbb_max_recordings"><?php echo _AT('bbb_max_recording'); ?></label><br />
<input type="text" name="bbb_max_recordings" id="bbb_max_recordings"  size="3"  value="<?php echo $_config['bbb_max_recordings'];  ?>" /><br />
<input type="submit" name="submit" value="<?php echo _AT('save'); ?>">
</form>

</div>

<?php
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

<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>