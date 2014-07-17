<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.css_backend", true, true);
include _wpl_import("widgets.carousel.scripts.js_backend", true, true);
?>
<div class="wpl_carousel_widget_backend_form" id="<?php echo $this->get_field_id('wpl_carousel_widget_container'); ?>">
    
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