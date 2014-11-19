<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();

/** Parameters **/
$this->params = $params;

/** get params **/
$this->googlemap_view = isset($params['googlemap_view']) ? $params['googlemap_view'] : 'WPL';
$this->map_width = isset($params['map_width']) ? $params['map_width'] : 980;
$this->map_height = isset($params['map_height']) ? $params['map_height'] : 480;
$this->default_lt = isset($params['default_lt']) ? $params['default_lt'] : '38.685516';
$this->default_ln = isset($params['default_ln']) ? $params['default_ln'] : '-101.073324';
$this->default_zoom = isset($params['default_zoom']) ? $params['default_zoom'] : '4';
$this->infowindow_event = isset($params['infowindow_event']) ? $params['infowindow_event'] : 'click';
$this->overviewmap = isset($params['overviewmap']) ? $params['overviewmap'] : 0;

/** unset current key **/
unset($wpl_properties['current']);

$this->markers = wpl_property::render_markers($wpl_properties);

/** load js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
$this->_wpl_import($this->tpl_path.'.scripts.default', true, true);

/** Demographic **/
$this->demographic = isset($params['demographic']) ? $params['demographic'] : 0;
if($this->demographic and wpl_global::check_addon('demographic')) $this->_wpl_import($this->tpl_path.'.scripts.addon_demographic', true, true);
?>
<div class="wpl_googlemap_container wpl_googlemap_plisting" id="wpl_googlemap_container<?php echo $this->activity_id; ?>">
	<div class="wpl_map_canvas" id="wpl_map_canvas<?php echo $this->activity_id; ?>" style="height: <?php echo $this->map_height ?>px;"></div>
</div>