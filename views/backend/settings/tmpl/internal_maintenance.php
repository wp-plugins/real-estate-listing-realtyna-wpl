<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl_maintenance"><div class="wpl_show_message"></div></div>
<div class="wpl-maintenance-container">
	<ul>
    	<li onclick="wpl_clear_unfinalized_properties(0);">
            <i class="icon-trash"></i>
            <span class="wpl_ajax_loader" id="wpl_unfinalized_properties_loader"></span>
            <span class="title">
                <?php echo __('Clear Unfinalized Listings', WPL_TEXTDOMAIN); ?>
            </span>
        </li>
        <li onclick="wpl_clear_properties_cached_datas(0);">
            <i class="icon-trash"></i>
            <span class="wpl_ajax_loader" id="wpl_properties_cached_ajax_loader"></span>
            <span class="title">
                <?php echo __('Clear listings cached data', WPL_TEXTDOMAIN); ?>
            </span>
        </li>
        <li onclick="wpl_clear_listings_cached_location_texts(0);">
            <i class="icon-trash"></i>
            <span class="wpl_ajax_loader" id="wpl_listings_location_text_cached_ajax_loader"></span>
            <span class="title">
                <?php echo __('Clear listings cached location texts', WPL_TEXTDOMAIN); ?>
            </span>
        </li>
        <li onclick="wpl_clear_listings_thumbnails(0);">
            <i class="icon-trash"></i>
            <span class="wpl_ajax_loader" id="wpl_listings_thumbnails_ajax_loader"></span>
            <span class="title">
                <?php echo __('Clear listings thumbnails', WPL_TEXTDOMAIN); ?>
            </span>
        </li>
        <li onclick="wpl_clear_users_thumbnails(0);">
            <i class="icon-trash"></i>
            <span class="wpl_ajax_loader" id="wpl_users_thumbnails_ajax_loader"></span>
            <span class="title">
                <?php echo __('Clear users thumbnails', WPL_TEXTDOMAIN); ?>
            </span>
        </li>
    </ul>
</div>