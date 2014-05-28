<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();

/** get params **/
$this->map_width = isset($params['map_width']) ? $params['map_width'] : 980;
$this->map_height = isset($params['map_height']) ? $params['map_height'] : 480;
$this->default_lt = isset($params['default_lt']) ? $params['default_lt'] : '38.685516';
$this->default_ln = isset($params['default_ln']) ? $params['default_ln'] : '-101.073324';
$this->default_zoom = isset($params['default_zoom']) ? $params['default_zoom'] : '4';

/** unset current key **/
unset($wpl_properties['current']);

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
$this->_wpl_import($this->tpl_path.'.scripts.default', true, true);
?>
<div class="wpl_googlemap_container wpl_googlemap_plisting" id="wpl_googlemap_container<?php echo $this->activity_id; ?>">
	<div class="wpl_map_canvas" id="wpl_map_canvas<?php echo $this->activity_id; ?>" style="height: <?php echo $this->map_height ?>px;"></div>
</div>