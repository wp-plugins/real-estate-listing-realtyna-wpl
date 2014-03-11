<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if(!isset($this->options->outer_margin)) $this->options->outer_margin = 2;
if(!isset($this->options->size)) $this->options->size = 4;
?>
<div class="fanc-row">
    <label for="wpl_o_picture_width"><?php echo __('Picture width', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[picture_width]" type="text" id="wpl_o_picture_width" value="<?php echo isset($this->options->picture_width) ? $this->options->picture_width : '90'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_picture_height"><?php echo __('Picture height', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[picture_height]" type="text" id="wpl_o_picture_height" value="<?php echo isset($this->options->picture_height) ? $this->options->picture_height: '90'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_outer_margin"><?php echo __('Outer margin', WPL_TEXTDOMAIN); ?></label>
    <select class="text_box" name="option[outer_margin]" type="text" id="wpl_o_outer_margin">
    	<?php for($i=1; $i<=4; $i++): ?>
	    <option value="<?php echo $i; ?>" <?php if(isset($this->options->outer_margin) and $this->options->outer_margin == $i) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
        <?php endfor; ?>
    </select>
</div>
<div class="fanc-row">
    <label for="wpl_o_size"><?php echo __('Size', WPL_TEXTDOMAIN); ?></label>
    <select class="text_box" name="option[size]" type="text" id="wpl_o_size">
    	<?php for($i=1; $i<=10; $i++): ?>
	    <option value="<?php echo $i; ?>" <?php if(isset($this->options->size) and $this->options->size == $i) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
        <?php endfor; ?>
    </select>
</div>