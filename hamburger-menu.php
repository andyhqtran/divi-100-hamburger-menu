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
			'plugin_name'        => __( 'Hamburger Menu' ),
			'plugin_slug'        => $plugin_slug,
			'plugin_id'          => "{$main_prefix}{$plugin_slug}",
			'plugin_prefix'      => "{$main_prefix}{$plugin_slug}-",
			'plugin_version'     => 20160602,
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
 * Load Hamburger Menu
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
				'plugin_slug'     => $this->config['plugin_slug'],
				'preview_dir_url' => plugin_dir_url( __FILE__ ) . 'preview/',
				'title'           => __( 'Hamburger Menu' ),
				'fields'          => $this->settings_fields(),
				'button_save_text' => __( 'Save Changes' ),
			);

			new Divi_100_Settings( $settings_args );

			// Add specific scripts for hamburger-menu plugin
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	private function settings_fields() {
		return array(
			'type' => array(
				'type'              => 'select',
				'preview_prefix'    => 'type-',
				'has_preview'       => false,
				'id'                => 'type',
				'label'             => __( 'Select Type' ),
				'description'       => __( 'This type will be used on the hamburger menu' ),
				'options'           => $this->get_types(),
				'sanitize_callback' => 'sanitize_text_field',
			),
			'style' => array(
				'type'              => 'select',
				'preview_prefix'    => 'style-',
				'has_preview'       => false,
				'id'                => 'style',
				'label'             => __( 'Select Style' ),
				'description'       => __( 'This style will be used on the hamburger menu' ),
				'options'           => $this->get_styles(),
				'sanitize_callback' => 'sanitize_text_field',
			),
			'default-color' => array(
				'type'                 => 'color',
				'id'                   => 'default-color',
				'label'                => __( 'Select Default Color' ),
				'description'          => __( 'The color you choose will be used as color on the blocks of hamburger menu' ),
				'sanitize_callback'    => 'et_divi_100_sanitize_alpha_color',
				'default'              => '#000000',
			),
			'active-color' => array(
				'type'                 => 'color',
				'id'                   => 'active-color',
				'label'                => __( 'Select Active Color' ),
				'description'          => __( 'The color you choose will be used as color on the blocks of hamburger menu when it is being clicked' ),
				'sanitize_callback'    => 'et_divi_100_sanitize_alpha_color',
				'default'              => '#000000',
			),
		);
	}

	/**
	 * Modify dashboard scripts
	 *
	 * @return void
	 */
	function enqueue_scripts() {
		if ( isset( $_GET['page'] ) && ( $this->config['plugin_id'] === $_GET['page'] || ( 'et_divi_100_options' === $_GET['page'] && et_divi_100_get_most_updated_plugin_slug() === $this->config['plugin_slug'] ) ) ) {
			// Dequeue default scripts
			wp_dequeue_script( $this->config['plugin_id'] . '-admin_scripts' );

			// Enqueue hamburger menu specific scripts
			wp_enqueue_script( $this->config['plugin_id'] . '-admin_hamburger_menu_scripts', plugin_dir_url( __FILE__ ) . 'assets/js/admin-scripts.js', array( 'jquery', 'iris' ), $this->config['plugin_version'], true );
			wp_localize_script( $this->config['plugin_id'] . '-admin_hamburger_menu_scripts', 'et_divi_100_js_params', apply_filters( 'et_divi_100_js_params', array(
				'preview_dir_url' => esc_url( plugin_dir_url( __FILE__ ) . 'assets/preview/' ),
				'help_label'      => esc_html__( 'Help' ),
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
		if ( '' !== $selected_style && '' !== $selected_type ) {
			$classes[] = esc_attr(  $this->config['plugin_id'] );
		}

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

		// Get plugin settings
		$settings             = $this->settings_fields();

		// Add custom default color
		$default_color_default  = $settings['default-color']['default'];
		$default_color          = $this->utils->get_value( 'default-color', $default_color_default );
		$default_color_selector = ( '2' === $this->utils->get_value( 'type', '' ) || '3' === $this->utils->get_value( 'type', '' ) ) ?
		'body.et_divi_100_custom_hamburger_menu .et_divi_100_custom_hamburger_menu__icon div:before,
		body.et_divi_100_custom_hamburger_menu .et_divi_100_custom_hamburger_menu__icon div:after' :
		'body.et_divi_100_custom_hamburger_menu .et_divi_100_custom_hamburger_menu__icon div';

		if ( $default_color && $default_color !== $default_color_default ) {
			$custom_default_color_css = sprintf(
				'%1$s {
					background: %2$s;
				}',
				$default_color_selector,
				et_divi_100_sanitize_alpha_color( $default_color )
			);
			wp_add_inline_style( 'custom-hamburger-menus', $custom_default_color_css );
		}

		// Add custom active color
		$active_color_active  = $settings['active-color']['default'];
		$active_color          = $this->utils->get_value( 'active-color', $active_color_active );
		$active_color_selector = ( '2' === $this->utils->get_value( 'type', '' ) || '3' === $this->utils->get_value( 'type', '' ) ) ?
		'body.et_divi_100_custom_hamburger_menu .et_divi_100_custom_hamburger_menu__icon.et_divi_100_custom_hamburger_menu__icon--toggled div:before,
		body.et_divi_100_custom_hamburger_menu .et_divi_100_custom_hamburger_menu__icon.et_divi_100_custom_hamburger_menu__icon--toggled div:after' :
		'body.et_divi_100_custom_hamburger_menu .et_divi_100_custom_hamburger_menu__icon.et_divi_100_custom_hamburger_menu__icon--toggled div';

		if ( $active_color && $active_color !== $active_color_active ) {
			$custom_active_color_css = sprintf(
				'%1$s {
					background: %2$s;
				}',
				$active_color_selector,
				et_divi_100_sanitize_alpha_color( $active_color )
			);
			wp_add_inline_style( 'custom-hamburger-menus', $custom_active_color_css );
		}
	}
}