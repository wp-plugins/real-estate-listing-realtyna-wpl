<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if ($type == 'select' and !$done_this) {
    ?>
    <div class="search-field-wp search-field-select <?php echo $value['enable']; ?>" 
         data-field-id="<?php echo $field->id; ?>" 
         data-status="<?php echo $value['enable']; ?>"
         data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>"
         data-field-order="<?php echo $value['sort']; ?>">

        <input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo $value['sort']; ?>" />
        <input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo $value['enable']; ?>" />
        <input type="hidden" id="field_id_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][id]" value="<?php echo $value['id']; ?>" />

        <h4>
            <span>
                <?php echo __($field->name, WPL_TEXTDOMAIN); ?>
            </span>
        </h4>

        <div class="field-body">
            <div class="erow">
                <select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this, 'select');">
                    <option value="select" <?php if ($value['type'] == 'select') echo 'selected="selected"'; ?>><?php echo __('Select box', WPL_TEXTDOMAIN); ?></option>
                    <option value="multiple" <?php if ($value['type'] == 'multiple') echo 'selected="selected"'; ?>><?php echo __('Multiple SelectBox', WPL_TEXTDOMAIN); ?></option>
                    <option value="radios" <?php if ($value['type'] == 'radios') echo 'selected="selected"'; ?>><?php echo __('Radio Buttons', WPL_TEXTDOMAIN); ?></option>
                    <option value="radios_any" <?php if ($value['type'] == 'radios_any') echo 'selected="selected"'; ?>><?php echo __('Radio buttons with any', WPL_TEXTDOMAIN); ?></option>
                    <option value="checkboxes" <?php if ($value['type'] == 'checkboxes') echo 'selected="selected"'; ?>><?php echo __('Check boxes', WPL_TEXTDOMAIN); ?></option>
                    <option value="predefined" <?php if ($value['type'] == 'predefined') echo 'selected="selected"'; ?>><?php echo __('Predefined', WPL_TEXTDOMAIN); ?></option>
                </select>
            </div>
            <div class="erow wpl_extoptions_span <?php echo $value['type']; ?>">
                <select multiple="multiple" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption][]">
                    <?php
                    $options = $field->options;
                    $options = json_decode($options, true);
                    $options = $options['params'];

                    foreach ($options as $option) {
                        ?>
                        <option <?php if (in_array($option['key'], $value['extoption'])) echo 'selected="selected"'; ?> value="<?php echo $option['key']; ?>"><?php echo $option['value']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <?php
    $done_this = true;
}