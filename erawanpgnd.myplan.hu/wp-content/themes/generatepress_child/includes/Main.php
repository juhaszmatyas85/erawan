<?php

namespace Erawan;

class Main {
	protected $plugin_name;
	protected $loader;
	protected $container;

	public function __construct() {
		$this->plugin_name = 'erawan';

		$this->load_dependencies();
		$this->includes();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return void
	 */
	private function load_dependencies() {
		$this->container = new Container();
		$this->loader    = new Loader();
	}

	/**
	 * Include the required files.
	 *
	 * The called classes either operate independently or their methods can be hooked into
	 * the define_admin_hooks() or define_public_hooks() methods here.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function includes() {
		// The StyleLoader instance is responsible for enqueueing the styles for the plugin.
		$this->container->set( 'style_loader', new \Erawan\Assets\StyleLoader( $this->container, $this->loader ) );

		// The ScriptLoader instance is responsible for enqueueing the scripts for the plugin.
		$this->container->set( 'script_loader', new \Erawan\Assets\ScriptLoader( $this->container, $this->loader ) );

		// The BlocksManager instance is responsible for registering the blocks for the plugin.
		$this->container->set( 'blocks_manager', new \Erawan\Blocks\BlocksManager( $this->container, $this->loader ) );

		// Custom login modal.
		$this->container->set( 'custom_login_modal', new \Erawan\Account\CustomLoginModal( $this->container, $this->loader ) );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$style_loader  = $this->container->get( 'style_loader' );
		$script_loader = $this->container->get( 'script_loader' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}

	public function get_container() {
		return $this->container;
	}
}
