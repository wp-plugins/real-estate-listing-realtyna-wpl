<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'boolean' and !$done_this)
{
    $true_label = isset($options['true_label']) ? $options['true_label'] : 'Yes';
    $false_label = isset($options['false_label']) ? $options['false_label'] : 'No';
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<select class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');">
    <option value="1" <?php if(1 == $value) echo 'selected="selected"'; ?>><?php echo __($true_label, WPL_TEXTDOMAIN); ?></option>
    <option value="0" <?php if(0 == $value) echo 'selected="selected"'; ?>><?php echo __($false_label, WPL_TEXTDOMAIN); ?></option>
</select>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}
elseif($type == 'date' and !$done_this)
{
    _wpl_import('libraries.render');
	wp_enqueue_script('jquery-ui-datepicker');

    $date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
    $jqdate_format = $date_format_arr[1];

    if($options['minimum_date'] == 'minimum_date') $options['minimum_date'] = date("Y-m-d");
    if($options['maximum_date'] == 'now') $options['maximum_date'] = date("Y-m-d");

    $mindate = explode('-', $options['minimum_date']);
    $maxdate = explode('-', $options['maximum_date']);

    $mindate[0] = (array_key_exists(0, $mindate) and $mindate[0]) ? $mindate[0] : '1970';
    $mindate[1] = array_key_exists(1, $mindate) ? intval($mindate[1]) : '01';
    $mindate[2] = array_key_exists(2, $mindate) ? intval($mindate[2]) : '01';

    $maxdate[0] = (array_key_exists(0, $maxdate) and $maxdate[0]) ? $maxdate[0] : date('Y');
    $maxdate[1] = array_key_exists(1, $maxdate) ? intval($maxdate[1]) : date('m');
    $maxdate[2] = array_key_exists(2, $maxdate) ? intval($maxdate[2]) : date('d');
?>
<div class="date-wp">
    <label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if (in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
    <input type="text" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" value="<?php echo wpl_render::render_date($value); ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
    <span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="ajax-inline-save"></span>
</div>
<?php
    echo '<script type="text/javascript">
		wplj(document).ready( function ()
		{
			wplj("#wpl_c_' . $field->id . '").datepicker(
			{ 
				dayNamesMin: ["' . __('SU', WPL_TEXTDOMAIN) . '", "' . __('MO', WPL_TEXTDOMAIN) . '", "' . __('TU', WPL_TEXTDOMAIN) . '", "' . __('WE', WPL_TEXTDOMAIN) . '", "' . __('TH', WPL_TEXTDOMAIN) . '", "' . __('FR', WPL_TEXTDOMAIN) . '", "' . __('SA', WPL_TEXTDOMAIN) . '"],
				dayNames: 	 ["' . __('Sunday', WPL_TEXTDOMAIN) . '", "' . __('Monday', WPL_TEXTDOMAIN) . '", "' . __('Tuesday', WPL_TEXTDOMAIN) . '", "' . __('Wednesday', WPL_TEXTDOMAIN) . '", "' . __('Thursday', WPL_TEXTDOMAIN) . '", "' . __('Friday', WPL_TEXTDOMAIN) . '", "' . __('Saturday', WPL_TEXTDOMAIN) . '"],
				monthNames:  ["' . __('January', WPL_TEXTDOMAIN) . '", "' . __('February', WPL_TEXTDOMAIN) . '", "' . __('March', WPL_TEXTDOMAIN) . '", "' . __('April', WPL_TEXTDOMAIN) . '", "' . __('May', WPL_TEXTDOMAIN) . '", "' . __('June', WPL_TEXTDOMAIN) . '", "' . __('July', WPL_TEXTDOMAIN) . '", "' . __('August', WPL_TEXTDOMAIN) . '", "' . __('September', WPL_TEXTDOMAIN) . '", "' . __('October', WPL_TEXTDOMAIN) . '", "' . __('November', WPL_TEXTDOMAIN) . '", "' . __('December', WPL_TEXTDOMAIN) . '"],
				dateFormat: "' . $jqdate_format . '",
				gotoCurrent: true,
				minDate: new Date(' . $mindate[0] . ', ' . $mindate[1] . '-1, ' . $mindate[2] . '),
				maxDate: new Date(' . $maxdate[0] . ', ' . $maxdate[1] . '-1, ' . $maxdate[2] . '),
				changeYear: true,
				yearRange: "' . $mindate[0] . ':' . $maxdate[0] . '",
				showOn: "both",
				buttonImage: "' . wpl_global::get_wpl_asset_url('img/system/calendar3.png') . '",
				buttonImageOnly: false,
				buttonImageOnly: true,
				firstDay: 1,
				onSelect: function(dateText, inst) 
				{
					ajax_save("' . $field->table_name . '","' . $field->table_column . '",dateText,' . $item_id . ',' . $field->id . ');
				}
			});
		});
	</script>';

    $done_this = true;
}
elseif(($type == 'checkbox' or $type == 'tag') and !$done_this)
{
?>
<div class="checkbox-wp">
    <input type="checkbox" class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" value="1" <?php if($value) echo 'checked="checked"'; ?> onchange="if(this.checked) value = 1; else value = 0; ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
	<label class="checkbox-label" for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
	<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="ajax-inline-save"></span>
</div>
<?php
    $done_this = true;
}
elseif($type == 'feature' and !$done_this)
{
    $checked = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? 'checked="checked"' : '';
    $style = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? '' : 'display:none;';
?>
<div class="checkbox-wp">
	<input type="checkbox" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" <?php echo $checked; ?> onchange="wplj('#wpl_span_feature_<?php echo $field->id; ?>').slideToggle(400); ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" />
	<label class="checkbox-label" for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if (in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>    
<?php
	if($options['type'] != 'none')
	{
		// setting the current value
		$value = trim($values[$field->table_column.'_options'], ', ');
		
		if($options['type'] == 'single')
		{
			echo '<div class="options-wp" id="wpl_span_feature_' . $field->id . '" style="' . $style . '">';
			echo '<select id="wpl_cf_' . $field->id . '" name="'.$field->table_column.'_options" onchange="ajax_save(\'' . $field->table_name . '\', \''.$field->table_column.'_options\', \',\'+this.value+\',\', \'' . $item_id . '\', \'' . $field->id . '\', \'#wpl_cf_' . $field->id . '\');">';
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
			echo '<select multiple="multiple" id="wpl_cf_' . $field->id . '" name="'.$field->table_column.'_options" onchange="ajax_save(\'' . $field->table_name . '\', \''.$field->table_column.'_options\', \',\'+wplj(this).val()+\',\', \'' . $item_id . '\', \'' . $field->id . '\', \'#wpl_cf_' . $field->id . '\');">';
	
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
elseif($type == 'listings' and !$done_this)
{
	$listings = wpl_global::get_listings();
	$current_user = wpl_users::get_wpl_user();
	$lrestrict = $current_user->maccess_lrestrict;
	$rlistings = explode(',', $current_user->maccess_listings);
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<select class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" onchange="wpl_listing_changed(this.value); ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');">
    <option value="-1"><?php echo __('Select', WPL_TEXTDOMAIN); ?></option>
    <?php foreach($listings as $listing): if($lrestrict and !in_array($listing['id'], $rlistings)) continue; ?>
    <option value="<?php echo $listing['id']; ?>" <?php if($listing['id'] == $value) echo 'selected="selected"'; ?>><?php echo __($listing['name'], WPL_TEXTDOMAIN); ?></option>
    <?php endforeach; ?>
</select>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}
elseif($type == 'neighborhood' and !$done_this)
{
    $checked = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? 'checked="checked"' : '';
    $style = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? '' : 'display:none;';
?>
<div class="checkbox-wp">
	<input type="checkbox" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" <?php echo $checked; ?> onchange="wpl_neighborhood_select('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" />
	<label class="checkbox-label" for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if (in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
	<div class="distance-wp distance_items_box" id="wpl_span_dis_<?php echo $field->id; ?>" style="<?php echo $style; ?>">
		<div class="distance-item distance-value">
			<input type="text" id="wpl_c_<?php echo $field->id; ?>_distance" name="<?php echo $field->table_column; ?>_distance" class="wpl_distance_text" value="<?php echo $values[$field->table_column.'_distance']; ?>" size='3' maxlength="4" onBlur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column.'_distance'; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>', '#n_<?php echo $field->id; ?>_distance');"  />
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
elseif($type == 'number' and !$done_this)
{
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input type="number" class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" value="<?php echo $value; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}
elseif($type == 'mmnumber' and !$done_this)
{
    $value_max = isset($values[$field->table_column.'_max']) ? $values[$field->table_column.'_max'] : 0;
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input type="number" class="wpl_minmax_textbox wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" value="<?php echo $value; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
 - <input type="number" class="wpl_minmax_textbox wpl_c_<?php echo $field->table_column; ?>_max" id="wpl_c_<?php echo $field->id; ?>_max" name="<?php echo $field->table_column; ?>_max" value="<?php echo $value_max; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>_max', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}
elseif($type == 'property_types' and !$done_this)
{
	$property_types = wpl_global::get_property_types();
	$current_user = wpl_users::get_wpl_user();
	$ptrestrict = $current_user->maccess_ptrestrict;
	$rproperty_types = explode(',', $current_user->maccess_property_types);
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<select class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" onchange="wpl_property_type_changed(this.value); ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');">
    <option value="-1"><?php echo __('Select', WPL_TEXTDOMAIN); ?></option>
    <?php foreach($property_types as $property_type): if($ptrestrict and !in_array($property_type['id'], $rproperty_types)) continue; ?>
    <option value="<?php echo $property_type['id']; ?>" <?php if($property_type['id'] == $value) echo 'selected="selected"'; ?>><?php echo __($property_type['name'], WPL_TEXTDOMAIN); ?></option>
    <?php endforeach; ?>
</select>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}
elseif($type == 'select' and !$done_this)
{
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<select class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');">
    <option value="-1"><?php echo __('Select', WPL_TEXTDOMAIN); ?></option>
    <?php foreach($options['params'] as $key=>$select): if(!$select['enabled']) continue; ?>
    <option value="<?php echo $select['key']; ?>" <?php if($select['key'] == $value) echo 'selected="selected"'; ?>><?php echo __($select['value'], WPL_TEXTDOMAIN); ?></option>
    <?php endforeach; ?>
</select>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}
elseif($type == 'separator' and !$done_this)
{
?>
    <div class="seperator-wp" id="wpl_listing_separator<?php echo $field->id; ?>">
    	<?php echo (isset($options['show_label']) and $options['show_label'] == "1") ? __($label, WPL_TEXTDOMAIN) : ''; ?>
    </div>
<?php
	$done_this = true;
}
elseif(in_array($type, array('price', 'volume', 'area', 'length')) and !$done_this)
{
	if($type == 'price') $units = wpl_units::get_units(4);
	if($type == 'volume') $units = wpl_units::get_units(3);
	if($type == 'area') $units = wpl_units::get_units(2);
	if($type == 'length') $units = wpl_units::get_units(1);
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input onkeyup="wpl_thousand_sep('wpl_c_<?php echo $field->id; ?>')" type="text" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" value="<?php echo number_format($value, 2); ?>" onblur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', wpl_de_thousand_sep(this.value), '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly']) == 1 ? 'disabled="disabled"' : ''); ?> />
<?php
    if(count($units) <= 1) echo $units[0]['name'];
    else
    {
        echo '<select onchange="ajax_save(\'' .$field->table_name. '\', \'' .$field->table_column. '_unit\', this.value, \''.$item_id.'\', \''.$field->id.'\');">';
        foreach($units as $unit) echo '<option value="'.$unit['id'].'" ' .( $values[$field->table_column.'_unit'] == $unit['id'] ? 'selected="selected"' : ''). '>' .$unit['name']. '</option>';
        echo '</select>';
    }
?>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}
elseif(in_array($type, array('mmprice', 'mmvolume', 'mmarea', 'mmlength')) and !$done_this)
{
	if($type == 'mmprice') $units = wpl_units::get_units(4);
	if($type == 'mmvolume') $units = wpl_units::get_units(3);
	if($type == 'mmarea') $units = wpl_units::get_units(2);
	if($type == 'mmlength') $units = wpl_units::get_units(1);
    
    $value_max = isset($values[$field->table_column.'_max']) ? $values[$field->table_column.'_max'] : 0;
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input onkeyup="wpl_thousand_sep('wpl_c_<?php echo $field->id; ?>')" type="text" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" value="<?php echo number_format($value, 2); ?>" onblur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', wpl_de_thousand_sep(this.value), '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly']) == 1 ? 'disabled="disabled"' : ''); ?> />
<input onkeyup="wpl_thousand_sep('wpl_c_<?php echo $field->id; ?>_max')" type="text" id="wpl_c_<?php echo $field->id; ?>_max" name="<?php echo $field->table_column; ?>_max" value="<?php echo number_format($value_max, 2); ?>" onblur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>_max', wpl_de_thousand_sep(this.value), '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly']) == 1 ? 'disabled="disabled"' : ''); ?> />
<?php
    if(count($units) <= 1) echo $units[0]['name'];
    else
    {
        echo '<select onchange="ajax_save(\'' .$field->table_name. '\', \'' .$field->table_column. '_unit\', this.value, \''.$item_id.'\', \''.$field->id.'\');">';
        foreach($units as $unit) echo '<option value="'.$unit['id'].'" ' .( $values[$field->table_column.'_unit'] == $unit['id'] ? 'selected="selected"' : ''). '>' .$unit['name']. '</option>';
        echo '</select>';
    }
?>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}
elseif($type == 'url' and !$done_this)
{
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input type="text" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" value="<?php echo $value; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}