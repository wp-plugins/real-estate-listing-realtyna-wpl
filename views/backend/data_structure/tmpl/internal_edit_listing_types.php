<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1">
    <h2><?php echo (isset($this->listing_type_data->name) ? $this->listing_type_data->name : __('Add new listing type', WPL_TEXTDOMAIN)); ?></h2>
    <div class="fanc-body">
        <div class="fanc-row">
            <label for="wpl_title<?php echo $this->listing_type_id; ?>"><?php echo __('Name', WPL_TEXTDOMAIN); ?></label>
            <input class="text_box" type="text" id="wpl_name<?php echo $this->listing_type_id; ?>" value="<?php echo (isset($this->listing_type_data->name) ? $this->listing_type_data->name : ''); ?>" onchange="wpl_ajax_save_listing_type('name', this, '<?php echo $this->listing_type_id; ?>');" autocomplete="off" />
            <span class="ajax-inline-save" id="wpl_name<?php echo $this->listing_type_id; ?>_ajax_loader"></span>
        </div>
        <div class="fanc-row">
            <label for="wpl_parent<?php echo $this->listing_type_id; ?>"><?php echo __('Category', WPL_TEXTDOMAIN); ?></label>
            <select class="text_box" id="wpl_parent<?php echo $this->listing_type_id; ?>" onchange="wpl_ajax_save_listing_type('parent', this, '<?php echo $this->listing_type_id; ?>');" autocomplete="off">
                <option value="">-----</option>
				<?php foreach($this->listing_types_category as $listing_types_category): ?>
				<option <?php if(isset($this->listing_type_data->parent) and $listing_types_category["id"] == $this->listing_type_data->parent): ?> selected="selected" <?php endif; ?> value="<?php echo $listing_types_category["id"] ?>"><?php echo $listing_types_category["name"] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="ajax-inline-save" id="wpl_parent<?php echo $this->listing_type_id; ?>_ajax_loader"></span>
        </div>
        <div class="fanc-row">
            <label for="wpl_gicon<?php echo $this->listing_type_id; ?>"><?php echo __('Google Icon', WPL_TEXTDOMAIN); ?></label>
            <select class="text_box" id="wpl_gicon<?php echo $this->listing_type_id; ?>" onchange="wpl_ajax_save_listing_type('gicon', this, '<?php echo $this->listing_type_id; ?>');" autocomplete="off">
                <option value="">-----</option>
                <?php foreach($this->listing_gicons as $listing_gicon): ?>
				<option <?php if(isset($this->listing_type_data->gicon) and $listing_gicon == $this->listing_type_data->gicon): ?> selected="selected" <?php endif; ?> value="<?php echo $listing_gicon ?>"><?php echo $listing_gicon ?></option>
                <?php endforeach; ?>
            </select>
            <span class="ajax-inline-save" id="wpl_gicon<?php echo $this->listing_type_id; ?>_ajax_loader"></span>
        </div>
        <?php if($this->listing_type_id === 10000){ ?>
        <div class="fanc-row">
         <label></label><input type="button" class="wpl-button button-1" onclick="wpl_ajax_insert_listing_type(<?php echo $this->listing_type_id; ?>);" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>"/>
        </div>
        <?php } ?>
        <div class="fanc-row">

        </div>
        <div class="wpl_show_message<?php echo $this->listing_type_id; ?>" style="margin: 0 10px;"></div>
    </div>
</div>