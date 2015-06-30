<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl-search-tab-content-<?php echo $this->widget_id . '-' . $category->id; ?>" class="search-body">
    <div class="search-msg-wp">
        <span>
        <?php echo __('Drag whichever field you would like to move from the bottom list to here. To change the order please use right side panel "Fields Order".', WPL_TEXTDOMAIN); ?>
        </span>
        <div class="search-msg-btn"></div>
    </div>


    <div class="active-block">
        <!--All active fields will be here-->
    </div>

    <div class="wpl-inactive-block-wp wpl-util-scrollbar-wrap">
        <div class="wpl-util-scrollbar-frame">
            <div class="inactive-block">
                <!--All inactive fields will be here-->
            </div>
        </div>
        <div class="wpl-util-scrollbar-scroll wpl-util-scrollbar-bottom">
            <div class="wpl-util-scrollbar-handler"></div>
        </div>
    </div>

    <div class="all-block">
        <?php wpl_search_widget::generate_backend_fields(wpl_flex::get_fields($category->id, 1, $this->kind, 'searchmod', 1), $values); ?>
    </div>

    <div class="overlay-wp">
        <div class="overlay-text">
            <?php echo __('Drag Here', WPL_TEXTDOMAIN); ?>
        </div>
    </div>
</div>
