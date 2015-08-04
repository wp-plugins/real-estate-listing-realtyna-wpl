<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1" id="wpl_delete_listing_property_type_cnt">
    <h2><?php echo __('Removing property type', WPL_TEXTDOMAIN) . ' ' . __($this->property_type_data->name, WPL_TEXTDOMAIN); ?></h2>

    <div class="fanc-body wpl-del-options" id="pt-del-options">
        <div class="wpl_show_message<?php echo $this->property_type_id; ?>" style="margin: 0 10px;"></div>

        <div onclick="purge_properties_property_type(<?php echo $this->property_type_id; ?>)" id="purge_properties" class="button button-large wpl-purge">
            <?php echo __('Purge related properties', WPL_TEXTDOMAIN); ?>
        </div>

        <div onclick="show_opt_2_property_type()" id="option_2" class="button button-primary button-large wpl-assign">
            <?php echo __('Assign to another property type', WPL_TEXTDOMAIN); ?>
        </div>
    </div>

    <div class="fanc-body hidden" id="pt-del-plist">
        <div class="fanc-row fanc-button-row-2">
            <input class="wpl-button button-1" type="button" value="<?php echo __('Assign', WPL_TEXTDOMAIN); ?>" onclick="assign_properties_property_type(<?php echo $this->property_type_id; ?>);" />
        </div>
        <div class="fanc-row">
            <label for="property_type_select"><?php echo __('Property Type', WPL_TEXTDOMAIN); ?></label>
            <select id="property_type_select">
                <option value="-1">-----</option>
                <?php
                foreach($this->property_types as $property_type)
                {
                    if($property_type['id'] == $this->property_type_id) continue;
                    echo '<option value="' . $property_type['id'] . '">' . $property_type['name'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>