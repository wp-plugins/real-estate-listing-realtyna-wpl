<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.locations');

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

            $this->get_locations($location_level, $parent, $current_location_id, $widget_id);
        }
        elseif($function == 'locationtextsearch_autocomplete')
        {
            $term = wpl_request::getVar('term');
            $this->locationtextsearch_autocomplete($term);
        }
        elseif($function == 'contact_listing_user' or $function == 'contact_agent')
        {
            $this->contact_listing_user();
        }
        elseif($function == 'set_pcc')
        {
            $this->set_pcc();
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
        $html .= '<option value="-1">' . __((trim($location_settings['location'.$location_level.'_keyword']) != '' ? $location_settings['location'.$location_level.'_keyword'] : 'Select'), WPL_TEXTDOMAIN) . '</option>';

        foreach($location_data as $location)
        {
            $html .= '<option value="' . $location->id . '" ' . ($current_location_id == $location->id ? 'selected="selected"' : '') . '>' . __($location->name, WPL_TEXTDOMAIN) . '</option>';
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
    
    private function contact_listing_user()
    {
        $fullname = wpl_request::getVar('fullname', '');
        $phone = wpl_request::getVar('phone', '');
        $email = wpl_request::getVar('email', '');
        $message = wpl_request::getVar('message', '');
        $property_id = wpl_request::getVar('pid', '');
        
        $parameters = array(
            'fullname' => $fullname,
            'phone' => $phone,
            'email' => $email,
            'message' => $message,
            'property_id' => $property_id,
            'user_id' => wpl_property::get_property_user($property_id)
        );
        
        // For integrating third party plugins such as captcha plugins
        apply_filters('preprocess_comment', array());
        
        $returnData = array();
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Your email is not a valid email!', WPL_TEXTDOMAIN);
        }
        else
        {
            wpl_events::trigger('contact_agent', $parameters);
            
            $returnData['success'] = 1;
            $returnData['message'] = __('Information sent to agent.', WPL_TEXTDOMAIN);
        }
        
        echo json_encode($returnData);
        exit;
    }
    
    private function set_pcc()
    {
        $pcc = wpl_request::getVar('pcc', '');
        
        setcookie('wplpcc', $pcc, time()+(86400*30), '/');
        wpl_request::setVar('wplpcc', $pcc, 'COOKIE');
        
        echo json_encode(array('success'=>1));
        exit;
    }
}
