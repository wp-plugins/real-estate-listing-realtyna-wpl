<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="side-6 side-statistic1">
    <div class="panel-wp">
        <h3><?php echo __('Properties by listing types', WPL_TEXTDOMAIN); ?></h3>
        <div class="panel-body">
        	<?php
				$properties = wpl_db::select("SELECT COUNT(*) as count, `listing` FROM `#__wpl_properties` WHERE `finalized`='1' AND `confirmed`='1' AND `expired`='0' AND `deleted`='0' AND `listing`!='0' GROUP BY `listing`", 'loadAssocList');
				
				$data = array();
                $total = 0;
				foreach($properties as $property)
				{
					$listing = wpl_global::get_listings($property['listing']);
					if(is_object($listing))
                    {
                        $data[__($listing->name, WPL_TEXTDOMAIN)] = $property['count'];
                        $total += $property['count'];
                    }
				}
				
				$params = array(
					'chart_background'=>'#fafafa',
					'chart_width'=>'100%',
					'chart_height'=>'250px',
					'show_value'=>1,
					'data'=>$data
				);
				
				if(count($data))
                {
                    echo '<div class="wpl-total-properties">'.sprintf(__('Total Properties: %s', WPL_TEXTDOMAIN), $total).'</div>';
                    wpl_global::import_activity('charts:bar', '', $params);
                }
				else echo __('No data!', WPL_TEXTDOMAIN);
			?>
        </div>
    </div>
</div>
<div class="side-6 side-statistic2">
    <div class="panel-wp">
        <h3><?php echo __('Properties by property types', WPL_TEXTDOMAIN); ?></h3>
        <div class="panel-body">
        	<?php
				$properties = wpl_db::select("SELECT COUNT(*) as count, `property_type` FROM `#__wpl_properties` WHERE `finalized`='1' AND `expired`='0' AND `confirmed`='1' AND `deleted`='0' AND `property_type`!='0' GROUP BY `property_type`", 'loadAssocList');
				
				$data = array();
                $total = 0;
				foreach($properties as $property)
				{
					$property_type = wpl_global::get_property_types($property['property_type']);
					if(is_object($property_type))
                    {
                        $data[__($property_type->name, WPL_TEXTDOMAIN)] = $property['count'];
                        $total += $property['count'];
                    }
				}
				
				$params = array(
					'chart_background'=>'#fafafa',
					'chart_width'=>'100%',
					'chart_height'=>'250px',
					'show_value'=>1,
					'data'=>$data
				);
				
				if(count($data))
                {
                    echo '<div class="wpl-total-properties">'.sprintf(__('Total Properties: %s', WPL_TEXTDOMAIN), $total).'</div>';
                    wpl_global::import_activity('charts:bar', '', $params);
                }
				else echo __('No data!', WPL_TEXTDOMAIN);
			?>
        </div>
    </div>
</div>