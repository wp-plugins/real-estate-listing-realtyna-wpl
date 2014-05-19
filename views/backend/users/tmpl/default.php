<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path . '.scripts.js');
_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp user-wp">
    <header>
        <div id="icon-user" class="icon48">
        </div>
        <h2><?php echo __('User Manager', WPL_TEXTDOMAIN); ?></h2>
    </header>
    <div class="wpl_user_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp">
        <div class="tablenav">
            <div class="tablenav-pages">
                <?php echo $this->pagination->show(); ?>
            </div>
        </div>

        <table class="widefat page">
            <thead>
                <tr>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('ID', WPL_TEXTDOMAIN), 'u.id'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('User Name', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Name', WPL_TEXTDOMAIN); ?></th>
                    <?php if(wpl_global::check_addon('pro')): ?><th scope="col" class="manage-column"><?php echo __('Membership', WPL_TEXTDOMAIN); ?></th><?php endif; ?>
                    <th scope="col" class="manage-column"><?php echo __('Email', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('Date Registered', WPL_TEXTDOMAIN), 'u.user_registered'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Actions', WPL_TEXTDOMAIN); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('ID', WPL_TEXTDOMAIN), 'u.id'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('User Name', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Name', WPL_TEXTDOMAIN); ?></th>
                    <?php if(wpl_global::check_addon('pro')): ?><th scope="col" class="manage-column"><?php echo __('Membership', WPL_TEXTDOMAIN); ?></th><?php endif; ?>
                    <th scope="col" class="manage-column"><?php echo __('Email', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('Date Registered', WPL_TEXTDOMAIN), 'u.user_registered'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Actions', WPL_TEXTDOMAIN); ?></th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                foreach ($this->wp_users as $wp_user):
                    $wpl_data = wpl_users::get_wpl_data($wp_user->ID);
                    ?>
                    <tr id="item_row<?php echo $wp_user->ID; ?>">
                        <td class="size-1"><?php echo $wp_user->ID; ?></td>
                        <td><?php echo $wp_user->user_login; ?></td>
                        <td><?php echo (is_object($wpl_data) ? $wpl_data->first_name.' '.$wpl_data->last_name : ''); ?></td>
                        <?php if(wpl_global::check_addon('pro')): ?>
                        <td>
                            <select data-without-chosen name="membership_id_<?php echo $wp_user->ID; ?>" id="membership_id_<?php echo $wp_user->ID; ?>" onChange="wpl_change_membership(<?php echo $wp_user->ID; ?>);" autocomplete="off">
                                <option value=""><?php echo __('None', WPL_TEXTDOMAIN); ?></option>
                                <?php foreach ($this->memberships as $membership): ?>
                                    <option value="<?php echo $membership->id; ?>"  <?php if (is_object($wpl_data) and $membership->id == $wpl_data->membership_id) echo 'selected="selected"'; ?>><?php echo __($membership->membership_name, WPL_TEXTDOMAIN); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span id="wpl_ajax_loader_membership_<?php echo $wp_user->ID; ?>"></span>
                        </td>
                        <?php endif; ?>
                        <td><?php echo $wp_user->user_email; ?></td>				
                        <td><?php echo $wp_user->user_registered; ?></td>            
                        <td class="wpl_manager_td">
                            <span id="wpl_edit_btn_<?php echo $wp_user->id; ?>" class="action-btn icon-edit wpl_show fancybox wpl_user_edit_div" href="#wpl_user_edit_div" onclick="wpl_edit_user(<?php echo $wp_user->ID; ?>);" style="<?php if(!$wp_user->id) echo 'display: none;'; ?>"></span>
                            <span class="<?php if ($wp_user->id) echo 'wpl_hidden_element'; ?>" id="no_added_to_wpl<?php echo $wp_user->ID; ?>">
                                <span class="action-btn icon-plus" onclick="add_to_wpl(<?php echo $wp_user->ID; ?>);" title="<?php echo __('Add user to WPL', WPL_TEXTDOMAIN); ?>"></span>
                            </span>
                            <span class="<?php if (!$wp_user->id) echo 'wpl_hidden_element'; ?>  wpl_actions_icon_disable" id="added_to_wpl<?php echo $wp_user->ID; ?>">                	
                                <span class="action-btn icon-disabled" onclick="wpl_remove_user(<?php echo $wp_user->ID; ?>, 0);" title="<?php echo __('Remove user from WPL', WPL_TEXTDOMAIN); ?>"></span>
                            </span>
                            <span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $wp_user->ID; ?>"></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="wpl_user_edit_div" class="fanc-box-wp wpl_lightbox wpl_hidden_element"></div>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>
<script type="text/javascript">
    (function($){$(function(){isWPL();})})(jQuery);
</script>