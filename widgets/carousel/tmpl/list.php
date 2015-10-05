<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import('widgets.carousel.scripts.js', true, true);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 90;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 82;
?>
<div class="wpl_carousel_container <?php echo $this->css_class; ?>">
	<ul class="simple_list">
		<?php
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

            $image_description = $gallery["items"]["gallery"][0]->item_extra2;

            if($gallery["items"]["gallery"][0]->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params);
            else $image_url = $gallery["items"]["gallery"][0]->item_extra3;

            echo '
            <li>
                <div class="left_section">
                    <a itemprop="url" href="'.$gallery["property_link"].'"><span style="width:'.$image_width.'px;height:'.$image_height.'px;"><img itemprop="image" src="'.$image_url.'" title="'.$image_title.'" alt="'.$image_alt.'" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" /></span></a>
                </div>
                <div class="right_section">
                    <div class="title" itemprop="name">'.$image_title.'</div>
                    <div class="location" itemprop="address">'.$gallery["raw"]["location_text"].'</div>
                    '.(isset($gallery['materials']['price']) ? '<div class="price" itemprop="price" content="'.$gallery['materials']['price']['value'].'">'.$gallery['materials']['price']['value'].'</div>' : '').'
                </div>
                <a itemprop="url" class="more_info" href="'.$gallery["property_link"].'">'.__('More Info', WPL_TEXTDOMAIN).'</a>
            </li>';
		}
		?>
	</ul>
</div>