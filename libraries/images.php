<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.settings');

/**
 ** Images Library
 ** Developed 07/28/2013
 **/
 
class wpl_images 
{
    /**
     * revised by Francis
     * @param string $source: source file path string
     * @param string $dest  : destination file path string
     * @param int $width    : desired destination file width
     * @param int $height   : desired destination file height
     * @param int $crop     : 0 if crop is disable, 1 if crop is enable, 2 if crop (center) is enable
     * @return string       : destination file path
     */
    public static function resize_image($source, $dest, $width, $height, $crop = 0) 
    {
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
        if($height == '' || $height == 0 || $height == '0')
            $height = $height_temp;

        // If Destination width is Null, Use approximate according to ratio.
        if($width == '' || $width == 0 || $width == '0')
            $width = $width_temp;

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
            if($crop == 1)
                imagecopy($dest_image, $tmp_dest, 0, 0, 0, 0, $width, $height);
            else
                imagecopy($dest_image, $tmp_dest, 0, 0, $src_x, $src_y, $width, $height);
        }
        else
            imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);

        if($extension == 'jpg' || $extension == 'jpeg') 
        {
            ob_start();
            imagejpeg($dest_image, NULL, 90);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'png') 
        {
            ob_start();
            imagepng($dest_image);
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
     * revised by Francis
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
        $watermark = strtolower($watermark);
        
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
                $w_dest = &imagecreatefromjpeg($filename);
                break;
            case 'gif':
                $w_dest = &imagecreatefromgif($filename);
                break;
            case 'png':
                $w_dest = &imagecreatefrompng($filename);
                break;
            default:
                return;
        }
        
        switch($w_extension) 
        {
            case 'jpg':
            case 'jpeg':
                $w_src = &imagecreatefromjpeg($watermark);
                break;
            case 'gif':
                $w_src = &imagecreatefromgif($watermark);
                break;
            case 'png':
                $w_src = &imagecreatefrompng($watermark);
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
                imagecopymerge($w_dest, $w_src, ($w_width - $markwidth) >> 1, ($w_height - $markheight) >> 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'left':
                imagecopymerge($w_dest, $w_src, ($w_width - $markwidth) > 1, ($w_height - $markheight) >> 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'right':
                imagecopymerge($w_dest, $w_src, ($w_width - $markwidth), ($w_height - $markheight) >> 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'top':
                imagecopymerge($w_dest, $w_src, ($w_width - $markwidth) >> 1, ($w_height - $markheight) > 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'bottom':
                imagecopymerge($w_dest, $w_src, ($w_width - $markwidth) >> 1, ($w_height - $markheight), 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'top-left':
                imagecopymerge($w_dest, $w_src, ($w_width - $markwidth) > 1, ($w_height - $markheight) > 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'top-right':
                imagecopymerge($w_dest, $w_src, ($w_width - $markwidth), ($w_height - $markheight) > 1, 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'bottom-left':
                imagecopymerge($w_dest, $w_src, ($w_width - $markwidth) > 1, ($w_height - $markheight), 0, 0, $markwidth, $markheight, $opacity);
                break;

            case 'bottom-right':
                imagecopymerge($w_dest, $w_src, ($w_width - $markwidth), ($w_height - $markheight), 0, 0, $markwidth, $markheight, $opacity);
                break;
        }

        if($extension == 'jpg' || $extension == 'jpeg') 
        {
            ob_start();
            imagejpeg($w_dest, NULL, 90);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'png') 
        {
            ob_start();
            imagepng($w_dest);
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
     * revised by Francis
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
     * written by Francis
     * @param String $source: source file string path
     * @param String $dest  : destination file string path
     * description          : gets gallery settings, resize and watermark source image
     */
    public static function resize_watermark_image($source, $dest, $width = '', $height = '')
    {
        //get gallery category settings
        $settings = wpl_settings::get_settings(2);
        if(trim($width) == '')
            $width = $settings['default_resize_width'];
        if(trim($height) == '')
            $height = $settings['default_resize_height'];
        
        $crop = $settings['image_resize_method'];
        $watermark_options['status'] = $settings['watermark_status'];
        $watermark_options['position'] = $settings['watermark_position'];
        $watermark_options['opacity'] = $settings['watermark_opacity'];
        $watermark_options['url'] = $settings['watermark_url'];
      
        self::resize_image($source, $dest, $width, $height, $crop);

        if($watermark_options['status'] == 1)
            self::add_watermark_image($dest, $dest, $watermark_options); 
    } 
    
     /**
     * written by Francis
     * @param int $width
     * @param int $height
     * @param array $params
     * @param boolean $watermark
     * @param boolean $rewrite
     * description: resize and watermark images specially for gallery activity
     */
    public static function create_gallary_image($width, $height, $params, $watermark = 0, $rewrite = 0, $crop = 0)
    {
        $image_name = wpl_file::stripExt($params['image_name']);
        $image_ext = wpl_file::getExt($params['image_name']);
        $resized_image_name = 'th'.$image_name.'_'.$width.'x'.$height.'.'.$image_ext;
        $image_dest = wpl_items::get_path($params['image_parentid'], $params['image_parentkind']).$resized_image_name;
        $image_url = wpl_items::get_folder($params['image_parentid'], $params['image_parentkind']).$resized_image_name;

		/** check resized files existance and rewrite option **/
		if($rewrite or !wpl_file::exists($image_dest))
		{
			if($watermark)
			   self::resize_watermark_image($params['image_source'], $image_dest, $width, $height);
			
			else
			   self::resize_image($params['image_source'], $image_dest, $width, $height, $crop);
		}
		
		return $image_url;
    }
	
	/**
     * written by Howard
     * @param string $source
	 * @param int $width
     * @param int $height
     * @param array $params
     * @param boolean $watermark
     * @param boolean $rewrite
	 * @param boolean $crop
     * description: resize and watermark images specially
     */
    public static function create_profile_images($source, $width, $height, $params, $watermark = 0, $rewrite = 0, $crop = 1)
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
		   if($watermark)
			   self::resize_watermark_image($source, $image_dest, $width, $height);
		   
		   else
			   self::resize_image($source, $image_dest, $width, $height, $crop);
		}
		
		return $image_url;
    }
	
	/**
     * create a image with given text
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $text text to write in image
     * @param float $size size of text
     * @param string $format output format(gif, jpg, png)
     * @param string $path if null image write tu output otherwise save in given path
     * @param boolean $transparent make transparent image
     * @param integer $spacing spacing of character
     * @param integer $padding padding from top, left, right, bottom
     * @param float $angle angle of text in image
     * @param integer $x position X in image to write text
     * @param integer $y position Y in image to write text
     */
    public static function create_text_image($text, $size = 10, $format = 'gif', $path = null, $transparent = false, $spacing = 0, $padding = 10, $angle = 0, $x = 0, $y = 0)
    {
        $text_color = '0x'.$text;
        $spacing *= 4;
		$font = WPL_ABSPATH.'assets'.DS.'fonts'.DS.'arial.ttf';
        $text_dimensions = self::calculate_text_dimensions($text, $font, $size, $angle, $spacing);
        $image_width = $text_dimensions["width"] + $padding;
        $image_height = $text_dimensions["height"] + $padding;

        $my_img = imagecreatetruecolor($image_width, $image_height);
		
        if($transparent)
        {
            ImageFill($my_img, 0, 0, IMG_COLOR_TRANSPARENT);
            imagesavealpha($my_img, true);
            imagealphablending($my_img, false);
        }
        else
        {
            $white = ImageColorAllocate($my_img, 255, 255, 255);
            ImageFillToBorder($my_img, 0, 0, $white, $white);
        }
		
        self::imagettftextSp($my_img, $size, $angle, $text_dimensions["left"] + ($image_width / 2) - ($text_dimensions["width"] / 2), $text_dimensions["top"] + ($image_height / 2) - ($text_dimensions["height"] / 2), $font, $text, $spacing);

        if($format == 'gif')
            imagegif($my_img, $path);
        elseif($format == 'jpg')
            imagejpeg($my_img, $path);
        elseif($format == 'png')
            imagepng($my_img, $path);

        imagedestroy($my_img);
    }
	
	/** Developed by Kevin **/
    private static function calculate_text_dimensions($text, $font, $size, $angle, $spacing)
    {
        $rect = imagettfbbox($size, $angle, $font, $text);
        $minX = min(array($rect[0], $rect[2], $rect[4], $rect[6]));
        $maxX = max(array($rect[0], $rect[2], $rect[4], $rect[6]));
        $minY = min(array($rect[1], $rect[3], $rect[5], $rect[7]));
        $maxY = max(array($rect[1], $rect[3], $rect[5], $rect[7]));
        $spacing = ($spacing * (strlen($text) + 2));
        return array(
            "left" => abs($minX) - 1,
            "top" => abs($minY) - 1,
            "width" => ($maxX - $minX) + $spacing,
            "height" => $maxY - $minY,
            "box" => $rect
        );
    }
	
	/** Developed by Kevin **/
    private static function imagettftextSp($image, $size, $angle, $x, $y, $font, $text, $spacing = 0)
    {
        $white = imagecolorallocate($image, 0, 0, 0);
        if($spacing == 0)
        {
            imagettftext($image, $size, $angle, $x, $y, $white, $font, $text);
        }
        else
        {
            $temp_x = $x;
            for($i = 0; $i < strlen($text); $i++)
            {
                $bbox = imagettftext($image, $size, $angle, $temp_x, $y, $white, $font, $text[$i]);
                $temp_x += $spacing + ($bbox[2] - $bbox[0]);
            }
        }
    }
}