<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var markers<?php echo $this->activity_id; ?> = <?php echo json_encode($this->markers); ?>;

/** default values in case of no marker to showing **/
var default_lt = '<?php echo $this->default_lt; ?>';
var default_ln = '<?php echo $this->default_ln; ?>';
var default_zoom = <?php echo $this->default_zoom; ?>;

wplj(document).ready(function()
{
	wpl_initialize<?php echo $this->activity_id; ?>();
    
    if(markers<?php echo $this->activity_id; ?>.length == 1)
    {
        /** restore the zoom level after the map is done scaling **/
        var listener = google.maps.event.addListener(wpl_map<?php echo $this->activity_id; ?>, 'idle', function(event)
        {
            wpl_map<?php echo $this->activity_id; ?>.setZoom(default_zoom);
            google.maps.event.removeListener(listener);
        });
    }
});
</script>