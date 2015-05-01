<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_form_report_abuse_container">
    <form id="wpl_report_abuse_form" onsubmit="wpl_report_abuse_submit(); return false;" novalidate="novalidate">
        <div>
            <input type="text" name="wplfdata[name]" placeholder="<?php echo __('Name', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div>
            <input type="email" name="wplfdata[email]" placeholder="<?php echo __('Email', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div>
            <input type="tel" name="wplfdata[tel]" placeholder="<?php echo __('Tel', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div>
            <textarea name="wplfdata[message]" placeholder="<?php echo __('Message', WPL_TEXTDOMAIN); ?>"></textarea>
        </div>
        <div>
            <input type="hidden" name="wplfdata[property_id]" value="<?php echo $this->property_id; ?>" />
            <input type="submit" value="<?php echo __('Send', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div class="wpl_show_message"></div>
    </form>
</div>