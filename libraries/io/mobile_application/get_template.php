<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_io_cmd_get_template extends wpl_io_cmd_base
{
    private $os;
    private $built;

    /**
     * This method is the main method of each commands
     * @return mixed
     */
    public function build()
    {
        $this->os = $this->params['os'];

        if(trim($this->params['os']) == "android")
        {
            if($this->params['function'] == "get_icons")
            {
                $this->get_icons($this->params['screen_size']);
            }
            else
            {
                $this->built['template']['colors'] = $this->get_colors();
            }
        }
        
        return $this->built;
    }

    /**
     * Sending file to header in order to download
     * @param $path
     */
    protected function start_download($path)
    {
        if(file_exists($path))
        {
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header("Content-length: ".filesize($path));
            header("Pragma: no-cache");
            header("Expires: 0");
            
            ob_clean();
            flush();
            readfile($path);
            
            exit;
        }
    }

    /**
     * Zipping icons
     * @param $destination
     */
    public function zip_icons($destination)
    {
        $tmp_path = get_temp_dir();
        
        if($this->params['os'] == "android")
        {
            $folder_path = $tmp_path . DS . 'addon_mobile_application' . DS . 'android';
            
            wpl_folder::create($folder_path);
            wpl_folder::create($folder_path . DS . 'drawable');
            wpl_folder::create($folder_path . DS . 'drawable-' . $this->params['screen_size']);
            wpl_folder::copy(WPL_UP_ABSPATH . 'addon_mobile_application' . DS . $this->params['os'] . DS . 'drawable', $folder_path . DS . 'drawable', '', true);
            wpl_folder::copy(WPL_UP_ABSPATH . 'addon_mobile_application' . DS . $this->params['os'] . DS . 'drawable-' . $this->params['screen_size'], $folder_path . DS . 'drawable-' . $this->params['screen_size'], '', true);
            
            $this->zip_directory($folder_path, $destination);
            wpl_folder::delete($folder_path);
        }
        else
        {
        }
    }

    /**
     *
     * Zipping the directory
     * @param $source
     * @param $destination
     * @return bool
     */
    private function zip_directory($source, $destination)
    {
        if(!extension_loaded('zip') || !file_exists($source))
        {
            return false;
        }
        
        $zip = new ZipArchive();
        if(!$zip->open($destination, ZIPARCHIVE::CREATE))
        {
            return false;
        }
        
        $source = str_replace('\\', '/', realpath($source));
        if(is_dir($source) === true)
        {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach($files as $file)
            {
                $file = str_replace('\\', '/', $file);
                if(in_array(substr($file, strrpos($file, '/')+1), array('.', '..')))
                {
                    continue;
                }
                
                $file = realpath($file);
                if(is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }
                else if(is_file($file) === true)
                {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        }
        else if(is_file($source) === true)
        {
            $zip->addFromString(basename($source), file_get_contents($source));
        }
        
        return $zip->close();
    }

    /**
     * Getting icons
     * @param $density
     */
    protected function get_icons($density)
    {
        if($this->os == 'android')
        {
            $zip_path = WPL_UP_ABSPATH . 'addon_mobile_application' . DS . $this->os . DS . 'zip' . DS . $density . '.zip';
            if(file_exists($zip_path))
            {
                $this->start_download($zip_path);
            }
            else
            {
                $this->zip_icons($zip_path);
                if(file_exists($zip_path))
                {
                    $this->start_download($zip_path);
                }
                
                exit;
            }
        }
    }

    /**
     * Get colors of template
     * @return mixed
     */
    private function get_colors()
    {
        return wpl_addon_mobile_application::get_template_colors();
    }

    /**
     * Data validation
     * @return boolean
     */
    public function validate()
    {
        if(isset($this->params['function']) == false || trim($this->params['function']) == '')
        {
            return false;
        }

        if(isset($this->params['os']) == false || trim($this->params['os']) == '')
        {
            return false;
        }
        else
        {
            if($this->params['os'] != "android" && $this->params['os'] != "ios" )
            {
                return false;
            }
        }

        if($this->params['function'] != "get_colors" && (isset($this->params['screen_size']) == false || trim($this->params['screen_size']) == ''))
        {
            return false;
        }
        else
        {
            if($this->params['function'] == "get_icons")
            {
                $android_screen_sizes = array('hdpi', 'ldpi', 'mdpi', 'xhdpi', 'xxhdpi', 'xxxhdpi');
                $ios_screen_sizes = array();
                $allowed_list = array_merge($android_screen_sizes, $ios_screen_sizes);
                if(in_array($this->params['screen_size'], $allowed_list) == false)
                {
                    return false;
                }
            }
        }

        return true;
    }
}