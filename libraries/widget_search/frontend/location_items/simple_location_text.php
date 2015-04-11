<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($show == 'simple_location_text' and !$done_this)
{
	/** current values **/
	$current_values = array();
	for($i=1; $i<=2; $i++) $current_values['location'.$i.'_id'] = wpl_request::getVar('sf_select_location'.$i.'_id', '');
	
	$current_values['locationtextsearch'] = stripslashes(wpl_request::getVar('sf_locationtextsearch', ''));
	
	$html .= '
	<script type="text/javascript">
	/** Location Loader **/
	function wpl'.$widget_id.'_search_widget_load_location(level, parent, id)
	{
		var next_level = parseInt(level)+1;
		var html = "";

		/** return if location 2 is loaded **/
		if(next_level >= 3) return;		
		
		/** Remove next location levels **/
		for(i=next_level; i<=2; i++)
		{
			if(wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+i).length)
			{
				/** remove form element **/
				wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+i).remove();
			}
		}
		
        wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+level).after(\'<div class="wpl_search_widget_location_level_container" id="wpl'.$widget_id.'_search_widget_location_level_container\'+next_level+\'"></div>\');
		wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+next_level).html(\'<img src="'.wpl_global::get_wpl_asset_url('img/ajax-loader3.gif').'" />\');
		
		request_str = "wpl_format=f:property_listing:ajax&wpl_function=get_locations&location_level="+next_level+"&current_location_id="+id+"&parent="+parent+"&widget_id='.$widget_id.'";
		
		/** run ajax query **/
		ajax = wpl_run_ajax_query("'.wpl_global::get_full_url().'", request_str);
		ajax.success(function(data)
		{
			if(data.success == 1)
			{
				wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+next_level).html("");
				
				html += \'<label class="wpl_search_widget_location_level_label" for="sf'.$widget_id.'_select_location\'+next_level+\'_id">\'+data.keyword+\'</label>\';
				html += data.html;
				wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+next_level).html(html);
                
                setTimeout(function()
                {
                    if(wplj.fn.chosen != "undefined")
                    {
                        wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+next_level+" select").chosen();
                    }
                }, 200);
			}
			else if(data.success != 1)
			{
				wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+next_level).html("");
			}
		});
	}
	</script>';
	
	$html .= '<div class="wpl_search_widget_location_container" id="wpl'.$widget_id.'_search_fields_location_'.$field['id'].'">';
	
	/** location levels **/
	for($i=1; $i<=2; $i++)
	{
		if($i != 1 and !trim($current_values['location'.($i-1).'_id'])) continue;
		
		$parent = $i != 1 ? $current_values['location'.($i-1).'_id'] : '';
		$current_location_id = $current_values['location'.$i.'_id'];
		$enabled = $i != 1 ? '' : '1';
		
		$locations = wpl_locations::get_locations($i, $parent, $enabled);
		
		/** select first location if just one location exists **/
		if(count($locations) == 1)
		{
			$T_locations = array_values($locations);
			
			if(!trim($current_values['location'.$i.'_id']))
			{
				$current_values['location'.$i.'_id'] = $T_locations[0]->id;
				$current_location_id = $T_locations[0]->id;
			}
		}
		
		if(!count($locations)) break;
		
		$html .= '<div class="wpl_search_widget_location_level_container" id="wpl'.$widget_id.'_search_widget_location_level_container'.$i.'">';
		$html .= '<label class="wpl_search_widget_location_level_label" for="sf'.$widget_id.'_select_location'.$i.'_id">'.$location_settings['location'.$i.'_keyword'].'</label>';
		$html .= '<select name="sf'.$widget_id.'_select_location'.$i.'_id" id="sf'.$widget_id.'_select_location'.$i.'_id" onchange="wpl'.$widget_id.'_search_widget_load_location(\''.$i.'\', this.value, \''.$current_location_id.'\');">';
		$html .= '<option value="-1">'.__((trim($location_settings['location'.$i.'_keyword']) != '' ? $location_settings['location'.$i.'_keyword'] : 'Select'), WPL_TEXTDOMAIN).'</option>';
        
		foreach($locations as $location)
		{
			$html .= '<option value="'.$location->id.'" '.($current_location_id == $location->id ? 'selected="selected"' : '').'>'.__($location->name, WPL_TEXTDOMAIN).'</option>';
		}
		
		$html .= '</select>';
		$html .= '</div>';
	}
	
	/** Location text **/
	$html .= '<div class="wpl_search_widget_location_level_container" id="wpl'.$widget_id.'_search_widget_location_level_container_location_text">';
	$html .= '<label class="wpl_search_widget_location_level_label" for="sf'.$widget_id.'_locationtextsearch">'.__('Location Text', WPL_TEXTDOMAIN).'</label>';
	$html .= '<input class="wpl_search_widget_'.$field['id'].'_location_text" value="'.$current_values['locationtextsearch'].'" name="sf'.$widget_id.'_locationtextsearch" id="sf'.$widget_id.'_locationtextsearch" placeholder="'.__($placeholder, WPL_TEXTDOMAIN).'" type="text" />';
	$html .= '</div>';
	
	$html .= '</div>';
	$done_this = true;
}