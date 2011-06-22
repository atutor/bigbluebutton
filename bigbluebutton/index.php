<?php
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
$_custom_css = $_base_path . 'mods/bigbluebutton/module.css'; // use a custom stylesheet
require (AT_INCLUDE_PATH.'header.inc.php');
require "bbb_api_conf.php";
require "bbb_api.php";
?>


<?php
 $bbb_joinURL;
 $moderatorPassword = "mp";
 $attendeePassword  = "ap";   
 $logoutUrl         = "http://bigbluebutton.org";
 $username          = get_login(intval($_SESSION["member_id"]));
 $meetingID         = $_SESSION['course_id'];
 $courseId          = $_SESSION['course_id'];
 $response          = BigBlueButton::createMeetingArray($username,
                                                        $meetingID,
                                                        "Welcome to the Classroom",
                                                        $moderatorPassword,
                                                        $attendeePassword,
                                                        $salt,
                                                        $url,
                                                        $logoutUrl);

//Analyzes the bigbluebutton server's response
if (!$response){//If the server is unreachable
    $msg = 'Unable to join the meeting. Please check the url of the bigbluebutton server AND check to see if the bigbluebutton server is running.';
}
else if ( $response['returncode'] == 'FAILED' ) { //The meeting was not created
    if ($response['messageKey'] == 'checksumError'){
	    $msg = 'A checksum error occured. Make sure you entered the correct salt.';
	}
	else {
	    $msg = $response['message'];
	}
}
else {//"The meeting was created, and the user will now be joined "
    $bbb_joinURL = BigBlueButton::joinURL($meetingID,
                                          $username,
                                          $attendeePassword,
                                          $salt,
                                          $url);
		
}
	
/////////////////////////////////////////////////////////////////////////////        
  
 
   $sql    = "SELECT * FROM ".TABLE_PREFIX."bigbluebutton WHERE `course_id`='$courseId'" ;
   $result = mysql_query($sql, $db);
   $row    = mysql_fetch_array($result);
 
   
   echo "
          <img src='/atutor/docs/mods/bigbluebutton/bigbluebutton.png'>
          <div id='bigbluebutton'>
              <table border='2'>
         	     <tr>
                     <td width='200'>Course timing</td><td>$row[1]</td>
                 </tr>
                 <tr>
                     <td>Message</td><td>$row[2]</td>
                 </tr>     
              </table>
              </br>
              </br>
          ";

   echo"<a href='$bbb_joinURL' target='_blank'><b>Click here</b></a> to go to BigBlueButtton classroom. </div>";	
?>

<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>