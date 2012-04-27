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

if(!$_SESSION['is_admin']){
	header('Location: '.AT_BASE_HREF.'mods/bigbluebutton/index.php');
	exit;
}


require (AT_INCLUDE_PATH.'header.inc.php'); 
require_once( "bbb_api_conf.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();

$bbb_joinURL;


$_courseId=$_SESSION['course_id'];
$_courseTiming=$_POST['course_timing'];
$_courseMessage=$_POST['course_message'];
$_attendeePassword="ap";   
$_logoutUrl= $_base_href.'mods/bigbluebutton/index.php';
$username=get_login(intval($_SESSION['member_id']));
$meetingID=$_GET['meetingId'];
$bbb_welcome = _AT('bbb_welcome');
$salt = $_config['bbb_salt'];
$url = $_config['bbb_url']."/bigbluebutton/";

	$joinParams = array(
		'meetingId' => $meetingID, 				// REQUIRED - We have to know which meeting to join.
		'username' => $username,		// REQUIRED - The user display name that will show in the BBB meeting.
		'password' => $_attendeePassword,	// REQUIRED - Must match either attendee or moderator pass for meeting.
		'createTime' => '',					// OPTIONAL - string
		'userId' => $_SESSION['member_id'],	// OPTIONAL - string
		'webVoiceConf' => ''				// OPTIONAL - string
	);
	
	$itsAllGood = true;
	try {$bbb_joinURL = $bbb->getJoinMeetingURL($joinParams);}
		catch (Exception $e) {
			echo 'Caught exception2: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}

?>
<p><?php echo _AT('bbb_continue_text'); ?></p>

<p> <?php echo _AT('bbb_continue_yes'); ?>  <a href="<?php echo $bbb_joinURL;?>"><?php echo _AT('bbb_yes_join');  ?></a> <?php echo _AT('or'); ?> <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><?php echo _AT('bbb_no_cancel');  ?></a>

<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>