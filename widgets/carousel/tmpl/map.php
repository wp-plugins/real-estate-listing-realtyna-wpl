<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** get params **/
$this->map_width  = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 1920;
$this->map_height   = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 558;

$this->default_lt   = '38.685516';
$this->default_ln   = '-101.073324';
$this->default_zoom = '4';

/** unset current key **/
unset($wpl_properties['current']);

$this->markers = wpl_property::render_markers($wpl_properties);

/** load js **/
include _wpl_import('widgets.carousel.scripts.js_map', true, true);
?>
<div class="wpl_googlemap_container" id="wpl_googlemap_container<?php echo $this->widget_id; ?>">
	<div class="wpl_map_canvas" id="wpl_carousel_map_canvas<?php echo $this->widget_id; ?>" style="height: <?php echo $this->map_height ?>px;"></div>
</div>