<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Locations Library
** Developed 04/07/2013
**/

class wpl_locations
{
	/**
		@description for update a locaton record using location id
	**/
	public function update_location($location_id, $key, $value, $location_level = 1)
	{
		if(!$key or !$location_level) return false;
		
		$query = "UPDATE `#__wpl_location".$location_level."` SET `$key`='$value' WHERE `id`='$location_id'";
		$result = wpl_db::q($query, 'update');
		
		return $result;
	}
	
	/**
		@description to delete a location level
	**/
	public function delete_location($location_id, $level = '', $recursive = false)
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
		@description adding a location to location database
	**/
	public function add_location($name, $level, $parent = 0)
	{
		if($level == 1)
			$query = "INSERT INTO `#__wpl_location".$level."` (`name`,`enabled`) VALUES ('$name',1)";
		else
			$query = "INSERT INTO `#__wpl_location".$level."` (`name`,`parent`) VALUES ('$name','$parent')";
		
		return wpl_db::q($query, 'insert');
	}
	
	/**
		@description editing a location
	**/
	public function edit_location($name, $level, $location_id)
	{
		$query = "UPDATE `#__wpl_location".$level."` SET `name`='$name' WHERE `id`='$location_id'";
		return wpl_db::q($query, 'update');
	}
	
	/**
		@description getting locations
	**/
	public function get_locations($level = 1, $parent = '', $enabled = 0, $condition = '')
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
		@description get an specific location by id
	**/
	public function get_location($location_id = '', $level = 1)
	{
		if(trim($location_id) == '') $location_id = 1;
		return $results = wpl_db::get('*', "wpl_location".$level, 'id', $location_id);
	}
	
	/**
		@description get location id by location name, parent id and level
	**/
	public function get_location_id($location_name = '', $parent_id = '', $level = 1)
	{
		$query = "SELECT `id` FROM `#__wpl_location".$level."` WHERE LOWER(name)='".strtolower($location_name)."' ".($parent_id ? " AND `parent`='$parent_id'" : "");
		return wpl_db::select($query, 'loadResult');
	}
	
	/**
		@description get location tree for creating breadcrumb and etc
	**/
	public function get_location_tree($location_id, $parent)
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
		@input {property_id} and [property_data]
		@return void
		@description this function is for updating #__wpl_locationtextsearch table which is using in autocomplete and etc
					 this function is calling by cronjob
		@author Howard
	**/
	public function update_locationtextsearch_data()
	{
		_wpl_import('libraries.property');
		$properties = wpl_property::select_active_properties('', '`id`,`location1_name`,`location2_name`,`location3_name`,`location4_name`,`location5_name`,`location6_name`,`location7_name`,`zip_name`');
		
		/** detele wpl_locationtextsearch completely **/
		$query = "DELETE FROM `#__wpl_locationtextsearch`";
		wpl_db::q($query);
		
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
			$query = "SELECT `kind`, COUNT(id) AS count FROM `#__wpl_properties` WHERE `deleted`='0' AND `finalized`='1' AND `confirmed`='1' AND `location_text` LIKE '%$location_text%' GROUP BY `kind`";
	        $counts = wpl_db::select($query, 'loadAssocList');
			
			$total_count = 0;
			foreach($counts as $count) $total_count += $count['count'];
			
			/** add to wpl_locationtextsearch **/
			$query = "INSERT INTO `#__wpl_locationtextsearch` (`location_text`,`count`,`counts`) VALUES ('$location_text','$total_count','".json_encode($counts)."')";
			wpl_db::q($query);
		}
	}
	
	/**
		@input {address}
		@return array latitude, longitude
		@author Howard
	**/
	public function get_LatLng($address)
	{
		$address = urlencode($address);
		
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false";
		$url2 = "http://maps.google.com/maps/geo?q=".$address."&output=csv";
		
		/** getting lat and lng using first url **/
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0); /** Change this to a 1 to return headers **/
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$data = curl_exec($ch);
		$data = json_decode($data, true);
		$location_point = $data['results'][0]['geometry']['location'];
		
		if($location_point['lat'] and $location_point['lng'])
		{
			curl_close($ch);
			return array($location_point['lat'], $location_point['lng']);
		}
		
		/** getting lat and lng using second url **/
		curl_setopt($ch, CURLOPT_URL, $url2);
	
		$data = curl_exec($ch);
		$location_point = explode(',', $data);
		
		if($location_point[2] and $location_point[3])
		{
			curl_close($ch);
			return array($location_point[2], $location_point[3]);
		}
	}
	
	/**
		@input {latitude}, {longitude}
		@return array address
		@author Howard
	**/
	public function get_address($latitude, $longitude)
	{
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=false";
		
		/** getting address **/
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0); /** Change this to a 1 to return headers **/
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
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
}