<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'parent' and !$done_this)
{
    $kinds = wpl_flex::get_kinds('wpl_properties');
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
				/** include main file **/
				include _wpl_import('libraries.dbst_modify.main.main', true, true);
			?>
		</div>
		<div class="col-fanc-right" id="wpl_flex_specific_options">
			<div class="fanc-row fanc-inline-title">
				<?php echo __('Specific Options', WPL_TEXTDOMAIN); ?>
			</div>
			<?php
				/** include specific file **/
				include _wpl_import('libraries.dbst_modify.main.'.($kind == 2 ? 'user' : '').'specific', true, true);
			?>
            <div class="fanc-row fanc-inline-title">
				<span>
					<?php echo __('Params', WPL_TEXTDOMAIN); ?>    
				</span>
			</div>
			<div class="fanc-row">
				<label for="<?php echo $__prefix; ?>opt_parent_kind"><?php echo __('Parent Kind', WPL_TEXTDOMAIN); ?></label>
                <select name="<?php echo $__prefix; ?>opt_parent_kind" id="<?php echo $__prefix; ?>opt_parent_kind">
                    <?php foreach($kinds as $kind): ?>
                    <option <?php echo (isset($options['parent_kind']) and $options['parent_kind'] == $kind['id']) ? 'selected="selected"' : ''; ?> value="<?php echo $kind['id']; ?>"><?php echo __($kind['name'], WPL_TEXTDOMAIN); ?></option>
                    <?php endforeach; ?>
                </select>
			</div>
            <div class="fanc-row">
				<label for="<?php echo $__prefix; ?>opt_replace"><?php echo __('Replace Parent Data', WPL_TEXTDOMAIN); ?></label>
                <select name="<?php echo $__prefix; ?>opt_replace" id="<?php echo $__prefix; ?>opt_replace">
                    <option <?php echo (isset($options['replace']) and $options['replace'] == 1) ? 'selected="selected"' : ''; ?> value="1"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
                    <option <?php echo (isset($options['replace']) and $options['replace'] == 0) ? 'selected="selected"' : ''; ?> value="0"><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
                </select>
			</div>
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