<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="shop_table woocommerce-checkout-review-order-table">
	<div class="card-body py-4">
	<?php
	do_action( 'woocommerce_review_order_before_cart_contents' );

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			?>
			<div class="d-flex justify-content-between align-items-center border-bottom pb-4 mb-4">
				<div class="media align-items-center">
					<div class="position-relative mr-3">
						<?php
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						if ( ! $product_permalink ) {
							echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
						<div class="qty-indicator">
							<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
					<div class="media-body">
						<h3 class="h6 font-weight-normal pr-4"><?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				<span><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</div>
			<?php
		}
	}
	woocommerce_checkout_coupon_form();
	do_action( 'woocommerce_review_order_after_cart_contents' );
	?>
	<div class="d-flex justify-content-between mb-2">
		<strong><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></strong>
		<span><?php wc_cart_totals_subtotal_html(); ?></span>
	</div>

		<div class="d-flex justify-content-between mb-2">
			<strong><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></strong>

			<span><?php echo WC()->cart->get_cart_shipping_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</div>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> d-flex justify-content-between mb-2">
			<strong><?php wc_cart_totals_coupon_label( $coupon ); ?></strong>
			<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
		</div>


	<?php endforeach; ?>



	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="fee d-flex justify-content-between mb-2">
			<strong><?php echo esc_html( $fee->name ); ?></strong>
			<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
		</div>
	<?php endforeach; ?>

	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited ?>
				<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?> d-flex justify-content-between mb-2">
					<strong><?php echo esc_html( $tax->label ); ?></strong>
					<span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="tax-total d-flex justify-content-between mb-2">
				<strong><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></strong>
				<span><?php wc_cart_totals_taxes_total_html(); ?></span>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

</div>
	<div class="card-footer border-0">
		<div class="d-flex justify-content-between align-items-center h5 mb-0">
			<strong><?php esc_html_e( 'Total', 'woocommerce' ); ?></strong>
			<span><?php wc_cart_totals_order_total_html(); ?></span>
		</div>
	</div>
	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
</div>
