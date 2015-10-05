<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function(){
});

function wpl_setting_save(setting_id, setting_name, setting_value, setting_category)
{
	wplj("#wpl_st_form_element"+setting_id).attr("disabled", "disabled");
	
	var element_type = wplj("#wpl_st_form_element"+setting_id).attr('type');
    var tag_name = wplj("#wpl_st_form_element"+setting_id).prop('tagName').toLowerCase();
	
	if(element_type == 'checkbox')
	{
		if(wplj("#wpl_st_form_element"+setting_id).is(':checked')) setting_value = 1;
		else setting_value = 0;
	}
    
    var ajax_loader_element = '#wpl_st_form_element'+setting_id;
    if(tag_name == 'select')
    {
        ajax_loader_element = '#wpl_st_form_element'+setting_id+'_chosen';
    }
	
    /** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show(ajax_loader_element, 'tiny', 'rightOut');
	
	var request_str = 'wpl_format=b:settings:ajax&wpl_function=save&setting_name='+setting_name+'&setting_value='+encodeURIComponent(setting_value)+'&setting_category='+setting_category;
	
	/** run ajax query **/
	var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		wplj("#wpl_st_form_element"+setting_id).removeAttr("disabled");
		
		/** Remove AJAX loader **/
        Realtyna.ajaxLoader.hide(wpl_ajax_loader);
	});
}

function wpl_setting_show_shortcode(setting_id, shortcode_key, shortcode_value)
{
	wplj("#wpl_st_"+setting_id+"_shortcode_value").html(shortcode_key+'="'+shortcode_value+'"');
}

function wpl_clear_properties_cached_datas(confirmed)
{
	if(!confirmed)
	{
		message = "<?php echo __("Are you sure you would like to remove properties' cached data?", WPL_TEXTDOMAIN); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_clear_properties_cached_datas(1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';
		
		wpl_show_messages(message, '.wpl_maintenance .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message();
	
	/** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show('#wpl_maintenance_clear_properties_cached_datas', 'tiny', 'leftOut');
	
	request_str = 'wpl_format=b:settings:ajax&wpl_function=clear_cache&cache_type=properties_cached_data';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		/** Remove AJAX loader **/
        Realtyna.ajaxLoader.hide(wpl_ajax_loader);
	});
}

function wpl_clear_listings_cached_location_texts(confirmed)
{
    if(!confirmed)
	{
		message = "<?php echo __("Are you sure you would like to remove cached location texts?", WPL_TEXTDOMAIN); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_clear_listings_cached_location_texts(1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';
		
		wpl_show_messages(message, '.wpl_maintenance .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message();
	
	/** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show('#wpl_maintenance_clear_listings_cached_location_texts', 'tiny', 'leftOut');
	
	request_str = 'wpl_format=b:settings:ajax&wpl_function=clear_cache&cache_type=location_texts';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		/** Remove AJAX loader **/
        Realtyna.ajaxLoader.hide(wpl_ajax_loader);
	});
}

function wpl_clear_listings_thumbnails(confirmed)
{
    if(!confirmed)
	{
		message = "<?php echo __("Are you sure you would like to remove listings thumbnails?", WPL_TEXTDOMAIN); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_clear_listings_thumbnails(1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';
		
		wpl_show_messages(message, '.wpl_maintenance .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message();
	
	/** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show('#wpl_maintenance_clear_listings_thumbnails', 'tiny', 'leftOut');
	
	request_str = 'wpl_format=b:settings:ajax&wpl_function=clear_cache&cache_type=listings_thumbnails';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		/** Remove AJAX loader **/
        Realtyna.ajaxLoader.hide(wpl_ajax_loader);
	});
}

function wpl_clear_users_thumbnails(confirmed)
{
    if(!confirmed)
	{
		message = "<?php echo __("Are you sure you would like to remove users thumbnails?", WPL_TEXTDOMAIN); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_clear_users_thumbnails(1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';
		
		wpl_show_messages(message, '.wpl_maintenance .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message();
	
	/** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show('#wpl_maintenance_clear_users_thumbnails', 'tiny', 'leftOut');
	
	request_str = 'wpl_format=b:settings:ajax&wpl_function=clear_cache&cache_type=users_thumbnails';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		/** Remove AJAX loader **/
        Realtyna.ajaxLoader.hide(wpl_ajax_loader);
	});
}

function wpl_clear_unfinalized_properties(confirmed)
{
    if(!confirmed)
	{
		message = "<?php echo __("Are you sure you would like to remove unfinalized listings?", WPL_TEXTDOMAIN); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_clear_unfinalized_properties(1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';
		
		wpl_show_messages(message, '.wpl_maintenance .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message();
	
    /** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show('#wpl_maintenance_clear_unfinalized_properties', 'tiny', 'leftOut');
    
	request_str = 'wpl_format=b:settings:ajax&wpl_function=clear_cache&cache_type=unfinalized_properties';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		/** Remove AJAX loader **/
        Realtyna.ajaxLoader.hide(wpl_ajax_loader);
	});
}

function wpl_clear_users_cached_datas(confirmed)
{
	if(!confirmed)
	{
		message = "<?php echo __("Are you sure you would like to remove users' cached data?", WPL_TEXTDOMAIN); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_clear_users_cached_datas(1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';
		
		wpl_show_messages(message, '.wpl_maintenance .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message();
	
	/** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show('#wpl_maintenance_clear_users_cached_datas', 'tiny', 'leftOut');
	
	request_str = 'wpl_format=b:settings:ajax&wpl_function=clear_cache&cache_type=users_cached_data';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		/** Remove AJAX loader **/
        Realtyna.ajaxLoader.hide(wpl_ajax_loader);
	});
}

function wpl_clear_calendar_data(confirmed)
{
    if(!confirmed)
	{
		message = "<?php echo __("Are you sure you would like to remove listings calendar data?", WPL_TEXTDOMAIN); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_clear_calendar_data(1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';
		
		wpl_show_messages(message, '.wpl_maintenance .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message();
	
	/** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show('#wpl_maintenance_clear_calendar_data', 'tiny', 'leftOut');
	
	request_str = 'wpl_format=b:settings:ajax&wpl_function=clear_calendar_data';
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		/** Remove AJAX loader **/
        Realtyna.ajaxLoader.hide(wpl_ajax_loader);
	});
}

function wpl_purge_cache_directory(confirmed)
{
    if(!confirmed)
	{
		var message = "<?php echo __("Are you sure you would like to purge WPL cache directory?", WPL_TEXTDOMAIN); ?>";
		message += '&nbsp;<span class="wpl_actions" onclick="wpl_purge_cache_directory(1);"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php echo __('No', WPL_TEXTDOMAIN); ?></span>';
		
		wpl_show_messages(message, '.wpl_maintenance .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message();
	
    /** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show('#wpl_maintenance_purge_cache_directory', 'tiny', 'leftOut');
    
	var request_str = 'wpl_format=b:settings:ajax&wpl_function=clear_cache&cache_type=wpl_cache_directory';
	
	/** run ajax query **/
	var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		/** Remove AJAX loader **/
        Realtyna.ajaxLoader.hide(wpl_ajax_loader);
	});
}

function wpl_export_settings()
{
	var format = wplj('#wpl_export_format').val();
	document.location = '<?php echo wpl_global::get_full_url(); ?>&wpl_format=b:settings:ajax&wpl_function=export_settings&wpl_export_format='+format;
}
</script>