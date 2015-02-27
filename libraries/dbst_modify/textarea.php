<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'textarea' and !$done_this)
{
?>
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
            <?php if(wpl_global::check_addon('pro')): ?>
			<div class="fanc-row fanc-inline-title">
				<?php echo __('Editor Settings', WPL_TEXTDOMAIN); ?>
			</div>
			<div class="fanc-row">
				<label for="<?php echo $__prefix; ?>opt_advanced_editor"><?php echo __('Advanced Editor', WPL_TEXTDOMAIN); ?></label>
                <select name="<?php echo $__prefix; ?>opt_advanced_editor" id="<?php echo $__prefix; ?>opt_advanced_editor">
					<option value="1" <?php if (isset($options['advanced_editor']) and $options['advanced_editor'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
					<option value="0" <?php if (isset($options['advanced_editor']) and $options['advanced_editor'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
				</select>
			</div>
            <?php endif; ?>
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