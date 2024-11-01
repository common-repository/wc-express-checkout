(function($) {
	'use strict';

	// Plus/minus input counter
	//
	$( document ).on(
		'click',
		'.counter .minus',
		function(){
			let $input = $( this ).parent().find( 'input' );
			let count  = parseInt( $input.val(), 10 ) - 1;
			count      = count < 1 ? 1 : count;
			$input.val( count ).trigger( 'change' );
			$( '[name="update_cart"]' ).trigger( 'click' );

		}
	);

	$( document ).on(
		'click',
		'.counter .plus',
		function(){
			let $input = $( this ).parent().find( 'input' );
			let count  = parseInt( $input.val(), 10 ) + 1;
			$input.val( count ).trigger( 'change' );
			$( '[name="update_cart"]' ).trigger( 'click' );
		}
	);

	$(document).on('submit', 'form.checkout_coupon', function(e){
		e.preventDefault();
		var $form = $( this );

		if ( $form.is( '.processing' ) ) {
			return false;
		}

		$form.addClass( 'processing' ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		var data = {
			security:		wc_checkout_params.apply_coupon_nonce,
			coupon_code:	$form.find( 'input[name="coupon_code"]' ).val()
		};

		$.ajax({
			type:		'POST',
			url:		wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
			data:		data,
			success:	function( code ) {
				$( '.woocommerce-error, .woocommerce-message' ).remove();
				$form.removeClass( 'processing' ).unblock();

				if ( code ) {
					$( '.woocommerce-error, .woocommerce-message' ).remove();
					$('form.woocommerce-checkout').before( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-updateOrderReview">' + code + '</div>' );
					$form.slideUp();

					$( document.body ).trigger( 'applied_coupon_in_checkout', [ data.coupon_code ] );
					$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
				}
			},
			dataType: 'html'
		});
	})


	// Don't allow letter e on number fields
	//
	$( 'input[type=number]' ).keydown(
		function() {
			return event.keyCode !== 69;
		}
	);

	// Disable form submissions if there are invalid fields
	//
	window.addEventListener(
		'load',
		function() {
			let forms      = document.getElementsByClassName( 'needs-validation' );
			let validation = Array.prototype.filter.call(
				forms,
				function(form) {
					form.addEventListener(
						'submit',
						function(event) {
							if (form.checkValidity() === false) {
								event.preventDefault();
								event.stopPropagation();
							}
							form.classList.add( 'was-validated' );
						},
						false
					);
				}
			);
		},
		false
	);
	$('body').on('submit', '.cart_coupon',function(e){
		e.preventDefault();
		$('#trigger_coupon').trigger('click');
	})
	$(document.body).on('payment_method_selected',function(){

	});
})( jQuery );
