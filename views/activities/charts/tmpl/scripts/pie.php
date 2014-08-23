<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	var s1 = <?php echo $this->render_data($this->data['data'], 'pie'); ?>;
    var plot = wplj.jqplot('chartdiv<?php echo $this->data['unique_chart_id']; ?>', [s1],
	{
		<?php if(trim($this->chart_title) != '') echo "title: '".$this->chart_title."',"; ?>
        grid: {
            drawBorder: false,
            drawGridlines: false,
            background: '<?php echo $this->chart_background; ?>',
            shadow: false
        },
        axesDefaults: {},
        seriesDefaults: {
			shadow: false,
            renderer: wplj.jqplot.PieRenderer,
            rendererOptions: {
                showDataLabels: true,
				<?php if($this->show_value): ?>dataLabels: 'value',<?php endif; ?>
				sliceMargin: 4
            }
        },
        legend: {show: true}
    });
});
</script>