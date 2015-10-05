<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($show == 'locationtextsearch' and !$done_this)
{
	/** add scripts and style sheet **/
	wp_enqueue_script('jquery-ui-autocomplete');
		
	/** current values **/
	$current_value = stripslashes(wpl_request::getVar('sf_locationtextsearch', ''));
	
	$html .= '<div class="wpl_search_widget_location_level_container" id="wpl'.$widget_id.'_search_widget_location_level_container_location_text">';
	$html .= '<input class="wpl_search_widget_location_textsearch" value="'.$current_value.'" name="sf'.$widget_id.'_locationtextsearch" id="sf'.$widget_id.'_locationtextsearch" placeholder="'.__($placeholder, WPL_TEXTDOMAIN).'" />';
	
	$html .= '
	<script type="text/javascript">
	var autocomplete_cache = {};
	(function($){$(function()
    {
		$("#sf'.$widget_id.'_locationtextsearch").autocomplete(
		{
			search : function(){},
			open : function(){$(this).removeClass("ui-corner-all").addClass("ui-corner-top");},
			close : function(){$(this).removeClass("ui-corner-top").addClass("ui-corner-all");},
			source: function(request, response)
			{
				var term = request.term.toUpperCase(), items = [];
				
				for(var key in autocomplete_cache)
				{
					if(key === term)
					{
						response(autocomplete_cache[key]);
						return;
					}
				}
				
				$.ajax(
				{
					type: "GET",
					url: "'.wpl_global::get_wp_site_url().'?wpl_format=f:property_listing:ajax&wpl_function=locationtextsearch_autocomplete&term="+request.term,
					contentType: "application/json; charset=utf-8",
					success: function (msg)
					{
					   response($.parseJSON(msg));
					   autocomplete_cache[request.term.toUpperCase()] = $.parseJSON(msg);
					},
					error: function (msg)
					{
					}
				});
			},
			width: 260,
			matchContains: true,
			minChars: 1,
			delay: 300
		});
	});})(jQuery);
	</script>';
	$html .= '</div>';
	
	$done_this = true;

}