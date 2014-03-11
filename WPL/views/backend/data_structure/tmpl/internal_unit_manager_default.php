<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('views.backend.data_structure.tmpl.scripts.internal_unit_manager_js');
_wpl_import($this->tpl_path . '.scripts.internal_unit_manager_css');
?>
<div class="unit-manager-wp">
    <div class="panel-wp">
        <div class="panel-body">
            <span><?php echo __('Unit type', WPL_TEXTDOMAIN); ?> : </span>
            <select class="selectbox" onchange="load_new_unit_category(this.value);">
                <?php foreach ($this->unit_types as $id => $wp_unite_type): ?>
                <option value="<?php echo $wp_unite_type['id']; ?>" <?php if ($wp_unite_type['id'] == 4) echo 'selected="selected"'; ?>><?php echo __($wp_unite_type['name'], WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
            <span id="wpl_ajax_loader_span"></span>
        </div>
    </div>
    <!-- end of filtering panel -->
    <div id="unit_manager_content">
    	<?php $this->generate_currency_page(); ?>
    </div>
</div>
