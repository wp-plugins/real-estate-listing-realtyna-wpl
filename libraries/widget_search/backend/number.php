<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'number' and !$done_this)
{
?>
<div class="search-field-wp search-field-number <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>" data-field-order="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	<input type="hidden" id="field_id_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][id]" value="<?php echo $value['id']; ?>" />

	<h4>
		<span>
			<?php echo __($field->name, WPL_TEXTDOMAIN); ?>
		</span>
	</h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this,'number');">
				<option value="text" <?php if($value['type'] == 'text') echo 'selected="selected"'; ?>><?php echo __('Text', WPL_TEXTDOMAIN); ?></option>
				<option value="exacttext" <?php if($value['type'] == 'exacttext') echo 'selected="selected"'; ?>><?php echo __('Exact Text', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax" <?php if($value['type'] == 'minmax') echo 'selected="selected"'; ?>><?php echo __('Min/Max textbox', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax_slider" <?php if($value['type'] == 'minmax_slider') echo 'selected="selected"'; ?>><?php echo __('Min/Max Slider', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax_selectbox" <?php if($value['type'] == 'minmax_selectbox') echo 'selected="selected"'; ?>><?php echo __('Min/Max SelectBox', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax_selectbox_plus" <?php if($value['type'] == 'minmax_selectbox_plus') echo 'selected="selected"'; ?>><?php echo __('Min/Max SelectBox+', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
		<div class="erow wpl_extoptions_span <?php echo $value['type']; ?>">
			<input type="text" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption]" value="<?php echo isset($value['extoption']) ? $value['extoption'] : ''; ?>" placeholder="<?php echo __('min,max,increment like 0,10,1', WPL_TEXTDOMAIN); ?>" title="<?php echo __('min,max,increment like 0,10,1', WPL_TEXTDOMAIN); ?>" />
		</div>
	</div>
</div>
<?php
    $done_this = true;
}