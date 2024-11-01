<?php

class Woocommerce_Express_Checkout_Helper {
	public function get_header() {
		if ( locate_template( 'header-express-checkout.php' ) ) {
			get_header( 'express-checkout' );
		} else {
			require_once WSC_PATH . '/templates/wordpress/header-express-checkout.php';
		}
	}

	public function get_footer() {
		if ( locate_template( 'footer-express-checkout.php' ) ) {
			get_footer( 'express-checkout' );
		} else {
			require_once WSC_PATH . '/templates/wordpress/footer-express-checkout.php';
		}
	}

	public function settings_id() {
		return 'woocommerce_express-checkout_settings_';
	}

	public function get_shop_logo() {
		$id     = woocommerce_express_checkout_helper()->settings_id();
		$img_id = get_option( $id . 'logo', 0 );
		if ( $img_id ) {
			$image_attributes = wp_get_attachment_image_src( $img_id, 'full' );
			if ( $image_attributes ) {
				return $image_attributes[0];
			}
		}
		return 0;
	}
	public function get_contact_link() {
		$id           = $this->settings_id();
		$contact_link = get_option( $id . 'contact_us', '' );
		return $contact_link;
    }
}



function woocommerce_express_checkout_helper() {
	return new Woocommerce_Express_Checkout_Helper();
}
