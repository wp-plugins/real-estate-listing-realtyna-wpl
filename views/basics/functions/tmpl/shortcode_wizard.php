<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$js = (object) array('param1'=>'wpl-tinymce-popup', 'param2'=>wpl_global::get_wordpress_url().'wp-includes/js/tinymce/tiny_mce_popup.js', 'external'=>true);
wpl_extensions::import_javascript($js);
?>
<div class="short-code-wp wpl_shortcode_wizard_container" id="wpl_shortcode_wizard_container" style="margin: 0 20px;">
    <h2>
        <i class="icon-shortcode"></i>
        <span><?php echo __('WPL Shortcodes', WPL_TEXTDOMAIN); ?></span>
        <button class="wpl-button button-1" onclick="insert_shortcode();"><?php echo __('Insert', WPL_TEXTDOMAIN); ?></button>
    </h2>
    <div class="short-code-body">
        <div class="plugin-row wpl_select_view">
            <label for="view_selectbox"><?php echo __('View', WPL_TEXTDOMAIN); ?></label>
            <select id="view_selectbox" onchange="wpl_view_selected(this.value);">
                <option value="property_listing"><?php echo __('Property Listing', WPL_TEXTDOMAIN); ?></option>
                <option value="property_show"><?php echo __('Property Show', WPL_TEXTDOMAIN); ?></option>
                <option value="profile_listing"><?php echo __('Profile/Agent Listing', WPL_TEXTDOMAIN); ?></option>
                <option value="profile_show"><?php echo __('Profile/Agent Show', WPL_TEXTDOMAIN); ?></option>
                <option value="profile_wizard"><?php echo __('My Profile', WPL_TEXTDOMAIN); ?></option>
                <?php if(wpl_global::check_addon('pro')): ?><option value="widget_shortcode"><?php echo __('Widget Shortcode', WPL_TEXTDOMAIN); ?></option><?php endif; ?>
                <?php if(wpl_global::check_addon('save_searches')): ?><option value="save_searches"><?php echo __('Save Searches', WPL_TEXTDOMAIN); ?></option><?php endif; ?>
            </select>
        </div>
		
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $kinds = wpl_flex::get_kinds('wpl_properties'); ?>
            <label for="pr_kind_selectbox"><?php echo __('Kind', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_kind_selectbox" name="kind">
                <?php foreach($kinds as $kind): ?>
				<option value="<?php echo $kind['id']; ?>"><?php echo __($kind['name'], WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $listings = wpl_global::get_listings(); ?>
            <label for="pr_listing_type_selectbox"><?php echo __('Listing type', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_listing_type_selectbox" name="sf_select_listing">
            	<option value="-1"><?php echo __('All', WPL_TEXTDOMAIN); ?></option>
                <?php foreach($listings as $listing): ?>
				<option value="<?php echo $listing['id']; ?>"><?php echo __($listing['name'], WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $property_types = wpl_global::get_property_types(); ?>
            <label for="pr_property_type_selectbox"><?php echo __('Property type', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_property_type_selectbox" name="sf_select_property_type">
            	<option value="-1"><?php echo __('All', WPL_TEXTDOMAIN); ?></option>
                <?php foreach($property_types as $property_type): ?>
				<option value="<?php echo $property_type['id']; ?>"><?php echo __($property_type['name'], WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- View Layouts -->
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $property_listing_layouts = wpl_global::get_layouts('property_listing', array('message.php'), 'frontend'); ?>
            <label for="pr_tpl_selectbox"><?php echo __('Layout', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_tpl_selectbox" name="tpl">
                <?php foreach($property_listing_layouts as $layout): ?>
				<option value="<?php echo ($layout != 'default' ? $layout : ''); ?>" <?php if($layout == 'default') echo 'selected="selected"'; ?>><?php echo __($layout, WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_show">
            <?php $property_show_layouts = wpl_global::get_layouts('property_show', array('message.php'), 'frontend'); ?>
            <label for="pr_tpl_selectbox"><?php echo __('Layout', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_tpl_selectbox" name="tpl">
                <?php foreach($property_show_layouts as $layout): ?>
				<option value="<?php echo ($layout != 'default' ? $layout : ''); ?>" <?php if($layout == 'default') echo 'selected="selected"'; ?>><?php echo __($layout, WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_listing">
            <?php $profile_listing_layouts = wpl_global::get_layouts('profile_listing', array('message.php'), 'frontend'); ?>
            <label for="pr_tpl_selectbox"><?php echo __('Layout', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_tpl_selectbox" name="tpl">
                <?php foreach($profile_listing_layouts as $layout): ?>
				<option value="<?php echo ($layout != 'default' ? $layout : ''); ?>" <?php if($layout == 'default') echo 'selected="selected"'; ?>><?php echo __($layout, WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_show">
            <?php $profile_show_layouts = wpl_global::get_layouts('profile_show', array('message.php'), 'frontend'); ?>
            <label for="pr_tpl_selectbox"><?php echo __('Layout', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_tpl_selectbox" name="tpl">
                <?php foreach($profile_show_layouts as $layout): ?>
				<option value="<?php echo ($layout != 'default' ? $layout : ''); ?>" <?php if($layout == 'default') echo 'selected="selected"'; ?>><?php echo __($layout, WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $location_settings = wpl_global::get_settings('3'); # location settings ?>
            <label for="pr_location_textsearch"><?php echo __('Location', WPL_TEXTDOMAIN); ?></label>
            <input type="text" id="pr_location_textsearch" name="sf_locationtextsearch" placeholder="<?php echo __($location_settings['locationzips_keyword'].', '.$location_settings['location3_keyword'].', '.$location_settings['location1_keyword'], WPL_TEXTDOMAIN); ?>" />
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <label for="pr_price_min"><?php echo __('Price (Min)', WPL_TEXTDOMAIN); ?></label>
            <input type="text" id="pr_price_min" name="sf_min_price" />
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <label for="pr_price_max"><?php echo __('Price (Max)', WPL_TEXTDOMAIN); ?></label>
            <input type="text" id="pr_price_max" name="sf_max_price" />
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $units = wpl_units::get_units(4); ?>
            <label for="pr_price_unit_selectbox"><?php echo __('Price Unit', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_price_unit_selectbox" name="sf_unit_price">
                <?php foreach($units as $unit): ?>
				<option value="<?php echo $unit['id']; ?>"><?php echo __($unit['name'], WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php $tags = wpl_flex::get_tag_fields(0); foreach($tags as $tag): ?>
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <label for="pr_only_<?php echo $tag->table_column; ?>_selectbox"><?php echo __($tag->name, WPL_TEXTDOMAIN); ?></label>
            <select id="pr_only_<?php echo $tag->table_column; ?>_selectbox" name="sf_select_<?php echo $tag->table_column; ?>">
                <option value="-1"><?php echo __('Any', WPL_TEXTDOMAIN); ?></option>
                <option value="0"><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
                <option value="1"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
            </select>
        </div>
        <?php endforeach; ?>
        
        <div class="plugin-row wpl_shortcode_parameter pr_property_listing pr_profile_show">
            <?php $wpl_users = wpl_users::get_wpl_users(); ?>
            <label for="pr_target_page_selectbox"><?php echo __('User', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_target_page_selectbox" name="sf_select_user_id" data-has-chosen="0">
            	<option value="">-----</option>
                <?php foreach($wpl_users as $wpl_user): ?>
				<option value="<?php echo $wpl_user->ID; ?>"><?php echo $wpl_user->user_login.((trim($wpl_user->first_name) != '' or trim($wpl_user->last_name) != '') ? ' ('.$wpl_user->first_name.' '.$wpl_user->last_name.')' : ''); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_listing">
            <?php $user_types = wpl_users::get_user_types(); ?>
            <label for="pr_user_type_selectbox"><?php echo __('User Type', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_user_type_selectbox" name="sf_select_membership_type">
                <option value="">-----</option>
                <?php foreach($user_types as $user_type): ?>
				<option value="<?php echo $user_type->id; ?>"><?php echo __($user_type->name, WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if(wpl_global::check_addon('membership')): ?>
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_listing">
            <?php $memberships = wpl_users::get_wpl_memberships(); ?>
            <label for="pr_membership_selectbox"><?php echo __('Membership', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_membership_selectbox" name="sf_select_membership_id">
                <option value="">-----</option>
                <?php foreach($memberships as $membership): ?>
				<option value="<?php echo $membership->id; ?>"><?php echo __($membership->membership_name, WPL_TEXTDOMAIN); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        
        <div class="plugin-row wpl_shortcode_parameter pr_property_listing pr_profile_listing pr_profile_show">
            <?php $pages = wpl_global::get_wp_pages(); ?>
            <label for="pr_target_page_selectbox"><?php echo __('Target page', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_target_page_selectbox" name="wpltarget" data-has-chosen="0">
            	<option value="">-----</option>
                <?php foreach($pages as $page): ?>
				<option value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing pr_profile_listing">
            <?php $page_sizes = explode(',', trim($this->settings['page_sizes'], ', ')); ?>
            <label for="pr_limit_selectbox"><?php echo __('Page Size', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_limit_selectbox" name="limit">
                <?php foreach($page_sizes as $page_size): ?>
                    <option value="<?php echo $page_size; ?>" <?php if($this->settings['default_page_size'] == $page_size) echo 'selected="selected"'; ?>><?php echo $page_size; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if(wpl_global::check_addon('pro')): ?>
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing pr_profile_listing pr_profile_show">
            <label for="pr_wplpagination_selectbox"><?php echo __('Pagination Type', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_wplpagination_selectbox" name="wplpagination">
                <option value="">-----</option>
                <option value="scroll"><?php echo __('Scroll Pagination'); ?></option>
            </select>
        </div>
        <?php endif; ?>
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $sort_options = wpl_sort_options::get_sort_options(0, 1);/** getting enaled sort options **/ ?>
            <label for="pr_orderby_selectbox"><?php echo __('Order by', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_orderby_selectbox" name="wplorderby">
                <?php foreach($sort_options as $value_array): ?>
                    <option value="<?php echo $value_array['field_name']; ?>" <?php if($this->settings['default_orderby'] == $value_array['field_name']) echo 'selected="selected"' ?>><?php echo $value_array['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_listing">
            <?php $sort_options = wpl_sort_options::get_sort_options(2, 1); /** getting enaled sort options **/ ?>
            <label for="pr_orderby_user_selectbox"><?php echo __('Order by', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_orderby_user_selectbox" name="wplorderby">
                <?php foreach($sort_options as $value_array): ?>
                    <option value="<?php echo $value_array['field_name']; ?>" <?php if($this->settings['default_profile_orderby'] == $value_array['field_name']) echo 'selected="selected"' ?>><?php echo $value_array['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing pr_profile_listing">
            <label for="pr_order_selectbox"><?php echo __('Order', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_order_selectbox" name="wplorder">
                <option value="DESC"><?php echo __('DESC', WPL_TEXTDOMAIN); ?></option>
                <option value="ASC"><?php echo __('ASC', WPL_TEXTDOMAIN); ?></option>
            </select>
        </div>
       
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_show">
            <label for="pr_mls_id_textbox"><?php echo __('Listing ID', WPL_TEXTDOMAIN); ?></label>
            <input type="text" id="pr_mls_id_textbox" name="mls_id" />
        </div>
        
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_widget_shortcode">
			<?php $widgets_list = wpl_widget::get_existing_widgets(); ?>
            <label for="pr_widget_selectbox"><?php echo __('Widget', WPL_TEXTDOMAIN); ?></label>
            <select id="pr_widget_selectbox" name="id">
            	<option value="">-----</option>
                <?php foreach($widgets_list as $sidebar=>$widgets): if($sidebar == 'wp_inactive_widgets') continue; ?>
                	<?php foreach($widgets as $widget): if(strpos($widget['id'], 'wpl_') === false) continue; ?>
					<option value="<?php echo $widget['id']; ?>"><?php echo ucwords(str_replace('_', ' ', $widget['id'])); ?></option>
                    <?php endforeach;?>
                <?php endforeach; ?>
            </select>
        </div>

    </div>
</div>
<script type="text/javascript">
wplj(document).ready(function()
{
    setTimeout(wpl_view_selected(wplj("#view_selectbox").val()), 1000);
});

function insert_shortcode()
{
	var shortcode = '';
	var view = wplj("#view_selectbox").val();

	if (view === 'property_listing') shortcode += '[WPL';
    else if (view === 'property_show') shortcode += '[wpl_property_show';
	else if (view === 'profile_listing') shortcode += '[wpl_profile_listing';
    else if (view === 'profile_show') shortcode += '[wpl_profile_show';
	else if (view === 'profile_wizard') shortcode += '[wpl_my_profile';
	else if (view === 'widget_shortcode') shortcode += '[wpl_widget_instance';
    else if (view === 'save_searches') shortcode += '[wpl_addon_save_searches';

	wplj("#wpl_shortcode_wizard_container .pr_" + view + " input:text, #wpl_shortcode_wizard_container .pr_" + view + " input[type='hidden'], #wpl_shortcode_wizard_container .pr_" + view + " select").each(function(ind, elm)
	{
		if(elm.name == '') return;
        if(wplj(elm).val() == '' || wplj(elm).val() == '-1') return;
        
		shortcode += ' ' + elm.name + '="' + wplj(elm).val() + '"';
	});

	shortcode += ']';

	// inserts the shortcode into the active editor
	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

	// closes Thickbox
	tinyMCEPopup.close();
}

function wpl_view_selected(view)
{
	if (!view) view = 'property_listing';

	wplj(".wpl_shortcode_wizard_container .wpl_shortcode_parameter").hide();
	wplj(".wpl_shortcode_wizard_container .pr_" + view).show();
}
</script>