<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_chart_background"><?php echo __('Chart Background', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[chart_background]" type="text" id="wpl_o_chart_background" value="<?php echo isset($this->options->chart_background) ? $this->options->chart_background : '#ffffff'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_chart_title"><?php echo __('Chart Title', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[chart_title]" type="text" id="wpl_o_chart_title" value="<?php echo isset($this->options->chart_title) ? $this->options->chart_title : 'Chart'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_chart_width"><?php echo __('Chart Width', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[chart_width]" type="text" id="wpl_o_chart_width" value="<?php echo isset($this->options->chart_width) ? $this->options->chart_width : '100%'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_chart_height"><?php echo __('Chart Height', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[chart_height]" type="text" id="wpl_o_chart_height" value="<?php echo isset($this->options->chart_height) ? $this->options->chart_height : '400px'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_data"><?php echo __('Chart Data', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[data]" type="text" id="wpl_o_data" value="<?php echo isset($this->options->data) ? stripslashes($this->options->data) : "[['Sony',7], ['Samsumg',13.3], ['LG',14.7], ['Vizio',5.2], ['Insignia', 1.2]]"; ?>" title="<?php echo __('Insert rendered string (For developers)', WPL_TEXTDOMAIN); ?>" />
</div>