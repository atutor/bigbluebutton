<?php 
/****************************************************************/
/* BigBlueButton module for ATutor                              */
/* https://github.com/nishant1000/BigBlueButton-module-for-ATutor*/
/*                                                              */
/* Author: Greg Gay										*/
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

/* start output buffering: */
ob_start(); 


global $db, $_base_href, $_base_path, $msg, $_config, $savant;
$link_limit = 3;		// Number of links to be displayed on "detail view" box

$sql = "SELECT * from %sbigbluebutton WHERE course_id = %d";
$rows_meetings = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id']));

if(count($rows_meetings) > 0){
	echo "<ul>";
	foreach($rows_meetings as $row){
		/****
		* SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY are defined in include/lib/constance.lib.inc
		* SUBLINK_TEXT_LEN determins the maxium length of the string to be displayed on "detail view" box.
		*****/
		if($_SESSION['is_admin']){
		echo '<li><a href="'.$_base_href.'mods/bigbluebutton/join_meeting_moderate.php?meeting_id='.$row['meeting_id'].'"'.
		          (strlen($row['course_timing']) > SUBLINK_TEXT_LEN ? ' title="'.$row['course_timing'].'"' : '') .' title="'.$row['course_timing'].'">'. 
		          validate_length(htmlentities_utf8($row['course_name']), SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY) .'</a></li>';
		 }else{
		 echo '<li><a href="'.$_base_href.'mods/bigbluebutton/join_meeting.php?meeting_id='.$row['meeting_id'].'"'.
		          (strlen($row['course_timing']) > SUBLINK_TEXT_LEN ? ' title="'.$row['course_timing'].'"' : '') .' title="'.$row['course_timing'].'">'. 
		          validate_length(htmlentities_utf8($row['course_name']), SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY) .'</a></li>';
		 
		 }
	}
		echo "</ul>";

}else{

	echo _AT('none_found');
}

$savant->assign('dropdown_contents', ob_get_contents());
ob_end_clean();

$savant->assign('title', _AT('BigBlueButton')); // the box title
$savant->display('include/box.tmpl.php');

?>