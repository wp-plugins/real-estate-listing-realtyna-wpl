<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
?>
<div class="wpl_property_listing_container" id="wpl_property_listing_container">
	<?php /** load position1 **/ wpl_activity::load_position('plisting_position1', array('wpl_properties'=>$this->wpl_properties)); ?>
    
    <?php if(is_active_sidebar('wpl-plisting-top') and $this->kind == 0): ?>
    <div class="wpl_plisting_top_sidebar_container">
        <?php dynamic_sidebar('wpl-plisting-top'); ?>
    </div>
    <?php elseif(is_active_sidebar('wpl-complex-plisting-top') and $this->kind == 1): ?>
    <div class="wpl_plisting_top_sidebar_container">
        <?php dynamic_sidebar('wpl-complex-plisting-top'); ?>
    </div>
    <?php endif; ?>
    
    <div class="wpl_sort_options_container">
        <div class="wpl_sort_options_container_title"><?php echo __("Sort Option:", WPL_TEXTDOMAIN) ?></div>
        <?php echo $this->model->generate_sorts(); ?>
    </div>
    <?php
    foreach($this->wpl_properties as $key=>$property)
    {
        if($key == 'current') continue;
        
        /** unset previous property **/
        unset($this->wpl_properties['current']);
        
        /** set current property **/
        $this->wpl_properties['current'] = $property;

        $room    = isset($property['materials']['bedrooms']) ? '<div class="bedroom">'.$property['materials']['bedrooms']['value'].'</div>' : '';
        if((!isset($property['materials']['bedrooms']) or (isset($property['materials']['bedrooms']) and $property['materials']['bedrooms']['value'] == 0)) and (isset($property['materials']['rooms']) and $property['materials']['rooms']['value'] != 0)) $room = '<div class="room">'.$property['materials']['rooms']['value'].'</div>';
        
        $bathroom   = isset($property['materials']['bathrooms']) ? '<div class="bathroom">'.$property['materials']['bathrooms']['value'].'</div>' : '';
        $parking    = '<div class="parking">'.($property['raw']['f_150'] == 1 ? $property['raw']['f_150_options'] : 0).'</div>';
        $pic_count  = '<div class="pic_count">'.$property['raw']['pic_numb'].'</div>';
		?>
		<div class="wpl_prp_cont" id="wpl_prp_cont<?php echo $property['data']['id']; ?>">
            <div class="wpl_prp_top">
                <div class="wpl_prp_top_boxes front">
                    <?php wpl_activity::load_position('wpl_property_listing_image', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <div class="wpl_prp_top_boxes back">
                    <a id="prp_link_id_<?php echo $property['data']['id']; ?>" href="<?php echo $property['property_link']; ?>" class="view_detail"><?php echo __('More Details', WPL_TEXTDOMAIN); ?></a>
                </div>
            </div>
            <div class="wpl_prp_bot">
                <?php
                echo '<h3 class="wpl_prp_title">'.((isset($property['materials']['field_313']) and trim($property['materials']['field_313']['value']) != '') ? $property['materials']['field_313']['value'] : $property['materials']['property_type']['value'].' - '.$property['materials']['listing']['value']).'</h3>';
                echo '<h4 class="wpl_prp_listing_location">'.$property['location_text'] .'</h4>';
                ?>
                <div class="wpl_prp_listing_icon_box"><?php echo $room . $bathroom . $parking . $pic_count; ?></div>
            </div>
            <div class="price_box"><span itemprop="price" content="<?php echo $property['materials']['price']['value']; ?>"><?php echo $property['materials']['price']['value']; ?></span></div>
		</div>
		<?php
    }
    ?>
    <div class="wpl_pagination_container">
        <?php echo $this->pagination->show(); ?>
    </div>
</div>