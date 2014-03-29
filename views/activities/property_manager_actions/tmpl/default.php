<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import($this->tpl_path . '.scripts.css');

$property_data = isset($params['property_data']['data']) ? $params['property_data']['data'] : NULL;
$pid = isset($property_data['id']) ? $property_data['id'] : NULL;
?>
<div id="pmanager_action_div<?php echo $pid; ?>" class="p-actions-wp pmanager_actions">
    <div id="pmanager_confirm<?php echo $pid; ?>" class="p-action-btn" onclick="confirm_property(<?php echo $pid; ?>);">
        <span><?php echo ($property_data['confirmed'] == 1 ? __('Confirm', WPL_TEXTDOMAIN) : __('Unconfirm', WPL_TEXTDOMAIN) ); ?></span>
        <i class="<?php echo ($property_data['confirmed'] == 1 ? 'icon-confirm' : 'icon-unconfirm'); ?>"></i>
    </div>
    <div id="pmanager_trash<?php echo $pid; ?>" class="p-action-btn" onclick="trash_property(<?php echo $pid; ?>);">
        <span><?php echo ($property_data['deleted'] == 1 ? __('Restore', WPL_TEXTDOMAIN): __('Trash', WPL_TEXTDOMAIN)); ?></span>
        <i class="<?php echo ($property_data['deleted'] == 1 ? 'icon-restore' : 'icon-trash'); ?>"></i>
    </div>
    <div id="pmanager_delete<?php echo $pid; ?>" class="p-action-btn" onclick="purge_property(<?php echo $pid; ?>);">
        <span><?php echo __('Purge', WPL_TEXTDOMAIN); ?></span>
        <i class="icon-delete"></i>
    </div>
    <a id="pmanager_edit<?php echo $pid; ?>" class="p-action-btn" href="<?php echo wpl_property::get_property_edit_link($pid); ?>">
        <span><?php echo __('Edit', WPL_TEXTDOMAIN); ?></span>
        <i class="icon-edit"></i>
    </a>
</div>