<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.carousel.scripts.js", true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 90;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 82;

?>
<div class="wpl_carousel_container">
	<ul class="simple_list">
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

				echo '
				<li>
					<div class="left_section">
						<a href="'.$gallery["property_link"].'"><span style="width:'.$image_width.'px;height:'.$image_height.'px;"><img src="'.$image_url.'" title="'.$image_title.'" alt="'.$image_title.'" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" /></span></a>
					</div>
					<div class="right_section">
						<div class="title">'.$image_title.'</div>
						<div class="location">'.$gallery["raw"]["location_text"].'</div>
						<div class="price">'.$gallery["rendered"][6]["value"].' '.$gallery["rendered"][14]["value"].'</div>
					</div>
					<a class="more_info" href="'.$gallery["property_link"].'">'.__('More Info', WPL_TEXTDOMAIN).'</a>
				</li>';
			}
		}
		?>

	</ul>
</div>
