<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1" id="wpl_delete_listing_property_type_cnt">
    <h2><?php echo __('Removing listing type', WPL_TEXTDOMAIN) . ' ' . __($this->listing_type_data->name, WPL_TEXTDOMAIN); ?></h2>

    <div class="fanc-body wpl-del-options" id="lt-del-options">
        <div class="wpl_show_message<?php echo $this->listing_type_id; ?>" style="margin: 0 10px;"></div>

        <div onclick="purge_properties_listing_type(<?php echo $this->listing_type_id; ?>)" id="purge_properties" class="button button-large wpl-purge">
            <?php echo __('Purge related properties', WPL_TEXTDOMAIN); ?>
        </div>

        <div onclick="show_opt_2_listing_type()" id="option_2" class="button button-primary button-large wpl-assign">
            <?php echo __('Assign to another listing type', WPL_TEXTDOMAIN); ?>
        </div>
    </div>

    <div class="fanc-body hidden" id="lt-del-plist">
        <div class="fanc-row fanc-button-row-2">
            <input type="button" class="wpl-button button-1" value="<?php echo __('Assign', WPL_TEXTDOMAIN); ?>" onclick="assign_properties_listing_type(<?php echo $this->listing_type_id; ?>);" />
        </div>
        <div class="fanc-row">
            <label for="listing_type_select"><?php echo __('Listing Type', WPL_TEXTDOMAIN); ?></label>
            <select id="listing_type_select">
                <option value="-1">-----</option>
                <?php
                foreach($this->listing_types as $listing_type)
                {
                    if($listing_type['id'] == $this->listing_type_id) continue;
                    echo '<option value="' . $listing_type['id'] . '">' . $listing_type['name'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>