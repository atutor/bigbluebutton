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

// THIS FILE IS DEPRACATED
exit;
// 1. define relative path to `include` directory:
define('AT_INCLUDE_PATH', '../../include/');

// 2. require the `vitals` file before any others:
require (AT_INCLUDE_PATH . 'vitals.inc.php');


require_once("config.php");
require_once("bbb-api.php");
$bbb = new BigBlueButton();
require_once("bbb_atutor.lib.php");

$meeting_id = intval($_GET['view_meeting']);
$bbb_recordURL = bbb_get_recordings($meeting_id); 

require (AT_INCLUDE_PATH.'header.inc.php'); 
?>
<script type="text/javascript">

</script>
<a tabindex="0" onkeypress="javascript:window.open('<?php echo $bbb_recordURL; ?>', 'BBBWindow', 'width=800,height=800')" onclick="javascript:window.open('<?php echo $bbb_recordURL; ?>', 'BBBWindow', 'width=800,height=800')">View Meeting in New Window</a>
<iframe src="<?php echo $bbb_recordURL; ?>" height="800px" width="100%" frameborder="0" scrolling="no" style="border:1px solid #cccccc; padding:1em;border-radius:.3em;">

</iframe>

<?php  require (AT_INCLUDE_PATH.'footer.inc.php'); ?>