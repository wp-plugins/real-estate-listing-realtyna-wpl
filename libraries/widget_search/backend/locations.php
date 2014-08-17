<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'locations' and !$done_this)
{
?>
<div class="search-field-wp search-field-locations <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>" data-field-order="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>">
    
	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	<input type="hidden" id="field_id_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][id]" value="<?php echo $value['id']; ?>" />
	
    <h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>
    
	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this, 'locations');">
				<option value="simple" <?php if (isset($value['type']) and $value['type'] == 'simple') echo 'selected="selected"' ?>><?php echo __('simple', WPL_TEXTDOMAIN); ?></option>
                <option value="locationtextsearch" <?php if (isset($value['type']) and $value['type'] == 'locationtextsearch') echo 'selected="selected"' ?>><?php echo __('Location textsearch', WPL_TEXTDOMAIN); ?></option>
                <?php if(wpl_global::check_addon('pro')): ?>
				<option value="radiussearch" <?php if (isset($value['type']) and $value['type'] == 'radiussearch') echo 'selected="selected"' ?>><?php echo __('Radius Search', WPL_TEXTDOMAIN); ?></option>
                <?php endif; ?>
			</select>
		</div>
        <div class="erow wpl_extoptions_span <?php echo (isset($value['type']) ? $value['type'] : ''); ?>">
			<input type="text" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption]" value="<?php echo isset($value['extoption']) ? $value['extoption'] : ''; ?>" placeholder="<?php echo __('Location place-holder', WPL_TEXTDOMAIN); ?>" title="<?php echo __('Location place-holder', WPL_TEXTDOMAIN); ?>" />
		</div>
	</div>
</div>
<?php
    $done_this = true;
}