<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if ($type == 'addon_video' and !$done_this) {
    ?>

    <div class="fanc-body">
        <div class="fanc-row fanc-button-row-2">
            <input class="wpl-button button-1" type="button" onclick="save_dbst('<?php echo $__prefix; ?>', <?php echo $dbst_id; ?>);" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>" id="wpl_dbst_submit_button" />
            <span id="wpl_dbst_modify_ajax_loader"></span>
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
                <div class="fanc-row">
                    <label for="<?php echo $__prefix; ?>opt_ext_file"><?php echo __('Valid file type', WPL_TEXTDOMAIN); ?></label>
                    <input type="text" name="<?php echo $__prefix; ?>opt_ext_file" id="<?php echo $__prefix; ?>opt_ext_file" value="<?php echo ($options['ext_file'] ? $options['ext_file'] : ''); ?>" />
                </div>
                <div class="fanc-row">
                    <label for="<?php echo $__prefix; ?>opt_file_size"><?php echo __('Maximum file size', WPL_TEXTDOMAIN); ?></label>
                    <input type="text" name="<?php echo $__prefix; ?>opt_file_size" id="<?php echo $__prefix; ?>opt_file_size" value="<?php echo ($options['file_size'] ? $options['file_size'] : ''); ?>" />
                </div>
            </div>
        </div>
    </div>
    <?php
    $done_this = true;
}