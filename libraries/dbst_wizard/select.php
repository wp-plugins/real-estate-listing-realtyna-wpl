<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'select' and !$done_this)
{
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<select id="wpl_c_<?php echo $field->id; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');">
    <option value="-1"><?php echo __('Select', WPL_TEXTDOMAIN); ?></option>
    <?php foreach($options['params'] as $key=>$select): if(!$select['enabled']) continue; ?>
    <option value="<?php echo $select['key']; ?>" <?php if($select['key'] == $value) echo 'selected="selected"'; ?>><?php echo __($select['value'], WPL_TEXTDOMAIN); ?></option>
    <?php endforeach; ?>
</select>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}