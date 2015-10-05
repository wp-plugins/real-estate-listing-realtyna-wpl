<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="panel-wp">
    <h3><?php echo __('Add new field', WPL_TEXTDOMAIN); ?></h3>
    <div class="panel-body">
        <select id="wpl_dbst_types_select">
            <?php foreach ($this->dbst_types as $dbst_type): ?>
                <option value="<?php echo $dbst_type->type; ?>"><?php echo $dbst_type->type; ?></option>
            <?php endforeach; ?>
        </select>
        <input data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_flex_edit_div" type="button" class="wpl-button button-1" onclick="generate_modify_page(0);" value="<?php echo __('Add', WPL_TEXTDOMAIN); ?>" />
    </div>
</div>