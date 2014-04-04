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
	$bbb_max_recordings = intval($_POST['bbb_max_recordings']);

if(isset($_POST['submit'])) {
		global $db;
		//Update URL 
		$sql1 = "REPLACE INTO %sconfig  (name, value) VALUES ('bbb_url', '%s') ";
        $result = queryDB($sql1, array(TABLE_PREFIX, $bbb_url));

		if($result > 0){	
			$msg->addFeedback('SETTINGS_CHANGED');
			//Redirect back to the form
		} else{
		
			$msg->addError('BBB_SETTINGS_FAILED');
		}
		$sql2 = "REPLACE INTO %sconfig  (name, value) VALUES ('bbb_salt', '%s') ";
	    $result2 = queryDB($sql2, array(TABLE_PREFIX, $bbb_salt));
	    
		if($result > 0){
			$msg->addFeedback('SETTINGS_CHANGED');
			//Redirect back to the form

		} else{
		
			$msg->addError('BBB_SETTINGS_FAILED');
		}
		
		$sql3 = "REPLACE INTO %sconfig  (name, value) VALUES ('bbb_max_recordings', '%s') ";
	    $result3 = queryDB($sql3, array(TABLE_PREFIX, $bbb_max_recordings));
	    
		if($result3 > 0){
			$msg->addFeedback('SETTINGS_CHANGED');
			//Redirect back to the form

		} else{
		
			$msg->addError('BBB_SETTINGS_FAILED');
		}
	header('Location: index_admin.php');
}

?>