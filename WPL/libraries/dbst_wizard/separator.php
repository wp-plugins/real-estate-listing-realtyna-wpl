<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'separator' and !$done_this)
{
?>
<div class="seperator-wp" id="wpl_listing_separator<?php echo $field->id; ?>"></div>
<?php
	$done_this = true;
}