<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-requirements-container">
	<ul>
        <!-- Headers -->
        <li class="header">
            <span class="wpl-requirement-name"></span>
            <span class="wpl-requirement-require"><?php echo __('Requirement', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-current"><?php echo __('Current', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<?php echo __('Status', WPL_TEXTDOMAIN); ?>
            </span>
        </li>
        <!-- Web Server -->
        <?php
            $webserver_name = strtolower(wpl_request::getVar('SERVER_SOFTWARE', 'UNKNOWN', 'SERVER'));
            $webserver = (strpos($webserver_name, 'apache') !== false or strpos($webserver_name, 'nginx') !== false) ? true : false;
        ?>
        <li>
        	<span class="wpl-requirement-name"><?php echo __('Web server', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require"><?php echo __('Standard', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-current"><?php echo $webserver ? __('Yes', WPL_TEXTDOMAIN) : __('No', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php echo $webserver ? 'confirm' : 'warning'; ?>"></i>
            </span>
		</li>
    	<!-- PHP version -->
        <?php $php_version = wpl_global::php_version(); ?>
    	<li>
        	<span class="wpl-requirement-name"><?php echo __('PHP version', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require">5.3.1</span>
            <span class="wpl-requirement-current"><?php echo $php_version; ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php echo (!version_compare($php_version, '5.3.1', '>=') ? 'danger' : 'confirm'); ?>"></i>
            </span>
		</li>
        <!-- WP version -->
        <?php $wp_version = wpl_global::wp_version(); ?>
    	<li>
        	<span class="wpl-requirement-name"><?php echo __('WP version', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require">3.0.1</span>
            <span class="wpl-requirement-current"><?php echo $wp_version; ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php echo (!version_compare($wp_version, '3.0.1', '>=') ? 'danger' : 'confirm'); ?>"></i>
            </span>
		</li>
        <!-- WP debug -->
        <?php $wp_debug = WP_DEBUG ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php echo __('WP debug', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require"><?php echo __('Off', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-current"><?php echo $wp_debug ? __('On', WPL_TEXTDOMAIN) : __('Off', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php echo $wp_debug ? 'warning' : 'confirm'; ?>"></i>
            </span>
		</li>
        <!-- Upload directory permission -->
        <?php $wpl_writable = substr(sprintf('%o', fileperms(wpl_global::get_upload_base_path())), -4) >= '0755' ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php echo __('Upload dir', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require"><?php echo __('Writable', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-current"><?php echo $wpl_writable ? __('Yes', WPL_TEXTDOMAIN) : __('No', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php echo $wpl_writable ? 'confirm' : 'danger'; ?>"></i>
            </span>
		</li>
        <!-- GD library -->
        <?php $gd = (extension_loaded('gd') && function_exists('gd_info')) ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php echo __('GD library', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require"><?php echo __('Installed', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-current"><?php echo $gd ? __('Installed', WPL_TEXTDOMAIN) : __('Not Installed', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php echo $gd ? 'confirm' : 'danger'; ?>"></i>
            </span>
		</li>
        <!-- CURL -->
        <?php $curl = function_exists('curl_version') ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php echo __('CURL', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require"><?php echo __('Installed', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-current"><?php echo $curl ? __('Installed', WPL_TEXTDOMAIN) : __('Not Installed', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php echo $curl ? 'confirm' : 'warning'; ?>"></i>
            </span>
		</li>
        <!-- Safe Mode -->
        <?php $safe = ini_get('safe_mode'); $safe_mode = (!$safe or strtolower($safe) == 'off') ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php echo __('Safe Mode', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require"><?php echo __('Off', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-current"><?php echo $safe_mode ? __('Off', WPL_TEXTDOMAIN) : __('On', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php echo $safe_mode ? 'confirm' : 'warning'; ?>"></i>
            </span>
		</li>
        <!-- Server providers offers -->
        <li class="wpl_server_offers">
        	<a href="http://hosting.realtyna.com/" target="_blank">Cloud real estate web hosting</a><br />
            <a href="http://bluehost.com/track/realtyna" target="_blank">Bluehost WordPress</a>
		</li>
    </ul>
</div>