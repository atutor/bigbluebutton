<?php
/****************************************************************/
/* BigBlueButton module for ATutor                              */
/* https://github.com/nishant1000/BigBlueButton-module-for-ATutor*/
/*                                                              */
/* This module allows to search OpenLearn for educational       */
/* content.														*/
/* Author:Greg Gay										*/
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
/*
if(!$_SESSION['is_admin']){
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index.php');
	exit;
}
*/
//authenticate(AT_PRIV_BIGBLUEBUTTON);
require_once("config.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();
require_once("bbb_atutor.lib.php");

if($_GET['publish'] == "yes"){

$meeting_id = intval($_GET['meetingid']);
$meeting_recording = bbb_get_recordings($meeting_id);
$recordId = split( "=",$meeting_recording);
debug($recordId['1']);
$meeting_info = bbb_get_meeting_info($meeting_id);
$published =  bbb_publish_meeting($recordId['1']);
debug($published);

//bbb_publish_meeting($recordId)
}

$meeting_id = intval($_GET['pub_meeting']);
$bbb_recordURL = bbb_get_recordings($meeting_id); 

require (AT_INCLUDE_PATH.'header.inc.php'); 
?>
Yes <a href="<?php echo $_SERVER['PHP_SELF']; ?>?meetingid=<?php echo $meeting_id; ?>&publish=yes"><?php echo _AT('bbb_publish'); ?> </a>

<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>