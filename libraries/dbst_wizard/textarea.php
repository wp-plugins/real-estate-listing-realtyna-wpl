<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'textarea' and !$done_this)
{
	/**
	echo '<div style="width: 500px;">';
	wp_editor($value, 'tinymce_wpl_c_'.$field->id, array('editor_css'=>'<style type="text/css">#tinymce_wpl_c_'.$field->id.'{width: 300px;}</style>'));
	echo '</div>';
	**/
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<textarea id="wpl_c_<?php echo $field->id; ?>" rows="<?php echo $options['rows']; ?>" cols="<?php echo $options['cols']; ?>" onblur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?>><?php echo $value; ?></textarea>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}