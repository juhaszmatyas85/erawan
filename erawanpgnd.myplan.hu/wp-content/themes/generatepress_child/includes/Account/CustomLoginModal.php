<?php

namespace Erawan\Account;

use Erawan\Container;
use Erawan\Loader;

/**
 * Class CustomLoginModal
 *
 * Handles the custom login modal functionality.
 */
class CustomLoginModal {
	private $loader;
	private $script_loader;

	public function __construct( Container $container, Loader $loader ) {
		$this->script_loader = $container->get( 'script_loader' );
		$this->loader        = $loader;

		add_shortcode( 'custom_login_modal', array( $this, 'shortcode_callback' ) );
	}

	public function shortcode_callback() {
		if ( ! has_action( 'wp_footer', array( $this, 'render_modal' ) ) ) {
			add_action( 'wp_footer', array( $this, 'render_modal' ) );
		}
	}

	public function render_modal() {
		include ERAWAN_TEMPLATE_PATH . 'partials/custom-login-modal.php';
	}
}
