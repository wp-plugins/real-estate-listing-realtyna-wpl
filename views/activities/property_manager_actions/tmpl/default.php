<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import($this->tpl_path . '.scripts.css');

$property_data = isset($params['property_data']['data']) ? $params['property_data']['data'] : NULL;
$pid = isset($property_data['id']) ? $property_data['id'] : NULL;
?>
<div id="pmanager_action_div<?php echo $pid; ?>" class="p-actions-wp pmanager_actions">
    <?php if(wpl_users::check_access('change_user')): ?>
        <div id="pmanager_change_user<?php echo $pid; ?>" class="change-user-cnt-wp">
            <div class="change-user-wp">
                <label id="pmanager_change_user_label<?php echo $pid; ?>"
                       for="pmanager_change_user_select<?php echo $pid; ?>"><?php echo __('User', WPL_TEXTDOMAIN); ?>: </label>
                <?php $wpl_users = wpl_users::get_wpl_users(); ?>
                <select id="pmanager_change_user_select<?php echo $pid; ?>" data-has-chosen onchange="change_user(<?php echo $pid; ?>, this.value);">
                    <?php foreach ($wpl_users as $wpl_user): ?>
                        <option value="<?php echo $wpl_user->ID; ?>"
                            <?php if($wpl_user->ID == $property_data['user_id']) echo 'selected="selected"'; ?>>
                            <?php echo $wpl_user->user_login; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
    <div id="pmanager_confirm<?php echo $pid; ?>" class="p-action-btn" onclick="confirm_property(<?php echo $pid; ?>);">
        <span><?php echo($property_data['confirmed'] == 1 ? __('Confirm', WPL_TEXTDOMAIN) : __('Unconfirm', WPL_TEXTDOMAIN)); ?></span>
        <i class="<?php echo($property_data['confirmed'] == 1 ? 'icon-confirm' : 'icon-unconfirm'); ?>"></i>
    </div>
    <div id="pmanager_trash<?php echo $pid; ?>" class="p-action-btn" onclick="trash_property(<?php echo $pid; ?>);">
        <span><?php echo($property_data['deleted'] == 1 ? __('Restore', WPL_TEXTDOMAIN) : __('Trash', WPL_TEXTDOMAIN)); ?></span>
        <i class="<?php echo($property_data['deleted'] == 1 ? 'icon-restore' : 'icon-trash'); ?>"></i>
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