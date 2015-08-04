<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var wpl_listing_request_str = '<?php echo wpl_global::generate_request_str(); ?>';
var wpl_listing_limit = <?php echo $this->model->limit; ?>;
var wpl_listing_total_pages = <?php echo $this->total_pages; ?>;
var wpl_listing_current_page = <?php echo $this->page_number; ?>;
var wpl_listing_last_search_time = 0;

/** CSS Class **/
var wpl_current_property_css_class = '<?php echo $this->property_css_class; ?>';

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

    if(!Modernizr.csstransitions)
    {
        wplj(".wpl_prp_top").hover(function()
        {
            wplj(this).children('.wpl_prp_top_boxes.front').hide();
        },
        function()
        {
            wplj(this).children('.wpl_prp_top_boxes.front').show();
        });
    }
    
    /** jQuery Triggers **/
    wpl_listing_set_js_triggers();
});

wplj(document).ajaxComplete(function()
{
    /** jQuery Triggers **/
    wpl_listing_set_js_triggers();
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
    url = window.location.href;
	
	order_obj = order_string.split('&');
	
	order_v1 = order_obj[0].split('=');
	order_v2 = order_obj[1].split('=');
	
	url = wpl_update_qs(order_v1[0], order_v1[1], url);
	url = wpl_update_qs(order_v2[0], order_v2[1], url);
	
    /** Move to First Page **/
    url = wpl_update_qs('wplpage', '1', url);
    
	window.location = url;
}

function wpl_pagesize_changed(page_size)
{
    url = window.location.href;
	url = wpl_update_qs('limit', page_size, url);
    
    /** Move to First Page **/
    url = wpl_update_qs('wplpage', '1', url);
    
	window.location = url;
}

var wpl_set_property_css_class_once = false;
function wpl_set_property_css_class(pcc)
{
    /** Run this function only once **/
    if(wpl_set_property_css_class_once) return;
    else wpl_set_property_css_class_once = true;
    
    if((pcc == 'row_box' || pcc == 'grid_box') && typeof wpl_sp_selector_div != 'undefined')
    {
        /** Remove previous scroll listener **/
        wplj(wpl_sp_selector_div).off('scroll', wpl_scroll_pagination_listener);
        
        wpl_sp_selector_div = window;
        wpl_sp_append_div = '#wpl_property_listing_container';
        
        /** Add new scroll listener **/
        var wpl_scroll_pagination_listener = wplj(wpl_sp_selector_div).on('scroll', function()
        {
            wpl_scroll_pagination();
        });
    }
    else if(pcc == 'map_box' && typeof wpl_sp_selector_div != 'undefined')
    {
        /** Remove previous scroll listener **/
        wplj(wpl_sp_selector_div).off('scroll', wpl_scroll_pagination_listener);
        
        wpl_sp_selector_div = '.wpl_property_listing_listings_container';
        wpl_sp_append_div = '.wpl_property_listing_listings_container';
        
        /** Add new scroll listener **/
        var wpl_scroll_pagination_listener = wplj(wpl_sp_selector_div).on('scroll', function()
        {
            wpl_scroll_pagination();
        });
    }
    
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
            wpl_set_property_css_class_once = false;
        }
    });
}

function wpl_listing_set_js_triggers()
{
    wplj('#list_view').on('click', function()
    {
        wplj('#grid_view, #map_view').removeClass('active');
        wplj('#list_view').addClass('active');
        
        wpl_set_property_css_class('row_box');
        
        wplj('.wpl_prp_cont').animate({opacity:0},function()
        {
            wpl_fix_no_image_size();
            
            <?php if(wpl_global::check_addon('aps')): ?>
            wplj('.wpl_property_listing_container').removeClass('wpl-property-listing-mapview');
            <?php endif; ?>
            
            wplj(this).removeClass('grid_box').addClass('row_box');
            wplj(this).stop().animate({opacity:1});
        });
    });

    wplj('#grid_view').on('click', function()
    {
        wplj('#list_view, #map_view').removeClass('active');
        wplj('#grid_view').addClass('active');
        
        wpl_set_property_css_class('grid_box');
        
        wplj('.wpl_prp_cont').animate({opacity:0},function()
        {   
            wpl_fix_no_image_size();
            
            <?php if(wpl_global::check_addon('aps')): ?>
            wplj('.wpl_property_listing_container').removeClass('wpl-property-listing-mapview');
            <?php endif; ?>
            
            wplj(this).removeClass('row_box').addClass('grid_box');
            wplj(this).stop().animate({opacity:1});
        });
    });
    
    <?php if(wpl_global::check_addon('aps')): ?>
    wplj('#map_view').on('click', function()
    {
        wplj('#list_view, #grid_view').removeClass('active');
        wplj('#map_view').addClass('active');
        
        wpl_set_property_css_class('map_box');
        
        wplj('.wpl_property_listing_container').animate({opacity:0},function()
        {
            wplj(this).addClass('wpl-property-listing-mapview');
            wplj(this).stop().animate({opacity:1});
        });
    });
    <?php endif; ?>
}

function wpl_paginate(page)
{
    url = window.location.href;
	url = wpl_update_qs('wplpage', page, url);
    
	window.location = url;
}

function wpl_generate_rss()
{
    var rss = '';
    
    rss = wpl_update_qs('wplpage', '', wpl_listing_request_str);
    rss = wpl_update_qs('wplview', '', rss);
    rss = wpl_update_qs('wplpagination', '', rss);
    
    window.open("<?php echo wpl_property::get_property_rss_link(); ?>?"+rss);
}

<?php if(wpl_global::check_addon('aps')): ?>
function map_view_toggle_listing()
{
    if(wplj('.map_view_handler').hasClass('op'))
    {
        wplj('.wpl_property_listing_list_view_container').animate({'margin-right': '-220px'}, function()
        {
            wplj('.map_view_handler').toggleClass('op');
        });
    }
    else
    {
        wplj('.wpl_property_listing_list_view_container').animate({'margin-right': '0.5%'}, function()
        {
            wplj('.map_view_handler').toggleClass('op');
        });
    }
}
<?php endif; ?>
</script>