<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
$fields = $checkout->get_checkout_fields( 'shipping' );
?>
<!-- shipping start -->
<div class="woocommerce-shipping-fields">
	<fieldset class="mb-4">
		<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
			<legend class="h6 mb-4"><?php esc_html_e( 'Billing Address', 'woocommerce' ); ?></legend>
			<ul class="list-group mb-4 ml-0">
				<li class="list-group-item">
					<div class="custom-control custom-radio">
						<input type="radio" id="radioShipping" name="ship_to_different_address" value="0" class="custom-control-input mr-4" data-toggle="collapse" data-target=".collapse-billing.show" checked>
						<label class="custom-control-label" for="radioShipping"><?php esc_html_e( 'Same as shipping address', 'woocommerce-express-checkout' ); ?></label>
					</div>
				</li>
				<li class="list-group-item">
					<div class="custom-control custom-radio">
						<input type="radio" id="radioBilling" name="ship_to_different_address" value="1" class="custom-control-input" data-toggle="collapse" data-target=".collapse-billing:not(.show)">
						<label class="custom-control-label" for="radioBilling"><?php esc_html_e( 'Use a different billing address', 'woocommerce-express-checkout' ); ?></label>
					</div>
				</li>
			</ul>
			<div class="shipping_address collapse collapse-billing">
				<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>
				<div class="form-row">
					<?php woocommerce_form_field( 'shipping_first_name', $fields['shipping_first_name'], $checkout->get_value( 'shipping_first_name' ) ); ?>
					<?php woocommerce_form_field( 'shipping_last_name', $fields['shipping_last_name'], $checkout->get_value( 'shipping_last_name' ) ); ?>
				</div>
				<div class="form-row">
					<?php woocommerce_form_field( 'shipping_company', $fields['shipping_company'], $checkout->get_value( 'shipping_company' ) ); ?>
				</div>
				<div class="form-row">
					<?php woocommerce_form_field( 'shipping_address_1', $fields['shipping_address_1'], $checkout->get_value( 'shipping_address_1' ) ); ?>
					<?php woocommerce_form_field( 'shipping_address_2', $fields['shipping_address_2'], $checkout->get_value( 'shipping_address_2' ) ); ?>
					<?php woocommerce_form_field( 'shipping_city', $fields['shipping_city'], $checkout->get_value( 'shipping_city' ) ); ?>
				</div>

				<div class="form-row">
					<?php
					woocommerce_form_field( 'shipping_country', $fields['shipping_country'], $checkout->get_value( 'shipping_country' ) );
					woocommerce_form_field( 'shipping_state', $fields['shipping_state'], $checkout->get_value( 'shipping_state' ) );
					woocommerce_form_field( 'shipping_postcode', $fields['shipping_postcode'], $checkout->get_value( 'shipping_postcode' ) );
					?>
				</div>

				<div class="form-row">
					<?php woocommerce_form_field( 'shipping_phone', $fields['shipping_phone'], $checkout->get_value( 'shipping_phone' ) ); ?>
				</div>

				<div class="woocommerce-billing-fields__field-wrapper">
					<?php
					unset( $fields['shipping_first_name'] );
					unset( $fields['shipping_last_name'] );
					unset( $fields['shipping_email'] );
					unset( $fields['shipping_company'] );
					unset( $fields['shipping_phone'] );
					unset( $fields['shipping_address_1'] );
					unset( $fields['shipping_address_2'] );
					unset( $fields['shipping_city'] );
					unset( $fields['shipping_country'] );
					unset( $fields['shipping_state'] );
					unset( $fields['shipping_postcode'] );
					foreach ( $fields as $key => $field ) {
						woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					}
					?>
				</div>

				<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
			</div>

		<?php endif; ?>
	</fieldset>
</div>
<!-- shipping end -->
<!-- additional start -->
<div class="woocommerce-additional-fields">
	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

		<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

			<h3><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>

		<?php endif; ?>

		<div class="woocommerce-additional-fields__field-wrapper form-row">
			<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
<!-- additional end -->
