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
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_BIGBLUEBUTTON);

require (AT_INCLUDE_PATH.'header.inc.php');
global $_base_href;


?>
<h3><?php echo _AT('bbb_admin_setup');  ?> </h3><br />

<div class="input-form" style="padding:.5em;">
<p><?php echo _AT('bbb_config_text'); ?></p>

<form name="form" action="<?php echo $_base_href; ?>mods/bigbluebutton/change_admin.php" method="post">
<label for="url"><?php echo _AT('bbb_url'); ?></label><br />
<input type="text" name="bbb_url" id="url" class="input" maxlength="60" size="40" value="<?php echo $_config['bbb_url']  ?>" /><br />
<label for="url"><?php echo _AT('bbb_salt'); ?></label><br />
<input type="text" name="bbb_salt" id="salt" maxlength="60" size="40"  value="<?php echo $_config['bbb_salt']  ?>" /><br />
<input type="submit" name="submit" value="<?php echo _AT('save'); ?>">
</form>

</div>
<a href="<?php echo $_config['bbb_url']; ?>" target="bbb">(<?php echo _AT('bbb_open_new_win');  ?>)</a><br />               
<iframe src="<?php echo $_config['bbb_url']; ?>" width="100%" height="600">
  <p>Your browser does not support iframes.</p>
</iframe>
<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>