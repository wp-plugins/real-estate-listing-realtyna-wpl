<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'booking_cancellation' and !$done_this)
{
	if(trim($value) != '')
	{
		_wpl_import('libraries.addon_booking');
		$booking = new wpl_addon_booking();
		$booking_cancellation = $booking->booking_policies(1, $value);

		if(!empty($booking_cancellation['term'])) $term_value = ' - <a data-realtyna-lightbox href="#wplbooking_term_'.$field->id.'" style="color:#3073AD;text-decoration: underline;">'.__('Terms', WPL_TEXTDOMAIN).'</a>
		<div style="display:none" class="wplbooking_show_cancellation_term" id="wplbooking_term_'.$field->id.'"> <div style="padding:10px;">'.str_replace('\\n', '<br />', $booking_cancellation['term']).' </div> </div>';

		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = $booking_cancellation['name'].$term_value;
		$return['raw_value'] = $value;
	}

	$done_this = true;
}
elseif($type == 'booking_pet' and !$done_this)
{
	if(trim($value) != '')
	{
		_wpl_import('libraries.addon_booking');
		$booking = new wpl_addon_booking();
		$booking_pet = $booking->booking_policies(2, $value);

		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = $booking_pet['name'];
		$return['raw_value'] = $value;
	}

	$done_this = true;
}