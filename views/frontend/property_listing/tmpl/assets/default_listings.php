<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$description_column = 'field_308';
if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, $this->kind)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

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
    $parking_number = explode(',',$property['raw']['f_150_options']);
    $parking_number = $parking_number[1];
    $parking    = (isset($property['raw']['f_150']) and trim($property['raw']['f_150_options'])) ? '<div class="parking">'.$parking_number.'</div>' : '';
    $pic_count  = '<div class="pic_count">'.$property['raw']['pic_numb'].'</div>';
    
    $description = stripslashes(strip_tags($property['raw'][$description_column]));
    $cut_position = strrpos(substr($description, 0, 400), '.', -1);
    if(!$cut_position) $cut_position = 399;
    ?>
    <div class="wpl_prp_cont <?php echo ((isset($this->property_css_class) and in_array($this->property_css_class, array('row_box', 'grid_box'))) ? $this->property_css_class : ''); ?>" id="wpl_prp_cont<?php echo $property['data']['id']; ?>" itemscope itemtype="https://schema.org/TradeAction">
        <div class="wpl_prp_top">
            <div class="wpl_prp_top_boxes front">
                <?php wpl_activity::load_position('wpl_property_listing_image', array('wpl_properties'=>$this->wpl_properties)); ?>
            </div>
            <div class="wpl_prp_top_boxes back">
                <a itemprop="url" id="prp_link_id_<?php echo $property['data']['id']; ?>" href="<?php echo $property['property_link']; ?>" class="view_detail"><?php echo __('More Details', WPL_TEXTDOMAIN); ?></a>
            </div>
        </div>
        <div class="wpl_prp_bot">
            <?php
            echo '<a id="prp_link_id_'.$property['data']['id'].'_view_detail" href="'.$property['property_link'].'" class="view_detail" title="'.$property['property_title'].'">
              <h3 class="wpl_prp_title" itemprop="name">'.$property['property_title'].'</h3></a>';
            echo '<h4 class="wpl_prp_listing_location" itemprop="location">'.$property['location_text'].'</h4>';
            ?>
            <div class="wpl_prp_listing_icon_box"><?php echo $room . $bathroom . $parking . $pic_count; ?></div>
            <div class="wpl_prp_desc" itemprop="description"><?php echo substr($description, 0, $cut_position + 1); ?></div>
        </div>
        <?php if(isset($property['materials']['price'])): ?><div class="price_box"><span itemprop="price" content="<?php echo $property['materials']['price']['value']; ?>"><?php echo $property['materials']['price']['value']; ?></span></div><?php endif; ?>
    </div>
    <?php
}