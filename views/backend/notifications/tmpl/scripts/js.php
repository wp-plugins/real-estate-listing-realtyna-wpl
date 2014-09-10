<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj("#notification_filter").keyup(function()
	{
        var term = wplj(this).val().toLowerCase();
		
		if(term != "")
		{
			wplj("#wpl_notification_table tbody tr").hide();
            wplj("#wpl_notification_table tbody tr").filter(function()
			{
				var notification_values = wplj(this)
				.children('td.wpl_notification_description, td.wpl_notification_subject, td.wpl_notification_template')
				.text();
				
				return notification_values.toLowerCase().indexOf(term) > -1;
            }).show();
		}
		else
		{
			wplj("#wpl_notification_table tbody tr").show();
		}
	});
});

function wpl_set_enabled_notification(notification_id, enabled_status)
{
	if (!notification_id)
	{
		wpl_show_messages("<?php echo __('Invalid Notification', WPL_TEXTDOMAIN); ?>", '.wpl_notification_list .wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_' + notification_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	request_str = 'wpl_format=b:notifications:ajax&wpl_function=set_enabled_notification&notification_id=' + notification_id + '&enabled_status=' + enabled_status;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element, 'JSON', 'POST');
   
	ajax.success(function(data)
	{
		if (data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_notification_list .wpl_show_message', 'wpl_green_msg');
			wplj(ajax_loader_element).html('');

			if (enabled_status == 0)
			{
				wplj('#notification_enable_' + notification_id).removeClass("wpl_show").addClass("wpl_hidden");
				wplj('#notification_disable_' + notification_id).removeClass("wpl_hidden").addClass("wpl_show");
			}
			else
			{
				wplj('#notification_enable_' + notification_id).removeClass("wpl_hidden").addClass("wpl_show");
				wplj('#notification_disable_' + notification_id).removeClass("wpl_show").addClass("wpl_hidden");
			}
		}
		else if (data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_notification_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}
</script>