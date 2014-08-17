<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_picture_width"><?php echo __('Picture width', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[picture_width]" type="text" id="wpl_o_picture_width" value="<?php echo isset($this->options->picture_width) ? $this->options->picture_width : '90'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_picture_height"><?php echo __('Picture height', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[picture_height]" type="text" id="wpl_o_picture_height" value="<?php echo isset($this->options->picture_height) ? $this->options->picture_height: '100'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_mailto"><?php echo __('Mailto:', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[mailto]" type="checkbox" id="wpl_o_mailto" <?php echo (isset($this->options->mailto) and $this->options->mailto) ? 'checked="checked"' : ''; ?> />
    <span class="gray_tip"><?php echo __('Sending emails directly', WPL_TEXTDOMAIN); ?></span>
</div>