<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
    wplj('#bxslider_<?php echo $this->property_id; ?>').bxSlider(
	{
        mode: 'fade',
        auto : true,
        captions: false,
        controls: false,
        pagerCustom: '#bx-pager'
    });
});
</script>