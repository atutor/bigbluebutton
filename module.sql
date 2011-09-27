# sql file for bigbluebutton module

CREATE TABLE `bigbluebutton` (
   `course_id` VARCHAR( 15 ) NOT NULL,
   `course_timing` VARCHAR( 15 ) NOT NULL,
   `message` TEXT NOT NULL,
   PRIMARY KEY ( `course_id` )
);

INSERT INTO `language_text` VALUES ('en', '_module','bigbluebutton','BigBlueButton',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bigbluebutton_text','A sample BBB text for detailed homepage.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_config_text','Enter the URL to the BigBlueButton Welcome screen, and the SALT security token. The SALT token is a 32 character string found in the bigbluebutton.properties file of your BBB installation.  ',NOW(),'');
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
