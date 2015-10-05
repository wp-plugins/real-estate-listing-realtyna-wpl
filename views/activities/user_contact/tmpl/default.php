<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_profiles = isset($params['wpl_profiles']) ? $params['wpl_profiles'] : array();

$this->user_id = isset($params['user_id']) ? $params['user_id'] : NULL;
$this->user_id = isset($wpl_profiles['current']['data']['id']) ? $wpl_profiles['current']['data']['id'] : $this->user_id;

$this->top_comment = isset($params['top_comment']) ? $params['top_comment'] : '';

include _wpl_import($this->tpl_path.'.scripts.js', true, true);
?>
<div class="wpl_contact_container wpl_user_contact_container" id="wpl_user_contact_container<?php echo $this->activity_id; ?><?php echo $this->user_id; ?>">
    <?php if(trim($this->top_comment) != ''): ?>
    <p class="wpl_contact_comment"><?php echo $this->top_comment; ?></p>
    <?php endif; ?>
	<form method="post" action="#" id="wpl_user_contact_form<?php echo $this->activity_id; ?><?php echo $this->user_id; ?>" onsubmit="return wpl_send_user_contact<?php echo $this->activity_id; ?>(<?php echo $this->user_id; ?>);">
        <div class="form-field text-field">
            <input class="text-box" type="text" id="wpl_user_contact_fullname<?php echo $this->activity_id; ?><?php echo $this->user_id; ?>" name="fullname" placeholder="<?php echo __('Full Name', WPL_TEXTDOMAIN); ?>" />
        </div>

        <div class="form-field text-field">
            <input class="text-box" type="text" id="wpl_user_contact_phone<?php echo $this->activity_id; ?><?php echo $this->user_id; ?>" name="phone" placeholder="<?php echo __('Phone', WPL_TEXTDOMAIN); ?>" />
        </div>

        <div class="form-field text-field">
            <input class="text-box" type="text" id="wpl_user_contact_email<?php echo $this->activity_id; ?><?php echo $this->user_id; ?>" name="email" placeholder="<?php echo __('Email', WPL_TEXTDOMAIN); ?>" />
        </div>

        <div class="form-field text-area">
            <textarea class="text-box" id="wpl_user_contact_message<?php echo $this->activity_id; ?><?php echo $this->user_id; ?>" name="message" placeholder="<?php echo __('Message', WPL_TEXTDOMAIN); ?>"></textarea>
        </div>
        
        <div class="form-field">
        <?php
            /**
            * Fires for integrating contact forms with third party plugins such as captcha plugins
            */
            do_action('comment_form_after_fields');
        ?>
        </div>
        
        <div class="form-field button">
            <input class="btn btn-primary" type="submit" value="<?php echo __('Send', WPL_TEXTDOMAIN); ?>" />
        </div>
    </form>
    <div id="wpl_user_contact_ajax_loader<?php echo $this->activity_id; ?>_<?php echo $this->user_id; ?>"></div>
    <div id="wpl_user_contact_message<?php echo $this->activity_id; ?>_<?php echo $this->user_id; ?>"></div>
</div>