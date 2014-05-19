<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'meta_desc' and !$done_this)
{
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="wpl_red_star">*</span><?php endif; ?></label>
<textarea id="wpl_c_<?php echo $field->id; ?>" rows="<?php echo $options['rows']; ?>" cols="<?php echo $options['cols']; ?>" onchange="metatag_desc_creator();" <?php echo ($options['readonly'] == 1 ? 'disabled="disabled"' : ''); ?>><?php echo $value; ?></textarea>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<script type="text/javascript">
wplj(document).ready(function()
{
	metatag_desc_creator();
<?php
	$array = array('wpl_listing_location1_select','wpl_listing_location2_select','wpl_listing_location3_select','wpl_listing_location4_select','wpl_listing_location5_select','wpl_listing_location6_select','wpl_listing_location7_select','wpl_listing_locationzips_select','wpl_c_2','wpl_c_5','wpl_c_8','wpl_c_13','wpl_c_3','wpl_c_54','wpl_c_55','wpl_c_9','wpl_c_42');
	foreach($array as $arr) echo 'wplj("#'.$arr.'").change( function(){ metatag_desc_creator()});'."\n";
?>
});

function metatag_desc_creator()
{
	var meta = '';
	var start = '';
	var address = '';
	
	for(i=7; i>=1; i--)
	{
		try
		{
			if(wplj("#wpl_listing_location"+i+"_select").val() != '0' && wplj.trim(wplj("#wpl_listing_location"+i+"_select").val()) != '')
			{
				if(!isNaN(wplj("#wpl_listing_location"+i+"_select").val()))
					address += wplj("#wpl_listing_location"+i+"_select :selected").text()+', ';
				else
					address += wplj("#wpl_listing_location"+i+"_select").val()+', ';
			}
		}
		catch(err)  { }
	}
	
	// Zipcode
	try
	{
		if(wplj("#wpl_listing_locationzips_select").val() != '0' && wplj.trim(wplj("#wpl_listing_locationzips_select").val()) != '')
		{
			if(wplj("#wpl_listing_locationzips_select").prop('tagName').toLowerCase() == 'select')
				address += wplj("#wpl_listing_locationzips_select :selected").text()+', ';
			else
				address += wplj("#wpl_listing_locationzips_select").val()+', ';
		}
	}
	catch(err)  { }
	
	// bedrooms
	try
	{
		if(wplj.trim(wplj("#wpl_c_8").val()) != '0' && wplj.trim(wplj("#wpl_c_8").val()) != '')
			start = wplj("#wpl_c_8").val() + ' <?php echo __('Bedrooms', WPL_TEXTDOMAIN);?> ';
	}
	catch(err)  { }

	// rooms
	try
	{
		if(wplj.trim(wplj("#wpl_c_13").val()) != '0' && wplj.trim(wplj("#wpl_c_13").val()) != '')
			start += wplj("#wpl_c_13").val() + ' <?php echo __('Rooms', WPL_TEXTDOMAIN);?> ';
	}
	catch(err)  { }

	// property type
	try
	{
		if(wplj.trim(wplj("#wpl_c_3").val()) != '0' || wplj.trim(wplj("#wpl_c_3").val()) != '-1')
			start += wplj("#wpl_c_3 :selected").text() + ' ';
	}
	catch(err)  { }

	// listintg type
	try
	{
		if(wplj.trim(wplj("#wpl_c_2").val()) != '0' || wplj.trim(wplj("#wpl_c_2").val()) != '-1')
			start += wplj("#wpl_c_2 :selected").text() + ' ';
	}
	catch(err)  { }
	
	// building name
	try
	{
		if(wplj.trim(wplj("#wpl_c_54").val()) != '')
			start += wplj("#wpl_c_54").val() + ' ';
	}
	catch(err)  { }

	// street
	try
	{
		if(wplj.trim(wplj("#wpl_c_42").val()) != '')
			start += wplj("#wpl_c_42").val() + ' ';
	}
	catch(err)  { }
	
	// floor
	try
	{
		if(wplj.trim(wplj("#wpl_c_55").val()) != '0' && wplj.trim(wplj("#wpl_c_55").val()) != '')
			start += "<?php echo __('On the', WPL_TEXTDOMAIN); ?> "+number_to_th(wplj("#wpl_c_55").val()) + " <?php echo __('Floor', WPL_TEXTDOMAIN); ?> ";
	}
	catch(err)  { }

	// bathrooms
	try
	{
		if(wplj.trim(wplj("#wpl_c_9").val()) != '0' && wplj.trim(wplj("#wpl_c_9").val()) != '')
			start += "<?php echo __('With', WPL_TEXTDOMAIN); ?> "+wplj("#wpl_c_9").val() + " <?php echo __('Bathrooms', WPL_TEXTDOMAIN); ?> ";
	}
	catch(err)  { }
	
	meta = start;
	if(address != '') meta += '<?php echo __('In', WPL_TEXTDOMAIN); ?> '+address;
	
	// Listing id
	try
	{
		if(wplj.trim(wplj("#wpl_c_5").val()) != '0' || wplj.trim(wplj("#wpl_c_5").val()) != '-1')
			meta += "<?php echo __('Listing ID', WPL_TEXTDOMAIN); ?> "+wplj("#wpl_c_5").val() + ' ';
	}
	catch(err)  { }
	
	meta = wplj.trim(meta);
	
	wplj("#wpl_c_<?php echo $field->id; ?>").val(meta);
	ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', meta, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');
}
</script>
<?php
	$done_this = true;
}