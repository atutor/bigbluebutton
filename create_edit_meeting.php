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

if (authenticate(AT_PRIV_BIGBLUEBUTTON) || admin_authenticate(AT_ADMIN_PRIV_BIGBLUEBUTTON, TRUE)){
	//alls well
}else{
	require (AT_INCLUDE_PATH.'header.inc.php'); 
	$msg->printInfos('NO_PERMISSION');
	require (AT_INCLUDE_PATH.'footer.inc.php'); 
	exit;
}
tool_origin();
/////////////////////////////
// Create BBB Meeting

if (isset($_GET['cancel'])) {
        $msg->addFeedback('CANCELLED');
        $return_url = $_SESSION['tool_origin']['url'];
        tool_origin('off');
		header('Location: '.$return_url);
		exit;
} else if($_GET['create']){
	if($_GET['course_time'] !='' && $_GET['course_message'] !=''){
		$bbb_meeting_name = $addslashes($_GET['course_name']);
		$bbb_message = $addslashes($_GET['course_message']);
		$bbb_meeting_time = $addslashes($_GET['course_time']);
		
		$sql ="INSERT into %sbigbluebutton VALUES ('',%d,'%s', '%s','%s','1')";	
		$result = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id'], $bbb_meeting_name, $bbb_meeting_time, $bbb_message));

		if($result > 0){
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			if($_SESSION['is_admin']){
                $return_url = $_SESSION['tool_origin']['url'];
                tool_origin('off');
		        header('Location: '.$return_url);
		        exit;
			}else{

			    tool_origin('off');
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
		$bbb_message = htmlentities_utf8($addslashes($_GET['course_message']));
		$bbb_meeting_time = $addslashes($_GET['course_time']);
		$bbb_meeting_status = intval($_GET['meeting_status']);

		$sql ="UPDATE %sbigbluebutton SET course_name = '%s', message = '%s', course_timing = '%s', status = %d WHERE meeting_id = %d";
		$result = queryDB($sql, array(TABLE_PREFIX, $bbb_meeting_name, $bbb_message, $bbb_meeting_time, $bbb_meeting_status, $_GET['meeting_id']));
		
		if($result > 0){				
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

	$sql = "SELECT * from %sbigbluebutton WHERE meeting_id = %d";
	$rows_meetings = queryDB($sql, array(TABLE_PREFIX, $meeting_id));
	
	foreach($rows_meetings as $row){
		$meeting_id  = $row['meeting_id'];
		$meeting_name = $row['course_name'];
		$meeting_time = $row['course_timing'];
		$meeting_status = $row['status'];
		$course_message = html_entity_decode($row['message']);
	}
	?>

	<div class="input-form" style="padding:1em;">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="form">
	<input type='hidden' name='create_classroom' value="checked">

		<input type='hidden' name='meeting_id' value="<?php echo $meeting_id; ?>">
		<dl>
		<dt><label for="meeting_name"><?php echo _AT('bbb_meeting_name'); ?></label></dt>
			<dd><input type="text" name="course_name" id="meeting_name" value="<?php echo $meeting_name; ?>"/></dd>
		<dt><label for="time"><?php echo _AT('bbb_meeting_time'); ?></label></dt>
			<dd><input type="text" name="course_time" id="time" value="<?php echo $meeting_time; ?>"/></dd>
		<dt><label for="message"><?php echo _AT('bbb_message'); ?></label></dt>
			<dd><textarea name="course_message" id="message" rows="2"  cols="20"><?php echo $course_message; ?></textarea></dd>
			
		<dt><label for="time"><?php echo _AT('bbb_meeting_status'); ?></label></dt>
			<dd><select name="meeting_status">
			<option value="1" <?php if($meeting_status == '1'){echo ' selected="selected"';} ?> ><?php echo _AT('bbb_meeting_pending'); ?></option>
			<option value="2" <?php if($meeting_status == '2'){echo ' selected="selected"';} ?> ><?php echo _AT('bbb_meeting_running'); ?></option>
			<option value="3" <?php if($meeting_status == '3'){echo ' selected="selected"';} ?> ><?php echo _AT('bbb_meeting_over'); ?></option>
			</select>
			</dd>
		</dl>

    <input type="submit" value="<?php echo _AT('bbb_edit_meeting'); ?>" name='editthis' class="button"/>
    <input type="submit" value="<?php echo _AT('cancel'); ?>" name='cancel' class="button"/>
    </form>
    </div>
<?php }else{ ?>

	<div class="input-form" style="padding:1em;">
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
    <input type="submit" value="<?php echo _AT('cancel'); ?>" name='cancel' class="button"/>
    </form>
    </div>

<?php } ?>
<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>