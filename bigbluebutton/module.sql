# sql file for bigbluebutton module

CREATE TABLE `bigbluebutton` (
   `course_id` VARCHAR( 15 ) NOT NULL,
   `course_timing` VARCHAR( 15 ) NOT NULL,
   `message` VARCHAR( 15 ) NOT NULL,
   PRIMARY KEY ( `course_id` )
);

INSERT INTO `language_text` VALUES ('en', '_module','bigbluebutton','BigBlueButton',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bigbluebutton_text','A sample BBB text for detailed homepage.',NOW(),'');