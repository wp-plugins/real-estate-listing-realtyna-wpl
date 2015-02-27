<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$profiles_str = $this->_wpl_render($this->tpl_path.'.assets.default_profiles', true);

if($this->wplraw)
{
    echo $profiles_str;
    exit;
}

$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
if($this->wplpagination == 'scroll' and wpl_global::check_addon('pro')) $this->_wpl_import($this->tpl_path.'.scripts.js_scroll', true, true);
?>
<div id="wpl_profile_listing_main_container">
    
    <?php if(is_active_sidebar('wpl-profile-listing-top')): ?>
    <div class="wpl_plisting_top_sidebar_container">
        <?php dynamic_sidebar('wpl-profile-listing-top'); ?>
    </div>
    <?php endif; ?>
    
	<div class="wpl_sort_options_container">
        <div class="wpl_sort_options_container_title"><?php echo __("Sort Option", WPL_TEXTDOMAIN) ?></div>
        <?php echo $this->model->generate_sorts(); ?>
        <?php if($this->property_css_class_switcher): ?>
        <div class="wpl_list_grid_switcher">
            <div id="grid_view" class="grid_view <?php if($this->property_css_class == 'grid_box') echo 'active'; ?>"></div>
            <div id="list_view" class="list_view <?php if($this->property_css_class == 'row_box') echo 'active'; ?>"></div>
        </div>
        <?php endif; ?>
    </div>
    <div class="wpl_profile_listing_container" id="wpl_profile_listing_container">
		
        <?php echo $profiles_str; ?>
        
        <?php if($this->wplpagination != 'scroll'): ?>
        <div class="wpl_pagination_container">
            <?php echo $this->pagination->show(); ?>
        </div>
        <?php endif; ?>
	</div>
</div>