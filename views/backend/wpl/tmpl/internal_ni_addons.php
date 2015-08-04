<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$addons = array();
$addons[0] = array('name'=>'WPL PRO', 'id'=>'3', 'addon_name'=>'pro', 'description'=>'Professional features such as Membership Manager, PDF Flyer, Radius Search etc.', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=pro', 'button_text'=>'Upgrade');
$addons[1] = array('name'=>'MLS Add On', 'id'=>'1', 'addon_name'=>'mls', 'description'=>'MLS/IDX/RETS Integration', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=mls', 'button_text'=>'Download');
$addons[2] = array('name'=>'Franchise Add On', 'id'=>'4', 'addon_name'=>'franchise', 'description'=>'Franchise/Multi Site support for WPL', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=franchise', 'button_text'=>'Download');
$addons[3] = array('name'=>'Importer Add On', 'id'=>'5', 'addon_name'=>'importer', 'description'=>'Import listings from CSV/XML files', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=importer', 'button_text'=>'Download');
$addons[4] = array('name'=>'Complex Add On', 'id'=>'7', 'addon_name'=>'complex', 'description'=>'Adding Complexes/Condos and assign listings to a certain Complex/Condo', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=complex', 'button_text'=>'Download');
$addons[5] = array('name'=>'Exporter Add On', 'id'=>'8', 'addon_name'=>'exporter', 'description'=>'Export Properties to XML/CSV files', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=exporter', 'button_text'=>'Download');
$addons[6] = array('name'=>'Mortgage Calculator', 'id'=>'11', 'addon_name'=>'mortgage_calculator', 'description'=>'Mortgage Calculator', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=mortgage_calculator', 'button_text'=>'Download');
$addons[7] = array('name'=>'Membership', 'id'=>'9', 'addon_name'=>'membership', 'description'=>'Empower your WordPress Real Estate website with an advanced Membership System for WPL.', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=membership', 'button_text'=>'Download');
$addons[8] = array('name'=>'Availability Calendar', 'id'=>'13', 'addon_name'=>'calendar', 'description'=>'Availability info on calendar for vacation rental listings.', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=calendar', 'button_text'=>'Download');
$addons[9] = array('name'=>'Demographic Info', 'id'=>'12', 'addon_name'=>'demographic', 'description'=>'WPL Add-on for drawing and defining regions on the map for different demographic status.', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=demographic', 'button_text'=>'Download');
$addons[10] = array('name'=>'Optimizer', 'id'=>'17', 'addon_name'=>'optimizer', 'description'=>'Optimize property images and speed up your website.', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=optimizer', 'button_text'=>'Download');
?>
<div class="side-6 side-ni-addons" id="wpl_dashboard_ni_addons">
    <div class="panel-wp">
        <h3><?php echo __('Optional Add Ons', WPL_TEXTDOMAIN); ?></h3>

        <div class="panel-body">
            <div class="wpl-ni-addons-wp wpl_ni_addons_container">
                <?php $i = 0; foreach($addons as $addon): if(wpl_global::check_addon($addon['addon_name'])) continue; $i++; ?>
                    <div class="wpl-ni-addon-row wpl_ni_addon_container" id="wpl_ni_addons_container<?php echo $addon['id']; ?>">
                        <div class="wpl_ni_addon_subject">
                            <span class="wpl_ni_addons_addon_name"><?php echo $addon['name']; ?></span>
                            <a class="readmore_link" href="<?php echo $addon['readmore_link']; ?>" target="_blank"><?php echo __($addon['button_text'], WPL_TEXTDOMAIN); ?></a>
                        </div>
                        <div class="wpl_ni_addon_description"><?php echo $addon['description']; ?></div>
                    </div>
                <?php endforeach; ?>
                <?php if($i == 0): ?>
                	<div><?php echo __('Congratulations! All the optional Add Ons are installed on your website!', WPL_TEXTDOMAIN); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>