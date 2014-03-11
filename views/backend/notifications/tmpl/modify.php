<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.modify_js');
?>
<form action="#" id="wpl_notification_form">
    <div class="wrap wpl-wp pwizard-wp">
        <header>
            <div id="icon-pwizard" class="icon48">
            </div>
            <h2><?php echo __('Edit Notification', WPL_TEXTDOMAIN); ?> => <?php echo $this->notification->subject ?></h2>
        </header>
        <div class="wpl_notification_modify"><div class="wpl_show_message"></div></div>
        <div class="sidebar-wp">
            <div class="side-2 side-tabs-wp">
                <ul>
                    <li class="finilized">
                        <span class="ajax-inline-save" id="wpl_modify_ajax_loader"></span>
                        <input id="wpl_notification_submit_button" type="submit" class="wpl-button button-1 tab-finalize wpl_slide_label" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>"/>
                    </li>
                    <li>
                        <a href="#basic" class="wpl_slide_label wpl_slide_label_idbasic" id="wpl_slide_label_idbasic" onclick="rta.internal.slides.open('basic', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
                            <?php echo __('Basic', WPL_TEXTDOMAIN); ?>
                        </a>
                    </li>
                    <li>
                        <a href="#advanced" class="wpl_slide_label wpl_slide_label_idadvanced" id="wpl_slide_label_idadvanced" onclick="rta.internal.slides.open('advanced', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
                            <?php echo __('Advanced', WPL_TEXTDOMAIN); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="side-12 side-content-wp">
                <div class="pwizard-panel wpl_slide_container wpl_slide_containerbasic" id="wpl_slide_container_idbasic">
                    <?php $this->generate_basic_option(); ?>
                </div>
                <div class="pwizard-panel wpl_slide_container wpl_slide_containeradvanced" id="wpl_slide_container_idadvanced">
                    <?php $this->generate_advanced_option(); ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="info[id]" value="<?php echo $this->notification->id; ?>"/>
</form>