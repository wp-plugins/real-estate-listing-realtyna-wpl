<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'number' and !$done_this)
{
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input type="text" class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" value="<?php echo $value; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ($options['readonly'] == 1 ? 'disabled="disabled"' : ''); ?> />
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}
elseif($type == 'mmnumber' and !$done_this)
{
    $value_max = isset($values[$field->table_column.'_max']) ? $values[$field->table_column.'_max'] : 0;
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input type="text" class="wpl_minmax_textbox wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" value="<?php echo $value; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ($options['readonly'] == 1 ? 'disabled="disabled"' : ''); ?> />
 - <input type="text" class="wpl_minmax_textbox wpl_c_<?php echo $field->table_column; ?>_max" id="wpl_c_<?php echo $field->id; ?>_max" value="<?php echo $value_max; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>_max', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ($options['readonly'] == 1 ? 'disabled="disabled"' : ''); ?> />
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}