<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'googlemap' and !$done_this)
{
    /** WPL Demographic addon **/
    $demographic_objects = array();
    if(wpl_global::check_addon('demographic'))
    {
        _wpl_import('libraries.addon_demographic');
        $demographic = new wpl_addon_demographic();
        
        $demographic_objects = wpl_items::get_items($item_id, 'demographic', $kind);
    }
    
    $w = 450;
    $h = 300;
    $ln_table_col = 'googlemap_ln';
    $lt_table_col = 'googlemap_lt';
    
    // Load Google Maps API
    $javascript = (object) array('param1'=>'google-maps', 'param2'=>'http'.(stristr(wpl_global::get_full_url(), 'https://') != '' ? 's' : '').'://maps.google.com/maps/api/js?libraries=places,drawing&sensor=true', 'external'=>true);
    wpl_extensions::import_javascript($javascript);
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	try
	{
		wplj(".wpl_listing_all_location_container_locations, .wpl_c_field_42, .wpl_c_post_code, .wpl_c_street_no").change(function()
		{
			wpl_address_creator();
			wpl_code_address(wplj("#wpl_map_address<?php echo $field->id; ?>").val());
		});
	}
	catch (err) {}
});

var pw_map = '';
var pw_marker = '';
var polygonsArray = [];
var polylinesArray = [];
var bounds;

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
    
    /** create empty LatLngBounds object **/
	bounds = new google.maps.LatLngBounds();
    
	var marker_position = new google.maps.LatLng(lt, ln);
	var myOptions = {
		scrollwheel: false,
		zoom: 11,
		center: marker_position,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	pw_map = new google.maps.Map(document.getElementById("wpl_map_canvas<?php echo $field->id; ?>"), myOptions);
    
    <?php if(wpl_global::check_addon('demographic')): ?>
    /** restore the zoom level after the map is done scaling **/
	var pw_listener = google.maps.event.addListener(pw_map, 'idle', function(event)
	{
        pw_map.fitBounds(bounds);
		google.maps.event.removeListener(pw_listener);
	});
    <?php endif; ?>
    
	/** marker **/
	pw_marker = new google.maps.Marker(
	{
		position: marker_position,
		map: pw_map,
		draggable: true,
		title: "<?php echo addslashes(__('Position of property', WPL_TEXTDOMAIN)); ?>"
	});
    
    /** extend the bounds **/
  	bounds.extend(pw_marker.position);
    
	google.maps.event.addListener(pw_marker, "dragend", function(event)
	{
		var curpos = event.latLng;
		var x = curpos.lng();
		var y = curpos.lat();

		wplj(".wpl_c_googlemap_ln").attr('value', x);
		wplj(".wpl_c_googlemap_lt").attr('value', y);

		ajax_save('wpl_properties', '<?php echo $lt_table_col; ?>', y, <?php echo $item_id; ?>);
		ajax_save('wpl_properties', '<?php echo $ln_table_col; ?>', x, <?php echo $item_id; ?>);
	});

	if (lt_orig == 0 || ln_orig == 0)
	{
		address = wplj('#wpl_map_address<?php echo $field->id; ?>').val();
		wpl_code_address(address);
	}
    
    <?php if(wpl_global::check_addon('demographic')): ?>
    init_dmgfc();
    <?php endif; ?>
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

			wplj(".wpl_c_googlemap_ln").attr('value', x);
			wplj(".wpl_c_googlemap_lt").attr('value', y);

			ajax_save('wpl_properties', '<?php echo $lt_table_col; ?>', y, <?php echo $item_id; ?>);
			ajax_save('wpl_properties', '<?php echo $ln_table_col; ?>', x, <?php echo $item_id; ?>);
		}
        else
		{
            wpl_show_messages("<?php echo addslashes(__('Geocode was not successful for the following reason', WPL_TEXTDOMAIN)); ?> : " + status, '.wpl_pwizard_googlemap_message .wpl_show_message', 'wpl_gold_msg');
            setTimeout(function(){wpl_remove_message('.wpl_pwizard_googlemap_message .wpl_show_message')}, 3000);
		}
	});
}

wplj(document).ready(function()
{
    if(wplj('#wpl_map_canvas<?php echo $field->id; ?>').is(':visible')) wpl_initialize();
    
	wplj(".wpl_slide_label_prefix_ad").click(function()
	{
		wpl_initialize();
	});

	wpl_address_creator();

	wplj('.autocomplete-w1').click(function()
    {
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
					address += wplj("#wpl_listing_location" + i + "_select option:selected").text() + ', ';
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
				address = wplj("#wpl_listing_locationzips_select option:selected").text() + ', ' + address;
			else
				address = wplj("#wpl_listing_locationzips_select").val() + ', ' + address;
		}
	}
	catch (err) {}

	// Street
	try
	{
		if (wplj(".wpl_c_field_42").length && wplj.trim(wplj(".wpl_c_field_42").val()) != '')
			address = wplj(".wpl_c_field_42").val() + ', ' + address;
	}
	catch (err) {}

	// Street number
	try
	{
		if (wplj(".wpl_c_street_no").length && wplj.trim(wplj(".wpl_c_street_no").val()) != '')
			address = wplj(".wpl_c_street_no").val() + ', ' + address;
	}
	catch (err) {}

	// Postal Code
	try
	{
		if (wplj(".wpl_c_post_code").length && wplj.trim(wplj(".wpl_c_post_code").val()) != '')
			address = wplj(".wpl_c_post_code").val() + ', ' + address;
	}
	catch (err) {}

	if (address.substring(address.length - 2) == ', ')
		address = address.substring(0, address.length - 2);
    
	wplj('#wpl_map_address<?php echo $field->id; ?>').val(address);
	if (orig_address != address) wpl_code_address(address);
}

function init_dmgfc()
{
	drawingManager = new google.maps.drawing.DrawingManager(
    {
		drawingControl: true,
		drawingControlOptions:
        {
			position: google.maps.ControlPosition.TOP_CENTER,
			drawingModes: [
                google.maps.drawing.OverlayType.POLYGON,
                google.maps.drawing.OverlayType.POLYLINE
			]
		},
		polygonOptions:
        {
            strokeColor: '#1e74c7',
            strokeOpacity: 0.6,
            strokeWeight: 1,
            editable: true,
            fillColor: '#1e90ff',
            fillOpacity: 0.3
        },
        polylineOptions:
        {
            strokeColor: '#1e74c7',
            strokeOpacity: 1.0,
            strokeWeight: 2,
            editable: true
        },
		map: pw_map
	});
    
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event)
	{
        drawingManager.setOptions({drawingMode: null});
        
        var overlay = event.overlay;
        wpl_dmgfc_set_boundaries(overlay, event.type);
        
        if(event.type == google.maps.drawing.OverlayType.POLYGON)
        {
            /** delete overlays **/
            for(var i = 0; i < polygonsArray.length; i++) polygonsArray[i].setMap(null);
            polygonsArray = new Array();
            
            /** push to array **/
            polygonsArray.push(overlay);
        }
        else if(event.type == google.maps.drawing.OverlayType.POLYLINE)
        {
            /** delete overlays **/
            for(var i = 0; i < polylinesArray.length; i++) polylinesArray[i].setMap(null);
            polylinesArray = new Array();
            
            /** push to array **/
            polylinesArray.push(overlay);
        }
        
        /** POLYGON **/
        if(event.type == google.maps.drawing.OverlayType.POLYGON)
        {
            overlay.getPaths().forEach(function(path, index)
            {
                google.maps.event.addListener(path, 'insert_at', function()
                {
                    wpl_dmgfc_set_boundaries(overlay, google.maps.drawing.OverlayType.POLYGON);
                });

                google.maps.event.addListener(path, 'remove_at', function()
                {
                    wpl_dmgfc_set_boundaries(overlay, google.maps.drawing.OverlayType.POLYGON);
                });

                google.maps.event.addListener(path, 'set_at', function()
                {
                    wpl_dmgfc_set_boundaries(overlay, google.maps.drawing.OverlayType.POLYGON);
                });
            });
        }
        else if(event.type == google.maps.drawing.OverlayType.POLYLINE)
        {
            google.maps.event.addListener(overlay.getPath(), 'insert_at', function()
            {
                wpl_dmgfc_set_boundaries(overlay, google.maps.drawing.OverlayType.POLYLINE);
            });

            google.maps.event.addListener(overlay.getPath(), 'remove_at', function()
            {
                wpl_dmgfc_set_boundaries(overlay, google.maps.drawing.OverlayType.POLYLINE);
            });

            google.maps.event.addListener(overlay.getPath(), 'set_at', function()
            {
                wpl_dmgfc_set_boundaries(overlay, google.maps.drawing.OverlayType.POLYLINE);
            });
        }
	});
    
    <?php
    foreach($demographic_objects as $demographic_object)
    {
        $boundaries = $demographic->toBoundaties($demographic_object->item_extra1);
        ?>
            var demographicCoords = [];
            <?php foreach($boundaries as $boundary): ?>
            var position = new google.maps.LatLng(<?php echo $boundary['lat']; ?>, <?php echo $boundary['lng']; ?>);
            demographicCoords.push(position);
            bounds.extend(position);
            <?php endforeach; ?>
        <?php
        if(strtolower($demographic_object->item_cat) == 'polygon')
        {
        ?>
            var polygon = new google.maps.Polygon(
            {
                paths: demographicCoords,
                strokeColor: '#1e74c7',
                strokeOpacity: 0.6,
                strokeWeight: 1,
                editable: true,
                fillColor: '#1e90ff',
                fillOpacity: 0.3
            });
    
            polygon.setMap(pw_map);
    
            /** push to array **/
            polygonsArray.push(polygon);

            polygon.getPaths().forEach(function(path, index)
            {
                google.maps.event.addListener(path, 'insert_at', function()
                {
                    wpl_dmgfc_set_boundaries(polygon, google.maps.drawing.OverlayType.POLYGON);
                });

                google.maps.event.addListener(path, 'remove_at', function()
                {
                    wpl_dmgfc_set_boundaries(polygon, google.maps.drawing.OverlayType.POLYGON);
                });

                google.maps.event.addListener(path, 'set_at', function()
                {
                    wpl_dmgfc_set_boundaries(polygon, google.maps.drawing.OverlayType.POLYGON);
                });
            });
        <?php
        }
        elseif(strtolower($demographic_object->item_cat) == 'polyline')
        {
        ?>
            var polyline = new google.maps.Polyline({
                path: demographicCoords,
                strokeColor: '#1e74c7',
                strokeOpacity: 1.0,
                strokeWeight: 2,
                editable: true
            });
            
            polyline.setMap(pw_map);
    
            /** push to array **/
            polylinesArray.push(polyline);
            
            google.maps.event.addListener(polyline.getPath(), 'insert_at', function()
            {
                wpl_dmgfc_set_boundaries(polyline, google.maps.drawing.OverlayType.POLYLINE);
            });

            google.maps.event.addListener(polyline.getPath(), 'remove_at', function()
            {
                wpl_dmgfc_set_boundaries(polyline, google.maps.drawing.OverlayType.POLYLINE);
            });

            google.maps.event.addListener(polyline.getPath(), 'set_at', function()
            {
                wpl_dmgfc_set_boundaries(polyline, google.maps.drawing.OverlayType.POLYLINE);
            });
        <?php
        }
    }
    ?>
}

function wpl_dmgfc_set_boundaries(overlay, type)
{
    var paths = [];
    
    if(type == google.maps.drawing.OverlayType.POLYGON)
    {
        overlay.getPaths().forEach(function(path, index)
        {
            var points = path.getArray();
            for(b in points)
            {
                paths.push(new google.maps.LatLng(points[b].lat(), points[b].lng()));
            }
        });
    }
    else if(type == google.maps.drawing.OverlayType.POLYLINE)
    {
        overlay.getPath().forEach(function(path, index)
        {
            paths.push(new google.maps.LatLng(path.lat(), path.lng()));
        });
    }
    
    item_save('', <?php echo $item_id; ?>, 0, 'demographic', type, encodeURIComponent(paths.toString()));
}
</script>
<div class="google-map-wp">
    <div class="wpl_pwizard_googlemap_message"><div class="wpl_show_message"></div></div>
	<div class="map-form-wp">
		<label for="wpl_map_address<?php echo $field->id; ?>"><?php echo __('Map point', WPL_TEXTDOMAIN); ?> :</label>
		<input class="text-address" id="wpl_map_address<?php echo $field->id; ?>" type="text" name="address" value="" />
		<button class="wpl-button button-1" onclick="wpl_code_address(wplj('#wpl_map_address<?php echo $field->id; ?>').val());"><?php echo addslashes(__('Go', WPL_TEXTDOMAIN)); ?></button>
	</div>
	<div class="map-canvas-wp">
        <div id="wpl_map_canvas<?php echo $field->id; ?>"></div>
	</div>
</div>
<?php
    $done_this = true;
}