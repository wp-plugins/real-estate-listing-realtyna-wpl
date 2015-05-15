<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

/**
 * WPL Activity library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 03/10/2013
 * @package WPL
 */
class wpl_activity
{
    /**
     * Frontend Key
     */
    const ACTIVITY_FRONTEND = 0;
    
    /**
     * Backend Key
     */
    const ACTIVITY_BACKEND = 1;
    
    /**
     *
     * @var string
     */
    public static $_wpl_activity;
    
    /**
     *
     * @var string
     */
    public static $_wpl_activity_layout;
    
    /**
     *
     * @var string
     */
    public static $_wpl_activity_file;
    
    /**
     *
     * @var string
     */
    public static $_wpl_activity_name;
    
    /**
     * Renders an activity and returns its output
     * @author Howard <howard@realtyna.com>
     * @static
     * @param object $activity
     * @param array $params
     * @return string
     */
    public static function render_activity($activity, $params = array())
    {
        $activity_params = array();
        if(trim($activity->params) != '') $activity_params = json_decode($activity->params, true);

        $params = array_merge($activity_params, $params);
        
        $wpl_activity = new wpl_activity();
        
        ob_start();
        $wpl_activity->import($activity->activity, $activity->id, $params);
        return $output = ob_get_clean();
    }
    
    /**
     * Loads a specific position
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $position
     * @param array $params
     */
    public static function load_position($position, $params = array())
    {
        $wpl_activity = new wpl_activity();
        $activities = $wpl_activity->get_activities($position, 1);
        
        foreach($activities as $activity)
        {
            $activity_params = array();
            if(trim($activity->params) != '')
                $activity_params = json_decode($activity->params, true);

            $params = array_merge($activity_params, $params);
            $wpl_activity->import($activity->activity, $activity->id, $params);
        }
    }
    
    /**
     * Imports an activity
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $activity
     * @param int $activity_id
     * @param array $params
     * @return mixed
     */
    public static function import($activity, $activity_id = 0, $params = false)
    {
        self::$_wpl_activity = $activity;
        $ex = explode(':', self::$_wpl_activity);

        self::$_wpl_activity_name = $ex[0];
        self::$_wpl_activity_layout = (isset($ex[1]) and trim($ex[1]) != '') ? $ex[1] : 'default';
        self::$_wpl_activity_file = (isset($ex[2]) and trim($ex[2]) != '') ? $ex[2] : 'main';
        $_wpl_activity_client = self::get_activity_client();

        $wpl_activity_path = 'views.' . $_wpl_activity_client . '.' . self::$_wpl_activity_name;
        $path = _wpl_import($wpl_activity_path . '.' . self::$_wpl_activity_file, true, true);

        /** check existation of an activity * */
        if(!wpl_file::exists($path))
        {
            echo '<div>' . __("Activity not found!", WPL_TEXTDOMAIN) . '</div>';
            return;
        }

        /** set activity params * */
        $layout = $wpl_activity_path . '.tmpl.' . self::$_wpl_activity_layout;
        $params = self::get_params($activity_id, $params);
        $activity_class_name = 'wpl_activity_' . self::$_wpl_activity_file . '_' . self::$_wpl_activity_name;

        /** include the activity class if not exists * */
        if(!class_exists($activity_class_name)) include $path;

        $activity_object = new $activity_class_name();
        $activity_object->activity_id = $activity_id;
        $activity_object->start($layout, $params);
    }
    
    /**
     * Returns params of activity
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $activity_id
     * @param boolean $params
     * @return array
     */
    public static function get_params($activity_id, $params = false)
    {
        if(!$params)
        {
            $query = "SELECT * FROM `#__wpl_activities` WHERE `id`='$activity_id'";
            $result = wpl_db::select($query, 'loadAssoc');

            $params = json_decode($result['params'], true);
        }

        if(!$params)
            return array();
        else
            return $params;
    }
    
    /**
     * Get directory of activities
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
    private static function get_activity_client()
    {
        return 'activities';
    }
    
    /**
     * Returns some activity with specified criteria
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $position
     * @param int $enabled
     * @param string $condition
     * @param string $result
     * @return objects
     */
    public static function get_activities($position, $enabled = 1, $condition = '', $result = 'loadObjectList')
    {
        if(trim($condition) == '')
        {
            $client = wpl_global::get_client();
            $condition = " AND `client` IN ($client, 2)";
            
            if(trim($position) != '') $condition .= " AND `position`='$position'";
            if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
            
            /** page associations **/
            if(is_page())
            {
                $post_id = wpl_global::get_the_ID();
                if($post_id) $condition .= " AND (`association_type`='1' OR (`association_type`='2' AND `associations` LIKE '%[".$post_id."]%') OR (`association_type`='3' AND `associations` NOT LIKE '%[".$post_id."]%'))";
            }
            
            $condition .= " ORDER BY `index` ASC, `ID` DESC";
        }

        $query = "SELECT * FROM `#__wpl_activities` WHERE 1 " . $condition;
        return wpl_db::select($query, $result);
    }
    
    /**
     * for importing internal files in object mode
     * @author Howard <howard@realtyna.com>
     * @param string $include
     * @param boolean $override
     * @param boolean $set_footer
     * @param boolean $once
     * @return void
     */
    protected function _wpl_import($include, $override = true, $set_footer = false, $once = false)
    {
        $path = _wpl_import($include, $override, true);

        /** check exists **/
        if(!wpl_file::exists($path)) return;
        
        if(!$set_footer)
        {
            if(!$once) include $path;
            else include_once $path;
        }
        else
        {
            ob_start();
            
            if(!$once) include $path;
            else include_once $path;
            
            wpl_html::set_footer(ob_get_clean());
        }
    }

    /**
     * check Activity is Frontend or Backend
     * @author Kevin J <kevin@realtyna.com>
     * @param string $activity_name 
     * @param integer $mode Activity mode to check
     * @return boolean
     */
    public static function check_activity($activity_name, $mode = self::ACTIVITY_FRONTEND)
    {
        $xml = self::get_system_params($activity_name);
        if ($xml) return ($mode == $xml->backend);
        return false;
    }

    /**
     * sort Activity item by given Activities ID
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $sort_ids id of item to sort by string seperate by coma(,)
     * @return int count of updated items
     */
    public static function sort($sort_ids)
    {
        $ex_sort_ids = explode(',', $sort_ids);
        $data = self::_get_data_for_sort($ex_sort_ids);
        $count = 0;
		
        foreach($ex_sort_ids as $ex_sort_id)
        {
            $newItem = explode(':', $ex_sort_id);
            $currentRank = $data[$newItem[0]];
            $newItem[1] = $newItem[1] / 100;
            if($currentRank != $newItem[1]) // Check Index of Activity is Changed or Not
            {
                self::update_one($newItem[0], 'index', $newItem[1]);
                $count++;
            }
        }
		
        return $count;
    }

    /**
     * get Activities by given Array of ID
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param array $ex_sort_ids
     * @return array
     */
    private static function _get_data_for_sort($ex_sort_ids)
    {
        $id = array();
        $data = array();
		
        foreach($ex_sort_ids as $ex_sort_id)
        {
            $id_section = explode(':', $ex_sort_id);
            $id[] = $id_section[0];
        }
		
        $query = "SELECT `id`, `index` FROM `#__wpl_activities` WHERE `id` IN (".implode(",", $id).") ORDER BY `index` ASC, `id` DESC";
        $activities = wpl_db::select($query, 'loadAssocList');
		
        foreach($activities as $activity)
        {
            $data[$activity['id']] = $activity['index'];
        }
		
        return $data;
    }

    /**
     * Remove Activity by Given ID
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param integer $activity_id Activity ID to remove
     * @return boolean
     */
    public static function remove_activity($activity_id)
    {
        /** trigger event **/
		wpl_global::event_handler('activity_removed', array('id'=>$activity_id));

        $query = "DELETE FROM `#__wpl_activities` WHERE `id`='$activity_id'";
        $result = wpl_db::q($query);
        
        return $result;
    }

    /**
     * update Activity
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param integer $id ID of Activity to Update
     * @param string $key field Key must to change
     * @param string $value new Value to set this
     * @return boolean
     */
    public static function update_one($id, $key, $value = '')
    {
        /** first validation **/
        if(trim($id) == '' or trim($key) == '') return false;
		
        return wpl_db::set('wpl_activities', $id, $key, $value);
    }

    /**
     * get Activity Folder Path
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @return string
     */
    public static function get_activity_folder()
    {
        return WPL_ABSPATH . 'views' . DS . 'activities' . DS;
    }

    /**
     * get Activity System Parameter
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param String $activity_name
     * @return Object|boolean in success return object of xml and FALSE in fail
     */
    public static function get_system_params($activity_name)
    {
        $path = self::get_activity_folder() . $activity_name . DS . 'system.xml';
        if (file_exists($path)) return simplexml_load_file($path);
		
        return false;
    }

    /**
     * Search Activity and return just one result
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $conditions
     * @return Object
     */
    public static function get_activity($conditions)
    {
        return self::get_activities('', '', $conditions, 'loadObject');
    }

    /** Split Activity name and layout name
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $name Activity name
     * @return array [0] is a activity name and [1] if exist is layout name
     */
    public static function get_activity_name_layout($name)
    {
        return explode(':', $name);
    }

    /**
     * Add Activity
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param array $information
     * @return integer inserted Activity ID
     */
    public static function add_activity($information)
    {
        $information = wpl_db::escape($information);
        
        $query  = 'INSERT INTO `#__wpl_activities` (`activity`, `position`, `enabled`, `params`, `show_title`, `title`, `index`, `association_type`, `associations`)';
        $query .= " VALUES ('{$information['activity']}','{$information['position']}',{$information['enabled']},";
        $query .= "'{$information['params']}',{$information['show_title']},'{$information['title']}',".(float)$information['index'].",'{$information['association_type']}','{$information['associations']}')";
        
        $activity_id = wpl_db::q($query, 'insert');
        
        /** trigger event **/
		wpl_global::event_handler('activity_added', array('id'=>$activity_id));
        
        return $activity_id;
    }

    /**
     * Update Activity by Given ID
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param array $information
     * @return boolean
     */
    public static function update_activity($information)
    {
        $information = wpl_db::escape($information);

        $query  = 'UPDATE `#__wpl_activities` SET ';
        $query .= "`activity` = '{$information['activity']}',`position` = '{$information['position']}',`enabled` = {$information['enabled']},";
        $query .= "`params` = '{$information['params']}',`show_title` = {$information['show_title']},";
        $query .= "`title` = '{$information['title']}', `index` = ".(float)$information['index'].",";
        $query .= "`association_type` = '{$information['association_type']}', `associations` = '{$information['associations']}', `client` = '{$information['client']}'";
        $query .= " WHERE `id` = '".$information['activity_id']."'";
        
        /** trigger event **/
		wpl_global::event_handler('activity_updated', array('id'=>$information['activity_id']));
        
        return wpl_db::q($query);
    }
    
    /**
     * get Activity Options form by Activity Name
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $activity_name
     * @return string|boolean if Activity have Option form return Path or return false if Activity Option not Exist
     */
    public static function get_activity_option_form($activity_name)
    {
        $optionsPath = self::get_activity_folder() . $activity_name . DS . 'form.php';
        if(file_exists($optionsPath))
        {
            return $optionsPath;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * get list of activity layout
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param type $activityName
     * @return array
     */
    public static function get_activity_layout($activityName)
    {
        $layouts = wpl_folder::files(self::get_activity_folder() . $activityName . DS . 'tmpl');
        $activity_layouts = array();
		
        foreach($layouts as $layout)
        {
            if(strpos($layout, '.html') !== false or strpos($layout, 'internal_') !== false) continue;
            
            $file = basename($layout, ".php");
            $activity_layouts[] = $file;
        }
		
        return $activity_layouts;
    }
    
    /**
     * generate activity layout select options
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param type $activity_name
     * @param type $current_layout
     * @return string select html code
     */
    public static function load_layouts_html($activity_name = '', $current_layout = '')
    {
        if(trim($activity_name) != '') $activity_layouts = self::get_activity_layout($activity_name);
		
        $html_form = '<select id="wpl_layout" class="text_box" name="info[layout]">';
        $html_form .= '<option value="">-----</option>';
		
        if(!empty($activity_layouts))
        {
            foreach($activity_layouts as $layout)
            {
                $html_form .= '<option';
				
                if (trim($current_layout) != '' && $layout == $current_layout)
                {
                    $html_form .= ' selected="selected" ';
                }
				
                $html_form .= ' value="'.$layout.'">'.$layout.'</option>';
            }
        }
		
        $html_form .= '</select>';
        return $html_form;
    }
	
	/**
     * get All Activities in Activity folder and remove Backend Activity
     * @author Kevin J <kevin@realtyna.com>
     * @return array list of Frontend activity list
     */
    public static function get_available_activities()
    {
        $activities_folders = wpl_folder::folders(wpl_activity::get_activity_folder());
        $frontend_activity = array();
		
        foreach($activities_folders as $activity)
        {
            if(wpl_activity::check_activity($activity, wpl_activity::ACTIVITY_FRONTEND)) $frontend_activity[] = $activity;
        }
		
        return $frontend_activity;
    }
}