<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.decem.co
 * @since      1.0.0
 *
 * @package    Localcf7
 * @subpackage Localcf7/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Localcf7
 * @subpackage Localcf7/includes
 * @author     Decem <info@decem.co>
 */
class Localcf7 {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Localcf7_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	public $localCF7TableObj;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LOCALCF7_VERSION' ) ) {
			$this->version = LOCALCF7_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'localcf7';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();


		// Add Admin menu tab for LocalCF7
		add_action('admin_menu', array($this, 'add_menu_page_local_CF7'));
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Localcf7_Loader. Orchestrates the hooks of the plugin.
	 * - Localcf7_i18n. Defines internationalization functionality.
	 * - Localcf7_Admin. Defines all hooks for the admin area.
	 * - Localcf7_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-localcf7-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-localcf7-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-localcf7-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-localcf7-public.php';

		$this->loader = new Localcf7_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Localcf7_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Localcf7_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Localcf7_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Localcf7_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		add_action('wpcf7_before_send_mail', array(&$this, 'saveFormData'));
	}

	// Add Menu Page
    public function add_menu_page_local_CF7() {        
        add_menu_page(
            __('LocalCF7 Page','localCF7-menupage'), // Page title
            __('LocalCF7','localCF7-menu'), // Menu title
            'manage_options', //capability
            'local-cf7-menu', //menu_slug
            array($this, 'toplevel_page'), //function
            'dashicons-buddicons-activity' //icon url
        );
	}
	
	// Displays the page content for the custom Toplevel menu
    public function toplevel_page() {
        echo "<h2>" . __( 'Welcome to LocalCF7 page', 'myplugin-menu' ) . "</h2>";
		
		$data = $this->get_localCF7_form_data();

		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		require_once( plugin_dir_path( __FILE__ ) . '/class-localcf7-table.php' );

		$this->localCF7TableObj = new LocalCF7Table();

		$this->localCF7TableObj->prepare_items();
		$this->localCF7TableObj->display();
	}
	
	public function get_localCF7_form_data() {
		global $wpdb;

		$table_name = $wpdb->prefix . "LocalCF7";

		$data = $wpdb->get_results( "SELECT * FROM $table_name" );

		return $data;
	}

	/**
     * Callback from Contact Form 7. CF7 passes an object with the posted data which is inserted into the database
     * by this function.
     * @param $cf7 WPCF7_ContactForm
     * @return bool
     */
    public function saveFormData($cf7) {
        try {
			global $wpdb;
			$data = $this->convertData($cf7);

			// $data->posted_data <- ovde se nalaze svi inputi

			$wpdb->insert('wp_LocalCF7', array(
				'title' => $data->title,
				'postedAt' => current_time('mysql'),
				'formData' => serialize($data->posted_data)
			));
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return true;
	}
	
	/**
	* @param $cf7 WPCF7_ContactForm
	* @return object
	*/
    public function convertData($cf7) {
        if (!isset($cf7->posted_data) && class_exists('WPCF7_Submission')) {
            // Contact Form 7 version 3.9 removed $cf7->posted_data and now
            // we have to retrieve it from an API
            $submission = WPCF7_Submission::get_instance();
            if ($submission) {
                $data = array();
                $data['title'] = $cf7->title();
                $data['posted_data'] = $submission->get_posted_data();
                $data['uploaded_files'] = $submission->uploaded_files();
                return (object) $data;
            }
        }
        return $cf7;
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Localcf7_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
