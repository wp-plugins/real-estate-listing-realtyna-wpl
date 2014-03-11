<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>

<div id="sbox-<?php echo $category->id; ?>" class="search-body">
    <div class="search-msg-wp">
        <span>
        <?php echo __('Drag whatever field that you want from bottom list to here. For changing the order please use right side panel " Fields Order ".', WPL_TEXTDOMAIN); ?>
        </span>
        <div class="search-msg-btn action-btn icon-disabled"></div>
    </div>
    <div id="active-<?php echo $category->id; ?>" class="active-block">
        <!--All active fields will be here-->

    </div>
    <div id="inactive-<?php echo $category->id; ?>" class="inactive-block">

        <!--All inactive fields will be here-->
    </div>
    <div id="all-<?php echo $category->id; ?>" class="all-block">
        <?php wpl_search_widget::generate_backend_fields(wpl_flex::get_fields($category->id, 1, 0, 'searchmod', 1), $values); ?>
    </div>

    <div class="overlay-wp">
        <div class="overlay-text">
            <?php echo __('Drag It Here', WPL_TEXTDOMAIN); ?>
        </div>
    </div>
</div>
