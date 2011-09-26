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
// This is the security salt that must match the value set in the BigBlueButton server
$salt = $_config['bbb_salt']; 

// This is the URL for the BigBlueButton server 
//Make sure the url ends with /bigbluebutton/
$url = $_config['bbb_url']."/bigbluebutton/";
?>
