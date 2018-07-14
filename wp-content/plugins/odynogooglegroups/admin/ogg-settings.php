<?php
/**
 * Created by PhpStorm.
 * User: astaniscia
 * Date: 30/07/16
 * Time: 11:27
 */

if ( ! class_exists( 'Ogg_Settings' ) ) {

	define('ODY_GOOGLE_GROUPS_SETTINGS_PAGE', 'OdynoGoogleGroupsPreference');
	define('ODY_GG_SETTINGS', 'ody_gg_settings');
	
	

	class Ogg_Settings {


		/**
		 * This is our constructor
		 *
		 * @return void
		 */
		public function __construct() {
			add_action      ( 'admin_init',                array( $this, 'register_settings_sections'  )     );
			add_action      ( 'admin_init',                array( $this, 'register_settings_style')     );
			add_action		( 'admin_menu', 			   array( $this, 'add_menu_settings_voice') 	);
			add_action		( 'plugin_action_links',       array( $this, 'add_setting_link'		  ),  2,   2  );

		}

		public function add_menu_settings_voice() {

			// Add new admin menu and save     returned page hook
			$hook_suffix = add_options_page(
				'Odyno gGroups Preference', // page Title
				'Odyno gGroups', // menu Link
				'manage_options', //Capability
				ODY_GOOGLE_GROUPS_SETTINGS_PAGE, //ID
				array( $this, 'get_HTML_OGG_Setting_Page')

			);

			//ADD CSS
			add_action('admin_print_styles-' . $hook_suffix, array( $this,'load_style'));
		}
		
		
		
		public function register_settings_style(){
			wp_register_style('odyno-google-group-style', ODY_GOOGLE_GROUPS_URL . "/admin/css/odyno-google-group-style.css");
		}
		
		
		public function load_style(){
			wp_enqueue_style('odyno-google-group-style');
		}

		public function add_setting_link($actions, $file){
			if(false !== strpos($file, 'odynogooglegroups')){
				$actions['settings'] = '<a href="options-general.php?page='.ODY_GOOGLE_GROUPS_SETTINGS_PAGE.'">Settings</a>';
			}
			return $actions;
		}
		

		public function register_settings_sections(){
			$this->add_section_description();
			$this->add_section_adv();

		}

		public function add_section_description(){
			//Build new Section
			add_settings_section(
				ODY_GG_SETTINGS . "_DESCRIPTION",                 //String for use in the 'id' attribute of tags.
				__('','odynogooglegroups'),                      //Title of the section
				array( $this, 'get_HTML_Description_Page'),      //Function that fills the section with the desired content. The function should echo its output.
				ODY_GOOGLE_GROUPS_SETTINGS_PAGE                  //The type of settings page on which to show the section
			);
		}


		public function add_section_adv(){
			//Build new Section
			add_settings_section(
				ODY_GG_SETTINGS . "_ADV",                 //String for use in the 'id' attribute of tags.
				__('Advertise','odynogooglegroups'),                              //Title of the section
				array( $this, 'get_HTML_ADV_description'),  //Function that fills the section with the desired content. The function should echo its output.
				ODY_GOOGLE_GROUPS_SETTINGS_PAGE           //The type of settings page on which to show the section
			);

			add_settings_field(
				ODY_GG_SHOW_SIGNE,                        //String for use in the 'id' attribute of tags.
				__('Show a little and invisible sign of this plugin ( Thanks! )','odynogooglegroups'),           // Title of the field.
				array( $this, 'get_HTML_field_Signe'),          //Function that fills the field with the desired inputs as part of the larger form. Name and id of the input should match the $id given to this function. The function should echo its output.
				ODY_GOOGLE_GROUPS_SETTINGS_PAGE,          //The type of settings page on which to show the field
				ODY_GG_SETTINGS . "_ADV"                  //The section of the settings page in which to show the box (default or a section you added with add_settings_section, look at the page in the source to see what the existing ones are.
			);

			add_settings_field(
				ODY_GG_ENABLED_ANALITYC,
				__('Enable the tracking of this WordPress installation with anonymous data.','odynogooglegroups'),
				array( $this, 'get_HTML_field_Analitycs'),
				ODY_GOOGLE_GROUPS_SETTINGS_PAGE,
				ODY_GG_SETTINGS . "_ADV"
			);

			register_setting(ODY_GG_SETTINGS, ODY_GG_SHOW_SIGNE);
			register_setting(ODY_GG_SETTINGS, ODY_GG_ENABLED_ANALITYC);
		}



		/**
		 * Description of option section
		 */
		function get_HTML_ADV_description() {
			echo "";
		}

		/**
		 * Function that fills the field with the desired inputs as part of the larger form.
		 * Name and id of the input should match the $id given to this function.
		 * The function should echo its output.
		 */
		function get_HTML_field_Signe() {
			echo ' <input name="'.ODY_GG_SHOW_SIGNE.'" type="checkbox" value="1"  class="code" ' . checked( 1, get_option(ODY_GG_SHOW_SIGNE), false ) . ' />';
		}

		function get_HTML_field_Analitycs() {
			echo ' <input name="'.ODY_GG_ENABLED_ANALITYC.'" type="checkbox" value="1"  class="code" ' . checked( 1, get_option(ODY_GG_ENABLED_ANALITYC), false ) . ' />';
		}


		function get_HTML_Description_Page() {
			include(ODY_GOOGLE_GROUPS_DIR . '/admin/views/html-promote.inc.php');
		}

		function get_HTML_OGG_Setting_Page() {
			include(ODY_GOOGLE_GROUPS_DIR . '/admin/views/html-settings-page.inc.php');
		}


	}

}