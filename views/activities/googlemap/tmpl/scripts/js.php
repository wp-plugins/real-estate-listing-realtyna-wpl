<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var wpl_map;
var markers_array = new Array();
var loaded_markers = new Array();
var markers;
var bounds;
var infowindow;

if(typeof google_place_radius == 'undefined') var google_place_radius = 1100;

function wpl_initialize<?php echo $this->activity_id; ?>()
{
	/** create empty LatLngBounds object **/
	bounds = new google.maps.LatLngBounds();
	var mapOptions = {
		scrollwheel: false,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
    
	/** init map **/
	wpl_map = new google.maps.Map(document.getElementById('wpl_map_canvas<?php echo $this->activity_id; ?>'), mapOptions);
	infowindow = new google.maps.InfoWindow();
	
	/** load markers **/
	wpl_load_markers<?php echo $this->activity_id; ?>(markers);
	
    var styles =
    [
        {
            "featureType": "water",
            "stylers": [
                {
                    "color": "#46bcec"
                },
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "landscape",
            "stylers": [
                {
                    "color": "#f2f2f2"
                }
            ]
        },
        {
            "featureType": "road",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "lightness": 45
                }
            ]
        },
        {
            "featureType": "road.highway",
            "stylers": [
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "administrative",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#444444"
                }
            ]
        },
        {
            "featureType": "poi",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        }
    ];

    var styledMap = new google.maps.StyledMapType(styles, {name: "WPL Map"});

    wpl_map.mapTypes.set('map_style', styledMap);
    wpl_map.setMapTypeId('map_style');

    /* Check Google Places */
	if((typeof google_place != 'undefined') && (google_place == 1) )
	{
        var request = {
            location: marker.position,
            radius: google_place_radius
        };
  
		var service = new google.maps.places.PlacesService(wpl_map);
		service.search(request, wpl_gplace_callback<?php echo $this->activity_id; ?>);
	}
}

function wpl_marker<?php echo $this->activity_id; ?>(dataMarker)
{
	if(wplj.inArray(dataMarker.id, loaded_markers) != '-1') return true;
	
  	marker = new google.maps.Marker({
		position: new google.maps.LatLng(dataMarker.googlemap_lt, dataMarker.googlemap_ln),
		map: wpl_map,
		property_ids: dataMarker.pids,
		icon: '<?php echo wpl_global::get_wpl_url(); ?>assets/img/listing_types/gicon/'+dataMarker.gmap_icon,
		title: dataMarker.title,
	});
	
	/** extend the bounds to include each marker's position **/
  	bounds.extend(marker.position);
  
	loaded_markers.push(dataMarker.id);
  	markers_array.push(marker);
	
	google.maps.event.addListener(marker, "click", function(event)
	{
		if(this.html)
		{
			infowindow.close();
			infowindow.setContent(this.html);
			infowindow.open(wpl_map, this);
		}
		else
		{
			wplj("#wpl_map_canvas<?php echo $this->activity_id; ?>").append('<div class="map_search_ajax_loader" style="position: absolute; top: 7px; left: 70px; z-index: 200;"><img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader4.gif'); ?>" /></div>');
			
			infowindow_html = get_infowindow_html<?php echo $this->activity_id; ?>(this.property_ids);
			this.html = infowindow_html;
			infowindow.close();
			infowindow.setContent(infowindow_html);
			infowindow.open(wpl_map, this);
			
			wplj(".map_search_ajax_loader").remove();
		}
	});
}

function wpl_load_markers<?php echo $this->activity_id; ?>(markers, delete_markers)
{
	if(delete_markers) delete_markers<?php echo $this->activity_id; ?>();
	
	for(var i = 0; i < markers.length; i++)
	{
		wpl_marker<?php echo $this->activity_id; ?>(markers[i]);
	}
    
	wpl_map.setZoom(1);
    
	if(!markers.length)
	{
		wpl_map.setCenter(new google.maps.LatLng(default_lt, default_ln));
		wpl_map.setZoom(parseInt(default_zoom));
	}
	else
	{
		/** now fit the map to the newly inclusive bounds **/
		wpl_map.fitBounds(bounds);
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
	if(markers_array)
	{
		for(i=0; i < markers_array.length; i++) markers_array[i].setMap(null);
		markers_array.length = 0;
	}
	
	if(loaded_markers) loaded_markers.length = 0;
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
	var image = new google.maps.MarkerImage(
  	place.icon, new google.maps.Size(51, 51),
  	new google.maps.Point(0, 0), new google.maps.Point(17, 34),
  	new google.maps.Size(25, 25));

	// create place types title
	var title_str = '';
    
	for(var i=0; i<place.types.length; i++)
	{
		title_str = title_str+place.types[i];
		if((i+1) != place.types.length) title_str = title_str+', ';
	}
    
	var marker = new google.maps.Marker(
    {
		map: wpl_map,
		icon: image,
		title: title_str,
		position: place.geometry.location
	});
    
    /** extend the bounds to include each marker's position **/
  	bounds.extend(place.geometry.location);
    
	google.maps.event.addListener(marker, 'click', function()
	{
		infowindow.setContent('<div class="wpl_gplace_infowindow_container" style="color: #000000;">'+place.name+'</div>');
		infowindow.open(wpl_map, this);
	});
}
</script>