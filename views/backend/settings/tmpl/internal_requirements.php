<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$php_version = wpl_global::php_version();
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
    	<!-- PHP version -->
    	<li>
        	<span class="wpl-requirement-name"><?php echo __('PHP version', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require">5.3.1</span>
            <span class="wpl-requirement-current"><?php echo $php_version; ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php echo (!version_compare($php_version, '5.3.1', '>=') ? 'danger' : 'confirm'); ?>"></i>
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
        <!-- CURL -->
        <?php $curl = function_exists('curl_version') ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php echo __('CURL', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-require"><?php echo __('Installed', WPL_TEXTDOMAIN); ?></span>
            <span class="wpl-requirement-current"><?php echo $curl ? __('Installed', WPL_TEXTDOMAIN) : __('Not Installed', WPL_TEXTDOMAIN); ?></span>
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