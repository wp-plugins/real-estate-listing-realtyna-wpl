<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'radiussearchunit' and !$done_this)
{
	/** importing library **/
	_wpl_import('libraries.locations');
	
	$unit_id = $value;
	$address = $vars['sf_radiussearch'];
	$radius = $vars['sf_radiussearchradius'];
	
    if(trim($address))
    {
        $location_point = wpl_locations::get_LatLng($address);
        $latitude = $location_point[0];
        $longitude = $location_point[1];
    }
    else
    {
        $latitude = isset($vars['sf_radiussearch_lat']) ? $vars['sf_radiussearch_lat'] : 0;
        $longitude = isset($vars['sf_radiussearch_lng']) ? $vars['sf_radiussearch_lng'] : 0;
    }
	
	if($latitude and $longitude and $radius and $unit_id)
	{
		$unit = wpl_units::get_unit($unit_id);
		
		if($unit)
		{
			$tosi =  (6371*1000)/$unit['tosi'];
			$radius_si = $radius*$unit['tosi'];
			
			$query .= " AND (( ".$tosi." * acos( cos( radians(".$latitude.") ) * cos( radians( p.googlemap_lt ) ) * cos( radians( p.googlemap_ln ) - radians(".$longitude.") ) + sin( radians(".$latitude.") ) * sin( radians( p.googlemap_lt ) ) ) ) < ".($radius) .") AND `show_address`='1'";
		}
	}

	$done_this = true;
}