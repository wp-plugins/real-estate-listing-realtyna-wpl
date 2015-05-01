<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($show == 'simple_location_database' and !$done_this)
{
	/** current values **/
	$current_values = array();
	for($i=1; $i<=7; $i++) $current_values['location'.$i.'_id'] = wpl_request::getVar('sf_select_location'.$i.'_id', '');
	
	$current_values['zip_id'] = wpl_request::getVar('sf_select_zip_id', '');
	
	$html .= '
	<script type="text/javascript">
	/** Location Loader **/
	function wpl'.$widget_id.'_search_widget_load_location(level, parent, id)
	{
		var next_level = parseInt(level)+1;
		var html = "";
		
		/** Remove zipcode level **/
		if(level != \'zips\')
		{
			if(wplj("#wpl'.$widget_id.'_search_widget_location_level_containerzips").length)
			{
				/** remove form element **/
				wplj("#wpl'.$widget_id.'_search_widget_location_level_containerzips").remove();
			}
		}

		/** Remove next location levels **/
		for(i=next_level; i<=7; i++)
		{
			if(wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+i).length)
			{
				/** remove form element **/
				wplj("#wpl'.$widget_id.'_search_widget_location_level_container"+i).remove();
			}
		}
		
		/** load zipcodes **/
		if(next_level > '.$location_settings['zipcode_parent_level'].') next_level = "zips";
		
		wplj("#wpl'.$widget_id.'_search_fields_location_'.$field['id'].'").append(\'<div class="wpl_search_widget_location_level_container" id="wpl'.$widget_id.'_search_widget_location_level_container\'+next_level+\'"></div>\');
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
	
	for($i=1; $i<=7; $i++)
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
	
	if($current_values['zip_id'])
	{
		$parent = $current_values['location'.($location_settings['zipcode_parent_level']).'_id'];
		$current_location_id = $current_values['zip_id'];
		
		$locations = wpl_locations::get_locations('zips', $parent, '');
		
		if(count($locations))
		{
			$html .= '<div class="wpl_search_widget_location_level_container" id="wpl'.$widget_id.'_search_widget_location_level_containerzips">';
			$html .= '<label class="wpl_search_widget_location_level_label" for="sf'.$widget_id.'_select_zip_id">'.$location_settings['locationzips_keyword'].'</label>';
			$html .= '<select name="sf'.$widget_id.'_select_zip_id" id="sf'.$widget_id.'_select_zip_id">';
			$html .= '<option value="-1">'.__((trim($location_settings['locationzips_keyword']) != '' ? $location_settings['locationzips_keyword'] : 'Select'), WPL_TEXTDOMAIN).'</option>';
			
			foreach($locations as $location)
			{
				$html .= '<option value="'.$location->id.'" '.($current_location_id == $location->id ? 'selected="selected"' : '').'>'.__($location->name, WPL_TEXTDOMAIN).'</option>';
			}
			
			$html .= '</select>';
			$html .= '</div>';
		}
	}
	
	$html .= '</div>';
	
	$done_this = true;
}