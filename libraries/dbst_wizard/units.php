<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if(in_array($type, array('price', 'volume', 'area', 'length')) and !$done_this)
{
	_wpl_import('libraries.units');
	
	if($type == 'price') $units = wpl_units::get_units(4);
	if($type == 'volume') $units = wpl_units::get_units(3);
	if($type == 'area') $units = wpl_units::get_units(2);
	if($type == 'length') $units = wpl_units::get_units(1);
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input onkeyup="wpl_thousand_sep('wpl_c_<?php echo $field->id; ?>')" type="text" id="wpl_c_<?php echo $field->id; ?>" value="<?php echo number_format($value, 2); ?>" onblur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', wpl_de_thousand_sep(this.value), '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly']) == 1 ? 'disabled="disabled"' : ''); ?> />
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
	_wpl_import('libraries.units');
	
	if($type == 'mmprice') $units = wpl_units::get_units(4);
	if($type == 'mmvolume') $units = wpl_units::get_units(3);
	if($type == 'mmarea') $units = wpl_units::get_units(2);
	if($type == 'mmlength') $units = wpl_units::get_units(1);
    
    $value_max = isset($values[$field->table_column.'_max']) ? $values[$field->table_column.'_max'] : 0;
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input onkeyup="wpl_thousand_sep('wpl_c_<?php echo $field->id; ?>')" type="text" id="wpl_c_<?php echo $field->id; ?>" value="<?php echo number_format($value, 2); ?>" onblur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', wpl_de_thousand_sep(this.value), '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly']) == 1 ? 'disabled="disabled"' : ''); ?> />
<input onkeyup="wpl_thousand_sep('wpl_c_<?php echo $field->id; ?>_max')" type="text" id="wpl_c_<?php echo $field->id; ?>_max" value="<?php echo number_format($value_max, 2); ?>" onblur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>_max', wpl_de_thousand_sep(this.value), '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly']) == 1 ? 'disabled="disabled"' : ''); ?> />
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