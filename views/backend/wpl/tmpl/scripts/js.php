<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function check_addon_update(addon_id)
{
    wpl_remove_message('.wpl_addons_message .wpl_show_message');
    
	/** run ajax query **/
	request_str = 'wpl_format=b:wpl:ajax&wpl_function=check_addon_update&addon_id='+addon_id;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);

	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_addons_message .wpl_show_message', 'wpl_green_msg');
            rta.util.equalPanel(true);
            
			setTimeout(function(){ window.location.reload(); }, 1500);
		}
		else if(data.success == 2)
		{
			wpl_show_messages(data.message, '.wpl_addons_message .wpl_show_message', 'wpl_gold_msg');
            rta.util.equalPanel(true);
		}
		else
		{
			wpl_show_messages(data.message, '.wpl_addons_message .wpl_show_message', 'wpl_red_msg');
            rta.util.equalPanel(true);
		}
	});
}

function trigger_addon_update(addon_id)
{
    wplj("#wpl_addon_container"+addon_id+" .wpl_addon_message span").trigger("click");
}

function update_package(sid)
{
	wpl_show_messages('<?php echo __('Please wait ...', WPL_TEXTDOMAIN); ?>', '.wpl_addons_message .wpl_show_message', 'wpl_gold_msg');
    rta.util.equalPanel(true);
    
	/** run ajax query **/
	request_str = 'wpl_format=b:wpl:ajax&wpl_function=update_package&sid=' + sid;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);

	ajax.success(function(data)
	{
		if(data.error != '')
		{
			wpl_show_messages(data.error, '.wpl_addons_message .wpl_show_message', 'wpl_red_msg');
            rta.util.equalPanel(true);
		}
		else
		{
			wpl_show_messages(data.message, '.wpl_addons_message .wpl_show_message', 'wpl_green_msg');
            rta.util.equalPanel(true);
            
			setTimeout(function() { window.location.reload(); }, 1500);
		}
	});
}

function save_realtyna_credentials()
{
	var username = encodeURIComponent(wplj("#realtyna_username").val());
	var password = encodeURIComponent(wplj("#realtyna_password").val());

	ajax_loader_element = '#wpl_realtyna_credentials_check';
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	/** run ajax query **/
	request_str = 'wpl_format=b:wpl:ajax&wpl_function=save_realtyna_credentials&username='+username+'&password='+password;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);

	ajax.success(function(data)
	{
		if(data.status == 1)
		{
			wpl_show_messages(data.message, '.wpl_addons_message .wpl_show_message', 'wpl_green_msg');
            rta.util.equalPanel(true);
            
			wplj(ajax_loader_element).html('<span class="action-btn icon-enabled"></span>');
		}
		else if(data.status != 1)
		{
			wpl_show_messages(data.message, '.wpl_addons_message .wpl_show_message', 'wpl_red_msg');
            rta.util.equalPanel(true);
            
			wplj(ajax_loader_element).html('<span class="action-btn icon-disabled"></span>');
		}
	});
}
</script>