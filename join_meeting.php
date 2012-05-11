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


require (AT_INCLUDE_PATH.'header.inc.php'); 
require_once( "config.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();
require("bbb_atutor.lib.php");

$_attendeePassword = "ap";   
$_logoutUrl = $_base_href.'mods/bigbluebutton/index.php';
$username = $_SESSION['login'];
//$username = get_login(intval($_SESSION['member_id']));
$meetingID = intval($_GET['meetingId']);
$response = bbb_get_meeting_info($meetingID);
$bbb_joinURL = bbb_join_meeting($meetingID, $username, $_attendeePassword);

if($response['returncode'] == "SUCCESS"){
?>
<p style="border:1px solid #cccccc; padding:1em;border-radius:.3em;background-color:#eeeeee;"><?php echo _AT('bbb_continue_text'); ?></p>

<p> <?php echo _AT('bbb_continue_yes'); ?>  <a href="<?php echo $bbb_joinURL;?>"><?php echo _AT('bbb_yes_join');  ?></a> <?php echo _AT('or'); ?> <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><?php echo _AT('bbb_no_cancel');  ?></a>


<?php
} else{
	echo '<p style="border:1px solid #cccccc; padding:1em;border-radius:.3em;background-color:#eeeeee;">'._AT('bbb_no_meeting').'</p>';
}

?>
<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>