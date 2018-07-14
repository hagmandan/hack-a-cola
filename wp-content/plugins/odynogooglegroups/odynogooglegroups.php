<?php
/*
Plugin Name: Odyno GoogleGroups
Plugin URI:  http://www.staniscia.net/odynogooglegroups/
Description: The <a href="http://www.staniscia.net/odynogooglegroups/" target="_new">Odyno GoogleGroups</a> embed the Google Groups on WordPress! You can see all discussion on your article or WordPress page. All you must do is to add a shortcode on your page editor! The main feautures are:<br> 1) Google Group forum on WordPress page/post <br>2)Widget with last messages of group.
Requires at least: 3.4.2
Tested up to: 4.7
Author: Alessandro Staniscia
Author URI: http://www.staniscia.net
Text Domain: odynogooglegroups
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path: /languages
Version: 0.0.10
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


define('ODY_GOOGLE_GROUPS_NAME', 'Odyno GoogleGroups');
define('ODY_GOOGLE_GROUPS_VERSION', '0.0.10');
define('ODY_GOOGLE_GROUPS_DIR', plugin_dir_path(__FILE__));
define('ODY_GOOGLE_GROUPS_URL', plugins_url()."/odynogooglegroups");
define('ODY_GOOGLE_GROUPS_FILE', ABSPATH . PLUGINDIR . '/odynogooglegroups/odynogooglegroups.php');
define('ODY_GOOGLE_GROUPS_NOS', '____NULL___');
define('_ODY_GOOGLE_GROUPS_FILE_', __FILE__);

define('OGG_START_DEBUG_TAG', " -- OGG START SECTION -- ");
define('OGG_STOP_DEBUG_TAG', " -- OGG END SECTION -- ");


if( !defined( 'ODY_GOOGLE_GROUPS_VERSION' ) )
	define( 'ODY_GOOGLE_GROUPS_VERSION', '0.0.10' );


define('ODY_GG_SHOW_SIGNE', 'ody_gg_show_signe');
define('ODY_GG_ENABLED_ANALITYC', 'ody_gg_enabled_analityc');



// Start up the engine
class Odyno_Google_Groups
{
	/**
	 * Static property to hold our singleton instance
	 *
	 */
	static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return void
	 */
	private function __construct() {
		// Load dependencies.
		$this->load_dependencies();

		// back end
		add_action('plugins_loaded', 	array( $this, 'textdomain'));
		add_action('widgets_init',      array( $this, 'add_widget'));


		add_shortcode( 'google_groups', array( $this, 'add_google_groups_shortcode'));
		register_activation_hook( _ODY_GOOGLE_GROUPS_FILE_, array( $this, 'on_activation' ) );
		register_deactivation_hook( _ODY_GOOGLE_GROUPS_FILE_, array( $this, 'on_deactivation' ) );
	}

	/**
	 *
	 * Load the dependencies of this plugin.
	 */
	private function load_dependencies() {

		// load Dependecy
		require_once( ODY_GOOGLE_GROUPS_DIR . '/admin/ogg-settings.php' );
		require_once( ODY_GOOGLE_GROUPS_DIR . '/include/odyno-google-groups-widget.php' );
		new Ogg_Settings();
	}


	public function add_widget() {
		register_widget('ODYNO_Google_Groups_Widget');
	}





	public static function getInstance() {
		if ( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}


	/**
	 * Installation. Runs on activation.
	 */
	public function on_activation() {
		update_option(ODY_GG_SHOW_SIGNE, true);
		update_option(ODY_GG_ENABLED_ANALITYC, true);
	}

	/**
	 * Deactivation Function
	 */
	public function on_deactivation() {
		delete_option(ODY_GG_SHOW_SIGNE);
		delete_option(ODY_GOOGLE_GROUPS_ANALYTICS);
	}

	/**
	 * load textdomain
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain('odynogooglegroups', FALSE, dirname(plugin_basename( __FILE__ )) . '/languages/');
	}


	public function add_google_groups_shortcode($param , $content = null){

		extract(shortcode_atts(array(
			'id' => uniqid("", true),
			'name' => ODY_GOOGLE_GROUPS_NOS,
			'width' => '100%',
			'height' => '800px',
			'showsearch' => 'false',
			'showtabs' => 'false',
			'hideforumtitle' => 'true',
			'hidesubject' => 'true',
			'domain' => ODY_GOOGLE_GROUPS_NOS
		), $param));

		do_action("pre_ogg_shortcode", $name, $id, $width, $height, $showsearch, $showtabs, $hideforumtitle, $hidesubject, $domain);

		$out=odyno_google_groups_get_page($name, $id, $width, $height, $showsearch, $showtabs, $hideforumtitle, $hidesubject, $domain);

		do_action("post_ogg_shortcode", $name, $id, $width, $height, $showsearch, $showtabs, $hideforumtitle, $hidesubject, $domain);

		return $out;
	}


/// end class
}


add_action('send_headers', 'add_ogg_header_x_frame_options');
function add_ogg_header_x_frame_options()
{
	header('X-Frame-Options: Allow-From https://groups.google.com');
}

include_once ODY_GOOGLE_GROUPS_DIR.'/odyno-google-groups-lib.php';

// Instantiate our class
$OGG_Odyno_Google_Groups = Odyno_Google_Groups::getInstance();




?>
