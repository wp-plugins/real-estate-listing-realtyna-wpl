<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_plisting_slider(i, property_id, images_total)
{
    if ((i+1)>=images_total) j=0; else j=i+1;
    if (j==i) return;
    
    wplj("#wpl_gallery_image"+property_id+"_"+i).fadeTo(200,0).css("display",'none');
    wplj("#wpl_gallery_image"+property_id+"_"+j).fadeTo(400,1);
}
</script>