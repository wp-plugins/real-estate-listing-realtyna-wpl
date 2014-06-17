<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var markers = <?php echo json_encode($this->markers); ?>;
var google_place = <?php echo $this->google_place; ?>;
var google_place_radius = <?php echo $this->google_place_radius; ?>

/** default values in case of no marker to showing **/
var default_lt = '<?php echo $this->default_lt; ?>';
var default_ln = '<?php echo $this->default_ln; ?>';
var default_zoom = <?php echo $this->default_zoom; ?>;
var wpl_map_initialized = false;

function wpl_pshow_map_init()
{
	if(wpl_map_initialized) return;
	
	wpl_initialize<?php echo $this->activity_id; ?>();
    
	/** restore the zoom level after the map is done scaling **/
	var listener = google.maps.event.addListener(wpl_map, 'idle', function(event)
	{
		wpl_map.setZoom(default_zoom);
		google.maps.event.removeListener(listener);
	});
	
	/** set true **/
	wpl_map_initialized = true;
}
</script>