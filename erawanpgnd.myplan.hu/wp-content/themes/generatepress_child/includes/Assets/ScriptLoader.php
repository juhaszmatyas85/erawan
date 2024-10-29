<?php

namespace Erawan\Assets;

use Erawan\Container;
use Erawan\Loader;

class ScriptLoader {
	private $container;
	private $loader;

	private $scripts = array(
		'app'                => array(
			'path'      => 'js/app/app.js',
			'deps'      => array( 'jquery' ),
			'in_footer' => true,
			'enqueue'   => true,
		),
		'hystmodal'          => array(
			'path'      => 'js/vendor/hystmodal.js',
			'deps'      => array( 'jquery' ),
			'in_footer' => true,
		),
		'custom-login-modal' => array(
			'path'      => 'js/custom-login-modal.js',
			'deps'      => array( 'jquery', 'hystmodal' ),
			'in_footer' => true,
		),
	);

	public function __construct( Container $container, Loader $loader ) {
		$this->container = $container;
		$this->loader    = $loader;

		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'register_scripts' );
	}

	public function register_scripts() {
		foreach ( $this->scripts as $handle => $script ) {
			$file_path = ERAWAN_PATH . $script['path'];

			if ( file_exists( $file_path ) ) {
				wp_register_script(
					$handle,
					ERAWAN_URL . $script['path'],
					$script['deps'],
					filemtime( $file_path ),
					$script['in_footer']
				);
				if ( $script['enqueue'] ) {
					wp_enqueue_script( $handle );
				}
			}
		}
	}

	public function enqueue_script( $handle ) {
		if ( wp_script_is( $handle, 'registered' ) ) {
			wp_enqueue_script( $handle );
		}
	}
}
