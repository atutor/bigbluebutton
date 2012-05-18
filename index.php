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
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

$_custom_css = $_base_path . 'mods/bigbluebutton/module.css'; // use a custom stylesheet
require (AT_INCLUDE_PATH.'header.inc.php');
require_once( "config.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();
require("bbb_atutor.lib.php");


$_courseId = $_SESSION['course_id'];
$sql = "SELECT * from ".TABLE_PREFIX."bigbluebutton WHERE course_id = '$_courseId'";
$result = mysql_query($sql, $db);

if(mysql_num_rows($result) != 0 && !isset($_GET['edit'])){
	$savant->assign('bbb', $bbb);
	$savant->assign('result', $result);
	$savant->assign('response', $response);
	$savant->assign('bbb_joinURL', $bbb_joinURL);
	$savant->assign('bbb_recordURL', $bbb_recordURL);
	$savant->display('templates/index.tmpl.php');

} else{

	$msg->printFeedbacks('NO_MEETINGS');
}

 require (AT_INCLUDE_PATH.'footer.inc.php'); ?>