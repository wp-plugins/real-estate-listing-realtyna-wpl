<script type="text/javascript">
wplj(document).ready(function()
{
	wplj(".MD_SEP > .wpl_search_field_container:first-child").click(function()
	{
		wplj(this).siblings(".wpl_search_field_container").slideToggle(400)
	})
});

/** main search function **/
function wpl_do_search_<?php echo $widget_id; ?>()
{
	request_str = '';
	wplj("#wpl_searchwidget_<?php echo $widget_id; ?> input:checkbox").each(function(index, element)
	{
		id = element.id;
		name = element.name;
		if(name.substring(0, 2) == 'sf')
		{
			if(wplj("#wpl_searchwidget_<?php echo $widget_id; ?> #"+id).closest('li').css('display') != 'none')
			{
				if(element.checked) value = element.value; else value = "-1";
				request_str += "&" + element.name.replace('sf<?php echo $widget_id; ?>_', 'sf_') +"=" + value;
			}
		}
	});

	wplj("#wpl_searchwidget_<?php echo $widget_id; ?> input:text").each(function(index, element)
	{
		id = element.id;
		name = element.name;
		if(name.substring(0, 2) == 'sf')
		{
			if(wplj("#wpl_searchwidget_<?php echo $widget_id; ?> #"+id).closest('li').css('display') != 'none')
			{
				value = element.value;
				request_str += "&" + element.name.replace('sf<?php echo $widget_id; ?>_', 'sf_') +"=" + value;
			}
		}
	});

	wplj("#wpl_searchwidget_<?php echo $widget_id;?> input[type=hidden]").each(function(index, element)
	{
		id = element.id;
		name = element.name;
		if(name.substring(0, 2) == 'sf')
		{
			if(wplj("#wpl_searchwidget_<?php echo $widget_id; ?> #"+id).closest('li').css('display') != 'none')
			{
				value = element.value;
				request_str += "&" + element.name.replace('sf<?php echo $widget_id; ?>_', 'sf_') +"=" + value;
			}
		}
	});
	
	wplj("#wpl_searchwidget_<?php echo $widget_id; ?> select, #wpl_searchwidget_<?php echo $widget_id; ?> textarea").each(function(index, element)
	{
		id = element.id;
		name = element.name;
		if(name.substring(0, 2) == 'sf')
		{
			if(wplj(element).closest('li').css('display') != 'none')
			{
				value = wplj(element).val();
				if(value != null) request_str += "&" + element.name.replace('sf<?php echo $widget_id; ?>_', 'sf_') +"=" + value;
			}
		}
	});
	
	/** Adding widget id **/
	request_str = 'widget_id=<?php echo $widget_id; ?>'+request_str;

	/** Create full url of search **/
	search_page = '<?php echo wpl_property::get_property_listing_link($target_id); ?>';
	
    if(search_page.indexOf('?') >= 0) search_str = search_page+'&'+request_str
    else search_str = search_page+'?'+request_str
    
	window.location = search_str;
	return false;
}

function wpl_sef_request<?php echo $widget_id; ?>(request_str)
{
	request_str = request_str.slice(1);
	splited = request_str.split("&");
	sef_str = '';
	unsef_str = '';
	var first_param = true;
	
	for(var i = 0; i < splited.length; i++)
	{
		splited2 = splited[i].split("=");
		key = splited2[0];
		value = splited2[1];
		
		if(key.substring(0, 9) == 'sf_select')
		{
			table_field = splited2[0].replace('sf_select_', '');
			key = wpl_ucfirst(table_field.replace('_', ' '));
			value = splited2[1];
			
			/** for setting text instead of value **/
			if(value != -1 && value != '' && (table_field == 'listing' || table_field == 'property_type'))
			{
				field_type = wplj("#sf<?php echo $widget_id; ?>_select_"+table_field).prop('tagName');
				if(field_type.toLowerCase() == 'select') value = wplj("#sf<?php echo $widget_id; ?>_select_"+table_field+" option:selected").text();
			}
			
			/** set to the SEF url **/
			if(value != -1 && value != '') sef_str += '/'+key+':'+value;
		}
		else
		{
			if(first_param && value != -1 && value != '')
			{
				unsef_str += '?'+key+'='+value;
				first_param = false;
			}
			else if(value != -1 && value != '')
			{
				unsef_str += '&'+key+'='+value;
			}
		}
	}
	
	final_str = sef_str+"/"+unsef_str;
	return final_str.slice(1);
}

function wpl_add_to_multiple<?php echo $widget_id; ?>(value, checked, table_column)
{
	setTimeout("wpl_add_to_multiple<?php echo $widget_id; ?>_do('"+value+"', "+checked+", '"+table_column+"');", 30);
}

function wpl_add_to_multiple<?php echo $widget_id; ?>_do(value, checked, table_column)
{
	var values = wplj('#sf<?php echo $widget_id; ?>_multiple_'+table_column).val();
	values = values.replace(value+',', '');
	
	if(checked) values += value+',';
	wplj('#sf<?php echo $widget_id; ?>_multiple_'+table_column).val(values);
}

function wpl_select_radio<?php echo $widget_id; ?>(value, checked, table_column)
{
	console.log(value+":"+checked+":"+table_column);
	if(checked) wplj('#sf<?php echo $widget_id;?>_select_'+table_column).val(value);
}

<?php
	$this->create_listing_specific_js();
	$this->create_property_type_specific_js();
?>
wplj(document).ready(function(){
	wplj("#wpl_searchwidget_<?php echo $widget_id; ?> select").chosen();
    wplj('#wpl_searchwidget_<?php echo $widget_id; ?> input[type="checkbox"]:not(.yesno)').checkbox({cls: 'jquery-safari-checkbox',empty:'<?php echo wpl_global::get_wpl_asset_url('img/empty.png'); ?>'});
    wplj('#wpl_searchwidget_<?php echo $widget_id; ?> input.yesno[type="checkbox"]').checkbox({empty:'<?php echo wpl_global::get_wpl_asset_url('img/empty.png'); ?>'});
})
</script>