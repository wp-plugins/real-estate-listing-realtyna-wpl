<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'separator' and !$done_this)
{
?>
<div class="fanc-body">
	<div class="fanc-row fanc-button-row-2">
		<input class="wpl-button button-1" type="button" onclick="save_dbst('<?php echo $__prefix; ?>', <?php echo $dbst_id; ?>);" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>" id="wpl_dbst_submit_button" />
		<span id="wpl_dbst_modify_ajax_loader"></span>
	</div>
	<?php
		/** include main file **/
		$path = _wpl_import('libraries.dbst_modify.main.main', true, true);
		include $path;
	?>
</div>
<?php
    $done_this = true;
}