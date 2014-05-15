<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);

$prp_type           = isset($this->wpl_properties['current']['rendered_raw'][3]['value']) ? $this->wpl_properties['current']['rendered_raw'][3]['value'] : '';
$prp_listings       = isset($this->wpl_properties['current']['rendered_raw'][2]['value']) ? $this->wpl_properties['current']['rendered_raw'][2]['value'] : '';
$build_up_area      = isset($this->wpl_properties['current']['rendered_raw'][10]['value']) ? $this->wpl_properties['current']['rendered_raw'][10]['value'] : '';
$bedroom            = isset($this->wpl_properties['current']['rendered_raw'][8]['value']) ? $this->wpl_properties['current']['rendered_raw'][8]['value'] : '';
$bathroom           = isset($this->wpl_properties['current']['rendered_raw'][9]['value']) ? $this->wpl_properties['current']['rendered_raw'][9]['value'] : '';
$listing_id         = isset($this->wpl_properties['current']['rendered_raw'][5]['value']) ? $this->wpl_properties['current']['rendered_raw'][5]['value'] : '';
$price              = isset($this->wpl_properties['current']['rendered_raw'][6]['value']) ? $this->wpl_properties['current']['rendered_raw'][6]['value'] : '';
$price_type         = isset($this->wpl_properties['current']['rendered_raw'][14]['value']) ? $this->wpl_properties['current']['rendered_raw'][14]['value'] : '';
$location_string 	= isset($this->wpl_properties['current']['location_text']) ? $this->wpl_properties['current']['location_text'] : '';

$pshow_gallery_activities = count(wpl_activity::get_activities('pshow_gallery', 1));
$pshow_googlemap_activities = count(wpl_activity::get_activities('pshow_googlemap', 1));

/** video tab for showing videos **/
$pshow_video_activities = count(wpl_activity::get_activities('pshow_video', 1));
if(!isset($this->wpl_properties['current']['items']['video']) or (isset($this->wpl_properties['current']['items']['video']) and !count($this->wpl_properties['current']['items']['video']))) $pshow_video_activities = 0;
?>
<div class="wpl_prp_show_container" id="wpl_prp_show_container">
    <div class="wpl_prp_container" id="wpl_prp_container<?php echo $this->pid; ?>">
        <div class="wpl_prp_show_tabs">
            <div class="tabs_container">
            	<?php if($pshow_gallery_activities): ?>
                <div id="tabs-1" class="tabs_contents">
                    <?php /** load position gallery **/ wpl_activity::load_position('pshow_gallery', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
                <?php endif; ?>
                <?php if($pshow_googlemap_activities): ?>
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
                    <?php if($pshow_googlemap_activities): ?>
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
                echo '<div class="title_text">'.$prp_type .' '.$prp_listings.'</div>';
                echo '<div class="location_build_up">'.$build_up_area.' - '. $location_string .'</div>';
				
                /** load QR Code **/ wpl_activity::load_position('pshow_qr_code', array('wpl_properties'=>$this->wpl_properties)); ?>
            </div>
            <div class="wpl_prp_container_content_left">               
				<?php if($this->wpl_properties['current']['data']['field_308']): ?>
                <div class="wpl_prp_show_detail_boxes">
                    <div class="wpl_prp_show_detail_boxes_title"><?php echo __('Property Description', WPL_TEXTDOMAIN) ?></div>
                    <div class="wpl_prp_show_detail_boxes_cont">
                        <?php echo $this->wpl_properties['current']['data']['field_308']; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php
                $i=0;
                $details_boxes_num = count($this->wpl_properties['current']['rendered']);
                foreach($this->wpl_properties['current']['rendered'] as $values)
				{
					if(!count($values['data'])) continue;
					
                    echo '<div class="wpl_prp_show_detail_boxes">
                            <div class="wpl_prp_show_detail_boxes_title">'.$values['self']['name'].'</div>
                            <div class="wpl_prp_show_detail_boxes_cont">';

                    foreach($values['data'] as $key => $value)
					{
                        if(!isset($value['type']) or $value['type'] == 'separator') continue;
                        
                        elseif($value['type'] == 'neighborhood')
                        {
                            echo '<div id="wpl-dbst-show'.$value['field_id'].'" class="rows neighborhood">' .__($value['name'],WPL_TEXTDOMAIN) .(isset($value['distance']) ? ' <span class="'.$value['by'].'">'. $value['distance'] .' '. __('Minutes',WPL_TEXTDOMAIN). '</span>':''). '</div>';
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
                                foreach ($value['values'] as $val) $html .= __($val, WPL_TEXTDOMAIN).', ';
                                $html = rtrim($html, ', ');
                                echo $html;
                                echo '</span>';
                            }
							
                            echo '</div>';
                        }
                        elseif($value['type'] == 'locations')
                        {
                            foreach ($value['locations'] as $ii=>$lvalue)
                            {
                                echo '<div id="wpl-dbst-show'.$value['field_id'].'" class="rows location">'.__($value['keywords'][$ii], WPL_TEXTDOMAIN).' : ';
                                echo '<span>'.$lvalue.'</span>';
                                echo '</div>';
                            }
                        }
                        else
                            echo '<div id="wpl-dbst-show'.$value['field_id'].'" class="rows other">' .__($value['name'], WPL_TEXTDOMAIN). ' : <span>'. __((isset($value['value']) ? $value['value'] : ''), WPL_TEXTDOMAIN) .'</span></div>';
                    }
					
                    echo '</div></div>';
                	$i++;
                }
                ?>
                
                <div class="wpl_prp_show_position3">
                    <?php /** load position1 **/ wpl_activity::load_position('pshow_position3', array('wpl_properties'=>$this->wpl_properties)); ?>
                </div>
            </div>
            <div class="wpl_prp_container_content_right">
                <div class="wpl_prp_right_boxes details">
                    <div class="wpl_prp_right_boxes_title">
                        <?php echo $prp_type .' <span class="title_color">'.$prp_listings.'</span>'; ?>
                    </div>
                    <div class="wpl_prp_right_boxes_content">
                        <div class="wpl_prp_right_boxe_details_top clearfix">
                            <div class="wpl_prp_right_boxe_details_left">
                                <ul>
                                    <?php if(trim($listing_id) != ''): ?><li><?php echo __('Listing ID', WPL_TEXTDOMAIN).' : <span>'.$listing_id.'</span>'; ?></li><?php endif; ?>
                                    <li><?php echo __('Bedroom', WPL_TEXTDOMAIN).' : <span>'.$bedroom.'</span>'; ?></li>
                                    <li><?php echo __('Bathroom', WPL_TEXTDOMAIN).' : <span>'.$bathroom.'</span>'; ?></li>
                                    <li><?php echo __('Built Up Area', WPL_TEXTDOMAIN).' : <span>'.$build_up_area.'</span>'; ?></li>
                                    <?php if($price_type){ ?>
                                    <li><?php echo __('Price Type', WPL_TEXTDOMAIN).' : <span>'.$price_type.'</span>'; ?></li>
                                    <?php } ?>
                                </ul>
                           </div>
                            <div class="wpl_prp_right_boxe_details_right">
                                <?php /** load wpl_pshow_link activity **/
                                    wpl_activity::load_position('wpl_pshow_link', array('wpl_properties'=>$this->wpl_properties));
                                ?>
                            </div>

                        </div>
                        <div class="wpl_prp_right_boxe_details_bot">
                            <?php echo '<div class="price_box">'.$price.'</div>'; ?>
                        </div>
                    </div>
                </div>
                <div class="wpl_prp_show_position2">
                    <?php
                        $activities = wpl_activity::get_activities('pshow_position2');
                        foreach($activities as $activity)
                        {
                            $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                            if(trim($content) != '')
                            {
                                $activity_title =  explode(':', $activity->activity);
                            ?> 
                                <div class="wpl_prp_right_boxes <?php echo $activity_title[0]; ?>">
                                    <?php if($activity->show_title and trim($activity->title) != '') echo '<div class="wpl_prp_right_boxes_title">'.__($activity->title).'</div>'; ?> 
                                    <div class="wpl_prp_right_boxes_content clearfix">
                                        <?php echo $content; ?>
                                    </div>
                                </div>
                            <?php
                            }
                        }
                    ?>
                </div>
            </div>
            <?php if(is_active_sidebar('wpl-pshow-bottom')) dynamic_sidebar('wpl-pshow-bottom'); ?>
        </div>
    </div>
</div>