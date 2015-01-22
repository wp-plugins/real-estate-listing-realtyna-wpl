<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.search.scripts.css_backend", true, true);
include _wpl_import("widgets.search.scripts.js_backend", true, true);

wpl_extensions::import_javascript((object) array('param1'=>'wpl-sly-scrollbar', 'param2'=>'js/libraries/wpl.slyscrollbar.min.js'));

?>
<div id="<?php echo $this->get_field_id('wpl_search_widget_container'); ?>" class="wpl-widget-search-wp">
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', WPL_TEXTDOMAIN); ?>: </label>
        <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
               value="<?php echo $instance['title']; ?>" style="width: 96%;"/>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout', WPL_TEXTDOMAIN); ?>: </label>
        <select id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>"
                style="width: 100%;min-width: 150px;">
            <?php echo $this->generate_layouts_selectbox('search', $instance); ?>
        </select>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('wpltarget'); ?>"><?php echo __('Target page', WPL_TEXTDOMAIN); ?>
            : </label>
        <select id="<?php echo $this->get_field_id('wpltarget'); ?>"
                name="<?php echo $this->get_field_name('wpltarget'); ?>" class="widefat">
            <option value="">-----</option>
            <?php echo $this->generate_pages_selectbox($instance); ?>
        </select>
    </p>

    <button id="btn-search-<?php echo $this->number ?>"
            data-is-init="false"
            data-item-id="<?php echo $this->number ?>"
            data-fancy-id="wpl_view_fields_<?php echo $this->number; ?>" class="wpl-button button-1"
            href="#wpl_view_fields_<?php echo $this->number ?>"><?php _e('View Fields', WPL_TEXTDOMAIN); ?></button>

    <?php if (wpl_global::check_addon('pro')): ?>
        <button id="btn-shortcode-<?php echo $this->number ?>"
                data-is-init="false"
                data-item-id="<?php echo $this->number ?>"
                data-fancy-id="wpl_view_shortcode_<?php echo $this->number; ?>" class="wpl-button button-1"
                href="#wpl_view_shortcode_<?php echo $this->number ?>"
                data-realtyna-lightbox><?php _e('View Shortcode', WPL_TEXTDOMAIN); ?></button>

    <?php endif; ?>
    <span class="page-must-reload"><?php _e('Must be reload page before open the dialog', WPL_TEXTDOMAIN); ?></span>
</div>

<div id="wpl_view_shortcode_<?php echo $this->number ?>" class="hidden">
    <div class="fanc-content size-width-1" id="wpl_flex_modify_container">
        <h2><?php echo __('View Shortcode', WPL_TEXTDOMAIN); ?></h2>

        <div class="fanc-body fancy-search-body">

            <p class="wpl_widget_shortcode_preview"><?php echo '[wpl_widget_instance id="' . $this->id . '"]'; ?></p>
        </div>
    </div>
</div>

<div id="wpl_view_fields_<?php echo $this->number ?>" class="hidden">
    <div class="fanc-content" id="wpl_flex_modify_container_<?php echo $this->number ?>">
        <h2><?php echo __('Search Fields Configurations', WPL_TEXTDOMAIN); ?></h2>

        <div class="fanc-body fancy-search-body wpl-widget-search-fields-wp">
            <div class="search-fields-wp">
                <div class="search-tabs-wp">
                    <?php $this->generate_backend_categories_tabs($instance['data']); ?>
                </div>
                <div class="search-tab-content">
                    <?php $this->generate_backend_categories($instance['data']); ?>
                </div>
            </div>
            <div id="fields-order" class="order-list-wp">
                <h4>
                    <span>
                        <?php echo __('Fields Order', WPL_TEXTDOMAIN); ?>    
                    </span>
                </h4>

                <div class="order-list-body">
                    <ul>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>