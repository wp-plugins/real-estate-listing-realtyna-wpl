<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj(function()
	{
		wplj('#wpl_notification_form').submit(function(e)
		{
			e.preventDefault();
			var include_user = '';
			var include_email = '';
			
			wplj("#wpl_to_user_include > option").each(function(i)
			{
				if(include_user !== '') include_user += ',';
				include_user += this.value;
			});
			
			wplj("#wpl_email_include > option").each(function(i)
			{
				if(include_email !== '') include_email += ',';
				include_email += this.value;
			});
			
			tinyMCE.triggerSave();
			
			ajax_loader_element = "#wpl_modify_ajax_loader";
			wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
			
			request_str = 'wpl_format=b:notifications:ajax&wpl_function=save_notification&info[include_email]=' + include_email + '&info[include_user]=' + include_user + '&' + wplj(this).serialize();
			ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element, 'HTML', 'POST');
			
			ajax.success(function(data)
			{
				wplj(ajax_loader_element).html('');
				wpl_show_messages('<?php echo __('Notification modified succesfully.', WPL_TEXTDOMAIN); ?>', '.wpl_notification_modify .wpl_show_message', 'wpl_green_msg');
			});
		});
	});
});

function wpl_add_email()
{
	var email = wplj('#wpl_email_include_textbox');
	if(wplj("#wpl_email_include option[value='" + email.val() + "']").length > 0) return false;
	if(wpl_check_email(email.val()))
	{
		wplj("#wpl_email_include").append(new Option(email.val(), email.val()));
		email.val('');
		email.focus();
	}
	else
	{
		wpl_alert("<?php echo __('Please enter correct email!', WPL_TEXTDOMAIN); ?>");
		email.focus();
	}
}

function wpl_remove_email()
{
	wplj("#wpl_email_include option:selected").remove();
}

function wpl_select_move(source, dest)
{
	wplj('#'+source+' option:selected').remove().appendTo('#' + dest);
}

function wpl_check_email(emailAddress)
{
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
}
</script>