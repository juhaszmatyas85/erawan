<?php

namespace Erawan\Setup;

class Activator {
    public function activate(): void {
		$min_php = '8.2';

		if ( version_compare( PHP_VERSION, $min_php, '<' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			/* translators: %s is replaced with the minimum PHP version */wp_die( sprintf( esc_html__( 'This plugin requires a minimum PHP Version of %s', 'erawan' ), $min_php ) );
		}
    }
}
