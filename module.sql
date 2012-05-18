# sql file for bigbluebutton module v0.4

CREATE TABLE `bigbluebutton` (
  `meeting_id` tinyint(8) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` tinyint(8) unsigned NOT NULL,
  `course_name` varchar(155) NOT NULL,
  `course_timing` varchar(15) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`meeting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `language_text` VALUES ('en', '_module','bigbluebutton','BigBlueButton',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bigbluebutton_text','A sample BBB text for detailed homepage.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_config_text','Enter the URL to the BigBlueButton base location, and the SALT security token. The SALT token is a 32 character string found in the bigbluebutton.properties file of your BBB installation. Enter the maximum number of recordings allowed per course to limit the amount of space occupied by BBB meeting recordings. Set maximum recordings to 0 to disable recordings. ',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_config','BigBlueButton Configuration',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_url','BigBlueButton URL',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_salt ','BigBlueButton SALT Security Token',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_admin_setup','BigBlueButton Configuration',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_open_new_win','Open BigBlueButton in a New Window',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_meeting_time','Meeting Time',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_message','Meeting Description',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_welcome','You have joined the meeting.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_edit_meeting','Edit Meeting',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_create_meeting_text','Create Meetings',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_create_meeting','Create Meeting',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_join_conference','Join Conference',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_join','Join',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_SETTINGS_CHANGED','BigBlueButton configuration options have been updated.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_BBB_SETTINGS_FAILED','BigBlueButton configuration settings failed to save. Try adjusting the URL and double checking the Salt token.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_UNABLE_TO_CONNECT_TO_BBB','Unable to join the meeting. Please check the url of the bigbluebutton server AND check to see if the bigbluebutton server is running.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_CHECKSUM_ERROR_BBB','A checksum error occured. Make sure you entered the correct salt.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_BBB_ACTION_FAILED','Failed to save meeting details.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_TIME_REQUIRED_BBB','All fields are required.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_CONFIRM_BBB_DELETE_CONFIRM','Are you sure you want to delete this meeting?',NOW(),'');

# New in V.04
INSERT INTO `language_text` VALUES ('en', '_module','bbb_meeting_name','Meeting Title',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_create_edit_meeting','Create/Edit Meeting',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_record_conference','Record Conference',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_recordings','Recordings',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_no_recording','No Recordings',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_meeting_ended','Ended',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_view_recording','View Recording',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_delete_recording','Delete Recording',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_join_meeting_moderate','Join as Moderator',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_join_meeting','Join as Meeting',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_view_meeting','View Meeting',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_yes_join','Yes, Join',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_no_cancel','No, Cancel',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_meeting_status','Status',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_course_name','Course Title',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_meeting_pending','Pending',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_meeting_running','Running',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_meeting_over','Ended',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_continue_yes','Do you wish to continue:',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_continue_text','You are about to leave ATutor and access the BigBlueButton video conferencing system, a Flash-based application. If you find you are unable to access the video conferencing system with your assistive technology, use your browser back button, to back out of the system. Contact your instructor for details on how to access recordings of video conference meetings.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_course_title','Course Title',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_current_meetings','Manage BigBlueButton Meetings',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_no_meeting','This meeting has not started yet. It will become available when the moderator logs in. Check back here at the scheduled meeting time. <a href="mods/bigbluebutton/index.php">Return Meetings List</a>',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_max_recording','Maximum Recordings per Course',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_MEETING_ENDED','Meeting has been ended. To re-enable choose the radio button for the meeting and press edit, then in the Status menu choose Pending. ',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_MEETING_ENDED_OTHER','You have successfully exited the meeting, though it continues to run. The meeting will be ended when the moderator logs out.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_RECORDING_IN_PROGRESS', 'Recordings may take a while to process before becoming available, if enabled for this meeting.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_NO_MEETINGS','No meetings are yet available.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_CONFIRM_DELETE_RECORDING','Are you sure you want to delete this recording?',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_SELECT_MEETING','No meeting was selected.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_INFOS_MAX_REACHED','This meeting will not be recorded because the maximum number of recordings has been reached for this course. To record this meeting, delete an existing recording, and start again.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_INFOS_MEETING_ENDED_MOD','The meeting you are trying to access has ended, or is otherwise unavailable. You can make the meeting available again by selecting it from the list below, choosing edit, then resetting it\'s status to "pending"',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_INFOS_MEETING_ENDED','The meeting you are trying to access has ended, or is otherwise unavailable.',NOW(),'');



