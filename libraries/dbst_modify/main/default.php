<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$done_this = true;
?>
<div class="fanc-body">
    <div class="fanc-row  fanc-button-row-2">
        <input class="wpl-button button-1" type="button" onclick="save_dbst('<?php echo $__prefix; ?>', <?php echo $dbst_id; ?>);" value="<?php echo __('Save'); ?>" id="wpl_dbst_submit_button" />
        <span class="ajax-inline-save" id="wpl_dbst_modify_ajax_loader"></span>
    </div>
    <div class="col-wp">
        <div class="col-fanc-left" id="wpl_flex_general_options">
        	<div class="fanc-row fanc-inline-title">
                <?php echo __('General Options', WPL_TEXTDOMAIN); ?>
            </div>
            <?php
            /** include main file **/
            $path = _wpl_import('libraries.dbst_modify.main.main', true, true);
            include $path;
            ?>
        </div>
        <div class="col-fanc-right">
        	<div class="fanc-row fanc-inline-title">
                <?php echo __('Specific Options', WPL_TEXTDOMAIN); ?>
            </div>
            <?php
            /** include specific file **/
            $path = _wpl_import('libraries.dbst_modify.main.specific', true, true);
            include $path;
            ?>
        </div>
    </div>
    <div class="fanc-row">

    </div>
</div>