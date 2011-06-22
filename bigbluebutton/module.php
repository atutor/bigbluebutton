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
$this->_stacks['bigbluebutton'] = array('title_var'=>'bigbluebutton', 'file'=>'mods/bigbluebutton/side_menu.inc.php');

/*******
 * instructor Manage section:
 */
$this->_pages['mods/bigbluebutton/index_instructor.php']['title_var'] = 'bigbluebutton';
$this->_pages['mods/bigbluebutton/index_instructor.php']['parent']   = 'tools/index.php';
// ** possible alternative: **
// $this->pages['./index_instructor.php']['title_var'] = 'hello_world';
// $this->pages['./index_instructor.php']['parent']    = 'tools/index.php';

/*******
 * student page.
 */
$this->_pages['mods/bigbluebutton/index.php']['title_var'] = 'bigbluebutton';
$this->_pages['mods/bigbluebutton/index.php']['img']       = 'mods/bigbluebutton/bigbluebutton.jpg';

/*******
 * Use the following array to define a tool to be added to the Content Editor's icon toolbar. 
 * id = a unique identifier to be referenced by javascript or css, prefix with the module name
 * class = reference to a css class in the module.css or the primary theme styles.css to style the tool icon etc
 * src = the src attribute for an HTML img element, referring to the icon to be embedded in the Content Editor toolbar
 * title = reference to a language token rendered as an HTML img title attribute
 * alt = reference to a language token rendered as an HTML img alt attribute
 * text = reference to a language token rendered as the text of a link that appears below the tool icon
 * js = reference to the script that provides the tool's functionality
 */

$this->_content_tools[] = array("id"=>"bigbluebutton_tool", 
                                "class"=>"fl-col clickable", 
                                "src"=>AT_BASE_HREF."mods/bigbluebutton/bigbluebutton.png",
                                "title"=>_AT('bigbluebutton_tool'),
                                "alt"=>_AT('bigbluebutton_tool'),
                                "text"=>_AT('bigbluebutton'), 
                                "js"=>AT_BASE_HREF."mods/bigbluebutton/content_tool_action.js");

/*******
 * Register the entry of the callback class. Make sure the class name is properly namespaced, 
 * for instance, prefixed with the module name, to enforce its uniqueness.
 * This class must be defined in "ModuleCallbacks.class.php".
 * This class is an API that contains the static methods to act on core functions.
 */
//$this->_callbacks['hello_world'] = 'HelloWorldCallbacks';

function bigbluebtton_get_group_url($group_id) {
	return 'mods/bigbluebutton/index.php';
}
?>