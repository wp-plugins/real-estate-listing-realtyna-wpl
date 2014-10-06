<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'neighborhood' and !$done_this)
{
    $checked = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? 'checked="checked"' : '';
    $style = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? '' : 'display:none;';
?>
<div class="checkbox-wp">
	<input type="checkbox" id="wpl_c_<?php echo $field->id; ?>" <?php echo $checked; ?> onchange="wpl_neighborhood_select('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" />
	<label class="checkbox-label" for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if (in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
	<div class="distance-wp distance_items_box" id="wpl_span_dis_<?php echo $field->id; ?>" style="<?php echo $style; ?>">
		<div class="distance-item distance-value">
			<input type="text" id="wpl_c_<?php echo $field->id; ?>_distance" class="wpl_distance_text" value="<?php echo $values[$field->table_column.'_distance']; ?>" size='3' maxlength="4" onBlur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column.'_distance'; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>', '#n_<?php echo $field->id; ?>_distance');"  />
		</div>
		<div class="distance-item minute-by">
			<?php echo __('Minutes', WPL_TEXTDOMAIN) . ' ' . __('By', WPL_TEXTDOMAIN); ?>
		</div>
		<div class="distance-item with-walk">
			<div class="radio-wp">
				<input type="radio" id="wpl_c_<?php echo $field->id; ?>_distance0" name="n_<?php echo $field->id; ?>_distance_by" <?php if ($values[$field->table_column."_distance_by"] == '1') echo 'checked="checked"'; ?> value='1' onchange="wpl_neighborhood_distance_type_select('<?php echo $field->table_name; ?>', '<?php echo $field->table_column.'_distance_by'; ?>', 1, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>', 'wpl_c_<?php echo $field->id; ?>_distance0')" />
				<label for="wpl_c_<?php echo $field->id; ?>_distance0"><?php echo __('Walk', WPL_TEXTDOMAIN); ?></label>
			</div>
		</div>
		<div class="distance-item with-car">
			<div class="radio-wp">
				<input type="radio" id="wpl_c_<?php echo $field->id; ?>_distance1" name="n_<?php echo $field->id; ?>_distance_by" <?php if ($values[$field->table_column."_distance_by"] == '2') echo 'checked="checked"'; ?> value='2' onchange="wpl_neighborhood_distance_type_select('<?php echo $field->table_name; ?>', '<?php echo $field->table_column.'_distance_by'; ?>', 2, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>', 'wpl_c_<?php echo $field->id; ?>_distance1')" />
				<label for="wpl_c_<?php echo $field->id; ?>_distance1"><?php echo __('Car', WPL_TEXTDOMAIN); ?></label>
			</div>
		</div>
		<div class="distance-item with-train">
			<div class="radio-wp">
				<input type="radio" id="wpl_c_<?php echo $field->id; ?>_distance2" name="n_<?php echo $field->id; ?>_distance_by" <?php if ($values[$field->table_column."_distance_by"] == '3') echo 'checked="checked"'; ?> value='3' onchange="wpl_neighborhood_distance_type_select('<?php echo $field->table_name; ?>', '<?php echo $field->table_column.'_distance_by'; ?>', 3, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>', 'wpl_c_<?php echo $field->id; ?>_distance2')" />
				<label for="wpl_c_<?php echo $field->id; ?>_distance2"><?php echo __('Train', WPL_TEXTDOMAIN); ?></label>
			</div>
		</div>
	</div>
	<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="ajax-inline-save"></span>
</div>
<?php
    $done_this = true;
}