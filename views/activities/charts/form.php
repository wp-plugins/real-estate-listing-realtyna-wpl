<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_category_field"><?php echo __('Category Field', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[category_field]" type="text" id="wpl_o_category_field" value="<?php echo isset($this->options->category_field) ? $this->options->category_field : 'year'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_data_date_format"><?php echo __('Data Date Format', WPL_TEXTDOMAIN); ?>(Line)</label>
    <input class="text_box" name="option[data_date_format]" type="text" id="wpl_o_data_date_format" value="<?php echo isset($this->options->data_date_format) ? $this->options->data_date_format : 'YYYY'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_value_field"><?php echo __('Value Field', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[value_field]" type="text" id="wpl_o_value_field" value="<?php echo isset($this->options->value_field) ? $this->options->value_field : 'value'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_min_period"><?php echo __('Min Period', WPL_TEXTDOMAIN); ?>(Line)</label>
    <input class="text_box" name="option[min_period]" type="text" id="wpl_o_min_period" value="<?php echo isset($this->options->min_period) ? $this->options->min_period : 'YYYY'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_ballon_text_size"><?php echo __('Ballon Text Size', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[ballon_text_size]" type="text" id="wpl_o_ballon_text_size" value="<?php echo isset($this->options->ballon_text_size) ? $this->options->ballon_text_size : '14px'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_ballon_date_type_format"><?php echo __('Ballon Data Type Format', WPL_TEXTDOMAIN); ?>(Line)</label>
    <input class="text_box" name="option[ballon_data_type_format]" type="text" id="wpl_o_ballon_date_type_format" value="<?php echo isset($this->options->ballon_data_type_format) ? $this->options->ballon_data_type_format : 'YYYY'; ?>" />
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
    <label for="wpl_o_label_rotation"><?php echo __('Label Rotation', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[label_rotation]" type="text" id="wpl_o_label_rotation" value="<?php echo isset($this->options->label_rotation) ? $this->options->label_rotation : '0'; ?>" />
</div>