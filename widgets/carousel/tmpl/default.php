<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.js", true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 1920;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 558;

/** add Layout js **/
$js[] = (object) array('param1'=>'bx.slider', 'param2'=>'js/jquery.bxslider.min.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);
?>
<script type="text/javascript">
wplj(document).ready(function()
{
    <?php if(count($wpl_properties) > 1): ?>
	wplj('.bxslider').bxSlider(
	{
		mode: 'fade',
		pager: false,
		auto: true,
		captions: true,
		nextText: '',
		prevText: ''
	});
    <?php endif; ?>
});
</script>
<div class="wpl_carousel_container">
	<ul class="bxslider">
		<?php 
		foreach($wpl_properties as $key=>$gallery)
		{
			if(isset($gallery["items"]["gallery"][0]))
			{
				$params = array();
		        $params['image_name'] 		= $gallery["items"]["gallery"][0]->item_name;
		        $params['image_parentid'] 	= $gallery["items"]["gallery"][0]->parent_id;
		        $params['image_parentkind'] = $gallery["items"]["gallery"][0]->parent_kind;
		        $params['image_source'] 	= wpl_global::get_upload_base_path().$params['image_parentid'].DS.$params['image_name'];

		        if(isset($gallery['rendered']['313']) and trim($gallery['rendered']['313']['value']) != '') $image_title = $gallery['rendered']['313']['value'];
		        else $image_title = $gallery['rendered'][3]['value'] .' '.$gallery['rendered'][2]['value'];
				
				$image_description = $gallery["items"]["gallery"][0]->item_extra2;
                
		        if($gallery["items"]["gallery"][0]->item_cat != 'external') $image_url = wpl_images::create_gallary_image($image_width, $image_height, $params);
                else $image_url = $gallery["items"]["gallery"][0]->item_extra3;

				echo '<li><a href="'.$gallery["property_link"].'"><img src="'.$image_url.'" title="'.$image_title.'" alt="'.$image_title.'" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" /></a></li>';
			}
		}
		?>
	</ul>
</div>
