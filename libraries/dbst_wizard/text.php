<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'text' and !$done_this)
{
    $current_language = wpl_global::get_current_language();
?>
<?php
    if(isset($field->multilingual) and $field->multilingual == 1 and wpl_global::check_multilingual_status()):
        wp_enqueue_script('jquery-effects-clip', false, array('jquery-effects-core'));
    ?>
    <label class="wpl-multiling-label wpl-multiling-text">
        <?php echo __($label, WPL_TEXTDOMAIN); ?>
        <?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?>
    </label>

    <div class="wpl-multiling-field wpl-multiling-text">

        <div class="wpl-multiling-flags-wp">

            <div class="wpl-multiling-flag-cnt">
                <?php foreach($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>
                    <div data-wpl-field="wpl_c_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>"
                         data-wpl-title="<?php echo $wpllang; ?>"
                        class="wpl-multiling-flag wpl-multiling-flag-<?php echo strtolower(substr($wpllang,-2)); echo empty($values[$lang_column])? ' wpl-multiling-empty': ''; ?>"
                        ></div>
                <?php endforeach; ?>
            </div>

            <div class="wpl-multiling-edit-btn"></div>

            <div class="wpl-multilang-field-cnt">

                <?php foreach($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>
                    <div class="wpl-lang-cnt" id="wpl_langs_cnt_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>">

                        <label for="wpl_c_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>"><?php echo $wpllang; ?></label>

                        <input type="text" class="wpl_c_<?php echo $field->table_column; ?>"
                               id="wpl_c_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>"
                               name="<?php echo $field->table_column; ?>_<?php echo strtolower($wpllang); ?>"
                               placeholder="<?php echo __('Enter Specific Language Value...', WPL_TEXTDOMAIN); ?>"
                               value="<?php echo isset($values[$lang_column]) ? $values[$lang_column] : ''; ?>"
                               onchange="ajax_multilingual_save('<?php echo $field->id; ?>', '<?php echo strtolower($wpllang); ?>', this.value, '<?php echo $item_id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />

                        <span id="wpl_listing_saved_span_<?php echo $field->id; ?>_<?php echo strtolower($wpllang); ?>" class="wpl_listing_saved_span"></span>

                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
<?php else: ?>
    <label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
    <input type="text" class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" value="<?php echo $value; ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
    <span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php endif; ?>

<?php
	$done_this = true;
}