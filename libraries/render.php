<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.units');

/**
** Render Library
** Developed 08/19/2013
**/

class wpl_render
{
	/**
		@inputs {date}, [year], [month] and [day]
		@param string $date
		@return rendered date based on global settings
		@author Howard
	**/
	public static function render_date($date, $year = '', $month = '', $day = '')
	{
		if(trim($date) == '0000-00-00' or trim($date) == '0000-00-00 00:00:00') return '';
		$date_arr = explode('-', $date);
		
		if($year == '') $year = $date_arr[0];
		if($month == '') $month = $date_arr[1];
		if($day == '') $day = $date_arr[2];
		
		$date = $year.'-'.$month.'-'.$day;
		$date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
		$date_format = $date_format_arr[0];
		
		return date($date_format, strtotime($date));
	}
	
	/**
		@inputs {longitude}
		@param string $longitude
		@return rendered longitude
		@author Howard
	**/
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
		
		return $sign . $degree .'° '. $minutes ."' ". round($seconds, 1) ."'' ";
	}
	
	/**
		@inputs {latitude}
		@param string $latitude
		@return rendered latitude
		@author Howard
	**/
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
		
		return $sign . $degree .'° '. $minutes ."' ". round($seconds, 1) ."'' ";
	}
	
	/**
		@inputs {size}
		@param integer or string $size
		@return rendered file size
		@author Howard
	**/
	public static function render_file_size($size)
	{
		$d = 'B';
		if($size > 1024) { $size = $size/1024; $d = 'KB'; }
		if($size > 1024) { $size = $size/1024; $d = 'MB'; }
		if($size > 1024) { $size = $size/1024; $d = 'GB'; }
		
		return round($size, 1).$d;
	}
	
	/**
		@inputs {price_si} and [unit_id]
		@param integer or string $price_si
		@param integer or string $unit_id
		@return converted price
		@author Howard
	**/
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
		@inputs {price} and [unit_id]
		@param integer or string $price
		@param integer or string $unit_id
		@return rendered price
		@author Howard
	**/
	public static function render_price($price, $unit_id = '')
	{
		/** in case of empty unit just do it with default currency **/
		if(trim($unit_id) == '')
		{
			$all_units = wpl_units::get_units(4, 1);
			$unit_id = $all_units[0]['id'];
		}
		
		/** get currency **/
		$currency = wpl_units::get_unit($unit_id);
		$symbol = $currency['name'];
		$decimal = 2;
		$return = '';
		
		$d_seperator = trim($currency['d_seperator']) != '' ? $currency['d_seperator'] : NULL;
		$seperator = trim($currency['seperator']) != '' ? $currency['seperator'] : NULL;
		
		/** set decimal **/
		if(!$d_seperator) $decimal = 0;
		
		/** set default value **/
		if(trim($price) == '') $price = 0;
		
		$price = intval($price);
		$price = number_format($price, $decimal, $d_seperator, $seperator);
		
		if($currency['after_before'] == 0) $return = $symbol.$price;
		else $return = $price.$symbol;
		
		return $return;
	}
	
	/**
		@inputs rendered date
		@param string $date
		@return derendered date based on global settings
		@author Albert
	**/
	public static function derender_date($date)
	{
		$date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
		$date_format = $date_format_arr[0];

		if(stristr($date_format, '-') != '')
			$delimiter = '-';
		else
			$delimiter = '/';
		
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
		
		$my_date = $standard_date['y'].'-'.$standard_date['m'].'-'.$standard_date['d'];
		$time = '';
		
		if(stristr(trim($date), ' ') != '')
		{
			$tmp = explode(' ', $date);
			$time = $tmp[1];
		}
		
		$my_date .= $time;
		return $my_date;
	}
}