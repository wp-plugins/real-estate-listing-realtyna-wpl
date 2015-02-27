<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.activities');

class wpl_activity_manager_controller extends wpl_controller
{
    public $tpl_path = 'views.backend.activity_manager.tmpl';
    public $tpl;
    public $activityPath;

    public function home()
    {
        /** check permission **/
        wpl_global::min_access('administrator');

        // get list of all activity
        $this->activities = wpl_activity::get_activities('', '', ' ORDER BY `index` ASC, `ID` DESC');
		$this->available_activities = wpl_activity::get_available_activities();

        /** import tpl * */
        parent::render($this->tpl_path, $this->tpl);
    }
}