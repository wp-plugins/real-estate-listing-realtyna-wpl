 <?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj(".sortable_sort_options").sortable(
    {
        handle: 'span.icon-move',
        cursor: "move" ,
        update : function(e, ui)
        {
            var stringDiv = "";
            wplj(this).children("tr").each(function(i)
            {
                var tr = wplj(this);
                var tr_id = tr.attr("id").split("_");
                if(i != 0) stringDiv += ",";

                stringDiv += tr_id[2];
            });

            request_str = 'wpl_format=b:data_structure:ajax_sort_options&wpl_function=sort_options&sort_ids='+stringDiv;

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

/** change enabled state enabled/disabled **/
function wpl_sort_options_enabled_change(id)
{
	if(!id)
	{
		wpl_show_messages("<?php echo __('Invalid sort option!', WPL_TEXTDOMAIN); ?>", '.wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_options_'+id;
	ajax_flag = '#wpl_ajax_flag_options_'+id;
	
	//---get status for whene repate the state
	var enabled_status=null;
	if(wplj(ajax_flag).hasClass('icon-enabled')) enabled_status = 0;
	else if(wplj(ajax_flag).hasClass('icon-disabled')) enabled_status = 1;
	
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');	
	request_str = 'wpl_format=b:data_structure:ajax_sort_options&wpl_function=sort_options_enabled_state_change&id='+id+'&enabled_status='+enabled_status;
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			if(enabled_status == 1)
			{
				wplj(ajax_flag).removeClass('icon-disabled').addClass('icon-enabled');
			}
			else
			{
				wplj(ajax_flag).removeClass('icon-enabled').addClass('icon-disabled');
			}
			
			wplj(ajax_loader_element).html('');
		}
		else if(data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}

/** change enabled state enabled/disabled **/
function wpl_save_sort_option(id)
{	
	if(!id)
	{
		wpl_show_messages("<?php echo __('Invalid sort option!', WPL_TEXTDOMAIN); ?>", '.wpl_show_message');
		return false;
	}
	
    var sort_name = wplj("#wpl_sort_option_name"+id).val();
	var ajax_loader_element = '#wpl_sort_option_ajax_loader'+id;
	
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');	
	request_str = 'wpl_format=b:data_structure:ajax_sort_options&wpl_function=save_sort_option&id='+id+'&sort_name='+sort_name;
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wplj(ajax_loader_element).html('');
		}
		else if(data.success != 1)
		{
			wplj(ajax_loader_element).html('');
		}
	});
}
</script>