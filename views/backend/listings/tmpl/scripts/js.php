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

function wpl_search_listings()
{
    /** Create full url of search **/
    search_str = '<?php echo wpl_global::get_full_url(); ?>';

    wplj("#wpl_listing_manager_search_form_cnt select").each(function(ind, elm)
	{
        if(wplj(elm).val() != '-1') search_str = wpl_update_qs(elm.id, wplj(elm).val(), search_str);
        else if(wplj(elm).val() == '-1') search_str = wpl_update_qs(elm.id, '', search_str);
	});

    wplj("#wpl_listing_manager_search_form_cnt input:text, #wpl_listing_manager_search_form_cnt input[type='hidden']").each(function(ind, elm)
	{
        search_str = wpl_update_qs(elm.id, wplj(elm).val(), search_str);
	});

    window.location = search_str;
}

function wpl_reset_listings()
{
    wplj("#wpl_listing_manager_search_form_cnt").find(':input').each(function()
    {
        switch(this.type)
        {
            case 'text':

                wplj(this).val('');
                break;

            case 'select-multiple':

                wplj(this).multiselect("uncheckAll");
                break;

            case 'select-one':

                wplj(this).val('-1');
                wplj(this).trigger("chosen:updated");
                break;

            case 'password':
            case 'textarea':

                wplj(this).val('');
                break;

            case 'checkbox':
            case 'radio':

                this.checked = false;
                break;
        }
    });
    
    wpl_search_listings();
}

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
        purge_property(pid, true);
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

function mass_change_user(uid)
{
    if(!uid)
    {
        wpl_show_messages('<?php echo __('User is not valid!', WPL_TEXTDOMAIN); ?>', '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
        return false;
    }

    wplj('.js-pcheckbox:checked').each(function()
	{
        pid = wplj(this).attr('id');
        wplj("#pmanager_change_user_select"+pid).val(uid);

        change_user(pid, uid);
    });
}

function purge_property(pid, confirmation)
{
    if(typeof confirmation == 'undefined') confirmation = 0;
    
    if(!confirmation)
    {
        message = '<?php echo __("Are you sure you want to delete this property?", WPL_TEXTDOMAIN); ?>';
        confirmation = confirm(message);

        if(!confirmation) return;
    }

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

function change_user(pid, uid)
{
    request_str = "wpl_format=b:listings:ajax&wpl_function=change_user&pid="+pid+"&uid="+uid;
    wplj("#pmanager_change_user_label"+pid).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');

    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
    ajax.success(function(data)
	{
		if(data.success === 1)
		{
			wplj("#pmanager_change_user_label"+pid).html('<?php echo __('User', WPL_TEXTDOMAIN); ?>: ');
		}
		else if(data.success !== 1)
		{
			wplj("#pmanager_change_user_label"+pid).html('<?php echo __('User', WPL_TEXTDOMAIN); ?>: ');
			wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
		}
    });
}
</script>