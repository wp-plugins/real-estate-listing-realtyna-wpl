<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
});

function ajax_multilingual_save(field_id, lang, value, item_id)
{
    var wpl_function = 'save_multilingual';
	var form_element_id = "#wpl_c_"+field_id+"_"+lang;
	
	var current_element_status = wplj(form_element_id).attr("disabled");
	wplj(form_element_id).attr("disabled", "disabled");
	
	var ajax_loader_element = '#wpl_listing_saved_span_'+field_id+"_"+lang;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:listing:ajax&wpl_function='+wpl_function+'&dbst_id='+field_id+'&value='+encodeURIComponent(value)+'&item_id='+item_id+'&lang='+lang;
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);
	ajax.success(function(data)
	{
		if(current_element_status != 'disabled') wplj(form_element_id).removeAttr("disabled");
		
		if(data.success == 1)
		{
			wplj(ajax_loader_element).html('');
			
			/** unfinalize property **/
			if(finalized)
			{
				ajax_save('', '', '0', item_id, '', '', 'finalize');
				finalized = 0;
				wplj("#wpl_listing_remember_to_finalize").show();
			}
		}
		else if(data.success != 1)
		{
			try{eval(data.js)} catch(err){}
  
			wplj(ajax_loader_element).html('');
		}
	});
}

function ajax_save(table_name, table_column, value, item_id, field_id, form_element_id, wpl_function)
{
	if(!wpl_function) wpl_function = 'save';
	if(!form_element_id) form_element_id = "#wpl_c_"+field_id;
	
	var current_element_status = wplj(form_element_id).attr("disabled");
	wplj(form_element_id).attr("disabled", "disabled");
	var element_type = wplj(form_element_id).attr('type');
	
	if(element_type == 'checkbox')
	{
		if(wplj(form_element_id).is(':checked')) value = 1;
		else value = 0;
	}
	
	var ajax_loader_element = '#wpl_listing_saved_span_'+field_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	var request_str = 'wpl_format=b:listing:ajax&wpl_function='+wpl_function+'&table_name='+table_name+'&table_column='+table_column+'&value='+encodeURIComponent(value)+'&item_id='+item_id;
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);
	ajax.success(function(data)
	{
		if(current_element_status != 'disabled') wplj(form_element_id).removeAttr("disabled");
		
		if(data.success == 1)
		{
			wplj(ajax_loader_element).html('');
			
			/** unfinalize property **/
			if(finalized)
			{
				ajax_save('', '', '0', item_id, '', '', 'finalize');
				finalized = 0;
				wplj("#wpl_listing_remember_to_finalize").show();
			}
		}
		else if(data.success != 1)
		{
			try{eval(data.js)} catch(err){}
  
			wplj(ajax_loader_element).html('');
		}
	});
}

/** for saving items into the items table **/
function item_save(value, item_id, field_id, item_type, item_cat, item_extra1, item_extra2, item_extra3, form_element_id, wpl_function)
{
    if(!item_extra1) item_extra1 = '';
    if(!item_extra2) item_extra2 = '';
    if(!item_extra3) item_extra3 = '';
	if(!wpl_function) wpl_function = 'item_save';
	if(!form_element_id) form_element_id = "#wpl_c_"+field_id;
	
	var current_element_status = wplj(form_element_id).attr("disabled");
	wplj(form_element_id).attr("disabled", "disabled");
	var element_type = wplj(form_element_id).attr('type');
	
	if(element_type == 'checkbox')
	{
		if(wplj(form_element_id).is(':checked')) value = 1;
		else value = 0;
	}
	
	var ajax_loader_element = '#wpl_listing_saved_span_'+field_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	var request_str = 'wpl_format=b:listing:ajax&wpl_function='+wpl_function+'&value='+encodeURIComponent(value)+'&item_id='+item_id+'&item_type='+item_type+'&item_cat='+item_cat+'&item_extra1='+item_extra1+'&item_extra2='+item_extra2+'&item_extra3='+item_extra3+'&kind=<?php echo $this->kind; ?>';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);
	ajax.success(function(data)
	{
		if(current_element_status != 'disabled') wplj(form_element_id).removeAttr("disabled");
		
		if(data.success == 1)
		{
			wplj(ajax_loader_element).html('');
			
			/** unfinalize property **/
			if(finalized)
			{
				ajax_save('', '', '0', item_id, '', '', 'finalize');
				finalized = 0;
				wplj("#wpl_listing_remember_to_finalize").show();
			}
		}
		else if(data.success != 1)
		{
			try{eval(data.js)} catch(err){}
  
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_neighborhood_select(table_name, table_column, value, item_id, field_id)
{
	if(wplj('#wpl_c_'+field_id).is(':checked')) value = 1; else value = 0;
	
	wplj("#wpl_span_dis_"+field_id).slideToggle(100);
	ajax_save(table_name, table_column, value, item_id, field_id);
	
	if(value == 1)
	{
		wplj('#wpl_c_'+field_id+'_distance0').attr('checked', 'checked');
		ajax_save(table_name, table_column+'_distance_by', 1, item_id, field_id, '#wpl_c_'+field_id+'_distance0');
	}
	else
	{
		wplj('input[name=wpl_c_'+field_id+'_distance_by]:checked').removeAttr('checked');
		ajax_save(table_name, table_column+'_distance_by', '', item_id, field_id, '#wpl_c_'+field_id+'_distance0');
		
		wplj('#wpl_c_'+field_id+'_distance').val(0);
		ajax_save(table_name, table_column+'_distance', 0, item_id, field_id, '#wpl_c_'+field_id+'_distance');
	}
}

function wpl_neighborhood_distance_type_select(table_name, table_column, value, item_id, field_id, form_element_id)
{
	if(wplj('#wpl_c_'+field_id+'_distance').val() == '')
	{
		wplj('input[name=wpl_c_'+field_id+'_distance_by]:checked').attr('checked', '');
		wpl_alert("<?php echo __("Please enter distance first!", WPL_TEXTDOMAIN); ?>");
	}
	else
	{
		ajax_save(table_name, table_column, value, item_id, field_id, form_element_id);
	}
}

function number_to_th(number)
{
	if(number > 10) return number + "th";
	else if(number == 1) return "<?php echo __('First', WPL_TEXTDOMAIN); ?>";
	else if(number == 2) return "<?php echo __('Second', WPL_TEXTDOMAIN); ?>";
	else if(number == 3) return "<?php echo __('Third', WPL_TEXTDOMAIN); ?>";
	else if(number == 4) return "<?php echo __('Fourth', WPL_TEXTDOMAIN); ?>";
	else if(number == 5) return "<?php echo __('Fifth', WPL_TEXTDOMAIN); ?>";
	else if(number == 6) return "<?php echo __('Sixth', WPL_TEXTDOMAIN); ?>";
	else if(number == 7) return "<?php echo __('Seventh', WPL_TEXTDOMAIN); ?>";
	else if(number == 8) return "<?php echo __('Eighth', WPL_TEXTDOMAIN); ?>";
	else if(number == 9) return "<?php echo __('Nineth', WPL_TEXTDOMAIN); ?>";
	else if(number == 10) return "<?php echo __('Tenth', WPL_TEXTDOMAIN); ?>";
}

function wpl_get_tinymce_content(html_element_id)
{
	if(wplj("#wp-"+html_element_id+"-wrap").hasClass("tmce-active"))
	{
		return tinyMCE.activeEditor.getContent();
	}
	else
	{
		return wplj("#"+html_element_id).val();
	}
}
</script>