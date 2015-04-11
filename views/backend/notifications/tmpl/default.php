<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp">
    <header>
        <div id="icon-data-structure" class="icon48"></div>
        <h2><?php echo __('Notifications', WPL_TEXTDOMAIN); ?></h2>
    </header>
    <div class="wpl_notification_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp">
        <div class="notification_top_bar">
            <div class="wpl_left_section">
                <input type="text" name="notification_filter" id="notification_filter" placeholder="<?php echo __('Filter', WPL_TEXTDOMAIN); ?>" autocomplete="off" />
            </div>
            <div class="clearfix"></div>
        </div>
        <table class="widefat page" id="wpl_notification_table">
            <thead>
                <tr>
                    <th scope="col" class="manage-column"><?php echo '#'; ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Subject', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Description', WPL_TEXTDOMAIN); ?></th>
                    <th></th>
                    <th scope="col" class="manage-column"><?php echo __('Actions', WPL_TEXTDOMAIN); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($this->notifications as $notification): ?>
                <tr>
                    <td class="size-1"><?php echo $notification->id; ?></td>
                    <td class="wpl_notification_subject"><a href="<?php echo wpl_global::add_qs_var('tpl', 'modify'); ?>&id=<?php echo $notification->id; ?>"><?php echo $notification->subject; ?></a></td>
                    <td class="wpl_notification_description"><?php echo $notification->description; ?></td>
                    <td class="manager-wp">
                        <span class="wpl_ajax_loader" id="wpl_ajax_loader_<?php echo $notification->id ?>"></span>
                    </td>
                    <td class="manager-wp">
                        <?php
                        if($notification->enabled == 1)
                        {
                            $notification_enable_class = "wpl_show";
                            $notification_disable_class = "wpl_hidden";
                        }
                        elseif($notification->enabled == 2)
                        {
                            $notification_enable_class = "wpl_show disable";
                            $notification_disable_class = "wpl_hidden";
                        }
                        else
                        {
                            $notification_enable_class = "wpl_hidden";
                            $notification_disable_class = "wpl_show";
                        }
                        ?>
                        <span class="action-btn icon-disabled <?php echo $notification_disable_class; ?>" id="notification_disable_<?php echo $notification->id; ?>" <?php if($notification->enabled != 2): ?>onclick="wpl_set_enabled_notification(<?php echo $notification->id ?>, 1);"<?php endif; ?>></span>
                        <span class="action-btn icon-enabled <?php echo $notification_enable_class; ?>" id="notification_enable_<?php echo $notification->id; ?>" <?php if($notification->enabled != 2): ?>onclick="wpl_set_enabled_notification(<?php echo $notification->id ?>, 0);"<?php endif; ?>></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>