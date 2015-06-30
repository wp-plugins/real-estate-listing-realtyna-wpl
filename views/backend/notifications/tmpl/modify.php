<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.modify_js');
$this->_wpl_import($this->tpl_path . '.scripts.css');
?>
<form action="#" id="wpl_notification_form">
    <div class="wrap wpl-wp pwizard-wp">
        <header>
            <div id="icon-pwizard" class="icon48"></div>
            <h2><?php echo __('Edit Notification', WPL_TEXTDOMAIN); ?> (<?php echo $this->notification->subject; ?>) <span class="ajax-inline-save" id="wpl_modify_ajax_loader"></span></h2>
        </header>
        <div class="wpl_notification_modify"><div class="wpl_show_message"></div></div>
        <div class="sidebar-wp">
            <div class="side-2 side-tabs-wp">
                <ul>
                    <li class="finilized">
                        <a id="wpl_notification_submit_button" class="tab-finalize wpl_slide_label" href="#" onclick="wplj('#wpl_notification_form').submit();">
                            <span><?php echo __('Save', WPL_TEXTDOMAIN); ?></span>
                            <i class="icon-finalize"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#basic" class="wpl-no-icon wpl_slide_label" id="wpl_slide_label_idbasic" onclick="rta.internal.slides.open('basic', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
                            <?php echo __('Basic', WPL_TEXTDOMAIN); ?>
                        </a>
                    </li>
                    <li>
                        <a href="#advanced" class="wpl-no-icon wpl_slide_label" id="wpl_slide_label_idadvanced" onclick="rta.internal.slides.open('advanced', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
                            <?php echo __('Advanced', WPL_TEXTDOMAIN); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="side-12 side-content-wp">
                <div class="pwizard-panel wpl_slide_container wpl_slide_containerbasic" id="wpl_slide_container_idbasic">
                    <?php $this->generate_basic_options(); ?>
                </div>
                <div class="pwizard-panel wpl_slide_container wpl_slide_containeradvanced" id="wpl_slide_container_idadvanced">
                    <?php $this->generate_advanced_options(); ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="info[id]" value="<?php echo $this->notification->id; ?>" />
    </div>
</form>