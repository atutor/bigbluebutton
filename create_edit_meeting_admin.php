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
if($_REQUEST['create']){
	if($_REQUEST['course_time'] !='' && $_REQUEST['course_message'] !=''){
		$bbb_meeting_name = $addslashes($_REQUEST['course_name']);
		$bbb_message = $addslashes($_REQUEST['course_message']);
		$bbb_meeting_time = $addslashes($_REQUEST['course_time']);
		$bbb_course_id = intval($_REQUEST['course_id']);
	
		$sql ="INSERT into %sbigbluebutton VALUES ('',  %d,'%s', '%s','%s','1')";
        $result = queryDB($sql, array(TABLE_PREFIX, $bbb_course_id, $bbb_meeting_name, $bbb_meeting_time, $bbb_message));

		if($result > 0){
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
			exit;

		}else{
			$msg->addError('ACTION_FAILED');
		}
	}else{
	
			$msg->addError('TIME_REQUIRED_BBB');
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_instructor.php?course_timing='.urlencode($stripslashes($_REQUEST['course_timing'])).SEP.'course_message='.urlencode($stripslashes($_REQUEST['course_message'])));
			exit;
	}
// Update an existing BBB meeting

} else if ($_REQUEST['editthis']){
	if($_REQUEST['course_time'] !='' && $_REQUEST['course_message'] !=''){
		$bbb_meeting_id = intval($_REQUEST[meeting_id]);
		$bbb_meeting_name = $addslashes($_REQUEST['course_name']);
		$bbb_message = htmlentities_utf8($addslashes($_REQUEST['course_message']));
		$bbb_meeting_time = $addslashes($_REQUEST['course_time']);
		$bbb_meeting_status = intval($_REQUEST['meeting_status']);
		$bbb_course_id =  intval($_REQUEST['course_id']);

		$sql ="UPDATE %sbigbluebutton SET course_id =  '%d', course_name = '%s', message = '%s', course_timing = '%s', status = %d WHERE meeting_id = %d";
	    $result = queryDB($sql, array(TABLE_PREFIX, $bbb_course_id, $bbb_meeting_name, $bbb_message, $bbb_meeting_time, $bbb_meeting_status, $bbb_meeting_id));

		if($result > 0){
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index_admin.php');
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

if(isset($_REQUEST['meeting_id'])){ 
	$meeting_id = intval($_REQUEST['meeting_id']);

	$sql = "SELECT * from %sbigbluebutton WHERE meeting_id = %d";
	$rows_meetings = queryDB($sql, array(TABLE_PREFIX, $meeting_id));

	foreach($rows_meetings as $row){
		$this_meeting_id  = $row['meeting_id'];
		$bbb_course_id  = $row['course_id'];
		$meeting_name = $row['course_name'];
		$meeting_time = $row['course_timing'];
		$meeting_status = $row['status'];
		$course_message = htmlentities_utf8($addslashes($row['message']));
	}

	?>

	<div class="input-form"  style="padding:1em;">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="form">
	<input type='hidden' name='create_classroom' value="checked">

		<input type='hidden' name='meeting_id' value="<?php echo $meeting_id; ?>">
		<dl>
			<dt><label for="course_title"><?php echo _AT('bbb_course_title'); ?></label></dt>
			<dd>
			<?php

				$sql2 = "SELECT title, course_id from %scourses";
				$rows_courses = queryDB($sql2, array(TABLE_PREFIX));
			?>
			<select name="course_id">
			<?php
				foreach($rows_courses as $row2){
					$selected = '';

					if($bbb_course_id == $row2['course_id']){
					 $selected = ' selected="selected"';
					}
					echo '<option value="'.$row2['course_id'].'"  '.$selected.'>'.$row2['title'].'</option>';
				}
			?>
			
			</select></dd>
		<dt><label for="meeting_name"><?php echo _AT('bbb_meeting_name'); ?></label></dt>
			<dd><input type="text" name="course_name" id="meeting_name" value="<?php echo $meeting_name; ?>"/></dd>
		<dt><label for="time"><?php echo _AT('bbb_meeting_time'); ?></label></dt>
			<dd><input type="text" name="course_time" id="time" value="<?php echo $meeting_time; ?>"/></dd>
		<dt><label for="message"><?php echo _AT('bbb_message'); ?></label></dt>
			<dd><textarea name="course_message" id="message" rows="2"  cols="20"><?php echo html_entity_decode($addslashes($course_message)); ?></textarea></dd>
			
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

	<div class="input-form" style="padding:1em;">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="form">
	<input type='hidden' name='create_meeting' value="checked">
	<input type='hidden' name='meeting_status' value="1">
		<dl>
			<dt><label for="course_title"><?php echo _AT('bbb_course_title'); ?></label></dt>
			<dd>
			<?php
				$sql = 'SELECT title, course_id from %scourses';
				$rows_titles = queryDB($sql, array(TABLE_PREFIX));
			?>
			<select name="course_id">
			<?php
				foreach($rows_titles as $row){
					if($course_id == $row['course_id']){
					 $selected = 'selected="selected"';
					}
					echo '<option value="'.$row['course_id'].'" '.$selected.'>'.$row['title'].'</option>';
				}
			
			?>
			
			</select></dd>
		<dt><label for="meeting_name"><?php echo _AT('bbb_meeting_name'); ?></label></dt>
			<dd><input type="text" name="course_name" id="meeting_name" value="<?php echo urldecode($stripslashes($_REQUEST['course_name'])); ?>"/></dd>

		<dt><label for="time"><?php echo _AT('bbb_meeting_time'); ?></label></dt>
			<dd><input type="text" name="course_time" id="time" value="<?php echo urldecode($stripslashes($_REQUEST['course_time'])); ?>"/></dd>
			<dt><label for="message"><?php echo _AT('bbb_message'); ?></label></dt>
			<dd><textarea name="course_message" id="message" rows="2"  cols="20"><?php echo urldecode($stripslashes($_REQUEST['course_message'])); ?></textarea></dd>
		</dl>

    <input type="submit" value="<?php echo _AT('bbb_create_meeting'); ?>" name='create' class="button"/>
    </form>
    </div>

<?php } ?>
<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>