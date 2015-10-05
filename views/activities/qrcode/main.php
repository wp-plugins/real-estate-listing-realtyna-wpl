<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.qrcode');

/** activity class **/
class wpl_activity_main_qrcode extends wpl_activity
{
    public $tpl_path = 'views.activities.qrcode.tmpl';
	
	public function start($layout, $params)
	{
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
	
	public function get_qr_image($qrfile_prefix = 'qr_', $size = 4, $outer_margin = 2)
	{
		$url = isset($this->url) ? $this->url : wpl_global::get_full_url();
		$file_name = $qrfile_prefix.md5($url).'.png';
		
		$file_path = wpl_global::get_upload_base_path(). 'qrcode' .DS. $file_name;
		
		if(!wpl_file::exists($file_path))
		{
			if(!wpl_file::exists(dirname($file_path))) wpl_folder::create(dirname($file_path));
            
			$QRcode = new QRcode();
            $QRcode->png($url, $file_path, 'L', $size, $outer_margin);
		}
		
		return wpl_global::get_upload_base_url().'qrcode/'.$file_name;
	}
}