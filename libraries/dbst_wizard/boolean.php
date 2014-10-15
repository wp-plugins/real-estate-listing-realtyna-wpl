<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'boolean' and !$done_this)
{
    $true_label = isset($options['true_label']) ? $options['true_label'] : 'Yes';
    $false_label = isset($options['false_label']) ? $options['false_label'] : 'No';
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<select class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');">
    <option value="1" <?php if(1 == $value) echo 'selected="selected"'; ?>><?php echo __($true_label, WPL_TEXTDOMAIN); ?></option>
    <option value="0" <?php if(0 == $value) echo 'selected="selected"'; ?>><?php echo __($false_label, WPL_TEXTDOMAIN); ?></option>
</select>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}