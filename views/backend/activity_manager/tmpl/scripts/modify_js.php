<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_save_activity()
{
    ajax_loader_element = "#wpl_activity_modify_ajax_loader";
    wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    wplj("#wpl_activity_submit_button").prop("disabled", "disabled");

    var param_str = '';
    wplj("#wpl_activity_modify_container input:checkbox").each(function(ind, elm)
	{
		param_str += "&"+elm.name+"=";
		if(elm.checked) param_str += '1'; else param_str += '0';
	});
	
	wplj("#wpl_activity_modify_container input:text, #wpl_activity_modify_container input[type='hidden'], #wpl_activity_modify_container select, #wpl_activity_modify_container textarea").each(function(ind, elm)
	{
		param_str += "&"+elm.name+"=";
		param_str += wplj(elm).val();
	});
    
    request_str = 'wpl_format=b:activity_manager:ajax&wpl_function=save_activity&'+param_str;
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element, 'HTML', 'POST');

    ajax.success(function(data)
    {
        wplj(ajax_loader_element).html('');
        wplj("#wpl_activity_submit_button").removeAttr("disabled");

        wplj._realtyna.lightbox.close();
    });
}

function wpl_page_association_selected(activity_id)
{
    var association = wplj("#wpl_page_association"+activity_id).val();
    if(association == '1' || association == '0') wplj(".wpl_activity_pages_container").hide();
    else wplj(".wpl_activity_pages_container").show();
}
</script>