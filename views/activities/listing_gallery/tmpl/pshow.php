<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

/** get image params **/
$image_width = isset($params['image_width']) ? $params['image_width'] : 360;
$image_height = isset($params['image_height']) ? $params['image_height'] : 285;
$image_class = isset($params['image_class']) ? $params['image_class'] : '';
$rewrite = (isset($params['rewrite']) and trim($params['rewrite']) != '') ? $params['rewrite'] : 0;
$watermark = (isset($params['watermark']) and trim($params['watermark']) != '') ? $params['watermark'] : 1;

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();
$gallery = wpl_items::render_gallery($raw_gallery);

$js[] = (object) array('param1'=>'jquery.bxslider', 'param2'=>'js/jquery.bxslider.min.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);
?>
<script type="text/javascript">
wplj(document).ready(function()
{
    wplj('#bxslider_<?php echo $property_id; ?>').bxSlider(
	{
        mode: 'fade',
        auto : true,
        captions: false,
        controls: false,
        pagerCustom: '#bx-pager'
    });
});
</script>
<div class="wpl_gallery_container" id="wpl_gallery_container<?php echo $property_id; ?>">
    <?php
    if(!count($gallery)) 
    {
        echo '<div class="gallery_no_image"></div>';
    }
    else 
    {
        echo '<ul class="bxslider" id="bxslider_'.$property_id.'">';
        $i = 0;
        $pager_box = '';
		
        foreach($gallery as $image)
        {
            if($image_width and $image_height)
            {
                /** set resize method parameters **/
                $params = array();
                $params['image_name'] = $image['raw']['item_name'];
                $params['image_parentid'] = $image['raw']['parent_id'];
                $params['image_parentkind'] = $image['raw']['parent_kind'];
                $params['image_source'] = $image['path'];
                
                /** resize image if does not exist and add watermark **/
                $image_url = wpl_images::create_gallary_image($image_width, $image_height, $params, $watermark, $rewrite);
                $image_thumbnail_url = wpl_images::create_gallary_image(100, 80, $params, $watermark, $rewrite);
            }
            
            /** start loading images **/
            echo '<li><img src="'.$image_url.'" title="" class="wpl_gallery_image '.$image_class.'" id="wpl_gallery_image'.$image['raw']['id'].'" width="'.$image_width.'" height="'.$image_height.'" /></li>';
            $pager_box .= '<a data-slide-index="'.$i.'" href=""><img src="'.$image_thumbnail_url.'" width="100" height="80"/></a>';
        	$i++;
        }
		
        echo '</ul>';
    ?>
    <div id="bx-pager">
        <?php echo $pager_box; ?>
    </div>
    <?php } ?>
    
</div>