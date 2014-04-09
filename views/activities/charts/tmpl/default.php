<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

wpl_extensions::import_javascript((object) array('param1'=>'wpl-jqplot-canvasTextRenderer', 'param2'=>'js/jqplot/plugins/jqplot.canvasTextRenderer.min.js'));
wpl_extensions::import_javascript((object) array('param1'=>'wpl-jqplot-canvasAxisLabelRenderer', 'param2'=>'js/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js'));
$this->data = $params;

$chart_background = (isset($this->data['chart_background']) and trim($this->data['chart_background']) != '') ? $this->data['chart_background'] : '';
$chart_title = (isset($this->data['chart_title']) and trim($this->data['chart_title']) != '') ? $this->data['chart_title'] : '';
?>
<div id="chartdiv<?php echo $this->data['unique_chart_id']; ?>" style="width: <?php echo $params['chart_width']; ?>; height: <?php echo $params['chart_height']; ?>;"></div>
<script type="text/javascript">
wplj(document).ready(function()
{
	var plot = wplj.jqplot('chartdiv<?php echo $this->data['unique_chart_id'];?>', <?php echo $this->render_data($this->data['data'], 'line'); ?>,
	{
		<?php if(trim($chart_title) != '') echo "title: '".$chart_title."',"; ?>
		axesDefaults: {
			labelRenderer: wplj.jqplot.CanvasAxisLabelRenderer
		},
		seriesDefaults: {
		  rendererOptions: {
			  smooth: true
		  }
		},
		axes: {}
    });
});
</script>