<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if ($type == 'textarea' and !$done_this) {
    ?>
    <div class="fanc-body">
        <div class="fanc-row fanc-button-row-2">
            <input class="wpl-button button-1" type="button" onclick="save_dbst('<?php echo $__prefix; ?>', <?php echo $dbst_id; ?>);" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>" id="wpl_dbst_submit_button" />
            <span class="ajax-inline-save" id="wpl_dbst_modify_ajax_loader"></span>
        </div>
        <div class="col-wp">
            <div class="col-fanc-left" id="wpl_flex_general_options">
                <div class="fanc-row fanc-inline-title">
                    <?php echo __('General Options', WPL_TEXTDOMAIN); ?>
                </div>
                <?php
                /** include main file * */
                $path = _wpl_import('libraries.dbst_modify.main.main', true, true);
                include $path;
                ?>
            </div>
            <div class="col-fanc-right" id="wpl_flex_specific_options">
                <div class="fanc-row fanc-inline-title">
                    <?php echo __('Specific Options', WPL_TEXTDOMAIN); ?>
                </div>
                <?php
                /** include specific file * */
                $path = _wpl_import('libraries.dbst_modify.main.specific', true, true);
                include $path;
                ?>
                <div class="fanc-row fanc-inline-title">
                    <?php echo __('Editor Settings', WPL_TEXTDOMAIN); ?>
                </div>
                <div class="fanc-row">
                    <label for="<?php echo $__prefix; ?>opt_advanced_editor"><?php echo __('Advanced Editor', WPL_TEXTDOMAIN); ?></label>
                    <select name="<?php echo $__prefix; ?>opt_advanced_editor" id="<?php echo $__prefix; ?>opt_advanced_editor">
                        <option value="1" <?php if ($options['advanced_editor'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
                        <option value="0" <?php if ($options['advanced_editor'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
                    </select>
                </div>
                <div class="fanc-row">
                    <label for="<?php echo $__prefix; ?>opt_cols"><?php echo __('Column width', WPL_TEXTDOMAIN); ?></label>
                    <input type="text" name="<?php echo $__prefix; ?>opt_cols" id="<?php echo $__prefix; ?>opt_cols" value="<?php echo ($options['cols'] ? $options['cols'] : 40); ?>" />
                </div>
                <div class="fanc-row">
                    <label for="<?php echo $__prefix; ?>opt_cols"><?php echo __('Column width', WPL_TEXTDOMAIN); ?></label>
                    <input type="text" name="<?php echo $__prefix; ?>opt_cols" id="<?php echo $__prefix; ?>opt_cols" value="<?php echo ($options['cols'] ? $options['cols'] : 40); ?>" />
                </div>
                <div class="fanc-row">
                    <label for="<?php echo $__prefix; ?>opt_rows"><?php echo __('Row width', WPL_TEXTDOMAIN); ?></label>
                    <input type="text" name="<?php echo $__prefix; ?>opt_rows" id="<?php echo $__prefix; ?>opt_rows" value="<?php echo ($options['rows'] ? $options['rows'] : 6); ?>" />
                </div>
            </div>
        </div>
    </div>
    <?php
    $done_this = true;
}