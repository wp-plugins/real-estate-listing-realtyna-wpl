<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_wpl_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.wpl.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'install_package') $this->install_package();
		elseif($function == 'check_addon_update') $this->check_addon_update();
		elseif($function == 'update_package') $this->update_package();
		elseif($function == 'save_realtyna_credentials') $this->save_realtyna_credentials();
	}
	
	private function install_package()
	{
		/** upload file into tmp directory **/
		$file = wpl_request::getVar('wpl_addon_file', '', 'FILES');
		$tmp_directory = wpl_global::init_tmp_folder();
		$dest = $tmp_directory.'package.zip';
		
		$response = wpl_global::upload($file, $dest, array('zip'), 20971520); #20MB
		if(trim($response['error']) != '') $this->response($response);
		
		$zip_file = $dest;
		wpl_global::zip_extract($zip_file, $tmp_directory);
		
		$script_file = $tmp_directory.'installer.php';
		if(!wpl_file::exists($script_file)) $this->response(array('error'=>__("Installer file doesn't exist!", WPL_TEXTDOMAIN), 'message'=>''));
		
		/** including installer and run the install method **/
		include $script_file;
		if(!class_exists('wpl_installer')) $this->response(array('error'=>__("Installer class doesn't exist!", WPL_TEXTDOMAIN), 'message'=>''));
		
		/** run install script **/
		$wpl_installer = new wpl_installer();
		$wpl_installer->path = $tmp_directory;
		
		if(!$wpl_installer->run()) $this->response(array('error'=>$wpl_installer->error, 'message'=>''));
		
        /** Trigger Event **/
        wpl_global::event_handler('package_installed', array('package_id'=>(isset($wpl_installer->addon_id) ? $wpl_installer->addon_id : 0)));
        
		$message = $wpl_installer->message ? $wpl_installer->message : __('Package installed.', WPL_TEXTDOMAIN);
		$this->response(array('error'=>'', 'message'=>$message));
	}
	
	private function check_addon_update()
	{
        /** Client should update WPL Franchise first **/
        if(wpl_global::is_multisite())
        {
            $fs_update = wpl_global::check_addon_update(4);
            if(isset($fs_update['success']) and $fs_update['success'] == 1)
            {
                wpl_db::q("UPDATE `#__wpl_addons` SET `message`='' WHERE `id`!='4'", 'UPDATE');
                $this->response(array('success'=>1, 'message'=>__("Please update franchise addon first.", WPL_TEXTDOMAIN)));
            }
        }
        
		$addon_id = wpl_request::getVar('addon_id');
		$response = wpl_global::check_addon_update($addon_id);
		
		$this->response($response);
	}
	
	private function update_package()
	{
		$sid = wpl_request::getVar('sid');
		
		$tmp_directory = wpl_global::init_tmp_folder();
		$dest = $tmp_directory.'package.zip';
		
		$zip_file = wpl_global::get_web_page('http://billing.realtyna.com/index.php?option=com_rls&view=downloadables&task=download&sid='.$sid.'&randomkey='.rand(1, 100));
		
		if(!$zip_file) $this->response(array('success'=>'0', 'error'=>__('Error: #U202, Could not download the update package!', WPL_TEXTDOMAIN), 'message'=>''));
		if(!class_exists('ZipArchive')) $this->response(array('success'=>'0', 'error'=>__('Error: #U205, Your PHP has no ZipArchive support enabled!', WPL_TEXTDOMAIN), 'message'=>''));
        if(!wpl_file::write($dest, $zip_file)) $this->response(array('success'=>'0', 'error'=>__('Error: #U203, Could not create the update file!', WPL_TEXTDOMAIN), 'message'=>''));
		if(!wpl_global::zip_extract($dest, $tmp_directory)) $this->response(array('success'=>'0', 'error'=>__('Error: #U204, Could not extract the update file!', WPL_TEXTDOMAIN), 'message'=>''));
		
		$script_file = $tmp_directory.'installer.php';
		if(!wpl_file::exists($script_file)) $this->response(array('error'=>__("Installer file doesn't exist!", WPL_TEXTDOMAIN), 'message'=>''));
		
        if(!is_writable(WPL_ABSPATH.'WPL.php')) $this->response(array('error'=>__("PHP doesn't have write access to the files and directories!", WPL_TEXTDOMAIN), 'message'=>''));
        
		/** including installer and run the install method **/
		include $script_file;
		if(!class_exists('wpl_installer')) $this->response(array('error'=>__("Installer class doesn't exist!", WPL_TEXTDOMAIN), 'message'=>''));
		
		/** run install script **/
		$wpl_installer = new wpl_installer();
		$wpl_installer->path = $tmp_directory;
		
		if(!$wpl_installer->run()) $this->response(array('error'=>$wpl_installer->error, 'message'=>''));
		
        /** Trigger Event **/
        wpl_global::event_handler('package_updated', array('package_id'=>(isset($wpl_installer->addon_id) ? $wpl_installer->addon_id : 0)));
        
        /** Check All Addon update **/
        wpl_global::check_all_update();
        
		$message = $wpl_installer->message ? $wpl_installer->message : __('Addon Updated.', WPL_TEXTDOMAIN);
		$this->response(array('error'=>'', 'message'=>$message));
	}
	
	private function save_realtyna_credentials()
	{
		/** import settings library **/
		_wpl_import('libraries.settings');
		
		$username = wpl_request::getVar('username');
		$password = wpl_request::getVar('password');
		
		wpl_settings::save_setting('realtyna_username', $username, 1);
		wpl_settings::save_setting('realtyna_password', $password, 1);
        
		$response = wpl_global::check_realtyna_credentials();
		$this->response($response);
	}
	
	private function response($response)
	{
		echo json_encode($response);
		exit;
	}
}