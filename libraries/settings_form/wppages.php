<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'wppages' and !$done_this)
{
	$show_empty = isset($options['show_empty']) ? $options['show_empty'] : NULL;
	$wp_pages = wpl_global::get_wp_pages();
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
    <div class="select-wp">
        <label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <select name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" onchange="wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" autocomplete="off">
            <?php if ($show_empty): ?><option value="">---</option><?php endif; ?>
            <?php foreach ($wp_pages as $wp_page): ?>
            <option value="<?php echo $wp_page->ID; ?>" <?php if($wp_page->ID == $value) echo 'selected="selected"'; ?>><?php echo $wp_page->post_title; ?></option>
            <?php endforeach; ?>
        </select>

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