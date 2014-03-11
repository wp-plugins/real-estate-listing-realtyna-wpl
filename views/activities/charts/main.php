<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** activity class **/
class wpl_activity_main_charts extends wpl_activity
{
    var $tpl_path = 'views.activities.charts.tmpl';
	
	public function start($layout, $params)
	{
        wpl_extensions::import_javascript((object) array('param1' => 'amcharts', 'param2' => 'js/amcharts/amcharts.js'));
        $params['uniq_chart_id'] = md5(uniqid(time().mt_rand(0, mt_getrandmax()), true));
        
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}