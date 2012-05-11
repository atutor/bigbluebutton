<?php
/****************************************************************/
/* BigBlueButton module for ATutor                              */
/* https://github.com/nishant1000/BigBlueButton-module-for-ATutor*/
/*                                                              */
/* Author: Nishant Kumar										*/
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_BIGBLUEBUTTON);
require(AT_INCLUDE_PATH.'header.inc.php');

?>
<div class="input-form" style="padding:1em;">
<p><?php echo _AT('bbb_config_text'); ?></p>
<form name="form" action="<?php echo $_base_href; ?>mods/bigbluebutton/change_admin.php" method="post">
<label for="url"><?php echo _AT('bbb_url'); ?></label><br />
<input type="text" name="bbb_url" id="url" class="input" maxlength="60" size="40" value="<?php echo $_config['bbb_url']  ?>" /><br />
<label for="salt"><?php echo _AT('bbb_salt'); ?></label><br />
<input type="text" name="bbb_salt" id="salt" maxlength="60" size="40"  value="<?php echo $_config['bbb_salt'];  ?>" /><br />
<label for="bbb_max_recordings"><?php echo _AT('bbb_max_recording'); ?></label><br />
<input type="text" name="bbb_max_recordings" id="bbb_max_recordings"  size="3"  value="<?php echo $_config['bbb_max_recordings'];  ?>" /><br />
<input type="submit" name="submit" value="<?php echo _AT('save'); ?>">
</form>
</div>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>