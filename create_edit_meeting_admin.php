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

// 1. define relative path to `include` directory:
define('AT_INCLUDE_PATH', '../../include/');

// 2. require the `vitals` file before any others:
require (AT_INCLUDE_PATH . 'vitals.inc.php');

// A hack to redirect student to the index.php file in the module
// Resolves a known bug in BBB, but will also prevent users given BBB priveleges from accessing this page
// Test again when bbb0.8 comes out.

//if(!$_SESSION['is_admin']){
//	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index.php');
//	exit;
//}
if (admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)){
	//alls well
}else{
	require (AT_INCLUDE_PATH.'header.inc.php'); 
	$msg->printInfos('NO_PERMISSION');
	require (AT_INCLUDE_PATH.'footer.inc.php'); 
	exit;
}
/////////////////////////////
// Create BBB Meeting


if($_GET['create']){
//debug($_GET);
	if($_GET['course_time'] !='' && $_GET['course_message'] !=''){
		$bbb_meeting_name = $addslashes($_GET['course_name']);
		$bbb_message = $addslashes($_GET['course_message']);
		$bbb_meeting_time = $addslashes($_GET['course_time']);
		
		$sql ="INSERT into ".TABLE_PREFIX."bigbluebutton VALUES ('','$_SESSION[course_id]','$bbb_meeting_name', '$bbb_meeting_time','$bbb_message ','1')";
		
		if($result = mysql_query($sql,$db)){
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			if($_SESSION['is_admin']){
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
			exit;
			}else{
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
			exit;
			
			}
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
	if($_GET['course_time'] !='' && $_GET['course_message'] !=''){
		$bbb_meeting_name = $addslashes($_GET['course_name']);
		$bbb_message = $addslashes($_GET['course_message']);
		$bbb_meeting_time = $addslashes($_GET['course_time']);
		$bbb_meeting_status = intval($_GET['meeting_status']);
		
		$sql ="UPDATE ".TABLE_PREFIX."bigbluebutton SET course_name = '$bbb_meeting_name', message = '$bbb_message', course_timing = '$bbb_meeting_time', status = '$bbb_meeting_status' WHERE meeting_id = '$_GET[meeting_id]'";
	
		if($result = mysql_query($sql, $db)){
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php');
			exit;
			
		}else{
			$msg->addError('BBB_ACTION_FAILED');
		}
	}else{
	
			$msg->addError('TIME_REQUIRED_BBB');
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/create_edit_meeting.php?edit=1');
			exit;
	}
}

?>
<?php  require (AT_INCLUDE_PATH.'header.inc.php'); ?>
<?php

if(isset($_GET['meeting_id'])){ 
	$meeting_id = intval($_GET['meeting_id']);
	$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton WHERE meeting_id = '$meeting_id'";
	$result = mysql_query($sql, $db);

	while($row = mysql_fetch_assoc($result)){
		$meeting_id  = $row['meeting_id'];
		$meeting_name = $row['course_name'];
		$meeting_time = $row['course_timing'];
		$meeting_status = $row['status'];
		$course_message = htmlentities_utf8($row['message']);
		//debug($meeting_status);
	}
	?>

	<div class="input-form">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="form">
	<input type='hidden' name='create_classroom' value="checked">

		<input type='hidden' name='meeting_id' value="<?php echo $meeting_id; ?>">
		<dl>
		<dt><label for="meeting_name"><?php echo _AT('bbb_meeting_name'); ?></label></dt>
			<dd><input type="text" name="course_name" id="meeting_name" value="<?php echo $meeting_name; ?>"/></dd>
		<dt><label for="time"><?php echo _AT('bbb_meeting_time'); ?></label></dt>
			<dd><input type="text" name="course_time" id="time" value="<?php echo $meeting_time; ?>"/></dd>
		<dt><label for="message"><?php echo _AT('bbb_message'); ?></label></dt>
			<dd><textarea name="course_message" id="message" rows="2"  cols="20"><?php echo htmlentities_utf8($course_message); ?></textarea></dd>
			
		<dt><label for="time"><?php echo _AT('bbb_meeting_status'); ?></label></dt>
			<dd><select name="meeting_status">
			<option value="1" <?php if($meeting_status == '1'){echo ' selected="selected"';} ?> ><?php echo _AT('bbb_meeting_pending'); ?></option>
			<option value="2" <?php if($meeting_status == '2'){echo ' selected="selected"';} ?> ><?php echo _AT('bbb_meeting_running'); ?></option>
			<option value="3" <?php if($meeting_status == '3'){echo ' selected="selected"';} ?> ><?php echo _AT('bbb_meeting_over'); ?></option>
			</select>
			</dd>
		</dl>

    <input type="submit" value="<?php echo _AT('bbb_edit_meeting'); ?>" name='editthis' class="button"/>
    </form>
    </div>
<?php }else{ ?>

	<div class="input-form">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="form">
	<input type='hidden' name='create_meeting' value="checked">
	<input type='hidden' name='meeting_status' value="1">
		<dl>
		<dt><label for="meeting_name"><?php echo _AT('bbb_meeting_name'); ?></label></dt>
			<dd><input type="text" name="course_name" id="meeting_name" value="<?php echo urldecode($stripslashes($_GET['course_name'])); ?>"/></dd>

		<dt><label for="time"><?php echo _AT('bbb_meeting_time'); ?></label></dt>
			<dd><input type="text" name="course_time" id="time" value="<?php echo urldecode($stripslashes($_GET['course_time'])); ?>"/></dd>
			<dt><label for="message"><?php echo _AT('bbb_message'); ?></label></dt>
			<dd><textarea name="course_message" id="message" rows="2"  cols="20"><?php echo urldecode($stripslashes($_GET['course_message'])); ?></textarea></dd>
		</dl>

    <input type="submit" value="<?php echo _AT('bbb_create_meeting'); ?>" name='create' class="button"/>
    </form>
    </div>

<?php } ?>
<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>