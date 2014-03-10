<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var trash_class = 'icon-trash';
var restore_class = 'icon-restore';
var confirm_class = 'icon-confirm';
var unconfirm_class = 'icon-unconfirm';
var ajax_loader_image_class = 'ajax-inline-save';
var delete_class = 'icon-delete';

function select_all_checkboxes()
{
    wplj(".js-pcheckbox").each(function()
	{
        wplj(this).attr("checked", status);
    });
}

function deselect_all_checkboxes()
{
    wplj(".js-pcheckbox").each(function()
	{
        wplj(this).removeAttr('checked');
    });
}

function toggle_checkboxes()
{
    wplj(".js-pcheckbox").each(function()
	{
        if(wplj(this).attr("checked"))
            wplj(this).removeAttr('checked');
        else
            wplj(this).attr("checked", status);
    });
}

function mass_delete_completely_properties()
{
    message = '<?php echo __("Are you sure you want to delete these properties?", WPL_TEXTDOMAIN); ?>';
    confirmation = confirm(message);
	
    if(!confirmation) return
    
    wplj('.js-pcheckbox:checked').each(function()
	{
        pid = wplj(this).attr('id');
        purge_property(pid);
    });
}

function mass_trash_properties()
{
    wplj('.js-pcheckbox:checked').each(function()
	{
        pid = wplj(this).attr('id');
        
        if(wplj("#pmanager_trash"+pid).find('i').hasClass(trash_class))
            trash_property(pid);
    });
}

function mass_restore_properties()
{
    wplj('.js-pcheckbox:checked').each(function()
	{
        pid = wplj(this).attr('id');
        
        if(wplj("#pmanager_trash"+pid).find('i').hasClass(restore_class))
            trash_property(pid);
    });
}

function mass_confirm_properties()
{
    wplj('.js-pcheckbox:checked').each(function()
	{
        pid = wplj(this).attr('id');
        
        if(wplj("#pmanager_confirm"+pid).find('i').hasClass(unconfirm_class))
            confirm_property(pid);
    });
}

function mass_unconfirm_properties()
{
    wplj('.js-pcheckbox:checked').each(function()
	{
        pid = wplj(this).attr('id');
        
        if(wplj("#pmanager_confirm"+pid).find('i').hasClass(confirm_class))
            confirm_property(pid);
    });
}

function purge_property(pid)
{
	request_str = "wpl_format=b:listings:ajax&wpl_function=purge_property&pid="+pid;
    wplj("#pmanager_delete"+pid).removeClass(delete_class).addClass(ajax_loader_image_class);
    
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
    ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wplj("#plist_main_div_"+pid).fadeOut('slow');
		}
		else if(data.success != 1)
		{
			wplj("#pmanager_delete"+pid).removeClass(ajax_loader_image_class).addClass(delete_class);
			wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
		}
    });
}

function confirm_property(pid)
{
    if(wplj("#pmanager_confirm"+pid+" i").hasClass(confirm_class))
    {
        new_class = unconfirm_class;
        prev_class = confirm_class;
        confirmed = 0;
    }
    
    if(wplj("#pmanager_confirm"+pid+" i").hasClass(unconfirm_class))
    {
        new_class = confirm_class;
        prev_class = unconfirm_class;
        confirmed = 1;
    }
    
    wplj("#pmanager_confirm"+pid+" i").removeClass(prev_class).addClass(ajax_loader_image_class);
    
    request_str = "wpl_format=b:listings:ajax&wpl_function=update_property&pid="+pid+"&action=confirm&value="+confirmed;
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
    ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wplj("#pmanager_confirm"+pid+" i").removeClass(ajax_loader_image_class).addClass(new_class);
			
			if(confirmed) wplj("#pmanager_confirm"+pid+" span").text("<?php echo __('Confirm', WPL_TEXTDOMAIN); ?>");
			else wplj("#pmanager_confirm"+pid+" span").text("<?php echo __('Unconfirm', WPL_TEXTDOMAIN); ?>");
		}
		else if(data.success != 1)
		{
			wplj("#pmanager_confirm"+pid+" i").removeClass(ajax_loader_image_class).addClass(prev_class);
			wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
		}
    });   
}

function trash_property(pid)
{
	if(wplj("#pmanager_trash"+pid+" i").hasClass(trash_class))
	{
		new_class = restore_class;
		prev_class = trash_class;
		deleted = 1;
	}
	
	if(wplj("#pmanager_trash"+pid+" i").hasClass(restore_class))
	{
		new_class = trash_class;
		prev_class = restore_class;
		deleted = 0;
	}
	
	wplj("#pmanager_trash"+pid+" i").removeClass(prev_class).addClass(ajax_loader_image_class);
	
	request_str = "wpl_format=b:listings:ajax&wpl_function=update_property&pid="+pid+"&action=trash"+"&value="+deleted;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	
	ajax.success(function(data)
	{
		if(data.success == 1)
		{
			wplj("#pmanager_trash"+pid+" i").removeClass(ajax_loader_image_class).addClass(new_class);
			
			if(deleted) wplj("#pmanager_trash"+pid+" span").text("<?php echo __('Restore', WPL_TEXTDOMAIN); ?>");
			else wplj("#pmanager_trash"+pid+" span").text("<?php echo __('Delete', WPL_TEXTDOMAIN); ?>");
		}
		else if(data.success != 1)
		{
			wplj("#pmanager_trash"+pid+" i").removeClass(ajax_loader_image_class).addClass(prev_class);
			wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
		}
	});
}
</script>