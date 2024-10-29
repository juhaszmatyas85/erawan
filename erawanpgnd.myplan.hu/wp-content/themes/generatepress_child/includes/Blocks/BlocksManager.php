<?php

namespace Erawan\Blocks;

class BlocksManager {
	private $container;
	private $loader;

	public function __construct( $container, $loader ) {
		$this->container = $container;
		$this->loader    = $loader;

		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		add_filter( 'block_categories_all', array( $this, 'register_block_category' ), 10, 2 );
		add_action( 'acf/init', array( $this, 'register_blocks' ) );
	}

	public function register_block_category( $categories ) {
		return array_merge(
			array(
				array(
					'slug'  => 'erawan-blocks',
					'title' => 'Erawan Blocks',
				),
			),
			$categories
		);
	}

	public function register_blocks() {
		$blocks = array(
			'mainnav-my-account' => array(
				'title'       => 'Mainnav My Account',
				'description' => 'Mainnav My Account',
				'icon'        => 'admin-users',
				// Ha kell a blokkhoz CSS vagy JS, akkor itt meg kell adni
				// és ha regisztrálva van, akkor a blokkot használó oldalakon
				// a blokkhoz tartozó CSS és JS is betöltődik.
				// StyleLoader és ScriptLoader osztályokban kell regisztrálni.
				'assets'      => array(
					'scripts' => array( 'hystmodal', 'custom-login-modal' ),
					'styles'  => array( 'hystmodal' ),
				),
			),
		);

		foreach ( $blocks as $block_name => $settings ) {
			$settings['keywords'] = array_merge( $settings['keywords'] ?? array( 'erawan' ), array( $block_name, 'erawan' ) );

			acf_register_block_type(
				array(
					'name'            => $block_name,
					'title'           => $settings['title'] ?? ucfirst( $block_name ),
					'description'     => $settings['description'] ?? '',
					'category'        => $settings['category'] ?? 'erawan-blocks',
					'icon'            => $settings['icon'] ?? 'admin-generic',
					'keywords'        => $settings['keywords'],
					'render_template' => ERAWAN_TEMPLATE_PATH . 'blocks/' . $block_name . '/markup.php',
					'enqueue_assets'  => function ( $block ) use ( $settings ) {
						if ( ! empty( $settings['assets'] ) ) {
							$this->enqueue_block_assets( $settings['assets'] );
						}
					},
				)
			);
		}
	}

	private function enqueue_block_assets( $assets ) {
		if ( ! empty( $assets['scripts'] ) ) {
			foreach ( $assets['scripts'] as $handle ) {
				$this->container->get( 'script_loader' )->enqueue_script( $handle );
			}
		}

		if ( ! empty( $assets['styles'] ) ) {
			foreach ( $assets['styles'] as $handle ) {
				$this->container->get( 'style_loader' )->enqueue_style( $handle );
			}
		}
	}
}
