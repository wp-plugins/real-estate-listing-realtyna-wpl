<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp data-structure-wp">
    <header>
        <div id="icon-data-structure" class="icon48"></div>
        <h2><?php echo __('Data Structure Manager', WPL_TEXTDOMAIN); ?></h2>
    </header>
    <div class="wpl_data_structure_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp">
        <div class="side-2 side-tabs-wp">
            <ul>
                <li>
                    <a href="#property_types" class="wpl_slide_label wpl_slide_label_id_property_types" id="wpl_slide_label_id_property_types" onclick="rta.internal.slides.open('_property_types', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
						<?php echo __('Property Types', WPL_TEXTDOMAIN); ?>
                    </a>
                </li>
                <li>
                    <a href="#listing_types" class="wpl_slide_label wpl_slide_label_id_listing_types" id="wpl_slide_label_id_listing_types" onclick="rta.internal.slides.open('_listing_types', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
						<?php echo __('Listing Types', WPL_TEXTDOMAIN); ?>
                    </a>
                </li>
                <li>
                    <a href="#unit_manager" class="wpl_slide_label wpl_slide_label_id_unit_manager" id="wpl_slide_label_id_unit_manager" onclick="rta.internal.slides.open('_unit_manager', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
						<?php echo __('Unit Manager', WPL_TEXTDOMAIN); ?>
                    </a>
                </li>
                <li>
                    <a href="#sort_options" class="wpl_slide_label wpl_slide_label_id_sort_options" id="wpl_slide_label_id_sort_options" onclick="rta.internal.slides.open('_sort_options', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
						<?php echo __('Sort Options', WPL_TEXTDOMAIN); ?>
                    </a>
                </li>
                <li>
                    <a href="#room_types" class="wpl_slide_label wpl_slide_label_id_room_types" id="wpl_slide_label_id_room_types" onclick="rta.internal.slides.open('_room_types', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
						<?php echo __('Room Types', WPL_TEXTDOMAIN); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="side-12 side-content-wp">
            <div class="wpl_slide_container wpl_slide_container_room_types" id="wpl_slide_container_id_room_types"><?php $this->generate_room_types(); ?></div>	
            <div class="wpl_slide_container wpl_slide_container_sort_options" id="wpl_slide_container_id_sort_options"><?php $this->generate_sort_options(); ?></div>
            <div class="wpl_slide_container wpl_slide_container_property_types" id="wpl_slide_container_id_property_types"><?php $this->generate_property_types(); ?></div>
            <div class="wpl_slide_container wpl_slide_container_listing_types" id="wpl_slide_container_id_listing_types"><?php $this->generate_listing_types(); ?></div>							
            <div class="wpl_slide_container wpl_slide_container_unit_manager" id="wpl_slide_container_id_unit_manager"><?php $this->generate_unit_manager(); ?></div>
        </div>
    </div>
    <div id="wpl_data_structure_edit_div" class="fanc-box-wp wpl_lightbox wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>