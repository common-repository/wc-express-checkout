<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="border-bottom pb-3 mb-3">
	<a href="#collapseDiscount" class="discount d-flex justify-content-between align-items-center collapsed text-dark" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseDiscount">
		<span class="small"><?php echo apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'woocommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<i class="material-icons rotate-icon">keyboard_arrow_down</i>
	</a>
	<div class="collapse" id="collapseDiscount">
		<form class="checkout_coupon woocommerce-form-coupon form-row mt-3" method="post">
			<div class="col">
				<label class="sr-only" for="discountCode"><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'woocommerce' ); ?></label>
				<input type="text" class="form-control form-control-sm mb-2 mr-sm-2" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" name="coupon_code"  id="coupon_code" value="" >
			</div>
			<div class="col-auto">
				<button type="submit" class="btn btn-primary btn-sm mb-2" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
			</div>
		</form>
	</div>
</div>
