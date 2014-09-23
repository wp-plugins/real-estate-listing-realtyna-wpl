<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.units');
_wpl_import('libraries.events');
_wpl_import('libraries.render');
_wpl_import('libraries.items');

/**
 * Property Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 05/26/2013
 */
class wpl_property
{
	/**
		@inputs [category], [kind] and [enabled]
		@return fields object
		@author Howard
	**/
	public static function get_pwizard_fields($category = '', $kind = 0, $enabled = 1)
	{
		return wpl_flex::get_fields($category, $enabled, $kind, 'pwizard', '1');
	}
	
	/**
	 * wpl_property::get_plisting_fields()
	 * Get listing fields
	 * 
	 * @param string $category
	 * @param integer $kind
	 * @param integer $enabled
	 * @return Array of Objects
	 * 
	 * @package WPL
	 * @author Ted@realtyna.com
	 * @since WPL 1.0
	 */
	public static function get_plisting_fields($category = '', $kind = 0, $enabled = 1)
	{
		return wpl_flex::get_fields($category, $enabled, $kind, 'plisting', '1');
	}
	
	/**
		@inputs [category], [kind] and [enabled]
		@return fields object
		@author Howard
	**/
	public static function get_pshow_fields($category = '', $kind = 0, $enabled = 1)
	{
		return wpl_flex::get_fields($category, $enabled, $kind, 'pshow', '1');
	}
	
	/**
		@inputs {user_id}, [temp]
		@return property id
		@author Howard
	**/
	public static function create_property_default($user_id = '', $kind = 0)
	{
		if(!$user_id) $user_id = wpl_users::get_cur_user_id();
		
        $fields = wpl_flex::get_fields('', 1, $kind);
	    list($query, $values) = self::generate_default_query($fields);
		
		/** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('create_default_property', array('query'=>$query, 'values'=>$values, 'user_id'=>$user_id, 'kind'=>$kind)));
		
       	$query = "INSERT INTO  `#__wpl_properties` (".(trim($query) != '' ? $query.", " : '')."`kind`, `user_id`, `finalized`, `add_date`, `mls_id`) VALUES (".(trim($values) != '' ? $values.", " : '')."'$kind', '$user_id', '0', NOW(), '".self::get_new_mls_id()."')";
		return wpl_db::q($query, 'insert');
    }
	
	/**
		@inputs {fields}
		@return array field and values query
		@author Howard
	**/
	public static function generate_default_query($fields)
	{
		$query = '';
		$values = '';

        $units4 = wpl_units::get_units(4);
        $units3 = wpl_units::get_units(3);
        $units2 = wpl_units::get_units(2);
        $units1 = wpl_units::get_units(1);

		/** To insert default values for measuring units **/
        foreach($fields as $field)
		{
            if($field->type == 'length' or $field->type == 'mmlength')
			{
                $query .= '`'.$field->table_column.'_unit`, ';
                $values .= "'".$units1[0]['id']."', ";
            }
            elseif($field->type == 'area' or $field->type == 'mmarea')
			{
                $query .= '`'.$field->table_column.'_unit`, ';
                $values .= "'".$units2[0]['id']."', ";
            }
            elseif($field->type == 'volume' or $field->type == 'mmvolume')
			{
                $query .= '`'.$field->table_column.'_unit`, ';
                $values .= "'".$units3[0]['id']."', ";
            }
            elseif($field->type == 'price' or $field->type == 'mmprice')
			{
                $query .= '`'.$field->table_column.'_unit`, ';
                $values .= "'".$units4[0]['id']."', ";
            }
        }
		
		return array(trim($query, ", "), trim($values, ", "));
	}
	
	/**
		@inputs {start}, {limit}, {orderby}, {order}, {where}
		@param int $start
		@param int $limit
		@param string $orderby
		@param string $order
		@param array $where
		@return void
		@description for start property model use this function for configuration
		@author Howard
	**/
	public function start($start, $limit, $orderby, $order, $where)
    {
		/** start time of model **/
		$this->start_time = microtime(true);
		
		/** pagination and order options **/
		$this->start = $start;
		$this->limit = $limit;
		$this->orderby = $orderby;
		$this->order = $order;
		
		/** listing fields **/
		$this->listing_fields = $this->get_plisting_fields();
		
		/** main table **/
		$this->main_table = "`#__wpl_properties` AS p";
		
		/** queries **/
		$this->join_query = $this->create_join();
		$this->groupby_query = $this->create_groupby();
		
		/** generate where condition **/
		$where = (array) $where;
		$vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		$vars = array_merge($vars, $where);
		
		$this->where = wpl_db::create_query($vars);
		
		/** generate select **/
		$this->select = $this->generate_select($this->listing_fields, 'p');
    }
	
	/**
		@inputs void
		@return string $quert
		@description this functions creates complete query
		@author Howard
	**/
	public function query()
    {
		$this->query  = " SELECT ".$this->select;
        $this->query .= " FROM ".$this->main_table;
        $this->query .= $this->join_query;
		$this->query .= " WHERE 1 ".$this->where;
		$this->query .= $this->groupby_query;
        $this->query .= " ORDER BY ".$this->orderby." ".$this->order;
        $this->query .= " LIMIT ".$this->start.", ".$this->limit;
		$this->query  = trim($this->query, ', ');
		
		return $this->query;
    }
	
	/** [TODO] **/
	public function create_join()
	{
		return '';
	}
	
	/** [TODO] **/
	public function create_groupby()
	{
		return '';
	}
	
	/**
		@inputs string $query
		@return object $properties
		@description use this function for running query and fetch the result
		@author Howard
	**/
	public function search($query = '')
    {
        if(!trim($query)) $query = $this->query;
		
        $properties = wpl_db::select($query);
        return $properties;
    }
	
	/**
		@inputs void
		@return int $time_taken
		@description this function is for calculating token time and total result
		@author Howard
	**/
	public function finish()
	{
		$this->finish_time = microtime(true);
        $this->time_taken = $this->finish_time - $this->start_time;
		$this->total = $this->get_properties_count();
		
		return $this->time_taken;
	}
	
	/**
		@input dbst fields
		@return select string
		@description this function generates select string for queries (like propertylisting)
		@author Howard
	**/
	public function generate_select($fields, $table_name = 'p')
	{
		/** first validation **/
		if(!$fields) return;
		
		/** get files **/
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'query_select';
		$files = array();
		$query = '';
		
		$defaults = array('id', 'kind', 'property_rank', 'pic_numb', 'att_numb', 'confirmed', 'finalized', 'deleted', 'user_id', 'add_date', 'visit_time', 'visit_date', 'sent_numb', 'contact_numb', 'zip_name', 'zip_id');
		foreach($defaults as $default)
		{
			$query .= $table_name.".`".$default."`, ";
		}
		
		if(wpl_folder::exists($path)) $files = wpl_folder::files($path, '.php$');
		
		foreach($fields as $key=>$field)
		{
			if(!$field) continue;
			if(trim($field->table_name) == '' or trim($field->table_column) == '') continue;
			
			$done_this = false;
			$type = $field->type;
			
			foreach($files as $file)
			{
				include($path .DS. $file);
				
				/** break and go to next field **/
				if($done_this) break;
			}
			
			if(!$done_this) $query .= $table_name.".`{$field->table_column}`, ";
		}
		
		return trim($query, ', ');
	}
	
	/**
		@inputs void
		@return new mls_id
		@author Howard
	**/
	public static function get_new_mls_id() 
	{
        $query = "SELECT max(cast(mls_id AS unsigned)) as mlsid FROM #__wpl_properties WHERE mls_id REGEXP '^[0-9]+$' LIMIT 1";
		$result = wpl_db::select($query, 'loadResult');
		
        if(!$result) $mls_id = 1000;
		else $mls_id = $result+1;
		
        return $mls_id;
    }
	
	/**
		@inputs {property_id} and [output_type]
		@return property_data
		@author Howard
	**/
	public static function get_property_raw_data($property_id, $output_type = 'loadAssoc')
	{
        $query = "SELECT * FROM `#__wpl_properties` WHERE `id`='$property_id'";
        return wpl_db::select($query, $output_type);
    }
    
	/**
		@inputs {property data}, [fields] and [finds]
		@param property data should be raw data from wpl_properties table
		@param fields should be an object of dbst fields
		@param finds detected files array
		@return rendered data
		@author Howard
	**/
	public static function render_property($property, $fields, &$finds = array(), $material = false)
	{
		$rendered = array();
		$materials = array();
        
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'dbst_show';
		$files = wpl_folder::files($path, '.php$', false, false, $finds);
		$values = (array) $property;
		
        $prp_listing = isset($values['listing']) ? $values['listing'] : NULL;
        $prp_property_type = isset($values['property_type']) ? $values['property_type'] : NULL;
        
		foreach($fields as $key=>$field)
		{
			if(trim($field->type) == '') continue;
			
            /** Take care for property type specific **/
            if(trim($field->property_type_specific) and $prp_property_type)
            {
                $ex = explode(',', $field->property_type_specific);
                if(!in_array($prp_property_type, $ex))
                {
                    $values[$field->table_column] = NULL;
                    continue;
                }
            }
            
            /** Take care for listing type specific **/
            if(trim($field->listing_specific) and $prp_listing)
            {
                $ex = explode(',', $field->listing_specific);
                if(!in_array($prp_listing, $ex))
                {
                    $values[$field->table_column] = NULL;
                    continue;
                }
            }
            
			$value = isset($values[$field->table_column]) ? $values[$field->table_column] : NULL;
			
			$done_this = false;
			$type = $field->type;
			$options = json_decode($field->options, true);
			$return = array();
			
			/** use detected files **/
			if(isset($finds[$type]))
			{
                include($path .DS. $finds[$type]);

                if(is_array($return) and count($return))
                {
                    $rendered[$field->id] = $return;
                    if($material and trim($field->table_column) != '') $materials[$field->table_column] = $return;
                }
                
                continue;
			}
            
			foreach($files as $file)
			{
				require $path.DS.$file;
				
				if($done_this == true)
				{
					/** set in detected files and proceed to nex field **/
					$finds[$type] = $file;
					break;
				}
			}
			
			if(is_array($return) and count($return))
            {
                $rendered[$field->id] = $return;
                if($material and trim($field->table_column) != '') $materials[$field->table_column] = $return;
            }
			
			if(!$done_this)
			{
				$rendered[$field->id] = array('field_id'=>$field->id, 'type'=>$field->type, 'name'=>__($field->name, WPL_TEXTDOMAIN), 'value'=>$value);
                if($material and trim($field->table_column) != '') $materials[$field->table_column] = array('field_id'=>$field->id, 'type'=>$field->type, 'name'=>__($field->name, WPL_TEXTDOMAIN), 'value'=>$value);
			}
		}
		
        /** returns rendered data by field ids and table columns **/
        if($material) return array('ids'=>$rendered, 'columns'=>$materials);
        
		return $rendered;
	}
    
    /**
     * Renders one dbst field
     * @author Howard R <howard@realtyna.com>
     * @param string $value
     * @param int $dbst_id
     * @return array rendered field
     */
    public static function render_field($value, $dbst_id)
    {
        /** first validation **/
        if(!$dbst_id) return array();
        
		$done_this = false;
        $return = array();
        
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'dbst_show';
		$files = wpl_folder::files($path, '.php$', false, false);
        $field = wpl_flex::get_field($dbst_id);
        
        $type = $field->type;
        $options = json_decode($field->options, true);
        
		foreach($files as $file)
        {
            require $path.DS.$file;
            if($done_this == true) break;
        }
		
		return $return;
    }
    
    /**
     * @return number of properties according to query condition
     * @author Francis
     */
    public function get_properties_count($condition = '')
    {
		$condition = trim($condition) != '' ? $condition : $this->where;
		
        $query = "SELECT COUNT(*) AS count FROM `#__wpl_properties` WHERE 1 ".$condition;
        return wpl_db::select($query, 'loadResult');
    }
	
	/**
		@inputs {property_id}, {mode} and [user_id]
		@description This function finalize property and triggering events
		@author Howard
	**/
	public static function finalize($pid, $mode, $user_id = '')
	{
        $property = self::get_property_raw_data($pid);
		
		$update_query = self::generate_finalize_query($property, $pid);
		$update_query .= "`finalized`='1',";
		if(wpl_global::check_access('confirm', $user_id)) $update_query .= "`confirmed`='1',";
		
		$update_query = trim($update_query, ', ');
		
		$query = "UPDATE `#__wpl_properties` SET ".$update_query." WHERE `id`='$pid'";
		wpl_db::q($query, 'update');
		
		wpl_property::update_text_search_field($pid);
        wpl_property::update_location_text_search_field($pid);
		wpl_property::update_alias($property, $pid);
		wpl_property::update_numbs($pid, $property);
		
		/** generate rendered data **/
		if(wpl_settings::get('cache')) wpl_property::generate_rendered_data($pid);
		
        /** throwing events **/
        if($mode == 'add') wpl_events::trigger('add_property', $pid);
        if($mode == 'edit') wpl_events::trigger('edit_property', $pid);
		
		if(wpl_global::check_access('confirm', $user_id)) wpl_events::trigger('property_confirm', $pid);
		
		return true;
    }
	
	/**
		@inputs {property_id}
		@description This function is for unfinalizing a property and triggering events
		@author Howard
	**/
	public static function unfinalize($pid)
	{
        $query = "UPDATE `#__wpl_properties` SET `finalized`='0' WHERE `id`='$pid'";
		wpl_db::q($query, 'update');
		
        /** throwing events **/
        wpl_events::trigger('property_unfinalized', $pid);
		
		return true;
    }
	
	/** This creates query for finalize property, user data, etc. **/
	public static function generate_finalize_query($data, $id = '')
	{
        $units = wpl_global::return_in_id_array(wpl_units::get_units('', 1));
        $query = '';
		
        foreach($data as $field=>$value)
		{
            if(!strstr($field, '_unit')) continue;
			
			$core_field = str_replace('_unit', '', $field);
            if(!array_key_exists($core_field.'_si', $data)) continue;
			
			$si_value = $units[$value]['tosi'] * $data[$core_field];
			$query .= "`".$core_field."_si`='".$si_value."',";

			if(isset($data[$core_field.'_max']))
			{
				$si_value = $units[$value]['tosi'] * $data[$core_field.'_max'];
				$query .= "`".$core_field."_max_si`='".$si_value."',";
			}
        }
		
		return $query;
	}
	
	/**
		@inputs {property_id}
		@description this function will generate rendered data of property and save them into db
		@author Howard
	**/
	public static function generate_rendered_data($pid)
	{
		_wpl_import('libraries.render');
		
		/** get property data **/
		$property_data = wpl_property::get_property_raw_data($pid);
		
		/** location text **/
		$location_text = self::generate_location_text($property_data);
        
        /** rendered data **/
        $find_files = array();
		$rendered_fields = self::render_property($property_data, self::get_plisting_fields('', $property_data['kind']), $find_files, true);
        
		$result = json_encode(array('rendered'=>$rendered_fields['ids'], 'materials'=>$rendered_fields['columns'], 'location_text'=>$location_text));
		$query = "UPDATE `#__wpl_properties` SET `rendered`='".wpl_db::escape($result)."', `location_text`='".wpl_db::escape($location_text)."' WHERE `id`='$pid'";
		
		/** update **/
		wpl_db::q($query, 'update');
	}
	
	/**
		@inputs {property_id}
		@description This function is for updating location text search field
		@author Howard
	**/
	public static function update_location_text_search_field($pid)
	{
        $property_data = wpl_property::get_property_raw_data($pid);
		$location_text = $property_data["location7_name"].", ".$property_data["location6_name"].", ".$property_data["location5_name"].", ".
						 $property_data["location4_name"].", ".$property_data["location3_name"].", ".$property_data["location2_name"].", ".$property_data["location1_name"];
						 
		$location_text = $property_data['zip_name'].", ".trim($location_text, ', ');
		wpl_db::set('wpl_properties', $pid, 'location_text', wpl_db::escape(trim($location_text, ', ')));
    }
	
	/**
		@inputs {property_id}
		@description This function is for updating pic_numbs, att_numbs and etc
		@author Howard
	**/
	public static function update_numbs($property_id, $property_data = NULL)
	{
		/** get property data if not provided **/
		if(!$property_data) $property_data = wpl_property::get_property_raw_data($property_id);
		
        $items = wpl_items::get_items($property_id, '', $property_data['kind'], '', 1);
		
		$pic_numb = isset($items['gallery']) ? count($items['gallery']) : 0;
		$att_numb = isset($items['attachment']) ? count($items['attachment']) : 0;
		
		$query = "UPDATE `#__wpl_properties` SET `pic_numb`='$pic_numb', `att_numb`='$att_numb' WHERE `id`='$property_id'";
		wpl_db::q($query);
    }
	
	/**
		@inputs {property_id}
		@description This function is for updating textsearch field
		@author Howard
	**/
	public static function update_text_search_field($pid)
	{
        $property_data = wpl_property::get_property_raw_data($pid);
		
		/** get text_search fields **/
		$fields = wpl_flex::get_fields('', 1, $property_data['kind'], 'text_search', '1');
		$rendered = self::render_property($property_data, $fields);
        
		$text_search_data = array();
		
		foreach($rendered as $data)
		{
			if(!isset($data['type'])) continue;
			if((isset($data['type']) and !trim($data['type'])) or (isset($data['value']) and !trim($data['value']))) continue;
			
			/** default value **/
			$value = isset($data['value']) ? $data['value'] : '';
			$value2 = '';
			$type = $data['type'];
			
			if($type == 'text' or $type == 'textarea')
			{
				$value = $data['name'] .' '. $data['value'];
			}
			elseif($type == 'neighborhood')
			{
				$value = $data['name'] .(isset($data['distance']) ? ' ('. $data['distance'] .' '. __('MINUTES', WPL_TEXTDOMAIN) .' '. __('BY', WPL_TEXTDOMAIN) .' '. $data['by'] .')' : '');
			}
			elseif($type == 'feature')
			{
				$feature_value = $data['name'];
				
				if(isset($data['values'][0]))
				{
					$feature_value .= ' ';
					
					foreach($data['values'] as $val) $feature_value .= $val .', ';
					$feature_value = rtrim($feature_value, ', ');
				}
				
				$value = $feature_value;
			}
			elseif($type == 'locations' and isset($data['locations']) and is_array($data['locations']))
			{
				$location_value = '';
				foreach($data['locations'] as $location_level=>$value)
				{
					$location_value .= $data['keywords'][$location_level] .' ';
					$location_value .= $value . ' ';
				}
                
				$value = $location_value;
			}
			elseif(isset($data['value']))
			{
				$value = $data['name'] .' '. $data['value'];
				if(is_numeric($data['value']))
				{
					$value2 = $data['name'] .' '. wpl_global::number_to_word($data['value']);
				}
			}
			
			/** set value in text search data **/
			if(trim($value) != '') $text_search_data[] = strip_tags($value);
			if(trim($value2) != '') $text_search_data[] = strip_tags($value2);
		}
        
		wpl_db::set('wpl_properties', $pid, 'textsearch', wpl_db::escape(implode(' ', $text_search_data)));
    }
	
	/**
		@inputs [property_data] and [property_id] and [target_id]
		@return property_show full link
		@author Howard
	**/
	public static function get_property_link($property_data, $property_id = 0, $target_id = 0)
	{
		/** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
		if(!$property_id) $property_id = $property_data['id'];
        
        $url = wpl_sef::get_wpl_permalink(true);
        
		if(trim($property_data['alias']) != '') $alias = urlencode($property_data['alias']);
		else $alias = urlencode(self::update_alias($property_data, $property_id));
		
        $home_type = wpl_global::get_wp_option('show_on_front', 'posts');
        $home_id = wpl_global::get_wp_option('page_on_front', 0);
        
		if(!$target_id) $target_id = wpl_request::getVar('wpltarget', 0);
		if($target_id)
        {
            $url = wpl_global::add_qs_var('pid', $property_id, wpl_sef::get_page_link($target_id));
            $url = wpl_global::add_qs_var('alias', $alias, $url);
            
            if($home_type == 'page' and $home_id == $target_id) $url = wpl_global::add_qs_var('wplview', 'property_show', $url);
        }
		else
        {
            $nosef = wpl_sef::is_permalink_default();
            $wpl_main_page_id = wpl_sef::get_wpl_main_page_id();
            
            if($nosef or ($home_type == 'page' and $home_id == $wpl_main_page_id))
            {
                $url = wpl_global::add_qs_var('wplview', 'property_show', $url);
                $url = wpl_global::add_qs_var('pid', $property_id, $url);
                $url = wpl_global::add_qs_var('alias', $alias, $url);
            }
            else
            {
                $url .= $alias;
            }
        }
		
        return $url;
    }
    
    /**
		@inputs {property_id} and [target_id]
		@return pdf full link
		@author Howard
	**/
	public static function get_property_pdf_link($property_id, $target_id = 0)
	{
        /** first validation **/
        if(!trim($property_id)) return false;
        
        $nosef = wpl_sef::is_permalink_default();
        
        if($nosef)
        {
            $url = wpl_sef::get_wpl_permalink(true);
            $url = wpl_global::add_qs_var('wplview', 'features', $url);
            $url = wpl_global::add_qs_var('wpltype', 'pdf', $url);
            $url = wpl_global::add_qs_var('pid', $property_id, $url);
        }
        else $url = wpl_sef::get_wpl_permalink(true).'features/pdf?pid='.$property_id;
        
        return $url;
    }
	
	/**
		@inputs void
		@return property_listing full link
		@author Howard
	**/
	public static function get_property_listing_link($target_id = 0)
	{
        if($target_id) $url = wpl_sef::get_page_link($target_id);
        else $url = wpl_sef::get_wpl_permalink(true);
        
        return $url;
    }
	
	/**
		@inputs {property_id}
		@return string location_text
		@author Howard
	**/
	public static function generate_location_text($property_data, $property_id = 0, $glue = ',')
	{
		/** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
        
		$locations = array();
        
		if(isset($property_data['street_no']) and trim($property_data['street_no']) != '') $locations['street_no'] = $property_data['street_no'];
        if(isset($property_data['field_42']) and trim($property_data['field_42']) != '') $locations['street'] = $property_data['field_42'];
        if(isset($property_data['location4_name']) and trim($property_data['location4_name']) != '') $locations['location4_name'] = $property_data['location4_name'];
        if(isset($property_data['location3_name']) and trim($property_data['location3_name']) != '') $locations['location3_name'] = $property_data['location3_name'];
        if(isset($property_data['location2_name']) and trim($property_data['location2_name']) != '') $locations['location2_name'] = $property_data['location2_name'];
        if(isset($property_data['zip_name']) and trim($property_data['zip_name']) != '') $locations['zip_name'] = $property_data['zip_name'];
        if(isset($property_data['location1_name']) and trim($property_data['location1_name']) != '') $locations['location1_name'] = $property_data['location1_name'];
        
        $location_pattern = wpl_global::get_setting('property_location_pattern');
        if(trim($location_pattern) == '') $location_pattern = '[street_no] [street][glue] [location4_name][glue] [location3_name][glue] [location2_name][glue] [location1_name] [zip_name]';
        
		$location_text = '';
		$location_text = isset($locations['street_no']) ? str_replace('[street_no]', $locations['street_no'], $location_pattern) : str_replace('[street_no]', '', $location_pattern);
        $location_text = isset($locations['street']) ? str_replace('[street]', $locations['street'], $location_text) : str_replace('[street]', '', $location_text);
        $location_text = isset($locations['location4_name']) ? str_replace('[location4_name]', $locations['location4_name'], $location_text) : str_replace('[location4_name]', '', $location_text);
        $location_text = isset($locations['location3_name']) ? str_replace('[location3_name]', $locations['location3_name'], $location_text) : str_replace('[location3_name]', '', $location_text);
        $location_text = isset($locations['location2_name']) ? str_replace('[location2_name]', $locations['location2_name'], $location_text) : str_replace('[location2_name]', '', $location_text);
        $location_text = isset($locations['zip_name']) ? str_replace('[zip_name]', $locations['zip_name'], $location_text) : str_replace('[zip_name]', '', $location_text);
        $location_text = isset($locations['location1_name']) ? str_replace('[location1_name]', $locations['location1_name'], $location_text) : str_replace('[location1_name]', '', $location_text);
        $location_text = str_replace('[glue]', $glue, $location_text);
        
        $final = '';
        $ex = explode($glue, $location_text);
        
        foreach($ex as $value)
        {
            if(trim($value) == '') continue;
            
            $final .= trim($value).$glue.' ';
        }
		
		return trim($final, ', ');
    }
	
	/**
		@inputs [property_data], [property_id]
		@return string property_alias
		@author Howard
	**/
	public static function update_alias($property_data, $property_id = 0)
	{
		/** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
		
		$alias = array();
		$alias['id'] = $property_data['id'];
		if(trim($property_data['property_type'])) $alias['property_type'] = __(wpl_global::get_property_types($property_data['property_type'])->name, WPL_TEXTDOMAIN);
		if(trim($property_data['listing'])) $alias['listing'] = __(wpl_global::get_listings($property_data['listing'])->name, WPL_TEXTDOMAIN);
		$alias['location'] = self::generate_location_text($property_data, $property_id, '-');
		
		if(trim($property_data['rooms'])) $alias['rooms'] = $property_data['rooms'].__('Room'.($property_data['rooms'] > 1 ? 's': ''), WPL_TEXTDOMAIN);
		if(trim($property_data['bedrooms'])) $alias['bedrooms'] = $property_data['bedrooms'].__('Bedroom'.($property_data['bedrooms'] > 1 ? 's': ''), WPL_TEXTDOMAIN);
		if(trim($property_data['bathrooms'])) $alias['bathrooms'] = $property_data['bathrooms'].__('Bathroom'.($property_data['bathrooms'] > 1 ? 's': ''), WPL_TEXTDOMAIN);
		
		$unit_data = wpl_units::get_unit($property_data['price_unit']);
		$alias['price'] = $property_data['price'].$unit_data['extra'];
		$alias_str = implode('-', $alias);
		
		/** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('generate_property_alias', array('alias'=>$alias, 'alias_str'=>$alias_str)));
		
		/** escape **/
		$alias_str = wpl_db::escape(wpl_global::url_encode($alias_str));
		
		/** update **/
		$query = "UPDATE `#__wpl_properties` SET `alias`='".$alias_str."' WHERE `id`='".$property_data['id']."'";
		wpl_db::q($query, 'update');

		return $alias_str;
    }
	
	/**
		@inputs [property_data], [property_id]
		@return string property_alias
		@author Howard
	**/
	public static function generate_property_title($property_data, $property_id = 0)
	{
		/** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
        
        /** firstvalidation **/
		if(!$property_data) return '';
        
		$listing = wpl_global::get_listings($property_data['listing'])->name;
		$property_type = wpl_global::get_property_types($property_data['property_type'])->name;
		
		$title = array();
		$title['property_type'] = __($property_type, WPL_TEXTDOMAIN);
		$title['listing'] = __($listing, WPL_TEXTDOMAIN);
		
		if(trim($property_data['rooms'])) $title['rooms'] = $property_data['rooms'].__('Room'.($property_data['rooms'] > 1 ? 's': ''), WPL_TEXTDOMAIN);
		if(trim($property_data['bedrooms'])) $title['bedrooms'] = $property_data['bedrooms'].__('Bedroom'.($property_data['bedrooms'] > 1 ? 's': ''), WPL_TEXTDOMAIN);
		if(trim($property_data['bathrooms'])) $title['bathrooms'] = $property_data['bathrooms'].__('Bathroom'.($property_data['bathrooms'] > 1 ? 's': ''), WPL_TEXTDOMAIN);
		
		$title['price'] = __('Price', WPL_TEXTDOMAIN).' '.wpl_render::render_price($property_data['price'], $property_data['price_unit']);
		$title['id'] = $property_data['mls_id'];
		
		$title_str = implode(' - ', $title);
		
		/** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('generate_property_title', array('title'=>$title, 'title_str'=>$title_str)));

		return $title_str;
    }
	
	/**
		@inputs {property_id}
		@return void
		@author Howard
	**/
	public static function property_visited($property_id)
	{
		/** first validation **/
		if(!trim($property_id)) return;
		
		$current_user_id = wpl_users::get_cur_user_id();
		$property_user = wpl_property::get_property_user($property_id);
		
		/** checking if the property user himself viewing not adding the counter **/
		if($current_user_id != $property_user)
		{
			$query = "UPDATE `#__wpl_properties` SET `visit_time`=visit_time+1, `visit_date`=NOW() WHERE `id`='$property_id'";
			wpl_db::q($query, 'update');
		}
	}
	
	/**
		@inputs {field_name} and {property_id}
		@return property user_id
		@author Howard
	**/
	public static function get_property_field($field_name, $property_id)
	{
		return wpl_db::get($field_name, 'wpl_properties', 'id', $property_id);
	}
	
	/**
		@inputs {property_id}
		@return property user_id
		@author Howard
	**/
	public static function get_property_user($property_id)
	{
		return self::get_property_field('user_id', $property_id);
	}
	
	/**
		@inputs {property_id}
		@return property kind
		@author Howard
	**/
	public static function get_property_kind($property_id)
	{
		return self::get_property_field('kind', $property_id);
	}
	
	/**
		@inputs {property_id}, {status} and [trigger_event]
		@return boolean
		@description Use this function for deleting property
		@author Howard
	**/
	public static function delete($property_id, $status = 1, $trigger_event = true)
	{
		/** first validation **/
		if(!trim($property_id) or !in_array($status, array(0, 1))) return false;
		
        wpl_db::update('wpl_properties', array('deleted'=>$status), 'id', $property_id);
		
		/** trigger event **/
		if($trigger_event and $status == 1) wpl_global::event_handler('property_deleted', array('property_id'=>$property_id, 'status'=>$status));
		elseif($trigger_event and $status == 0) wpl_global::event_handler('property_restored', array('property_id'=>$property_id, 'status'=>$status));
		
		return true;
	}
	
	/**
		@inputs {property_id}, {status} and [trigger_event]
		@return boolean
		@description Use this function for confirming property
		@author Howard
	**/
	public static function confirm($property_id, $status = 1, $trigger_event = true)
	{
		/** first validation **/
		if(!trim($property_id) or !in_array($status, array(0, 1))) return false;
		
        wpl_db::update('wpl_properties', array('confirmed'=>$status), 'id', $property_id);
		
		/** trigger event **/
		if($trigger_event and $status == 1) wpl_global::event_handler('property_confirmed', array('property_id'=>$property_id, 'status'=>$status));
		elseif($trigger_event and $status == 0) wpl_global::event_handler('property_unconfirmed', array('property_id'=>$property_id, 'status'=>$status));
		
		return true;
	}
	
	/**
		@inputs {property_id} and [trigger_event]
		@return boolean
		@description Use this function for purging property completely
		@author Howard
	**/
	public static function purge($property_id, $trigger_event = true)
	{
		/** first validation **/
		if(!trim($property_id)) return false;
		
		$property_data = self::get_property_raw_data($property_id);
		
		/** trigger event **/
		if($trigger_event) wpl_global::event_handler('property_before_purge', array('property_id'=>$property_id, 'property_data'=>$property_data));
		
		/** purging property related data **/
        _wpl_import('libraries.items');
		wpl_items::delete_all_items($property_id, $property_data['kind']);
		
		/** purging property record **/
        wpl_db::delete('wpl_properties', $property_id);
		
		/** purging property folder **/
        wpl_folder::delete(wpl_items::get_path($property_id));
		
		/** trigger event **/
		if($trigger_event) wpl_global::event_handler('property_purged', array('property_id'=>$property_id, 'property_data'=>$property_data));
		
		return true;
	}
    
    /**
		@inputs {property_id} and {user_id}
		@return boolean
		@description Use this function for changing the user of property
		@author Howard
	**/
	public static function change_user($property_id, $user_id)
	{
		/** first validation **/
		if(!trim($property_id) or !trim($user_id)) return false;
		
		/** running the query **/
        wpl_db::q("UPDATE `#__wpl_properties` SET `user_id`='$user_id' WHERE `id`='$property_id'");
        
		/** trigger event **/
		wpl_global::event_handler('property_user_changed', array('property_id'=>$property_id, 'user_id'=>$user_id));
		
		return true;
	}
	
	/**
		@inputs [extra_condition], [output], [limit], [order] and [select]
		@return boolean
		@description Use this function for purging property completely
		@author Howard
	**/
	public static function select_active_properties($extra_condition = '', $select = '*', $output = 'loadAssocList', $limit = 0, $order = '`id` ASC')
	{
		$condition = " AND `deleted`='0' AND `finalized`='1' AND `confirmed`='1'";
		if(trim($extra_condition) != '') $condition .= $extra_condition;
		
		$query = "SELECT ".$select." FROM `#__wpl_properties` WHERE 1 ".$condition." ORDER BY $order ".($limit ? "LIMIT $limit" : '');
		
		$results = wpl_db::select($query, $output);
		return $results;
	}
	
	/**
		@inputs [params]
		@return array or html
		@description Use this function for generating sort options
		@author Howard
	**/
	public function generate_sorts($params = array())
	{
		include _wpl_import('views.basics.sorts.property_listing', true, true);
		return $result;
	}
	
	/**
		@inputs {property_id}, [plisting_fields], [property] and [params]
		@return array full render of property
		@description This is a very useful function for rendering whole data of property. you need to just pass property_id and get everything!
		@author Howard
	**/
	public static function full_render($property_id, $plisting_fields = NULL, $property = NULL, $params = array())
	{
		/** get plisting fields **/
		if(!$plisting_fields) $plisting_fields = self::get_plisting_fields();
		
		$raw_data = self::get_property_raw_data($property_id);
		if(!$property) $property = (object) $raw_data;
		
		$rendered = json_decode($raw_data['rendered'], true);
		$result = array();
		
		$result['data'] = (array) $property;
		
		/** render data **/
        $find_files = array();
        $rendered_fields = self::render_property($property, $plisting_fields, $find_files, true);
        
		if($rendered['rendered']) $result['rendered'] = $rendered['rendered'];
		else $result['rendered'] = $rendered_fields['ids'];
        
        if(isset($rendered['materials']) and $rendered['materials']) $result['materials'] = $rendered['materials'];
		else $result['materials'] = $rendered_fields['columns'];
		
		$result['items'] = wpl_items::get_items($property_id, '', $property->kind, '', 1);
		$result['raw'] = $raw_data;
		
		/** location text **/
		if($rendered['location_text']) $result['location_text'] = $rendered['location_text'];
		else $result['location_text'] = self::generate_location_text($raw_data);
		
		/** property full link **/
        $target_page = isset($params['wpltarget']) ? $params['wpltarget'] : 0;
		$result['property_link'] = self::get_property_link($raw_data, NULL, $target_page);
		
		return $result;
	}
	
	/**
		@inputs [listing_id]
		@return string pid
		@description Use this function for converting listing id (mls_id) to pid
		@author Howard
	**/
	public static function pid($value, $key = 'mls_id')
	{
		return wpl_db::get('id', 'wpl_properties', $key, $value);
	}
    
    /**
     * Returns mls_id column of properties table
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param mixed $value
     * @param string $key
     * @return string $listing_id
     */
    public static function listing_id($value, $key = 'id')
	{
		return wpl_db::get('mls_id', 'wpl_properties', $key, $value);
	}
	
	/**
		@input WPL structural data, wpl_unique_field
		@return property ids
		@author Howard
	**/
	public static function import($properties_to_import, $wpl_unique_field = 'mls_id', $user_id = '', $source = 'mls', $finalize = true)
	{
		if(!$user_id) $user_id = wpl_users::get_cur_user_id();
		$pids = array();
		$possible_columns = wpl_db::columns('wpl_properties');
		
		foreach($properties_to_import as $property_to_import)
		{
			$q = '';
			$unique_value = '';
			
			foreach($property_to_import as $key=>$row)
			{
				$wpl_field = $row['wpl_table_column'] ? $row['wpl_table_column'] : $key;
				$wpl_value = $row['wpl_value'] ? $row['wpl_value'] : '';
				
				/** validation table column **/
				if(!in_array($wpl_field, $possible_columns)) continue;
				
				/** set unique value **/
				if($wpl_field == $wpl_unique_field) $unique_value = $wpl_value;
				
				/** set user id value **/
				if($wpl_field == 'user_id') $user_id = $wpl_value;
				
				$q .= "`$wpl_field`='".wpl_db::escape($wpl_value)."',";
			}
            
			$exists = wpl_property::get_properties_count(" AND `$wpl_unique_field`='$unique_value'");
			if(!$exists) $pid = wpl_property::create_property_default($user_id);
			else $pid = wpl_property::pid($unique_value, $wpl_unique_field);
			
			/** add property id to return **/
			$pids[] = $pid;
			
			$q = trim($q, ', ');
			$query = "UPDATE `#__wpl_properties` SET ".$q." WHERE `id`='".$pid."'";
			wpl_db::q($query);
			
			if($finalize)
			{
				$mode = $exists ? 'edit' : 'add';
				wpl_property::finalize($pid, $mode, $user_id);
			}
		}
		
		return $pids;
	}
	
	/**
		@inputs [property_id]
		@return property_edit full link
		@author Howard
	**/
	public static function get_property_edit_link($property_id = 0)
	{
		/** first validation **/
		if(!$property_id) return false;
		
		$target_id = wpl_request::getVar('wpltarget', 0);
		
		if($target_id) $url = wpl_global::add_qs_var('pid', $property_id, wpl_sef::get_page_link($target_id));
		else $url = wpl_global::add_qs_var('pid', $property_id, wpl_global::get_wpl_admin_menu('wpl_admin_add_listing'));
		
        return $url;
    }
    
    /**
     * Get property ids for a criteria
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $column
     * @param mixed $value
     * @return type
     */
	public static function get_properties_list($column, $value)
	{
		$query = "SELECT `id` FROM `#__wpl_properties` WHERE `$column`='$value'";
		return wpl_db::select($query, 'loadAssocList');
    }
    
    /**
     * Updates properties and regenerate some of cached property data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $column
     * @param mixed $previous_value
     * @param mixed $new_value
     * @return boolean
     */
	public static function update_properties($column, $previous_value, $new_value)
	{
		$listings = wpl_property::get_properties_list($column, $previous_value);
		$query = "UPDATE `#__wpl_properties` SET `$column`='$new_value' WHERE `$column`='$previous_value'";
		$result = wpl_db::q($query);
        
		foreach($listings as $listing)
		{
			$pid = $listing['id'];
			$property = self::get_property_raw_data($pid);
            
			wpl_property::update_text_search_field($pid);
			wpl_property::update_location_text_search_field($pid);
			wpl_property::update_alias($property, $pid);
			wpl_property::update_numbs($pid, $property);
            
			/** generate rendered data **/
			if(wpl_settings::get('cache')) wpl_property::generate_rendered_data($pid);
		}
        
		return $result;
    }
    
    /**
     * Removes property thumbnails
     * @author Howard R <Howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param int $kind
     * @return boolean
     */
    public static function remove_thumbnails($property_id, $kind = 0)
    {
        /** first validation **/
        if(!trim($property_id)) return false;
        
        $ext_array = array('jpg', 'jpeg', 'gif', 'png');
        
        $path = wpl_items::get_path($property_id, $kind);
        $thumbnails = wpl_folder::files($path, 'th.*\.('.implode('|', $ext_array).')$', 3, true);

        foreach($thumbnails as $thumbnail)
        {
            wpl_file::delete($thumbnail);
        }
        
        return true;
    }
}