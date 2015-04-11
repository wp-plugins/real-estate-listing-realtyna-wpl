<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'radiussearchunit' and !$done_this)
{
	/** importing library **/
	_wpl_import('libraries.locations');
	
	$unit_id = $value;
	$address = isset($vars['sf_radiussearch']) ? $vars['sf_radiussearch'] : '';
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
			$tosi = (6371*1000)/$unit['tosi'];
			$radius_si = $radius*$unit['tosi'];
			
			$query .= " AND (( ".$tosi." * acos( cos( radians(".$latitude.") ) * cos( radians( googlemap_lt ) ) * cos( radians( googlemap_ln ) - radians(".$longitude.") ) + sin( radians(".$latitude.") ) * sin( radians( googlemap_lt ) ) ) ) < ".($radius) .") AND `show_address`='1'";
		}
	}

	$done_this = true;
}
elseif($format == 'polygonsearch' and wpl_global::check_addon('aps') and !$done_this)
{
    /** importing library **/
	_wpl_import('libraries.addon_aps');
    
	$raw_points = isset($vars['sf_polygonsearchpoints']) ? $vars['sf_polygonsearchpoints'] : '[]';
    
    if(version_compare(wpl_db::version(), '5.6.1', '>=')) $sql_function = 'ST_Contains';
    else $sql_function = 'Contains';
    
    $APS = new wpl_addon_aps();
    $polygons = $APS->toPolygons($raw_points);
    
    $qq = array();
    foreach($polygons as $polygon)
    {
        $polygon_str = '';
        foreach($polygon as $polygon_point) $polygon_str .= $polygon_point[1].' '.$polygon_point[0].', ';
        $polygon_str = trim($polygon_str, ', ');
        
        $qq[] = $sql_function."(GeomFromText('Polygon((".$polygon_str."))'), geopoints) = 1";
    }
    
    if(count($qq)) $query .= " AND (".implode(' OR ', $qq).") AND `show_address`='1'";
    
	$done_this = true;
}