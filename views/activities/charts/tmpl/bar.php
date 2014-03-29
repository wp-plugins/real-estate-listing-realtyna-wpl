<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

wpl_extensions::import_javascript((object) array('param1'=>'wpl-jqplot-barrenderer', 'param2'=>'js/jqplot/plugins/jqplot.barRenderer.min.js'));
wpl_extensions::import_javascript((object) array('param1'=>'wpl-jqplot-categoryAxisRenderer', 'param2'=>'js/jqplot/plugins/jqplot.categoryAxisRenderer.min.js'));
$this->data = $params;

$chart_background = (isset($this->data['chart_background']) and trim($this->data['chart_background']) != '') ? $this->data['chart_background'] : '';
$chart_title = (isset($this->data['chart_title']) and trim($this->data['chart_title']) != '') ? $this->data['chart_title'] : '';
$rendered = $this->render_data($this->data['data'], 'bar');
?>
<div id="chartdiv<?php echo $this->data['unique_chart_id']; ?>" style="width: <?php echo $params['chart_width']; ?>; height: <?php echo $params['chart_height']; ?>;"></div>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj.jqplot.config.enablePlugins = true;
	var s1 = <?php echo $rendered[0]; ?>;
	var ticks = <?php echo $rendered[1]; ?>;
	
	plot = wplj.jqplot('chartdiv<?php echo $this->data['unique_chart_id']; ?>', [s1],
	{
		<?php if(trim($chart_title) != '') echo "title: '".$chart_title."',"; ?>
		animate: !wplj.jqplot.use_excanvas,
		seriesDefaults: {
			renderer: wplj.jqplot.BarRenderer,
			pointLabels: { show: true }
		},
		axes: {
			xaxis: {
				renderer: wplj.jqplot.CategoryAxisRenderer,
				ticks: ticks
			}
		},
		highlighter: { show: true }
	});
});
</script>