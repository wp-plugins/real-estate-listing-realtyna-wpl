<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="side-8 side-addons" id="wpl_dashboard_side_addons">
    <div class="panel-wp">
        <h3><?php echo __('WPL Add Ons', WPL_TEXTDOMAIN); ?></h3>
        
        <div class="panel-body">
            <?php if(!wpl_global::check_addon('pro')): ?>
            <p class="pro-message"><?php echo __('You cannot install any add-on on WPL basic! You should upgrade to WPL PRO first.', WPL_TEXTDOMAIN); ?></p>
            <?php else: ?>
            <div class="wpl-addons-install-wp wpl_install_addons_container">
                <div class="wpl_realtyna_credentials_container">
                	<input type="text" name="realtyna_username" id="realtyna_username" value="<?php if(isset($this->settings['realtyna_username'])) echo $this->settings['realtyna_username']; ?>" placeholder="<?php echo __('Billing username', WPL_TEXTDOMAIN); ?>" />
                    <input type="password" name="realtyna_password" id="realtyna_password" value="<?php if(isset($this->settings['realtyna_password'])) echo $this->settings['realtyna_password']; ?>" placeholder="<?php echo __('Billing password', WPL_TEXTDOMAIN); ?>" />
                    <input class="wpl-button button-1" type="button" onclick="save_realtyna_credentials();" value="<?php echo __('Save', WPL_TEXTDOMAIN); ?>" />
                    &nbsp;<span id="wpl_realtyna_credentials_check"><span class="action-btn <?php echo ((isset($this->settings['realtyna_verified']) and $this->settings['realtyna_verified']) ? 'icon-enabled' : 'icon-disabled'); ?>"></span></span>
                    <br />
                    <span class="wpl_realtyna_credentials_tip"><?php echo __('Mandatory Realtyna Billing Credentials are necessary for Premium Support and Add On Updates!', WPL_TEXTDOMAIN); ?></span>
                </div>
                <label for="wpl_addon_file"><?php echo __('Install Add On', WPL_TEXTDOMAIN); ?> : </label>
                <?php
					$params = array('html_element_id' => 'wpl_addon_file', 'html_path_message' => '.wpl_addons_message .wpl_show_message', 'html_ajax_loader' => '#wpl_install_addon_ajax_loader', 'request_str' => 'admin.php?wpl_format=b:wpl:ajax&wpl_function=install_package', 'valid_extensions' => array('zip'));
					wpl_global::import_activity('ajax_file_upload:default', '', $params);
                ?>
                <span id="wpl_install_addon_ajax_loader"></span>
            </div>
            <div class="wpl-addons-wp wpl_addons_container">
            	<div class="wpl_addons_message"><div class="wpl_show_message"></div></div>
                <?php foreach ($this->addons as $addon): ?>
                    <div class="wpl-addon-row wpl_addon_container" id="wpl_addon_container<?php echo $addon['id']; ?>">
                        <label class="wpl_addon_name"><?php echo $addon['name']; ?></label>
                        <span title="<?php echo __('Version', WPL_TEXTDOMAIN); ?>"><?php echo $addon['version']; ?></span>
                        <?php if($addon['updatable']): ?>
                        <span class="action-btn icon-recycle-2" onclick="<?php echo (trim($addon['message']) != '' ? 'trigger_addon_update('.$addon['id'].');' : 'check_addon_update('.$addon['id'].');'); ?>" title="<?php echo __('Update', WPL_TEXTDOMAIN); ?>"></span>
                        <?php endif; ?>
						<?php if(trim($addon['message']) != ''): ?>
						<span class="wpl_addon_message"><?php echo $addon['message']; ?></span>
						<?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>