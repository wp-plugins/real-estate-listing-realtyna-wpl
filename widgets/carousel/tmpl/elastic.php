<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.js", true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 1920;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 558;

$thumbnail_width = isset($this->instance['data']['thumbnail_width']) ? $this->instance['data']['thumbnail_width'] : 150;
$thumbnail_height = isset($this->instance['data']['thumbnail_height']) ? $this->instance['data']['thumbnail_height'] : 60;

/** add Layout js **/
$js[] = (object) array('param1'=>'elastic.slideshow', 'param2'=>'js/elastic_slideshow/jquery.eislideshow.js');
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
            <img src="'.$image_url.'" alt="'.$image_title.'" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" />
            <div class="ei-title">
                <h2>'.$image_title.'</h2>
                <h3>'.$gallery['materials']['living_area']['value'].' - '.$gallery["location_text"].'</h3>
                <a class="more_info" href="'.$gallery['property_link'].'">'. __('More info', WPL_TEXTDOMAIN).'</a>
            </div>
        </li>';

        $thumbnail	.='<li><a href="#">'.$image_title.'</a><img src="'.$thumbnail_url.'" alt="'.$image_title.'" width="'.$thumbnail_width.'" height="'.$thumbnail_height.'" style="width: '.$thumbnail_width.'px; height: '.$thumbnail_height.'px;" /></li>';
	}
}
?>
<script type="text/javascript">
wplj(function()
{
    <?php if(count($wpl_properties) > 1): ?>
	wplj('#ei-slider').eislideshow(
	{
		animation			: 'center',
		autoplay			: true,
		slideshow_interval	: 3000,
		titlesFactor		: 0
	});
    <?php endif; ?>
});
</script>
<div class="wpl_carousel_container">
	<div id="ei-slider" class="ei-slider">
	    <ul class="ei-slider-large">
	    	<?php echo $larg_images; ?>
	    </ul><!-- ei-slider-large -->
	    <ul class="ei-slider-thumbs">
	        <li class="ei-slider-element">Current</li>
	        <?php echo $thumbnail; ?>
	    </ul><!-- ei-slider-thumbs -->
	</div><!-- ei-slider -->
</div>
