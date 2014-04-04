<?php
/****************************************************************/
/* BigBlueButton module for ATutor                              */
/*                                                              */
/*                                                              */
/* Author: Greg Gay										        */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/

function bbb_end_meeting($meeting_id, $modpwd){
	global $bbb;
	$endParams = array(
		'meetingId' => $_SERVER['HTTP_HOST'].'-'.$meeting_id, //REQUIRED - We have to know which meeting to end.
		'password' => $modpwd	//REQUIRED - Must match moderator pass for meeting.
	);

	$itsAllGood = true;
	try {$response = $bbb->endMeetingWithXmlResponseArray($endParams);}
		catch (Exception $e) {
			echo 'Caught exception -c: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}

	if ($itsAllGood == true) {
		// If it's all good, then we've interfaced with our BBB php api OK:
		if ($response == null) {
			// If we get a null response, then we're not getting any XML back from BBB.
			echo "Failed to get any response. Maybe we can't contact the BBB server.";
		}
	}	
	return $response;
}

function bbb_delete_meeting($delete_id){
	global $bbb;
	$recordingsParams = array(
		'meetingId' => $_SERVER['HTTP_HOST'].'-'.$delete_id 	// OPTIONAL - comma separate if multiples
	);

	try {$response = $bbb->getRecordingsWithXmlResponseArray($recordingsParams);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	$bbb_deleteURL = $response['0']['recordId'];

	$recordingParams = array(
		'recordId' => $bbb_deleteURL
	);
	$itsAllGood = true;
	try {$response = $bbb->deleteRecordingsWithXmlResponseArray($recordingParams);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	return $response;
}


function bbb_get_recordings($meeting_id){
	global $bbb;
	$recordingsParams = array('meetingId' => $_SERVER['HTTP_HOST'].'-'.$meeting_id); // OPTIONAL - comma separate if multiples
	try {$recording = $bbb->getRecordingsWithXmlResponseArray($recordingsParams);}
		catch (Exception $e) {
			echo 'Caught exception -f: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}

	if ($itsAllGood == true) {
		// If it's all good, then we've interfaced with our BBB php api OK:
		if ($recording == null) {
			// If we get a null response, then we're not getting any XML back from BBB.
			echo "Failed to get any response. Maybe we can't contact the BBB server.";
		}	
	}	

	if($recording['0']['playbackFormatUrl'] != ''){
		 $bbb_recordURL = $recording['0']['playbackFormatUrl'] ;
	}
	 return $bbb_recordURL;
}

function bbb_get_meeting_info($meeting_id){
	global $bbb;
	$infoParams = array(
		'meetingId' => $_SERVER['HTTP_HOST'].'-'.$meeting_id, 		// REQUIRED - We have to know which meeting.
		'password' => 'mp',			// REQUIRED - Must match attendee pass for meeting.
	
	);
	$itsAllGood = true;
	try {$response = $bbb->getMeetingInfoWithXmlResponseArray($infoParams);}
		catch (Exception $e) {
			echo 'Caught exception -g: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	
		if ($itsAllGood == true) {
			// If it's all good, then we've interfaced with our BBB php api OK:
			if ($response == null) {
				// If we get a null response, then we're not getting any XML back from BBB.
				echo "Failed to get any response. Maybe we can't contact the BBB server.";
			}	
		}
	return $response;
}

function bbb_create_meeting($_attendeePassword,$_moderatorPassword,$_logoutUrl,$meetingID, $record, $username){
	global $bbb;
	// If recording
	$creationParams = array(
		'meetingId' => $_SERVER['HTTP_HOST'].'-'.$meetingID, 					// REQUIRED
		'meetingName' => 'Course Meeting', 			// REQUIRED
		'attendeePw' => $_attendeePassword, 		// Match this value in getJoinMeetingURL() to join as attendee.
		'moderatorPw' => $_moderatorPassword, 		// Match this value in getJoinMeetingURL() to join as moderator.
		'welcomeMsg' => '', 						// ''= use default. Change to customize.
		'dialNumber' => '', 						// The main number to call into. Optional.
		'voiceBridge' => '', 						// PIN to join voice. Optional.
		'webVoice' => '', 							// Alphanumeric to join voice. Optional.
		'logoutUrl' => $_logoutUrl."?meeting_id=".$meetingID.SEP.'mod='.$username, // Default in bigbluebutton.properties. Optional.
		'maxParticipants' => '-1', 				// Optional. -1 = unlimitted. Not supported in BBB. [number]
		'record' => $record, 					// New. 'true' will tell BBB to record the meeting.
		'duration' => '0', 						// Default = 0 which means no set duration in minutes. [number]
		//'meta_category' => '', 				// Use to pass additional info to BBB server. See API docs.
	);

	$itsAllGood = true;
	try {$response = $bbb->createMeetingWithXmlResponseArray($creationParams);}
		catch (Exception $e) {
			echo 'Caught exception1: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	
	if ($itsAllGood == true) {
		// If it's all good, then we've interfaced with our BBB php api OK:
		if ($response == null) {
			// If we get a null response, then we're not getting any XML back from BBB.
			echo "Failed to get any response. Maybe we can't contact the BBB server.";
		} 
	}
}

function bbb_join_meeting_moderate($_attendeePassword,$_moderatorPassword,$_logoutUrl,$meetingID, $record, $username){
	global $bbb;

	bbb_create_meeting($_attendeePassword,$_moderatorPassword,$_logoutUrl,$meetingID, $record, $username);
	$joinParams = array(
		'meetingId' => $_SERVER['HTTP_HOST'].'-'.$meetingID, 				// REQUIRED - We have to know which meeting to join.
		'username' => $username,		// REQUIRED - The user display name that will show in the BBB meeting.
		'password' => $_moderatorPassword,	// REQUIRED - Must match either attendee or moderator pass for meeting.
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
	return $bbb_joinURL;
}


function bbb_join_meeting($meetingID,$username,$_attendeePassword){
	global $bbb;
	//bbb_create_meeting($_attendeePassword,$_moderatorPassword,$_logoutUrl,$meetingID, $record, $username);
	
	$joinParams = array(
		'meetingId' => $_SERVER['HTTP_HOST'].'-'.$meetingID, 				// REQUIRED - We have to know which meeting to join.
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
	return $bbb_joinURL;

}

function bbb_publish_meeting($recordId){
	global $bbb;
	$recordingParams = array(
		/* 
		* NOTE: Set the recordId below to a valid id after you have created a recorded meeting, 
		* and received back a real recordID back from your BBB server using the 
		* getRecordingsWithXmlResponseArray method.
		*/
		
		// REQUIRED - We have to know which recording:
		'recordId' => $recordId, 			
		'publish' => 'true',		// REQUIRED - To publish or not to publish.
	
	);
	
	// Now do it:
	$itsAllGood = true;
	try {$result = $bbb->publishRecordingsWithXmlResponseArray($recordingParams);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	
	if ($itsAllGood == true) {
		//Output results to see what we're getting:
		print_r($result);
	}
}
function bbb_is_meeting_running($meetingId){
	global $bbb;
	$itsAllGood = true;
	$meetingId = $_SERVER['HTTP_HOST'].'-'.$meetingId;
	try {$response = $bbb->isMeetingRunningWithXmlResponseArray($meetingId);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	return $response;
}

?>