<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var wpl_carousel_property_ids<?php echo $this->widget_id; ?> = false;
function random_clicked<?php echo $this->widget_id; ?>()
{
	if(!wpl_carousel_property_ids<?php echo $this->widget_id; ?>)
		wpl_carousel_property_ids<?php echo $this->widget_id; ?> = wplj("#<?php echo $this->get_field_id('data_property_ids'); ?>").val();	
	
	if(wplj('#<?php echo $this->get_field_id('data_random'); ?>').is(':checked'))
	{
		wpl_carousel_property_ids<?php echo $this->widget_id; ?> = wplj("#<?php echo $this->get_field_id('data_property_ids'); ?>").val();	
		wplj("#<?php echo $this->get_field_id('data_property_ids'); ?>").val('');
	}
	else
	{
		wplj("#<?php echo $this->get_field_id('data_property_ids'); ?>").val(wpl_carousel_property_ids<?php echo $this->widget_id; ?>);
	}
}
</script>
<script type="text/javascript">
    (function($){$(function(){isWPL();})})(jQuery);
</script>