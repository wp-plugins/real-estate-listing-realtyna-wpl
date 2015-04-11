<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp">
    <header>
        <div id="icon-data-structure" class="icon48"></div>
        <h2><?php echo __('Activity Manager', WPL_TEXTDOMAIN); ?></h2>
    </header>
    <div class="wpl_activity_manager_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp">
        <div class="activity_manager_top_bar">
        	<div class="wpl_left_section">
            	<input type="text" name="activity_manager_filter" id="activity_manager_filter" placeholder="<?php echo __('Filter', WPL_TEXTDOMAIN); ?>" autocomplete="off" />
            </div>
            <div class="wpl_right_section">
                <select name="wpl_activity_add" id="wpl_activity_add" data-has-chosen="1">
                    <option value="">-----</option>
                    <?php foreach($this->available_activities as $available_activity): ?>
                    <option value="<?php echo $available_activity; ?>"><?php echo $available_activity; ?></option>
                    <?php endforeach; ?>
                </select>&nbsp;
                <span class="wpl_create_new action-btn icon-plus" title="<?php echo __('Add Activity', WPL_TEXTDOMAIN); ?>" onclick="wpl_generate_modify_activity_page(0);"></span>
                <span id="wpl_lightbox_handler" class="wpl_hidden_element" data-realtyna-href="#wpl_activity_manager_edit_div"></span>
            </div>
            <div class="clearfix"></div>
        </div>
        <table class="widefat page" id="wpl_activity_manager_table">
            <thead>
                <tr>
                    <th scope="col" class="manage-column"><?php echo __('ID', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Title', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Activity', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Layout', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Position', WPL_TEXTDOMAIN); ?></th>
                    <th></th>
                    <th id="wpl_actions_td_thead" scope="col" class="manage-column wpl_actions_td"><?php echo __('Actions', WPL_TEXTDOMAIN); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column"><?php echo __('ID', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Title', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Activity', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Layout', WPL_TEXTDOMAIN); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Position', WPL_TEXTDOMAIN); ?></th>
                    <th></th>
                    <th scope="col" class="manage-column wpl_actions_td"><?php echo __('Actions', WPL_TEXTDOMAIN); ?></th>
                </tr>
            </tfoot>
            <tbody class="sortable_activity">
                <?php
                foreach($this->activities as $activity)
				{
                    $activity_field_name = wpl_activity::get_activity_name_layout($activity->activity);
					
                    /** Skip Backend Activity **/
                    if(wpl_activity::check_activity($activity_field_name[0], wpl_activity::ACTIVITY_BACKEND)) continue;
                    ?>
                    <tr id="<?php echo $activity->id; ?>">
                        <td class="size-1"><?php echo $activity->id; ?></td>
                        <td class="wpl_activity_title"><?php echo $activity->title; ?></td>
                        <td class="wpl_activity_activity"><?php echo $activity_field_name[0]; ?></td>
                        <td class="wpl_activity_layout"><?php echo isset($activity_field_name[1]) ? $activity_field_name[1] : ''; ?></td>
                        <td class="wpl_activity_position"><?php echo $activity->position; ?></td>
                        <td class="manager-wp">
                            <span class="wpl_ajax_loader" id="wpl_ajax_loader_<?php echo $activity->id ?>"></span>
                        </td>
                        <td class="wpl_actions_td">
                            <?php
                            if($activity->enabled == 1)
                            {
                                $activity_enable_class = "wpl_show";
                                $activity_disable_class = "wpl_hidden";
                            }
                            else
                            {
                                $activity_enable_class = "wpl_hidden";
                                $activity_disable_class = "wpl_show";
                            }
                            ?>
                            <span class="action-btn icon-disabled <?php echo $activity_disable_class; ?>" id="activity_disable_<?php echo $activity->id; ?>" onclick="wpl_set_enabled_activity(<?php echo $activity->id ?>, 1);"></span>
                            <span class="action-btn icon-enabled <?php echo $activity_enable_class; ?>" id="activity_enable_<?php echo $activity->id; ?>" onclick="wpl_set_enabled_activity(<?php echo $activity->id ?>, 0);"></span>
                            <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_activity_manager_edit_div" class="action-btn icon-edit" onclick="wpl_generate_modify_activity_page(<?php echo $activity->id; ?>)"></span>
                            <span class="action-btn icon-recycle wpl_show" onclick="wpl_remove_activity(<?php echo $activity->id; ?>);"></span>
                            <span class="action-btn icon-move" id="extension_move_1"></span>
                        </td>
                    </tr>
				<?php
				}
                ?>
            </tbody>
        </table>
    </div>
    <div id="wpl_activity_manager_edit_div" class="fanc-box-wp wpl_lightbox wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>