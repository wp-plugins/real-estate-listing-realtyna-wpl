<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** activity class **/
class wpl_activity_main_charts extends wpl_activity
{
    public $tpl_path = 'views.activities.charts.tmpl';
	
	public function start($layout, $params)
	{
        wpl_extensions::import_javascript((object) array('param1'=>'wpl-jqplot', 'param2'=>'packages/jqplot/jquery.jqplot.min.js'));
		wpl_extensions::import_style((object) array('param1'=>'wpl-jqplot', 'param2'=>'packages/jqplot/jquery.jqplot.min.css'));
        $params['unique_chart_id'] = md5(uniqid(time().mt_rand(0, mt_getrandmax()), true));
        
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
	
	private function render_data($data, $chart_type = 'pie')
	{
		/** return if data is string **/
		if(is_string($data)) return stripslashes($data);
		
		$rendered1 = '';
		$rendered2 = '';
		
        foreach($data as $key=>$value)
		{
			if(trim($key) == '' or trim($value) == '') continue;
			
			if($chart_type == 'pie') $rendered1 .= '["'.$key.'",'.$value.'], ';
			elseif($chart_type == 'bar')
			{
				$rendered1 .= $value.', ';
				$rendered2 .= "'".$key."', ";
			}
			elseif($chart_type == 'line') $rendered1 .= $value.', ';
		}
		
		if($chart_type == 'pie') return '['.trim($rendered1, ', ').']';
		elseif($chart_type == 'bar') return array('['.trim($rendered1, ', ').']', '['.trim($rendered2, ', ').']');
		elseif($chart_type == 'line') return '[['.trim($rendered1, ', ').']]';
	}
}