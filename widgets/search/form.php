<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.search.scripts.css_backend", true, true);
include _wpl_import("widgets.search.scripts.js_backend", true, true);
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

        <button id="btn-search-<?php echo $this->number ?>" data-is-init="false" data-item-id="<?php echo $this->number ?>"
                data-fancy-id="wpl_view_fields_<?php echo $this->number; ?>" class="wpl-button button-1"
                href="#wpl_view_fields_<?php echo $this->number ?>" onclick="showLiBo<?php echo $this->number ?>();return false;"><?php _e('View Fields', WPL_TEXTDOMAIN); ?></button>
        <span class="page-must-reload"><?php _e(' Must be reload page before open the dialog ', WPL_TEXTDOMAIN); ?></span>
</div>

<div id="wpl_view_fields_<?php echo $this->number ?>" class="wpl_inline_lightbox fanc-box-wp wpl_lightbox hidden">
    <div class="fanc-content size-width-3" id="wpl_flex_modify_container">
        <h2><?php echo __('Search Fields'); ?></h2>

        <div class="fanc-body fancy-search-body">
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
                    <!--<a id="reorder-list" href="#" title="Reorder sorting" class="action-btn icon-recycle-2"></a>-->
                </h4>

                <div class="order-list-body">
                    <ul>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>