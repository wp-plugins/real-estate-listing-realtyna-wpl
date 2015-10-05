<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Get Franchise addon assets **/
$current_blog_id = wpl_global::get_current_blog_id();
$super_admin = wpl_users::is_super_admin();

_wpl_import($this->tpl_path . '.scripts.js');
_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp user-wp">
    <header>
        <div id="icon-user" class="icon48"></div>
        <h2><?php echo __('User Manager', WPL_TEXTDOMAIN); ?></h2>
        <?php if(wpl_global::check_addon('membership')): ?>
        <a href="<?php echo wpl_global::add_qs_var('kind', wpl_flex::get_kind_id('user'), wpl_global::get_wpl_admin_menu('wpl_admin_flex')); ?>" class="setting-toolbar-btn button" title="<?php echo __('Manage User Data Structure', WPL_TEXTDOMAIN); ?>"><?php echo __('Manage User Data Structure', WPL_TEXTDOMAIN); ?></a>
        <?php endif; ?>
    </header>
    <div class="wpl_user_list"><div class="wpl_show_message"></div></div>
    <div class="wpl-users-search-form">
        <form method="GET" id="wpl_users_search_form">
            <input type="hidden" name="page" value="wpl_admin_user_manager" />
            <label for="sf_filter"><?php echo __('Filter', WPL_TEXTDOMAIN); ?>: </label>
            <input type="text" id="sf_filter" name="filter" value="<?php echo $this->filter; ?>" placeholder="<?php echo __('Name, Email', WPL_TEXTDOMAIN); ?>" class="long" />
            <select name="show_all" id="show_all" data-has-chosen="">
                <option value="0" <?php if($this->show_all == 0) echo 'selected="selected"'; ?>><?php echo __('Only WPL users'); ?></option>
                <option value="1" <?php if($this->show_all == 1) echo 'selected="selected"'; ?>><?php echo __('All WordPress users'); ?></option>
            </select>
            <?php if(wpl_global::check_addon('membership')): ?>
            <select name="membership_id" id="membership_id" data-has-chosen="">
                <option value=""><?php echo __('Membership', WPL_TEXTDOMAIN); ?></option>
                <?php foreach($this->memberships as $membership): ?>
                <option value="<?php echo $membership->id; ?>" <?php if(isset($this->membership_id) and $membership->id == $this->membership_id) echo 'selected="selected"'; ?>><?php echo __($membership->membership_name, WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
            <button class="wpl-button button-1"><?php echo __('Search', WPL_TEXTDOMAIN); ?></button>
            <button type="reset" class="button button-1" onclick="wpl_reset_users_form();"><?php echo __('Reset', WPL_TEXTDOMAIN); ?></button>
        </form>
    </div>
    <div class="sidebar-wp">
        <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
        <div class="pagination-wp">
            <?php echo $this->pagination->show(); ?>
        </div>
        <?php endif; ?>
        <table class="widefat page">
            <thead>
                <tr>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('ID', WPL_TEXTDOMAIN), 'u.id'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Username', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Name', WPL_TEXTDOMAIN); ?></th>
                    <?php if(wpl_global::check_addon('membership')): ?><th scope="col" class="manage-column"><?php echo __('Membership', WPL_TEXTDOMAIN); ?></th><?php endif; ?>
                    <th scope="col" class="manage-column"><?php echo __('Email', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('Registered', WPL_TEXTDOMAIN), 'u.user_registered'); ?></th>
                    <?php if(wpl_global::check_addon('membership')): ?><th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('Expiry Date', WPL_TEXTDOMAIN), 'wpl.expiry_date'); ?></th><?php endif; ?>
                    <th scope="col" class="manage-column"><?php echo __('Actions', WPL_TEXTDOMAIN); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('ID', WPL_TEXTDOMAIN), 'u.id'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Username', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Name', WPL_TEXTDOMAIN); ?></th>
                    <?php if(wpl_global::check_addon('membership')): ?><th scope="col" class="manage-column"><?php echo __('Membership', WPL_TEXTDOMAIN); ?></th><?php endif; ?>
                    <th scope="col" class="manage-column"><?php echo __('Email', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('Registered', WPL_TEXTDOMAIN), 'u.user_registered'); ?></th>
                    <?php if(wpl_global::check_addon('membership')): ?><th scope="col" class="manage-column"><?php echo wpl_global::order_table(__('Expiry Date', WPL_TEXTDOMAIN), 'u.user_registered'); ?></th><?php endif; ?>
                    <th scope="col" class="manage-column"><?php echo __('Actions', WPL_TEXTDOMAIN); ?></th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach($this->wp_users as $wp_user): $wpl_data = wpl_users::get_wpl_data($wp_user->ID); ?>
                <tr id="item_row<?php echo $wp_user->ID; ?>">
                    <td class="size-1"><?php echo $wp_user->ID; ?></td>
                    <td>
                        <?php if($wp_user->id): ?>
                        <a href="<?php echo wpl_global::add_qs_var('id', $wp_user->ID, wpl_global::get_wpl_admin_menu('wpl_admin_profile')); ?>"><?php echo $wp_user->user_login; ?></a>
                        <?php else: ?>
                        <?php echo $wp_user->user_login; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo (is_object($wpl_data) ? $wpl_data->first_name.' '.$wpl_data->last_name : ''); ?></td>
                    <?php if(wpl_global::check_addon('membership')): ?>
                    <td>
                        <?php if(wpl_global::check_addon('franchise') and $wp_user->blog_id != $current_blog_id and !$super_admin): ?>
                        <span><?php echo __('No Permission!', WPL_TEXTDOMAIN); ?></span>
                        <?php elseif($wp_user->id): ?>
                        <select data-without-chosen name="membership_id_<?php echo $wp_user->ID; ?>" id="membership_id_<?php echo $wp_user->ID; ?>" onChange="wpl_change_membership(<?php echo $wp_user->ID; ?>);" autocomplete="off">
                            <option value=""><?php echo __('None', WPL_TEXTDOMAIN); ?></option>
                            <?php foreach ($this->memberships as $membership): ?>
                                <option value="<?php echo $membership->id; ?>"  <?php if (is_object($wpl_data) and $membership->id == $wpl_data->membership_id) echo 'selected="selected"'; ?>><?php echo __($membership->membership_name, WPL_TEXTDOMAIN); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id="wpl_ajax_loader_membership_<?php echo $wp_user->ID; ?>"></span>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                    <td><?php echo $wp_user->user_email; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($wp_user->user_registered)); ?></td>
                    <?php if(wpl_global::check_addon('membership')): ?>
                    <td>
                        <span id="wpl_user_expiry_date<?php echo $wp_user->ID; ?>">
                        <?php
                        if(!trim($wp_user->expiry_date) or $wp_user->expiry_date == '-1'): echo __('Unlimited', WPL_TEXTDOMAIN).'</span>';
                        else: echo date('Y-m-d', strtotime($wp_user->expiry_date));
                        ?>
                        </span>
                        <span id="wpl_user_renew<?php echo $wp_user->ID; ?>" class="action-btn wpl-gen-icon-refresh" onclick="wpl_renew_user(<?php echo $wp_user->ID; ?>);" title="<?php echo __('Renew', WPL_TEXTDOMAIN); ?>"></span>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                    <td class="wpl_manager_td">
                        <?php if(wpl_global::check_addon('franchise') and $wp_user->blog_id != $current_blog_id and !$super_admin): ?>
                        <span><?php echo __('No Permission!', WPL_TEXTDOMAIN); ?></span>
                        <?php else: ?>
                        <span data-realtyna-lightbox data-realtyna-href="#wpl_user_edit_div" id="wpl_edit_btn_<?php echo $wp_user->id; ?>" class="action-btn icon-edit wpl_show wpl_user_edit_div" onclick="wpl_edit_user(<?php echo $wp_user->ID; ?>);" style="<?php if(!$wp_user->id) echo 'display: none;'; ?>"></span>
                        <span class="<?php if ($wp_user->id) echo 'wpl_hidden_element'; ?>" id="no_added_to_wpl<?php echo $wp_user->ID; ?>">
                            <span class="action-btn icon-plus" onclick="add_to_wpl(<?php echo $wp_user->ID; ?>);" title="<?php echo __('Add user to WPL', WPL_TEXTDOMAIN); ?>"></span>
                        </span>
                        <span class="<?php if (!$wp_user->id) echo 'wpl_hidden_element'; ?>  wpl_actions_icon_disable" id="added_to_wpl<?php echo $wp_user->ID; ?>">                	
                            <span class="action-btn icon-disabled" onclick="wpl_remove_user(<?php echo $wp_user->ID; ?>, 0);" title="<?php echo __('Remove user from WPL', WPL_TEXTDOMAIN); ?>"></span>
                        </span>
                        <span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $wp_user->ID; ?>"></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="wpl_user_edit_div" class="wpl_hidden_element"></div>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>