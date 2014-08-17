<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'feature' and !$done_this)
{
    $checked = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? 'checked="checked"' : '';
    $style = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? '' : 'display:none;';
?>
<div class="checkbox-wp">
	<input type="checkbox" id="wpl_c_<?php echo $field->id; ?>" <?php echo $checked; ?> onchange="wplj('#wpl_span_feature_<?php echo $field->id; ?>').slideToggle(400); ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" />
	<label class="checkbox-label" for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if (in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>    
<?php
	if($options['type'] != 'none')
	{
		// setting the current value
		$value = $values[$field->table_column.'_options'];
		
		if($options['type'] == 'single')
		{
			echo '<div class="options-wp" id="wpl_span_feature_' . $field->id . '" style="' . $style . '">';
			echo '<select id="wpl_cf_' . $field->id . '" onchange="ajax_save(\'' . $field->table_name . '\', \''.$field->table_column.'_options\', this.value, \'' . $item_id . '\', \'' . $field->id . '\', \'#wpl_cf_' . $field->id . '\');">';
			echo '<option value="0">' . __('Select', WPL_TEXTDOMAIN) . '</option>';
	
			foreach($options['values'] as $select)
			{
				$selected = $value == $select['key'] ? 'selected="selected"' : '';
				echo '<option value="' . $select['key'] . '" ' . $selected . '>' . __($select['value'], WPL_TEXTDOMAIN) . '</option>';
			}
			
			echo '</select>';
			echo '</div>';
		}
		elseif($options['type'] == 'multiple')
		{
			$value_array = explode(',', $value);
		
			echo '<div class="options-wp" id="wpl_span_feature_' . $field->id . '" style="' . $style . '">';
			echo '<select multiple="multiple" id="wpl_cf_' . $field->id . '" onchange="ajax_save(\'' . $field->table_name . '\', \''.$field->table_column.'_options\', wplj(this).val(), \'' . $item_id . '\', \'' . $field->id . '\', \'#wpl_cf_' . $field->id . '\');">';
	
			foreach($options['values'] as $select)
			{
				$selected = in_array($select['key'], $value_array) ? 'selected="selected"' : '';
				echo '<option value="' . $select['key'] . '" ' . $selected . '>' . __($select['value'], WPL_TEXTDOMAIN) . '</option>';
			}
		
			echo '</select>';
			echo '</div>';
		}
	}
?>
</div>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
    $done_this = true;
}