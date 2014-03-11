<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'checkbox' and !$done_this)
{
?>
<div class="checkbox-wp">
	<input type="checkbox" id="wpl_c_<?php echo $field->id; ?>" value="1" <?php if($value) echo 'checked="checked"'; ?> onchange="if(this.checked) value = 1; else value = 0; ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ($options['readonly'] == 1 ? 'disabled="disabled"' : ''); ?> />
	<label class="checkbox-label" for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
	<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="ajax-inline-save"></span>
</div>
<?php
    $done_this = true;
}
?>