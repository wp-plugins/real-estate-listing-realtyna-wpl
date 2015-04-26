<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl_sort_options_container">

    <div class="wpl_sort_options_container_title"><?php echo __('Sort Option', WPL_TEXTDOMAIN); ?></div>

    <?php echo $this->model->generate_sorts(); ?>

    <?php if($this->property_css_class_switcher): ?>
        <div class="wpl_list_grid_switcher">
            <div id="grid_view" class="grid_view <?php if($this->property_css_class == 'grid_box') echo 'active'; ?>"></div>
            <div id="list_view" class="list_view <?php if($this->property_css_class == 'row_box') echo 'active'; ?>"></div>
        </div>
    <?php endif; ?>

    <?php if(wpl_global::check_addon('pro') and $this->listings_rss_enabled): ?>
        <div class="wpl-rss-wp">
            <a class="wpl-rss-link" href="#" onclick="wpl_generate_rss();"><span><?php echo __('RSS', WPL_TEXTDOMAIN); ?></span></a>
        </div>
    <?php endif; ?>
    
    <?php if(wpl_global::check_addon('save_searches')): ?>
        <div class="wpl-save-search-wp">
            <a id="wpl_save_search_link_lightbox" class="wpl-save-search-link" data-realtyna-href="#wpl_plisting_lightbox_content_container" onclick="return wpl_generate_save_search();" data-realtyna-lightbox-opts="title:'<?php echo __('Save this Search', WPL_TEXTDOMAIN); ?>'"><span><?php echo __('Save Search', WPL_TEXTDOMAIN); ?></span></a>
        </div>
    <?php endif; ?>
</div>

<?php echo $this->properties_str; ?>

<?php if($this->wplpagination != 'scroll'): ?>
<div class="wpl_pagination_container">
    <?php echo $this->pagination->show(); ?>
</div>
<?php endif; ?>