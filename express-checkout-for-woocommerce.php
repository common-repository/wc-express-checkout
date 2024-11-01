<?php
/**
 * Plugin Name: Express Checkout for WooCommerce
 * Plugin URI: https://expresscheckout.app/
 * Description: Express Checkout instantly converts the default WooCommerce checkout into a beautiful, high converting checkout experience for your customers.
 * Version: 1.0.4
 * Author: expresscheckout
 * Author URI: http://expresscheckout.app/
 * Developer: expresscheckout,webpigment
 * Developer URI: http://webpigment.com/
 * Text Domain: woocommerce-express-checkout
 * Domain Path: /languages
 *
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * WC requires at least: 2.2
 * WC tested up to: 4
 *
 * Copyright: © 2009-2019 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 **/
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	// Put your plugin code here
	return;
}

define( 'WSC_PATH', dirname( __FILE__ ) );
define( 'WSC_URL', plugin_dir_url( __FILE__ ) );

require_once dirname( __FILE__ ) . '/classes/class-woocommerce-express-checkout.php';
require_once dirname( __FILE__ ) . '/classes/class-woocommerce-express-checkout-helper.php';
require_once dirname( __FILE__ ) . '/classes/class-woocommerce-express-checkout-settings.php';
new Woocommerce_Express_Checkout();
new Woocommerce_Express_Checkout_Settings();
