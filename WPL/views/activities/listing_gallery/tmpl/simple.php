<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

/** get image params **/
$image_width = isset($params['image_width']) ? $params['image_width'] : 285;
$image_height = isset($params['image_height']) ? $params['image_height'] : 200;
$image_class = isset($params['image_class']) ? $params['image_class'] : '';
$rewrite = (isset($params['rewrite']) and trim($params['rewrite']) != '') ? $params['rewrite'] : 0;
$watermark = (isset($params['watermark']) and trim($params['watermark']) != '') ? $params['watermark'] : 0;

/** Property tags **/
$features = '';
$hot_offer = '';
$open_house = '';
$forclosure = '';

if(isset($wpl_properties['current']['rendered'][400]) and $wpl_properties['current']['rendered'][400]) $features = '<div class="feature">'.$wpl_properties['current']['rendered'][400]['name'].'</div>' ;
if(isset($wpl_properties['current']['rendered'][401]) and $wpl_properties['current']['rendered'][401]) $hot_offer = '<div class="hot_offer">'.$wpl_properties['current']['rendered'][401]['name'].'</div>' ;
if(isset($wpl_properties['current']['rendered'][402]) and $wpl_properties['current']['rendered'][402]) $open_house = '<div class="open_house">'.$wpl_properties['current']['rendered'][402]['name'].'</div>' ;
if(isset($wpl_properties['current']['rendered'][403]) and $wpl_properties['current']['rendered'][403]) $forclosure = '<div class="forclosure">'.$wpl_properties['current']['rendered'][403]['name'].'</div>' ;

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();
$gallery = wpl_items::render_gallery($raw_gallery);
?>
<div class="wpl_gallery_container" id="wpl_gallery_container<?php echo $property_id; ?>" >
    <?php 
    if(!count($gallery)) 
    {
        echo '<div class="no_image_box"></div>';
    }
    else 
    {
        if($image_width and $image_height)
        {
            /** set resize method parameters **/
            $params = array();
            $params['image_name'] = $gallery[0]['raw']['item_name'];
            $params['image_parentid'] = $gallery[0]['raw']['parent_id'];
            $params['image_parentkind'] = $gallery[0]['raw']['parent_kind'];
            $params['image_source'] = $gallery[0]['path'];
            
            /** resize image if does not exist **/
            $image_url = wpl_images::create_gallary_image($image_width, $image_height, $params, $watermark, $rewrite);
        }
        
        echo '<img id="wpl_gallery_image'.$property_id .'_'.$i.'" src="'.$image_url.'" class="wpl_gallery_image '.$image_class.'" alt="'.$params['image_name'].'" />';
    } 
    /* Property tags */
    echo $features.$hot_offer.$open_house.$forclosure;
    ?>
</div>