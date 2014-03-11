<?php
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.js", true, true);

/** add Layout js **/
$js[] = (object) array('param1'=>'elastic.slideshow', 'param2'=>'js/elastic_slideshow/jquery.eislideshow.js');
foreach ($js as $javascript)
    wpl_extensions::import_javascript($javascript);

$larg_images = $thumbnail = NULL;
foreach ($wpl_properties as $key => $gallery) {

	if($gallery["items"]["gallery"][0]){
		
		$params = array();
        $params['image_name'] 		= $gallery["items"]["gallery"][0]->item_name;
        $params['image_parentid'] 	= $gallery["items"]["gallery"][0]->parent_id;
        $params['image_parentkind'] = $gallery["items"]["gallery"][0]->parent_kind;
        $params['image_source'] 	= wpl_global::get_upload_base_path().$params['image_parentid'].DS.$params['image_name'];

        if($gallery['raw']['property_title']) $image_title = $gallery['raw']['property_title'];
        else $image_title = $gallery['rendered'][3]['value'] .' '.$gallery['rendered'][2]['value'];
		
		$image_description	= $gallery["items"]["gallery"][0]->item_extra2;
        $image_url 			= wpl_images::create_gallary_image(1920, 558, $params, $watermark, $rewrite);
        $thumbnail_url 		= wpl_images::create_gallary_image(150, 60, $params, $watermark, $rewrite);

		$larg_images .= '
		<li>
            <img src="'.$image_url.'" alt="'.$image_title.'" />
            <div class="ei-title">
                <h2>'.$gallery["rendered"][3]["value"].' '.$gallery["rendered"][2]["value"].'</h2>
                <h3>'.$gallery["rendered"][10]["value"].' - '.$gallery["location_text"].'</h3>
                <a class="more_info" href="'.$gallery["property_link"].'">'. __('More info', WPL_TEXTDOMAIN).'</a>
            </div>
        </li>';

        $thumbnail	.='<li><a href="#">'.$image_title.'</a><img src="'.$thumbnail_url.'" alt="'.$image_title.'" /></li>';
	}
}
?>
<script type="text/javascript">
    wplj(function() {
        wplj('#ei-slider').eislideshow({
			animation			: 'center',
			autoplay			: true,
			slideshow_interval	: 3000,
			titlesFactor		: 0
        });
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
