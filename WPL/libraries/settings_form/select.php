<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'select' and !$done_this)
{
	$show_empty = isset($options['show_empty']) ? $options['show_empty'] : NULL;
	$show_shortcode = isset($options['show_shortcode']) ? $options['show_shortcode'] : NULL;
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
    <div class="select-wp">
        <label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <select name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" onchange="<?php if ($show_shortcode): ?>wpl_setting_show_shortcode('<?php echo $setting_record->id; ?>', '<?php echo $options['shortcode_key']; ?>', this.value);<?php endif; ?> wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" autocomplete="off">
            <?php if ($show_empty): ?><option value="">---</option><?php endif; ?>
            <?php foreach ($options['values'] as $value_array): ?>
            <option value="<?php echo $value_array['key']; ?>" <?php if ($value_array['key'] == $value) echo 'selected="selected"' ?>><?php echo $value_array['value']; ?></option>
            <?php endforeach; ?>
        </select>

        <?php if ($show_shortcode): ?>
        <div class="shortcode-wp" id="wpl_setting_form_shortcode_container<?php echo $setting_record->id; ?>">
            <span title="<?php echo __('Shortcode', WPL_TEXTDOMAIN); ?>" id="wpl_st_<?php echo $setting_record->id; ?>_shortcode_value"><?php echo $options['shortcode_key'] . '="' . $value . '"'; ?></span>
        </div>
        <?php endif; ?>

        <?php if ($params['tooltip']): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], WPL_TEXTDOMAIN); ?></span>
        </span>
        <?php endif; ?>

        <span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
    </div>
</div>
<?php
	$done_this = true;
}