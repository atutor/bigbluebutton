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

function bigbluebutton_news() {
	global $db, $enrolled_courses, $system_courses;
	$news = array();
	if ($enrolled_courses == ''){
		return $news;
	} 

    $sql = 'SELECT * FROM %sbigbluebutton WHERE course_id IN %s';
	$rows_meetings = queryDB($sql, array(TABLE_PREFIX, $enrolled_courses));
	
	if(count($rows_meetings) > 0){
		foreach($rows_meetings as $row){
			$news[] = array('time'=>htmlentities_utf8($row['course_timing']), 
							'object'=>$row, 
							'alt'=>_AT('bigbluebutton'),
							'course'=>$system_courses[$row['course_id']]['title'],
							'thumb'=>'mods/bigbluebutton/bigbluebutton_sm.png',
							'link'=>htmlentities_utf8($row['message']));
		}
	}
	return $news;
}

?>
