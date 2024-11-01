<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">
	<div class="card cart border-0 bg-light mb-4">
		<div class="card-body py-4">
			<?php do_action( 'woocommerce_before_cart_totals' ); ?>
			<div class="d-flex justify-content-between mb-2">
				<strong><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></strong>
				<span><?php wc_cart_totals_subtotal_html(); ?></span>
			</div>
			<div class="d-flex justify-content-between mb-2">
				<strong><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></strong>
				<span><?php echo esc_attr__( 'Calculated at next step', 'woocommerce-express-checkout' ); ?></span>
			</div>
			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<div class="d-flex justify-content-between mb-2 fee">
					<strong><?php echo esc_html( $fee->name ); ?></strong>
					<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
				</div>
			<?php endforeach; ?>
			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<div class="d-flex justify-content-between mb-2 cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<strong><?php wc_cart_totals_coupon_label( $coupon ); ?></strong>
					<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
				</div>
			<?php endforeach; ?>
			<?php
			if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
				$taxable_address = WC()->customer->get_taxable_address();
				$estimated_text  = '';

				if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
					/* translators: %s location. */
					$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
				}

				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
					foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
						?>
						<div class="d-flex justify-content-between mb-2 tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
							<strong><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
							<span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
						</div>
						<?php
					}
				} else {
					?>
					<div class="d-flex justify-content-between mb-2 tax-total">
						<strong><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
						<span><?php wc_cart_totals_taxes_total_html(); ?></span>
					</div>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

			<div class="d-flex justify-content-between h5 mb-2 mt-4">
				<span><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
				<span><?php wc_cart_totals_order_total_html(); ?></span>
			</div>

			<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
			<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

		</div>
		<div class="card-footer p-0 mt-2 mb-2 ml-0 mr-0 border-0">
			<?php do_action( 'woocommerce_cart_coupon' ); ?>
		</div>
		<?php if ( wc_coupons_enabled() ) { ?>
		<div class="card-footer border-0">
			<a href="#collapseDiscount" class="discount d-flex justify-content-between align-items-center collapsed text-dark" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseDiscount">
				<span class="small"><?php esc_html_e( 'Promo code?', 'woocommerce-express-checkout' ); ?></span>
				<i class="material-icons rotate-icon">keyboard_arrow_down</i>
			</a>
			<div class="collapse coupon" id="collapseDiscount">
				<form class="form-row cart_coupon mt-3">
					<div class="col">
						<label class="sr-only" for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
						<input type="text" name="coupon_code" class="input-text form-control form-control-sm mb-2 mr-sm-2" id="coupon_code" value="<?php echo isset( $_GET['coupon_code'] ) ? esc_attr( $_GET['coupon_code'] ) : ''; ?>" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
					</div>
					<div class="col-auto">
						<button type="submit" class="btn btn-primary btn-sm mb-2" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
					</div>
				</form>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="bg-light text-center p-3 wc-proceed-to-checkout">
		<i class="material-icons align-bottom">lock</i> Secure Checkout
	</div>
</div>
<?php do_action( 'woocommerce_after_cart_totals' ); ?>
