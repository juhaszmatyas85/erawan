<?php

namespace Erawan\Assets;

class StyleLoader {
	private $container;
	private $loader;

	private $styles = array(
		'erawan'    => array(
			'path'    => 'erawan.css',
			'deps'    => array(),
			'media'   => 'all',
			'enqueue' => true,
		),
		'hystmodal' => array(
			'path'  => 'css/vendor/hystmodal.css',
			'deps'  => array(),
			'media' => 'all',
		),
	);

	public function __construct( $container, $loader ) {
		$this->container = $container;
		$this->loader    = $loader;

		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'register_styles' );
	}

	public function register_styles() {
		foreach ( $this->styles as $handle => $style ) {
			$file_path = ERAWAN_PATH . $style['path'];

			if ( file_exists( $file_path ) ) {
				wp_register_style(
					$handle,
					ERAWAN_URL . $style['path'],
					$style['deps'] ?? array(),
					filemtime( $file_path ),
					$style['media'] ?? 'all'
				);

				if ( isset( $style['enqueue'] ) && $style['enqueue'] ) {
					wp_enqueue_style( $handle );
				}
			}
		}
	}

	public function enqueue_style( $handle ) {
		if ( wp_style_is( $handle, 'registered' ) ) {
			wp_enqueue_style( $handle );
		}
	}
}
