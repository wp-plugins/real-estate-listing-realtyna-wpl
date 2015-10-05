<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Process service
 * @author Howard <howard@realtyna.com>
 * @date 11/17/2013
 * @package WPL
 */
class wpl_service_process
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run()
	{
		$wpl_format = wpl_request::getVar('wpl_format');
		if(trim($wpl_format) == '') return;
		
		/** add listing menu **/
		if($wpl_format == 'b:listing:ajax')
		{
			$wpl_function = wpl_request::getVar('wpl_function');
			
			if($wpl_function == 'save')
			{
				$table_column = wpl_request::getVar('table_column');
				$value = wpl_request::getVar('value');
				
				/** for checking limitation on feature and hot tag **/
				if(($table_column == 'sp_featured' or $table_column == 'sp_hot') and $value == 1)
				{
					_wpl_import('libraries.property');
					$current_user_id = wpl_users::get_cur_user_id();
					$user_data = wpl_users::get_wpl_user($current_user_id);
                    
					$user_limit = $table_column == 'sp_featured' ? $user_data->maccess_num_feat : $user_data->maccess_num_hot;
                    
                    $model = new wpl_property();
					$used = $model->get_properties_count(" AND `user_id`='$current_user_id' AND `$table_column`='1'");
					
					if($used >= $user_limit and $user_limit != '-1') self::response(array('success'=>'0', 'message'=>'', 'data'=>'', 'js'=>"wplj(form_element_id).prop('checked', false); wpl_alert(\"".__('Your membership limit reached. contact to administrator if you want to upgrade!', WPL_TEXTDOMAIN)."\");"));
				}
			}
		}
	}
	
    /**
     * Response function
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $response
     */
	private static function response($response)
	{
		echo json_encode($response);
		exit;
	}
}