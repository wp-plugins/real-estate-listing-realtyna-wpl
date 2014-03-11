<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

wpl_extensions::import_javascript((object) array('param1'=>'serial', 'param2'=>'js/amcharts/serial.js'));
$this->data = $params;
?>
<div id="chartdiv<?php echo $this->data['uniq_chart_id'];?>" style="width: <?php echo $params['chart_width'] ?>; height: <?php echo $params['chart_height'] ?>;"></div>
<script type="text/javascript">
wplj(document).ready(function()
{
	var chart;
	
	AmCharts.ready(function()
	{
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = <?php echo json_encode($this->data['data']); ?>;
		chart.categoryField = "<?php echo $this->data['category_field']; ?>";
		chart.startDuration = 1;

		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = <?php echo $this->data['label_rotation']; ?>;
		categoryAxis.gridPosition = "start";

		var graph = new AmCharts.AmGraph();
		graph.valueField = "<?php echo $this->data['value_field']; ?>";
		graph.balloonText = "[[category]]: <b><span style='font-size:<?php echo $this->data['ballon_text_size']; ?>;'>[[value]]</span></b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 0.8;
		chart.addGraph(graph);

		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);

		chart.creditsPosition = "top-right";

		chart.write("chartdiv<?php echo $this->data['uniq_chart_id']; ?>");
	});
});
</script>