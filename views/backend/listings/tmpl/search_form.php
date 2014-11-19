<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="panel-wp lm-search-form-wp">
    <h3><?php echo __("Search", WPL_TEXTDOMAIN); ?></h3>

    <div id="wpl_listing_manager_search_form_cnt" class="panel-body">
        <div class="pwizard-panel">
            <div class="pwizard-section">
                <div class="prow">
                    <?php $current_value = wpl_request::getVar('sf_select_listing', '-1'); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <select name="sf_select_listing" id="sf_select_listing">
                            <option value="-1"><?php echo __('Listing', WPL_TEXTDOMAIN); ?></option>
                            <?php foreach ($this->listings as $listing): ?>
                                <option
                                    value="<?php echo $listing['id']; ?>" <?php echo($current_value == $listing['id'] ? 'selected="selected"' : ''); ?>><?php echo __($listing['name'], WPL_TEXTDOMAIN); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php $current_value = wpl_request::getVar('sf_select_property_type', '-1'); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <select name="sf_select_property_type" id="sf_select_property_type">
                            <option value="-1"><?php echo __('Property Type', WPL_TEXTDOMAIN); ?></option>
                            <?php foreach ($this->property_types as $property_type): ?>
                                <option
                                    value="<?php echo $property_type['id']; ?>" <?php echo($current_value == $property_type['id'] ? 'selected="selected"' : ''); ?>><?php echo __($property_type['name'], WPL_TEXTDOMAIN); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if (wpl_users::is_administrator()): ?>
                        <?php $current_value = wpl_request::getVar('sf_select_user_id', '-1'); ?>
                        <div class="wpl_listing_manager_search_form_element_cnt">
                            <select name="sf_select_user_id" id="sf_select_user_id">
                                <option value="-1"><?php echo __('User', WPL_TEXTDOMAIN); ?></option>
                                <?php foreach ($this->users as $user): ?>
                                    <option
                                        value="<?php echo $user->ID; ?>" <?php echo($current_value == $user->ID ? 'selected="selected"' : ''); ?>><?php echo __($user->user_login, WPL_TEXTDOMAIN); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php $current_value = wpl_request::getVar('sf_select_confirmed', '-1'); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <select name="sf_select_confirmed" id="sf_select_confirmed">
                            <option value="-1"><?php echo __('Confirm Status', WPL_TEXTDOMAIN); ?></option>
                            <option
                                value="1" <?php echo($current_value == '1' ? 'selected="selected"' : ''); ?>><?php echo __('Confirmed', WPL_TEXTDOMAIN); ?></option>
                            <option
                                value="0" <?php echo($current_value == '0' ? 'selected="selected"' : ''); ?>><?php echo __('Unconfirmed', WPL_TEXTDOMAIN); ?></option>
                        </select>
                    </div>

                    <?php $current_value = wpl_request::getVar('sf_select_finalized', '-1'); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <select name="sf_select_finalized" id="sf_select_finalized">
                            <option value="-1"><?php echo __('Finalize Status', WPL_TEXTDOMAIN); ?></option>
                            <option
                                value="1" <?php echo($current_value == '1' ? 'selected="selected"' : ''); ?>><?php echo __('Finalized', WPL_TEXTDOMAIN); ?></option>
                            <option
                                value="0" <?php echo($current_value == '0' ? 'selected="selected"' : ''); ?>><?php echo __('Unfinalized', WPL_TEXTDOMAIN); ?></option>
                        </select>
                    </div>
                </div>
                <div class="prow">
                    
                    <?php $current_value = wpl_request::getVar('sf_select_mls_id', ''); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <input type="text" name="sf_select_mls_id" id="sf_select_mls_id" value="<?php echo $current_value; ?>"
                               placeholder="<?php echo __('Listing ID', WPL_TEXTDOMAIN); ?>"/>
                    </div>

                    <?php $current_value = wpl_request::getVar('sf_locationtextsearch', ''); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <input type="text" name="sf_locationtextsearch" id="sf_locationtextsearch"
                               value="<?php echo $current_value; ?>"
                               placeholder="<?php echo __('Location', WPL_TEXTDOMAIN); ?>"/>
                    </div>

                    <?php $current_value = wpl_request::getVar('sf_textsearch_textsearch', ''); ?>
                    <div class="wpl_listing_manager_search_form_element_cnt">
                        <input type="text" name="sf_textsearch_textsearch" id="sf_textsearch_textsearch"
                               value="<?php echo $current_value; ?>"
                               placeholder="<?php echo __('Text Search', WPL_TEXTDOMAIN); ?>"/>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="prow wpl-btn-wp">
        <div class="wpl_listing_manager_search_form_element_cnt">
            <button class="wpl-button button-1" onclick="wpl_search_listings();"><?php echo __('Search', WPL_TEXTDOMAIN); ?></button>
            <span class="wpl_reset_button" onclick="wpl_reset_listings();"><?php echo __('Reset', WPL_TEXTDOMAIN); ?></span>
        </div>
    </div>
</div>