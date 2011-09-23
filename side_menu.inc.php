<?php 
/* start output buffering: */
ob_start(); 
if (authenticate(AT_PRIV_BIGBLUEBUTTON, AT_PRIV_RETURN))
{
	echo "Go to <a href='/atutor/docs/mods/hello_world/index_instructor.php'>page</a>";
}
else 
{
	echo "Go to <a href='/atutor/docs/mods/hello_world/index.php'>page</a>";
	
}?>



<?php
$savant->assign('dropdown_contents', ob_get_contents());
ob_end_clean();
$savant->assign('title', _AT('BigBlueButton')); // the box title
$savant->display('include/box.tmpl.php');
?>