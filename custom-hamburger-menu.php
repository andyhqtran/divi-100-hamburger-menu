<?php

/**
* @package Custom_Hamburger_Menu
* @version 0.0.1
*/

/*
* Plugin Name: Custom Hamburger Menu
* Plugin URI: https://elegantthemes.com/
* Description: TODO
* Author: Andy Tran
* Version: 0.0.1
* Author URI: http://andy.design/
* License: GPL3
*/

/**
 * Load Divi 100 Setup
 */
require_once( plugin_dir_path( __FILE__ ) . 'divi-100-setup/divi-100-setup.php' );

/**
 * Load Custom Hamburger Menu
 */
class ET_Divi_100_Custom_Hamburger_Menu {
	/**
	 * Unique instance of plugin
	 */
	public static $instance;
	public $main_prefix;
	public $plugin_slug;
	public $plugin_prefix;

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
		$this->main_prefix   = 'et_divi_100_';
		$this->plugin_slug   = 'custom_hamburger_menu';
		$this->plugin_prefix = "{$this->main_prefix}{$this->plugin_slug}_";

		$this->init();
	}

	/**
	 * Hooking methods into WordPress actions and filters
	 *
	 * @return void
	 */
	private function init(){
		add_action( 'admin_menu',         array( $this, 'add_submenu' ), 30 ); // Make sure the priority is higher than Divi 100's add_menu()
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_filter( 'body_class',         array( $this, 'body_class' ) );
	}

	/**
	 * Add submenu
	 * @return void
	 */
	function add_submenu() {
		add_submenu_page(
			$this->main_prefix . 'options',
			__( 'Custom Hamburger Menu' ),
			__( 'Custom Hamburger Menu' ),
			'switch_themes',
			$this->plugin_prefix . 'options',
			array( $this, 'render_options_page' )
		);
	}

	/**
	 * Render options page
	 * @return void
	 */
	function render_options_page() {
		$is_option_updated         = false;
		$is_option_updated_success = false;
		$is_option_updated_message = '';
		$hamburger_menu_name       = 'hamburger-menu-style';
		$nonce_action              = $this->plugin_prefix . 'options';
		$nonce                     = $this->plugin_prefix . 'options_nonce';

		// Verify whether an update has been occured
		if ( isset( $_POST[ $hamburger_menu_name ] ) && isset( $_POST[ $nonce ] ) ) {
			$is_option_updated = true;

			// Verify nonce. Thou shalt use correct nonce
			if ( wp_verify_nonce( $_POST[ $nonce ], $nonce_action ) ) {

				// Verify input
				if ( in_array( $_POST[$hamburger_menu_name], array_keys( $this->get_styles() ) ) ) {
					// Update option
					update_option( $this->plugin_prefix . 'styles', sanitize_text_field( $_POST[ $hamburger_menu_name ] ) );

					// Update submission status & message
					$is_option_updated_message = __( 'Your setting has been updated.' );
					$is_option_updated_success = true;
				} else {
					$is_option_updated_message = __( 'Invalid submission. Please try again.' );
				}
			} else {
				$is_option_updated_message = __( 'Error authenticating request. Please try again.' );
			}
		}

		?>
		<div class="wrap">
			<h1><?php _e( 'Custom Hamburger Menu' ); ?></h1>

			<?php if ( $is_option_updated ) { ?>
				<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible <?php echo $is_option_updated_success ? '' : 'error' ?>">
					<p>
						<strong><?php echo esc_html( $is_option_updated_message ); ?></strong>
					</p>
					<button type="button" class="notice-dismiss">
						<span class="screen-reader-text"><?php _e( 'Dismiss this notice.' ); ?></span>
					</button>
				</div>
			<?php } ?>

			<form action="" method="POST">
				<p><?php _e( 'Proper description goes here Nullam id dolor id nibh ultricies vehicula ut id elit. Vestibulum id ligula porta felis euismod semper. Nullam id dolor id nibh ultricies vehicula ut id elit. Vestibulum id ligula porta felis euismod semper.' ); ?></p>

				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="hamburger-menu-style"><?php _e( 'Select Style' ); ?></label>
							</th>
							<td>
								<select name="hamburger-menu-style" id="hamburger-menu-style">
									<?php
									// Get saved style
									$csf_style = $this->get_selected_style();

									// Render options
									foreach ( $this->get_styles() as $style_id => $style ) {
										printf(
											'<option value="%1$s" %3$s>%2$s</option>',
											esc_attr( $style_id ),
											esc_html( $style ),
											$csf_style === $style_id ? 'selected="selected"' : ''
										);
									}
									?>
								</select>
								<p class="description"><?php _e( 'Proper description goes here' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- /.form-table -->

				<?php wp_nonce_field( $nonce_action, $nonce ); ?>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>">
				</p>
				<!-- /.submit -->

			</form>
		</div>
		<!-- /.wrap -->
		<?php
	}

	/**
	 * List of valid styles
	 * @return void
	 */
	function get_styles() {
		return apply_filters( $this->plugin_prefix . 'styles', array(
			''      => __( 'Default' ),
			'one'   => __( 'One' ),
			'two'   => __( 'Two' ),
			'three' => __( 'Three' ),
			'four'  => __( 'Four' ),
		) );
	}

	/**
	 * Get selected style
	 * @return string
	 */
	function get_selected_style() {
		$csf_style = get_option( $this->plugin_prefix . 'styles', '' );

		return apply_filters( $this->plugin_prefix . 'get_selected_style', $csf_style );
	}

	/**
	 * Add specific class to <body>
	 * @return array
	 */
	function body_class( $classes ) {
		// Get selected style
		$selected_style = $this->get_selected_style();

		// Assign specific class to <body> if needed
		if ( '' !== $selected_style ) {
			$classes[] = sanitize_title(  $this->plugin_prefix . $selected_style );
		}

		return $classes;
	}

	/**
	 * Load front end scripts
	 * @return void
	 */
	function enqueue_frontend_scripts() {
		wp_enqueue_style( 'custom-hamburger-menus', plugin_dir_url( __FILE__ ) . 'css/style.css' );
		wp_enqueue_script( 'custom-hamburger-menus', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array( 'jquery' ), '0.0.1', true );
	}
}
ET_Divi_100_Custom_Hamburger_Menu::instance();