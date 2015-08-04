<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_image_width"><?php echo __('Image width', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[image_width]" type="text" id="wpl_o_image_width" value="<?php echo isset($this->options->image_width) ? $this->options->image_width : '285'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_image_height"><?php echo __('Image height', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[image_height]" type="text" id="wpl_o_image_height" value="<?php echo isset($this->options->image_height) ? $this->options->image_height : '140'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_image_class"><?php echo __('Image class', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[image_class]" type="text" id="wpl_o_image_class" value="<?php echo isset($this->options->image_class) ? $this->options->image_class : ''; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_autoplay"><?php echo __('Autoplay', WPL_TEXTDOMAIN); ?></label>
    <select class="text_box" name="option[autoplay]" type="text" id="wpl_o_autoplay">
        <option value="1" <?php if(isset($this->options->autoplay) and $this->options->autoplay == '1') echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
        <option value="0" <?php if(isset($this->options->autoplay) and $this->options->autoplay == '0') echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<div class="fanc-row">
    <label for="wpl_o_resize"><?php echo __('Resize', WPL_TEXTDOMAIN); ?></label>
    <select class="text_box" name="option[resize]" type="text" id="wpl_o_resize">
        <option value="1" <?php if(isset($this->options->resize) and $this->options->resize == '1') echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
	    <option value="0" <?php if(isset($this->options->resize) and $this->options->resize == '0') echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<div class="fanc-row">
    <label for="wpl_o_rewrite"><?php echo __('Rewrite', WPL_TEXTDOMAIN); ?></label>
    <select class="text_box" name="option[rewrite]" type="text" id="wpl_o_rewrite">
	    <option value="0" <?php if(isset($this->options->rewrite) and $this->options->rewrite == '0') echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
        <option value="1" <?php if(isset($this->options->rewrite) and $this->options->rewrite == '1') echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<div class="fanc-row">
    <label for="wpl_o_watermark"><?php echo __('Watermark', WPL_TEXTDOMAIN); ?></label>
    <select class="text_box" name="option[watermark]" type="text" id="wpl_o_watermark">
	    <option value="0" <?php if(isset($this->options->watermark) and $this->options->watermark == '0') echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
        <option value="1" <?php if(isset($this->options->watermark) and $this->options->watermark == '1') echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>