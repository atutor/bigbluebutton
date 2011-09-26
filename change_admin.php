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
	require (AT_INCLUDE_PATH . 'vitals.inc.php');
	admin_authenticate(AT_ADMIN_PRIV_BIGBLUEBUTTON);

	//Include message class for providing feedback to the admin.
	require_once(AT_INCLUDE_PATH . '/classes/Message/Message.class.php');
	global $savant;
	$msg = new Message($savant);
	
	$bbb_url= $addslashes($_POST['bbb_url']);
	$bbb_salt= $addslashes($_POST['bbb_salt']);

if(isset($_POST['submit'])) {
		global $db;
		//Updtae URL 
		$sql1 = "REPLACE INTO " . TABLE_PREFIX . "config  (name, value) VALUES ('bbb_url', '$bbb_url') ";

		if(mysql_query($sql1, $db)){	
			$msg->addFeedback('SETTINGS_CHANGED');
			//Redirect back to the form
		} else{
		
			$msg->addError('BBB_SETTINGS_FAILED');
		}
		$sql2 = "REPLACE INTO " . TABLE_PREFIX . "config  (name, value) VALUES ('bbb_salt', '$bbb_salt') ";
	
		if(mysql_query($sql2, $db)){	
			$msg->addFeedback('SETTINGS_CHANGED');
			//Redirect back to the form

		} else{
		
			$msg->addError('BBB_SETTINGS_FAILED');
		}
		
	header('Location: index_admin.php');
}

?>