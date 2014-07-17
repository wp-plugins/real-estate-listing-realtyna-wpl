<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_favorite_control(id, mode)
{
	request_str = 'wpl_format=f:property_listing:ajax_pro&wpl_function=favorites_control&pid='+id+'&mode='+mode;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'JSON', 'GET');
	
	ajax.success(function(data)
	{
		wplj('#wpl_favorite_remove_'+id).toggle().parent('li').toggleClass('added');;
		wplj('#wpl_favorite_add_'+id).toggle();
        
		wpl_load_favorites(data.pids);
	});
	
	return false;
}

function wpl_load_favorites(pids)
{
    var pids_string = '';
    for(var i = 0; i < pids.length; i++)  pids_string += pids[i]+',';
    
    request_str = 'wpl_format=f:property_listing:ajax_pro&wpl_function=favorites_load&pids='+pids_string;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'HTML', 'GET');
	
	ajax.success(function(data)
	{
	});
}
</script>