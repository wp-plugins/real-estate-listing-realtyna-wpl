<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'textarea' and !$done_this)
{
    $current_language = wpl_global::get_current_language();
?>
<?php
    if(isset($field->multilingual) and $field->multilingual == 1 and wpl_global::check_multilingual_status()):
        wp_enqueue_script('jquery-effects-clip', false, array('jquery-effects-core'));
?>

        <label class="wpl-multiling-label">
            <?php echo __($label, WPL_TEXTDOMAIN); ?>
            <?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?>
        </label>

        <div class="wpl-multiling-field wpl-multiling-textarea">

            <div class="wpl-multiling-flags-wp">

                <div class="wpl-multiling-flag-cnt">
                    <?php foreach($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>
                        <div data-wpl-field="wpl_langs_cnt_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>"
                             data-wpl-field-id="wpl_c_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>"
                             data-wpl-title="<?php echo $wpllang; ?>"
                             class="wpl-multiling-flag wpl-multiling-flag-<?php echo strtolower(substr($wpllang,-2)); echo empty($values[$lang_column])? ' wpl-multiling-empty': ''; ?>">
                             </div>
                    <?php endforeach; ?>
                </div>

                <div class="wpl-multilang-field-cnt">

                    <?php foreach($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>

                        <div class="wpl-lang-cnt" id="wpl_langs_cnt_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>">

                            <?php if(isset($options['advanced_editor']) and $options['advanced_editor'] and wpl_global::check_addon('pro')): ?>

                                <div class="wpl-multiling-editor">
                                    <?php wp_editor(stripslashes($values[$lang_column]), 'tinymce_wpl_c_'.$field->id.'_'.strtolower($wpllang), array('teeny'=>false, 'quicktags'=>false)); ?>
                                </div>

                                <input class="wpl-button button-1 wpl-multiling-save-pro"
                                       type="button" onclick="ajax_multilingual_save('<?php echo $field->id; ?>', '<?php echo strtolower($wpllang); ?>', wpl_get_tinymce_content('tinymce_wpl_c_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>'), '<?php echo $item_id; ?>');"
                                       value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>" />

                            <?php else: ?>

                                <textarea class="wpl_c_<?php echo $field->table_column; ?>_<?php echo strtolower($wpllang); ?>"
                                          id="wpl_c_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>"
                                          name="<?php echo $field->table_column; ?>_<?php echo strtolower($wpllang); ?>"
                                          rows="<?php echo $options['rows']; ?>" cols="<?php echo $options['cols']; ?>"
                                          onblur="ajax_multilingual_save('<?php echo $field->id; ?>', '<?php echo strtolower($wpllang); ?>', this.value, '<?php echo $item_id; ?>');"
                                          <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> ><?php echo (isset($values[$lang_column]) ? stripslashes($values[$lang_column]) : ''); ?></textarea>

                            <?php endif; ?>

                            <span id="wpl_listing_saved_span_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>" class="wpl_listing_saved_span"></span>

                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>

<?php else: ?>
    <label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
    <?php if(isset($options['advanced_editor']) and $options['advanced_editor'] and wpl_global::check_addon('pro')): ?>
            <div class="wpl-pwizard-editor">
    <?php wp_editor(stripslashes($value), 'tinymce_wpl_c_'.$field->id, array('teeny'=>false, 'quicktags'=>false)); ?>
            </div>
    <input class="wpl-button button-1 wpl-save-btn" type="button" onclick="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', wpl_get_tinymce_content('tinymce_wpl_c_<?php echo $field->id; ?>'), '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>" />
    <?php else: ?>
    <textarea class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" rows="<?php echo isset($options['rows']) ? $options['rows'] : ''; ?>" cols="<?php echo isset($options['cols']) ? $options['cols'] : ''; ?>" onblur="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?>><?php echo stripslashes($value); ?></textarea>
    <?php endif; ?>
    <span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php endif; ?>
<?php
	$done_this = true;
}