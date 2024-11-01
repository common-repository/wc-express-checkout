<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( class_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_attr_e( 'Skip to content', 'woocommerce-express-checkout' ); ?></a>

	<header class="mb-4 mt-4 site-header">
		<div class="container">
			<nav class="site-nav navbar navbar-expand-md navbar-light p-0">
				<?php
				$shop_logo = woocommerce_express_checkout_helper()->get_shop_logo();
				if ( $shop_logo ) {
					?>
					<img src="<?php echo esc_url( $shop_logo ); ?>" class="responsive-image" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
					<?php
				} elseif ( has_custom_logo() ) {
					echo get_custom_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					echo '<a href="' . esc_attr( get_site_url() ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
				}
				?>
			</nav>
		</div>
	</header>
	<main id="content" class="container mb-4">
