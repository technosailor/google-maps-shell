=== Plugin Name ===
Contributors: technosailor
Tags: google-maps, maps
Requires at least: 3.4
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is meant to be used in conjunction with external code that provides necessary coordinates to the plugin.

== Description ==

This plugin is for developers and is meant to be used to enable Google Maps integration. While it does come with a default location, developers will want to feed the plugin a nested array of location data. This data can be manually curated or retrieved through external means.

You need to use the filter `ab_google_maps_markers` and return the new nested array.

Example:

`function new_array( $markers ) {
	$markers = (object) array( 
		array( 
			'lat' => '30.266132', 
			'lon' => '-97.745825',
			'title' => 'Gingerman Austin',
			'address' => '301 Lavaca St, Austin TX 78701'
		),
		array(
			'lat' => '30.2658',
			'lon' => '-97.735691',
			'title' => 'Easy Tiger Bake Shop and Beer Garden',
			'address' => '709 East 6th St, Austin TX 78701'
		),
	);
	return $markers;
}
add_filter( 'ab_google_maps_markers', 'new_array' );`

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Get a Google Maps API Key from the <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key">Google Developers Console</a>
1. Add `define( 'AB_GMAPS_API_KEY', 'foo');` to your `wp-config.php` file where `foo` is the API Key from Google.

== Changelog ==

= 1.0 =
* Initial Release

