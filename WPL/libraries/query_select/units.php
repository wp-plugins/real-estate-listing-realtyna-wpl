<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** this is for unit fields **/
$units = array('area','volume','price','length');

if(in_array($type, $units) and !$done_this)
{
	$column = $field->table_column;
	$query .= $table_name.".`".$column."`, ".$table_name.".`".$column."_unit`, ".$table_name.".`".$column."_si`, ";
	$done_this = true;
}
