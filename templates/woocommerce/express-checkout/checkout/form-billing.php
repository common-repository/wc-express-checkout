<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
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
$fields = $checkout->get_checkout_fields( 'billing' );
?>
<!-- billing start -->
<div class="woocommerce-billing-fields">

	<fieldset class="mb-4">
		<legend class="h6 mb-4"><?php esc_html_e( 'Customer Information', 'woocommerce-express-checkout' ); ?></legend>
		<div class="form-row">
			<?php woocommerce_form_field( 'billing_email', $fields['billing_email'], $checkout->get_value( 'billing_email' ) ); ?>
		</div>
		<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
			<?php if ( ! $checkout->is_registration_required() ) : ?>
				<div class="form-row">
					<div class="col-12">
						<div class="custom-control custom-radio d-none">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox custom-control-input mr-4" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" checked="checked" name="createaccount" value="1" />
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox custom-control-label" for="createaccount">
									 <?php esc_html_e( 'Create an account?', 'woocommerce' ); ?>
							</label>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>
			<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
				<div class="form-row create-account">
					<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
						<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
					<?php endforeach; ?>
					<div class="clear"></div>
				</div>
			<?php endif; ?>
				<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
		<?php endif; ?>
	</fieldset>
	<fieldset class="mb-4">
		<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
			<legend class="h6 mb-4"><?php esc_html_e( 'Billing &amp; Shipping', 'woocommerce' ); ?></legend>
		<?php else : ?>
			<legend class="h6 mb-4"><?php esc_html_e( 'Shipping Address', 'woocommerce' ); ?></legend>
		<?php endif; ?>
		<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>
		<div class="form-row">
			<?php woocommerce_form_field( 'billing_first_name', $fields['billing_first_name'], $checkout->get_value( 'billing_first_name' ) ); ?>
			<?php woocommerce_form_field( 'billing_last_name', $fields['billing_last_name'], $checkout->get_value( 'billing_last_name' ) ); ?>
		</div>
		<div class="form-row">
			<?php woocommerce_form_field( 'billing_company', $fields['billing_company'], $checkout->get_value( 'billing_company' ) ); ?>
		</div>
		<div class="form-row">
			<?php woocommerce_form_field( 'billing_address_1', $fields['billing_address_1'], $checkout->get_value( 'billing_address_1' ) ); ?>
			<?php woocommerce_form_field( 'billing_address_2', $fields['billing_address_2'], $checkout->get_value( 'billing_address_2' ) ); ?>
			<?php woocommerce_form_field( 'billing_city', $fields['billing_city'], $checkout->get_value( 'billing_city' ) ); ?>
		</div>

		<div class="form-row">
			<?php
			woocommerce_form_field( 'billing_country', $fields['billing_country'], $checkout->get_value( 'billing_country' ) );
			woocommerce_form_field( 'billing_state', $fields['billing_state'], $checkout->get_value( 'billing_state' ) );
			woocommerce_form_field( 'billing_postcode', $fields['billing_postcode'], $checkout->get_value( 'billing_postcode' ) );
			?>
		</div>

		<div class="form-row">
			<?php woocommerce_form_field( 'billing_phone', $fields['billing_phone'], $checkout->get_value( 'billing_phone' ) ); ?>
		</div>

		<div class="woocommerce-billing-fields__field-wrapper">
			<?php
			unset( $fields['billing_first_name'] );
			unset( $fields['billing_last_name'] );
			unset( $fields['billing_email'] );
			unset( $fields['billing_company'] );
			unset( $fields['billing_phone'] );
			unset( $fields['billing_address_1'] );
			unset( $fields['billing_address_2'] );
			unset( $fields['billing_city'] );
			unset( $fields['billing_country'] );
			unset( $fields['billing_state'] );
			unset( $fields['billing_postcode'] );
			foreach ( $fields as $key => $field ) {
				woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
			}
			?>
		</div>
	</fieldset>
	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>
<!-- billing end -->
