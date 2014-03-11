<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if ($type == 'separator' and !$done_this) {
    ?>


    <div class="search-field-wp search-field-separator <?php echo $value['enable']; ?>" 
         data-field-id="<?php echo $field->id; ?>" 
         data-status="<?php echo $value['enable']; ?>"
         data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?> - <?php echo __("Separator", WPL_TEXTDOMAIN); ?>"
         data-field-order="<?php echo $value['sort']; ?>">

        <input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo $value['sort']; ?>" />
        <input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo $value['enable']; ?>" />
        <input type="hidden" id="field_id_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][id]" value="<?php echo $value['id']; ?>" />

        <h4>
            <span>
                <?php echo __($field->name, WPL_TEXTDOMAIN); ?> - <?php echo __("Separator", WPL_TEXTDOMAIN); ?>
            </span>
        </h4>

        <div class="field-body">
            <div class="erow">
                <?php echo __('No Option Available', WPL_TEXTDOMAIN); ?>
            </div>
        </div>
    </div>

    <?php
    $done_this = true;
}