<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj(function()
	{
		wplj(".sortable_property_type").sortable(
		{
			handle: 'span.icon-move',
			cursor: "move",
			update: function(e, ui)
			{
				var stringDiv = "";
				wplj(this).children("tr").each(function(i)
				{
					var tr = wplj(this);
					var tr_id = tr.attr("id").split("_");
					if (i != 0) stringDiv += ",";
					stringDiv += tr_id[2];
				});

				request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=sort_property_types&sort_ids=' + stringDiv;

				wplj.ajax(
				{
					type: "POST",
					url: '<?php echo wpl_global::get_full_url(); ?>',
					data: request_str,
					success: function(data)
					{
					},
					error: function(jqXHR, textStatus, errorThrown)
					{
						wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
					}
				});
			}
		});
	})
});

function wpl_remove_property_type(property_type_id, confirmed)
{
	if (!property_type_id)
	{
		wpl_show_messages("<?php echo __('Invalid Property Types', WPL_TEXTDOMAIN); ?>", '.wpl_data_structure_list .wpl_show_message');
		return false;
	}

	if (!confirmed)
	{
		message = "<?php echo __('Are you sure to remove this item?', WPL_TEXTDOMAIN); ?>&nbsp;(<?php echo __('ID', WPL_TEXTDOMAIN); ?>:" + property_type_id + ")&nbsp;<?php echo __('All related items will be removed.', WPL_TEXTDOMAIN); ?>";
		message += '<span class="wpl_actions" onclick="wpl_remove_property_type(' + property_type_id + ', 1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';

		wpl_show_messages(message, '.wpl_data_structure_list .wpl_show_message');

		return false;
	}
	else
	{
		wpl_remove_message();
	}

	ajax_loader_element = '#wpl_ajax_loader_' + property_type_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');

	request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=remove_property_type&property_type_id=' + property_type_id + '&wpl_confirmed=' + confirmed;

	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);

	ajax.success(function(data)
	{
		if (data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_green_msg');
			wplj(ajax_loader_element).html('');
			wplj(ajax_loader_element).parent().parent().remove();
		}
		else if (data.success != 1)
		{

			wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_set_enabled_property_type(property_type_id, enabeled_status)
{
	if (!property_type_id)
	{
		wpl_show_messages("<?php echo __('Invalid Property Type', WPL_TEXTDOMAIN); ?>", '.wpl_data_structure_list .wpl_show_message');
		return false;
	}

	ajax_loader_element = '#wpl_ajax_loader_' + property_type_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');

	request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=set_enabled_property_type&property_type_id=' + property_type_id + '&enabeled_status=' + enabeled_status;

	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);

	ajax.success(function(data)
	{
		if (data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_green_msg');
			wplj(ajax_loader_element).html('');

			if (enabeled_status == 0)
			{
				wplj('#property_types_enable_' + property_type_id).removeClass("wpl_show").addClass("wpl_hidden");
				wplj('#property_types_disable_' + property_type_id).removeClass("wpl_hidden").addClass("wpl_show");
			}
			else
			{
				wplj('#property_types_enable_' + property_type_id).removeClass("wpl_hidden").addClass("wpl_show");
				wplj('#property_types_disable_' + property_type_id).removeClass("wpl_show").addClass("wpl_hidden");
			}
		}
		else if (data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_generate_new_page_property_type()
{
	wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
	request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=generate_new_page';
	
	/** refresh the fancybox **/
	rta.config.fancybox.reloadAfterClose = true;
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_data_structure_edit_div").html(data);
			wplj('.wpl_help').wpl_help();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj.fancybox.close();
		}
	});
}

function wpl_generate_edit_page_property_type(property_type_id)
{
	if (!property_type_id) return false;

	wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
	request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=generate_edit_page&property_type_id=' + property_type_id;

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_data_structure_edit_div").html(data);
			wplj('.wpl_help').wpl_help();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj.fancybox.close();
		}
	});
}

function wpl_ajax_save_property_type(key, element, id)
{
	table = 'wpl_property_types';

	ajax_loader_element = '#' + element.id + '_ajax_loader';
	url = '<?php echo wpl_global::get_full_url(); ?>';

	wpl_remove_message('.wpl_show_message' + id);
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');

	ajax = wpl_ajax_save(table, key, element, id, url);

	ajax.success(function(data)
	{
		if (data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_green_msg');
			wplj(ajax_loader_element).html('');
		}
		else if (data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}
</script>