<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-links-report-wp" id="wpl_form_report_abuse_container">
    <form class="wpl-gen-form-wp" id="wpl_report_abuse_form" onsubmit="wpl_report_abuse_submit(); return false;" novalidate="novalidate">
        <div class="wpl-gen-form-row">
            <label for="wpl-links-report-name"><?php echo __('Name', WPL_TEXTDOMAIN); ?></label>
            <input type="text" name="wplfdata[name]" id="wpl-links-report-name" placeholder="<?php echo __('Name', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-report-email"><?php echo __('Email', WPL_TEXTDOMAIN); ?></label>
            <input type="email" name="wplfdata[email]" placeholder="<?php echo __('Email', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-report-tel"><?php echo __('Tel', WPL_TEXTDOMAIN); ?></label>
            <input type="tel" name="wplfdata[tel]" placeholder="<?php echo __('Tel', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-report-message"><?php echo __('Message', WPL_TEXTDOMAIN); ?></label>
            <textarea name="wplfdata[message]" placeholder="<?php echo __('Message', WPL_TEXTDOMAIN); ?>"></textarea>
        </div>
        <div class="wpl-gen-form-row wpl-util-right">
            <input class="wpl-gen-btn-1" type="submit" value="<?php echo __('Send', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div class="wpl_show_message"></div>

        <input type="hidden" name="wplfdata[property_id]" value="<?php echo $this->property_id; ?>" />
    </form>
</div>