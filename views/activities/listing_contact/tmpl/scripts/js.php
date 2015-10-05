<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_send_contact<?php echo $this->activity_id; ?>(property_id)
{
    var ajax_loader_element = '#wpl_contact_ajax_loader<?php echo $this->activity_id; ?>_'+property_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    wpl_remove_message('#wpl_contact_message<?php echo $this->activity_id; ?>_'+property_id);
	
	var request_str = 'wpl_format=f:property_listing:ajax&wpl_function=contact_listing_user&'+wplj('#wpl_contact_form<?php echo $this->activity_id; ?>'+property_id).serialize()+'&pid='+property_id;
	var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_site_url(); ?>', request_str, ajax_loader_element, 'JSON', 'GET');
	
	ajax.success(function(data)
	{
		if(data.success === 1)
		{
			wpl_show_messages(data.message, '#wpl_contact_message<?php echo $this->activity_id; ?>_'+property_id, 'wpl_green_msg');
            wplj('#wpl_contact_form<?php echo $this->activity_id; ?>'+property_id).hide();
		}
		else if(data.success === 0)
		{
			wpl_show_messages(data.message, '#wpl_contact_message<?php echo $this->activity_id; ?>_'+property_id, 'wpl_red_msg');
		}
        
		wplj(ajax_loader_element).html('');
	});
    
    ajax.error(function(jqXHR, textStatus, errorThrown)
    {
        wpl_show_messages("<?php echo addslashes(__('Error Occurred!', WPL_TEXTDOMAIN)); ?>", '#wpl_contact_message<?php echo $this->activity_id; ?>_'+property_id, 'wpl_red_msg');
    });
	
	return false;
}
</script>