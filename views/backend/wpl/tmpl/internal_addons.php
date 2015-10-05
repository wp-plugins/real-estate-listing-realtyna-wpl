<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="side-8 side-addons" id="wpl_dashboard_side_addons">
    <div class="panel-wp">
        <h3><?php echo __('Purchased Add Ons', WPL_TEXTDOMAIN); ?></h3>
        
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
                        <span class="wpl_addon_info">
							<?php if(trim($addon['message']) != ''): ?>
							<span class="wpl_addon_message"><?php echo $addon['message']; ?></span>
							<?php endif; ?>
                        	<span title="<?php echo __('Version', WPL_TEXTDOMAIN); ?>"><?php echo $addon['version']; ?></span>
	                        <?php if($addon['updatable']): ?>
	                        <span class="action-btn icon-recycle-2" onclick="<?php echo (trim($addon['message']) != '' ? 'trigger_addon_update('.$addon['id'].');' : 'check_addon_update('.$addon['id'].');'); ?>" title="<?php echo __('Update', WPL_TEXTDOMAIN); ?>"></span>
	                        <?php endif; ?>
	                        <?php /**<span class="wpl_addon_log_btn"><?php echo __('LOG', WPL_TEXTDOMAIN); ?></span>**/ ?>
                        </span>
                        <span id="wpl_addon_log_info<?php echo $addon['id']; ?>" class="wpl_addon_log_info" style="display:none">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui. officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusa ntium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architec to beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui. officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusa ntium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architec to beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius.</span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>