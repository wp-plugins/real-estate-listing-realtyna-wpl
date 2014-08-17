<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$this->property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

/** get image params **/
$this->image_width = isset($params['image_width']) ? $params['image_width'] : 285;
$this->image_height = isset($params['image_height']) ? $params['image_height'] : 200;
$this->image_class = isset($params['image_class']) ? $params['image_class'] : '';
$this->resize = (isset($params['resize']) and trim($params['resize']) != '') ? $params['resize'] : 1;
$this->rewrite = (isset($params['rewrite']) and trim($params['rewrite']) != '') ? $params['rewrite'] : 0;
$this->watermark = (isset($params['watermark']) and trim($params['watermark']) != '') ? $params['watermark'] : 0;

/** Property tags **/
$features = '';
$hot_offer = '';
$open_house = '';
$forclosure = '';

if(isset($wpl_properties['current']['materials']['sp_featured']) and $wpl_properties['current']['materials']['sp_featured']) $features = '<div class="feature">'.$wpl_properties['current']['materials']['sp_featured']['name'].'</div>';
if(isset($wpl_properties['current']['materials']['sp_hot']) and $wpl_properties['current']['materials']['sp_hot']) $hot_offer = '<div class="hot_offer">'.$wpl_properties['current']['materials']['sp_hot']['name'].'</div>';
if(isset($wpl_properties['current']['materials']['sp_openhouse']) and $wpl_properties['current']['materials']['sp_openhouse']) $open_house = '<div class="open_house">'.$wpl_properties['current']['materials']['sp_openhouse']['name'].'</div>';
if(isset($wpl_properties['current']['materials']['sp_forclosure']) and $wpl_properties['current']['materials']['sp_forclosure']) $forclosure = '<div class="forclosure">'.$wpl_properties['current']['materials']['sp_forclosure']['name'].'</div>';

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();
$gallery = wpl_items::render_gallery($raw_gallery);

/** import js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.default', true, true, true);
?>
<div class="wpl_gallery_container" id="wpl_gallery_container<?php echo $this->property_id; ?>" >
    <?php 
    if(!count($gallery)) 
    {
        echo '<div class="no_image_box"></div>';
    }
    else 
    {
        $i = 0;
        $images_total = count($gallery);
        foreach($gallery as $image)
        {
            $image_url = $image['url'];
            
            if($this->resize and $this->image_width and $this->image_height and $image['category'] != 'external')
            {
                /** set resize method parameters **/
                $params = array();
                $params['image_name'] = $image['raw']['item_name'];
                $params['image_parentid'] = $image['raw']['parent_id'];
                $params['image_parentkind'] = $image['raw']['parent_kind'];
                $params['image_source'] = $image['path'];
                
                /** resize image if does not exist **/
                $image_url = wpl_images::create_gallary_image($this->image_width, $this->image_height, $params, $this->watermark, $this->rewrite);
            }
            
            echo '<img id="wpl_gallery_image'.$this->property_id .'_'.$i.'" src="'.$image_url.'" class="wpl_gallery_image '.$this->image_class.'" onclick="wpl_plisting_slider('.$i.', '.$this->property_id.', '.$images_total.');" alt="'.$image['raw']['item_name'].'" width="'.$this->image_width.'" height="'.$this->image_height.'" style="width: '.$this->image_width.'px; height: '.$this->image_height.'px;" />';
            $i++;
        }
    } 

    /* Property tags */
    echo $features.$hot_offer.$open_house.$forclosure;
    ?>
</div>