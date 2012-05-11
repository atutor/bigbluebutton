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

/* start output buffering: */
ob_start(); 


global $db, $_base_href, $_base_path, $msg, $_config, $savant;
$link_limit = 3;		// Number of links to be displayed on "detail view" box

$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton WHERE course_id = '$_SESSION[course_id]'";
$result = mysql_query($sql, $db);


if (mysql_num_rows($result) > 0) {
	echo "<ul>";
	while ($row = mysql_fetch_assoc($result)) {
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

}

$savant->assign('dropdown_contents', ob_get_contents());
ob_end_clean();

$savant->assign('title', _AT('BigBlueButton')); // the box title
$savant->display('include/box.tmpl.php');

?>