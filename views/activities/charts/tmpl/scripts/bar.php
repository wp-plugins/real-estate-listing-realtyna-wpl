<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj.jqplot.config.enablePlugins = true;
	var s1 = <?php echo $this->rendered[0]; ?>;
	var ticks = <?php echo $this->rendered[1]; ?>;
	
	plot = wplj.jqplot('chartdiv<?php echo $this->data['unique_chart_id']; ?>', [s1],
	{
		<?php if(trim($this->chart_title) != '') echo "title: '".$this->chart_title."',"; ?>
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