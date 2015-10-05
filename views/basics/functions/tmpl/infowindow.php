<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.images');

$image_width = isset($image_width) ? $image_width : 180;
$image_height = isset($image_height) ? $image_height : 125;

foreach($this->wpl_properties as $key=>$property)
{
	$property_id = $property['data']['id'];
    $kind = $property['data']['kind'];
    $locations	 = $property['location_text'];
    
    // Get blog ID of property
    $blog_id = wpl_property::get_blog_id($property_id);

	$room       = isset($property['materials']['bedrooms']) ? '<div class="bedroom">'.$property['materials']['bedrooms']['value'].'</div>' : '';
    if((!isset($property['materials']['bedrooms']) or (isset($property['materials']['bedrooms']) and $property['materials']['bedrooms']['value'] == 0)) and (isset($property['materials']['rooms']) and $property['materials']['rooms']['value'] != 0)) $room = '<div class="room">'.$property['materials']['rooms']['value'].'</div>';
    
    $bathroom   = isset($property['materials']['bathrooms']) ? '<div class="bathroom">'.$property['materials']['bathrooms']['value'].'</div>' : '';
    $parking    = (isset($property['raw']['f_150']) and trim($property['raw']['f_150_options'])) ? '<div class="parking">'.$property['raw']['f_150_options'].'</div>' : '';
    $pic_count  = '<div class="pic_count">'.$property['raw']['pic_numb'].'</div>';
    $price 		= '<div class="price">'.$property['materials']['price']['value'].'</div>';
?>
	<div id="main_infowindow">
		<div class="main_infowindow_l">
			<?php
			if(isset($property['items']['gallery']))
			{
				$i = 0;
                $images_total = count($property['items']['gallery']);
                $property_path = wpl_items::get_path($property_id, $kind, $blog_id);
                
				foreach($property['items']['gallery'] as $key1 => $image)
				{
					/** set resize method parameters **/
	                $params = array();
	                $params['image_name'] = $image->item_name;
	                $params['image_parentid'] = $image->parent_id;
	                $params['image_parentkind'] = $image->parent_kind;
	                $params['image_source'] = $property_path.$image->item_name;
	                
                    /** resize image if does not exist **/
                    if(isset($image->item_cat) and $image->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params);
                    else $image_url = $image->item_extra3;

					echo '<img itemprop="image" id="wpl_gallery_image'.$property_id .'_'.$i.'" src="'.$image_url.'" class="wpl_gallery_image" onclick="wpl_Plisting_slider('.$i.','.$images_total.','.$property_id.');" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" />';
					$i++;	
				}
			}
			else
			{
				echo '<div class="no_image_box"></div>';
			}
			?>
		</div>
		<div class="main_infowindow_r">
			<div class="main_infowindow_r_t">
				<?php echo '<a itemprop="url" class="main_infowindow_title" href="'.$property['property_link'].'">'.$property['property_title'].'</a>'; ?>
				<div class="main_infowindow_location" itemprop="address" ><?php echo $locations; ?></div>
			</div>
			<div class="main_infowindow_r_b">
				<?php echo $room.$bathroom.$parking.$pic_count.$price; ?>
			</div>
		</div>
	</div>
<?php } ?>