<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.property');
_wpl_import('libraries.items');

class wpl_listing_controller extends wpl_controller
{
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('agent');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'save_room') $this->save_room();
		elseif($function == 'delete_room') $this->delete_room();
	}
	
	public function save_room()
	{
		$pid = wpl_request::getVar('pid');
		$kind = wpl_request::getVar('kind');
		$room_name = wpl_request::getVar('room_name');
		$room_type_id = wpl_request::getVar('room_type_id');
		$x = wpl_request::getVar('x_param');
		$y = wpl_request::getVar('y_param');
		$index = floatval(wpl_items::get_maximum_index($pid, 'rooms', $kind))+1.00;
        
		$item = array('parent_id'=>$pid, 'parent_kind'=>$kind, 'item_type'=>'rooms', 'item_cat'=>$room_type_id, 'item_name'=>$room_name, 
					  'creation_date'=>date("Y-m-d H:i:s"), 'index'=>$index, 'item_extra1'=>$x, 'item_extra2'=>$y);
		
		$id = wpl_items::save($item);
		
		$res = (int) $id;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = $id;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	public function delete_room()
	{
        $item_id = wpl_request::getVar('item_id');
        
		/** deleting the room **/
		if($item_id != -1) $result = wpl_items::delete($item_id);
		
		$res = (int) $result;
		$message = $res ? __('Deleted.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = $item_id;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
}