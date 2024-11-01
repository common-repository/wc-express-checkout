<?php
/**
 * Class Woocommerce_Shopify_Settings
 */

class Woocommerce_Express_Checkout_Settings {

	protected $links;
	protected $checkout;

	public function __construct() {
		add_action( 'woocommerce_settings_tabs_woocommerce_express_checkout_settings', array( $this, 'settings_tab' ) );
		add_action( 'woocommerce_update_options_woocommerce_express_checkout_settings', array( $this, 'update_settings' ) );
		add_action( 'woocommerce_admin_field_image', array( $this, 'display_image_field' ) );
		add_action( 'woocommerce_admin_field_express_checkout', array( $this, 'display_banner_field' ) );
		add_action( 'admin_menu', array( $this, 'add_custom_link_into_woocommerce_menu' ) );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
		add_filter( 'parent_file', array( $this, 'change_active_sub_menu_item' ) );
		add_filter( 'woocommerce_order_button_text', array( $this, 'change_button_text' ), 99 );
		add_filter( 'woocommerce_pay_order_button_text', array( $this, 'change_button_text' ), 99 );
	}

	public function change_active_sub_menu_item( $parent_file ) {
		global $submenu_file;
		if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'woocommerce_express_checkout_settings' ) {
			$submenu_file = 'admin.php?page=wc-settings&tab=woocommerce_express_checkout_settings';
		}

		return $parent_file;
	}

	public function add_custom_link_into_woocommerce_menu() {
		add_submenu_page(
			'woocommerce',
			'Express Checkout',
			'Express Checkout',
			'manage_options',
			'admin.php?page=wc-settings&tab=woocommerce_express_checkout_settings'
		);
	}

	public function change_button_text() {
		return __( 'Complete Purchase', 'woocommerce' );
	}

	protected function prepare_links() {
		if ( empty( $this->links ) ) {
			$this->links[] = get_post_field( 'post_name', get_option( 'woocommerce_checkout_page_id' ) );
			$this->links[] = get_post_field( 'post_name', get_option( 'woocommerce_cart_page_id' ) );
		}
	}

	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['woocommerce_express_checkout_settings'] = __( 'Express Checkout', 'woocommerce-express-checkout' );
		return $settings_tabs;
	}

	public function settings_tab() {
		woocommerce_admin_fields( $this->get_settings() );
	}

	public function update_settings() {
		woocommerce_update_options( $this->get_settings() );
	}

	public function get_settings() {
		$id             = woocommerce_express_checkout_helper()->settings_id();
	     if ( ! function_exists( 'get_plugins' ) ) {
		     require_once ABSPATH . 'wp-admin/includes/plugin.php';
	     }
		$settings = array(
			'section_title'    => array(
				'name' => __( 'Settings', 'woocommerce-express-checkout' ),
				'type' => 'title',
				'desc' => '',
				'id'   => $id . 'general',
			),
			'logo'             => array(
				'name' => __( 'Logo', 'woocommerce-express-checkout' ),
				'type' => 'image',
				'desc' => __( 'If empty default site logo will be used.', 'woocommerce-express-checkout' ),
				'id'   => $id . 'logo',
			),
			'contact_us'          => array(
			     'name' => __( 'Contact us link', 'woocommerce-express-checkout' ),
			     'type' => 'text',
			     'id'   => $id . 'contact_us',
		     ),
		     'banner'             => array(
			     'name' => __( 'Logo', 'woocommerce-express-checkout' ),
			     'type' => 'express_checkout',
			     'desc' => __( 'If empty default site logo will be used.', 'woocommerce-express-checkout' ),
			     'id'   => $id . 'logo',
		     ),
			'section_end'      => array(
				'type' => 'sectionend',
				'id'   => $id . 'section_end',
			),
		);
		return apply_filters( 'wc_settings_tab_demo_settings', $settings );
	}


	public function display_image_field( $value ) {
		$this->enqueue_media();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
			</th>
			<td class="forminp">
				<?php $image_attributes = wp_get_attachment_image_src( $value['value'], 'full' ); ?>
				<div>
					<div class="image-temp_container">
						<div class="img-container">
							<?php if ( $image_attributes ) { ?>
								<a href="#" class="woocommerce-image-upload">
									<img src="<?php echo esc_url( $image_attributes[0] ); ?>" style="max-width:405px;width: auto; max-height: 80px;height: auto;" />
								</a>
							<?php } else { ?>
								<a href="#" class="woocommerce-image-upload button">
									Upload image
								</a>
							<?php } ?>
						</div>
						<div class="image-actions" style="
						<?php
						if ( $image_attributes ) {
							?>
							display:block;
							<?php
						} else {
							?>
							display:none;
						<?php } ?>">
							<a href="#" class="woocommerce-remove-image button" style="display:inline-block;">Remove image</a>
							<a href="#" class="woocommerce-image-upload button" style="display:inline-block;">Change image</a>
						</div>
						<input type="hidden" name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" value="<?php echo esc_attr( $value['value'] ); ?>" />
					</div>
					<p><?php echo wp_kses( $value['desc'], array() ); ?></p>
				</div>
			</td>
		</tr>
		<?php
	}

	protected function enqueue_media() {
		wp_enqueue_media();
	     wp_enqueue_script( 'woocommerce-express-checkout-admin', plugin_dir_url( __FILE__ ) . '../assets/js/admin/custom.js' );
	     wp_enqueue_style( 'woocommerce-express-checkout-admin', plugin_dir_url( __FILE__ ) . '../assets/css/admin/styles.css' );
	}

	public function display_banner_field( $value ) {
		?>
		<tr valign="top">
			<th scope="row" colspan="2">
	               <a href="https://expresscheckout.app"><img src="<?php echo WSC_URL . 'assets/images/banner.png'; ?>"/></a>
			</th>
		</tr>
		<?php
     }
}
