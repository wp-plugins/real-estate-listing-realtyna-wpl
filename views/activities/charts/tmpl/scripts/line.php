<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	var plot = wplj.jqplot('chartdiv<?php echo $this->data['unique_chart_id'];?>', <?php echo $this->render_data($this->data['data'], 'line'); ?>,
	{
		<?php if(trim($this->chart_title) != '') echo "title: '".$this->chart_title."',"; ?>
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