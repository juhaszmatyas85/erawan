<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$front           = \FKCart\Includes\Front::get_instance();
$settings        = \FKCart\Includes\Data::get_settings();
$cart_item_count = $front->get_cart_content_count();
?>
<div class="fkcart-slider-heading fkcart-panel">
	<div class="fkcart-title">
		<?php
			esc_html_e( $settings['cart_heading'] );
		?>
	</div>
	<div class="fkcart-modal-close">
		<?php
		fkcart_get_template_part(
			'icon/close',
			'',
			array(
				'width'  => 20,
				'height' => 20,
			)
		);
		?>
	</div>
</div>
