<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

/** get image params **/
$image_width = isset($params['image_width']) ? $params['image_width'] : 285;
$image_height = isset($params['image_height']) ? $params['image_height'] : 200;
$image_class = isset($params['image_class']) ? $params['image_class'] : '';
$rewrite = (isset($params['rewrite']) and trim($params['rewrite']) != '') ? $params['rewrite'] : 0;
$watermark = (isset($params['watermark']) and trim($params['watermark']) != '') ? $params['watermark'] : 0;

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();
$gallery = wpl_items::render_gallery($raw_gallery);
?>
<div class="wpl_gallery_container" id="wpl_gallery_container<?php echo $property_id; ?>" >
    <?php
    if(!count($gallery))
    {
        echo '
            <div class="no_image_box">
                <i class="icon-no-pic"></i>
                <span>'.__('No Image', WPL_TEXTDOMAIN).'</span>
            </div>';
    }
	else
    {
        $pimage = $gallery[0];
        $image_url = $pimage['url'];
        
        if($image_width and $image_height and $pimage['category'] != 'external')
        {
            /** set resize method parameters **/
            $params = array();
            $params['image_name'] = $pimage['raw']['item_name'];
            $params['image_parentid'] = $pimage['raw']['parent_id'];
            $params['image_parentkind'] = $pimage['raw']['parent_kind'];
            $params['image_source'] = $pimage['path'];

            /** resize image if does not exist **/
            $image_url = wpl_images::create_gallary_image($image_width, $image_height, $params, $watermark, $rewrite);
        }
        
        echo '<img id="wpl_gallery_image'.$property_id.'" src="'.$image_url.'" class="wpl_gallery_image '.$image_class.'" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" />';
	}
	?>
</div>