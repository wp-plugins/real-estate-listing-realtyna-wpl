<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1">
    <h2><?php echo (isset($this->property_type_data->name) ? $this->property_type_data->name : __('Add new property type', WPL_TEXTDOMAIN)); ?></h2>
    <div class="fanc-body">
        <div class="fanc-row">
            <label for="wpl_title<?php echo $this->property_type_id; ?>"><?php echo __('Name', WPL_TEXTDOMAIN); ?></label>
            <input class="text_box" type="text" id="wpl_name<?php echo $this->property_type_id; ?>" value="<?php echo (isset($this->property_type_data->name) ? $this->property_type_data->name : ''); ?>" onchange="wpl_ajax_save_property_type('name', this, '<?php echo $this->property_type_id; ?>');" autocomplete="off" />
            <span class="ajax-inline-save" id="wpl_name<?php echo $this->property_type_id; ?>_ajax_loader"></span>
        </div>
        <div class="fanc-row">
            <label for="wpl_parent<?php echo $this->property_type_id; ?>"><?php echo __('Category', WPL_TEXTDOMAIN); ?></label>
            <select class="text_box" id="wpl_parent<?php echo $this->property_type_id; ?>" onchange="wpl_ajax_save_property_type('parent', this, '<?php echo $this->property_type_id; ?>');" autocomplete="off">
                <option value="">-----</option>
                <?php foreach ($this->property_types_category as $property_types_category): ?>
				<option <?php if(isset($this->property_type_data->parent) and $property_types_category["id"] == $this->property_type_data->parent): ?> selected="selected" <?php endif; ?> value="<?php echo $property_types_category["id"] ?>"><?php echo $property_types_category["name"] ?></option>
                <?php endforeach; ?>
                <span class="ajax-inline-save" id="wpl_parent<?php echo $this->property_type_id; ?>_ajax_loader"></span>
            </select>
        </div>
        <?php if($this->property_type_id === 10000){ ?>
        <div class="fanc-row">
            <label></label>
            <input type="button" class="wpl-button button-1" onclick="wpl_ajax_insert_property_type(<?php echo $this->property_type_id; ?>);" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>"/>
        </div>
		<?php } ?>
        <div class="wpl_show_message<?php echo $this->property_type_id; ?>" style="margin: 0 10px;"></div>
    </div>
</div>