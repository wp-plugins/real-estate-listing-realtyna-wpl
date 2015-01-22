<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj("#activity_manager_filter").keyup(function()
	{
        var term = wplj(this).val().toLowerCase();
		
		if(term != "")
		{
			wplj("#wpl_activity_manager_table tbody tr").hide();
            wplj("#wpl_activity_manager_table tbody tr").filter(function()
			{
				var activity_values = wplj(this)
				.children('td.wpl_activity_title, td.wpl_activity_activity, td.wpl_activity_layout, td.wpl_activity_position')
				.text();
				
				return activity_values.toLowerCase().indexOf(term) > -1;
            }).show();
		}
		else
		{
			wplj("#wpl_activity_manager_table tbody tr").show();
		}
	});
	
	wplj(".sortable_activity").sortable(
    {
        handle: 'span.icon-move',
        cursor: "move",
        update: function(e, ui)
        {
            var stringDiv = "";
            wplj(this).children("tr").each(function(i)
            {
                var tr = wplj(this);
                if(i != 0) stringDiv += ",";
                stringDiv += tr.attr("id") + ":" + i;
            });

            request_str = 'wpl_format=b:activity_manager:ajax&wpl_function=sort_activities&sort_ids=' + stringDiv;
            wplj.ajax(
            {
                type: "POST",
                url: '<?php echo wpl_global::get_full_url(); ?>',
                data: request_str,
                success: function(data)
                {
                    wpl_show_messages(data + ' <?php echo __('Activity Sorted!', WPL_TEXTDOMAIN); ?>', '.wpl_activity_manager_list .wpl_show_message', 'wpl_green_msg');
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '.wpl_activity_manager_list .wpl_show_message', 'wpl_red_msg');
                }
            });
        }
    });
});

function wpl_set_enabled_activity(activity_id, enabled_status)
{
	if (!activity_id)
	{
		wpl_show_messages("<?php echo __('Invalid Activity', WPL_TEXTDOMAIN); ?>", '.wpl_activity_manager_list .wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_' + activity_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
	request_str = 'wpl_format=b:activity_manager:ajax&wpl_function=set_enabled_activity&activity_id=' + activity_id + '&enabled_status=' + enabled_status;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element, 'JSON', 'POST');
   
	ajax.success(function(data)
	{
		if (data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_activity_manager_list .wpl_show_message', 'wpl_green_msg');
			wplj(ajax_loader_element).html('');

			if (enabled_status == 0)
			{
				wplj('#activity_enable_' + activity_id).removeClass("wpl_show").addClass("wpl_hidden");
				wplj('#activity_disable_' + activity_id).removeClass("wpl_hidden").addClass("wpl_show");
			}
			else
			{
				wplj('#activity_enable_' + activity_id).removeClass("wpl_hidden").addClass("wpl_show");
				wplj('#activity_disable_' + activity_id).removeClass("wpl_show").addClass("wpl_hidden");
			}
		}
		else if (data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_activity_manager_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_remove_activity(activity_id, confirmed)
{
	if (!activity_id)
	{
		wpl_show_messages("<?php echo __('Invalid Activity', WPL_TEXTDOMAIN); ?>", '.wpl_activity_manager_list .wpl_show_message');
		return false;
	}
	if (!confirmed)
	{
		message = "<?php echo __('Are you sure you want to remove this item?', WPL_TEXTDOMAIN); ?>&nbsp;(<?php echo __('ID', WPL_TEXTDOMAIN); ?>:" + activity_id + ")&nbsp;<?php echo __('All related items will be removed.', WPL_TEXTDOMAIN); ?>";
		message += '<span class="wpl_actions" onclick="wpl_remove_activity(' + activity_id + ', 1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';
		wpl_show_messages(message, '.wpl_activity_manager_list .wpl_show_message');
		return false;
	}
	else
	{
		wpl_remove_message();
	}
	
	ajax_loader_element = '#wpl_ajax_loader_' + activity_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
	request_str = 'wpl_format=b:activity_manager:ajax&wpl_function=remove_activity&activity_id=' + activity_id + '&wpl_confirmed=' + confirmed;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);
    
	ajax.success(function(data)
	{
		if (data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_activity_manager_list .wpl_show_message', 'wpl_green_msg');
			wplj(ajax_loader_element).html('');
			wplj(ajax_loader_element).parent().parent().remove();
		}
		else if (data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_activity_manager_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_generate_modify_activity_page(activity_id)
{
	wpl_remove_message('.wpl_activity_manager_list .wpl_show_message');
	
	request_str = 'wpl_format=b:activity_manager:ajax&wpl_function=generate_modify_page';
	if(activity_id) request_str += '&activity_id='+activity_id;
	else
	{
		var activity_name = wplj("#wpl_activity_add").val();
		if(!activity_name)
		{
			wpl_show_messages('<?php echo __('Please select activity to import!', WPL_TEXTDOMAIN); ?>', '.wpl_activity_manager_list .wpl_show_message', 'wpl_red_msg');
			return;
		}
		
		request_str += '&activity_name='+activity_name;
	}
	
	/** open lightbox **/
    if(!activity_id) wplj._realtyna.lightbox.open("#wpl_lightbox_handler", {reloadPage: true});
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_activity_manager_edit_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '.wpl_activity_manager_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}
</script>