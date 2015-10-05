<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$this->property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;
$this->current_property = $wpl_properties['current'];

/** get image params **/
$this->image_width = isset($params['image_width']) ? $params['image_width'] : 285;
$this->image_height = isset($params['image_height']) ? $params['image_height'] : 200;
$this->image_class = isset($params['image_class']) ? $params['image_class'] : '';
$this->resize = (isset($params['resize']) and trim($params['resize']) != '') ? $params['resize'] : 1;
$this->rewrite = (isset($params['rewrite']) and trim($params['rewrite']) != '') ? $params['rewrite'] : 0;
$this->watermark = (isset($params['watermark']) and trim($params['watermark']) != '') ? $params['watermark'] : 0;
$this->img_category = (isset($image['category']) and trim($image['category']) != '') ? $image['category'] : '';

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();
$gallery = wpl_items::render_gallery($raw_gallery, wpl_property::get_blog_id($this->property_id));
?>
<div class="wpl_gallery_container" id="wpl_gallery_container<?php echo $this->property_id; ?>">
    <?php
    if(!count($gallery))
    {
        echo '<div class="no_image_box"></div>';
    }
    else
    {
        $image_url = $gallery[0]['url'];
        $image_alt = '';
        
        if(isset($gallery[0]['item_extra2'])) $image_alt = $gallery[0]['item_extra2'];
        elseif(isset($wpl_properties['current']['raw']['meta_keywords'])) $image_alt = $wpl_properties['current']['raw']['meta_keywords'];

        if($this->resize and $this->image_width and $this->image_height and $this->img_category != 'external')
        {
            /** set resize method parameters **/
            $params = array();
            $params['image_name'] = $gallery[0]['raw']['item_name'];
            $params['image_parentid'] = $gallery[0]['raw']['parent_id'];
            $params['image_parentkind'] = $gallery[0]['raw']['parent_kind'];
            $params['image_source'] = $gallery[0]['path'];
            
            /** resize image if does not exist **/
            $image_url = wpl_images::create_gallery_image($this->image_width, $this->image_height, $params, $this->watermark, $this->rewrite);
        }
        
        echo '<img itemprop="image" id="wpl_gallery_image'.$this->property_id .'" src="'.$image_url.'" class="wpl_gallery_image '.$this->image_class.'" alt="'.$image_alt.'" width="'.$this->image_width.'" height="'.$this->image_height.'" style="width: '.$this->image_width.'px; height: '.$this->image_height.'px;" />';
    }
    ?>

    <div class="wpl-listing-tags-wp">
        <div class="wpl-listing-tags-cnt">
            <?php /* Property tags */ echo $this->tags(); ?>
        </div>
    </div>
</div>