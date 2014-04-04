<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
_wpl_import("libraries.locations");
_wpl_import("libraries.favorites");

class wpl_property_listing_controller extends wpl_controller
{
    public function display()
    {
        $function = wpl_request::getVar('wpl_function');

        if($function == 'get_locations')
        {
            $location_level = wpl_request::getVar('location_level');
            $parent = wpl_request::getVar('parent');
            $current_location_id = wpl_request::getVar('current_location_id');
            $widget_id = wpl_request::getVar('widget_id');

            self::get_locations($location_level, $parent, $current_location_id, $widget_id);
        }
        elseif($function == 'locationtextsearch_autocomplete')
        {
            $term = wpl_request::getVar('term');
            self::locationtextsearch_autocomplete($term);
        }
    }

    private function get_locations($location_level = '', $parent = '', $current_location_id = '', $widget_id)
    {
        $location_settings = wpl_global::get_settings('3'); # location settings

        if($location_settings['zipcode_parent_level'] == $location_level - 1)
        {
            $location_level = 'zips';
        }

        $location_data = wpl_locations::get_locations($location_level, $parent, ($location_level == '1' ? 1 : ''));

        $res = count($location_data) ? 1 : 0;
        $message = $res ? __('Fetched.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
        $name_id = $location_level != 'zips' ? 'sf' . $widget_id . '_select_location' . $location_level . '_id' : 'sf' . $widget_id . '_select_zip_id';

        $html = '<select name="' . $name_id . '" id="' . $name_id . '"';

        if($location_level != 'zips')
            $html .='onchange="wpl' . $widget_id . '_search_widget_load_location(\'' . $location_level . '\', this.value, \'' . $current_location_id . '\');"';

        $html .= '>';
        $html .= '<option value="">' . __('Select', WPL_TEXTDOMAIN) . '</option>';

        foreach($location_data as $location)
        {
            $html .= '<option value="' . $location->id . '" ' . ($current_location_id == $location->id ? 'selected="selected"' : '') . '>' . $location->name . '</option>';
        }

        $html .= '</select>';

        $response = array('success' => $res, 'message' => $message, 'data' => $location_data, 'html' => $html, 'keyword' => __($location_settings['location' . $location_level . '_keyword'], WPL_TEXTDOMAIN));
        echo json_encode($response);
        exit;
    }

    private function locationtextsearch_autocomplete($term)
    {
        $limit = 10;
        $query = "SELECT `count`, `location_text` AS name FROM `#__wpl_locationtextsearch` WHERE `location_text` LIKE '" . $term . "%' ORDER BY `count` DESC LIMIT " . $limit;
        $results = wpl_db::select($query, 'loadAssocList');
        
        $output = array();
        foreach($results as $result)
        {
            $output[] = array('label' => $result['name'], 'value' => $result['name']);
        }

        echo json_encode($output);
        exit;
    }
}
