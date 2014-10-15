<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'textarea' and !$done_this)
{
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<?php if(isset($options['advanced_editor']) and $options['advanced_editor'] and wpl_global::check_addon('pro')): ?>
<?php wp_editor($value, 'tinymce_wpl_c_'.$field->id, array('teeny'=>false, 'quicktags'=>false)); ?>
<input class="wpl-button button-1 wpl-save-btn" type="button" onclick="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', wpl_get_tinymce_content('tinymce_wpl_c_<?php echo $field->id; ?>'), '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>" />
<?php else: ?>
<textarea class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" rows="<?php echo $options['rows']; ?>" cols="<?php echo $options['cols']; ?>" onblur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?>><?php echo $value; ?></textarea>
<?php endif; ?>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}