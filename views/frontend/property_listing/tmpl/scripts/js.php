<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	main_win_size = wplj(window).width();
	if((main_win_size <= 480 ))
	{
		wplj('#wpl_property_listing_container .wpl_sort_options_container .wpl_sort_options_container_title').click(function()
		{
			wplj(this).next('ul').stop().slideToggle();
		});
	}

	wplj('.wpl_prp_cont').hover(function(){
		wplj(this).children('.wpl_prp_top').addClass('flip');
	},function(){
		wplj(this).children('.wpl_prp_top').removeClass('flip');
	});	
});

wplj(window).resize(function()
{
	win_size = wplj(window).width();
	if((win_size <= 480 ))
	{
		wplj('#wpl_property_listing_container .wpl_sort_options_container .wpl_sort_options_container_title').unbind('click').click(function()
		{
			wplj(this).next('ul').slideToggle();
		});
	}
	else if(win_size > 480)
	{
		wplj('#wpl_property_listing_container .wpl_sort_options_container .wpl_sort_options_container_title').unbind('click');
		wplj('#wpl_property_listing_container .wpl_sort_options_container ul').show();
	}	
});

function wpl_page_sortchange(order_string)
{
	url = '<?php echo wpl_global::get_full_url(); ?>';
	
	order_obj = order_string.split('&');
	
	order_v1 = order_obj[0].split('=');
	order_v2 = order_obj[1].split('=');
	
	url = wpl_update_qs(order_v1[0], order_v1[1], url);
	url = wpl_update_qs(order_v2[0], order_v2[1], url);
	
	window.location = url;
}

function wpl_pagesize_changed(page_size)
{
	url = '<?php echo wpl_global::get_full_url(); ?>';
	
	url = wpl_update_qs('limit', page_size, url);
	window.location = url;
}
</script>