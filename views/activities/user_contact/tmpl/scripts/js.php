<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_send_user_contact<?php echo $this->activity_id; ?>(user_id)
{
    var ajax_loader_element = '#wpl_user_contact_ajax_loader<?php echo $this->activity_id; ?>_'+user_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    wpl_remove_message('#wpl_user_contact_message<?php echo $this->activity_id; ?>_'+user_id);
	
	var request_str = 'wpl_format=f:profile_listing:ajax&wpl_function=contact_profile&'+wplj('#wpl_user_contact_form<?php echo $this->activity_id; ?>'+user_id).serialize()+'&user_id='+user_id;
	var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_site_url(); ?>', request_str, ajax_loader_element, 'JSON', 'GET');
	
	ajax.success(function(data)
	{
		if(data.success === 1)
		{
			wpl_show_messages(data.message, '#wpl_user_contact_message<?php echo $this->activity_id; ?>_'+user_id, 'wpl_green_msg');
            wplj('#wpl_user_contact_form'+user_id).hide();
		}
		else if(data.success === 0)
		{
			wpl_show_messages(data.message, '#wpl_user_contact_message<?php echo $this->activity_id; ?>_'+user_id, 'wpl_red_msg');
		}
        
		wplj(ajax_loader_element).html('');
	});
    
    ajax.error(function(jqXHR, textStatus, errorThrown)
    {
        wpl_show_messages("<?php echo addslashes(__('Error Occurred!', WPL_TEXTDOMAIN)); ?>", '#wpl_user_contact_message<?php echo $this->activity_id; ?>_'+user_id, 'wpl_red_msg');
    });
	
	return false;
}
</script>