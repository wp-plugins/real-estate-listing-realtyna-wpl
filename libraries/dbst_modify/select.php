<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'select' and !$done_this)
{
?>
<script type="text/javascript">
function wpl_flex_add_param()
{
	html = '<div class="fanc-row" id="wpl_flex_select_params_row' + wpl_flex_param_counter + '">' +
			'<input type="hidden" id="<?php echo $__prefix; ?>opt_params[' + wpl_flex_param_counter + '][key]" ' +
			'name="<?php echo $__prefix; ?>opt_params[' + wpl_flex_param_counter + '][key]" value="' + wpl_flex_param_counter + '" />' +
			'<input type="hidden" id="<?php echo $__prefix; ?>opt_params[' + wpl_flex_param_counter + '][enabled]" ' +
			'name="<?php echo $__prefix; ?>opt_params[' + wpl_flex_param_counter + '][enabled]" value="1" />' +
			'<input type="text" id="<?php echo $__prefix; ?>opt_params[' + wpl_flex_param_counter + '][value]" name="<?php echo $__prefix; ?>opt_params[' + wpl_flex_param_counter + '][value]" value="" />' +
			'<span class="margin-left-1p action-btn icon-enabled" id="wpl_felx_change_param_status' + wpl_flex_param_counter + '" onclick="wpl_flex_disable_param(' + wpl_flex_param_counter + ');"></span></div>';
	wplj("#wpl_flex_select_before").before(html);

	wpl_flex_param_counter++;
}

function wpl_flex_disable_param(param_id)
{
	if (wplj("#wpl_felx_change_param_status" + param_id).hasClass("wpl_actions_icon_enable"))
	{
		wplj("#wpl_flex_select_params_row" + param_id + " input[type='text']").attr("disabled", "disabled");
		wplj("#wpl_felx_change_param_status" + param_id).removeClass("wpl_actions_icon_enable");
		wplj("#wpl_felx_change_param_status" + param_id).addClass("wpl_actions_icon_disable");
		wplj("#wpl_flex_select_params_row" + param_id + " input[name='<?php echo $__prefix; ?>opt_params[" + param_id + "][enabled]']").val(0);
	}
	else
	{
		wplj("#wpl_flex_select_params_row" + param_id + " input[type='text']").removeAttr("disabled");
		wplj("#wpl_felx_change_param_status" + param_id).removeClass("wpl_actions_icon_disable");
		wplj("#wpl_felx_change_param_status" + param_id).addClass("wpl_actions_icon_enable");
		wplj("#wpl_flex_select_params_row" + param_id + " input[name='<?php echo $__prefix; ?>opt_params[" + param_id + "][enabled]']").val(1);
	}
}
</script>
<div class="fanc-body">
	<div class="fanc-row fanc-button-row-2">
        <span class="ajax-inline-save" id="wpl_dbst_modify_ajax_loader"></span>
		<input class="wpl-button button-1" type="button" onclick="save_dbst('<?php echo $__prefix; ?>', <?php echo $dbst_id; ?>);" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>" id="wpl_dbst_submit_button" />
	</div>
	<div class="col-wp">
		<div class="col-fanc-left" id="wpl_flex_general_options">
			<div class="fanc-row fanc-inline-title">
				<?php echo __('General Options', WPL_TEXTDOMAIN); ?>
			</div>
			<?php
				/** include main file * */
				include _wpl_import('libraries.dbst_modify.main.main', true, true);
			?>
		</div>
		<div class="col-fanc-right" id="wpl_flex_specific_options">
			<div class="fanc-row fanc-inline-title">
				<?php echo __('Specific Options', WPL_TEXTDOMAIN); ?>
			</div>
			<?php
				/** include specific file * */
				include _wpl_import('libraries.dbst_modify.main.'.($kind == 2 ? 'user' : '').'specific', true, true);
			?>
			<div class="fanc-row fanc-inline-title">
				<span>
					<?php echo __('Params', WPL_TEXTDOMAIN); ?>    
				</span>
				<span class="action-btn icon-plus margin-left-1p" onclick="wpl_flex_add_param();"></span>
			</div>
			<?php
			$i = 1;
			$option_params = (isset($options['params']) and is_array($options['params'])) ? $options['params'] : array();
			foreach ($option_params as $k => $v):
			?>
				<div class="fanc-row" id="wpl_flex_select_params_row<?php echo $i; ?>">
					<input type="text" id="<?php echo $__prefix; ?>opt_params[<?php echo $i; ?>][value]" name="<?php echo $__prefix; ?>opt_params[<?php echo $i; ?>][value]" value="<?php echo $v['value']; ?>" <?php echo ($v['enabled'] ? '' : 'disabled="disabled"'); ?> />
					<span class="margin-left-1p action-btn icon-<?php echo ($v['enabled'] ? 'enabled' : 'disabled'); ?>" id="wpl_felx_change_param_status<?php echo $i; ?>" onclick="wpl_flex_disable_param(<?php echo $i; ?>);"></span>
					<input type="hidden" id="<?php echo $__prefix; ?>opt_params[<?php echo $i; ?>][key]" name="<?php echo $__prefix; ?>opt_params[<?php echo $i; ?>][key]" value="<?php echo $v['key']; ?>" />
					<input type="hidden" id="<?php echo $__prefix; ?>opt_params[<?php echo $i; ?>][enabled]" name="<?php echo $__prefix; ?>opt_params[<?php echo $i; ?>][enabled]" value="<?php echo $v['enabled']; ?>" />
				</div>
			<?php $i++; endforeach; ?>
			<div id="wpl_flex_select_before"></div>
			<script type="text/javascript">
			var wpl_flex_param_counter = <?php echo $i; ?>;
			</script>
		</div>
	</div>
    <div class="col-wp">
        <div class="col-fanc-left">
        	<div class="fanc-row fanc-inline-title">
                <?php echo __('Accesses', WPL_TEXTDOMAIN); ?>
            </div>
            <?php
				/** include accesses file **/
				include _wpl_import('libraries.dbst_modify.main.accesses', true, true);
            ?>
        </div>
    </div>
</div>
<?php
    $done_this = true;
}