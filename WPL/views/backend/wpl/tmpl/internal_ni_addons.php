<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$addons = array();
$addons[0] = array('name'=>'WPL PRO', 'id'=>'3', 'addon_name'=>'pro', 'description'=>'Professional features such as Membership manager, PDF fyler, Radius search and etc', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=pro', 'button_text'=>'Upgrade');
$addons[1] = array('name'=>'MLS addon', 'id'=>'1', 'addon_name'=>'mls', 'description'=>'MLS/IDX/RETS Integration', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=mls', 'button_text'=>'Download');
$addons[2] = array('name'=>'Multisite addon', 'id'=>'4', 'addon_name'=>'multisite', 'description'=>'Multisite support for WPL', 'readmore_link'=>'http://wpl.realtyna.com/redirect.php?action=download&item=multisite', 'button_text'=>'Download');
?>
<div class="side-14 side-ni-addons">
    <div class="panel-wp">
        <h3><?php echo __('Optional Addons', WPL_TEXTDOMAIN); ?></h3>

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
                	<div><?php echo __('Congratulations! All the optional addons are installed on your website!', WPL_TEXTDOMAIN); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>