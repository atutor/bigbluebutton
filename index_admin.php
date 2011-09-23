<?php
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_BIGBLUEBUTTON);

require (AT_INCLUDE_PATH.'header.inc.php');
global $_base_href;


?>
<h2><?php echo _AT('bbb_admin_setup');  ?> </h2>
<fieldset><legend><?php echo _AT('bbb_config'); ?></legend>
<div class="input-form">
<p><?php echo _AT('bbb_config_text'); ?></p>

<form name="form" action="<?php echo $_base_href; ?>mods/bigbluebutton/change_admin.php" method="post">
<label for="url"><?php echo _AT('bbb_url'); ?></label><br />
<input type="text" name="bbb_url" id="url" class="input" maxlength="60" size="40" value="<?php echo $_config['bbb_url']  ?>" /><br />
<label for="url"><?php echo _AT('bbb_salt'); ?></label><br />
<input type="text" name="bbb_salt" id="salt" maxlength="60" size="40"  value="<?php echo $_config['bbb_salt']  ?>" /><br />
<input type="submit" name="submit" value="<?php echo _AT('save'); ?>">
</form>

</div>
</fieldset>
<iframe src="<?php echo $_config['bbb_url']; ?>" width="100%" height="300">
  <p>Your browser does not support iframes.</p>
</iframe>
<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>