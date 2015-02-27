<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** activity class **/
class wpl_activity_main_params extends wpl_activity
{
    public $tpl_path = 'views.activities.params.tmpl';
    
	public function start($layout, $params)
	{
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('author');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'save_params') $this->save_params();
	}
	
	private function save_params()
	{
		$table = wpl_request::getVar('table');
		$id = wpl_request::getVar('id');
		
		$post = wpl_request::get('post');
		$keys = (isset($post['wpl_params']) and is_array($post['wpl_params']['keys'])) ? $post['wpl_params']['keys'] : array();
		$values = (isset($post['wpl_params']) and is_array($post['wpl_params']['values'])) ? $post['wpl_params']['values'] : array();
		
		$params = array();
		foreach($keys as $key=>$value)
		{
			if(trim($value) == '') continue;
			$params[$value] = $values[$key];
		}
		
		/** save params **/
		wpl_global::set_params($table, $id, $params);
		
		/** trigger event **/
		wpl_global::event_handler('params_saved', array('table'=>$table, 'id'=>$id, 'params'=>$params));
		
		$res = 1;
		$message = $res ? __('Params Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
}