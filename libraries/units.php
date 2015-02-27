<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * WPL Units library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 05/01/2013
 * @package WPL
 */
class wpl_units
{
    /**
     * Returns unit types [AREA,VALUME,....]
     * @author Howard <howard@realtyna.com>
     * @static
     * @return array
     */
	public static function get_unit_types()
	{
		$query = "SELECT * FROM `#__wpl_unit_types`  ORDER BY `id` ASC";
		return wpl_db::select($query, 'loadAssocList');
	}
	
    /**
     * Get units
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $type
     * @param int $enabled
     * @param string $condition
     * @return array
     */
	public static function get_units($type = 4, $enabled = 1, $condition = '')
	{
		if(trim($condition) == '')
		{
			$condition = '';
			
			if(trim($type) != '') $condition .= " AND `type`='$type'";
			if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
		}
		
		$query = "SELECT * FROM `#__wpl_units` WHERE 1 ".$condition." ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadAssocList');
	}
    
    /**
     * Getsa unit data
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $id
     * @return array
     */
	public static function get_unit($id)
	{
		/** first validation **/
		if(trim($id) == '') return array();
		
		$unit = wpl_units::get_units('', '', " AND `id`='".$id."'");
		return (isset($unit[0]) ? $unit[0] : NULL);
	}
	
    /**
     * Returns unit ID by desired key=value criteria
     * @author Howard <howard@realtyna.com>
     * @static
     * @param mixed $value
     * @param string $by
     * @param int $type
     * @return int
     */
    public static function id($value, $by = 'extra', $type = 4)
    {
        $query = "SELECT `id` FROM `#__wpl_units` WHERE `$by`='$value' AND `type`='$type'";
        return wpl_db::select($query, 'loadResult');
    }
    
    /**
     * Returns default unit
     * @author Howard <howard@realtyna.com>
     * @static
     * @param type $type
     * @param type $enabled
     * @param type $condition
     * @return type
     */
    public static function get_default_unit($type = 4, $enabled = 1, $condition = '')
	{
		if(trim($condition) == '')
		{
			$condition = '';
			
			if(trim($type) != '') $condition .= " AND `type`='$type'";
			if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
		}
		
		$query = "SELECT * FROM `#__wpl_units` WHERE 1 ".$condition." ORDER BY `index` ASC LIMIT 1";
		return wpl_db::select($query, 'loadAssoc');
	}
    
    /**
     * Sorts units
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $sort_ids
     */
	public static function sort_units($sort_ids)
	{
		$query = "SELECT `id`,`index` FROM `#__wpl_units` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC";
		$units = wpl_db::select($query, 'loadAssocList');
		
		$conter = 0;
		$ex_sort_ids = explode(',', $sort_ids);
		
		foreach($ex_sort_ids as $ex_sort_id)
		{
			self::update($ex_sort_id, 'index', ($conter+1));
			$conter++;
		}
	}
	
    /**
     * Update wpl_units table
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $unit_id
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
	public static function update($unit_id, $key, $value = '')
	{
		/** first validation **/
		if(trim($unit_id) == '' or trim($key) == '') return false;
		return wpl_db::set('wpl_units', $unit_id, $key, $value);
	}	
	
    /**
     * This is a function for updating all currencies exchange rates from yahoo server
     * @author Howard <howard@realtyna.com>
     * @static
     * @return void
     */
	public static function update_exchange_rates()
	{
		$currencies = self::get_units(4);

		foreach ($currencies as $currency)
		{
			$currency_code = $currency['extra'];
			$exchange_rate = self::currency_converter($currency_code, 'USD', 1);
			self::update($currency['id'], 'tosi', $exchange_rate);
		}
        
        /** trigger event **/
		wpl_global::event_handler('exchange_rates_updated', array());
	}
	
    /**
     * Update one currency exchange rate
     * @author Howard <howard@realtyna.com>
     * @static
     * @param type $unit_id
     * @param type $currency_code
     * @return int
     */
	public static function update_a_exchange_rate($unit_id, $currency_code)
	{
		$exchange_rate = self::currency_converter($currency_code, 'USD', 1);
		$result = self::update($unit_id, 'tosi', $exchange_rate);
		
        /** trigger event **/
		wpl_global::event_handler('exchange_rate_updated', array('unit_id'=>$unit_id, 'currency_code'=>$currency_code));
        
		if($result)	return $exchange_rate;
		else return 0;
	}
    
    /**
     * Updates exchange rate of a currency
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $unit_id
     * @param mixed $value
     * @return boolean
     */
	public static function update_exchange_rate($unit_id, $value)
	{
		return self::update($unit_id, 'tosi', $value);
	}
	
    /**
     * Convert a value from a currency to another one
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $cur_from
     * @param int $cur_to
     * @param string $val
     * @return string|boolean
     */
	public static function currency_converter($cur_from, $cur_to, $val)
	{
		if(strlen($cur_from) == 0) $cur_from = "USD";
		if(strlen($cur_to) == 0) $cur_to = "CAD";
		if($cur_from == $cur_to) return '1.0000';
		
		$host = "download.finance.yahoo.com";
		$fp = @fsockopen($host, 80, $errno, $errstr, 30);
		
		if(!$fp)
		{
			$errorstr = "$errstr ($errno)<br />\n";
			return false;
		}
		else
		{
			$data = '';
			$file = "/d/quotes.csv";
			$str = "?s=".$cur_from.$cur_to."=X&f=sl1d1t1ba&e=.csv";
			$out = "GET ".$file.$str." HTTP/1.0\r\n";
			$out .= "Host: download.finance.yahoo.com\r\n";
			$out .= "Connection: Close\r\n\r\n";
			@fputs($fp, $out);
			
			while(!@feof($fp))
			{
				$data .= @fgets($fp, 128);
			}
			
			@fclose($fp);
			@preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $data, $match);
			$data = $match[2];
			$search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'","'&(quot|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i","'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(\d+);'e");
			$replace = array ("","","\\1","\"","&","<",">"," ",chr(161),chr(162),chr(163),chr(169),"chr(\\1)");
			$data = @preg_replace($search, $replace, $data);
			$result = @split(",",$data);
			$w  = @ereg_replace("[^0-9\.]", "", $val);
			$w2 = number_format($w, 2, '.', '');
			
			$x  = $result[1];
			$x1 = $x * $w;
			$x2 = number_format($x1, 4, '.', '');
			
			return $x2;
		}
	}
    
    /**
     * Converts a value from a currency to another one using WPL units table
     * @author Howard <howard@realtyna.com>
     * @static
     * @param double $value
     * @param int $unit_from
     * @param int $unit_to
     * @return double
     */
	public static function convert($value, $unit_from, $unit_to)
	{
        /** Returns $value when both of currencies are same **/
        if($unit_from == $unit_to) return $value;
        
		$unit_from_data = self::get_unit($unit_from);
        $unit_to_data = self::get_unit($unit_to);
        
        $value_si = $value*$unit_from_data['tosi'];
        $value_final = $value_si/$unit_to_data['tosi'];
        
        return $value_final;
	}
}
