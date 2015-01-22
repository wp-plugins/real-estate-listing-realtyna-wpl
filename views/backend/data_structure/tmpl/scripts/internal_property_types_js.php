<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
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
});

function wpl_remove_property_type(property_type_id, confirmed)
{
	if (!property_type_id)
	{
		wpl_show_messages("<?php echo __('Invalid Property Types', WPL_TEXTDOMAIN); ?>", '.wpl_data_structure_list .wpl_show_message');
		return false;
	}

	/** load delete light box **/
    wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
    request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=generate_delete_page&property_type_id='+property_type_id;

    /** run ajax query **/
    wplj.ajax(
    {
        type: "POST",
        url: '<?php echo wpl_global::get_full_url(); ?>',
        data: request_str,
        success: function(data)
        {
            wplj("#wpl_data_structure_edit_div").html(data);
            wplj._realtyna.lightbox.open("#wpl_property_type_remove"+property_type_id, {reloadPage: true});
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
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
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_data_structure_edit_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
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
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_ajax_save_property_type(key, element, id)
{
	if(id == '10000') return;
	table = 'wpl_property_types';

	ajax_loader_element = '#' + element.id + '_ajax_loader';
	url = '<?php echo wpl_global::get_full_url(); ?>';

	wpl_remove_message('.wpl_show_message' + id);
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    /** run ajax query **/
    request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=save_property_type&property_type_id=' + id + '&key=' + key + '&value=' + element.value;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);

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

function wpl_ajax_insert_property_type(id)
{
	if(id != '10000') return;
	table = 'wpl_property_types';

	url = '<?php echo wpl_global::get_full_url(); ?>';

	wpl_remove_message('.wpl_show_message' + id);
	parent = wplj('#wpl_parent10000').val();
    name = wplj('#wpl_name10000').val();
    
    /** validation for parent **/
    if(parent == '')
    {
        wpl_show_messages('<?php echo __('Select category!', WPL_TEXTDOMAIN); ?>', '.wpl_show_message' + id, 'wpl_red_msg');
        return;
    }
    
    /** run ajax query **/
    request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=insert_property_type&parent=' + parent + '&name=' + name;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);

	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_green_msg');
			setTimeout(function()
            {
			    wplj._realtyna.lightbox.close();
				location.reload();
			}, 1000);
		}
		else if (data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_red_msg');
		}
	});
}

function purge_properties_property_type(property_type_id)
{
	request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=purge_related_property&property_type_id=' + property_type_id;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);

	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_show_message' + property_type_id, 'wpl_green_msg');
			setTimeout(function()
            {
			    wplj._realtyna.lightbox.close();
			}, 1000);
		}
		else if (data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_show_message' + property_type_id, 'wpl_red_msg');
		}
	});
}

function assign_properties_property_type(property_type_id)
{
	var select_id = wplj('#property_type_select').val();

	if(select_id == -1) return;
	
	request_str = 'wpl_format=b:data_structure:ajax_property_types&wpl_function=assign_related_properties&property_type_id=' + property_type_id+ '&select_id=' + select_id;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);

	ajax.success(function(data)
	{
		if (data.success == 1)
		{
			wpl_show_messages(data.message, '.wpl_show_message' + property_type_id, 'wpl_green_msg');
			setTimeout(function()
            {
			    wplj._realtyna.lightbox.close();
			}, 1000);
		}
		else if (data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_show_message' + property_type_id, 'wpl_red_msg');
		}
	});
}

function show_opt_2_property_type()
{
	wplj('#pt-del-options').fadeOut(200,function()
    {
        wplj('#pt-del-plist').fadeIn();
    });
}
</script>