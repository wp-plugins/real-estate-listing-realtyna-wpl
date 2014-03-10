<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.addon_pro');

/** activity class **/
class wpl_activity_main_listing_links extends wpl_activity
{
    public static $js_loaded = false;
    
	public function start($layout, $params)
	{
        if (!self::$js_loaded)
        {
            $this->_wpl_import('views.activities.listing_links.tmpl.scripts.js');
            self::$js_loaded = true;
        }

        /** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}