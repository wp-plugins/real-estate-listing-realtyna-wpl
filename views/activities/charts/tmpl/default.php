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
	var graph;
	
	AmCharts.ready(function()
	{
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = <?php echo json_encode($this->data['data']); ?>;
		chart.marginLeft = 10;
		chart.categoryField = "<?php echo $this->data['category_field']; ?>";
		chart.dataDateFormat = "<?php echo $this->data['data_date_format']; ?>";
		chart.startDuration = 1;
		chart.pathToImages = "<?php echo wpl_global::get_wpl_asset_url('js/amcharts/images/');?>"

		var categoryAxis = chart.categoryAxis;
		categoryAxis.parseDates = true;
		categoryAxis.minPeriod = "<?php echo $this->data['min_period']; ?>";
		categoryAxis.dashLength = 3;
		categoryAxis.labelRotation = <?php echo $this->data['label_rotation']; ?>;
		categoryAxis.minorGridEnabled = true;
		categoryAxis.minorGridAlpha = 0.1;

		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.axisAlpha = 0;
		valueAxis.inside = true;
		valueAxis.dashLength = 3;
		chart.addValueAxis(valueAxis);

		graph = new AmCharts.AmGraph();
		graph.type = "smoothedLine";
		graph.lineColor = "#d1655d";
		graph.negativeLineColor = "#637bb6";
		graph.bullet = "round";
		graph.bulletSize = 8;
		graph.bulletBorderColor = "#FFFFFF";
		graph.bulletBorderAlpha = 1;
		graph.bulletBorderThickness = 2;
		graph.lineThickness = 2;
		graph.valueField = "<?php echo $this->data['value_field']; ?>";
		graph.balloonText = "[[category]]<br><b><span style='font-size:<?php echo $this->data['ballon_text_size']; ?>;'>[[value]]</span></b>";
		chart.addGraph(graph);

		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.cursorPosition = "mouse";
		chartCursor.categoryBalloonDateFormat = "<?php echo $this->data['ballon_data_type_format']; ?>";
		chart.addChartCursor(chartCursor);

		var chartScrollbar = new AmCharts.ChartScrollbar();
		chart.addChartScrollbar(chartScrollbar);

		chart.creditsPosition = "bottom-right";
		chart.write("chartdiv<?php echo $this->data['uniq_chart_id']; ?>");
	});
});
</script>