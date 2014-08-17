<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'feature' and !$done_this)
{
	if($values[$field->table_column] != 0) 
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		
		/** options of property column **/
		$column_options = $values[$field->table_column.'_options'];
		$column_values = explode(',', $column_options);
		$i = 0;
		
		if(isset($options['values']))
		{
			foreach($options['values'] as $field_option)
			{
				if(in_array($field_option['key'], $column_values))
				{
					$return['values'][$i] = __($field_option['value'], WPL_TEXTDOMAIN);
					$i++;
				}
			}
		}
		else
		{
			$return['value'] = 1;
		}
	}
	
	$done_this = true;       
}
