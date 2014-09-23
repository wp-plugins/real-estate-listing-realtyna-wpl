<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$this->property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

/** get image params **/
$this->image_width = isset($params['image_width']) ? $params['image_width'] : 360;
$this->image_height = isset($params['image_height']) ? $params['image_height'] : 285;
$this->image_class = isset($params['image_class']) ? $params['image_class'] : '';
$this->resize = (isset($params['resize']) and trim($params['resize']) != '') ? $params['resize'] : 1;
$this->rewrite = (isset($params['rewrite']) and trim($params['rewrite']) != '') ? $params['rewrite'] : 0;
$this->watermark = (isset($params['watermark']) and trim($params['watermark']) != '') ? $params['watermark'] : 1;

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();
$this->gallery = wpl_items::render_gallery($raw_gallery);

$js[] = (object) array('param1'=>'jquery.bxslider', 'param2'=>'js/jquery.bxslider.min.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript, true);

/** import js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.pshow', true, true);
?>
<div class="wpl_gallery_container" id="wpl_gallery_container<?php echo $this->property_id; ?>">
    <?php
    if(!count($this->gallery))
    {
        echo '<div class="gallery_no_image"></div>';
    }
    else
    {

        echo '<ul class="bxslider" id="bxslider_'.$this->property_id.'">';
        $i = 0;
        $pager_box = '';

        foreach($this->gallery as $image)
        {

            $image_url = $image['url'];
            $image_thumbnail_url = $image['url'];
            if(isset($image['item_extra2'])) $image_alt = $image['item_extra2'];
            else $image_alt = $wpl_properties['current']['raw']['meta_keywords'];

            if($this->resize and $this->image_width and $this->image_height and $image['category'] != 'external')
            {
                /** set resize method parameters **/
                $params                     = array();
                $params['image_name']       = $image['raw']['item_name'];
                $params['image_parentid']   = $image['raw']['parent_id'];
                $params['image_parentkind'] = $image['raw']['parent_kind'];
                $params['image_source']     = $image['path'];
                
                /** resize image if does not exist and add watermark **/
                $image_url           = wpl_images::create_gallary_image($this->image_width, $this->image_height, $params, $this->watermark, $this->rewrite);
                $image_thumbnail_url = wpl_images::create_gallary_image(100, 80, $params, $this->watermark, $this->rewrite);
            }

            /** start loading images **/
            echo '<li><img src="'.$image_url.'" itemprop="image" class="wpl_gallery_image '.$this->image_class.'" id="wpl_gallery_image'.$image['raw']['id'].'" width="'.$this->image_width.'" height="'.$this->image_height.'" alt="'.$image_alt.'" /></li>';
            $pager_box .= '<a data-slide-index="'.$i.'" href=""><img src="'.$image_thumbnail_url.'" width="100" height="80" style="width: 100px; height: 80px;" itemprop="image" alt="'.$image_alt.'" /></a>';
        	$i++;
        }
		
        echo '</ul>';
    ?>
    <div id="bx-pager">
        <?php echo '<div id="img_count">'.count($this->gallery).'</div>'.$pager_box; ?>
    </div>
    <?php } ?>
    
</div>
