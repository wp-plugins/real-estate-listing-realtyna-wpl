<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($show == 'radiussearch' and !$done_this)
{
	/** importing library **/
	_wpl_import('libraries.units');
	
	/** add scripts and style sheet **/
	wp_enqueue_script('jquery-ui-autocomplete');
		
	/** current values **/
	$current_value = wpl_request::getVar('sf_radiussearch');
	$current_unit = wpl_request::getVar('sf_radiussearchunit');
	$current_radius = wpl_request::getVar('sf_radiussearchradius', 10);
	
	/** get units **/
	$units = wpl_units::get_units(1);
	
	$great_units = array();
	foreach($units as $unit) if($unit['tosi'] > 100) $great_units[] = $unit;
	
	$html .= '<div class="wpl_search_widget_location_level_container" id="wpl'.$widget_id.'_search_widget_location_level_container_location_text">';
	$html .= '<label class="wpl_search_widget_location_level_label" for="sf'.$widget_id.'_radiussearch_id">'.__('Radius', WPL_TEXTDOMAIN).'<img src="'.wpl_global::get_wpl_asset_url('img/radius.png').'" alt="'.__('Radius', WPL_TEXTDOMAIN).'" style="vertical-align: middle;" /></label>';
	$html .= '<input class="wpl_search_widget_radiussearchradius_textbox" type="text" id="sf'.$widget_id.'_radiussearchradius" name="sf'.$widget_id.'_radiussearchradius" value="'.$current_radius.'" style="width: 50px;" />';
	
	if(count($great_units) == 0)
	{
		$html .= '<span class="wpl_search_widget_radius_unit_label">'.__('km', WPL_TEXTDOMAIN).'</span><input class="wpl_search_widget_radiussearchunit_hidden" id="sf'.$widget_id.'_radiussearchunit" name="sf'.$widget_id.'_radiussearchunit" type="hidden" value="13" />';
	}
	elseif(count($great_units) == 1)
	{
		$html .= '<span class="wpl_search_widget_radius_unit_label">'.$unit['name'].'</span><input class="wpl_search_widget_radiussearchunit_hidden" id="sf'.$widget_id.'_radiussearchunit" name="sf'.$widget_id.'_radiussearchunit" type="hidden" value="'.$unit['id'].'" />';
	}
	else
	{
		$html .= '<select class="wpl_search_widget_radius_unit_selectbox" name="sf'.$widget_id.'_radiussearchunit" id="sf'.$widget_id.'_radiussearchunit">';
		foreach($great_units as $unit) $html .= '<option value="'.$unit['id'].'" '.($current_unit == $unit['id'] ? 'selected="selected"' : '').'>'.$unit['name'].'</option>';
		$html .= '</select>';
	}
	
	$html .= '<input class="wpl_search_widget_location_textsearch" value="'.$current_value.'" name="sf'.$widget_id.'_radiussearch" id="sf'.$widget_id.'_radiussearch" placeholder="'.__('Zip-code, City, County', WPL_TEXTDOMAIN).'" />';
	
	$html .= '
	<script type="text/javascript">
	var autocomplete_cache = {};
	
	wplj(document).ready(function()
	{
		wplj("#sf'.$widget_id.'_radiussearch").autocomplete(
		{
			search : function(){},
			open : function(){wplj(this).removeClass("ui-corner-all").addClass("ui-corner-top");},
			close : function(){wplj(this).removeClass("ui-corner-top").addClass("ui-corner-all");},
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
				
				wplj.ajax(
				{
					type: "GET",
					url: "'.wpl_global::get_wp_site_url().'?wpl_format=f:property_listing:ajax&wpl_function=locationtextsearch_autocomplete&term="+request.term,
					contentType: "application/json; charset=utf-8",
					success: function (msg)
					{
					   response(wplj.parseJSON(msg));
					   autocomplete_cache[request.term.toUpperCase()] = wplj.parseJSON(msg);
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
	});
	</script>';
	
	$html .= '</div>';
	$done_this = true;
}