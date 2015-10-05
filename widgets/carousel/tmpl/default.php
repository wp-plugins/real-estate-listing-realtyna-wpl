<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import('widgets.carousel.scripts.js', true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 310;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 220;
$slide_interval = isset($this->instance['data']['slide_interval']) ? $this->instance['data']['slide_interval'] : 3000;
$auto_play = isset($this->instance['data']['auto_play']) ? $this->instance['data']['auto_play'] : true;
$show_nav = isset($this->instance['data']['show_nav']) ? $this->instance['data']['show_nav'] : true;

$js[] = (object)array('param1' => 'owl.slider', 'param2' => 'packages/owl_slider/owl.carousel.min.js');
foreach ($js as $javascript) wpl_extensions::import_javascript($javascript);

$images = NULL;
foreach($wpl_properties as $key => $gallery)
{
    if(!isset($gallery["items"]["gallery"][0])) continue;

    $params = array();
    $params['image_name'] = $gallery["items"]["gallery"][0]->item_name;
    $params['image_parentid'] = $gallery["items"]["gallery"][0]->parent_id;
    $params['image_parentkind'] = $gallery["items"]["gallery"][0]->parent_kind;
    $params['image_source'] = wpl_global::get_upload_base_path() . $params['image_parentid'] . DS . $params['image_name'];

    $image_title = wpl_property::update_property_title($gallery['raw']);

    if(isset($gallery['items']['gallery'][0]->item_extra2) and trim($gallery['items']['gallery'][0]->item_extra2) != '') $image_alt = $gallery['items']['gallery'][0]->item_extra2;
    else $image_alt = $gallery['raw']['meta_keywords'];

    $image_description = $gallery["items"]["gallery"][0]->item_extra2;

    if($gallery["items"]["gallery"][0]->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params);
    else $image_url = $gallery["items"]["gallery"][0]->item_extra3;

    $images .= '<div><a href="' . $gallery["property_link"] . '"><img itemprop="image" src="' . $image_url . '" alt="' . $image_alt . '"/></a></div>';
}
?>

<div id="owl-slider<?php echo $this->widget_id; ?>" class="wpl-plugin-owl owl-carousel wpl-owl-theme-1 wpl-carousel-default <?php echo $this->css_class; ?>">
    <?php echo $images; ?>
</div>
<script type="text/javascript">
wplj(function()
{
    wplj("#owl-slider<?php echo $this->widget_id; ?>").owlCarousel({
        items: 1,
        nav: <?php echo $show_nav? 'true' : 'false'; ?>,
        dots: false,
        navText: false,
        loop: true,
        autoplay: <?php echo $auto_play? 'true' : 'false'; ?>,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 1
            },
            768: {
                items: 1
            }
        }
    });
});
</script>