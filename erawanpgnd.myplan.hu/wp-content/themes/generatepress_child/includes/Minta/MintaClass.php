<?php

namespace Erawan\Minta;

use Erawan\Container;
use Erawan\Loader;

class MintaClass {
	private $loader;
	private $script_loader;

	public function __construct( Container $container, Loader $loader ) {
		$this->script_loader = $container->get( 'script_loader' );
		$this->loader        = $loader;

		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_assets' );
	}

	public function enqueue_assets() {
		$this->script_loader->enqueue_script( 'asset_neve' );
	}
}
