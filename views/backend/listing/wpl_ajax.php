<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.property');
_wpl_import('libraries.locations');
_wpl_import('libraries.render');
_wpl_import('libraries.items');

class wpl_listing_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.listing.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('agent');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'save')
		{
			$table_name = wpl_request::getVar('table_name');
			$table_column = wpl_request::getVar('table_column');
			$value = wpl_request::getVar('value');
			$item_id = wpl_request::getVar('item_id');
			
			$this->save($table_name, $table_column, $value, $item_id);
		}
		elseif($function == 'location_save')
		{
			$table_name = wpl_request::getVar('table_name');
			$table_column = wpl_request::getVar('table_column');
			$value = wpl_request::getVar('value');
			$item_id = wpl_request::getVar('item_id');
			
			$this->location_save($table_name, $table_column, $value, $item_id);
		}
		elseif($function == 'get_locations')
		{
			$location_level = wpl_request::getVar('location_level');
			$parent = wpl_request::getVar('parent');
			$current_location_id = wpl_request::getVar('current_location_id');
            $field_id = wpl_request::getVar('field_id', 41);
			
			$this->get_locations($location_level, $parent, $current_location_id, $field_id);
		}
		elseif($function == 'finalize')
		{
			$item_id = wpl_request::getVar('item_id');
			$mode = wpl_request::getVar('mode');
			$value = wpl_request::getVar('value', 1);
			
			$this->finalize($item_id, $mode, $value);
		}
        elseif($function == 'item_save') $this->item_save();
        elseif($function == 'get_parents') $this->get_parents();
        elseif($function == 'set_parent') $this->set_parent();
        elseif($function == 'save_multilingual') $this->save_multilingual();
	}
	
	private function save($table_name, $table_column, $value, $item_id)
	{
		$field_type = wpl_global::get_db_field_type($table_name, $table_column);
		if($field_type == 'datetime' or $field_type == 'date') $value = wpl_render::derender_date($value);
        else $value = wpl_db::escape($value);
        
		$res = wpl_db::set($table_name, $item_id, $table_column, $value, 'id');
        
		$res = (int) $res;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
    
    private function save_multilingual()
	{
        $dbst_id = wpl_request::getVar('dbst_id');
        $value = wpl_db::escape(wpl_request::getVar('value'));
        $item_id = wpl_request::getVar('item_id');
        $lang = wpl_request::getVar('lang');
        
        $field = wpl_flex::get_field($dbst_id);
        
        $table_name = $field->table_name;
        $table_column1 = wpl_addon_pro::get_column_lang_name($field->table_column, $lang, false);
        $default_language = wpl_addon_pro::get_default_language();
        
        $table_column2 = NULL;
        if(strtolower($default_language) == strtolower($lang)) $table_column2 = wpl_addon_pro::get_column_lang_name($field->table_column, $lang, true);
        
		wpl_db::set($table_name, $item_id, $table_column1, $value, 'id');
        if($table_column2) wpl_db::set($table_name, $item_id, $table_column2, $value, 'id');
        
		$res = 1;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function location_save($table_name, $table_column, $value, $item_id)
	{
		$location_settings = wpl_global::get_settings('3'); # location settings
		
		$location_level = str_replace('_id', '', $table_column);
		$location_level = substr($location_level, -1);
		
		if($table_column == 'zip_id') $location_level = 'zips';
		
		$location_data = wpl_locations::get_location($value, $location_level);
		$location_name_column = $location_level != 'zips' ? 'location'.$location_level.'_name' : 'zip_name';
		
		/** update property location data **/
		if($location_settings['location_method'] == 2 or ($location_settings['location_method'] == 1 and in_array($location_level, array(1, 2)))) $res = wpl_db::update($table_name, array($table_column=>$value, $location_name_column=>$location_data->name), 'id', $item_id);
		else $res = wpl_db::update($table_name, array($location_name_column=>$value), 'id', $item_id);
		
		$res = (int) $res;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function get_locations($location_level, $parent, $current_location_id = '', $field_id = 41)
	{
		$location_data = wpl_locations::get_locations($location_level, $parent, '');
		$location_settings = wpl_global::get_settings('3'); # location settings
		
		$res = count($location_data) ? 1 : 0;
		if(!is_numeric($parent)) $res = 1;
		
		$message = $res ? __('Fetched.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = $location_data;
		
		/** website is configured to use location text **/
		if($location_settings['location_method'] == 1 and ($location_level >= 3 or $location_level == 'zips'))
		{
			$html = '<input type="text" name="location'.$location_level.'_name" id="wpl_listing_location'.$location_level.'_select" onchange="wpl_listing_location_change(\''.$field_id.'\', \''.$location_level.'\', this.value);" />';
		}
		/** website is configured to use location database **/
		elseif($location_settings['location_method'] == 2 or ($location_settings['location_method'] == 1 and $location_level <= 2))
		{
			$html = '<select name="location'.$location_level.'_id" id="wpl_listing_location'.$location_level.'_select" onchange="wpl_listing_location_change(\''.$field_id.'\', \''.$location_level.'\', this.value);" class="'.((is_numeric($location_level) and $location_level <= 2) ? 'wpl_location_indicator_selectbox' : '').'">';
			$html .= '<option value="0">'.__('Select', WPL_TEXTDOMAIN).'</option>';
			
			foreach($location_data as $location)
			{
				$html .= '<option value="'.$location->id.'" '.($current_location_id == $location->id ? 'selected="selected"' : '').'>'.__($location->name, WPL_TEXTDOMAIN).'</option>';
			}
			
			$html .= '</select>';
		}
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data, 'html'=>$html, 'keyword'=>__($location_settings['location'.$location_level.'_keyword'], WPL_TEXTDOMAIN));
		
		echo json_encode($response);
		exit;
	}
	
	private function finalize($item_id, $mode, $value = 1)
	{
		if($value) wpl_property::finalize($item_id, $mode);
		else wpl_property::unfinalize($item_id);
		
		$res = 1;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
    
    private function item_save()
	{
		$kind = wpl_request::getVar('kind', 0);
		$parent_id = wpl_request::getVar('item_id', 0);
        $item_type = wpl_request::getVar('item_type', '');
        $item_cat = wpl_request::getVar('item_cat', '');
        $item_name = wpl_request::getVar('value', '');
        $item_extra1 = wpl_request::getVar('item_extra1', '');
        $item_extra2 = wpl_request::getVar('item_extra2', '');
        $item_extra3 = wpl_request::getVar('item_extra3', '');
        
        $query = "SELECT `id` FROM `#__wpl_items` WHERE `parent_kind`='$kind' AND `parent_id`='$parent_id' AND `item_type`='$item_type' AND `item_cat`='$item_cat'";
        $item_id = wpl_db::select($query, 'loadResult');
        
        $item = array('parent_id'=>$parent_id, 'parent_kind'=>$kind, 'item_type'=>$item_type, 'item_cat'=>$item_cat, 'item_name'=>$item_name, 
					  'creation_date'=>date("Y-m-d H:i:s"), 'index'=>'1.00', 'item_extra1'=>$item_extra1, 'item_extra2'=>$item_extra2, 'item_extra3'=>$item_extra3);
		
		wpl_items::save($item, $item_id);
        
		$res = 1;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
    
    private function get_parents()
    {
        $kind = wpl_request::getVar('kind', 1);
		$term = wpl_request::getVar('term', '');
        $exclude = trim(wpl_request::getVar('exclude', ''), ', ');
        
        $parents = wpl_property::select_active_properties("AND (`mls_id` LIKE '%$term%' OR `field_312` LIKE '%$term%' OR `field_313` LIKE '%$term%') AND `kind`='$kind' AND `id` NOT IN ($exclude)", '`id`, `mls_id`');
        $results = array();
        
        foreach($parents as $parent)
        {
            $label = '#'.$parent['mls_id'].' - '.wpl_property::update_property_title(NULL, $parent['id']);
            $results[$parent['id']] = array('id'=>$parent['id'], 'label'=>$label, 'value'=>$parent['mls_id']);
        }
        
        echo json_encode($results);
        exit;
    }
    
    private function set_parent()
    {
        $parent_id = wpl_request::getVar('parent_id', 0);
		$item_id = wpl_request::getVar('item_id', 0);
        $replace = wpl_request::getVar('replace', 1);
        $key = wpl_request::getVar('key', 'parent');
        
        /** Set Parent **/
        wpl_db::q("UPDATE `#__wpl_properties` SET `$key`='$parent_id' WHERE `id`='$item_id'");
        
        if($replace)
        {
            $parent_data = wpl_property::get_property_raw_data($parent_id);
            $forbidden_fields = array('id', 'kind', 'deleted', 'mls_id', 'parent', 'pic_numb', 'att_numb',
                'sent_numb', 'contact_numb', 'user_id', 'add_date', 'finalized', 'confirmed', 'visit_time',
                'visit_date', 'last_modified_time_stamp', 'sp_featured', 'sp_hot', 'sp_openhouse',
                'sp_forclosure', 'textsearch', 'property_title', 'location_text', 'vids_numb', 'rendered', 'alias', 'blog_id');

            $q = '';
            foreach($parent_data as $key=>$value)
            {
                if(in_array($key, $forbidden_fields)) continue;

                $q .= "`$key`='$value', ";
            }

            $q .= trim($q, ', ');
            wpl_db::q("UPDATE `#__wpl_properties` SET $q WHERE `id`='$item_id'");
        }
        
        echo json_encode(array('success'=>1));
        exit;
    }
}