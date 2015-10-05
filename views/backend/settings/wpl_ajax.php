<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.settings');
_wpl_import('libraries.flex');

class wpl_settings_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.settings.tmpl';
	public $tpl;
	
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
			
			$this->save($setting_name, $setting_value, $setting_category);
		}
		elseif($function == 'save_watermark_image') $this->save_watermark_image();
        elseif($function == 'save_languages') $this->save_languages();
        elseif($function == 'generate_language_keywords') $this->generate_language_keywords();
        elseif($function == 'save_customizer') $this->save_customizer();
		elseif($function == 'clear_cache') $this->clear_cache();
        elseif($function == 'remove_upload') $this->remove_upload();
        elseif($function == 'clear_calendar_data') $this->clear_calendar_data();
        elseif($function == 'import_settings') $this->import_settings();
        elseif($function == 'export_settings') $this->export_settings();
        elseif($function == 'uploader') $this->uploader();
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
	private function save_watermark_image()
	{
        $file = wpl_request::getVar('wpl_watermark_uploader', NULL, 'FILES');
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

			if(!in_array($extention, $ext_array)) $error = __('File extension should be jpg, png or gif.', WPL_TEXTDOMAIN);
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
    
    private function save_languages()
    {
        $raws = wpl_request::getVar('wpllangs', array());
        
        $langs = array();
        $lang_options = array();
        
        foreach($raws as $key=>$raw)
        {
            if(!trim($raw['full_code'])) continue;
            
            $langs[$key] = $raw['full_code'];
            $lang_options[$key] = $raw;
        }
        
        wpl_settings::save_setting('lang_options', json_encode($lang_options));
        wpl_addon_pro::save_languages($langs);
		
		$res = 1;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
    }
    
    private function save_customizer()
    {
        $wplcustomizer = wpl_request::getVar('wplcustomizer', array());
        
        $_variables = wpl_file::read(WPL_ABSPATH.'assets'.DS.'scss'.DS.'ui_customizer'.DS.'_variables_source.scss');
        foreach($wplcustomizer as $key=>$value) $_variables = str_replace('['.$key.']', $value, $_variables);
        
        /** Write on _variables.scss file **/
        wpl_file::write(WPL_ABSPATH.'assets'.DS.'scss'.DS.'ui_customizer'.DS.'_variables.scss', $_variables);
        
        /** Initialize SCSS Compiler **/
        _wpl_import('libraries.scss');
        
        $wplscss = new wpl_scss();
        $wplscss->set_import_path(WPL_ABSPATH.'assets'.DS.'scss'.DS.'ui_customizer'.DS);
        
        /** Compile **/
        $css_path = WPL_ABSPATH.'assets'.DS.'css'.DS.'ui_customizer'.DS.'wpl.css';
        
        // Make WPL UI Customizer multisite support
        $current_blog_id = wpl_global::get_current_blog_id();
        if($current_blog_id and $current_blog_id != 1) $css_path = WPL_ABSPATH.'assets'.DS.'css'.DS.'ui_customizer'.DS.'wpl'.$current_blog_id.'.css';
        
        $wplscss->compile_file(WPL_ABSPATH.'assets'.DS.'scss'.DS.'ui_customizer'.DS.'wpl.scss', $css_path);
        
        /** Save UI Customizer Options in Database **/
        wpl_settings::save_setting('wpl_ui_customizer', json_encode($wplcustomizer));
        
		$res = 1;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
    }
    
    private function generate_language_keywords()
    {
        wpl_addon_pro::generate_dynamic_keywords();
		
		$res = 1;
		$message = $res ? __('Language strings are generated.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
    }
    
    private function clear_calendar_data()
    {
        _wpl_import('libraries.addon_calendar');
        
        $res = wpl_addon_calendar::clear_calendar_data();
        $message = $res ? __('Calendar Data removed.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
        $data = NULL;
        
        $response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		echo json_encode($response);
		exit;
    }

    private function import_settings()
    {
		$file = wpl_request::getVar('wpl_import_file', '', 'FILES');
		$tmp_directory = wpl_global::init_tmp_folder();
		$ext = strtolower(wpl_file::getExt($file['name']));
		$settings_file = $tmp_directory.'settings.'.$ext;
		
		$response = wpl_global::upload($file, $settings_file, array('json', 'xml'), 20971520); #20MB
		if(trim($response['error']) != '')
		{
			echo json_encode($response);
			exit;
		}
		
		if(wpl_settings::import_settings($settings_file))
		{
			$error = '';
        	$message = __('Settings have been imported successfuly!', WPL_TEXTDOMAIN);
		}
        else
        {
        	$error = '1';
        	$message = __('Cannot import settings!', WPL_TEXTDOMAIN);
        }

		echo json_encode(array('error'=>$error, 'message'=>$message));
		exit;
    }

    private function export_settings()
    {
    	$format = wpl_request::getVar('wpl_export_format', 'json');
    	$output = wpl_settings::export_settings($format);

    	if($format == 'json')
    	{
    		header('Content-disposition: attachment; filename=settings.json');
			header('Content-type: application/json');	
    	}
    	elseif($format == 'xml')
    	{
    		header('Content-disposition: attachment; filename=settings.xml');
			header('Content-type: application/xml');
    	}

    	echo $output;
		exit;
    }
    
    private function uploader()
	{
        $settings_key = wpl_request::getVar('settings_key', '');
        $file = wpl_request::getVar($settings_key, NULL, 'FILES');
        
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

			if(!in_array($extention, $ext_array)) $error = __('File extension should be jpg, png or gif.', WPL_TEXTDOMAIN);
			if($error == '')
			{
				$dest = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'system' .DS. $filename;
                
				wpl_file::upload($file['tmp_name'], $dest);
				wpl_settings::save_setting($settings_key, $filename);
			}
		}
        
		$response = array('error'=>$error, 'message'=>$message);
        
		echo json_encode($response);
		exit;
	}
}