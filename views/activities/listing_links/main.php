<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.addon_pro');

/** activity class **/
class wpl_activity_main_listing_links extends wpl_activity
{
    var $tpl_path = 'views.activities.listing_links.tmpl';
    public static $js_loaded = false;
    
	public function start($layout, $params)
	{
        if (!self::$js_loaded)
        {
            $this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
            self::$js_loaded = true;
        }

        /** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}