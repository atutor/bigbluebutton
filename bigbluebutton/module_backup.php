<?php
/* each table to be backed up. includes the sql entry and fields */

$dirs = array();
$dirs['bigbluebutton/'] = AT_CONTENT_DIR . 'bigbluebutton' . DIRECTORY_SEPARATOR;

$sql = array();
$sql['bigbluebutton']  = 'SELECT * FROM '.TABLE_PREFIX.'bbb WHERE course_id=?';

function bigbluebutton_convert($row, $course_id, $table_id_map, $version) {
	$new_row = array();
	$new_row[0]  = $course_id;
	$new_row[1]  = $row[0];

	return $new_row;
}

?>