<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$this->property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

$this->top_comment = isset($params['top_comment']) ? $params['top_comment'] : '';

include_once _wpl_import($this->tpl_path.'.scripts.js', true, true);
?>
<div class="wpl_contact_container" id="wpl_contact_container<?php echo $this->property_id; ?>">
    <?php if(trim($this->top_comment) != ''): ?>
    <p class="wpl_contact_comment"><?php echo $this->top_comment; ?></p>
    <?php endif; ?>
	<form method="post" action="#" id="wpl_contact_form<?php echo $this->property_id; ?>" onsubmit="return wpl_send_contact(<?php echo $this->property_id; ?>);">
        <div class="form-field">
            <input class="text-box" type="text" id="wpl_contact_fullname<?php echo $this->property_id; ?>" name="fullname" placeholder="<?php echo __('Full Name', WPL_TEXTDOMAIN); ?>" />
        </div>

        <div class="form-field">
            <input class="text-box" type="text" id="wpl_contact_phone<?php echo $this->property_id; ?>" name="phone" placeholder="<?php echo __('Phone', WPL_TEXTDOMAIN); ?>" />
        </div>

        <div class="form-field">
            <input class="text-box" type="text" id="wpl_contact_email<?php echo $this->property_id; ?>" name="email" placeholder="<?php echo __('Email', WPL_TEXTDOMAIN); ?>" />
        </div>

        <div class="form-field">
            <textarea class="text-box" id="wpl_contact_message<?php echo $this->property_id; ?>" name="message" placeholder="<?php echo __('Message', WPL_TEXTDOMAIN); ?>"></textarea>
        </div>

        <div class="form-field">
            <input class="btn btn-primary" type="submit" value="<?php echo __('Send', WPL_TEXTDOMAIN); ?>" />
        </div>
    </form>
    <div id="wpl_contact_ajax_loader_<?php echo $this->property_id; ?>"></div>
    <div id="wpl_contact_message_<?php echo $this->property_id; ?>"></div>
</div>