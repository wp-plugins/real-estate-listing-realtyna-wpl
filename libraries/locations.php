<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Locations Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 04/07/2013
 * @package WPL
 */
class wpl_locations
{
    /**
     * For updating a locaton record using location id
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $location_id
     * @param string $key
     * @param string $value
     * @param int $location_level
     * @return boolean
     */
	public static function update_location($location_id, $key, $value, $location_level = 1)
	{
		if(!$key or !$location_level) return false;
		
		$query = "UPDATE `#__wpl_location".$location_level."` SET `$key`='$value' WHERE `id`='$location_id'";
		$result = wpl_db::q($query, 'update');
		
		return $result;
	}
	
    /**
     * Deletes a location from database
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $location_id
     * @param int $level
     * @param boolean $recursive
     * @return boolean
     */
	public static function delete_location($location_id, $level = '', $recursive = false)
	{
		/** first validation **/
		if(!$level) return false;
		
		/** recursive remove locations **/
		if($recursive and $level != 'zips')
		{
			$query = "SELECT * FROM `#__wpl_location".($level+1)."` WHERE `parent`='$location_id' ";
			$sub_locations = wpl_db::select($query);
			
			if(count($sub_locations))
			{
				foreach($sub_locations as $location) self::delete_location($location->id, $level+1, $recursive);
			}
		}
		
		$query = "DELETE FROM `#__wpl_location".$level."` WHERE `id`='$location_id'";
		$result = wpl_db::q($query, 'delete');
		
		return $result;
	}
	
    /**
     * Adds a new location to location database
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param int $level
     * @param int $parent
     * @return int
     */
	public static function add_location($name, $abbr, $level, $parent = 0)
	{
		if($level == 1) $query = "INSERT INTO `#__wpl_location".$level."` (`name`,`abbr`,`enabled`) VALUES ('$name','$abbr',1)";
		else $query = "INSERT INTO `#__wpl_location".$level."` (`name`,`abbr`,`parent`) VALUES ('$name','$abbr','$parent')";
		
		return wpl_db::q($query, 'insert');
	}
	
    /**
     * Edits a location
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param int $level
     * @param int $location_id
     * @return mixed
     */
	public static function edit_location($name, $abbr, $level, $location_id)
	{
		$query = "UPDATE `#__wpl_location".$level."` SET `name`='$name', `abbr`='$abbr' WHERE `id`='$location_id'";
		return wpl_db::q($query, 'update');
	}
	
    /**
     * Returns locations
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $level
     * @param int $parent
     * @param int $enabled
     * @param string $condition
     * @return array
     */
	public static function get_locations($level = 1, $parent = '', $enabled = 0, $condition = '')
	{
		if(trim($condition) == '')
		{
			$condition = "";
			
			if(trim($parent) != '') $condition .= "AND `parent`='$parent' ";
			if(trim($enabled) != '') $condition .= "AND `enabled`='$enabled' ";
		}
		
		$query = "SELECT * FROM `#__wpl_location".$level."` WHERE 1 ".$condition;
		$locations = wpl_db::select($query);

		return $locations;
	}
    
    /**
     * Returns a specific location data by id
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $location_id
     * @param type $level
     * @return type
     */
	public static function get_location($location_id = '', $level = 1)
	{
		if(trim($location_id) == '') $location_id = 1;
		return $results = wpl_db::get('*', "wpl_location".$level, 'id', $location_id);
	}
	
    /**
     * Returns location id by location name, parent id and level
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $location_name
     * @param int $parent_id
     * @param int $level
     * @return int
     */
	public static function get_location_id($location_name = '', $parent_id = '', $level = 1)
	{
		$query = "SELECT `id` FROM `#__wpl_location".$level."` WHERE LOWER(name)='".strtolower($location_name)."' ".($parent_id ? " AND `parent`='$parent_id'" : "");
		return wpl_db::select($query, 'loadResult');
	}
    
    /**
     * Returns location tree for creating breadcrumb and etc
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $location_id
     * @param int $parent
     * @return array
     */
	public static function get_location_tree($location_id, $parent)
	{
		$res = array();
		$i = 0;
		
		while($parent > 0)
		{
			$pr = $parent == 1 ? "" : ", `parent`";
			$query = "SELECT `id`, `name`".$pr." FROM `#__wpl_location".$parent."` WHERE `id` = '$location_id'";
			$items = wpl_db::select($query);
			
			foreach($items as $item)
			{
				$res[$i]['id'] = $item->id;
				$res[$i]['name'] = $item->name;
				$location_id = $parent == 1 ? 0 : $item->parent;
			}
			
			$i++;
			$parent--;
		}
		
		return $res;
	}
	
    /**
     * Updates locationtextsearch data. It runes by WPL cronjob!
     * @author Howard <howard@realtyna.com>
     * @static
     */
	public static function update_locationtextsearch_data()
	{
		/** detele wpl_locationtextsearch completely **/
		wpl_db::q("DELETE FROM `#__wpl_locationtextsearch`");
		
        /** Don't run in case of many listings **/
        if(wpl_db::num('', 'wpl_properties') > 2500)
        {
            wpl_db::q("UPDATE `#__wpl_cronjobs` SET `enabled`='0' WHERE `id`='1'");
            return false;
        }

        _wpl_import('libraries.property');
		$properties = wpl_property::select_active_properties('', '`id`,`location1_name`,`location2_name`,`location3_name`,`location4_name`,`location5_name`,`location6_name`,`location7_name`,`zip_name`');
        
		$locations = array();
		foreach($properties as $property)
		{
			$pid = $property['id'];
			
			$locations[$pid] = array();
			$locations[$pid]['full_location'] = '';
			$locations[$pid]['zip'] = '';
			for($j=1; $j<=7; $j++) $locations[$pid][$j] = '';
			
			for($i=7; $i>=1; $i--)
			{
				$locations[$pid]['full_location'] .= ', '.$property['location'.$i.'_name'];
				
				if($i<=7 and trim($property['location7_name'])) $locations[$pid]['7'] .= ', '.$property['location'.$i.'_name'];
				if($i<=6 and trim($property['location6_name'])) $locations[$pid]['6'] .= ', '.$property['location'.$i.'_name'];
				if($i<=5 and trim($property['location5_name'])) $locations[$pid]['5'] .= ', '.$property['location'.$i.'_name'];
				if($i<=4 and trim($property['location4_name'])) $locations[$pid]['4'] .= ', '.$property['location'.$i.'_name'];
				if($i<=3 and trim($property['location3_name'])) $locations[$pid]['3'] .= ', '.$property['location'.$i.'_name'];
				if($i<=2 and trim($property['location2_name'])) $locations[$pid]['2'] .= ', '.$property['location'.$i.'_name'];
				if($i<=1 and trim($property['location1_name'])) $locations[$pid]['1'] .= ', '.$property['location'.$i.'_name'];
			}
			
			/** remove extra , and spaces if any **/
			foreach($locations[$pid] as $key=>$location) $locations[$pid][$key] = trim($location, ', ');
		
			/** add zip code **/
			$locations[$pid]['zip'] = $property['zip_name'].', '.$locations[$pid]['full_location'];
		}
		
		/** make a new location array **/
		$unique_locations = array();
		foreach($locations as $pid=>$location)
		{
			foreach($location as $location_level=>$location_string) $unique_locations[] = $location_string;
		}
		
		$unique_locations = array_unique($unique_locations);
		
		foreach($unique_locations as $location_text)
		{
			$query = "SELECT `kind`, COUNT(id) AS count FROM `#__wpl_properties` WHERE `deleted`='0' AND `finalized`='1' AND `confirmed`='1' AND `expired`='0' AND `location_text` LIKE '%".wpl_db::escape($location_text)."%' GROUP BY `kind`";
	        $counts = wpl_db::select($query, 'loadAssocList');
			
			$total_count = 0;
			foreach($counts as $count) $total_count += $count['count'];
			
			/** add to wpl_locationtextsearch **/
			$query = "INSERT INTO `#__wpl_locationtextsearch` (`location_text`,`count`,`counts`) VALUES ('".wpl_db::escape($location_text)."','$total_count','".json_encode($counts)."')";
			wpl_db::q($query);
		}
	}
    
    /**
     * Returns latitude and longitude of an address
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $address
     * @return array
     */
	public static function get_LatLng($address)
	{
		$address = urlencode($address);
		$api_key = wpl_global::get_setting('google_api_key', 1);
        
		$url1 = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address.(trim($api_key) ? "&key=".$api_key : "");
		$url2 = "https://maps.google.com/maps/geo?q=".$address."&output=csv";
		
		/** getting lat and lng using first url **/
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url1);
		curl_setopt($ch, CURLOPT_HEADER, 0); /** Change this to a 1 to return headers **/
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		
		$data = curl_exec($ch);
		$data = json_decode($data, true);
		
		$location_point = isset($data['results'][0]) ? $data['results'][0]['geometry']['location'] : NULL;
		
		if((isset($location_point['lat']) and $location_point['lat']) and (isset($location_point['lng']) and $location_point['lng']))
		{
			curl_close($ch);
			return array($location_point['lat'], $location_point['lng']);
		}
		
		/** getting lat and lng using second url **/
		curl_setopt($ch, CURLOPT_URL, $url2);
	
		$data = curl_exec($ch);
		$location_point = explode(',', $data);
		
		if((isset($location_point[2]) and $location_point[2]) and (isset($location_point[3]) and $location_point[3]))
		{
			curl_close($ch);
			return array($location_point[2], $location_point[3]);
		}
	}
	
    /**
     * Returns address of proeprty by latitude and longitude
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $latitude
     * @param int $longitude
     * @return array
     */
	public static function get_address($latitude, $longitude)
	{
        $api_key = wpl_global::get_setting('google_api_key', 1);
		$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=false".(trim($api_key) ? "&key=".$api_key : "");
		
		/** getting address **/
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0); /** Change this to a 1 to return headers **/
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		
		$data = curl_exec($ch);
		curl_close($ch);
		
		$data = json_decode($data, true);
		
		$formatted_locations = $data['results'][0]['address_components'];
		$locations = array();
		
		foreach($formatted_locations as $formatted_location)
		{
			if(in_array('country', $formatted_location['types'])) $locations['location1'] = $formatted_location['long_name'];
			elseif(in_array('administrative_area_level_1', $formatted_location['types'])) $locations['location2'] = $formatted_location['long_name'];
			elseif(in_array('administrative_area_level_2', $formatted_location['types'])) $locations['location3'] = $formatted_location['long_name'];
		}
		
		$locations['full_address'] = $data['results'][0]['formatted_address'];
		
		return $locations;
	}
    
    /**
     * Updates latitude and longitude of a property
     * @author Howard <howard@realtyna.com>
     * @static
     * @param type $property_data
     * @param type $property_id
     * @return type
     */
    public static function update_LatLng($property_data, $property_id = NULL)
    {
        /** fetch property data if property id is setted **/
		if($property_id) $property_data = wpl_property::get_property_raw_data($property_id);
        if(!$property_id) $property_id = $property_data['id'];
        
        $location_text = wpl_property::generate_location_text($property_data);
        $LatLng = self::get_LatLng($location_text);
        
        if($LatLng[0] and $LatLng[1])
        {
            $query = "UPDATE `#__wpl_properties` SET `googlemap_lt`='".$LatLng[0]."', `googlemap_ln`='".$LatLng[1]."' WHERE `id`='$property_id'";
            wpl_db::q($query);
        }
        
        $latitude = $LatLng[0] ? $LatLng[0] : $property_data['googlemap_lt'];
        $longitude = $LatLng[1] ? $LatLng[1] : $property_data['googlemap_ln'];
        
        return array($latitude, $longitude);
    }
    
    /**
     * Returns location name by abbreviation
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $abbr
     * @param int $location_level
     * @return string
     */
    public static function get_location_name_by_abbr($abbr, $location_level = 1)
    {
        /** First Validation **/
        if(!$location_level) $location_level = 1;
        if($location_level == 'zips') return $abbr;
        
        $name = wpl_db::select("SELECT `name` FROM `#__wpl_location".$location_level."` WHERE `abbr`='$abbr'", 'loadResult');
        return (trim($name) ? $name : $abbr);
    }
    
    /**
     * Returns abbreviation by location name
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param int $location_level
     * @return string
     */
    public static function get_location_abbr_by_name($name, $location_level = 1)
    {
        /** First Validation **/
        if(!$location_level) $location_level = 1;
        if($location_level == 'zips') return $name;
        
        $abbr = wpl_db::select("SELECT `abbr` FROM `#__wpl_location".$location_level."` WHERE LOWER(`name`)='".strtolower($name)."'", 'loadResult');
        return (trim($abbr) ? $abbr : $name);
    }
    
    /**
     * Returns Location Suffixes and Prefixes
     * @author Howard <howard@realtyna.com>
     * @static
     * @return array
     */
    public static function get_location_suffix_prefix()
    {
        $results = explode(',', trim(wpl_global::get_setting('location_suffix_prefix', 3), ', '));
        
        $sufpre = array();
        foreach($results as $result) $sufpre[] = trim($result, ', ');
        
        return $sufpre;
    }
}