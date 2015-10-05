<?php
/**
	Plugin Name: WPL
	Plugin URI: http://wpl.realtyna.com
	Description: This is a professional WordPress real estate plugin created by Realtyna.
	Author: Realtyna Softwares
	Project Manager: howard@realtyna.com
	Version: 2.8.0
	Author URI: http://wpl.realtyna.com
**/

/**
 *  WPL execution
 */
define('_WPLEXEC', 1);

/**
 * Directory Separator
 */
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

/** WPL ABS PATH **/
define('WPL_ABSPATH', dirname(__FILE__) .DS);
define('WPL_BASENAME', basename(WPL_ABSPATH));
define('WPL_UP_ABSPATH', ABSPATH .'wp-content' .DS. 'uploads' .DS. 'WPL' .DS);

define('WPL_TEXTDOMAIN', 'wpl'); /** WPL textdomain for language **/
define('WPL_VERSION', '2.8.0'); /** WPL version **/

require WPL_ABSPATH.'config.php';

_wpl_import('global');
_wpl_import('libraries.request');
_wpl_import('libraries.file');
_wpl_import('libraries.folder');
_wpl_import('libraries.db');
_wpl_import('libraries.html');
_wpl_import('libraries.sef');
_wpl_import('libraries.property');
_wpl_import('libraries.users');
_wpl_import('controller');
_wpl_import('extensions');

/** request controller **/
_wpl_import('request_controller');