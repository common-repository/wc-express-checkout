<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

	<?php else : ?>

		<div class="row">
			<div class="col-lg-7">
				<div class="media align-items-center mb-4">
					<i class="material-icons text-success display-3 mr-2">
						check_circle_outline
					</i>
					<div class="media-body">
						<div class="mb-1"><?php esc_html_e( 'Order', 'woocommerce' ); ?> #<?php echo $order->get_id(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div class="h5"><?php esc_html_e( 'Thank you', 'woocommerce' ); ?> <?php echo $order->get_billing_first_name(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>!</div>
					</div>
				</div>
				<div class="card bg-light border-0 mb-4">
					<div class="card-header border-0">
						<h3 class="h6 mb-0"><?php esc_html_e( 'Your order is confirmed', 'woocommerce' ); ?></h3>
					</div>
					<div class="card-body">
						<p class="card-text">We've accepted your order, and we're getting it ready. A confirmation email has been sent to <strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></p>
					</div>
				</div>
				<div class="card bg-light border-0 mb-4">
					<div class="card-header border-0">
						<h3 class="h6 mb-0"><?php esc_html_e( 'Customer Information', 'woocommerce-express-checkout' ); ?></h3>
					</div>
					<div class="card-body">
						<div class="row">
							<?php $show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address(); ?>
							<?php if ( $show_shipping ) { ?>
								<div class="col-sm-6">
									<h4 class="h6"><?php esc_html_e( 'Shipping Address', 'woocommerce' ); ?></h4>
									<p><?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?></p>
								</div>
								<div class="col-sm-6">
									<h4 class="h6"><?php esc_html_e( 'Billing Address', 'woocommerce' ); ?></h4>
									<p><?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?></p>
								</div>
								<div class="col-sm-6">
									<h4 class="h6"><?php esc_html_e( 'Shipping Method', 'woocommerce' ); ?></h4>
									<p><?php echo wp_kses_post( $order->get_shipping_method() ); ?></p>
								</div>
								<div class="col-sm-6">
									<h4 class="h6"><?php esc_html_e( 'Payment Method', 'woocommerce' ); ?></h4>
									<p class="payments"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
								</div>
							<?php } else { ?>
								<div class="col-sm-6">
									<h4 class="h6"><?php esc_html_e( 'Billing Address', 'woocommerce' ); ?></h4>
									<p><?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?></p>
								</div>
								<div class="col-sm-6">
									<h4 class="h6"><?php esc_html_e( 'Payment Method', 'woocommerce' ); ?></h4>
									<p class="payments"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
					<p class="mb-sm-0"><i class="material-icons align-bottom">
							help_outline
						</i> <?php esc_html_e( 'Need help?', 'woocommerce-express-checkout' ); ?> <a href="<?php echo esc_attr( woocommerce_express_checkout_helper()->get_contact_link() ); ?>"><?php esc_html_e( 'Contact us', 'woocommerce-express-checkout' ); ?></a></p>
					<a href="<?php echo esc_attr( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Continue Shopping', 'woocommerce' ); ?></a>
				</div>
				<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
					<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
					<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="card cart border-0 bg-light mb-3">
					<div class="card-body py-4">
						<?php woocommerce_order_details_table( $order->get_id() ); ?>
					</div>
					<div class="card-footer border-0">
						<div class="d-flex justify-content-between align-items-center h5 mb-0">
							<strong><?php esc_html_e( 'Order Total', 'woocommerce' ); ?></strong>
							<span><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
						</div>
					</div>
				</div>
				<div class="bg-light text-center p-3">
					<i class="material-icons align-bottom">lock</i> <?php esc_html_e( 'Secure Checkout', 'woocommerce-express-checkout' ); ?>
				</div>
			</div>
		</div>

	<?php endif; ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
