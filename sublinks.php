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
if (!defined('AT_INCLUDE_PATH')) { exit; }

global $db;
global $_base_href, $msg, $_config;
$link_limit = 3;		// Number of links to be displayed on "detail view" box

$sql = "SELECT * from %sbigbluebutton WHERE course_id = %d";
$rows_meetings = queryDB($sql, array(TABLE_PREFIX, $_SESSION[course_id]));

if(count($rows_meetings) > 0){
    foreach($rows_meetings as $row){
		if($_SESSION['is_admin']){
		$list[] = '<a href="'.$_base_href.'mods/bigbluebutton/join_meeting_moderate.php?meeting_id='.$row['meeting_id'].'"'. (strlen($row['course_timing']) > SUBLINK_TEXT_LEN ? ' title="'.$row['course_timing'].'"' : '') .' title="'.$row['course_timing'].'">'. 
		          validate_length(htmlentities_utf8($row['course_name']), SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY) .'</a>';
		}else{
		
		$list[] = '<a href="'.$_base_href.'mods/bigbluebutton/join_meeting.php?meeting_id='.$row['meeting_id'].'"'. (strlen($row['course_timing']) > SUBLINK_TEXT_LEN ? ' title="'.$row['course_timing'].'"' : '') .' title="'.$row['course_timing'].'">'. 
		          validate_length(htmlentities_utf8($row['course_name']), SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY) .'</a>';
		}
	}
	return $list;	
} else {
	return 0;
}

?>