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

// 1. define relative path to `include` directory:
define('AT_INCLUDE_PATH', '../../include/');

// 2. require the `vitals` file before any others:
require (AT_INCLUDE_PATH . 'vitals.inc.php');

// A hack to redirect student to the index.php file in the module
// Resolves a known bug in BBB, but will also prevent users given BBB priveleges from accessing this page
// Test again when bbb0.8 comes out.

if(!$_SESSION['is_admin']){
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index.php');
	exit;
}
authenticate(AT_PRIV_BIGBLUEBUTTON);


// After confirming deleting a BBB meeting either delte it of return to the meeting list on cancel

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
	exit;
} else if (isset($_POST['submit_yes'])) {
	$sql ="DELETE from ".TABLE_PREFIX."bigbluebutton WHERE course_id = '$_SESSION[course_id]'";
	$result = mysql_query($sql,$db);
	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
	exit;
}

// Create BBB Meeting

if($_GET['create']){
	if($_GET['course_timing'] !='' && $_GET['course_message'] !=''){
		$bbb_message = $addslashes($_GET['course_message']);
		$bbb_meeting_time = $addslashes($_GET['course_timing']);
		$sql ="INSERT into ".TABLE_PREFIX."bigbluebutton VALUES ('$_SESSION[course_id]','$bbb_meeting_time','$bbb_message ')";
		if($result = mysql_query($sql,$db)){
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
			exit;
		}else{
			$msg->addError('ACTION_FAILED');
		}
	}else{
	
			$msg->addError('TIME_REQUIRED_BBB');
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php?course_timing='.urlencode($stripslashes($_GET['course_timing'])).SEP.'course_message='.urlencode($stripslashes($_GET['course_message'])));
			exit;
	}
// Update an existing BBB meeting

} else if ($_GET['editthis']){
	if($_GET['course_timing'] !='' && $_GET['course_message'] !=''){
		$bbb_message = $addslashes($_GET['course_message']);
		$bbb_meeting_time = $addslashes($_GET['course_timing']);
		$sql ="UPDATE ".TABLE_PREFIX."bigbluebutton SET message = '$bbb_message', course_timing = '$bbb_meeting_time' WHERE course_id = '$_SESSION[course_id]'";
	
		if($result = mysql_query($sql, $db)){
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		}else{
			$msg->addError('BBB_ACTION_FAILED');
		}
	}else{
	
			$msg->addError('TIME_REQUIRED_BBB');
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php?edit=1');
			exit;
	}
}

require (AT_INCLUDE_PATH.'header.inc.php');
require_once( "bbb_api_conf.php");
require_once("bbb_api.php");

// Confirm deleting a BBB meeting before actually deleting it.

if(isset($_GET['delete'])){
	require_once(AT_INCLUDE_PATH . '/classes/Message/Message.class.php');
	//global $savant;
	$msg->addConfirm("BBB_DELETE_CONFIRM"); 
	$msg->printConfirm();
}

// Set some variables

$bbb_joinURL;
$_courseId=$_SESSION['course_id'];
$_courseTiming=$_POST['course_timing'];
$_courseMessage=$_POST['course_message'];
$_moderatorPassword="mp";
$_attendeePassword="ap";   
$_logoutUrl= $_base_href.'mods/bigbluebutton/index_instructor.php';
$username=get_login(intval($_SESSION['member_id']));
$meetingID=$_SESSION['course_id'];
$bbb_welcome = _AT('bbb_welcome');
$salt = $_config['bbb_salt'];
$url = $_config['bbb_url']."/bigbluebutton/";

$response = BigBlueButton::createMeetingArray($username,$meetingID,$bbb_welcome,$_moderatorPassword,$_attendeePassword, $salt, $url,$_logoutUrl);

//Analyzes the bigbluebutton server's response

if(!$response){//If the server is unreachable

	$msg->addError("UNABLE_TO_CONNECT_TO_BBB");
	
}else if( $response['returncode'] == 'FAILED' ) { //The meeting was not created

	if($response['messageKey'] == 'checksumError'){
	
		$msg->addError("CHECKSUM_ERROR_BBB");
	}
	else{
	
		$msg = $response['message'];
	}
}else{  //The meeting was created, and the user can now join

	$bbb_joinURL = BigBlueButton::joinURL($meetingID,$username,"mp", $salt, $url);

}

$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton WHERE course_id = '$meetingID'";
$result = mysql_query($sql, $db);

if(mysql_num_rows($result) != 0 && !isset($_GET['edit'])){

	$savant->assign('result', $result);
	$savant->assign('bbb_joinURL', $bbb_joinURL);
	$savant->display('templates/index_instructor.tmpl.php');

}else if(isset($_GET['edit'])){ 


	while($row = mysql_fetch_assoc($result)){
		$meeting_time = $row['course_timing'];
		$course_message = htmlentities_utf8($row['message']);
	}
	?>

	<div class="input-form">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="form">
	<input type='hidden' name='create_classroom' value="checked">
		<dl>
		<dt><label for="time"><?php echo _AT('bbb_meeting_time'); ?></label></dt>
			<dd><input type="text" name="course_timing" id="time" value="<?php echo $meeting_time; ?>"/></dd>
		<dt><label for="message"><?php echo _AT('bbb_message'); ?></label></dt>
			<dd><textarea name="course_message" id="message" rows="2"  cols="20"><?php echo $course_message; ?></textarea></dd>
		</dl>

    <input type="submit" value="<?php echo _AT('bbb_edit_meeting'); ?>" name='editthis' class="button"/>
    </form>
    </div>
<?php }else{ ?>

	<div class="input-form">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="form">
	<input type='hidden' name='create_meeting' value="checked">
		<dl>
		<dt><label for="time"><?php echo _AT('bbb_meeting_time'); ?></label></dt>
			<dd><input type="text" name="course_timing" id="time" value="<?php echo urldecode($stripslashes($_GET['course_timing'])); ?>"/></dd>
		<dt><label for="message"><?php echo _AT('bbb_message'); ?></label></dt>
			<dd><textarea name="course_message" id="message" rows="2"  cols="20"><?php echo urldecode($stripslashes($_GET['course_message'])); ?></textarea></dd>
		</dl>

    <input type="submit" value="<?php echo _AT('bbb_create_meeting'); ?>" name='create' class="button"/>
    </form>
    </div>

<?php } ?>

<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>