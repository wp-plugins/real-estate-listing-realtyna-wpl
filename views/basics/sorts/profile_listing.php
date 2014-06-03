<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** include library **/
_wpl_import('libraries.sort_options');

$result = NULL;

$type = isset($params['type']) ? $params['type'] : 1; # 1 == ul and 0 == selectbox
$return_array = isset($params['return_array']) ? $params['return_array'] : 0;

$sort_options = wpl_sort_options::get_sort_options(2, 1);

$result_array = array();
foreach($sort_options as $sort_option)
{
	$result_array['sort_options'][] = array
	(
		'field_name' => $sort_option['field_name'],
		'url' => '',
		'active' => $this->orderby == $sort_option['field_name'] ? 1 : 0,
		'order' => ($this->order == 'DESC' and $this->orderby == $sort_option['field_name']) ? 'ASC' : 'DESC',
		'name' => $sort_option['name']
	);
}

$html = '';
if($type == 0)
{
	$html .= '<select class="wpl_plist_sort" onchange="wpl_page_sortchange(this.value);">';
	
	foreach($sort_options as $sort_option)
	{
		$html .= '<option value="wplorderby='.urlencode($sort_option['field_name']).'&amp;wplorder=ASC" '.(($this->orderby == $sort_option['field_name'] and $this->order == 'ASC') ? 'selected="selected"' : '').'>'.__($sort_option['name'], WPL_TEXTDOMAIN).' '.__('Ascending', WPL_TEXTDOMAIN).'</option>';
		$html .= '<option value="wplorderby='.urlencode($sort_option['field_name']).'&amp;wplorder=DESC" '.(($this->orderby == $sort_option['field_name'] and $this->order == 'DESC') ? 'selected="selected"' : '').'>'.__($sort_option['name'], WPL_TEXTDOMAIN).' '.__('Descending', WPL_TEXTDOMAIN).'</option>';
	}
	
	$html .= '</select>';
}
elseif($type == 1)
{
	$html .= '<ul>';
	$sort_type = '';

	foreach($sort_options as $sort_option)
	{
		$class = "wpl_plist_sort";
		
		if($this->orderby == $sort_option['field_name']) $class = "wpl_plist_sort wpl_plist_sort_active";
		$order = ($this->order == "ASC" ? "DESC" : "ASC");
		
		$html .= '<li><div class="'.$class;
		
		if($this->orderby == $sort_option['field_name'])
		{
			if($this->order == "ASC") $sort_type = 'sort_up';
			else $sort_type = 'sort_down';
			
			$html .= ' '.$sort_type;
		}
		
		$html .= '" onclick="wpl_page_sortchange(\'wplorderby='.urlencode($sort_option['field_name']).'&amp;wplorder='.$order.'\');">'.__($sort_option['name'], WPL_TEXTDOMAIN);
		$html .= '</div></li>';
	}
	
	$html .= '</ul>';
}

$result_array['html'] = $html;

if($return_array) $result = $result_array;
else $result = $html;