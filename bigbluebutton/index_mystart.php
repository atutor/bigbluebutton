<?php
$_user_location	= 'users';
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
$_custom_css = $_base_path . 'mods/bigbluebutton/module.css'; // use a custom stylesheet
require (AT_INCLUDE_PATH.'header.inc.php');
?>

<div id="bigbluebutton">
	This is a page of the BigBlueButton module that requires that enables video conferencing and much more.
</div>

<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>