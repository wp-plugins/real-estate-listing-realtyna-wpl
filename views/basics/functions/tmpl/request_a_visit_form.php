<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-links-req-visit-wp" id="wpl_form_request_a_visit_container">
    <form class="wpl-gen-form-wp" id="wpl_request_a_visit_form" onsubmit="wpl_request_a_visit_submit(); return false;" novalidate="novalidate">
        <div class="wpl-gen-form-row">
            <label for="wpl-links-req-visit-name"><?php echo __('Name', WPL_TEXTDOMAIN); ?></label>
            <input type="text" name="wplfdata[name]" id="wpl-links-req-visit-name" placeholder="<?php echo __('Name', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-req-visit-email"><?php echo __('Email', WPL_TEXTDOMAIN); ?></label>
            <input type="email" name="wplfdata[email]" id="wpl-links-req-visit-email" placeholder="<?php echo __('Email', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-req-visit-tel"><?php echo __('Tel', WPL_TEXTDOMAIN); ?></label>
            <input type="tel" name="wplfdata[tel]" id="wpl-links-req-visit-tel" placeholder="<?php echo __('Tel', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-req-visit-message"><?php echo __('Message', WPL_TEXTDOMAIN); ?></label>
            <textarea name="wplfdata[message]" id="wpl-links-req-visit-message" placeholder="<?php echo __('Message', WPL_TEXTDOMAIN); ?>"></textarea>
        </div>
        <div class="wpl-gen-form-row wpl-util-right">
            <input class="wpl-gen-btn-1" type="submit" value="<?php echo __('Send', WPL_TEXTDOMAIN); ?>" />
        </div>
        <div class="wpl_show_message"></div>

        <input type="hidden" name="wplfdata[property_id]" value="<?php echo $this->property_id; ?>" />
    </form>
</div>