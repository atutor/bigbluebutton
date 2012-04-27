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
//authenticate(AT_PRIV_BIGBLUEBUTTON);
require_once( "bbb_api_conf.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();
$meetingid = intval($_GET['view_meeting']);
$recordingsParams = array(
	'meetingId' => $meetingid 		// OPTIONAL - comma separate if multiples

);

try {$result = $bbb->getRecordingsWithXmlResponseArray($recordingsParams);}
	catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
		$itsAllGood = false;
	}

	if ($itsAllGood == true) {
		// If it's all good, then we've interfaced with our BBB php api OK:
		if ($result == null) {
			// If we get a null response, then we're not getting any XML back from BBB.
			echo "Failed to get any response. Maybe we can't contact the BBB server.";
		}	
		else { 
		// We got an XML response, so let's see what it says:
		//debug($result);
			if ($result['returncode'] == 'SUCCESS') {
				// Then do stuff ...
				//echo "<p>Meeting info was found on the server.</p>";
			}
			else {
				//echo "<p>Failed to get meeting info.</p>";
			}
		}
	}	
$bbb_recordURL = $result['0']['playbackFormatUrl'];
require (AT_INCLUDE_PATH.'header.inc.php'); 
?>

<iframe src="<?php echo $bbb_recordURL; ?>" height="800px" width="100%" frameborder="0" scrolling="no">

</iframe>

<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>