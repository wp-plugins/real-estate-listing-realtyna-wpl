<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1" id="wpl_delete_listing_property_type_cnt">
    <h2><?php echo __('Delete listing type', WPL_TEXTDOMAIN).' '.__($this->listing_type_data->name, WPL_TEXTDOMAIN); ?></h2>
    <div class="fanc-body">
        <div class="wpl_show_message<?php echo $this->listing_type_id; ?>" style="margin: 0 10px;"></div>
		<div class="options">
			<div class="option-1">
				<div onclick="purge_properties_listing_type(<?php echo $this->listing_type_id; ?>)" id="purge_properties" class="purge_properties">
					<?php echo __('Purge properties in this listing type', WPL_TEXTDOMAIN); ?>
				</div>
			</div>
			<div class="option-2">
				<div onclick="show_opt_2_listing_type()" id="option_2" class="option_2">
					<?php echo __('Assign to another listing type', WPL_TEXTDOMAIN); ?>
				</div>
			</div>
		</div>
		<div class="hidden" id="listing_type_list">
            <label for="listing_type_select"><?php echo __('Listing Type', WPL_TEXTDOMAIN); ?></label>
			<select id="listing_type_select">
                <option value="-1">-----</option>
                <?php
                foreach($this->listing_types as $listing_type)
                {
                    if($listing_type['id'] == $this->listing_type_id) continue;
                    echo '<option value="'.$listing_type['id'].'">'.$listing_type['name'].'</option>';
                }
                ?>
			</select>
            <div class="wpl_button_cnt"><input type="button" class="wpl-button button-1" value="<?php echo __('Submit', WPL_TEXTDOMAIN); ?>" onclick="assign_properties_listing_type(<?php echo $this->listing_type_id; ?>);" /></div>
		</div>
    </div>
</div>