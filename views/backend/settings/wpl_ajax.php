<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.settings');

class wpl_settings_controller extends wpl_controller
{
	var $tpl_path = 'views.backend.settings.tmpl';
	var $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'save')
		{
			$setting_name = wpl_request::getVar('setting_name');
			$setting_value = wpl_request::getVar('setting_value');
			$setting_category = wpl_request::getVar('setting_category');
			
			self::save($setting_name, $setting_value, $setting_category);
		}
		elseif($function == 'save_watermark_image')
		{
			$file = $_FILES['wpl_watermark_uploader'];
			self::save_watermark_image($file);
		}
		elseif($function == 'clear_cache') $this->clear_cache();
        elseif($function == 'remove_upload') $this->remove_upload();
	}
	
	private function save($setting_name, $setting_value, $setting_category)
	{
		$res = wpl_settings::save_setting($setting_name, $setting_value, $setting_category);
		
		$res = (int) $res;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
    
	/**
	 * added by Francis
	 * @param array $file: the array which come from $_FILE
	 * description       : save watermark image to the specific path and
	 *                     save filename as a setting value to database
	 */
	private function save_watermark_image($file)
	{
		$filename = wpl_global::normalize_string($file['name']);
		$ext_array = array('jpg','png','gif','jpeg');
		$error = '';
		$message = '';
        
		if(!empty($file['error']) or (empty($file['tmp_name']) or ($file['tmp_name'] == 'none')))
		{
			$error = __('An error ocurred uploading your file.', WPL_TEXTDOMAIN);
		}
		else 
		{
			// check the extention
			$extention = strtolower(wpl_file::getExt($file['name']));

			if(!in_array($extention, $ext_array))
			{
				$error = __('File extension should be jpg, png or gif.', WPL_TEXTDOMAIN);
			}

			if($error == '')
			{
				$dest = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'system' .DS. $filename;
				wpl_file::upload($file['tmp_name'], $dest);
				wpl_settings::save_setting('watermark_url', $filename, 2);
			}
		}

		$response = array('error'=>$error, 'message'=>$message);

		echo json_encode($response);
		exit;
	}
	
	private function clear_cache()
	{
		$cache_type = wpl_request::getVar('cache_type', NULL);
		$res = wpl_settings::clear_cache($cache_type);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
    
    private function remove_upload()
    {
        $setting_name = wpl_request::getVar('setting_name', '');
        $settings_value = wpl_settings::get($setting_name);
        $upload_src = wpl_global::get_wpl_asset_url('img/system/'.$settings_value);
        
        wpl_settings::save_setting($setting_name, NULL);
        wpl_file::delete($upload_src);
        
        /** Remove Thumbnails **/
        wpl_settings::clear_cache('listings_thumbnails');
        wpl_settings::clear_cache('users_thumbnails');
        
        $response = array('success'=>1, 'message'=>__('Uploaded file removed successfully!', WPL_TEXTDOMAIN));
		
		echo json_encode($response);
		exit;
    }
}