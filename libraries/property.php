<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.units');
_wpl_import('libraries.events');
_wpl_import('libraries.render');
_wpl_import('libraries.items');
_wpl_import('libraries.locations');
_wpl_import('libraries.property_types');
_wpl_import('libraries.listing_types');

/**
 * Property Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @package WPL
 * @date 05/26/2013
 */
class wpl_property
{
    /**
     * Returns property wizard fields
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int|string $category
     * @param int $kind
     * @param int $enabled
     * @return array of objects
     */
	public static function get_pwizard_fields($category = '', $kind = 0, $enabled = 1)
	{
		return wpl_flex::get_fields($category, $enabled, $kind, 'pwizard', '1');
	}
    
    /**
     * Returns property listing fields
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int|string $category
     * @param int $kind
     * @param int $enabled
     * @return array of objects
     */
	public static function get_plisting_fields($category = '', $kind = 0, $enabled = 1)
	{
		return wpl_flex::get_fields($category, $enabled, $kind, 'plisting', '1');
	}
	
    /**
     * Returns property show fields
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int|string $category
     * @param int $kind
     * @param int $enabled
     * @return array of objects
     */
	public static function get_pshow_fields($category = '', $kind = 0, $enabled = 1)
	{
		return wpl_flex::get_fields($category, $enabled, $kind, 'pshow', '1');
	}
    
    /**
     * Returns property PDF fields
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int|string $category
     * @param int $kind
     * @param int $enabled
     * @return array of objects
     */
    public static function get_pdf_fields($category = '', $kind = 0, $enabled = 1)
	{
		return wpl_flex::get_fields($category, $enabled, $kind, 'pdf', '1');
	}
    
    /**
     * Creates default property for listing wizard etc pages
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $user_id
     * @param int $kind
     * @return int Created property ID
     */
	public static function create_property_default($user_id = '', $kind = 0)
	{
		if(!$user_id) $user_id = wpl_users::get_cur_user_id();
		
        $fields = wpl_flex::get_fields('', 1, $kind);
	    list($query, $values) = self::generate_default_query($fields);
		
		/** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('create_default_property', array('query'=>$query, 'values'=>$values, 'user_id'=>$user_id, 'kind'=>$kind)));
		
       	$query = "INSERT INTO  `#__wpl_properties` (".(trim($query) != '' ? $query.", " : '')."`kind`, `user_id`, `finalized`, `add_date`, `mls_id`) VALUES (".(trim($values) != '' ? $values.", " : '')."'$kind', '$user_id', '0', '".date("Y-m-d H:i:s")."', '".self::get_new_mls_id()."')";
        return wpl_db::q($query, 'insert');
    }
	
    /**
     * Generates default query for creating default property
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array of objects $fields
     * @return array
     */
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
		
        /** Add default value for geopoints column **/
        if(wpl_global::check_addon('aps'))
        {
            $query .= '`geopoints`, ';
            $values .= "Point(0,0), ";
        }
        
		return array(trim($query, ', '), trim($values, ', '));
	}
    
    /**
     * Starts search command
     * @author Howard R <howard@realtyna.com>
     * @param int $start
     * @param int $limit
     * @param string $orderby
     * @param string $order
     * @param array $where
     * @param int $kind
     */
	public function start($start, $limit, $orderby, $order, $where, $kind = 0)
    {
		/** start time of model **/
		$this->start_time = microtime(true);
		
        if(in_array($orderby, array('p.mls_id+0', 'p.mls_id'))) $orderby = 'cast(p.mls_id as unsigned)';
        
		/** pagination and order options **/
		$this->start = $start;
		$this->limit = $limit;
		$this->orderby = $orderby;
		$this->order = $order;
        $this->kind = $kind;
		
		/** listing fields **/
		$this->listing_fields = $this->get_plisting_fields('', $this->kind);
		
		/** main table **/
		$this->main_table = "`#__wpl_properties` AS p";
		
		/** queries **/
		$this->join_query = $this->create_join();
		$this->groupby_query = $this->create_groupby();
		
		/** generate where condition **/
		$where = (array) $where;
		$this->where = wpl_db::create_query($where);
		
		/** generate select **/
		$this->select = isset($this->select) ? $this->select : $this->generate_select($this->listing_fields, 'p');
    }
	
    /**
     * Creates complete query for searching
     * @author Howard R <howard@realtyna.com>
     * @return string
     */
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
    
    /**
     * @author Howard R <howard@realtyna.com>
     * @todo
     * @return string
     */
	public function create_join()
	{
		return '';
	}
    
    /**
     * @author Howard R <howard@realtyna.com>
     * @todo
     * @return string
     */
	public function create_groupby()
	{
		return '';
	}
	
    /**
     * Searches on properties
     * @author Howard R <howard@realtyna.com>
     * @param string $query
     * @return array of objects
     */
	public function search($query = '')
    {
        if(!trim($query)) $query = $this->query;
		
        return wpl_db::select($query);
    }
	
    /**
     * Calculates token time and results count
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return int
     */
	public function finish()
	{
		$this->finish_time = microtime(true);
        $this->time_taken = $this->finish_time - $this->start_time;
		$this->total = $this->get_properties_count();
		
        if($this->orderby == 'cast(p.mls_id as unsigned)') $this->orderby = 'p.mls_id';
        
		return $this->time_taken;
	}
	
    /**
     * Generates select cluase of search query
     * @author Howard R <howard@realtyna.com>
     * @param array of objects $fields
     * @param string $table_name
     * @return string
     */
	public function generate_select($fields, $table_name = 'p')
	{
		/** first validation **/
		if(!$fields) return;
		
		/** get files **/
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'query_select';
		$files = array();
		$query = '';
		
		$defaults = array('id', 'kind', 'pic_numb', 'att_numb', 'confirmed', 'finalized', 'deleted', 'user_id', 'add_date', 'visit_time', 'visit_date', 'sent_numb', 'contact_numb', 'zip_name', 'zip_id');
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
     * Returns new unique id for mls_id column of properties table
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return int
     */
	public static function get_new_mls_id()
	{
        $query = "SELECT MAX(cast(mls_id AS unsigned)) as max_id FROM #__wpl_properties WHERE mls_id REGEXP '^[0-9]+$' LIMIT 1";
		$result = wpl_db::select($query, 'loadResult');
		
        if(!$result) $mls_id = 1000;
		else $mls_id = $result+1;
		
        return $mls_id;
    }
	
    /**
     * Get raw data of a listing
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param string $output_type
     * @return mixed
     */
	public static function get_property_raw_data($property_id, $output_type = 'loadAssoc')
	{
        $query = "SELECT * FROM `#__wpl_properties` WHERE `id`='$property_id'";
        return wpl_db::select($query, $output_type);
    }
    
    /**
     * Renders Property data (And User data and other entities)
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $property
     * @param array of objects $fields
     * @param array $finds
     * @param boolean $material
     * @return array
     */
	public static function render_property($property, $fields, &$finds = array(), $material = false)
	{
		$rendered = array();
		$materials = array();
        
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'dbst_show';
		$files = wpl_folder::files($path, '.php$', false, false);
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
            
            /** Accesses **/
			if(isset($field->accesses) and trim($field->accesses) != '' and wpl_global::check_addon('membership'))
			{
				$accesses = explode(',', trim($field->accesses, ', '));
                $cur_membership_id = wpl_users::get_user_membership();
                
				if(!in_array($cur_membership_id, $accesses) and trim($field->accesses_message) == '') continue;
                elseif(!in_array($cur_membership_id, $accesses) and trim($field->accesses_message) != '')
                {
                    $rendered[$field->id] = array('field_id'=>$field->id, 'type'=>$field->type, 'name'=>__($field->name, WPL_TEXTDOMAIN), 'value'=>__($field->accesses_message, WPL_TEXTDOMAIN));
                    if($material and trim($field->table_column) != '') $materials[$field->table_column] = array('field_id'=>$field->id, 'type'=>$field->type, 'name'=>__($field->name, WPL_TEXTDOMAIN), 'value'=>__($field->accesses_message, WPL_TEXTDOMAIN));

                    continue;
                }
			}
            
			$value = isset($values[$field->table_column]) ? stripslashes($values[$field->table_column]) : NULL;
            
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
        $value = stripslashes($value);
        
        $type = $field->type;
        $options = json_decode($field->options, true);
        
		foreach($files as $file)
        {
            require $path.DS.$file;
            if($done_this == true) break;
        }
        
        /** Accesses **/
        if(trim($field->accesses) != '')
        {
            $accesses = explode(',', trim($field->accesses, ', '));
            $cur_membership_id = wpl_users::get_user_membership();

            if(!in_array($cur_membership_id, $accesses) and trim($field->accesses_message) == '') return array();
            elseif(!in_array($cur_membership_id, $accesses) and trim($field->accesses_message) != '')
            {
                $return = array('field_id'=>$field->id, 'type'=>$field->type, 'name'=>__($field->name, WPL_TEXTDOMAIN), 'value'=>__($field->accesses_message, WPL_TEXTDOMAIN));
            }
        }
		
		return $return;
    }
    
    /**
     * Render Google markers
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $wpl_properties
     * @return array
     */
    public static function render_markers($wpl_properties)
    {
        $listings = wpl_global::return_in_id_array(wpl_global::get_listings());
        $markers = array();
        $geo_points = array();
        $rendered = array();
        
        $i = 0;
        foreach($wpl_properties as $key=>$property)
        {
            if($key == 'current' and !count($property)) continue;
            
            /** skip to next if address is hidden **/
            if(!$property['raw']['show_address']) continue;
            
            /** if property already rendered **/
            if(in_array($property['raw']['id'], $rendered)) continue;
            array_push($rendered, $property['raw']['id']);
            
            /** Fetch latitude and longitude if it's not set **/
            if(!$property['raw']['googlemap_lt'] or !$property['raw']['googlemap_ln'])
            {
                $LatLng = wpl_locations::update_LatLng(NULL, $property['raw']['id']);

                $property['raw']['googlemap_lt'] = $LatLng[0];
                $property['raw']['googlemap_ln'] = $LatLng[1];
            }
            
            /** Create multiple marker **/
            if(isset($geo_points[$property['raw']['googlemap_lt'].','.$property['raw']['googlemap_ln']]))
            {
                $j = $geo_points[$property['raw']['googlemap_lt'].','.$property['raw']['googlemap_ln']];
                $markers[$j]['pids'] .= ','.$property['raw']['id'];
                $markers[$j]['gmap_icon'] = 'multiple.png';
            
                continue;
            }
            
            $markers[$i]['id'] = $property['raw']['id'];
            $markers[$i]['googlemap_lt'] = $property['raw']['googlemap_lt'];
            $markers[$i]['googlemap_ln'] = $property['raw']['googlemap_ln'];
            $markers[$i]['title'] = NULL;

            $markers[$i]['pids'] = $property['raw']['id'];
            $markers[$i]['gmap_icon'] = (isset($listings[$property['raw']['listing']]['gicon']) and $listings[$property['raw']['listing']]['gicon']) ? $listings[$property['raw']['listing']]['gicon'] : 'default.png';
            
            $geo_points[$property['raw']['googlemap_lt'].','.$property['raw']['googlemap_ln']] = $i;
            
            $i++;
        }
        
        /** apply filters **/
        _wpl_import('libraries.filters');
		@extract(wpl_filters::apply('render_property_markers', array('wpl_properties'=>$wpl_properties, 'markers'=>$markers)));
        
        return $markers;
    }
    
    /**
     * Returns number of properties according to query condition
     * @author Francis <francis@realtyna.com>
     * @param type $condition
     * @return type
     */
    public function get_properties_count($condition = '')
    {
		$condition = trim($condition) != '' ? $condition : $this->where;
		
        $query = "SELECT COUNT(*) AS count FROM `#__wpl_properties` WHERE 1 ".$condition;
        return wpl_db::select($query, 'loadResult');
    }
	
    /**
     * Finalize a property and render needed data
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param string $mode
     * @param int $user_id
     * @return boolean
     */
	public static function finalize($property_id, $mode = 'edit', $user_id = '')
	{
        $property = self::get_property_raw_data($property_id);
		
		$update_query = self::generate_finalize_query($property, $property_id);
		$update_query .= "`finalized`='1',";
		if(wpl_global::check_access('confirm', $user_id)) $update_query .= "`confirmed`='1',";
		
		$update_query = trim($update_query, ', ');
		
		$query = "UPDATE `#__wpl_properties` SET ".$update_query." WHERE `id`='$property_id'";
		wpl_db::q($query, 'update');
        
        /** Remove Property Cache **/
        wpl_property::clear_property_cache($property_id);
        
        /** Multilingual **/
        if(wpl_global::check_multilingual_status())
        {
            $languages = wpl_addon_pro::get_wpl_languages();
            $current_language = wpl_global::get_current_language();
            
            foreach($languages as $language)
            {
                if(wpl_global::switch_language($language))
                {
                    wpl_property::update_text_search_field($property_id);
                    wpl_property::update_alias($property);
                    wpl_property::update_property_page_title($property);
                    wpl_property::update_property_title($property);

                    /** generate rendered data **/
                    wpl_property::generate_rendered_data($property_id);
                }
            }
            
            /** Switch to current language again **/
            wpl_global::switch_language($current_language);
        }
        else
        {
            wpl_property::update_text_search_field($property_id);
            wpl_property::update_alias($property);
            wpl_property::update_property_page_title($property);
            wpl_property::update_property_title($property);

            /** generate rendered data **/
            wpl_property::generate_rendered_data($property_id);
        }
        
        /** Fixes **/
        wpl_property::fix_aliases($property, $property_id);
        wpl_property::update_numbs($property_id, $property);
		
        /** throwing events **/
        if($mode == 'add') wpl_events::trigger('add_property', $property_id);
        elseif($mode == 'edit') wpl_events::trigger('edit_property', $property_id);
		
        /** Finalize Event (Run on both of Add and Edit mode) **/
        wpl_events::trigger('property_finalized', $property_id);
        
		if(wpl_global::check_access('confirm', $user_id)) wpl_events::trigger('property_confirm', $property_id);
		return true;
    }
	
    /**
     * Unfinalize a property
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @return boolean
     */
	public static function unfinalize($property_id)
	{
        $query = "UPDATE `#__wpl_properties` SET `finalized`='0' WHERE `id`='$property_id'";
		wpl_db::q($query, 'update');
		
        /** throwing events **/
        wpl_events::trigger('property_unfinalized', $property_id);
		
		return true;
    }
	
    /**
     * Generates finalize query of property converts units to SI units etc.
     * @author Howard <howard@realtyna.com>
     * @static
     * @param type $data
     * @param type $id
     * @return string
     */
	public static function generate_finalize_query($data, $id = '')
	{
        $units = wpl_global::return_in_id_array(wpl_units::get_units('', 1));
        $query = '';
		
        foreach($data as $field=>$value)
		{
            if(!strstr($field, '_unit')) continue;
            if(!isset($units[$value])) continue;
			
			$core_field = str_replace('_unit', '', $field);
            if(!array_key_exists($core_field.'_si', $data)) continue;
            if(!isset($data[$core_field])) continue;
			
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
     * Generate rendered data of a property
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $pid
     * @return string
     */
	public static function generate_rendered_data($pid)
	{
		_wpl_import('libraries.render');
		
		/** get property data **/
		$property_data = wpl_property::get_property_raw_data($pid);
		
		/** location text **/
		$location_text = self::generate_location_text($property_data, NULL, ',', true);
        
        /** rendered data **/
        $find_files = array();
		$rendered_fields = self::render_property($property_data, self::get_plisting_fields('', $property_data['kind']), $find_files, true);
        
		$result = json_encode(array('rendered'=>$rendered_fields['ids'], 'materials'=>$rendered_fields['columns'], 'location_text'=>$location_text));
        
        $column = 'rendered';
        if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        
		$query = "UPDATE `#__wpl_properties` SET `$column`='".wpl_db::escape($result)."' WHERE `id`='$pid'";
		
		/** update **/
		wpl_db::q($query, 'update');
        return $result;
	}
	
    /**
     * Updates picture count, attachment count etc.
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param array $property_data
     */
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
     * Updates text search field
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     */
	public static function update_text_search_field($property_id)
	{
        $property_data = wpl_property::get_property_raw_data($property_id);
		$kind = wpl_property::get_property_kind($property_id);
        
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
                $location_values = array();
				foreach($data['locations'] as $location_level=>$value)
				{
                    array_push($location_values, $data['keywords'][$location_level]);
                    
                    $abbr = wpl_locations::get_location_abbr_by_name($data['raw'][$location_level], $location_level);
                    $name = wpl_locations::get_location_name_by_abbr($abbr, $location_level);
                    
                    $ex_space = explode(' ', $name);
                    foreach($ex_space as $value_raw) array_push($location_values, $value_raw);
                    
                    if($name !== $abbr) array_push($location_values, $abbr);
				}
                
                /** Add all location fields to the location text search **/
                $location_category = wpl_flex::get_category(NULL, " AND `kind`='$kind' AND `prefix`='ad'");
                $location_fields = wpl_flex::get_fields($location_category->id, 1, $kind);
                
                foreach($location_fields as $location_field)
                {
                    if(!isset($rendered[$location_field->id])) continue;
                    if(!trim($location_field->table_column)) continue;
                    if(!isset($rendered[$location_field->id]['value']) or (isset($rendered[$location_field->id]['value']) and !trim($rendered[$location_field->id]['value']))) continue;
                    
                    $ex_space = explode(' ', strip_tags($rendered[$location_field->id]['value']));
                    foreach($ex_space as $value_raw) array_push($location_values, $value_raw);
                }
                
                $location_suffix_prefix = wpl_locations::get_location_suffix_prefix();
                foreach($location_suffix_prefix as $suffix_prefix) array_push($location_values, $suffix_prefix);
                
                $location_string = '';
                $location_values = array_unique($location_values);
                foreach($location_values as $location_value) $location_string .= 'LOC-'.__($location_value, WPL_TEXTDOMAIN).' ';
                
				$value = trim($location_string);
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
        
        $column = 'textsearch';
        if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        
		wpl_db::set('wpl_properties', $property_id, $column, wpl_db::escape(implode(' ', $text_search_data)));
    }
    
    /**
     * Returns property page link
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $property_data
     * @param int $property_id
     * @param int $target_id
     * @return string
     */
	public static function get_property_link($property_data, $property_id = 0, $target_id = 0)
	{
		/** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
		if(!$property_id) $property_id = $property_data['id'];
        
        $url = wpl_sef::get_wpl_permalink(true);
        
        $alias_column = 'alias';
        $alias_field_id = wpl_flex::get_dbst_id($alias_column, $property_data['kind']);
        $alias_field = wpl_flex::get_field($alias_field_id);
        
        if(isset($alias_field->multilingual) and $alias_field->multilingual and wpl_global::check_multilingual_status()) $alias_column = wpl_addon_pro::get_column_lang_name($alias_column, wpl_global::get_current_language(), false);
        
		if(trim($property_data[$alias_column]) != '') $alias = urlencode($property_data[$alias_column]);
		else $alias = urlencode(self::update_alias($property_data, $property_id));
		
        $home_type = wpl_global::get_wp_option('show_on_front', 'posts');
        $home_id = wpl_global::get_wp_option('page_on_front', 0);
        
		if(!$target_id) $target_id = wpl_request::getVar('wpltarget', 0);
		if($target_id)
        {
            $url = wpl_global::add_qs_var('pid', $property_id, wpl_sef::get_page_link($target_id));
            $url = wpl_global::add_qs_var('alias', $alias, $url);
            
            $url = wpl_global::add_qs_var('wplview', 'property_show', $url);
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
                $url .= $property_id.'-'.$alias;
            }
        }
		
        return $url;
    }
    
    /**
     * Returns PDF link of property
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param int $target_id
     * @return string|boolean
     */
	public static function get_property_pdf_link($property_id, $target_id = 0)
	{
        /** first validation **/
        if(!trim($property_id)) return false;
        
        $nosef = wpl_sef::is_permalink_default();
        
        $home_type = wpl_global::get_wp_option('show_on_front', 'posts');
        $home_id = wpl_global::get_wp_option('page_on_front', 0);
        $wpl_main_page_id = wpl_sef::get_wpl_main_page_id();
        
        if($nosef  or ($home_type == 'page' and $home_id == $wpl_main_page_id))
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
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @static
     * @param type $target_id
     * @return type
     */
	public static function get_property_listing_link($target_id = 0)
	{
        if($target_id) $url = wpl_sef::get_page_link($target_id);
        else $url = wpl_sef::get_wpl_permalink(true);
        
        return $url;
    }
	
    /**
     * Generates proeprty location text
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $property_data
     * @param int $property_id
     * @param string $glue
     * @param boolean $force
     * @return string
     */
	public static function generate_location_text($property_data, $property_id = 0, $glue = ',', $force = false)
	{
		/** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
        if(!$property_id) $property_id = $property_data['id'];
        
        /** Return hidex_keyword if address of property is hidden **/
        if(isset($property_data['show_address']) and !$property_data['show_address'])
        {
            $location_hidden_keyword = wpl_global::get_setting('location_hidden_keyword', 3);
            $placeholder = trim($location_hidden_keyword) ? $location_hidden_keyword : 'Address not available!';
            
            return __($placeholder, WPL_TEXTDOMAIN);
        }
        
        $column = 'location_text';
        $field_id = wpl_flex::get_dbst_id($column, $property_data['kind']);
        $field = wpl_flex::get_field($field_id);
        $base_column = NULL;
        
        if(isset($field->multilingual) and $field->multilingual and wpl_global::check_multilingual_status())
        {
            $base_column = wpl_global::get_current_language() == wpl_addon_pro::get_default_language() ? $column : NULL;
            $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        }
        
        /** return current location text if exists **/
        if(isset($property_data[$column]) and trim($property_data[$column]) != '' and !$force) return $property_data[$column];
        
		$locations = array();
        
        $street_no_column = 'street_no';
        if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($street_no_column, $property_data['kind'])) $street_no_column = wpl_addon_pro::get_column_lang_name($street_no_column, wpl_global::get_current_language(), false);
		if(isset($property_data[$street_no_column]) and trim($property_data[$street_no_column]) != '') $locations['street_no'] = __($property_data[$street_no_column], WPL_TEXTDOMAIN);
        
        $street_column = 'field_42';
        if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($street_column, $property_data['kind'])) $street_column = wpl_addon_pro::get_column_lang_name($street_column, wpl_global::get_current_language(), false);
        if(isset($property_data[$street_column]) and trim($property_data[$street_column]) != '') $locations['street'] = __($property_data[$street_column], WPL_TEXTDOMAIN);
        
        if(isset($property_data['location7_name']) and trim($property_data['location7_name']) != '') $locations['location7_name'] = __($property_data['location7_name'], WPL_TEXTDOMAIN);
        if(isset($property_data['location6_name']) and trim($property_data['location6_name']) != '') $locations['location6_name'] = __($property_data['location6_name'], WPL_TEXTDOMAIN);
        if(isset($property_data['location5_name']) and trim($property_data['location5_name']) != '') $locations['location5_name'] = __($property_data['location5_name'], WPL_TEXTDOMAIN);
        if(isset($property_data['location4_name']) and trim($property_data['location4_name']) != '') $locations['location4_name'] = __($property_data['location4_name'], WPL_TEXTDOMAIN);
        if(isset($property_data['location3_name']) and trim($property_data['location3_name']) != '') $locations['location3_name'] = __($property_data['location3_name'], WPL_TEXTDOMAIN);
        if(isset($property_data['location2_name']) and trim($property_data['location2_name']) != '') $locations['location2_name'] = __($property_data['location2_name'], WPL_TEXTDOMAIN);
        if(isset($property_data['zip_name']) and trim($property_data['zip_name']) != '') $locations['zip_name'] = __($property_data['zip_name'], WPL_TEXTDOMAIN);
        if(isset($property_data['location1_name']) and trim($property_data['location1_name']) != '') $locations['location1_name'] = __($property_data['location1_name'], WPL_TEXTDOMAIN);
        
        $location_pattern = wpl_global::get_setting('property_location_pattern');
        if(trim($location_pattern) == '') $location_pattern = '[street_no] [street][glue] [location4_name][glue] [location3_name][glue] [location2_name][glue] [location1_name] [zip_name]';
        
		$location_text = $location_pattern;
        $location_text = str_replace('[glue]', $glue, $location_text);
        
        preg_match_all('/\[([^\]]*)\]/', $location_pattern, $matches_pattern);
        foreach($matches_pattern[1] as $pattern)
        {
            if(isset($locations[$pattern])) $location_text = str_replace('[' . $pattern . ']', $locations[$pattern], $location_text);
            elseif(isset($property_data[$pattern]))
            {
                if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($pattern, $property_data['kind'])) $pattern_multilingual = wpl_addon_pro::get_column_lang_name($pattern, wpl_global::get_current_language(), false);
                
                $value = $property_data[(isset($pattern_multilingual) ? $pattern_multilingual : $pattern)];
                if(!trim($value)) $value = '';
                
                $location_text = str_replace('[' . $pattern . ']', $value, $location_text);
            }
        }
        
        $location_text = preg_replace('/\[[^\]]*\]/', '', $location_text);
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('generate_property_location_text', array('location_text'=>$location_text, 'glue'=>$glue, 'property_data'=>$property_data)));
        
        $final = '';
        $ex = explode($glue, $location_text);
        
        foreach($ex as $value)
        {
            if(trim($value) == '') continue;
            $final .= trim($value).$glue.' ';
        }
        
        $location_text = trim($final, $glue.' ');

        /** update **/
		$query = "UPDATE `#__wpl_properties` SET `$column`='".wpl_db::escape($location_text)."' WHERE `id`='".$property_id."'";
		wpl_db::q($query, 'update');
        
        if($base_column)
        {
            $query = "UPDATE `#__wpl_properties` SET `$base_column`='".wpl_db::escape($location_text)."' WHERE `id`='".$property_id."'";
            wpl_db::q($query, 'update');
        }
		return $location_text;
    }
	
    /**
     * Generates alias of property
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $property_data
     * @param int $property_id
     * @param string $glue
     * @param boolean $force
     * @return string
     */
	public static function update_alias($property_data, $property_id = 0, $glue = '-', $force = false)
	{
		/** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
        if(!$property_id) $property_id = $property_data['id'];
        
        $column = 'alias';
        $field_id = wpl_flex::get_dbst_id($column, $property_data['kind']);
        $field = wpl_flex::get_field($field_id);
        $base_column = NULL;
        
        if(isset($field->multilingual) and $field->multilingual and wpl_global::check_multilingual_status())
        {
            $base_column = wpl_global::get_current_language() == wpl_addon_pro::get_default_language() ? $column : NULL;
            $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        }
        
        /** return current alias if exists **/
        if(isset($property_data[$column]) and trim($property_data[$column]) != '' and !$force) return $property_data[$column];
        
		$alias = array();
		$alias['id'] = $property_id;
		if(trim($property_data['property_type'])) $alias['property_type'] = __(wpl_global::get_property_types($property_data['property_type'])->name, WPL_TEXTDOMAIN);
		if(trim($property_data['listing'])) $alias['listing'] = __(wpl_global::get_listings($property_data['listing'])->name, WPL_TEXTDOMAIN);
        
        if(trim($property_data['location1_name'])) $alias['location1'] = __($property_data['location1_name'], WPL_TEXTDOMAIN);
        if(trim($property_data['location2_name'])) $alias['location2'] = __($property_data['location2_name'], WPL_TEXTDOMAIN);
        if(trim($property_data['location3_name'])) $alias['location3'] = __($property_data['location3_name'], WPL_TEXTDOMAIN);
        if(trim($property_data['location4_name'])) $alias['location4'] = __($property_data['location4_name'], WPL_TEXTDOMAIN);
        if(trim($property_data['location5_name'])) $alias['location5'] = __($property_data['location5_name'], WPL_TEXTDOMAIN);
        if(trim($property_data['zip_name'])) $alias['zipcode'] = __($property_data['zip_name'], WPL_TEXTDOMAIN);
        
		$alias['location'] = self::generate_location_text($property_data, $property_id, '-');
		
		if(trim($property_data['rooms'])) $alias['rooms'] = $property_data['rooms'].__('Room'.($property_data['rooms'] > 1 ? 's': ''), WPL_TEXTDOMAIN);
		if(trim($property_data['bedrooms'])) $alias['bedrooms'] = $property_data['bedrooms'].__('Bedroom'.($property_data['bedrooms'] > 1 ? 's': ''), WPL_TEXTDOMAIN);
		if(trim($property_data['bathrooms'])) $alias['bathrooms'] = $property_data['bathrooms'].__('Bathroom'.($property_data['bathrooms'] > 1 ? 's': ''), WPL_TEXTDOMAIN);
		if(trim($property_data['mls_id'])) $alias['listing_id'] = $property_data['mls_id'];
        
		$unit_data = wpl_units::get_unit($property_data['price_unit']);
		if(trim($property_data['price'])) $alias['price'] = str_replace('.', '', wpl_render::render_price($property_data['price'], $unit_data['id'], $unit_data['extra']));
        
        $alias_pattern = wpl_global::get_setting('property_alias_pattern');
        if(trim($alias_pattern) == '') $alias_pattern = '[property_type][glue][listing_type][glue][location][glue][rooms][glue][bedrooms][glue][bathrooms][glue][price]';

        $alias_str = $alias_pattern;
        $alias_str = str_replace('[glue]', $glue, $alias_str);
        
        preg_match_all('/\[([^\]]*)\]/', $alias_pattern, $matches_pattern);
        foreach($matches_pattern[1] as $pattern)
        {
            if(isset($alias[$pattern])) $alias_str = str_replace('[' . $pattern . ']', $alias[$pattern], $alias_str);
            elseif(isset($property_data[$pattern]))
            {
                if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($pattern, $property_data['kind'])) $pattern_multilingual = wpl_addon_pro::get_column_lang_name($pattern, wpl_global::get_current_language(), false);
                
                $value = $property_data[(isset($pattern_multilingual) ? $pattern_multilingual : $pattern)];
                if(!trim($value)) $value = '';
                
                $alias_str = str_replace('[' . $pattern . ']', $value, $alias_str);
            }
        }
        
        $alias_str = preg_replace('/\[[^\]]*\]/', '', $alias_str);

        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('generate_property_alias', array('alias'=>$alias, 'alias_str'=>$alias_str)));
        
		/** escape **/
		$alias_str = wpl_db::escape(wpl_global::url_encode($alias_str));
        
		/** update **/
		$query = "UPDATE `#__wpl_properties` SET `$column`='".$alias_str."' WHERE `id`='".$property_id."'";
		wpl_db::q($query, 'update');
        
        if($base_column)
        {
            $query = "UPDATE `#__wpl_properties` SET `$base_column`='".$alias_str."' WHERE `id`='".$property_id."'";
            wpl_db::q($query, 'update');
        }
        
		return $alias_str;
    }
	
	/**
     * Updates property page title
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $property_data
     * @param int $property_id
     * @param boolean $force
     * @return string
     */
	public static function update_property_page_title($property_data, $property_id = 0, $force = false)
	{
        /** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
        if(!$property_id) $property_id = $property_data['id'];
        
        $column = 'field_312';
        $field_id = wpl_flex::get_dbst_id($column, $property_data['kind']);
        $field = wpl_flex::get_field($field_id);
        $base_column = NULL;
        
        if(isset($field->multilingual) and $field->multilingual and wpl_global::check_multilingual_status())
        {
            $base_column = wpl_global::get_current_language() == wpl_addon_pro::get_default_language() ? $column : NULL;
            $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        }
        
        /** return current page title if exists **/
        if(isset($property_data[$column]) and trim($property_data[$column]) != '' and !$force) return stripslashes($property_data[$column]);
        
        /** first validation **/
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
		@extract(wpl_filters::apply('generate_property_page_title', array('title'=>$title, 'title_str'=>$title_str, 'property_data'=>$property_data)));
        
        /** update **/
        if(wpl_db::columns('wpl_properties', $column))
        {
            $query = "UPDATE `#__wpl_properties` SET `".$column."`='".$title_str."' WHERE `id`='".$property_id."'";
            wpl_db::q($query, 'update');
        }
        
        /** update **/
        if($base_column and wpl_db::columns('wpl_properties', $base_column))
        {
            $query = "UPDATE `#__wpl_properties` SET `".$base_column."`='".$title_str."' WHERE `id`='".$property_id."'";
            wpl_db::q($query, 'update');
        }
        
		return stripslashes($title_str);
    }
    
    /**
     * Updates property title
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $property_data
     * @param int $property_id
     * @param boolean $force
     * @return string
     */
    public static function update_property_title($property_data, $property_id = 0, $force = false)
	{
        /** fetch property data if property id is set **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
        if(!$property_id) $property_id = $property_data['id'];
        
        $column = 'field_313';
        $field_id = wpl_flex::get_dbst_id($column, $property_data['kind']);
        $field = wpl_flex::get_field($field_id);
        $base_column = NULL;
        
        if(isset($field->multilingual) and $field->multilingual and wpl_global::check_multilingual_status())
        {
            $base_column = wpl_global::get_current_language() == wpl_addon_pro::get_default_language() ? $column : NULL;
            $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        }
        
        /** return current title if exists **/
        if(isset($property_data[$column]) and trim($property_data[$column]) != '' and !$force) return stripslashes($property_data[$column]);
        
        /** first validation **/
		if(!$property_data) return '';
        
        $listing_data = wpl_global::get_listings($property_data['listing']);
		$listing = isset($listing_data->name) ? $listing_data->name : '';
        
        $property_type_data = wpl_global::get_property_types($property_data['property_type']);
		$property_type = isset($property_type_data->name) ? $property_type_data->name : '';
		
		$title = array();
		$title['property_type'] = __($property_type, WPL_TEXTDOMAIN);
		$title['listing'] = __($listing, WPL_TEXTDOMAIN);
		
        if($property_data['kind'])
        {
            $kind_label = wpl_flex::get_kind_label($property_data['kind']);
            if(trim($kind_label)) $title['kind'] = '('.__($kind_label, WPL_TEXTDOMAIN).')';
        }
        
		$title_str = implode(' ', $title);
		
		/** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('generate_property_title', array('title'=>$title, 'title_str'=>$title_str, 'property_data'=>$property_data)));
        
        /** update **/
        if(wpl_db::columns('wpl_properties', $column))
        {
            $query = "UPDATE `#__wpl_properties` SET `".$column."`='".$title_str."' WHERE `id`='".$property_id."'";
            wpl_db::q($query, 'update');
        }
        
        /** update **/
        if($base_column and wpl_db::columns('wpl_properties', $base_column))
        {
            $query = "UPDATE `#__wpl_properties` SET `".$base_column."`='".$title_str."' WHERE `id`='".$property_id."'";
            wpl_db::q($query, 'update');
        }
        
        return stripslashes($title_str);
    }
	
    /**
     * Fix Aliases for all languages
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $property_data
     * @param int $property_id
     */
    public static function fix_aliases($property_data, $property_id = 0)
	{
		/** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
        if(!$property_id) $property_id = $property_data['id'];
        
        $columns = wpl_global::get_multilingual_columns(array('alias'), 'wpl_properties');
        foreach($columns as $column)
        {
            $alias = wpl_db::escape(wpl_global::url_encode($property_data[$column]));
            
            $query = "UPDATE `#__wpl_properties` SET `$column`='$alias' WHERE `id`='$property_id'";
            wpl_db::q($query, 'UPDATE');
        }
    }
    
    /**
     * Calls proeprty visit actions
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @return boolean
     */
	public static function property_visited($property_id)
	{
		/** first validation **/
		if(!trim($property_id)) return false;
		
		$current_user_id = wpl_users::get_cur_user_id();
		$property_user = wpl_property::get_property_user($property_id);
		
		/** checking if the property user himself viewing not adding the counter **/
		if($current_user_id != $property_user)
		{
			$query = "UPDATE `#__wpl_properties` SET `visit_time`=visit_time+1, `visit_date`='".date("Y-m-d H:i:s")."' WHERE `id`='$property_id'";
			wpl_db::q($query, 'update');
		}
        
        return true;
	}
	
    /**
     * Returns a certain field of property data
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $field_name
     * @param int $property_id
     * @return mixed
     */
	public static function get_property_field($field_name, $property_id)
	{
		return wpl_db::get($field_name, 'wpl_properties', 'id', $property_id);
	}
    
    /**
     * Returns property user (Agent) ID
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @return int
     */
	public static function get_property_user($property_id)
	{
		return self::get_property_field('user_id', $property_id);
	}
	
    /**
     * Returns property kind
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @return int
     */
	public static function get_property_kind($property_id)
	{
		return self::get_property_field('kind', $property_id);
	}
	
    /**
     * Temporary delete a proeprty
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param int $status
     * @param boolean $trigger_event
     * @return boolean
     */
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
     * Confirm a property
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param int $status
     * @param boolean $trigger_event
     * @return boolean
     */
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
     * Purge a property completely
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param boolean $trigger_event
     * @return boolean
     */
	public static function purge($property_id, $trigger_event = true)
	{
		/** first validation **/
		if(!trim($property_id)) return false;
		
		$property_data = self::get_property_raw_data($property_id);
		
		/** trigger event **/
		//if($trigger_event) wpl_global::event_handler('property_before_purge', array('property_id'=>$property_id, 'property_data'=>$property_data));

		/** trigger event **/
        if($trigger_event) wpl_global::event_handler('property_purged', array('property_id'=>$property_id, 'property_data'=>$property_data));

		/** purging property related data **/
        _wpl_import('libraries.items');
		wpl_items::delete_all_items($property_id, $property_data['kind']);
		
		/** purging property record **/
        wpl_db::delete('wpl_properties', $property_id);
		
		/** purging property folder **/
        wpl_folder::delete(wpl_items::get_path($property_id));
		
		return true;
	}
    
    /**
     * Changes user (Agent) of a property
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param int $user_id
     * @return boolean
     */
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
     * Returns active properties according to criteria
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $extra_condition
     * @param string $select
     * @param string $output
     * @param int $limit
     * @param string $order
     * @return array
     */
	public static function select_active_properties($extra_condition = '', $select = '*', $output = 'loadAssocList', $limit = 0, $order = '`id` ASC')
	{
		$condition = " AND `deleted`='0' AND `finalized`='1' AND `confirmed`='1' AND `expired`='0'";
		if(trim($extra_condition) != '') $condition .= $extra_condition;
		
		$query = "SELECT ".$select." FROM `#__wpl_properties` WHERE 1 ".$condition." ORDER BY $order ".($limit ? "LIMIT $limit" : '');
		return wpl_db::select($query, $output);
	}
	
    /**
     * Generates sort output
     * @author Howard <howard@realtyna.com>
     * @param type $params
     * @return type
     */
	public function generate_sorts($params = array())
	{
		include _wpl_import('views.basics.sorts.property_listing', true, true);
		return $result;
	}
	
    /**
     * This is a very useful function for rendering whole data of property. you need to just pass property_id and get everything!
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param array $plisting_fields
     * @param array $property
     * @param array $params
     * @return array
     */
	public static function full_render($property_id, $plisting_fields = NULL, $property = NULL, $params = array())
	{
		/** get plisting fields **/
		if(!$plisting_fields) $plisting_fields = self::get_plisting_fields();
		
		$raw_data = self::get_property_raw_data($property_id);
        
        if(!$raw_data) return array();
		if(!$property) $property = (object) $raw_data;
		
        $column = 'rendered';
        if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        
        /** generate rendered data if rendered data is empty **/
        if(!trim($raw_data[$column]) and wpl_settings::get('cache')) $rendered = json_decode(wpl_property::generate_rendered_data($property_id), true);
        elseif(!wpl_settings::get('cache')) $rendered = array();
        else $rendered = json_decode($raw_data[$column], true);
        
		$result = array();
		$result['data'] = (array) $property;
		
        if(!isset($rendered['rendered']) or !isset($rendered['materials']))
        {
            /** render data on the fly **/
            $find_files = array();
            $rendered_fields = self::render_property($property, $plisting_fields, $find_files, true);
        }
        
		if(isset($rendered['rendered']) and $rendered['rendered']) $result['rendered'] = $rendered['rendered'];
		else $result['rendered'] = $rendered_fields['ids'];
        
        if(isset($rendered['materials']) and $rendered['materials']) $result['materials'] = $rendered['materials'];
		else $result['materials'] = $rendered_fields['columns'];
		
		$result['items'] = wpl_items::get_items($property_id, '', $property->kind, '', 1);
		$result['raw'] = $raw_data;
		
		/** location text **/
		$result['location_text'] = self::generate_location_text($raw_data);
		
		/** property full link **/
        $target_page = isset($params['wpltarget']) ? $params['wpltarget'] : 0;
		$result['property_link'] = self::get_property_link($raw_data, NULL, $target_page);
        $result['property_title'] = self::update_property_title($raw_data);
		
		return $result;
	}
    
    /**
     * Converts Listing ID to pid
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $value
     * @param string $key
     * @return string
     */
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
     * This function is for importing/updating properties into the WPL. It uses WPL standard format for importing
     * This function must call in everywhere that we need to import properties like MLS and IMPORTER Addons.
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $properties_to_import
     * @param string $wpl_unique_field
     * @param int $user_id
     * @param string $source
     * @param boolean $finalize
     * @param array $log_params
     * @return array property IDs
     */
	public static function import($properties_to_import, $wpl_unique_field = 'mls_id', $user_id = '', $source = 'mls', $finalize = true, $log_params = array())
	{
		if(!$user_id) $user_id = wpl_users::get_cur_user_id();
		$pids = array();
		$added = array(); // Used for logging results
		$updated = array(); // Used for logging results
		
        /** model **/
        $model = new wpl_property();
        
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
            
			$exists = $model->get_properties_count(" AND `$wpl_unique_field`='$unique_value'");
			if(!$exists) $pid = $model->create_property_default($user_id);
			else $pid = $model->pid($unique_value, $wpl_unique_field);
			
			/** add property id to return **/
			$pids[] = $pid;
			
            /** Add source and last sync date **/
            if(in_array('source', $possible_columns) and in_array('last_sync_date', $possible_columns))
            {
                $q .= "`source`='$source',";
                $q .= "`last_sync_date`='".date('Y-m-d H:i:s')."',";
            }
            
			$q = trim($q, ', ');
			$query = "UPDATE `#__wpl_properties` SET ".$q." WHERE `id`='".$pid."'";
			wpl_db::q($query);
			
			if($finalize)
			{
				$mode = $exists ? 'edit' : 'add';
				$model->finalize($pid, $mode, $user_id);
			}
            
			if($exists) $added[] = $unique_value;
            else $updated[] = $unique_value;
		}
        
		/** Creating Log **/
		if($source == 'mls' and wpl_global::check_addon('mls'))
		{
            _wpl_import('libraries.addon_mls');
            if(method_exists('wpl_addon_mls', 'log')) wpl_addon_mls::log($added, $updated, $log_params);
		}
        
        /** WPL Import Event **/
        wpl_events::trigger('wpl_import', array('properties'=>$properties_to_import, 'wpl_unique_field'=>$wpl_unique_field, 'user_id'=>$user_id, 'source'=>$source, 'added'=>$added, 'updated'=>$updated, 'log_params'=>$log_params, 'pids'=>$pids));
        
		return $pids;
	}
    
    /**
     * Returns Property Edit Link
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @return boolean|string
     */
	public static function get_property_edit_link($property_id = 0)
	{
		/** first validation **/
		if(!$property_id) return false;
		
		$target_id = wpl_request::getVar('wpltarget', 0);
		
		if($target_id) $url = wpl_global::add_qs_var('pid', $property_id, wpl_sef::get_page_link($target_id));
		else
        {
            /** Backend **/
            if(wpl_global::get_client()) $url = wpl_global::add_qs_var('pid', $property_id, wpl_global::get_wpl_admin_menu('wpl_admin_add_listing'));
            /** Frontend **/
            else $url = wpl_global::add_qs_var('pid', $property_id, wpl_global::add_qs_var('wplmethod', 'wizard'));
        }
		
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

        foreach($thumbnails as $thumbnail) wpl_file::delete($thumbnail);
        
        return true;
    }
    
    /**
     * Removes property cache
     * @author Howard R <Howard@realtyna.com>
     * @static
     * @param int $property_id
     * @return boolean
     */
    public static function clear_property_cache($property_id)
    {
        /** First Validation **/
        if(!trim($property_id)) return false;
        
        _wpl_import('libraries.settings');
        
        $q = " `location_text`='', `rendered`='', `alias`=''";
        if(wpl_global::check_multilingual_status()) $q = wpl_settings::get_multilingual_query(array('location_text', 'rendered', 'alias'));
            
        $query = "UPDATE `#__wpl_properties` SET ".$q." WHERE `id`='$property_id'";
        return wpl_db::q($query, 'UPDATE');
    }
    
    /**
     * Checks if property has parent or not
     * @author Howard R <Howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param string $parent_column
     * @return boolean
     */
    public static function has_parent($property_id, $parent_column = 'parent')
    {
        if(wpl_property::get_parent($property_id, $parent_column)) return true;
        else return false;
    }
    
    /**
     * Returns Parent of a property
     * @author Howard R <Howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param string $parent_column
     * @return int
     */
    public static function get_parent($property_id, $parent_column = 'parent')
    {
        return wpl_property::get_property_field($parent_column, $property_id);
    }

    /**
     * Returns RSS link of property listing
     * @author Steve A. <steve@realtyna.com>
     * @static
     * @return string|boolean
     */
    public static function get_property_rss_link()
    {
        $nosef = wpl_sef::is_permalink_default();
        
        $home_type = wpl_global::get_wp_option('show_on_front', 'posts');
        $home_id = wpl_global::get_wp_option('page_on_front', 0);
        $wpl_main_page_id = wpl_sef::get_wpl_main_page_id();
        
        if($nosef  or ($home_type == 'page' and $home_id == $wpl_main_page_id))
        {
            $url = wpl_sef::get_wpl_permalink(true);
            $url = wpl_global::add_qs_var('wplview', 'features', $url);
            $url = wpl_global::add_qs_var('wpltype', 'rss', $url);
        }
        else $url = wpl_sef::get_wpl_permalink(true).'features/rss';
        
        return $url;
    }
    
    /**
     * Returns property meta keywords, This function calls on sef service when meta description of property is empty
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $property_data
     * @param int $property_id
     * @return string
     */
    public static function get_meta_keywords($property_data, $property_id = 0)
	{
        /** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
        if(!$property_id) $property_id = $property_data['id'];
        
        $keywords = array();
        if(isset($property_data['bedrooms']) and $property_data['bedrooms']) $keywords[] = $property_data['bedrooms'].' '.__('Bedroom'.($property_data['bedrooms'] > 1 ? 's' : ''), WPL_TEXTDOMAIN);
        if(isset($property_data['rooms']) and $property_data['rooms']) $keywords[] = $property_data['rooms'].' '.__('Room'.($property_data['rooms'] > 1 ? 's' : ''), WPL_TEXTDOMAIN);
        if(isset($property_data['bathrooms']) and $property_data['bathrooms']) $keywords[] = $property_data['bathrooms'].' '.__('Bathroom'.($property_data['bathrooms'] > 1 ? 's' : ''), WPL_TEXTDOMAIN);
        
        if(isset($property_data['property_type']))
        {
            $property_type = wpl_global::get_property_types($property_data['property_type']);
            if(trim($property_type->name)) $keywords[] = __($property_type->name, WPL_TEXTDOMAIN);
        }
        
        if(isset($property_data['listing']))
        {
            $listing = wpl_global::get_listings($property_data['listing']);
            if(trim($listing->name)) $keywords[] = __($listing->name, WPL_TEXTDOMAIN);
        }
        
        if(isset($property_data['mls_id'])) $keywords[] = $property_data['mls_id'];
        
        $keywords_str = implode(', ', $keywords);
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('generate_meta_keywords', array('keywords_str'=>$keywords_str, 'keywords'=>$keywords, 'property_data'=>$property_data)));
        
        return $keywords_str;
    }
    
    /**
     * Returns property meta description, This function calls on sef service when meta description of listing is empty
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $property_data
     * @param int $property_id
     * @return string
     */
    public static function get_meta_description($property_data, $property_id = 0)
	{
        /** fetch property data if property id is setted **/
		if($property_id) $property_data = self::get_property_raw_data($property_id);
        if(!$property_id) $property_id = $property_data['id'];
        
        $locale = wpl_global::get_current_language();
        
        $column = 'field_308';
        if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($column, $property_data['kind'])) $column = wpl_addon_pro::get_column_lang_name($column, $locale, false);
        
        $description = substr($property_data[$column], 0, 250);
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('generate_meta_description', array('description'=>$description, 'property_data'=>$property_data)));
        
        return $description;
    }
    
    /**
     * Returns property featured image if exists otherwise it returns empty string
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param string $sizes
     * @return string
     */
    public static function get_property_image($property_id, $sizes = '150*150')
    {
        if(!trim($property_id)) return false;
        if(!trim($sizes)) $sizes = '150*150';
        
        $images = wpl_items::render_gallery_custom_sizes($property_id, NULL, array($sizes));
        $size_alias = str_replace('*', '_', $sizes);
        
        return (count($images) ? $images[$size_alias][0]['url'] : '');
    }
    
    /**
     * Get located blog id of property 
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @return int
     */
    public static function get_blog_id($property_id)
    {
        if(!wpl_global::is_multisite() or !$property_id) return 1;
        
        return wpl_property::get_property_field('blog_id', $property_id);
    }
}