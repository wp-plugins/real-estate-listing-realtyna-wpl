<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'locationtextsearch' and !$done_this)
{
	$values_raw = explode(',', $value);
	$values = array();
	
	foreach($values_raw as $value_raw)
	{
		if(trim($value_raw) != '') array_push($values, trim($value_raw));
	}
	
	$values = array_reverse($values);
	
	if(count($values))
	{
		$qqq = array();
        $qq = array();
		
        $qq[] = " `zip_name` LIKE '".$values[count($values) - 1]."%' ";
		
        for($j = 1; $j < count($values); $j++)
            $qq[] = " `location".($j)."_name` LIKE '".$values[$j - 1]."%' ";

        $qqq[] = '('.implode(' AND ', $qq).')';

        for($i = 1; $i <= (7 - count($values) + 1); $i++)
        {
            $qq = array();
            for ($j = 0; $j < count($values); $j++)
                $qq[] = " `location".($i+$j)."_name` LIKE '".$values[$j]."%' ";

            $qqq[] = '('.implode(' AND ', $qq).')';
        }

        $query .= ' AND ('.implode(' OR ', $qqq).')';
	}
	
	$done_this = true;
}