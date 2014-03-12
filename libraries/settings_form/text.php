<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'text' and !$done_this)
{
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
	<div class="text-wp">
		<label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
		<input type="text" name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" value="<?php echo $setting_record->setting_value; ?>" onblur="<?php if ($options['show_shortcode']): ?>wpl_setting_show_shortcode('<?php echo $setting_record->id; ?>', '<?php echo $options['shortcode_key']; ?>', this.value);<?php endif; ?> wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" autocomplete="off" />

		<?php if ($options['show_shortcode']): ?>
        <div class="shortcode-wp" id="wpl_setting_form_shortcode_container<?php echo $setting_record->id; ?>">
            <span title="<?php echo __('Shortcode'); ?>" id="wpl_st_<?php echo $setting_record->id; ?>_shortcode_value"><?php echo $options['shortcode_key'] . '="' . $value . '"'; ?></span>
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