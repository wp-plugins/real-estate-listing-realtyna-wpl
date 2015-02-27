<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
    wplj('#list_view').click(function()
    {
        wplj('#grid_view').removeClass('active');
        wplj('#list_view').addClass('active');
        
        wpl_set_property_css_class('row_box');
        
        wplj('.wpl_prp_cont').animate({opacity:0},function()
        {
            wplj(this).removeClass('grid_box').addClass('row_box');
            wplj(this).stop().animate({opacity:1});
        });
    });

    wplj('#grid_view').click(function()
    {
        wplj('#list_view').removeClass('active');
        wplj('#grid_view').addClass('active');
        
        wpl_set_property_css_class('grid_box');
        
        wplj('.wpl_prp_cont').animate({opacity:0},function()
        {
            wplj(this).removeClass('row_box').addClass('grid_box');
            wplj(this).stop().animate({opacity:1});
        });
    });
    
	main_win_size = wplj(window).width();
	if((main_win_size <= 480 ))
	{
		wplj('#wpl_property_listing_container .wpl_sort_options_container .wpl_sort_options_container_title').click(function()
		{
			wplj(this).next('ul').stop().slideToggle();
		});
	}

    if(!Modernizr.csstransitions)
    {
        wplj(".wpl_prp_top").hover(function ()
        {
            wplj(this).children('.wpl_prp_top_boxes.front').hide();
        },
        function()
        {
            wplj(this).children('.wpl_prp_top_boxes.front').show();
        });
    }

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

var wpl_current_property_css_class;
function wpl_set_property_css_class(pcc)
{
    wpl_current_property_css_class = pcc;
    
    wplj.ajax(
    {
        url: '<?php echo wpl_global::get_full_url(); ?>',
        data: 'wpl_format=f:property_listing:ajax&wpl_function=set_pcc&pcc='+pcc,
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(data)
        {
        }
    });
}
</script>