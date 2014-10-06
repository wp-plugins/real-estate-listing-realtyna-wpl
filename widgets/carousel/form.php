<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.css_backend", true, true);
include _wpl_import("widgets.carousel.scripts.js_backend", true, true);
?>
<script type="text/javascript">
function wpl_carousel_toggle<?php echo $this->widget_id; ?>(element_id)
{
    wplj("#"+element_id).toggle();
}
</script>
<div class="wpl_carousel_widget_backend_form" id="<?php echo $this->get_field_id('wpl_carousel_widget_container'); ?>">
    
    <h4><?php echo __('Widget Configurations'); ?></h4>
    <div>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', WPL_TEXTDOMAIN); ?>: </label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo isset($instance['title']) ? $instance['title'] : ''; ?>" />
    </div>
    
    <div>
        <label for="<?php echo $this->get_field_id('layout'); ?>"><?php echo __('Layout', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
	        <?php echo $this->generate_layouts_selectbox('carousel', $instance); ?>
        </select>
    </div>
    
    <div>
        <label for="<?php echo $this->get_field_id('wpltarget'); ?>"><?php echo __('Target page', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('wpltarget'); ?>" name="<?php echo $this->get_field_name('wpltarget'); ?>">
            <option value="">-----</option>
	        <?php echo $this->generate_pages_selectbox($instance); ?>
        </select>
    </div>
    
    <div>
        <label for="<?php echo $this->get_field_id('image_width'); ?>"><?php echo __('Image Width', WPL_TEXTDOMAIN); ?>: </label>
        <input type="text" id="<?php echo $this->get_field_id('image_width'); ?>" name="<?php echo $this->get_field_name('data'); ?>[image_width]" value="<?php echo isset($instance['data']['image_width']) ? $instance['data']['image_width'] : '1920'; ?>" />
    </div>
    
    <div>
        <label for="<?php echo $this->get_field_id('image_height'); ?>"><?php echo __('Image Height', WPL_TEXTDOMAIN); ?>: </label>
        <input type="text" id="<?php echo $this->get_field_id('image_height'); ?>" name="<?php echo $this->get_field_name('data'); ?>[image_height]" value="<?php echo isset($instance['data']['image_height']) ? $instance['data']['image_height'] : '558'; ?>" />
    </div>
    
    <div>
        <label for="<?php echo $this->get_field_id('thumbnail_width'); ?>"><?php echo __('Thumbnail Width', WPL_TEXTDOMAIN); ?>: </label>
        <input type="text" id="<?php echo $this->get_field_id('thumbnail_width'); ?>" name="<?php echo $this->get_field_name('data'); ?>[thumbnail_width]" value="<?php echo isset($instance['data']['thumbnail_width']) ? $instance['data']['thumbnail_width'] : '150'; ?>" />
    </div>
    
    <div>
        <label for="<?php echo $this->get_field_id('thumbnail_height'); ?>"><?php echo __('Thumbnail Height', WPL_TEXTDOMAIN); ?>: </label>
        <input type="text" id="<?php echo $this->get_field_id('thumbnail_height'); ?>" name="<?php echo $this->get_field_name('data'); ?>[thumbnail_height]" value="<?php echo isset($instance['data']['thumbnail_height']) ? $instance['data']['thumbnail_height'] : '60'; ?>" />
    </div>
    
    <h4><?php echo __('Filter Properties'); ?></h4>
    <div>
        <?php $kinds = wpl_flex::get_kinds('wpl_properties'); ?>
        <label for="<?php echo $this->get_field_id('data_kind'); ?>"><?php echo __('Kind', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_kind'); ?>" name="<?php echo $this->get_field_name('data'); ?>[kind]">
            <?php foreach($kinds as $kind): ?>
            <option <?php if(isset($instance['data']['kind']) and $instance['data']['kind'] == $kind['id']) echo 'selected="selected"'; ?> value="<?php echo $kind['id']; ?>"><?php echo __($kind['name'], WPL_TEXTDOMAIN); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <?php $listings = wpl_global::get_listings(); ?>
    <div>
    	<label for="<?php echo $this->get_field_id('data_listing'); ?>"><?php echo __('Listing', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_listing'); ?>" name="<?php echo $this->get_field_name('data'); ?>[listing]">
        	<option value="-1"><?php echo __('All', WPL_TEXTDOMAIN); ?></option>
            <?php foreach($listings as $listing): ?>
            <option <?php if(isset($instance['data']['listing']) and $instance['data']['listing'] == $listing['id']) echo 'selected="selected"'; ?> value="<?php echo $listing['id']; ?>"><?php echo __($listing['name'], WPL_TEXTDOMAIN); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <?php $property_types = wpl_global::get_property_types(); ?>
    <div>
    	<label for="<?php echo $this->get_field_id('data_property_type'); ?>"><?php echo __('Property type', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_property_type'); ?>" name="<?php echo $this->get_field_name('data'); ?>[property_type]">
        	<option value="-1"><?php echo __('All', WPL_TEXTDOMAIN); ?></option>
            <?php foreach($property_types as $property_type): ?>
            <option <?php if(isset($instance['data']['property_type']) and $instance['data']['property_type'] == $property_type['id']) echo 'selected="selected"'; ?> value="<?php echo $property_type['id']; ?>"><?php echo __($property_type['name'], WPL_TEXTDOMAIN); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <?php if(wpl_global::check_addon('complex')): ?>
    <div>
    	<label for="<?php echo $this->get_field_id('data_parent'); ?>"><?php echo __('Parent', WPL_TEXTDOMAIN); ?>: </label>
        <input type="text" id="<?php echo $this->get_field_id('data_parent'); ?>" title="<?php echo __('Listing ID of parent property', WPL_TEXTDOMAIN); ?>" name="<?php echo $this->get_field_name('data'); ?>[parent]" value="<?php echo isset($instance['data']['parent']) ? $instance['data']['parent'] : ''; ?>" />
    </div>
    <div>
    	<input <?php if(isset($instance['data']['auto_parent']) and $instance['data']['auto_parent']) echo 'checked="checked"'; ?> value="1" type="checkbox" id="<?php echo $this->get_field_id('data_auto_parent'); ?>" name="<?php echo $this->get_field_name('data'); ?>[auto_parent]" />
    	<label for="<?php echo $this->get_field_id('data_auto_parent'); ?>" title="<?php echo __('For single property pages.', WPL_TEXTDOMAIN); ?>"><?php echo __('Detect parent automatically.', WPL_TEXTDOMAIN); ?></label>
    </div>
    <?php endif; ?>
    
    <div>
    	<label for="<?php echo $this->get_field_id('data_property_ids'); ?>"><?php echo __('Property IDs', WPL_TEXTDOMAIN); ?>: </label>
        <input type="text" id="<?php echo $this->get_field_id('data_property_ids'); ?>" name="<?php echo $this->get_field_name('data'); ?>[property_ids]" value="<?php echo isset($instance['data']['property_ids']) ? $instance['data']['property_ids'] : ''; ?>" />
    </div>
    
    <div>
    	<input <?php if(isset($instance['data']['random']) and $instance['data']['random']) echo 'checked="checked"'; ?> value="1" type="checkbox" id="<?php echo $this->get_field_id('data_random'); ?>" name="<?php echo $this->get_field_name('data'); ?>[random]" onclick="random_clicked<?php echo $this->widget_id; ?>();" />
    	<label for="<?php echo $this->get_field_id('data_random'); ?>"><?php echo __('Random', WPL_TEXTDOMAIN); ?>: </label>
    </div>
    
    <div>
    	<label for="<?php echo $this->get_field_id('data_only_featured'); ?>"><?php echo __('Only Featured', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_only_featured'); ?>" name="<?php echo $this->get_field_name('data'); ?>[only_featured]">
        	<option value="0" <?php if(isset($instance['data']['only_featured']) and $instance['data']['only_featured'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
            <option value="1" <?php if(isset($instance['data']['only_featured']) and $instance['data']['only_featured'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
        </select>
    </div>
    
    <div>
    	<label for="<?php echo $this->get_field_id('data_only_hot'); ?>"><?php echo __('Only Hot', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_only_hot'); ?>" name="<?php echo $this->get_field_name('data'); ?>[only_hot]">
        	<option value="0" <?php if(isset($instance['data']['only_hot']) and $instance['data']['only_hot'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
            <option value="1" <?php if(isset($instance['data']['only_hot']) and $instance['data']['only_hot'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
        </select>
    </div>
    
    <div>
    	<label for="<?php echo $this->get_field_id('data_only_openhouses'); ?>"><?php echo __('Only OpenHouses', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_only_openhouses'); ?>" name="<?php echo $this->get_field_name('data'); ?>[only_openhouse]">
        	<option value="0" <?php if(isset($instance['data']['only_openhouse']) and $instance['data']['only_openhouse'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
            <option value="1" <?php if(isset($instance['data']['only_openhouse']) and $instance['data']['only_openhouse'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
        </select>
    </div>
    
    <div>
    	<label for="<?php echo $this->get_field_id('data_only_forclosures'); ?>"><?php echo __('Only Forclosures', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_only_forclosures'); ?>" name="<?php echo $this->get_field_name('data'); ?>[only_forclosure]">
        	<option value="0" <?php if(isset($instance['data']['only_forclosure']) and $instance['data']['only_forclosure'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
            <option value="1" <?php if(isset($instance['data']['only_forclosure']) and $instance['data']['only_forclosure'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
        </select>
    </div>
    
    <h4><?php echo __('Similar Properties'); ?></h4>
    <div>
    	<label for="<?php echo $this->get_field_id('data_sml_only_similars'); ?>"><?php echo __('Only Similars', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_sml_only_similars'); ?>" name="<?php echo $this->get_field_name('data'); ?>[sml_only_similars]" onchange="wpl_carousel_toggle<?php echo $this->widget_id; ?>('<?php echo $this->get_field_id('data_sml_fields_container'); ?>');">
        	<option value="0" <?php if(isset($instance['data']['sml_only_similars']) and $instance['data']['sml_only_similars'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
            <option value="1" <?php if(isset($instance['data']['sml_only_similars']) and $instance['data']['sml_only_similars'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
        </select>
    </div>
    
    <div id="<?php echo $this->get_field_id('data_sml_fields_container'); ?>" style="<?php echo ((!isset($instance['data']['sml_only_similars']) or (isset($instance['data']['sml_only_similars']) and !$instance['data']['sml_only_similars'])) ? 'display: none;' : ''); ?>">
        <div>
            <label for="<?php echo $this->get_field_id('data_sml_inc_listing'); ?>"><?php echo __('Include Listings', WPL_TEXTDOMAIN); ?>: </label>
            <select id="<?php echo $this->get_field_id('data_sml_inc_listing'); ?>" name="<?php echo $this->get_field_name('data'); ?>[sml_inc_listing]">
                <option value="1" <?php if(isset($instance['data']['sml_inc_listing']) and $instance['data']['sml_inc_listing'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
                <option value="0" <?php if(isset($instance['data']['sml_inc_listing']) and $instance['data']['sml_inc_listing'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
            </select>
        </div>

        <div>
            <label for="<?php echo $this->get_field_id('data_sml_inc_property_type'); ?>"><?php echo __('Include Property Type', WPL_TEXTDOMAIN); ?>: </label>
            <select id="<?php echo $this->get_field_id('data_sml_inc_property_type'); ?>" name="<?php echo $this->get_field_name('data'); ?>[sml_inc_property_type]">
                <option value="1" <?php if(isset($instance['data']['sml_inc_property_type']) and $instance['data']['sml_inc_property_type'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
                <option value="0" <?php if(isset($instance['data']['sml_inc_property_type']) and $instance['data']['sml_inc_property_type'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
            </select>
        </div>

        <div>
            <label for="<?php echo $this->get_field_id('data_sml_inc_price'); ?>"><?php echo __('Include Price', WPL_TEXTDOMAIN); ?>: </label>
            <select id="<?php echo $this->get_field_id('data_sml_inc_price'); ?>" name="<?php echo $this->get_field_name('data'); ?>[sml_inc_price]" onchange="wpl_carousel_toggle<?php echo $this->widget_id; ?>('<?php echo $this->get_field_id('data_sml_price_container'); ?>');">
                <option value="1" <?php if(isset($instance['data']['sml_inc_price']) and $instance['data']['sml_inc_price'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
                <option value="0" <?php if(isset($instance['data']['sml_inc_price']) and $instance['data']['sml_inc_price'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
            </select>
        </div>
        
        <div id="<?php echo $this->get_field_id('data_sml_price_container'); ?>" style="<?php echo ((!isset($instance['data']['sml_inc_price']) or (isset($instance['data']['sml_inc_price']) and !$instance['data']['sml_inc_price'])) ? 'display: none;' : ''); ?>">
            <div>
                <label for="<?php echo $this->get_field_id('data_sml_price_down_rate'); ?>"><?php echo __('Price Down Rate', WPL_TEXTDOMAIN); ?>: </label>
                <input type="text" id="<?php echo $this->get_field_id('data_sml_price_down_rate'); ?>" name="<?php echo $this->get_field_name('data'); ?>[sml_price_down_rate]" value="<?php echo isset($instance['data']['sml_price_down_rate']) ? $instance['data']['sml_price_down_rate'] : '0.8'; ?>" />
            </div>

            <div>
                <label for="<?php echo $this->get_field_id('data_sml_price_up_rate'); ?>"><?php echo __('Price Up Rate', WPL_TEXTDOMAIN); ?>: </label>
                <input type="text" id="<?php echo $this->get_field_id('data_sml_price_up_rate'); ?>" name="<?php echo $this->get_field_name('data'); ?>[sml_price_up_rate]" value="<?php echo isset($instance['data']['sml_price_up_rate']) ? $instance['data']['sml_price_up_rate'] : '1.2'; ?>" />
            </div>
        </div>
        
        <div>
            <label for="<?php echo $this->get_field_id('data_sml_inc_radius'); ?>"><?php echo __('Include Radius', WPL_TEXTDOMAIN); ?>: </label>
            <select id="<?php echo $this->get_field_id('data_sml_inc_radius'); ?>" name="<?php echo $this->get_field_name('data'); ?>[sml_inc_radius]" onchange="wpl_carousel_toggle<?php echo $this->widget_id; ?>('<?php echo $this->get_field_id('data_sml_radius_container'); ?>');">
                <option value="0" <?php if(isset($instance['data']['sml_inc_radius']) and $instance['data']['sml_inc_radius'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
                <option value="1" <?php if(isset($instance['data']['sml_inc_radius']) and $instance['data']['sml_inc_radius'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
            </select>
        </div>
        
        <div id="<?php echo $this->get_field_id('data_sml_radius_container'); ?>" style="<?php echo ((!isset($instance['data']['sml_inc_radius']) or (isset($instance['data']['sml_inc_radius']) and !$instance['data']['sml_inc_radius'])) ? 'display: none;' : ''); ?>">
            <div>
                <label for="<?php echo $this->get_field_id('data_sml_radius'); ?>"><?php echo __('Radius', WPL_TEXTDOMAIN); ?>: </label>
                <input type="text" id="<?php echo $this->get_field_id('data_sml_radius'); ?>" name="<?php echo $this->get_field_name('data'); ?>[sml_radius]" value="<?php echo isset($instance['data']['sml_radius']) ? $instance['data']['sml_radius'] : '2000'; ?>" />
            </div>

            <?php $units = wpl_units::get_units(1); ?>
            <div>
                <label for="<?php echo $this->get_field_id('data_sml_radius_unit'); ?>"><?php echo __('Radius Unit', WPL_TEXTDOMAIN); ?>: </label>
                <select id="<?php echo $this->get_field_id('data_sml_radius_unit'); ?>" name="<?php echo $this->get_field_name('data'); ?>[sml_radius_unit]">
                    <?php foreach($units as $unit): ?>
                    <option value="<?php echo $unit['id']; ?>" <?php if(isset($instance['data']['sml_radius_unit']) and $instance['data']['sml_radius_unit'] == $unit['id']) echo 'selected="selected"'; ?>><?php echo __($unit['name'], WPL_TEXTDOMAIN); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    
    <h4><?php echo __('Sort and Limit'); ?></h4>
    <?php $sort_options = wpl_sort_options::get_sort_options(0); ?>
    <div>
    	<label for="<?php echo $this->get_field_id('data_orderby'); ?>"><?php echo __('Order by', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_orderby'); ?>" name="<?php echo $this->get_field_name('data'); ?>[orderby]">
        	<?php foreach($sort_options as $sort_option): ?>
            <option <?php if(isset($instance['data']['orderby']) and urlencode($sort_option['field_name']) == $instance['data']['orderby']) echo 'selected="selected"'; ?> value="<?php echo urlencode($sort_option['field_name']); ?>"><?php echo $sort_option['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div>
    	<label for="<?php echo $this->get_field_id('data_order'); ?>"><?php echo __('Order', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('data_order'); ?>" name="<?php echo $this->get_field_name('data'); ?>[order]">
            <option <?php if(isset($instance['data']['order']) and $instance['data']['order'] == 'ASC') echo 'selected="selected"'; ?> value="ASC"><?php echo __('ASC', WPL_TEXTDOMAIN); ?></option>
            <option <?php if(isset($instance['data']['order']) and $instance['data']['order'] == 'DESC') echo 'selected="selected"'; ?> value="DESC"><?php echo __('DESC', WPL_TEXTDOMAIN); ?></option>
        </select>
    </div>
    
    <div>
    	<label for="<?php echo $this->get_field_id('data_limit'); ?>"><?php echo __('Number of properties', WPL_TEXTDOMAIN); ?>: </label>
        <input type="text" id="<?php echo $this->get_field_id('data_limit'); ?>" name="<?php echo $this->get_field_name('data'); ?>[limit]" value="<?php echo isset($instance['data']['limit']) ? $instance['data']['limit'] : ''; ?>" />
    </div>
</div>