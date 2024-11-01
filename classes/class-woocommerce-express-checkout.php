<?php

class Woocommerce_Express_Checkout {

	public function __construct() {
		add_filter( 'woocommerce_locate_template', array( $this, 'locate_template' ), 10, 3 );
		add_filter( 'woocommerce_product_loop_title_classes', array( $this, 'fix_product_title' ), 10 );
		add_filter( 'wc_get_template_part', array( $this, 'locate_template_file' ), 10, 3 );
		add_filter( 'woocommerce_form_field', array( $this, 'remove_paragraph' ), 10, 4 );
		add_filter( 'page_template', array( $this, 'setup_page_template' ) );
		add_filter( 'woocommerce_enqueue_styles', array( $this, 'remove_woo_styles' ) );

		add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
		add_filter( 'woocommerce_form_field_args', array( $this, 'wc_form_field_args' ), 10, 3 );
		add_filter( 'woocommerce_checkout_posted_data', array( $this, 'validate_fields' ), 10, 1 );
		add_filter( 'woocommerce_checkout_fields', array( $this, 'change_field_labels' ), 99, 1 );
		add_filter( 'wc_payment_gateway_authorize_net_cim_credit_card_order_button_text', array( $this, 'change_checkout_button_label' ), 99, 1 );
		add_filter( 'wc_payment_gateway_authorize_net_cim_echeck_order_button_text', array( $this, 'change_checkout_button_label' ), 99, 1 );
		add_filter( 'woocommerce_get_script_data', array( $this, 'rename_option_on_checkout_page' ), 99, 2 );
		add_filter( 'woocommerce_default_address_fields' , array( $this, 'custom_override_default_address_fields' ) );

		add_filter( 'woocommerce_cart_ready_to_calc_shipping', array( $this, 'disable_shipping_on_cart' ) );
		add_filter( 'woocommerce_cart_needs_shipping', array( $this, 'disable_shipping_on_cart' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ), 100 );
		add_action( 'wp_print_styles', array( $this, 'remove_unnecessary_styles' ), 99 );
		add_action( 'wp_head', array( $this, 'deregister_hooks' ), 1 );
	}

	public function change_checkout_button_label( $label ) {
		return __( 'Complete Purchase', 'woocommerce-express-checkout' );
	}

	public function custom_override_default_address_fields( $address_fields ) {
		$address_fields['city']['label'] =  __( 'City / Town', 'woocommerce-express-checkout' );
		$address_fields['postcode']['label'] =  __( 'Zip Code', 'woocommerce-express-checkout' );
		$address_fields['postcode']['placeholder'] =  __( 'Zip Code', 'woocommerce-express-checkout' );

		return $address_fields;
	}

	public function rename_option_on_checkout_page( $params, $handle ) {
		if ( $handle === 'wc-country-select' ) {
			$params['i18n_select_state_text'] = __( 'Select', 'woocommerce' );
		}
		return $params;
	}

	public function change_field_labels( $fields ) {

		$fields['billing']['billing_postcode']['label']       = __( 'Zip Code', 'woocommerce-express-checkout' );
		$fields['billing']['billing_postcode']['placeholder'] = __( 'Zip Code', 'woocommerce-express-checkout' );

		$fields['billing']['billing_company']['label']       = __( 'Company', 'woocommerce-express-checkout' );
		$fields['billing']['billing_company']['placeholder'] = __( 'Company', 'woocommerce-express-checkout' );
		$fields['billing']['billing_city']['label']          = __( 'City / Town', 'woocommerce-express-checkout' );
		$fields['billing']['billing_city']['placeholder']    = __( 'City / Town', 'woocommerce-express-checkout' );
		$fields['shipping']['shipping_company']              = $fields['billing']['billing_company'];
		$fields['shipping']['shipping_phone']                = $fields['billing']['billing_phone'];
		$fields['shipping']['shipping_city']['label']        = __( 'City / Town', 'woocommerce-express-checkout' );
		$fields['shipping']['shipping_city']['placeholder']  = __( 'City / Town', 'woocommerce-express-checkout' );
		return $fields;
	}

	public function disable_shipping_on_cart( $enabled ) {
		return is_checkout() ? $enabled : false;
	}

	public function replace_error_messages( $fields, &$errors ) {
		if ( $fields['ship_to_different_address'] ) {

			foreach ( $errors->errors as $type => $messages ) {
				foreach ( $messages as $key => $error ) {
					if ( strpos( $error, __( 'Billing', 'woocommerce' ) ) !== false ) {
						$errors->errors[ $type ][ $key ] = str_replace( __( 'Billing', 'woocommerce' ), __( 'Shipping', 'woocommerce' ), $error );
					} elseif ( strpos( $error, __( 'Shipping', 'woocommerce' ) ) !== false ) {
						$errors->errors[ $type ][ $key ] = str_replace( __( 'Shipping', 'woocommerce' ), __( 'Billing', 'woocommerce' ), $error );
					}
				}
			}
		}
	}

	public function validate_fields( $data ) {
		if ( $data['ship_to_different_address'] ) {
			foreach ( $data as $key => $value ) {
				if ( false !== strpos( $key, 'billing_' ) ) {
					$shipping_key = str_replace( 'billing_', 'shipping_', $key );
					if ( isset( $data[ $shipping_key ] ) ) {
						$data[ $key ]          = $data[ $shipping_key ];
						$data[ $shipping_key ] = $value;
					}
				}
			}
		}
		return $data;
	}

	public function remove_woo_styles( $array ) {
		if ( ! $this->is_enabled() ) {
			return $array;
		}
		return array();

	}

	public function deregister_hooks() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
		if ( class_exists( 'woocommerce' ) ) {
			wp_dequeue_style( 'select2' );
			wp_deregister_style( 'select2' );
			wp_dequeue_style( 'wc-block-style-css' );
			wp_deregister_style( 'wc-block-style-css' );

			wp_dequeue_script( 'select2' );
			wp_deregister_script( 'select2' );
			wp_dequeue_script( 'selectWoo' );
			wp_deregister_script( 'selectWoo' );
			wp_dequeue_script( 'wc-enhanced-select' );
			wp_deregister_script( 'wc-enhanced-select' );
		}

	}

	public function remove_paragraph( $field, $key, $args, $value ) {
		$required          = '';
		$field             = '';
		$label_id          = $args['id'];
		$sort              = $args['priority'] ? $args['priority'] : '';
		$field_container   = '<div class="%1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '"><div class="form-label-group">%3$s</div></div>';
		$custom_attributes = array();

		if ( false !== strpos( $args['id'], 'number' ) ) {
			$args['class'][] = 'col-12';
		}
		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		if ( $args['required'] ) {
			$custom_attributes[] = ' required="required" ';
		}

		$screen_reader = array_search( 'screen-reader-text', $args['label_class'], true );
		if ( $screen_reader !== false ) {
			unset( $args['label_class'][ $screen_reader ] );
		}
		switch ( $args['type'] ) {
			case 'country':
				$countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

				if ( 1 === count( $countries ) ) {

					$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

					$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';

				} else {

					$field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . '><option value="">' . esc_html__( 'Select', 'woocommerce' ) . '</option>';

					foreach ( $countries as $ckey => $cvalue ) {
						$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . $cvalue . '</option>';
					}

					$field .= '</select>';

					$field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country', 'woocommerce' ) . '">' . esc_html__( 'Update country', 'woocommerce' ) . '</button></noscript>';

				}

				break;
			case 'state':
				/* Get country this state field is representing */
				$for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( 'billing_state' === $key ? 'billing_country' : 'shipping_country' );
				$states      = WC()->countries->get_states( $for_country );

				if ( is_array( $states ) && empty( $states ) ) {

					$field_container = '<div class="form-row %1$s" id="%2$s" style="display: none">%3$s</div>';

					$field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" readonly="readonly" data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

				} elseif ( ! is_null( $for_country ) && is_array( $states ) ) {

					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_html__( 'Select', 'woocommerce' ) ) . '"  data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '">
						<option value="">' . esc_html__( 'Select', 'woocommerce' ) . '</option>';

					foreach ( $states as $ckey => $cvalue ) {
						$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . $cvalue . '</option>';
					}

					$field .= '</select>';

				} else {

					$field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['placeholder'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

				}

				break;
			case 'textarea':
				$field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';

				break;
			case 'checkbox':
				$field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
						<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';

				break;
			case 'text':
			case 'password':
			case 'datetime':
			case 'datetime-local':
			case 'date':
			case 'month':
			case 'time':
			case 'week':
			case 'number':
			case 'email':
			case 'url':
			case 'tel':
				$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';
				break;
			case 'select':
				$field   = '';
				$options = '';

				if ( ! empty( $args['options'] ) ) {
					foreach ( $args['options'] as $option_key => $option_text ) {
						if ( '' === $option_key ) {
							// If we have a blank option, select2 needs a placeholder.
							if ( empty( $args['placeholder'] ) ) {
								$args['placeholder'] = $option_text ? $option_text : __( 'Select', 'woocommerce' );
							}
							$custom_attributes[] = 'data-allow_clear="true"';
						}
						$options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) . '</option>';
					}

					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
							' . $options . '
						</select>';
				}

				break;
			case 'radio':
				$label_id .= '_' . current( array_keys( $args['options'] ) );

				if ( ! empty( $args['options'] ) ) {
					foreach ( $args['options'] as $option_key => $option_text ) {
						$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
						$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . $option_text . '</label>';
					}
				}

				break;
		}

		if ( ! empty( $field ) ) {
			$field_html = '';

			$field_html .= $field;

			if ( $args['label'] && 'checkbox' !== $args['type'] ) {
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . $args['label'] . '</label>';
			}

			if ( $args['description'] ) {
				$field_html .= '<div class="description invalid-feedback" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</div>';
			}

			$container_class = esc_attr( implode( ' ', $args['class'] ) );
			$container_id    = esc_attr( $args['id'] ) . '_field';
			$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
		}
		remove_filter( 'woocommerce_form_field', array( $this, 'remove_paragraph' ), 10, 4 );
		/**
		 * Filter by type.
		 */
		$field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

		/**
		 * General filter on form fields.
		 *
		 * @since 3.4.0
		 */
		$field = apply_filters( 'woocommerce_form_field', $field, $key, $args, $value );
		add_filter( 'woocommerce_form_field', array( $this, 'remove_paragraph' ), 10, 4 );
		if ( $args['return'] ) {
			return $field;
		} else {
			echo $field; // WPCS: XSS ok.
		}
	}

	public function wc_form_field_args( $args, $key, $value = null ) {
		if ( empty( $args['placeholder'] ) ) {
			$args['placeholder'] = $args['label'];
		}
		if ( ! $args['required'] && false === strpos( strtolower( $args['label'] ), __( 'optional', 'woocommerce' ) ) ) {
			$args['label'] .= ' ' . __( '(optional)', 'woocommerce' );
		}
		if ( isset( $args['required'] ) && $args['required'] ) {
			$args['description'] = $args['label'] . ' is required';
		}
		if ( in_array( $args['type'], array( 'select', 'country', 'state' ), true ) ) {
			$args['class'] = array( 'select-label-group', 'col', 'mb-0' );
			if ( in_array( $args['type'], array( 'country', 'state' ), true ) ) {
				$args['class'][] = 'address-field';
				$args['class'][] = 'update_totals_on_change';
			}
			$args['input_class'][] = 'custom-select';
		} else {
			if ( in_array( $key, array( 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_phone', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_phone' ), true ) ) {
				$args['class'] = array( 'col-12' );
			} elseif ( in_array( $key, array( 'billing_first_name', 'billing_last_name', 'shipping_address_1' ), true ) ) {
				$args['class'] = array( 'col-md-6' );
			} elseif ( in_array( $key, array( 'shipping_city', 'shipping_address_2' ), true ) ) {
				$args['class'] = array( 'col-md-3' );
			} else {
				$args['class'] = array( 'col' );
			}
			$args['input_class'][] = 'form-control';
		}
		return $args;
	}

	/**
	 * Remove all style sheets.
	 */
	public function remove_unnecessary_styles() {
		if ( $this->is_enabled() ) {
			$enabled = array( 'wec', 'admin-bar', 'wec-icons' );
			global $wp_styles;
			foreach ( $wp_styles->queue as $key => $value ) {
				if ( in_array( $value, $enabled, true ) || false !== strpos( $value, 'payment' ) ) {
					continue;
				}
				unset( $wp_styles->queue[ $key ] );
			}
		}
	}


	public function add_scripts() {
		if ( $this->is_enabled() ) {
			$suffix  = SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_style( 'wec-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array() );
			wp_enqueue_style( 'wec', WSC_URL . '/assets/css/style' . $suffix . '.css', array() );
			wp_enqueue_script( 'wec-bootstrap', WSC_URL . '/assets/js/bootstrap.min.js', array( 'jquery' ), '1.0.0', true );
			wp_enqueue_script( 'wec', WSC_URL . '/assets/js/custom.js', array( 'jquery' ), '1.0.0', true );
		}
	}

	public function setup_page_template( $page_template ) {
		if ( $this->is_enabled() ) {
			if ( locate_template( '/woocommerce/pages/template-blank.php' ) ) {
				return locate_template( '/woocommerce/pages/template-blank.php' );
			} else {
				return WSC_PATH . '/templates/wordpress/template-blank.php';
			}
		}
		return $page_template;
	}

	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 * yourtheme/$template_path/$template_name
	 * yourtheme/$template_name
	 * $default_path/$template_name
	 *
	 * @param string $template  Default path.
	 * @param string $template_name Template name.
	 * @param string $template_path Template path. (default: '').
	 * @return string
	 */
	public function locate_template( $template, $template_name, $template_path ) {
		return $this->plugin_template( $template, $template_name, $template_path );
	}

	public function plugin_template( $template, $template_name, $template_path ) {
		if ( ! is_cart() && ! is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
			return $template;
		}
		if ( file_exists( WSC_PATH . '/templates/woocommerce/express-checkout/' . $template_name ) ) {
			return WSC_PATH . '/templates/woocommerce/express-checkout/' . $template_name;
		}
		return $template;
	}

	protected function is_enabled() {
		return is_cart() || is_checkout();
	}

	public function locate_template_file( $template, $template_name, $template_path ) {
		if ( ! is_cart() && ! is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
			return $template;
		}
		if ( file_exists( WSC_PATH . '/templates/woocommerce/express-checkout/' . $template_name . '-' . $template_path . '.php' ) ) {
			return WSC_PATH . '/templates/woocommerce/express-checkout/' . $template_name . '-' . $template_path . '.php';
		} elseif ( file_exists( WSC_PATH . '/templates/woocommerce/express-checkout/' . $template_name . '.php' ) ) {
			return WSC_PATH . '/templates/woocommerce/express-checkout/' . $template_name . '.php';
		}
		return $template;
	}

	public function fix_product_title( $class ) {
		return $class . ' h6 mt-2 mb-2 ';
	}
}
