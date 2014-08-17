<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1">
    <h2></h2>
    <div class="fanc-body">
        <div class="fanc-row">
            <label for="name"><?php echo __('Room type', WPL_TEXTDOMAIN); ?>: </label>
            <input class="text_box" type="text" id="name" autocomplete="off" />
            <input type="button" class="wpl-button button-1" value="save" onclick="wpl_ajax_save_room_type();"/>
            <span class="wpl_ajax_loader" id="wpl_room_name_ajax_loader"></span>
        </div>
        <div class="wpl_show_message"></div>
    </div>
</div>
