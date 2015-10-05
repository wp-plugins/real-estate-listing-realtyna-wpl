<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$prp_type           = isset($this->wpl_properties['current']['materials']['property_type']['value']) ? $this->wpl_properties['current']['materials']['property_type']['value'] : '';
$prp_listings       = isset($this->wpl_properties['current']['materials']['listing']['value']) ? $this->wpl_properties['current']['materials']['listing']['value'] : '';
$build_up_area      = isset($this->wpl_properties['current']['materials']['living_area']['value']) ? $this->wpl_properties['current']['materials']['living_area']['value'] : (isset($this->wpl_properties['current']['materials']['lot_area']['value']) ? $this->wpl_properties['current']['materials']['lot_area']['value'] : '');
$build_up_area_name = isset($this->wpl_properties['current']['materials']['living_area']['value']) ? $this->wpl_properties['current']['materials']['living_area']['name'] : (isset($this->wpl_properties['current']['materials']['lot_area']['value']) ? $this->wpl_properties['current']['materials']['lot_area']['name'] : '');
$bedroom            = isset($this->wpl_properties['current']['materials']['bedrooms']['value']) ? $this->wpl_properties['current']['materials']['bedrooms']['value'] : '';
$bathroom           = isset($this->wpl_properties['current']['materials']['bathrooms']['value']) ? $this->wpl_properties['current']['materials']['bathrooms']['value'] : '';
$listing_id         = isset($this->wpl_properties['current']['materials']['mls_id']['value']) ? $this->wpl_properties['current']['materials']['mls_id']['value'] : '';
$price              = isset($this->wpl_properties['current']['materials']['price']['value']) ? $this->wpl_properties['current']['materials']['price']['value'] : '';
$price_type         = isset($this->wpl_properties['current']['materials']['price_period']['value']) ? $this->wpl_properties['current']['materials']['price_period']['value'] : '';
$location_string 	= isset($this->wpl_properties['current']['location_text']) ? $this->wpl_properties['current']['location_text'] : '';
$prp_title          = isset($this->wpl_properties['current']['property_title']) ? $this->wpl_properties['current']['property_title'] : '';

$pshow_gallery_activities = wpl_activity::get_activities('pshow_gallery', 1);
$pshow_googlemap_activities = wpl_activity::get_activities('pshow_googlemap', 1, '', 'loadObject');
$pshow_walkscore_activities = wpl_activity::get_activities('pshow_walkscore', 1);

$this->pshow_googlemap_activity_id = $pshow_googlemap_activities->id;

/** video tab for showing videos **/
$pshow_video_activities = count(wpl_activity::get_activities('pshow_video', 1));
if(!isset($this->wpl_properties['current']['items']['video']) or (isset($this->wpl_properties['current']['items']['video']) and !count($this->wpl_properties['current']['items']['video']))) $pshow_video_activities = 0;

/** Import JS file **/
$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
?>
<div class="wpl_prp_show_container" id="wpl_prp_show_container">
    <div class="wpl_prp_container" id="wpl_prp_container<?php echo $this->pid; ?>" itemscope itemtype="https://schema.org/TradeAction">
        <div class="wpl_prp_show_tabs">
            <div class="tabs_container">
            	<?php if($pshow_gallery_activities): ?>
                <div id="tabs-1" class="tabs_contents">
                    <?php /** load position gallery **/ wpl_activity::load_position('pshow_gallery', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
                <?php if($pshow_googlemap_activities and $this->wpl_properties['current']['raw']['show_address']): ?>
                <div id="tabs-2" class="tabs_contents">
                    <?php /** load position googlemap **/ wpl_activity::load_position('pshow_googlemap', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
                <?php if($pshow_video_activities): ?>
                <div id="tabs-3" class="tabs_contents">
                    <?php /** load position video **/ wpl_activity::load_position('pshow_video', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="tabs_box">
                <ul class="tabs">
                	<?php if($pshow_gallery_activities): ?>
                    <li><a href="#tabs-1"><?php echo __('Pictures', WPL_TEXTDOMAIN) ?></a></li>
                    <?php endif; ?>
                    <?php if($pshow_googlemap_activities and $this->wpl_properties['current']['raw']['show_address']): ?>
                    <li><a href="#tabs-2" data-init-googlemap="1"><?php echo __('Google Map', WPL_TEXTDOMAIN) ?></a></li>
                    <?php endif; ?>
                    <?php if($pshow_video_activities): ?>
                    <li><a href="#tabs-3"><?php echo __('Video', WPL_TEXTDOMAIN) ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="wpl_prp_container_content">
            <div class="wpl_prp_container_content_title">
                <?php
                echo '<h1 class="title_text" itemprop="name">'.$prp_title.'</h1>';
                echo '<h2 class="location_build_up">'.(trim($build_up_area) ? $build_up_area.' - ' : '').'<span itemprop="address">'. $location_string .'</span></h2>';
				
                /** load QR Code **/ wpl_activity::load_position('pshow_qr_code', array('wpl_properties'=>$this->wpl_properties)); ?>
            </div>
            <div class="wpl_prp_container_content_left">
				<?php
                    $description_column = 'field_308';
                    if(wpl_global::check_multilingual_status()) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);
                    
                    if(isset($this->wpl_properties['current']['data'][$description_column]) and $this->wpl_properties['current']['data'][$description_column]):
                ?>
                <div class="wpl_prp_show_detail_boxes">
                    <div class="wpl_prp_show_detail_boxes_title"><?php echo __(wpl_flex::get_dbst_key('name', wpl_flex::get_dbst_id('field_308', $this->kind)), WPL_TEXTDOMAIN) ?></div>
                    <div class="wpl_prp_show_detail_boxes_cont" itemprop="description">
                        <?php echo apply_filters('the_content', stripslashes($this->wpl_properties['current']['data'][$description_column])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php
                $i = 0;
                $details_boxes_num = count($this->wpl_properties['current']['rendered']);
                
                foreach($this->wpl_properties['current']['rendered'] as $values)
				{
                    /** skip empty categories **/
					if(!count($values['data'])) continue;
                    
                    /** skip location if property address is hiden **/
					if($values['self']['prefix'] == 'ad' and !$this->wpl_properties['current']['raw']['show_address']) continue;
                    
                    echo '<div class="wpl_prp_show_detail_boxes">
                            <div class="wpl_prp_show_detail_boxes_title">'.__($values['self']['name'], WPL_TEXTDOMAIN).'</div>
                            <div class="wpl_prp_show_detail_boxes_cont">';

                    foreach($values['data'] as $key => $value)
					{
                        if(!isset($value['type'])) continue;
                        
                        elseif($value['type'] == 'neighborhood')
                        {
                            echo '<div id="wpl-dbst-show'.$value['field_id'].'" class="rows neighborhood">' .__($value['name'],WPL_TEXTDOMAIN) .(isset($value['distance']) ? ' <span class="'.$value['vehicle_type'].'">'. $value['distance'] .' '. __('Minutes',WPL_TEXTDOMAIN). '</span>':''). '</div>';
                        }
                        elseif($value['type'] == 'feature')
                        {
                            echo '<div id="wpl-dbst-show'.$value['field_id'].'" class="rows feature ';
                            if(!isset($value['values'][0])) echo ' single ';
							
                            echo '">'.__($value['name'], WPL_TEXTDOMAIN);
							
                            if(isset($value['values'][0]))
                            {
                                $html = '';
                                echo ' : <span>';
                                foreach($value['values'] as $val) $html .= __($val, WPL_TEXTDOMAIN).', ';
                                $html = rtrim($html, ', ');
                                echo $html;
                                echo '</span>';
                            }
							
                            echo '</div>';
                        }
                        elseif($value['type'] == 'locations' and isset($value['locations']) and is_array($value['locations']))
                        {
                            foreach($value['locations'] as $ii=>$lvalue)
                            {
                                echo '<div id="wpl-dbst-show'.$value['field_id'].'" class="rows location" itemprop="address">'.__($value['keywords'][$ii], WPL_TEXTDOMAIN).' : ';
                                echo '<span>'.$lvalue.'</span>';
                                echo '</div>';
                            }
                        }
                        elseif($value['type'] == 'separator')
                        {
                            echo '<div id="wpl-dbst-show'.$value['field_id'].'" class="rows separator">' .__($value['name'], WPL_TEXTDOMAIN). '</div>';
                        }
                        else
                            echo '<div id="wpl-dbst-show'.$value['field_id'].'" class="rows other">' .__($value['name'], WPL_TEXTDOMAIN). ' : <span>'. __((isset($value['value']) ? $value['value'] : ''), WPL_TEXTDOMAIN) .'</span></div>';
                    }
					
                    echo '</div></div>';
                	$i++;
                }
                ?>
                
                <div class="wpl_prp_show_position3">
                    <?php
                        $activities = wpl_activity::get_activities('pshow_position3');
                        foreach($activities as $activity)
                        {
                            $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                            if(trim($content) == '') continue;
                            
                            $activity_title =  explode(':', $activity->activity);
                            ?>
                            <div class="wpl_prp_position3_boxes <?php echo $activity_title[0]; ?>">
                                <?php
                                if($activity->show_title and trim($activity->title) != '')
                                {
                                    $activity_box_title = NULL;
                                    $title_parts = explode(' ', __($activity->title, WPL_TEXTDOMAIN));
                                    $i_part = 0;

                                    foreach($title_parts as $title_part)
                                    {
                                        if($i_part == 0) $activity_box_title .= '<span>'.$title_part.'</span> ';
                                        else $activity_box_title .= $title_part.' ';

                                        $i_part++;
                                    }

                                    echo '<div class="wpl_prp_position3_boxes_title">'.$activity_box_title.'</div>';
                                }
                                ?>
                                <div class="wpl_prp_position3_boxes_content clearfix">
                                    <?php echo $content; ?>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
            <div class="wpl_prp_container_content_right">
                <div class="wpl_prp_right_boxes details" itemscope>
                    <div class="wpl_prp_right_boxes_title">
                        <?php echo '<span>'.$prp_type .'</span> '.$prp_listings; ?>
                    </div>
                    <div class="wpl_prp_right_boxes_content">
                        <div class="wpl_prp_right_boxe_details_top clearfix">
                            <div class="wpl_prp_right_boxe_details_left">
                                <ul>
                                    <?php if(trim($listing_id) != ''): ?><li><?php echo __($this->wpl_properties['current']['materials']['mls_id']['name'], WPL_TEXTDOMAIN).' : <span itemprop="productID">'.$listing_id.'</span>'; ?></li><?php endif; ?>
                                    <?php if(trim($bedroom) != ''): ?><li><?php echo __($this->wpl_properties['current']['materials']['bedrooms']['name'], WPL_TEXTDOMAIN).' : <span>'.$bedroom.'</span>'; ?></li><?php endif; ?>
                                    <?php if(trim($bathroom) != ''): ?><li><?php echo __($this->wpl_properties['current']['materials']['bathrooms']['name'], WPL_TEXTDOMAIN).' : <span>'.$bathroom.'</span>'; ?></li><?php endif; ?>
                                    <?php if(trim($build_up_area) != ''): ?><li><?php echo __($build_up_area_name, WPL_TEXTDOMAIN).' : <span>'.$build_up_area.'</span>'; ?></li><?php endif; ?>
                                    <?php if($price_type): ?><li><?php echo __($this->wpl_properties['current']['materials']['price_period']['name'], WPL_TEXTDOMAIN).' : <span>'.$price_type.'</span>'; ?></li><?php endif; ?>
                                </ul>
                            </div>
                            <div class="wpl_prp_right_boxe_details_right">
                                <?php /** load wpl_pshow_link activity **/ wpl_activity::load_position('wpl_pshow_link', array('wpl_properties'=>$this->wpl_properties)); ?>
                            </div>
                        </div>
                        <div class="wpl_prp_right_boxe_details_bot">
                            <?php echo '<div class="price_box" itemprop="price">'.$price.'</div>'; ?>
                        </div>
                    </div>
                </div>
                <div class="wpl_prp_show_position2">
                    <?php
                        $activities = wpl_activity::get_activities('pshow_position2');
                        foreach($activities as $activity)
                        {
                            $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                            if(trim($content) == '') continue;
                            
                            $activity_title =  explode(':', $activity->activity);
                            ?>
                            <div class="wpl_prp_right_boxes <?php echo $activity_title[0]; ?>">
                                <?php
                                if($activity->show_title and trim($activity->title) != '')
                                {
                                    $activity_box_title = NULL;
                                    $title_parts = explode(' ', __($activity->title, WPL_TEXTDOMAIN));
                                    $i_part = 0;

                                    foreach($title_parts as $title_part)
                                    {
                                        if($i_part == 0) $activity_box_title .= '<span>'.$title_part.'</span> ';
                                        else $activity_box_title .= $title_part.' ';

                                        $i_part++;
                                    }

                                    echo '<div class="wpl_prp_right_boxes_title">'.$activity_box_title.'</div>';
                                }
                                ?>
                                <div class="wpl_prp_right_boxes_content clearfix">
                                    <?php echo $content; ?>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
            <div class="wpl_prp_show_bottom">
                <?php if($pshow_walkscore_activities): ?>
                <div class="wpl_prp_show_walkscore">
                    <?php /** load position walkscore **/ wpl_activity::load_position('pshow_walkscore', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
                <?php if(is_active_sidebar('wpl-pshow-bottom')) dynamic_sidebar('wpl-pshow-bottom'); ?>
            </div>
        </div>
    </div>
    <?php /** Don't remove this element **/ ?>
    <div id="wpl_pshow_lightbox_content_container" class="wpl-util-hidden"></div>
</div>