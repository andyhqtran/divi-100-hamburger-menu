<?php

/**
* @package Custom_Hamburger_Menu
* @version 0.0.1
*/

/*
* Plugin Name: Divi 100 Hamburger Menu
* Plugin URI: https://elegantthemes.com/
* Description: This plugin gives you the option to choose between 20 different hamburger menu variations for your header.
* Author: Elegant Themes
* Version: 0.0.1
* Author URI: http://elegantthemes.com/
* License: GPL3
*/

/**
 * Register plugin to Divi 100 list
 */
class ET_Divi_100_Custom_Hamburger_Menu_Config {
	public static $instance;

	/**
	 * Hook the plugin info into Divi 100 list
	 */
	function __construct() {
		add_filter( 'et_divi_100_settings', array( $this, 'register' ) );
		add_action( 'plugins_loaded',       array( $this, 'init' ) );
	}

	/**
	* Gets the instance of the plugin
	*/
	public static function instance(){
		if ( null === self::$instance ){
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Define plugin info
	 *
	 * @return array plugin info
	 */
	public static function info() {
		$main_prefix = 'et_divi_100_';
		$plugin_slug = 'custom_hamburger_menu';

		return array(
			'main_prefix'        => $main_prefix,
			'plugin_name'        => __( 'Custom Hamburger Menu' ),
			'plugin_description' => __( 'Nullam quis risus eget urna mollis ornare vel eu leo.' ),
			'plugin_slug'        => $plugin_slug,
			'plugin_id'          => "{$main_prefix}{$plugin_slug}",
			'plugin_prefix'      => "{$main_prefix}{$plugin_slug}-",
			'plugin_version'     => 20160301,
			'plugin_dir_path'    => plugin_dir_path( __FILE__ ),
		);
	}

	/**
	 * et_divi_100_settings callback
	 *
	 * @param array  settings
	 * @return array settings
	 */
	function register( $settings ) {
		$info = self::info();

		$settings[ $info['plugin_slug'] ] = $info;

		return $settings;
	}

	/**
	 * Init plugin after all plugins has been loaded
	 */
	function init() {
		// Load Divi 100 Setup
		require_once( plugin_dir_path( __FILE__ ) . 'divi-100-setup/divi-100-setup.php' );

		// Load Hamburger Menu
		ET_Divi_100_Custom_Hamburger_Menu::instance();
	}
}
ET_Divi_100_Custom_Hamburger_Menu_Config::instance();

/**
 * Load Custom Hamburger Menu
 */
class ET_Divi_100_Custom_Hamburger_Menu {
	/**
	 * Unique instance of plugin
	 */
	public static $instance;
	public $config;
	protected $settings;
	protected $utils;

	/**
	 * Gets the instance of the plugin
	 */
	public static function instance(){
		if ( null === self::$instance ){
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct(){
		$this->config   = ET_Divi_100_Custom_Hamburger_Menu_Config::info();
		$this->settings = maybe_unserialize( get_option( $this->config['plugin_id'] ) );
		$this->utils    = new Divi_100_Utils( $this->settings );

		// Initialize if Divi is active
		if ( et_divi_100_is_active() ) {
			$this->init();
		}
	}

	/**
	 * Hooking methods into WordPress actions and filters
	 *
	 * @return void
	 */
	private function init(){
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_filter( 'body_class',         array( $this, 'body_class' ) );

		if ( is_admin() ) {
			$settings_args = array(
				'plugin_id'       => $this->config['plugin_id'],
				'preview_dir_url' => plugin_dir_url( __FILE__ ) . 'preview/',
				'title'           => __( 'Custom Hamburger Menu' ),
				'description'     => __( 'Nullam quis risus eget urna mollis ornare vel eu leo.' ),
				'fields' => array(
					array(
						'type'              => 'select',
						'preview_prefix'    => 'type-',
						'has_preview'       => false,
						'id'                => 'type',
						'label'             => __( 'Select Type' ),
						'description'       => __( 'Proper description goes here' ),
						'options'           => $this->get_types(),
						'sanitize_callback' => 'sanitize_text_field',
					),
					array(
						'type'              => 'select',
						'preview_prefix'    => 'style-',
						'has_preview'       => false,
						'id'                => 'style',
						'label'             => __( 'Select Style' ),
						'description'       => __( 'Proper description goes here' ),
						'options'           => $this->get_styles(),
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'button_save_text' => __( 'Save Changes' ),
			);

			new Divi_100_Settings( $settings_args );

			// Add specific scripts for hamburger-menu plugin
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * Modify dashboard scripts
	 *
	 * @return void
	 */
	function enqueue_scripts() {
		if ( isset( $_GET['page'] ) && $this->config['plugin_id'] === $_GET['page'] ) {
			// Dequeue default scripts
			wp_dequeue_script( $this->config['plugin_id'] . '-admin_scripts' );

			// Enqueue hamburger menu specific scripts
			wp_enqueue_script( $this->config['plugin_id'] . '-admin_hamburger_menu_scripts', plugin_dir_url( __FILE__ ) . 'assets/js/admin-scripts.js', array( 'jquery' ), $this->config['plugin_version'], true );
			wp_localize_script( $this->config['plugin_id'] . '-admin_hamburger_menu_scripts', 'et_divi_100_js_params', apply_filters( 'et_divi_100_js_params', array(
				'preview_dir_url' => esc_url( plugin_dir_url( __FILE__ ) . 'assets/preview/' ),
			) ) );
		}
	}

	/**
	 * List of valid styles
	 * @return array
	 */
	function get_styles() {
		return apply_filters( $this->config['plugin_prefix'] . 'styles', array(
			''  => __( 'Default' ),
			'1' => __( 'One' ),
			'2' => __( 'Two' ),
			'3' => __( 'Three' ),
			'4' => __( 'Four' ),
			'5' => __( 'Five' ),
		) );
	}

	/**
	 * List of valid types
	 * @return array
	 */
	function get_types() {
		return apply_filters( $this->config['plugin_prefix'] . 'types', array(
			''  => __( 'Default' ),
			'1' => __( 'One' ),
			'2' => __( 'Two' ),
			'3' => __( 'Three' ),
			'4' => __( 'Four' ),
		) );
	}

	/**
	 * Add specific class to <body>
	 * @return array
	 */
	function body_class( $classes ) {
		// Get selected style
		$selected_style = $this->utils->get_value( 'style' );

		// Get selected type
		$selected_type = $this->utils->get_value( 'type' );

		// Assign specific class to <body> if needed
		if ( '' !== $selected_style ) {
			$classes[] = esc_attr(  $this->config['plugin_prefix'] . '-style-' . $selected_style );
		}

		if ( '' !== $selected_type ) {
			$classes[] = esc_attr(  $this->config['plugin_prefix'] . '-type-' . $selected_type );
		}

		return $classes;
	}

	/**
	 * Load front end scripts
	 * @return void
	 */
	function enqueue_frontend_scripts() {
		wp_enqueue_style( 'custom-hamburger-menus', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), $this->config['plugin_version'] );
		wp_enqueue_script( 'custom-hamburger-menus', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', array( 'jquery' ), $this->config['plugin_version'], true );
	}
}