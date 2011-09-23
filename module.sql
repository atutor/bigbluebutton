# sql file for bigbluebutton module

CREATE TABLE `bigbluebutton` (
   `course_id` VARCHAR( 15 ) NOT NULL,
   `course_timing` VARCHAR( 15 ) NOT NULL,
   `message` VARCHAR( 15 ) NOT NULL,
   PRIMARY KEY ( `course_id` )
);

INSERT INTO `language_text` VALUES ('en', '_module','bigbluebutton','BigBlueButton',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bigbluebutton_text','A sample BBB text for detailed homepage.',NOW(),'');

INSERT INTO `language_text` VALUES ('en', '_module','bbb_config_text','Enter the URL to the BigBlueButton Welcome screen. and the SALT security token. The SALT token is a 32 character string found in the bigbluebutton.properties file of your BBB installation.  ',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_config','BigBlueButton Configuration',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_url','BigBlueButton URL',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bbb_salt ','BigBlueButton SALT Security Token',NOW(),'');


INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_SETTINGS_CHANGED','BigBlueButton configuration options have been updated.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_BBB_SETTINGS_FAILED','BigBlueButton configuration settings failed to save. Try adjusting the URL and double checking the Salt token.',NOW(),'');


