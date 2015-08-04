<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

?>
<div class="fanc-row">
    <label for="wpl_o_facebook"><?php echo __('Facebook', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->facebook) and $this->options->facebook == '1') echo 'checked="checked"'; ?> class="text_box" name="option[facebook]" type="checkbox" id="wpl_o_facebook" value="<?php echo isset($this->options->facebook) ? $this->options->facebook : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_google_plus"><?php echo __('Google plus', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->google_plus) and $this->options->google_plus == '1') echo 'checked="checked"'; ?> class="text_box" name="option[google_plus]" type="checkbox" id="wpl_o_google_plus" value="<?php echo isset($this->options->google_plus) ? $this->options->google_plus : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_twitter"><?php echo __('Twitter', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->twitter) and $this->options->twitter == '1') echo 'checked="checked"'; ?> class="text_box" name="option[twitter]" type="checkbox" id="wpl_o_twitter" value="<?php echo isset($this->options->twitter) ? $this->options->twitter : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_pinterest"><?php echo __('Pinterest', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->pinterest) and $this->options->pinterest == '1') echo 'checked="checked"'; ?> class="text_box" name="option[pinterest]" type="checkbox" id="wpl_o_pinterest" value="<?php echo isset($this->options->pinterest) ? $this->options->pinterest : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_linkedin"><?php echo __('Linkedin', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->linkedin) and $this->options->linkedin == '1') echo 'checked="checked"'; ?> class="text_box" name="option[linkedin]" type="checkbox" id="wpl_o_linkedin" value="<?php echo isset($this->options->linkedin) ? $this->options->linkedin : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_favorite"><?php echo __('Favorite', WPL_TEXTDOMAIN); ?></label>
    
    <?php if(!wpl_global::check_addon('pro')): ?>
	<span id="wpl_o_favorite" class="gray_tip"><?php echo __('Pro addon must be installed for this!', WPL_TEXTDOMAIN); ?></span>
	<?php else: ?>
    <input <?php if(isset($this->options->favorite) and $this->options->favorite == '1') echo 'checked="checked"'; ?> class="text_box" name="option[favorite]" type="checkbox" id="wpl_o_favorite" value="<?php echo isset($this->options->favorite) ? $this->options->favorite : '1'; ?>" />
    <?php endif; ?>
    
</div>
<div class="fanc-row">
    <label for="wpl_o_pdf"><?php echo __('PDF', WPL_TEXTDOMAIN); ?></label>
    
    <?php if(!wpl_global::check_addon('pro')): ?>
	<span id="wpl_o_pdf" class="gray_tip"><?php echo __('Pro addon must be installed for this!', WPL_TEXTDOMAIN); ?></span>
	<?php else: ?>
    <input <?php if(isset($this->options->pdf) and $this->options->pdf == '1') echo 'checked="checked"'; ?> class="text_box" name="option[pdf]" type="checkbox" id="wpl_o_pdf" value="<?php echo isset($this->options->pdf) ? $this->options->pdf : '1'; ?>" />
    <?php endif; ?>
    
</div>
<div class="fanc-row">
    <label for="wpl_o_report_abuse"><?php echo __('Report Abuse', WPL_TEXTDOMAIN); ?></label>
    
    <?php if(!wpl_global::check_addon('pro')): ?>
	<span id="wpl_o_report_abuse" class="gray_tip"><?php echo __('Pro addon must be installed for this!', WPL_TEXTDOMAIN); ?></span>
	<?php else: ?>
    <input <?php if(isset($this->options->report_abuse) and $this->options->report_abuse == '1') echo 'checked="checked"'; ?> class="text_box" name="option[report_abuse]" type="checkbox" id="wpl_o_report_abuse" value="<?php echo isset($this->options->report_abuse) ? $this->options->report_abuse : '1'; ?>" />
    <?php endif; ?>
    
</div>
<div class="fanc-row">
    <label for="wpl_o_send_to_friend"><?php echo __('Send to Friend', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->send_to_friend) and $this->options->send_to_friend == '1') echo 'checked="checked"'; ?> class="text_box" name="option[send_to_friend]" type="checkbox" id="wpl_o_send_to_friend" value="<?php echo isset($this->options->send_to_friend) ? $this->options->send_to_friend : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_request_a_visit"><?php echo __('Request a Visit', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->request_a_visit) and $this->options->request_a_visit == '1') echo 'checked="checked"'; ?> class="text_box" name="option[request_a_visit]" type="checkbox" id="wpl_o_request_a_visit" value="<?php echo isset($this->options->request_a_visit) ? $this->options->request_a_visit : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_crm"><?php echo __('CRM', WPL_TEXTDOMAIN); ?></label>
    
    <?php if(!wpl_global::check_addon('crm')): ?>
    <span id="wpl_o_crm" class="gray_tip"><?php echo __('CRM addon must be installed for this!', WPL_TEXTDOMAIN); ?></span>
    <?php else: ?>
    <input <?php if(isset($this->options->crm) and $this->options->crm == '1') echo 'checked="checked'; ?> class="text_box" name="option[crm]" type="checkbox" id="wpl_o_crm" value="<?php echo isset($this->options->crm) ? $this->options->crm : '1'; ?>" />
    <?php endif; ?>
    
</div>