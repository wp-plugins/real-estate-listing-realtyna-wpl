<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

wpl_extensions::import_javascript((object) array('param1'=>'pie', 'param2'=>'js/amcharts/pie.js'));
$this->data = $params;
?>
<div id="chartdiv<?php echo $this->data['uniq_chart_id'];?>" style="width: <?php echo $params['chart_width'] ?>; height: <?php echo $params['chart_height'] ?>;"></div>
<script type="text/javascript">
wplj(document).ready(function()
{
	var chart;
	
	AmCharts.ready(function()
	{
		chart = new AmCharts.AmPieChart();
		chart.dataProvider = <?php echo json_encode($this->data['data']); ?>;
		chart.titleField = "<?php echo $this->data['category_field']; ?>";
		chart.valueField = "<?php echo $this->data['value_field']; ?>";
		chart.outlineColor = "#FFFFFF";
		chart.outlineAlpha = 0.8;
		chart.outlineThickness = 2;
		chart.write("chartdiv<?php echo $this->data['uniq_chart_id']; ?>");
	});
});
</script>