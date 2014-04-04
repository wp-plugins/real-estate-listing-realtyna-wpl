<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'sort_option' and !$done_this)
{
    $kind = trim($options['kind']) != '' ? $options['kind'] : 1;
    _wpl_import('libraries.sort_options');
    $sort_options = wpl_sort_options::get_sort_options($options['kind'], 1); /** getting enaled sort options **/
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
	<div class="select-wp">
		<label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?><span class="wpl_st_citation">:</span></label>
		<select name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" onchange="wpl_setting_show_shortcode('<?php echo $setting_record->id; ?>', '<?php echo $options['shortcode_key']; ?>', this.value);
				wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" autocomplete="off">
					<?php foreach ($sort_options as $value_array): ?>
				<option value="<?php echo $value_array['field_name']; ?>" <?php if ($value_array['field_name'] == $value) echo 'selected="selected"' ?>><?php echo $value_array['name']; ?></option>
			<?php endforeach; ?>
		</select>

		<?php if ($options['show_shortcode']): ?>
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