<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'property_types' and !$done_this)
{
	$property_types = wpl_global::get_property_types();
	$current_user = wpl_users::get_wpl_user();
	$ptrestrict = $current_user->maccess_ptrestrict;
	$rproperty_types = explode(',', $current_user->maccess_property_types);
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<select id="wpl_c_<?php echo $field->id; ?>" onchange="wpl_property_type_changed(this.value); ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');">
    <option value="-1"><?php echo __('Select', WPL_TEXTDOMAIN); ?></option>
    <?php foreach($property_types as $property_type): if($ptrestrict and !in_array($property_type['id'], $rproperty_types)) continue; ?>
    <option value="<?php echo $property_type['id']; ?>" <?php if($property_type['id'] == $value) echo 'selected="selected"'; ?>><?php echo __($property_type['name'], WPL_TEXTDOMAIN); ?></option>
    <?php endforeach; ?>
</select>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}