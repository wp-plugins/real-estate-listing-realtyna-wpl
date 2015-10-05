<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_favorite_control(id, mode)
{
	var request_str = 'wpl_format=f:property_listing:ajax_pro&wpl_function=favorites_control&pid='+id+'&mode='+mode;
	var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'JSON', 'GET');
	
	ajax.success(function(data)
	{
		wplj('#wpl_favorite_remove_'+id).toggle().parent('li').toggleClass('added');
		wplj('#wpl_favorite_add_'+id).toggle();
        
		if(typeof wpl_load_favorites == 'function')
        {
            wpl_load_favorites(data.pids);
        }
	});
	
	return false;
}

function wpl_report_abuse_get_form(id)
{
	var request_str = 'wpl_format=c:functions:ajax&wpl_function=report_abuse_form&pid='+id+'&form_id=0';
	var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'HTML', 'GET');
	
	ajax.success(function(html)
	{
        wplj("<?php echo $this->lightbox_container; ?>").html(html);
	});
	
	return false;
}

function wpl_report_abuse_submit()
{
    var message_path = '.wpl_show_message';
	var request_str = 'wpl_format=c:functions:ajax&wpl_function=report_abuse_submit&'+wplj('#wpl_report_abuse_form').serialize();
	var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'JSON', 'GET');
	
	ajax.success(function(data)
	{
        if(data.success) wpl_show_messages(data.message, message_path, 'wpl_green_msg');
        else wpl_show_messages(data.message, message_path, 'wpl_red_msg');
	});
	
	return false;
}

function wpl_send_to_friend_get_form(id)
{
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=send_to_friend_form&pid='+id+'&form_id=0';
    var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'HTML', 'GET');

    ajax.success(function(html)
    {
        wplj("<?php echo $this->lightbox_container; ?>").html(html);
    });

    return false;
}

function wpl_send_to_friend_submit()
{
    var message_path = '.wpl_show_message';
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=send_to_friend_submit&'+wplj('#wpl_send_to_friend_form').serialize();
    var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'JSON', 'GET');

    ajax.success(function(data)
    {
        if(data.success) wpl_show_messages(data.message, message_path, 'wpl_green_msg');
        else wpl_show_messages(data.message, message_path, 'wpl_red_msg');
    });

    return false;
}

function wpl_request_a_visit_get_form(id)
{
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=request_a_visit_form&pid='+id+'&form_id=0';
    var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'HTML', 'GET');

    ajax.success(function(html)
    {
        wplj("<?php echo $this->lightbox_container; ?>").html(html);
    });

    return false;
}

function wpl_request_a_visit_submit()
{
    var message_path = '.wpl_show_message';
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=request_a_visit_submit&'+wplj('#wpl_request_a_visit_form').serialize();
    var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'JSON', 'GET');

    ajax.success(function(data)
    {
        if(data.success) wpl_show_messages(data.message, message_path, 'wpl_green_msg');
        else wpl_show_messages(data.message, message_path, 'wpl_red_msg');
    });

    return false;
}
</script>