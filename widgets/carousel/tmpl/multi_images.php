<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import('widgets.carousel.scripts.js', true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 310;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 220;
$images_per_page = isset($this->instance['data']['images_per_page']) ? $this->instance['data']['images_per_page'] : 3;

$slide_interval = isset($this->instance['data']['slide_interval']) ? $this->instance['data']['slide_interval'] : 3000;

/** add Layout js **/
$js[] = (object) array('param1'=>'owl.slider', 'param2'=>'packages/owl_slider/owl.carousel.min.js');
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

    $image_title = wpl_property::update_property_title($gallery['raw']);

    if(isset($gallery['items']['gallery'][0]->item_extra2) and trim($gallery['items']['gallery'][0]->item_extra2) != '') $image_alt = $gallery['items']['gallery'][0]->item_extra2;
    else $image_alt = $gallery['raw']['meta_keywords'];

    $image_description	= $gallery["items"]["gallery"][0]->item_extra2;

    if($gallery["items"]["gallery"][0]->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params);
    else $image_url = $gallery["items"]["gallery"][0]->item_extra3;

    $images .= '
    <div class="wpl-carousel-item">
        <img itemprop="image" src="'.$image_url.'" alt="'.$image_alt.'" height="'.$image_height.'" style="height: '.$image_height.'px;" />
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
    wplj("#wpl-multi-images-<?php echo $this->widget_id; ?>").owlCarousel(
    {
        items: <?php echo $images_per_page; ?>,
        loop: true,
        nav: true,
        autoplay: true,
        autoplayTimeout: <?php echo $slide_interval; ?>,
        autoplayHoverPause: true,
        navText: false,
        dots: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: false,
                dots: true
            },
            768: {
                items: 2
            },
            1024:{
                items: <?php echo $images_per_page; ?>
            }
        }
    });
});
</script>
<div id="wpl-multi-images-<?php echo $this->widget_id; ?>" class="wpl-plugin-owl wpl-carousel-multi-images container <?php echo $this->css_class; ?>">
    <?php echo $images; ?>
</div>