<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.js", true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 1920;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 558;

$thumbnail_width = isset($this->instance['data']['thumbnail_width']) ? $this->instance['data']['thumbnail_width'] : 150;
$thumbnail_height = isset($this->instance['data']['thumbnail_height']) ? $this->instance['data']['thumbnail_height'] : 60;
$auto_play = isset($this->instance['data']['auto_play']) ? $this->instance['data']['auto_play'] : true;
$slide_interval = isset($this->instance['data']['slide_interval']) ? $this->instance['data']['slide_interval'] : 3000;

/** add Layout js **/
$js[] = (object) array('param1'=>'elastic.slideshow', 'param2'=>'packages/elastic_slideshow/jquery.eislideshow.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

$larg_images = $thumbnail = NULL;
foreach($wpl_properties as $key=>$gallery)
{
    if(isset($gallery["items"]["gallery"][0]))
    {
        $params = array();
        $params['image_name'] 		= $gallery["items"]["gallery"][0]->item_name;
        $params['image_parentid'] 	= $gallery["items"]["gallery"][0]->parent_id;
        $params['image_parentkind'] = $gallery["items"]["gallery"][0]->parent_kind;
        $params['image_source'] 	= wpl_global::get_upload_base_path().$params['image_parentid'].DS.$params['image_name'];

        if(isset($gallery['materials']['field_313']) and trim($gallery['materials']['field_313']['value']) != '') $image_title = $gallery['materials']['field_313']['value'];
        else $image_title = $gallery['materials']['property_type']['value'] .' '.$gallery['materials']['listing']['value'];
        if(isset($gallery['items']['gallery'][0]->item_extra2) and trim($gallery['items']['gallery'][0]->item_extra2) != '') $image_alt = $gallery['items']['gallery'][0]->item_extra2;
        else $image_alt = $gallery['raw']['meta_keywords'];

        $image_description	= $gallery["items"]["gallery"][0]->item_extra2;

        if($gallery["items"]["gallery"][0]->item_cat != 'external')
        {
            $image_url 			= wpl_images::create_gallary_image($image_width, $image_height, $params);
            $thumbnail_url 		= wpl_images::create_gallary_image($thumbnail_width, $thumbnail_height, $params);
        }
        else
        {
            $image_url 			= $gallery["items"]["gallery"][0]->item_extra3;
            $thumbnail_url 		= $gallery["items"]["gallery"][0]->item_extra3;
        }

        $larg_images .= '
		<li>
            <img itemprop="image" src="'.$image_url.'" alt="'.$image_alt.'" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" />
            <div class="ei-title">
                <h2>'.$image_title.'</h2>
                <h3>'.$gallery["rendered"][10]["value"].' - '.$gallery["location_text"].'</h3>
                <a itemprop="url" class="more_info" href="'.$gallery["property_link"].'">'. __('More info', WPL_TEXTDOMAIN).'</a>
            </div>
        </li>';

        $thumbnail	.='<li><a href="#">'.$image_title.'</a><img src="'.$thumbnail_url.'" alt="'.$image_alt.'" width="'.$thumbnail_width.'" height="'.$thumbnail_height.'" style="width: '.$thumbnail_width.'px; height: '.$thumbnail_height.'px;" /></li>';
    }
}
?>
<script type="text/javascript">
    wplj(function()
    {
        <?php if(count($wpl_properties) > 1): ?>
        wplj('#wpl-modern-<?php echo $this->widget_id; ?>').eislideshow(
            {
                animation			: 'center',
                autoplay			: <?php echo $auto_play; ?>,
                slideshow_interval	: <?php echo $slide_interval ?>,
                titlesFactor		: 0,
                thumbMaxWidth       : <?php echo $thumbnail_width ?>
            });
        <?php endif; ?>
    });
</script>
<div class="wpl_carousel_container">
    <div id="wpl-modern-<?php echo $this->widget_id; ?>" class="ei-slider">
        <ul class="ei-slider-large">
            <?php echo $larg_images; ?>
        </ul>
        <ul class="ei-slider-thumbs">
            <li class="ei-slider-element">Current</li>
            <?php echo $thumbnail; ?>
        </ul>
    </div>
</div>
