<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_favorite_control(id, mode, property_show)
{
	var image = '';
	var title = '';
	var link = '';
	
	if (mode == 1)
	{
		if (property_show)
		{
			wplj("#wpl_gallery_container" + id + ' ul li:visible img').each(function()
            {
				image = this.src;
			});
		}
		else
		{
			wplj("#wpl_gallery_container" + id).children('img').each(function()
            {
				if (wplj(this).is(":visible"))
				{
					image = this.src;
					return false;
				}
			});
		}
		
		title = wplj('#wpl_property_title_' + id).val();
		link = wplj('#property_link_id_' + id).prop('href');
	}
	
	request_str = 'wpl_format=f:property_listing:ajax_pro&wpl_function=favorite_control&pid=' + id + "&image=" + image + '&title=' + title + '&link=' + link +'&mode=' + mode;
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_wp_url(); ?>', request_str, false, 'HTML', 'GET');
	
	ajax.success(function(data)
	{
		wplj('#wpl_favorite_remove_' + id).toggle().parent('li').toggleClass('added');;
		wplj('#wpl_favorite_add_' + id).toggle();
		wpl_load_favorites();
	});
	
	return false;
}
</script>