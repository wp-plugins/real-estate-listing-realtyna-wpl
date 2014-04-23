<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('views.backend.data_structure.tmpl.scripts.internal_unit_manager_js');
?>
<table class="widefat page" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th scope="col" class="manage-column" width="50"><?php echo __('Enabled', WPL_TEXTDOMAIN);?></th>
            <th scope="col" class="manage-column" width="50"><?php echo __('Name', WPL_TEXTDOMAIN);?></th>
            <th scope="col" class="manage-column"><?php echo __('Conv .Factor', WPL_TEXTDOMAIN);?></th>
            <th scope="col" class="manage-column"><?php echo __('Move', WPL_TEXTDOMAIN);?></th>
        </tr>
    </thead>
    <tbody class="sortable_unit">
        <?php foreach($this->units as $id=>$unit): ?>
            <tr id="item_row_<?php echo $unit['id']; ?>">
                <td>
                    <span class="action-btn enabled_check <?php echo $unit['enabled'] == 1 ? "icon-enabled" : "icon-disabled"; ?> " 
                    onclick="wpl_unit_enabled_change(<?php echo $unit['id']; ?>, <?php echo ($unit['enabled'] == 1 ? 0 : 1); ?>);" 
                    id="wpl_ajax_flag_<?php echo $unit['id']; ?>"></span>
                    <span class="wpl_ajax_loader" id="wpl_ajax_loader_<?php echo $unit['id']; ?>"></span>					
                </td>
                <td width="100">
                    <span><?php echo __($unit['name'], WPL_TEXTDOMAIN); ?></span>
                </td>
                <td width="100">
                    <span><?php echo $unit['tosi']; ?></span>
                </td>				
                <td class="wpl_manager_td">
                    <span class="action-btn icon-move" id="extension_move_1"></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
<table>	
