<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'googlemap' and !$done_this)
{
    $w = 450;
    $h = 300;
    $ln_table_col = wpl_db::get('table_column', 'wpl_dbst', 'id', '51');
    $lt_table_col = wpl_db::get('table_column', 'wpl_dbst', 'id', '52');

    $javascript = (object) array('param1' => 'wpl-googlemap-api3', 'param2' => 'http' . (stristr(wpl_global::get_full_url(), 'https://') != '' ? 's' : '') . '://maps.google.com/maps/api/js?sensor=false', 'external' => true);
    wpl_extensions::import_javascript($javascript);
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	try
	{
		wplj("#wpl_listing_all_location_container41, #wpl_c_42, #wpl_c_43, #wpl_c_45").change(function()
		{
			wpl_address_creator();
			wpl_code_address(wplj("#wpl_map_address<?php echo $field->id; ?>").val());
		});
	}
	catch (err) {}
});

var pw_map = '';
var pw_marker = '';

function wpl_initialize()
{
	if (pw_map != '') return;

	var lt_orig = '<?php echo $values['googlemap_lt']; ?>';
	var ln_orig = '<?php echo $values['googlemap_ln']; ?>';

	if (lt_orig == 0 || ln_orig == 0)
	{
		lt = 90;
		ln = 90;
	}
	else
	{
		lt = lt_orig;
		ln = ln_orig;
	}

	var myLatlng = new google.maps.LatLng(lt, ln);
	var myOptions = {
		scrollwheel: false,
		zoom: 11,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	pw_map = new google.maps.Map(document.getElementById("wpl_map_canvas<?php echo $field->id; ?>"), myOptions);

	/** marker **/
	pw_marker = new google.maps.Marker(
	{
		position: myLatlng,
		map: pw_map,
		draggable: true,
		title: "Position of property"
	});

	google.maps.event.addListener(pw_marker, "dragend", function(event)
	{
		var curpos = event.latLng;
		var x = curpos.lng();
		var y = curpos.lat();

		wplj("#wpl_c_51").attr('value', x);
		wplj("#wpl_c_52").attr('value', y);

		ajax_save('wpl_properties', '<?php echo $lt_table_col; ?>', y, <?php echo $item_id; ?>);
		ajax_save('wpl_properties', '<?php echo $ln_table_col; ?>', x, <?php echo $item_id; ?>);
	});

	if (lt_orig == 0 || ln_orig == 0)
	{
		address = wplj('#wpl_map_address<?php echo $field->id; ?>').val();
		wpl_code_address(address);
	}
}

function wpl_code_address(address)
{
	if (wplj.trim(address) == '') return;
	if (pw_map == '') return;

	geocoder = new google.maps.Geocoder();
	geocoder.geocode({'address': address}, function(results, status)
	{
		if (status == google.maps.GeocoderStatus.OK)
		{
			pw_map.setCenter(results[0].geometry.location);
			pw_marker.setPosition(results[0].geometry.location);

			var curpos = pw_marker.getPosition();
			var x = curpos.lng();
			var y = curpos.lat();

			wplj("#wpl_c_51").attr('value', x);
			wplj("#wpl_c_52").attr('value', y);

			ajax_save('wpl_properties', '<?php echo $lt_table_col; ?>', y, <?php echo $item_id; ?>);
			ajax_save('wpl_properties', '<?php echo $ln_table_col; ?>', x, <?php echo $item_id; ?>);
		}
		else
		{
			wpl_alert("<?php echo __('Geocode was not successful for the following reason', WPL_TEXTDOMAIN); ?> : " + status);
		}
	});
}

wplj(document).ready(function()
{
	wplj("#wpl_slide_label_id2").click(function()
	{
		wpl_initialize();
	});

	wpl_address_creator();

	wplj('.autocomplete-w1').click(function() {
		wpl_address_creator();
	});
});

function wpl_address_creator()
{
	var orig_address = wplj('#wpl_map_address<?php echo $field->id; ?>').val();
	var address = '';

	// Location levels
	for (i = 7; i >= 1; i--)
	{
		try
		{
			if (wplj("#wpl_listing_location" + i + "_select").val() != '0' && wplj.trim(wplj("#wpl_listing_location" + i + "_select").val()) != '')
			{
				if (!isNaN(wplj("#wpl_listing_location" + i + "_select").val()))
					address += wplj("#wpl_listing_location" + i + "_select:selected").text() + ', ';
				else
					address += wplj("#wpl_listing_location" + i + "_select").val() + ', ';
			}

		}
		catch (err) {}
	}

	// Zipcode
	try
	{
		if (wplj("#wpl_listing_locationzips_select").val() != '0' && wplj.trim(wplj("#wpl_listing_locationzips_select").val()) != '')
		{
			if (wplj("#wpl_listing_locationzips_select").prop('tagName').toLowerCase() == 'select')
				address = wplj("#wpl_listing_locationzips_select:selected").text() + ', ' + address;
			else
				address = wplj("#wpl_listing_locationzips_select").val() + ', ' + address;
		}
	}
	catch (err) {}

	// Street
	try
	{
		if (wplj("#wpl_c_42").length && wplj.trim(wplj("#wpl_c_42").val()) != '')
			address = wplj("#wpl_c_42").val() + ', ' + address;
	}
	catch (err) {}

	// Street number
	try
	{
		if (wplj("#wpl_c_45").length && wplj.trim(wplj("#wpl_c_45").val()) != '')
			address = wplj("#wpl_c_45").val() + ', ' + address;
	}
	catch (err) {}

	// Postal Code
	try
	{
		if (wplj("#wpl_c_43").length && wplj.trim(wplj("#wpl_c_43").val()) != '')
			address = wplj("#wpl_c_43").val() + ', ' + address;
	}
	catch (err) {}

	if (address.substring(address.length - 2) == ', ')
		address = address.substring(0, address.length - 2);

	wplj('#wpl_map_address<?php echo $field->id; ?>').val(address);
	if (orig_address != address) wpl_code_address(address);
}
</script>
<div class="google-map-wp">
	<div class="map-form-wp">
		<label for="wpl_map_address<?php echo $field->id; ?>"><?php echo __('Map point', WPL_TEXTDOMAIN); ?> :</label>
		<input class="text-address" id="wpl_map_address<?php echo $field->id; ?>" type="text" name="address" value="">
		<button class="wpl-button button-1" onclick="wpl_code_address(wplj('#wpl_map_address<?php echo $field->id; ?>').val());"><?php echo __('Go', WPL_TEXTDOMAIN); ?></button>
	</div>
	<div class="map-canvas-wp">
		<div id="wpl_map_canvas<?php echo $field->id; ?>"></div>
	</div>
</div>
<?php
    $done_this = true;
}