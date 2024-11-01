<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( is_user_logged_in() ) {
	return;
}

?>
<form class="woocommerce-form woocommerce-form-login login mb-3" method="post" <?php echo ( $hidden ) ? 'style="display:none;"' : ''; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

	<?php echo ( $message ) ? wpautop( wptexturize( $message ) ) : ''; // @codingStandardsIgnoreLine ?>
	<div class="form-row">
		<div class="col-sm-5">
			<div class="form-label-group">
				<input type="text" class="input-text form-control" name="username" id="username" autocomplete="username" />
				<label for="username"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?></label>
				<div class="invalid-feedback">
					<?php esc_html_e( 'Username or email', 'woocommerce' ); ?> is required
				</div>
			</div>
		</div>
		<div class="col-sm-5">
			<div class="form-label-group">
				<input class="input-text form-control" type="password" name="password" id="password" autocomplete="current-password" />
				<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?></label>
				<div class="invalid-feedback">
					<?php esc_html_e( 'Password', 'woocommerce' ); ?> is required
				</div>
			</div>
		</div>
		<div class="col-sm-2 text-right">
			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
			<button type="submit" class="btn btn-primary woocommerce-button button woocommerce-form-login__submit d-block" style="width: 100%;" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
		</div>
	</div>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form' ); ?>

	<div class="form-row">
		<div class="col">
			<p class="lost_password">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
			</p>
			<div class="custom-control custom-radio d-none">
				<input selected="selected" class="woocommerce-form__input woocommerce-form__input-checkbox custom-control-input mr-4" name="rememberme" type="checkbox" id="rememberme" value="forever" />
				<label for="rememberme"  class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme custom-control-label"><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></label>
			</div>
		</div>

	</div>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
