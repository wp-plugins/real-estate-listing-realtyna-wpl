<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'checkbox' and !$done_this)
{
?>
<div class="prow wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
    <div class="checkbox-wp" >
        <label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <input type="checkbox" name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" autocomplete="off" <?php if ($value) echo 'checked="checked"'; ?> onchange="wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" />
        
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