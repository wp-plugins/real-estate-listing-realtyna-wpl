<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_send_contact(property_id)
{
    ajax_loader_element = '#wpl_contact_ajax_loader_'+property_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    wpl_remove_message('#wpl_contact_message_'+property_id);
	
	request_str = 'wpl_format=f:property_listing:ajax&wpl_function=contact_agent&'+wplj('#wpl_contact_form'+property_id).serialize()+'&pid='+property_id;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, ajax_loader_element, 'JSON', 'GET');
	
	ajax.success(function(data)
	{
		if(data.success === 1)
		{
			wpl_show_messages(data.message, '#wpl_contact_message_'+property_id, 'wpl_green_msg');
            wplj('#wpl_contact_form'+property_id).hide();
		}
		else if(data.success === 0)
		{
			wpl_show_messages(data.message, '#wpl_contact_message_'+property_id, 'wpl_red_msg');
		}
        
		wplj(ajax_loader_element).html('');
	});
	
	return false;
}
</script>