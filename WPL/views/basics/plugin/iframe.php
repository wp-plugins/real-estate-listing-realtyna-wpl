<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
<head>
	<?php wp_head(); ?>
</head>
<body>
	<?php echo $this->$function(); ?>
	<?php wp_footer(); ?>
</body>
</html>