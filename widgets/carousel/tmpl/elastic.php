<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.js", true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 1920;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 558;

$thumbnail_width = isset($this->instance['data']['thumbnail_width']) ? $this->instance['data']['thumbnail_width'] : 150;
$thumbnail_height = isset($this->instance['data']['thumbnail_height']) ? $this->instance['data']['thumbnail_height'] : 60;

$slide_interval = isset($this->instance['data']['slide_interval']) ? $this->instance['data']['slide_interval'] : 3000;
$slide_fillmode = isset($this->instance['data']['slide_fillmode']) ? $this->instance['data']['slide_fillmode'] : 0;

/** add Layout js **/
$js[] = (object) array('param1'=>'jssor', 'param2'=>'js/jssor/jssor.js');
$js[] = (object) array('param1'=>'jssor.slider', 'param2'=>'js/jssor/jssor.slider.js');
$js[] = (object) array('param1'=>'jssor.transitions', 'param2'=>'js/jssor/jssor.transitions.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

$larg_images = $thumbnail = NULL;
foreach($wpl_properties as $key=>$gallery)
{
    if(!isset($gallery["items"]["gallery"][0])) continue;

    $params = array();
    $params['image_name'] 		= $gallery["items"]["gallery"][0]->item_name;
    $params['image_parentid'] 	= $gallery["items"]["gallery"][0]->parent_id;
    $params['image_parentkind'] = $gallery["items"]["gallery"][0]->parent_kind;
    $params['image_source'] 	= wpl_global::get_upload_base_path().$params['image_parentid'].DS.$params['image_name'];

    $image_title = isset($gallery['property_title']) ? $gallery['property_title'] : wpl_property::update_property_title($gallery['raw']);

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
    <div class="jssor_slide">
        <img u="image" itemprop="image" src="'.$image_url.'" alt="'.$image_alt.'"  />
        <img u="thumb" src="'.$thumbnail_url.'" alt="'.$image_alt.'"  />
        <h2 class="jssor_caption jssor_title" t="L2" u="caption">'.$image_title.'</h2>
        <h3 class="jssor_caption jssor_desc" t="R2" u="caption">'.(isset($gallery['materials']['living_area']) ? $gallery['materials']['living_area']['value'].' - ' : '').$gallery["location_text"].'</h3>
        <a itemprop="url" class="jssor_caption more_info"  t="B" u="caption" href="'.$gallery["property_link"].'">'. __('More info', WPL_TEXTDOMAIN).'</a>
    </div>';

}
$thumbnail	.='
    <div u="thumbnavigator" class="jssor_thumbs_container" >
        <div u="slides" class="jssor_thumbs">
            <div u="prototype" class="jssor_thumb_prototype ">
                <div u="thumbnailtemplate" class="jssor_thumb_img"></div>
                <div class="jssor_thumb_frame"></div>
            </div>
        </div>
    </div>';

?>

<script type="text/javascript">
    wplj(document).ready(function ($) {
        <?php if(count($wpl_properties) > 1): ?>
        var options = {
            $FillMode: <?php echo $slide_fillmode; ?>,
            $AutoPlay: <?php echo ($slide_interval > 0 ? "true" : "false"); ?>,
            $AutoPlayInterval: <?php echo $slide_interval; ?>,
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$,
                $ChanceToShow: 1
            },

            $ThumbnailNavigatorOptions: {
                $Class: $JssorThumbnailNavigator$,
                $ChanceToShow: 2,
                $Loop: 1,
                $ActionMode: 1,
                $SpacingX: 1,
                $DisplayPieces: 8,
                $ParkingPosition: 360
            },

            $CaptionSliderOptions: {
                $Class: $JssorCaptionSlider$,
                $CaptionTransitions: _CaptionTransitions,
                $PlayInMode: 3,
                $PlayOutMode: 3
            }

        };

        var jssor_slider = new $JssorSlider$("jssor_slider<?php echo $this->widget_id; ?>", options);
        function ScaleSlider() {
            var bodyWidth = $("jssor_slider<?php echo $this->widget_id; ?>").parent().width();
            if (bodyWidth)
                jssor_slider.$ScaleWidth(Math.min(bodyWidth, 1920));
            else
                window.setTimeout(ScaleSlider, 30);
        }
        ScaleSlider();
        $(window).bind("load", ScaleSlider);
        $(window).bind("resize", ScaleSlider);
        $(window).bind("orientationchange", ScaleSlider);
        <?php endif; ?>
    });

</script>
<div class="wpl_carousel_container">
    <div id="jssor_slider<?php echo $this->widget_id; ?>" class="jssor_slider_container" style="height: <?php echo $image_height; ?>px">
        <div u="loading" class="jssor_loading"><div class="wpl_loading spinner"></div></div>
        <div u="slides" class="jssor_slides" style="height: <?php echo $image_height; ?>px">
            <?php echo $larg_images; ?>
        </div>

        <?php echo $thumbnail; ?>

        <span u="arrowleft" class="jssor_nav_prev "></span>
        <span u="arrowright" class="jssor_nav_next "></span>

    </div>
</div>
