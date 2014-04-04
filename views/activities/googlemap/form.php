<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_default_lt"><?php echo __('Default latitude', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[default_lt]" type="text" id="wpl_o_default_lt" value="<?php echo isset($this->options->default_lt) ? $this->options->default_lt : '38.685516'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_default_ln"><?php echo __('Default longitude', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[default_ln]" type="text" id="wpl_o_default_ln" value="<?php echo isset($this->options->default_ln) ? $this->options->default_ln: '-101.073324'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_default_zoom"><?php echo __('Default zoom level', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[default_zoom]" type="text" id="wpl_o_default_zoom" value="<?php echo isset($this->options->default_zoom) ? $this->options->default_zoom: '4'; ?>" />
</div>