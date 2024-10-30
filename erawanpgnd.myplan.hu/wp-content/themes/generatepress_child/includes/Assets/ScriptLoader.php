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
			'type'      => 'module',
		),
		'app-legacy'         => array(
			'path'      => 'js/app/app-legacy.js',
			'deps'      => array( 'jquery' ),
			'in_footer' => true,
			'enqueue'   => true,
			'type'      => 'nomodule',
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
		'slick' => array(
			'path'      => 'js/vendor/slick.js',
			'deps'      => array( 'jquery'),
			'in_footer' => true,
		),
	);

	public function __construct( Container $container, Loader $loader ) {
		$this->container = $container;
		$this->loader    = $loader;

		$this->loader->add_action( 'wp_print_scripts', $this, 'print_theme_data' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'register_scripts' );
		$this->loader->add_filter( 'script_loader_tag', $this, 'add_type_attribute', 10, 3 );
	}

	public function print_theme_data() {
		$theme      = wp_get_theme();
		$theme_data = array(
			'version'  => $theme->get( 'Version' ),
			'name'     => $theme->get( 'Name' ),
			'author'   => $theme->get( 'Author' ),
			'themeUri' => $theme->get( 'ThemeURI' ),
		);

		echo '<script>window.themeData = ' . wp_json_encode( $theme_data ) . ';</script>';
	}

	public function register_scripts() {
		foreach ( $this->scripts as $handle => $script ) {
			if ( ! isset( $script['path'] ) ) {
				continue;
			}

			$file_path = ERAWAN_PATH . $script['path'];

			if ( file_exists( $file_path ) ) {
				wp_register_script(
					$handle,
					ERAWAN_URL . $script['path'],
					$script['deps'] ?? array(),
					filemtime( $file_path ),
					$script['in_footer'] ?? true
				);

				if ( isset( $script['type'] ) && $script['type'] === 'module' ) {
					wp_script_add_data( $handle, 'type', 'module' );
				}

				if ( isset( $script['enqueue'] ) && $script['enqueue'] ) {
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

	public function add_type_attribute( $tag, $handle, $src ) {
		$script_config = $this->scripts[ $handle ] ?? null;

		if ( $script_config && isset( $script_config['type'] ) ) {
			if ( $script_config['type'] === 'nomodule' ) {
				$tag = '<script nomodule src="' . esc_url( $src ) . '"></script>';
			} else {
				$tag = '<script type="' . esc_attr( $script_config['type'] ) . '" src="' . esc_url( $src ) . '"></script>';
			}
		}

		return $tag;
	}
}
