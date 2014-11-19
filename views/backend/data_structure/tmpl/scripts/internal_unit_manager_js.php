 <?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj(".sortable_unit").sortable(
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

            request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=sort_units&sort_ids='+stringDiv;

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

//load new category whene select box changed for category show
function load_new_unit_category(type)
{
	ajax_loader = '#wpl_ajax_loader_span';
	wplj(ajax_loader).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	ajax_loader_element = '#unit_manager_content';
	request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=generate_new_page&type='+type;
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element, "HTML");
	ajax.success(function(data)
	{
		wplj("#unit_manager_content").html(data);
		wplj(ajax_loader).html('');
	});	
}

//change 3digit seperators for units
function change_decimal_seperator(unit_id, d_seperator)
{
	if(!unit_id)
	{
		wpl_show_messages("<?php echo __('Invalid field', WPL_TEXTDOMAIN); ?>", '.wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_d_sep_'+unit_id;
	
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=unit_decimal_seperator_change&unit_id='+unit_id+'&d_seperator='+d_seperator;
	
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
			wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}

//change 3digit seperators for units
function change_3digit_seperator(unit_id,seperator)
{
	if(!unit_id)
	{
		wpl_show_messages("<?php echo __('Invalid field', WPL_TEXTDOMAIN); ?>", '.wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_3digit_'+unit_id;
	
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=unit_3digit_seperator_change&unit_id='+unit_id+'&seperator='+seperator;
	
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

//change enabled state enabled/disabled
function wpl_unit_enabled_change(unit_id)
{
	if(!unit_id)
	{
		wpl_show_messages("<?php echo __('Invalid field', WPL_TEXTDOMAIN); ?>", '.wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_'+unit_id;
	ajax_flag = '#wpl_ajax_flag_'+unit_id;
	
	//---get status for whene repate the state
	var enabled_status = null;
	if(wplj(ajax_flag).hasClass('icon-enabled')) enabled_status = 0;
	else if(wplj(ajax_flag).hasClass('icon-disabled')) enabled_status = 1;
	
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=unit_enabled_state_change&unit_id='+unit_id+'&enabled_status='+enabled_status;
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			if(enabled_status == 1) wplj(ajax_flag).removeClass('icon-disabled').addClass('icon-enabled');
			else wplj(ajax_flag).removeClass('icon-enabled').addClass('icon-disabled');
			wplj(ajax_loader_element).html('');
		}
		else if(data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}

//change after before state for units
function after_before_change(unit_id,after_befor)
{
	if(!unit_id)
	{
		wpl_show_messages("<?php echo __('Invalid field', WPL_TEXTDOMAIN); ?>", '.wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_after_before_'+unit_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=after_before_change_state&unit_id='+unit_id+'&after_before_status='+after_befor;	
	
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

//change after befor state for units
function wpl_update_exchange_rates()
{
	ajax_loader_element = '.wpl_ajax_loader_exchanges';
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=update_exchange_rates';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	ajax.success(function(data)
	{
		wplj(ajax_loader_element).html('');
		load_new_unit_category(4);
	});
}

function wpl_update_a_exchange_rate(unit_id,currency_code)
{
	if(!unit_id)
	{
		wpl_show_messages("<?php echo __('Invalid field', WPL_TEXTDOMAIN); ?>", '.wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_exchange_rate_'+unit_id;
	ajax_loader_txt_rate="#wpl_unit_tosi_"+unit_id;
	
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=update_a_exchange_rate&unit_id='+unit_id+'&currency_code='+currency_code;
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wplj(ajax_loader_element).html('');
			wplj(ajax_loader_txt_rate).val(data.res);
		}
		else if(data.success != 1)
		{
			wplj(ajax_loader_element).html('');
			wplj(ajax_loader_txt_rate).val(0);
		}
	});
}

function wpl_exchange_rate_manual(unit_id, new_exchange_rate)
{
	ajax_loader_element = '#wpl_ajax_loader_exchange_rate_'+unit_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=exchange_rate_manual&unit_id='+unit_id+'&tosi='+new_exchange_rate;
	
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

function wpl_change_currency_name(unit_id, name)
{
	ajax_loader_element = '#wpl_ajax_loader_name_'+unit_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:data_structure:ajax_unit_manager&wpl_function=change_currnecy_name&unit_id='+unit_id+'&name='+name;
	
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