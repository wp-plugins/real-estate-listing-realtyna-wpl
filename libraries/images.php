<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.settings');

/**
 * Images Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 07/28/2013
 * @package WPL
 */
class wpl_images
{
    /**
     * Resizes an image
     * @author Howard R <howard@realtyna.com>
     * @param string $source
     * @param string $dest
     * @param int $width
     * @param int $height
     * @param int $crop
     * @return string
     */
    public static function resize_image($source, $dest, $width, $height, $crop = 0)
    {
        // Don't execute the function if source file doesn't exists.
        if(!wpl_file::exists($source)) return $source;
        
        // set memory limit
        @ini_set('memory_limit', '-1');
        
        $extension = wpl_file::getExt(strtolower($source));
        switch($extension)
        {
            case 'jpg':
            case 'jpeg':
                $src_image = imagecreatefromjpeg($source);
                break;
            case 'gif':
                $src_image = imagecreatefromgif($source);
                break;
            case 'png':
                $src_image = imagecreatefrompng($source);
                break;
            default:
                return;
        }

        list($src_width, $src_height) = getimagesize($source);

        // Width ratio
        $width_ratio = $src_width / $width;
        $height_temp = round($src_height / $width_ratio);

        // height ratio
        $height_ratio = $src_height / $height;
        $width_temp = round($src_width / $height_ratio);

        // If Destination height is Null, Use approximate according to ratio.
        if($height == '' || $height == 0 || $height == '0') $height = $height_temp;

        // If Destination width is Null, Use approximate according to ratio.
        if($width == '' || $width == 0 || $width == '0') $width = $width_temp;

        $dest_width = $width;
        $dest_height = $height;

        $dest_image = imagecreatetruecolor($dest_width, $dest_height);

        if($extension == 'png') 
        {
            imagealphablending($dest_image, false);
            imagesavealpha($dest_image, true);
            $transparent = imagecolorallocatealpha($dest_image, 255, 255, 255, 127);
            imagefilledrectangle($dest_image, 0, 0, $dest_width, $dest_height, $transparent);
        }

        if($extension == 'gif') 
        {
            // keeping transparency 
            $transparent_index = imagecolortransparent($src_image);
            if($transparent_index >= 0) 
            {
                imagepalettecopy($src_image, $dest_image);
                imagefill($dest_image, 0, 0, $transparent_index);
                imagecolortransparent($dest_image, $transparent_index);
                imagetruecolortopalette($dest_image, true, 256);
            }
        }

        if($crop > 0) 
        {
            $original_ratio = $src_width / $src_height;
            $crop_resize_ratio = $width / $height;
            if($crop_resize_ratio > $original_ratio) 
            { 
                ////check if cropped image is becoming wider
                //checking which side to keep for resizing. it calculates if the new size for resize doesn't get smaller than the one specified in function parameters
                if($height * $original_ratio < $width) 
                {
                    $tmpx = $width;
                    $tmpy = $width / $original_ratio;
                    $src_x = 0;
                    $src_y = ($tmpy - $height) / 2;
                }
                else 
                {
                    $tmpy = $height;
                    $tmpx = $height / $original_ratio;
                    $src_x = ($tmpx - $width) / 2;
                    $src_y = 0;
                }
            }
            // if cropped image is becoming narrower
            else 
            {
                //checking which side to keep for resizing. it calculates if the new size for resize doesn't get smaller than the one specified in function parameters
                if($width / $original_ratio < $height) 
                {
                    $tmpy = $height;
                    $tmpx = $height * $original_ratio;
                    $src_x = ($tmpx - $width) / 2;
                    $src_y = 0;
                }
                else 
                {
                    $tmpx = $width;
                    $tmpy = $width / $original_ratio;
                    $src_x = 0;
                    $src_y = ($tmpy - $height) / 2;
                    ;
                }
            }

            $tmp_dest = imagecreatetruecolor($tmpx, $tmpy);
            if($extension == 'png') 
            {
                imagealphablending($tmp_dest, false);
                imagesavealpha($tmp_dest, true);
                $transparent = imagecolorallocatealpha($tmp_dest, 255, 255, 255, 127);
                imagefilledrectangle($tmp_dest, 0, 0, $dest_width, $dest_height, $transparent);
            }

            if($extension == 'gif') 
            {
                // keeping transparency 
                $transparent_index = imagecolortransparent($src_image);
                if($transparent_index >= 0) 
                {
                    imagepalettecopy($src_image, $tmp_dest);
                    imagefill($tmp_dest, 0, 0, $transparent_index);
                    imagecolortransparent($tmp_dest, $transparent_index);
                    imagetruecolortopalette($tmp_dest, true, 256);
                }
            }

            //resizing image to the calculated temporary sizes
            imagecopyresampled($tmp_dest, $src_image, 0, 0, 0, 0, $tmpx, $tmpy, $src_width, $src_height);

            //crops the temporary resized image to the size given by function parameters
            if($crop == 1) imagecopy($dest_image, $tmp_dest, 0, 0, 0, 0, $width, $height);
            else imagecopy($dest_image, $tmp_dest, 0, 0, $src_x, $src_y, $width, $height);
        }
        else imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
		
        if($extension == 'jpg' || $extension == 'jpeg') 
        {
			$quality = 95;
			if(wpl_global::check_addon('optimizer')) $quality = wpl_addon_optimizer::optimize_image(wpl_addon_optimizer::IMAGE_JPEG, $dest_image);
            
            ob_start();
            imagejpeg($dest_image, NULL, $quality);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'png') 
        {
			$quality = 9;
			if(wpl_global::check_addon('optimizer')) $quality = wpl_addon_optimizer::optimize_image(wpl_addon_optimizer::IMAGE_PNG, $dest_image);
            
            ob_start();
            imagepng($dest_image, NULL, $quality);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        } 
        elseif($extension == 'gif') 
        {
            ob_start();
            imagegif($dest_image);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }

        imagedestroy($src_image);
        return $dest;
    }
    
    /**
     * Add watermark to an image
     * @author Francis R <francis@realtyna.com>
     * @param string $source: source file string path
     * @param string $dest  : destination file string path
     * @param int $watermark: 0 if watermark is disable, 1 if watermark is enable
     * @param array $options: array consist of status, opacity, position and user_logo
     * @return string       : destination file path
     */
    public static function add_watermark_image($source, $dest, $options = '') 
    {
        if($options == '') $options['status'] = 0;
        if($options['status'] != 1) return;
        
        $filename = $source;

        //default path for watermark
        $watermark = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'system' . DS;

        if(trim($options['url']) != '') $watermark .= trim($options['url']);
        if(!wpl_file::exists($watermark)) return;

        $source = strtolower($source);
        $extension = wpl_file::getExt($source);
               
        $w_extension = wpl_file::getExt($watermark);
        
        list($w_width, $w_height, $w_type, $w_attr) = getimagesize($filename);
        list($markwidth, $markheight, $w_type1, $w_attr1) = getimagesize($watermark);

        switch($extension) 
        {
            case 'jpg':
            case 'jpeg':
                $w_dest = imagecreatefromjpeg($filename);
                break;
            case 'gif':
                $w_dest = imagecreatefromgif($filename);
                break;
            case 'png':
                $w_dest = imagecreatefrompng($filename);
                break;
            default:
                return;
        }
        
        switch($w_extension) 
        {
            case 'jpg':
            case 'jpeg':
                $w_src = imagecreatefromjpeg($watermark);
                break;
            case 'gif':
                $w_src = imagecreatefromgif($watermark);
                break;
            case 'png':
                $w_src = imagecreatefrompng($watermark);
                break;
            default:
                return;
        }

        // Copy and merge
        $opacity = $options['opacity'];
        $position = strtolower($options['position']);
        switch($position) 
        {
            case 'center':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) >> 1, ($w_height - $markheight) >> 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'left':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) > 1, ($w_height - $markheight) >> 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'right':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth), ($w_height - $markheight) >> 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'top':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) >> 1, ($w_height - $markheight) > 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'bottom':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) >> 1, ($w_height - $markheight), 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'top-left':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) > 1, ($w_height - $markheight) > 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'top-right':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth), ($w_height - $markheight) > 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'bottom-left':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) > 1, ($w_height - $markheight), 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'bottom-right':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth), ($w_height - $markheight), 0, 0, $markwidth, $markheight, $opacity);
                break;
        }

        if($extension == 'jpg' || $extension == 'jpeg') 
        {
			$quality = 95;
			if(wpl_global::check_addon('optimizer') && wpl_global::get_client() === 0) $quality = wpl_addon_optimizer::optimize_image(wpl_addon_optimizer::IMAGE_JPEG, $w_dest);
            
            ob_start();
            imagejpeg($w_dest, NULL, $quality);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'png') 
        {
			$quality = 9;
			if(wpl_global::check_addon('optimizer') && wpl_global::get_client() === 0) $quality = wpl_addon_optimizer::optimize_image(wpl_addon_optimizer::IMAGE_PNG, $w_dest);
            
            ob_start();
            imagepng($w_dest, NULL, $quality);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'gif') 
        {
            ob_start();
            imagegif($w_dest);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }

        imagedestroy($w_src);
        imagedestroy($w_dest);
        
        // Return Destination
        return $source;
    }

    
    /**
     * Same as imagecopymerge but it handles alpha channel and PNG images well!
     * @author Peter P <peter@realtyna.com>
     * @param type $w_dest  Destination image link resource.
     * @param type $w_src   Source image link resource.
     * @param type $dst_x   x-coordinate of destination point.
     * @param type $dst_y   y-coordinate of destination point.
     * @param type $src_x   x-coordinate of source point.   
     * @param type $src_y   y-coordinate of destination point.
     * @param type $src_w   Source width.
     * @param type $src_h   Source height.
     * @param type $opacity Transparency 
     */
    public static function imagecopymerge_alpha($w_dest, $w_src, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity)
    {
        // creating a cut resource
        $cut = imagecreatetruecolor($src_w, $src_h);
        // copying that section of the background to the cut
        imagecopy($cut, $w_dest, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
        // placing the watermark now
        imagecopy($cut, $w_src, 0, 0, $src_x, $src_y, $src_w, $src_h);
        imagecopymerge($w_dest, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
    }
    
    /**
     * Converts text to an image
     * @author Howard <howard@realtyna.com>
     * @param string $text : text string
     * @param string $color: color hex code string
     * @param string $dest : destination file path string
     */
    public static function text_to_image($text, $color, $dest)
    {
        $len = strlen($text);
        $im = imagecreate($len*8, 20);

        $color = str_split($color, 2);
        $color[0] = hexdec($color[0]);
        $color[1] = hexdec($color[1]);
        $color[2] = hexdec($color[2]);

        // Make the background transparent
        $black = imagecolorallocate($im, 0, 0, 0);
        imagecolortransparent($im, $black);

        $textcolor = imagecolorallocate($im, $color[0],$color[1], $color[2]);

        // write the string at the top left
        imagestring($im, 4, 1, 0, $text, $textcolor);
        
        // output the image
        imagepng($im, $dest);
    }
    
    /**
     * Resize and add watermark to an image
     * @author Francis <francis@realtyna.com>
     * @param String $source: source file string path
     * @param String $dest  : destination file string path
     * description          : gets gallery settings, resize and watermark source image
     */
    public static function resize_watermark_image($source, $dest, $width = '', $height = '')
    {
        //get gallery category settings
        $settings = wpl_settings::get_settings(2);
        if(trim($width) == '') $width = $settings['default_resize_width'];
        if(trim($height) == '') $height = $settings['default_resize_height'];
        
        $crop = $settings['image_resize_method'];
        
        $watermark_options = array();
        $watermark_options['status'] = $settings['watermark_status'];
        $watermark_options['position'] = $settings['watermark_position'];
        $watermark_options['opacity'] = $settings['watermark_opacity'];
        $watermark_options['url'] = $settings['watermark_url'];
      
        self::resize_image($source, $dest, $width, $height, $crop);

        if($watermark_options['status'] == 1) self::add_watermark_image($dest, $dest, $watermark_options);
    }
    
    /**
     * Use wpl_images::create_gallery_image function instead
     * @deprecated since version 2.7.0
     */
    public static function create_gallary_image($width, $height, $params, $watermark = 0, $rewrite = 0, $crop = '')
    {
        return create_gallery_image($width, $height, $params, $watermark, $rewrite, $crop);
    }
    
    /**
     * Creates gallery image
     * @author Francis <francis@realtyna.com>
     * @param int $width
     * @param int $height
     * @param array $params
     * @param boolean $watermark
     * @param boolean $rewrite
     * @param int $crop
     * description: resize and watermark images specially for gallery activity
     */
    public static function create_gallery_image($width, $height, $params, $watermark = 0, $rewrite = 0, $crop = '')
    {
        // Get blog ID of property
        $blog_id = wpl_property::get_blog_id($params['image_parentid']);
        
        $image_name = wpl_file::stripExt($params['image_name']);
        $image_ext = wpl_file::getExt($params['image_name']);
        $resized_image_name = 'th'.$image_name.'_'.$width.'x'.$height.'.'.$image_ext;
        $image_dest = wpl_items::get_path($params['image_parentid'], $params['image_parentkind'], $blog_id).$resized_image_name;
        $image_url = wpl_items::get_folder($params['image_parentid'], $params['image_parentkind'], $blog_id).$resized_image_name;

		/** check resized files existance and rewrite option **/
		if($rewrite or !wpl_file::exists($image_dest))
		{
			if($watermark) self::resize_watermark_image($params['image_source'], $image_dest, $width, $height);
			else
			{
				/** if crop was not set, read from wpl settings **/
				if(!trim($crop))
				{
					$settings = wpl_settings::get_settings(2);
					$crop = $settings['image_resize_method'];
				}
                
			    self::resize_image($params['image_source'], $image_dest, $width, $height, $crop);
			}
		}
		
		return $image_url;
    }
	
	/**
     * Creates profile image
     * @author Howard <howard@realtyna.com>
     * @param string $source
	 * @param int $width
     * @param int $height
     * @param array $params
     * @param int $watermark
     * @param int $rewrite
	 * @param int $crop
     * description: resize and watermark images specially
     */
    public static function create_profile_images($source, $width, $height, $params, $watermark = 0, $rewrite = 0, $crop = '')
    {
		/** first validation **/
		if(!trim($source)) return NULL;
		
        $image_name = wpl_file::stripExt($params['image_name']);
        $image_ext = wpl_file::getExt($params['image_name']);
        $resized_image_name = 'th'.$image_name.'_'.$width.'x'.$height.'.'.$image_ext;
        $image_dest = wpl_items::get_path($params['image_parentid'], 2).$resized_image_name;
        $image_url = wpl_items::get_folder($params['image_parentid'], 2).$resized_image_name;

		/** check resized files existance and rewrite option **/
		if($rewrite or !wpl_file::exists($image_dest))
		{
		   if($watermark) self::resize_watermark_image($source, $image_dest, $width, $height);
		   else self::resize_image($source, $image_dest, $width, $height, $crop);
		}
		
		return $image_url;
    }
    
    public static function watermark_original_image($params)
    {
        // Get blog ID of property
        $blog_id = wpl_property::get_blog_id($params['image_parentid']);
        
        $image_name = wpl_file::stripExt($params['image_name']);
        $image_ext = wpl_file::getExt($params['image_name']);
        $watermarked_image_name = 'wm'.$image_name.'.'.$image_ext;
        $image_dest = wpl_items::get_path($params['image_parentid'], $params['image_parentkind'], $blog_id).$watermarked_image_name;
        $image_url = wpl_items::get_folder($params['image_parentid'], $params['image_parentkind'], $blog_id).$watermarked_image_name;

		/** check resized files existance**/
		if(!wpl_file::exists($image_dest))
		{
            $settings = wpl_settings::get_settings(2);
            
            $watermark_options = array();
            $watermark_options['status'] = $settings['watermark_status'];
            $watermark_options['position'] = $settings['watermark_position'];
            $watermark_options['opacity'] = $settings['watermark_opacity'];
            $watermark_options['url'] = $settings['watermark_url'];

            if($watermark_options['status'] == 1) self::add_watermark_image($params['image_source'], $image_dest, $watermark_options);
		}
		
		return $image_url;
    }
}

/**
 * Color Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL2.0.0
 * @date 11/19/2014
 * @package WPL
 */
class wpl_color
{
    /**
     * Convert a hex color to lighter or darker version based on radiance
     * @author Howard R <howard@realtyna.com>
     * @param string $hex
     * @param int $radiance
     * @param boolean $trim
     * @return string
     */
    public function convert($hex, $radiance, $trim = false)
    {
        $RGB = $this->hex2rgb($hex);
        $result = $this->rgb2hex($this->radiance($RGB, $radiance));
        
        if($trim) $result = trim($result, '# ');
        return $result;
    }
    
    /**
     * Convert hex color to RGB color
     * @author Howard R <howard@realtyna.com>
     * @param string $hex
     * @return type
     */
    public function hex2rgb($hex)
    {
        if($hex[0] == '#')
            $hex = substr($hex, 1);

        if(strlen($hex) == 3)
        {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec($hex[0] . $hex[1]);
        $g = hexdec($hex[2] . $hex[3]);
        $b = hexdec($hex[4] . $hex[5]);

        return $b + ($g << 0x8) + ($r << 0x10);
    }

    /**
     * Converts RGB to hex color
     * @author Howard R <howard@realtyna.com>
     * @param type $RGB
     * @return string
     */
    public function rgb2hex($RGB)
    {
        $r = 0xFF & ($RGB >> 0x10);
        $g = 0xFF & ($RGB >> 0x8);
        $b = 0xFF & $RGB;

        $r = dechex($r);
        $g = dechex($g);
        $b = dechex($b);

        return "#" . str_pad($r, 2, "0", STR_PAD_LEFT) . str_pad($g, 2, "0", STR_PAD_LEFT) . str_pad($b, 2, "0", STR_PAD_LEFT);
    }
    
    /**
     * Creates lighter or darker version of an RGB color
     * @author Howard R <howard@realtyna.com>
     * @param type $RGB
     * @param int $radiance
     * @return type
     */
    public function radiance($RGB, $radiance)
    {
        $HSL = self::rgb2hsl($RGB);
        $NewHSL = (int)(((float) $radiance / 100) * 255) + (0xFFFF00 & $HSL);
        return self::hsl2rgb($NewHSL);
    }
    
    /**
     * Converts RGB to HSL
     * @author Howard R <howard@realtyna.com>
     * @param type $RGB
     * @return type
     */
    public function rgb2hsl($RGB)
    {
        $r = 0xFF & ($RGB >> 0x10);
        $g = 0xFF & ($RGB >> 0x8);
        $b = 0xFF & $RGB;

        $r = ((float) $r) / 255.0;
        $g = ((float) $g) / 255.0;
        $b = ((float) $b) / 255.0;

        $maxC = max($r, $g, $b);
        $minC = min($r, $g, $b);

        $l = ($maxC + $minC) / 2.0;

        if($maxC == $minC)
        {
            $s = 0;
            $h = 0;
        }
        else
        {
            if($l < .5) $s = ($maxC - $minC) / ($maxC + $minC);
            else $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
            
            if($r == $maxC) $h = ($g - $b) / ($maxC - $minC);
            if($g == $maxC) $h = 2.0 + ($b - $r) / ($maxC - $minC);
            if($b == $maxC) $h = 4.0 + ($r - $g) / ($maxC - $minC);

            $h = $h / 6.0; 
        }

        $h = (int) round(255.0 * $h);
        $s = (int) round(255.0 * $s);
        $l = (int) round(255.0 * $l);

        $HSL = $l + ($s << 0x8) + ($h << 0x10);
        return $HSL;
    }

    /**
     * Converts HSL to RGB
     * @author Howard R <howard@realtyna.com>
     * @param type $HSL
     * @return type
     */
    public function hsl2rgb($HSL)
    {
        $h = 0xFF & ($HSL >> 0x10);
        $s = 0xFF & ($HSL >> 0x8);
        $l = 0xFF & $HSL;

        $h = ((float) $h) / 255.0;
        $s = ((float) $s) / 255.0;
        $l = ((float) $l) / 255.0;

        if($s == 0)
        {
            $r = $l;
            $g = $l;
            $b = $l;
        }
        else
        {
            if($l < .5)
            {
                $t2 = $l * (1.0 + $s);
            }
            else
            {
                $t2 = ($l + $s) - ($l * $s);
            }
            
            $t1 = 2.0 * $l - $t2;

            $rt3 = $h + 1.0/3.0;
            $gt3 = $h;
            $bt3 = $h - 1.0/3.0;

            if($rt3 < 0) $rt3 += 1.0;
            if($rt3 > 1) $rt3 -= 1.0;
            if($gt3 < 0) $gt3 += 1.0;
            if($gt3 > 1) $gt3 -= 1.0;
            if($bt3 < 0) $bt3 += 1.0;
            if($bt3 > 1) $bt3 -= 1.0;

            if(6.0 * $rt3 < 1) $r = $t1 + ($t2 - $t1) * 6.0 * $rt3;
            elseif(2.0 * $rt3 < 1) $r = $t2;
            elseif(3.0 * $rt3 < 2) $r = $t1 + ($t2 - $t1) * ((2.0/3.0) - $rt3) * 6.0;
            else $r = $t1;

            if(6.0 * $gt3 < 1) $g = $t1 + ($t2 - $t1) * 6.0 * $gt3;
            elseif(2.0 * $gt3 < 1) $g = $t2;
            elseif(3.0 * $gt3 < 2) $g = $t1 + ($t2 - $t1) * ((2.0/3.0) - $gt3) * 6.0;
            else $g = $t1;

            if(6.0 * $bt3 < 1) $b = $t1 + ($t2 - $t1) * 6.0 * $bt3;
            elseif(2.0 * $bt3 < 1) $b = $t2;
            elseif(3.0 * $bt3 < 2) $b = $t1 + ($t2 - $t1) * ((2.0/3.0) - $bt3) * 6.0;
            else $b = $t1;
        }

        $r = (int) round(255.0 * $r);
        $g = (int) round(255.0 * $g);
        $b = (int) round(255.0 * $b);

        $RGB = $b + ($g << 0x8) + ($r << 0x10);
        return $RGB;
    }
}