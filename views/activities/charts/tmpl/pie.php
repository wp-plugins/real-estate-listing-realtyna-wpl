<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

wpl_extensions::import_javascript((object) array('param1'=>'wpl-jqplot-pie', 'param2'=>'js/jqplot/plugins/jqplot.pieRenderer.min.js'));
$this->data = $params;

$chart_background = (isset($this->data['chart_background']) and trim($this->data['chart_background']) != '') ? $this->data['chart_background'] : '#ffffff';
$chart_title = (isset($this->data['chart_title']) and trim($this->data['chart_title']) != '') ? $this->data['chart_title'] : '';
$show_value = (isset($this->data['show_value']) and trim($this->data['show_value']) != '') ? $this->data['show_value'] : 0;
?>
<div id="chartdiv<?php echo $this->data['unique_chart_id']; ?>" style="width: <?php echo $params['chart_width']; ?>; height: <?php echo $params['chart_height']; ?>;"></div>
<script type="text/javascript">
wplj(document).ready(function()
{
	var s1 = <?php echo $this->render_data($this->data['data'], 'pie'); ?>;
    var plot = wplj.jqplot('chartdiv<?php echo $this->data['unique_chart_id']; ?>', [s1],
	{
		<?php if(trim($chart_title) != '') echo "title: '".$chart_title."',"; ?>
        grid: {
            drawBorder: false,
            drawGridlines: false,
            background: '<?php echo $chart_background; ?>',
            shadow: false
        },
        axesDefaults: {},
        seriesDefaults: {
			shadow: false,
            renderer: wplj.jqplot.PieRenderer,
            rendererOptions: {
                showDataLabels: true,
				<?php if($show_value): ?>dataLabels: 'value',<?php endif; ?>
				sliceMargin: 4
            }
        },
        legend: {show: true}
    });
});
</script>