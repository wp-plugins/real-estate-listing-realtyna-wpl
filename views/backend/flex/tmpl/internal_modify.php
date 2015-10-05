<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<!-- do not change this id -->
<div class="fanc-content size-width-2" id="wpl_flex_modify_container">
        <h2><?php echo __('General Options', WPL_TEXTDOMAIN); ?></h2>
        <?php
        // loads libraries/dbst_modify/
        wpl_flex::generate_modify_form($this->field_type, $this->field_id, $this->kind);
        /** including a custom file **/ $this->_wpl_import($this->tpl_path.'.custom.modifydbst');
        ?>
</div>