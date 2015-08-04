<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Render Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 08/19/2013
 * @package WPL
 */
class wpl_render
{
    /**
     * Renders date based on global date format
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $date
     * @param int $year
     * @param int $month
     * @param int $day
     * @return string
     */
	public static function render_date($date, $year = '', $month = '', $day = '')
	{
		if(trim($date) == '0000-00-00' or trim($date) == '0000-00-00 00:00:00') return '';
		$date_arr = explode('-', $date);
		
		if($year == '' and isset($date_arr[0])) $year = $date_arr[0];
		if($month == '' and isset($date_arr[1])) $month = $date_arr[1];
		if($day == '' and isset($date_arr[2])) $day = $date_arr[2];
		
		$date = $year.'-'.$month.'-'.$day;
		$date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
		$date_format = $date_format_arr[0];
		
		return date($date_format, strtotime($date));
	}
	
    /**
     * Render date time
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $datetime
     * @param mixed $year
     * @param mixed $month
     * @param mixed $day
     * @return string
     */
    public static function render_datetime($datetime, $year = '', $month = '', $day = '')
	{
		if(trim($datetime) == '0000-00-00' or trim($datetime) == '0000-00-00 00:00:00') return '';
		$tmp = explode(' ', $datetime);
        
		$date = isset($tmp[0]) ? $tmp[0] : '';
		$time = isset($tmp[1]) ? $tmp[1] : '';
        
		$output = wpl_render::render_date($date).' '.$time;
		return $output;
	}
    
    /**
     * Renders longitude
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $longitude
     * @return string
     */
	public static function render_longitude($longitude)
	{
		$degree = floor($longitude);
		$rest = ($longitude - $degree)*60;
		$minutes = floor($rest);
		$rest = $rest - $minutes;
		$seconds = $rest*60;
		
		if($degree < 0)
		{
			$degree = $degree * -1;
			$sign = 'W';
		}
		else $sign = 'E';
		
		return $sign . $degree .'&deg; '. $minutes ."' ". round($seconds, 1) ."'' ";
	}
	
    /**
     * Renders latitude
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $latitude
     * @return string
     */
	public static function render_latitude($latitude)
	{
		$degree = floor($latitude);
		$rest = ($latitude - $degree)*60;
		$minutes = floor($rest);
		$rest = $rest - $minutes;
		$seconds = $rest*60;
		
		if($degree < 0)
		{
			$degree = $degree * -1;
			$sign = 'S';
		}
		else $sign = 'N';
		
		return $sign . $degree .'&deg; '. $minutes ."' ". round($seconds, 1) ."'' ";
	}
	
    /**
     * Render file size based on Byte
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $size
     * @return string
     */
	public static function render_file_size($size)
	{
		$d = 'B';
		if($size > 1024) { $size = $size/1024; $d = 'KB'; }
		if($size > 1024) { $size = $size/1024; $d = 'MB'; }
		if($size > 1024) { $size = $size/1024; $d = 'GB'; }
		
		return round($size, 1).$d;
	}
	
    /**
     * Converts SI price (USD) to another currency
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $price_si
     * @param int $unit_id
     * @return int
     */
	public static function convert_price($price_si, $unit_id)
	{
		/** in case of empty unit just do it with default currency **/
		if(!trim($unit_id))
		{
			$all_units = wpl_units::get_units(4, 1);
			$unit_id = $all_units[0]['id'];
		}
		
		/** get unit data **/
		$unit_data = wpl_units::get_unit($unit_id);
		
		if(!$unit_data) return 0;
		if(!$unit_data->tosi) return 0;
		
		return ($price_si/$unit_data['tosi']);
	}
	
    /**
     * Renders price based on currency unit id
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $price
     * @param int $unit_id
     * @param string $symbol
     * @return string
     */
	public static function render_price($price, $unit_id = '', $symbol = '')
	{
		/** in case of empty unit just do it with default currency **/
		if(trim($unit_id) == '')
		{
			$all_units = wpl_units::get_units(4, 1);
			$unit_id = $all_units[0]['id'];
		}
		
		/** get currency **/
		$currency = wpl_units::get_unit($unit_id);
        
		if(!trim($symbol)) $symbol = $currency['name'];
		$decimal = 2;
		$return = '';
		
		$d_seperator = trim($currency['d_seperator']) != '' ? $currency['d_seperator'] : '';
		$seperator = trim($currency['seperator']) != '' ? $currency['seperator'] : '';
		
		/** set decimal **/
		if(!$d_seperator) $decimal = 0;
		
		/** set default value **/
		if(trim($price) == '') $price = 0;
        
        /** Convert price to float **/
        if(strpos($price, '.') !== false)
        {
            if(!$d_seperator) $d_seperator = '.';
            $price = (float) $price;
            $decimal = 2;
        }
        
        /** Remove decimals if the price is not float **/
		if(!is_float($price))
        {
            $price = intval($price);
            $decimal = 0;
        }
        
		$price = number_format($price, $decimal, $d_seperator, $seperator);
		
		if($currency['after_before'] == 0) $return = $symbol.$price;
		else $return = $price.$symbol;
		
		return $return;
	}
	
    /**
     * Derendere date based on global settings
     * @author Albert <albert@realtyna.com>
     * @static
     * @param string $date
     * @return type
     */
	public static function derender_date($date)
	{
		$date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
		$date_format = $date_format_arr[0];

		if(stristr($date_format, '-') != '') $delimiter = '-';
		else $delimiter = '/';
		
		$date_format_parts = explode($delimiter, $date_format);
		$date_parts = explode($delimiter, $date);
		$standard_date = array();
		
		for($i=0; $i<3; $i++)
		{
			switch(strtolower($date_format_parts[$i]))
			{
				case 'y':
					$standard_date['y'] = $date_parts[$i];
				break;
				
				case 'm':
					$standard_date['m'] = $date_parts[$i];
				break;
				
				case 'd':
					$standard_date['d'] = $date_parts[$i];
				break;
			}
		}
		
		$dedate = $standard_date['y'].'-'.$standard_date['m'].'-'.$standard_date['d'];
		$time = '';
		
		if(stristr(trim($date), ' ') != '')
		{
			$tmp = explode(' ', $date);
			$time = $tmp[1];
		}
		
		$dedate .= $time;
		return $dedate;
	}
    
    /**
     * Renders Parent Field
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param string $parent_column
     * @return string
     */
    public static function render_parent($property_id, $parent_column = 'parent', $ids = false)
    {
        $parents = array();
        
        if($ids) $parents[] = $property_id;
        else $parents[] = wpl_property::update_property_title(NULL, $property_id);
        
        $parent_id = wpl_property::get_parent($property_id);
        if($parent_id) $parents[] = self::render_parent($parent_id, $parent_column, $ids);
        
        $glue = $ids ? ',' : ' / ';
        return implode($glue, $parents);
    }
    
    /**
     * Renders numerci values
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $number
     * @return string
     */
    public static function render_number($number)
    {
        return number_format($number, 0, '.', ',');
    }
}