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
                    <th scope="col" class="manage-column"><?php echo __('Template', WPL_TEXTDOMAIN); ?></th>
                    <th></th>
                    <th scope="col" class="manage-column"><?php echo __('Enable', WPL_TEXTDOMAIN); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column"><?php echo '#'; ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Subject', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Description', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Template', WPL_TEXTDOMAIN); ?></th>
                    <th></th>
                    <th scope="col" class="manage-column"><?php echo __('Enable', WPL_TEXTDOMAIN); ?></th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                foreach($this->notifications as $notification):
                    ?>
                    <tr>
                        <td class="size-1"><?php echo $notification->id; ?></td>
                        <td class="wpl_notification_subject"><a href="admin.php?page=wpl_admin_notifications&tpl=modify&id=<?php echo $notification->id; ?>#basic"><?php echo $notification->subject; ?></a></td>
                        <td class="wpl_notification_description"><?php echo $notification->description; ?></td>
                        <td class="wpl_notification_template"><?php echo $notification->template; ?></td>
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
                            else
                            {
                                $notification_enable_class = "wpl_hidden";
                                $notification_disable_class = "wpl_show";
                            }
                            ?>
                            <span class="action-btn icon-disabled <?php echo $notification_disable_class; ?>" id="notification_disable_<?php echo $notification->id; ?>" onclick="wpl_set_enabled_notification(<?php echo $notification->id ?>, 1);"></span>
                            <span class="action-btn icon-enabled <?php echo $notification_enable_class; ?>" id="notification_enable_<?php echo $notification->id; ?>" onclick="wpl_set_enabled_notification(<?php echo $notification->id ?>, 0);"></span>
                        </td>
                    </tr>
                    <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>
<?php
/*
$params = array(
    'category_field' => 'year',
    'data_date_format' => 'YYYY',
    'value_field' => 'value',
    'min_period' => 'YYYY',
    'ballon_text_size' => '14px',
    'ballon_data_type_format' => 'YYYY',
    'chart_width' => '100%',
    'chart_height' => '300px',
    'label_rotation' => '0',
    'data' => array(
        array(
            'year' => '2004',
            'value' => '5'
        ),
        array(
            'year' => '2005',
            'value' => '20'
        ),
        array(
            'year' => '2006',
            'value' => '10'
        ),
        array(
            'year' => '2007',
            'value' => '15'
        ),
        array(
            'year' => '2008',
            'value' => '40'
        ),
        array(
            'year' => '2009',
            'value' => '10'
        ),
        array(
            'year' => '2010',
            'value' => '100'
        ),
    )
);
?>
<?php
wpl_global::import_activity('charts:default', '', $params);

$params = array(
    'category_field' => 'country',
    'data_date_format' => '',
    'value_field' => 'visits',
    'min_period' => '',
    'ballon_text_size' => '14px',
    'ballon_data_type_format' => '',
    'chart_width' => '400px',
    'chart_height' => '300px',
    'label_rotation' => '0',
    'data' => array(
        array(
            'country' => 'US',
            'visits' => '5'
        ),
        array(
            'country' => 'UK',
            'visits' => '20'
        ),
        array(
            'country' => 'TR',
            'visits' => '10'
        ),
        array(
            'country' => 'FR',
            'visits' => '15'
        ),
        array(
            'country' => 'RU',
            'visits' => '40'
        ),
        array(
            'country' => 'JP',
            'visits' => '10'
        ),
        array(
            'country' => 'GER',
            'visits' => '100'
        ),
    )
);
wpl_global::import_activity('charts:column', '', $params);
$params = array(
    'category_field' => 'country',
    'data_date_format' => '',
    'value_field' => 'litres',
    'min_period' => '',
    'ballon_text_size' => '14px',
    'ballon_data_type_format' => '',
    'chart_width' => '400px',
    'chart_height' => '300px',
    'label_rotation' => '0',
    'data' => array(
        array(
            'country' => 'US',
            'litres' => '1.5'
        ),
        array(
            'country' => 'UK',
            'litres' => '20.9999'
        ),
        array(
            'country' => 'TR',
            'litres' => '109.6'
        ),
        array(
            'country' => 'FR',
            'litres' => '15'
        ),
        array(
            'country' => 'RU',
            'litres' => '40.25'
        ),
        array(
            'country' => 'JP',
            'litres' => '100'
        ),
        array(
            'country' => 'GER',
            'litres' => '56.88'
        ),
    )
);
wpl_global::import_activity('charts:pie', '', $params);
*/
?>