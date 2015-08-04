<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import('widgets.search.scripts.css_backend', true, true);
include _wpl_import('widgets.search.scripts.js_backend', true, true);

wpl_extensions::import_javascript((object) array('param1'=>'wpl-sly-scrollbar', 'param2'=>'js/libraries/wpl.slyscrollbar.min.js'));
?>
<div class="wpl-widget-search-wp wpl-widget-form-wp" id="<?php echo $this->get_field_id('wpl_search_widget_container'); ?>">
    
    <div class="wpl-widget-row">
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', WPL_TEXTDOMAIN); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>"
               onblur="wplSearchWidget<?php echo $this->number ?>.saveChange(this);" />
    </div>
    
    <div class="wpl-widget-row">
        <?php $kinds = wpl_flex::get_kinds(''); ?>
        <label for="<?php echo $this->get_field_id('kind'); ?>"><?php echo __('Kind', WPL_TEXTDOMAIN); ?></label>
        <select id="<?php echo $this->get_field_id('kind'); ?>" name="<?php echo $this->get_field_name('kind'); ?>"
               onchange="wplSearchWidget<?php echo $this->number ?>.saveAndReload(this);">
            <?php foreach($kinds as $kind): if(trim($kind['addon_name']) and !wpl_global::check_addon($kind['addon_name'])) continue; ?>
            <option <?php if(isset($instance['kind']) and $instance['kind'] == $kind['id']) echo 'selected="selected"'; ?> value="<?php echo $kind['id']; ?>"><?php echo __($kind['name'], WPL_TEXTDOMAIN); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="wpl-widget-row">
        <label for="<?php echo $this->get_field_id('layout'); ?>"><?php echo __('Layout', WPL_TEXTDOMAIN); ?></label>
        <select id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>"
                onchange="wplSearchWidget<?php echo $this->number ?>.saveChange(this);">
            <?php echo $this->generate_layouts_selectbox('search', $instance); ?>
        </select>
    </div>

    <div class="wpl-widget-row">
        <label for="<?php echo $this->get_field_id('wpltarget'); ?>"><?php echo __('Target page', WPL_TEXTDOMAIN); ?></label>
        <select id="<?php echo $this->get_field_id('wpltarget'); ?>"
                name="<?php echo $this->get_field_name('wpltarget'); ?>">
            <option value="">-----</option>
            <?php echo $this->generate_pages_selectbox($instance); ?>
            <option value="-1" <?php echo ((isset($instance['wpltarget']) and $instance['wpltarget'] == '-1') ? 'selected="selected"' : ''); ?>><?php echo __('Self Page', WPL_TEXTDOMAIN); ?></option>
        </select>
    </div>
    
    <?php if(wpl_global::check_addon('aps')): ?>
    <div class="wpl-widget-row">
        <label for="<?php echo $this->get_field_id('ajax'); ?>"><?php echo __('AJAX Search', WPL_TEXTDOMAIN); ?></label>
        <select id="<?php echo $this->get_field_id('ajax'); ?>"
               name="<?php echo $this->get_field_name('ajax'); ?>">
            <option value="0" <?php if(isset($instance['ajax']) and $instance['ajax'] == 0) echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
            <option value="1" <?php if(isset($instance['ajax']) and $instance['ajax'] == 1) echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
            <option value="2" <?php if(isset($instance['ajax']) and $instance['ajax'] == 2) echo 'selected="selected"'; ?>><?php echo __('Yes (On the fly)', WPL_TEXTDOMAIN); ?></option>
        </select>
    </div>
    <?php endif; ?>
    
    <div class="wpl-widget-row">
        <label for="<?php echo $this->get_field_id('css_class'); ?>"><?php echo __('CSS Class', WPL_TEXTDOMAIN); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('css_class'); ?>" name="<?php echo $this->get_field_name('css_class'); ?>" value="<?php echo isset($instance['css_class']) ? $instance['css_class'] : ''; ?>" />
    </div>

    <button id="btn-search-<?php echo $this->number ?>"
            data-is-init="false"
            data-item-id="<?php echo $this->number ?>"
            data-fancy-id="wpl_view_fields_<?php echo $this->number; ?>" class="wpl-button button-1"
            href="#wpl_view_fields_<?php echo $this->number ?>"><?php echo __('View Fields', WPL_TEXTDOMAIN); ?></button>

    <?php if(wpl_global::check_addon('pro')): ?>
        <button id="btn-shortcode-<?php echo $this->number ?>"
                data-is-init="false"
                data-item-id="<?php echo $this->number ?>"
                data-fancy-id="wpl_view_shortcode_<?php echo $this->number; ?>" class="wpl-button button-1"
                href="#wpl_view_shortcode_<?php echo $this->number ?>"
                data-realtyna-lightbox><?php echo __('View Shortcode', WPL_TEXTDOMAIN); ?></button>

    <?php endif; ?>

    <span id="wpl-js-page-must-reload-<?php echo $this->number ?>" class="wpl-widget-search-must-reload"><?php echo __('Page need to reloaded before opening the Field Editor...', WPL_TEXTDOMAIN); ?></span>
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