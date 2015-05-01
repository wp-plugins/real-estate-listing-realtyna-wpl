<?php
/** -- CUSTOM CODES SHOULD BE REMOVED IN NEXT UPDATE PACKAGE -- **/
_wpl_import('libraries.property');
_wpl_import('libraries.users');
_wpl_import('libraries.flex');

/** Update Carousel Widgets **/
$widgets = get_option('widget_wpl_carousel_widget');
if(count($widgets))
{
    foreach($widgets as $key=>$widget)
    {
        if($widgets[$key]['layout'] == 'elastic') $widgets[$key]['layout'] = 'modern';
        elseif($widgets[$key]['layout'] == 'owl_slider') $widgets[$key]['layout'] = 'multi_images';
        
        if(isset($widgets[$key]['data']['property_ids']) and trim($widgets[$key]['data']['property_ids']))
        {
            $pids = explode(',', $widgets[$key]['data']['property_ids']);
            if(count($pids))
            {
                $listing_ids = '';
                foreach($pids as $pid) $listing_ids .= wpl_property::listing_id($pid, 'id').',';
                
                $widgets[$key]['data']['listing_ids'] = trim($listing_ids, ', ');
                unset($widgets[$key]['data']['property_ids']);
            }
        }
    }
    
    update_option('widget_wpl_carousel_widget', $widgets);
}