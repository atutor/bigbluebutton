<?php
/*******
 * doesn't allow this file to be loaded with a browser.
 */
if (!defined('AT_INCLUDE_PATH')) { exit; }

/******
 * this file must only be included within a Module obj
 */
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

/*******
 * assign the instructor and admin privileges to the constants.
 */
define('AT_PRIV_BIGBLUEBUTTON',       $this->getPrivilege());
define('AT_ADMIN_PRIV_BIGBLUEBUTTON', $this->getAdminPrivilege());

/*******
 * create a side menu box/stack.
 */
$this->_stacks['bigbluebutton'] = array('title_var'=>'bigbluebutton', 'file'=>AT_INCLUDE_PATH.'../mods/bigbluebutton/side_menu.inc.php');

/*******
 * if this module is to be made available to students on the Home or Main Navigation.
 */
//$_group_tool = $_student_tool = 'mods/bigbluebutton/index.php';

$_student_tool = 'mods/bigbluebutton/index.php';

$this->_list['bigbluebutton'] = array('title_var'=>'bigbluebutton','file'=>'mods/bigbluebutton/sublinks.php');

// Uncomment for tiny list bullet icon for module sublinks "icon view" on course home page
$this->_pages['mods/bigbluebutton/index.php']['icon']      = 'mods/bigbluebutton/bigbluebutton_sm.png';

// Uncomment for big icon for module sublinks "detail view" on course home page
$this->_pages['mods/bigbluebutton/index.php']['img']      = 'mods/bigbluebutton/bigbluebutton.png';

/*******
 * add the admin pages when needed.
 */
if (admin_authenticate(AT_ADMIN_PRIV_BIGBLUEBUTTON, TRUE) || admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)) {
	$this->_pages[AT_NAV_ADMIN] = array('mods/bigbluebutton/index_admin.php');
	$this->_pages['mods/bigbluebutton/index_admin.php']['title_var'] = 'bigbluebutton';
	$this->_pages['mods/bigbluebutton/index_admin.php']['parent']    = AT_NAV_ADMIN;
}

/*******
 * instructor Manage section:
 */
$this->_pages['mods/bigbluebutton/index_instructor.php']['title_var'] = 'bigbluebutton';
$this->_pages['mods/bigbluebutton/index_instructor.php']['parent']   = 'tools/index.php';

/*******
 * student page.
 */
$this->_pages['mods/bigbluebutton/index.php']['title_var'] = 'bigbluebutton';
$this->_pages['mods/bigbluebutton/index.php']['img']       = 'mods/bigbluebutton/bigbluebutton.png';


//function bigbluebutton_get_group_url($group_id) {
//	return 'mods/bigbluebutton/index_group.php';
//}

?>