<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'textsearch' and !$done_this)
{
?>
<div class="search-field-wp search-field-textsearch <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>" data-field-order="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>">

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
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]">
				<option value="textbox" <?php if (isset($value['type']) and $value['type'] == 'textbox') echo 'selected="selected"'; ?>><?php echo __('Textbox', WPL_TEXTDOMAIN); ?></option>
				<option value="textarea" <?php if (isset($value['type']) and $value['type'] == 'textarea') echo 'selected="selected"'; ?>><?php echo __('Textarea', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}