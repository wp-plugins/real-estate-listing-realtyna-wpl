<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

// Load Google Maps API
$javascript = (object) array('param1'=>'google-maps', 'param2'=>'http'.(stristr(wpl_global::get_full_url(), 'https://') != '' ? 's' : '').'://maps.google.com/maps/api/js?libraries=places,drawing&sensor=true', 'external'=>true);
wpl_extensions::import_javascript($javascript);
    
$map_activities = wpl_activity::get_activities('plisting_position1', 1);
?>
<script type="text/javascript">
var wpl_map<?php echo $this->activity_id; ?>;
var markers_array<?php echo $this->activity_id; ?> = new Array();
var loaded_markers<?php echo $this->activity_id; ?> = new Array();
var markers<?php echo $this->activity_id; ?>;
var bounds<?php echo $this->activity_id; ?>;
var infowindow<?php echo $this->activity_id; ?>;
var wpl_map_bounds_extend<?php echo $this->activity_id; ?> = true;
var wpl_map_set_default_geo_point<?php echo $this->activity_id; ?> = true;

if(typeof google_place_radius == 'undefined') var google_place_radius = 1100;

function wpl_initialize<?php echo $this->activity_id; ?>()
{
	/** create empty LatLngBounds object **/
	bounds<?php echo $this->activity_id; ?> = new google.maps.LatLngBounds();
	var mapOptions = {
		scrollwheel: false,
		mapTypeId: google.maps.MapTypeId.<?php echo (isset($this->googlemap_view) ? $this->googlemap_view : 'ROADMAP'); ?>,
        <?php if(isset($this->overviewmap) and $this->overviewmap): ?>
        overviewMapControl: true,
        overviewMapControlOptions: {opened: true}
        <?php endif; ?>
	}
    
	/** init map **/
	wpl_map<?php echo $this->activity_id; ?> = new google.maps.Map(document.getElementById('wpl_map_canvas<?php echo $this->activity_id; ?>'), mapOptions);
	infowindow<?php echo $this->activity_id; ?> = new google.maps.InfoWindow();
	
	/** load markers **/
	wpl_load_markers<?php echo $this->activity_id; ?>(markers<?php echo $this->activity_id; ?>);
	
    <?php if(isset($this->googlemap_view) and $this->googlemap_view == 'WPL'): ?>
    var styles = [{"featureType": "water", "stylers": [{"color": "#46bcec"},{"visibility": "on"}]},{"featureType": "landscape","stylers": [{"color": "#f2f2f2"}]},{"featureType": "road","stylers": [{"saturation": -100},{"lightness": 45}]},{"featureType": "road.highway","stylers": [{"visibility": "simplified"}]},{"featureType": "administrative","elementType": "labels.text.fill","stylers": [{"color": "#444444"}]},{"featureType": "poi","stylers": [{"visibility": "off"}]}];
    var styledMap = new google.maps.StyledMapType(styles, {name: "WPL Map"});

    wpl_map<?php echo $this->activity_id; ?>.mapTypes.set('map_style', styledMap);
    wpl_map<?php echo $this->activity_id; ?>.setMapTypeId('map_style');
    <?php endif; ?>

    /* Check Google Places */
	if((typeof google_place != 'undefined') && (google_place == 1) && typeof marker != 'undefined')
	{
        var request = {
            location: marker.position,
            radius: google_place_radius
        };
  
		var service = new google.maps.places.PlacesService(wpl_map<?php echo $this->activity_id; ?>);
		service.search(request, wpl_gplace_callback<?php echo $this->activity_id; ?>);
	}
    
    if(typeof wpl_dmgfc_init != 'undefined')
    {
        var wpl_dmgfc_init_listener = google.maps.event.addListener(wpl_map<?php echo $this->activity_id; ?>, 'idle', function()
        {
            wpl_dmgfc_init();
            jQuery('.wpl_map_canvas').append('<div class="wpl_dmgfc_container"></div>');

            /** Remove listener **/
            google.maps.event.removeListener(wpl_dmgfc_init_listener);
        });
    }
}

function wpl_marker<?php echo $this->activity_id; ?>(dataMarker)
{
	if(wplj.inArray(dataMarker.id, loaded_markers<?php echo $this->activity_id; ?>) != '-1') return true;
	
  	marker = new google.maps.Marker(
    {
		position: new google.maps.LatLng(dataMarker.googlemap_lt, dataMarker.googlemap_ln),
		map: <?php echo ($this->show_marker ? 'wpl_map'.$this->activity_id : 'null'); ?>,
		property_ids: dataMarker.pids,
		icon: '<?php echo wpl_global::get_wpl_url(); ?>assets/img/listing_types/gicon/'+dataMarker.gmap_icon,
		title: dataMarker.title,
	});
	
	/** extend the bounds to include each marker's position **/
  	if(wpl_map_bounds_extend<?php echo $this->activity_id; ?>) bounds<?php echo $this->activity_id; ?>.extend(marker.position);
  
	loaded_markers<?php echo $this->activity_id; ?>.push(dataMarker.id);
  	markers_array<?php echo $this->activity_id; ?>.push(marker);
	
	google.maps.event.addListener(marker, "<?php echo $this->infowindow_event; ?>", function(event)
	{
        /** Don't run APS AJAX search because of boundary change due to opening infowindow **/
        if(typeof wpl_aps_freeze != 'undefined') wpl_aps_freeze = true;
                
		if(this.html)
		{
			infowindow<?php echo $this->activity_id; ?>.close();
			infowindow<?php echo $this->activity_id; ?>.setContent(this.html);
			infowindow<?php echo $this->activity_id; ?>.open(wpl_map<?php echo $this->activity_id; ?>, this);
		}
		else
		{
            /** AJAX loader **/
			wplj("#wpl_map_canvas<?php echo $this->activity_id; ?>").append('<div class="map_search_ajax_loader"><img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader4.gif'); ?>" /></div>');
			
			infowindow_html = get_infowindow_html<?php echo $this->activity_id; ?>(this.property_ids);
			this.html = infowindow_html;
			infowindow<?php echo $this->activity_id; ?>.close();
			infowindow<?php echo $this->activity_id; ?>.setContent(infowindow_html);
			infowindow<?php echo $this->activity_id; ?>.open(wpl_map<?php echo $this->activity_id; ?>, this);
			
            /** AJAX loader **/
			wplj(".map_search_ajax_loader").remove();
		}
	});
}

function wpl_load_markers<?php echo $this->activity_id; ?>(markers, delete_markers)
{
	if(delete_markers)
    {
        delete_markers<?php echo $this->activity_id; ?>();
        bounds<?php echo $this->activity_id; ?> = new google.maps.LatLngBounds();
    }
	
	for(var i = 0; i < markers.length; i++)
	{
		wpl_marker<?php echo $this->activity_id; ?>(markers[i]);
	}
    
	if(!markers.length && wpl_map_set_default_geo_point<?php echo $this->activity_id; ?>)
	{
		wpl_map<?php echo $this->activity_id; ?>.setCenter(new google.maps.LatLng(default_lt, default_ln));
		wpl_map<?php echo $this->activity_id; ?>.setZoom(parseInt(default_zoom));
	}
	else
	{
		/** now fit the map to the newly inclusive bounds **/
		if(wpl_map_bounds_extend<?php echo $this->activity_id; ?>) wpl_map<?php echo $this->activity_id; ?>.fitBounds(bounds<?php echo $this->activity_id; ?>);
	}
}

function get_infowindow_html<?php echo $this->activity_id; ?>(property_ids)
{
	var infowindow_html;
	
	wplj.ajax(
	{
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: 'wpl_format=c:functions:ajax&wpl_function=infowindow&property_ids='+property_ids+'&wpltarget=<?php echo wpl_request::getVar('wpltarget', 0); ?>',
		type: 'GET',
		async: false,
		cache: false,
		timeout: 30000,
		success: function(data)
		{
			infowindow_html = data;
		}
	});
	
	return infowindow_html;
}

function delete_markers<?php echo $this->activity_id; ?>()
{
	if(markers_array<?php echo $this->activity_id; ?>)
	{
		for(i=0; i < markers_array<?php echo $this->activity_id; ?>.length; i++) markers_array<?php echo $this->activity_id; ?>[i].setMap(null);
		markers_array<?php echo $this->activity_id; ?>.length = 0;
	}
	
	if(loaded_markers<?php echo $this->activity_id; ?>) loaded_markers<?php echo $this->activity_id; ?>.length = 0;
}

function wpl_Plisting_slider(i, total_images, id)
{
    images_total = total_images;
    
    if ((i+1)>=images_total) j=0; else j=i+1;
    if (j==i) return;
    
    wplj("#wpl_gallery_image"+ id +"_"+i).fadeTo(200,0).css("display",'none');
    wplj("#wpl_gallery_image"+ id +"_"+j).fadeTo(400,1);
}

/** Google places functions **/
function wpl_gplace_callback<?php echo $this->activity_id;?>(results, status)
{
	if(status == google.maps.places.PlacesServiceStatus.OK)
	{
		for(var i=0; i<results.length; i++) wpl_gplace_marker<?php echo $this->activity_id;?>(results[i]);
	}
}

function wpl_gplace_marker<?php echo $this->activity_id;?>(place)
{
	var placeLoc = place.geometry.location;
	var image = new google.maps.MarkerImage
    (
        place.icon,
        new google.maps.Size(51, 51),
        new google.maps.Point(0, 0),
        new google.maps.Point(17, 34),
        new google.maps.Size(25, 25)
    );

	// create place types title
	var title_str = '';
    
	for(var i=0; i<place.types.length; i++)
	{
		title_str = title_str+place.types[i];
		if((i+1) != place.types.length) title_str = title_str+', ';
	}
    
	var marker = new google.maps.Marker(
    {
		map: wpl_map<?php echo $this->activity_id; ?>,
		icon: image,
		title: title_str,
		position: place.geometry.location
	});
    
    /** extend the bounds to include each marker's position **/
  	bounds<?php echo $this->activity_id; ?>.extend(place.geometry.location);
    
	google.maps.event.addListener(marker, 'click', function()
	{
		infowindow<?php echo $this->activity_id; ?>.setContent('<div class="wpl_gplace_infowindow_container" style="color: #000000;">'+place.name+'</div>');
		infowindow<?php echo $this->activity_id; ?>.open(wpl_map<?php echo $this->activity_id; ?>, this);
	});
}

function wpl_load_map_markers(request_str, delete_markers)
{
    if(typeof delete_markers == 'undefined') delete_markers = false;
    
    /** AJAX loader **/
    wplj("#wpl_map_canvas<?php echo $this->activity_id; ?>").append('<div class="map_search_ajax_loader"><img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader4.gif'); ?>" /></div>');
    
    request_str = 'wpl_format=f:property_listing:raw&wplmethod=get_markers&'+request_str;
    var markers;
    
    wplj.ajax(
    {
        url: '<?php echo wpl_global::get_full_url(); ?>',
        data: request_str,
        type: 'GET',
        dataType: 'jSON',
        async: true,
        cache: false,
        timeout: 30000,
        success: function(data)
        {
            /** AJAX loader **/
            wplj(".map_search_ajax_loader").remove();
            
            /** Disable Map search **/
            if(typeof wpl_aps_freeze != 'undefined') wpl_aps_freeze = true;
            
            markers = data.markers;
            
            <?php foreach($map_activities as $activity): ?>
            wpl_load_markers<?php echo $activity->id; ?>(markers, delete_markers);
            <?php endforeach; ?>
        }
    });
}
</script>