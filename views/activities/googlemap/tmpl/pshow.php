<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

/** get params **/
$this->map_width = isset($params['map_width']) ? $params['map_width'] : 360;
$this->map_width = isset($params['map_height']) ? $params['map_height'] : 385;
$this->default_lt = isset($params['default_lt']) ? $params['default_lt'] : '38.685516';
$this->default_ln = isset($params['default_ln']) ? $params['default_ln'] : '-101.073324';
$this->default_zoom = isset($params['default_zoom']) ? $params['default_zoom'] : '14';

/* Get Google Place Option */
$this->google_place = isset($params['google_place']) ? $params['google_place'] : 0;
$this->google_place_radius = isset($params['google_place_radius']) ? $params['google_place_radius'] : 1000;

$listings = wpl_global::return_in_id_array(wpl_global::get_listings());
$this->markers = array();

$i = 0;
foreach($wpl_properties as $property)
{
	$this->markers[$i]['id'] = $property['raw']['id'];
	$this->markers[$i]['googlemap_lt'] = $property['raw']['googlemap_lt'];
	$this->markers[$i]['googlemap_ln'] = $property['raw']['googlemap_ln'];
	$this->markers[$i]['title'] = $property['raw']['googlemap_title'];
	
	$this->markers[$i]['pids'] = $property['raw']['id'];
    $this->markers[$i]['gmap_icon'] = (isset($listings[$property['raw']['listing']]['gicon']) and $listings[$property['raw']['listing']]['gicon']) ? $listings[$property['raw']['listing']]['gicon'] : 'default.png';
	
	$i++;
}

/** load js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
$this->_wpl_import($this->tpl_path.'.scripts.pshow', true, true);
?>
<div class="wpl_googlemap_container wpl_googlemap_pshow" id="wpl_googlemap_container<?php echo $this->activity_id; ?>">
	<div class="wpl_map_canvas" id="wpl_map_canvas<?php echo $this->activity_id; ?>" style="height: <?php echo $this->map_width ?>px;"></div>
</div>