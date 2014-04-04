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

//authenticate(AT_PRIV_BIGBLUEBUTTON);
require_once("config.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();
require_once("bbb_atutor.lib.php");

if($_GET['publish'] == "yes"){

$meeting_id = intval($_GET['meetingid']);
$meeting_recording = bbb_get_recordings($meeting_id);
$recordId = split( "=",$meeting_recording);

$meeting_info = bbb_get_meeting_info($meeting_id);
$published =  bbb_publish_meeting($recordId['1']);


//bbb_publish_meeting($recordId)
}

$meeting_id = intval($_GET['pub_meeting']);
$bbb_recordURL = bbb_get_recordings($meeting_id); 

require (AT_INCLUDE_PATH.'header.inc.php'); 
?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?meetingid=<?php echo $meeting_id; ?>&publish=yes"><?php echo _AT('bbb_publish'); ?> </a>

<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>