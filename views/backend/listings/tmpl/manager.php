<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.css');
$this->_wpl_import($this->tpl_path.'.scripts.js');
?>
<div class="wrap wpl-wp pmanager-wp">
    <header>
        <div id="icon-pmanager" class="icon48"></div>
        <h2><?php echo __('Listing Manager', WPL_TEXTDOMAIN); ?></h2>
    </header>
    <div class="wpl_property_manager_list"><div class="wpl_show_message"></div></div>
    <div class="pmanager-cnt">

        <div class="mass-panel-wp">
            <h3><?php echo __("Mass actions", WPL_TEXTDOMAIN).": "; ?></h3>
            <div class="mass-actions-wp p-actions-wp">
                <div class="group-btn">
                    <div class="mass-btn icon-select-all p-action-btn" onclick="rta.util.checkboxes.selectAll('.properties-wp');">
                        <span><?php echo __('Select all', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-select"></i>
                    </div>
                    <div class="mass-btn icon-deselect-all p-action-btn" onclick="rta.util.checkboxes.deSelectAll('.properties-wp');">
                        <span><?php echo __('Deselect all', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-unselect"></i>
                    </div>
                    <div class="mass-btn icon-toggle p-action-btn" onclick="rta.util.checkboxes.toggle('.properties-wp');">
                        <span><?php echo __('Toggle', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-toggle"></i>
                    </div>
                </div>
                <div class="group-btn">
                    <div class="mass-btn icon-confirm p-action-btn" onclick="mass_confirm_properties();">
                        <span><?php echo __('Confirm', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-confirm"></i>
                    </div>
                    <div class="mass-btn icon-unconfirm p-action-btn" onclick="mass_unconfirm_properties();">
                        <span><?php echo __('Unconfirm', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-unconfirm"></i>
                    </div>
                    <div class="mass-btn icon-delete p-action-btn" onclick="mass_trash_properties();">
                        <span><?php echo __('Delete', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-trash"></i>
                    </div>
                    <div class="mass-btn icon-restore p-action-btn" onclick="mass_restore_properties();">
                        <span><?php echo __('Restore', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-restore"></i>
                    </div>
                    <div class="mass-btn icon-delete-permanently p-action-btn" onclick="mass_delete_completely_properties();">
                        <span><?php echo __('Purge', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-delete"></i>
                    </div>

                </div>
                <?php /** load position1 **/ wpl_activity::load_position('pmanager_position1', array('wpl_properties'=>$this->wpl_properties)); ?>
            </div>
            <?php if(wpl_users::check_access('change_user')): ?>
                <div class="change-user-cnt-wp">
                    <div class="change-user-wp">
                        <label id="pmanager_mass_change_user_label" for="pmanager_mass_change_user_select"><?php echo __('User', WPL_TEXTDOMAIN); ?>: </label>
                        <?php $wpl_users = wpl_users::get_wpl_users(); ?>
                        <select id="pmanager_mass_change_user_select" data-has-chosen onchange="mass_change_user(this.value);">
                            <?php foreach($wpl_users as $wpl_user): ?>
                                <option value="<?php echo $wpl_user->ID; ?>"><?php echo $wpl_user->user_login; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="pagination-wp">
            <?php echo $this->pagination->show(); ?>
        </div>
        <div class="properties-wp">
            <?php
            foreach($this->wpl_properties as $key=>$property)
            {
                if($key == 'current') continue;

                $property_type = isset($property['rendered'][3]['value']) ? $property['rendered'][3]['value'] : '';
                $listing_type = isset($property['rendered'][2]['value']) ? $property['rendered'][2]['value'] : '';
                $price = isset($property['rendered'][6]['value']) ? '<span class="plist_price">'.$property['rendered'][6]['value'].'</span>' : '';
                $builtup_area = isset($property['rendered'][10]['value']) ? $property['rendered'][10]['value'] : '';

                $details_string = trim($property_type.', '.$listing_type.', '.$price.', '.$builtup_area, ', ');
                $location_string = $property['location_text'];

                /** unset previous property **/
                unset($this->wpl_properties['current']);

                /** set current property **/
                $this->wpl_properties['current'] = $property;
                ?>

                <div id="plist_main_div_<?php echo $property['data']['id']; ?>" class="propery-wp">
                    <div class="checkbox-wp">
                        <input class="js-pcheckbox" type="checkbox" id="<?php echo $property['data']['id']; ?>" />    
                    </div>

                    <div class="property-image">
                        <?php /** load position3 **/ wpl_activity::load_position('pmanager_position3', array('wpl_properties'=>$this->wpl_properties)); ?>
                        <?php $listing_target_page = wpl_global::get_client() == 1 ? wpl_global::get_setting('backend_listing_target_page') : NULL; ?>
                        <a class="p-links" href="<?php echo wpl_property::get_property_link('', $property['data']['id'], $listing_target_page); ?>"><?php echo __('View this listing', WPL_TEXTDOMAIN); ?></a>
                    </div>
                    <div class="info-action-wp">
                        <div class="property-detailes">
                            
							<?php if(trim($details_string) != ''): ?>
							<span class="detail p-types"><?php echo $details_string; ?></span>
                            <?php endif; ?>
                            
                            <?php if(trim($location_string) != ''): ?>
							<span class="detail p-location"><?php echo $location_string; ?></span>
                            <?php endif; ?>
                            
                            <div class="detail p-add-date">
                                <span class="title"><?php echo __('Add date', WPL_TEXTDOMAIN).' : '; ?></span>
                                <span class="value"><?php echo $property['data']['add_date']; ?></span>
                            </div>
                            <div class="detail p-visit-time">
                                <span class="title"><?php echo __('Visited times', WPL_TEXTDOMAIN).' : '; ?></span>
                                <span class="value"><?php echo $property['data']['visit_time']; ?></span>
                            </div>

                            <?php if(!$property['data']['finalized']): ?>
                            <div class="finilize-msg" id="pmanager_finalized_status<?php echo $property['data']['id']; ?>">
                                <span><?php echo __('Property is not finalized.', WPL_TEXTDOMAIN); ?></span>
                            </div>
                            <?php endif; ?>

                        </div>
                        <div class="property-actions">
                            <?php /** load position2 **/ wpl_activity::load_position('pmanager_position2', array('property_data'=>$property)); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="pagination-wp">
            <?php echo $this->pagination->show(); ?>
        </div>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>