<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.js", true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 1920;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 558;
?>
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

		        if($gallery['raw']['property_title']) $image_title = $gallery['raw']['property_title'];
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
