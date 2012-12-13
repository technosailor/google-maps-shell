<?php
/*
Plugin Name: Google Maps Shell
Description: This plugin is meant to be used in conjunction with external code that provides necessary coordinates to the plugin using the ab_google_maps_markers filter
Author: Aaron Brazell
Version: 1.0
Author URI: http://technosailor.com
*/

class AB_GoogleMaps {

	var $gmaps_api_url;
	var $gmaps_api_key;

	public function __construct( $api_key = null )
	{
		if( is_null( $api_key ) && !defined( 'AB_GMAPS_API_KEY' ) )
			return false;

		$this->gmaps_api_key = AB_GMAPS_API_KEY ? AB_GMAPS_API_KEY : $api_key;
		$this->gmaps_api_url = 'http://maps.googleapis.com/maps/api/staticmap';

		$this->hooks();
	}

	public function hooks()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'js' ) );
	}

	public function js()
	{
		wp_enqueue_script('gmaps', 'http://maps.googleapis.com/maps/api/js?&sensor=false&key=' . $this->gmaps_api_key, array( 'jquery') );
	}

	public function display_map( $width = 300, $height = 300, $zoom = 16, $objects = array() )
	{					
		$markers = array();
		foreach( $objects as $obj )
		{
			$obj = (object) $obj;
			$markers[] = array(
				'lat' 		=> $obj->lat,
				'lon' 		=> $obj->lon,
				'title'		=> $obj->title,
				'address'	=> $obj->address,
			);
			$center_lat = $obj->lat;
			$center_lon = $obj->lon;
		}
		$tlat = 0;
		$tlon = 0;
		foreach( $markers as $coords )
		{
			$tlat = $tlat + $coords['lat'];
			$tlon = $tlon + $coords['lon'];
		}
		?>
		<script>
			jQuery(document).ready(function(){
				jQuery('body').attr( 'onload', 'initialize()' );
			});
			var map;
			
			function initialize() {
				var mapOptions = {
					zoom: <?php echo $zoom ?>,
					center: new google.maps.LatLng(<?php echo $center_lat ?>, <?php echo $center_lon ?>),
					mapTypeId: google.maps.MapTypeId.ROADMAP,
				}
				map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
				add_office();
			}
			
			function add_office() {
				<?php
				foreach( $markers as $marker )
				{
					$marker = (object) $marker;
					?>
					marker = new google.maps.Marker(
					{ 
						position: new google.maps.LatLng(<?php echo $marker->lat ?>, <?php echo $marker->lon ?>), 
						map: map 
					} );
					
					var infowindow = new google.maps.InfoWindow();
					
					google.maps.event.addListener(marker, 'click', (function(marker) {
						return function() {
							infowindow.setContent('<strong><?php echo $marker->title ?></strong><p><?php echo $address ?>');
							infowindow.open(map, marker);
						}
					})(marker));
					
					<?php
				}
				?>
			}
			
		</script>
		<div id="map_canvas" style="height:<?php echo $height; ?>px; width:<?php echo $width ?>px;"></div>
		<?php
	}
}

$ab_maps = new AB_GoogleMaps;

function ab_display_gmap( $width = 300, $height = 300, $zoom = 12 )
{
	global $ab_maps;
	$markers = (object) array( 
		'location1' => array( 
			'lat' => '30.266132', 
			'lon' => '-97.745825',
			'title' => 'Gingerman Austin',
			'address' => '301 Lavaca St, Austin TX 78701'
		)
	);
	$markers = apply_filters( 'ab_google_maps_markers', $markers );
	$gmaps_markers = array();
	foreach( $markers as $marker )
	{
		$marker = (object) $marker;
		$gmaps_markers[] = array( 
			'lat' => $marker->lat, 
			'lon' => $marker->lon, 
			'title' => $marker->title,
			'address' => $marker->address 
		);
	}
	$ab_maps->display_map( $width, $height, $zoom, $gmaps_markers );
}