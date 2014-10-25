<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.js", true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 310;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 220;

/** add Layout js **/
$js[] = (object) array('param1'=>'owl.slider', 'param2'=>'js/owl_slider/owl.carousel.min.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

$images = NULL;
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
    }
    else
    {
        $image_url 			= $gallery["items"]["gallery"][0]->item_extra3;
    }

    $images .= '
    <div class="item">
        <img itemprop="image" src="'.$image_url.'" alt="'.$image_alt.'" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" />
        <div class="title">
            <h3 itemprop="name">'.$image_title.'</h3>
            <a itemprop="url" class="more_info" href="'.$gallery["property_link"].'">'. __('More', WPL_TEXTDOMAIN).'</a>
        </div>
    </div>';
}
?>
<script type="text/javascript">
wplj(function()
{
    wplj("#owl-slider<?php echo $this->widget_id; ?>").owlCarousel({
        items : 3,
        lazyLoad : true,
        navigation : true,
        slideSpeed : 500,
        navigationText : false,
        pagination : false,
        itemsTablet: [768,2],
        itemsTabletSmall: false,
        itemsMobile : [480,1]
    });
});
</script>
<div id="owl-slider<?php echo $this->widget_id; ?>" class="owl-carousel owl-theme container">
    <?php echo $images ?>
</div>
