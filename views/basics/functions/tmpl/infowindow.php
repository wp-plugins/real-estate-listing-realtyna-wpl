<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.images');

$image_width = isset($image_width) ? $image_width : 180;
$image_height = isset($image_height) ? $image_height : 125;

foreach($this->wpl_properties as $key => $property)
{
	$property_id = $property['data']['id'];
    $locations	 = $property['location_text'];

	$bedroom    = '<div class="bedroom">'.$property['raw']['bedrooms'].'</div>';
    $room    	= '<div class="room">'.$property['raw']['rooms'].'</div>';
    $bathroom   = '<div class="bathroom">'.$property['raw']['bathrooms'].'</div>';
    $parking    = '<div class="parking">'.$property['raw']['parkings'].'</div>';
    $pic_count  = '<div class="pic_count">'.$property['raw']['pic_numb'].'</div>';
    $price 		= '<div class="price">'.$property['rendered'][6]['value'].'</div>';
?>
	<div id="main_infowindow">
		<div class="main_infowindow_l">
			<?php
			if(isset($property['items']['gallery']))
			{
				$i = 0;
                $images_total = count($property['items']['gallery']);
				foreach($property['items']['gallery'] as $key1 => $image)
				{
					/** set resize method parameters **/
	                $params = array();
	                $params['image_name'] = $image->item_name;
	                $params['image_parentid'] = $image->parent_id;
	                $params['image_parentkind'] = $image->parent_kind;
	                $params['image_source'] = wpl_global::get_upload_base_path().$image->parent_id.DS.$image->item_name;
	                
                    /** resize image if does not exist **/
                    if(isset($image->item_cat) and $image->item_cat != 'external') $image_url = wpl_images::create_gallary_image($image_width, $image_height, $params);
                    else $image_url = $image->item_extra3;

					echo '<img id="wpl_gallery_image'.$property_id .'_'.$i.'" src="'.$image_url.'" class="wpl_gallery_image" onclick="wpl_Plisting_slider('.$i.','.$images_total.','.$property_id.');" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" />';
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
				<?php echo '<a class="main_infowindow_title" href="'.$property['property_link'].'">'.$property['rendered'][3]['value'].' '.$property['rendered'][2]['value'].'</a>'; ?>
				<div class="main_infowindow_location"><?php echo $locations; ?></div>
			</div>
			<div class="main_infowindow_r_b">
				<?php echo $bedroom.$bathroom.$parking.$pic_count.$price; ?>
			</div>
		</div>
	</div>
<?php } ?>