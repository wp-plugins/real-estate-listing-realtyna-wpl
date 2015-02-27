<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'url' and !$done_this)
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
				<label for="<?php echo $__prefix; ?>opt_link_title"><?php echo __('Link title', WPL_TEXTDOMAIN); ?></label>
				<input type="text" name="<?php echo $__prefix; ?>opt_link_title" id="<?php echo $__prefix; ?>opt_link_title" value="<?php echo (isset($options['link_title']) ? $options['link_title'] : ''); ?>" />
			</div>
			<div class="fanc-row">
				<label for="<?php echo $__prefix; ?>opt_link_target"><?php echo __('Target', WPL_TEXTDOMAIN); ?></label>
                <select name="<?php echo $__prefix; ?>opt_link_target" id="<?php echo $__prefix; ?>opt_link_target">
                    <option <?php echo (isset($options['link_target']) and $options['link_target'] == '_blank') ? 'selected="selected"' : ''; ?> value="_blank"><?php echo __('New Window', WPL_TEXTDOMAIN); ?></option>
                    <option <?php echo (isset($options['link_target']) and $options['link_target'] == '_self') ? 'selected="selected"' : ''; ?> value="_self"><?php echo __('Current Window', WPL_TEXTDOMAIN); ?></option>
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