<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$html_element_id = isset($params['html_element_id']) ? $params['html_element_id'] : '';
$root_url = isset($params['root_url']) ? $params['root_url'] : wpl_global::get_full_url();
$element_class = isset($params['element_class']) ? $params['element_class'] : 'location_breadcrumb';
$separator = isset($params['separator']) ? $params['separator'] : ' > ';
$location_level = isset($params['location_level']) ? $params['location_level'] : 1;
$location_id = isset($params['location_id']) ? $params['location_id'] : '';
$load_zipcodes = isset($params['load_zipcodes']) ? $params['load_zipcodes'] : 0;

$location_tree = wpl_locations::get_location_tree($location_id, ($location_level-1));
$levels = count($location_tree)+1;
$breadcrumb_str = "";

$i = 1;
foreach($location_tree as $branch)
{
	if(trim($branch['name']) == '') continue;
	
	$link = wpl_global::add_qs_var('level', $levels, $root_url);
	$link = wpl_global::add_qs_var('sf_select_parent', $branch['id'], $link);
	if(($load_zipcodes and $i == 1)) $link = wpl_global::add_qs_var('load_zipcodes', 1, $link);
	
	$breadcrumb_str = $separator.'<a href="'.$link.'">'.(($load_zipcodes and $i == 1) ? $branch['name'].' ('.__('Zipcodes', WPL_TEXTDOMAIN).')' : $branch['name']).'</a>'.$breadcrumb_str;
	$levels--;
	$i++;
}
?>
<div class="<?php echo $element_class; ?>" id="<?php echo $html_element_id; ?>">
	<a href="<?php echo $root_url; ?>"><?php echo __('All Countries', WPL_TEXTDOMAIN); ?></a>
    <?php echo $breadcrumb_str; ?>
    <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_location_fancybox_cnt" class="wpl_create_new action-btn icon-plus" id="wpl_add_location_item" onclick="wpl_generate_modify_page('<?php echo (!$load_zipcodes ? $location_level : 'zips'); ?>','<?php echo $location_id; ?>')" title="<?php echo __('Add location', WPL_TEXTDOMAIN); ?>"></span>
</div>