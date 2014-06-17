<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1" id="wpl_delete_listing_property_type_cnt">
    <h2><?php echo __('Delete property type', WPL_TEXTDOMAIN).' '.__($this->property_type_data->name, WPL_TEXTDOMAIN); ?></h2>
    <div class="fanc-body">
        <div class="wpl_show_message<?php echo $this->property_type_id; ?>" style="margin: 0 10px;"></div>
		<div class="options">
			<div class="option-1">
				<div onclick="purge_properties_property_type(<?php echo $this->property_type_id; ?>)" id="purge_properties" class="purge_properties">
					<?php echo __('Purge properties in this property type', WPL_TEXTDOMAIN); ?>
				</div>
			</div>
			<div class="option-2">
				<div onclick="show_opt_2_property_type()" id="option_2" class="option_2">
					<?php echo __('Assign to another property type', WPL_TEXTDOMAIN); ?>
				</div>
			</div>
		</div>
		<div class="hidden" id="property_type_list">
            <label for="property_type_select"><?php echo __('Property Type', WPL_TEXTDOMAIN); ?></label>
			<select id="property_type_select">
                <option value="-1">-----</option>
                <?php
                foreach($this->property_types as $property_type)
                {
                    if($property_type['id'] == $this->property_type_id) continue;
                    echo '<option value="'.$property_type['id'].'">'.$property_type['name'].'</option>';
                }
                ?>
			</select>
            <div class="wpl_button_cnt"><input type="button" class="wpl-button button-1" value="<?php echo __('Submit', WPL_TEXTDOMAIN); ?>" onclick="assign_properties_property_type(<?php echo $this->property_type_id; ?>);" /></div>
		</div>
    </div>
</div>