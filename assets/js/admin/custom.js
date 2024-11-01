jQuery(function($){
	"use stric";
	$('body').on('click', '.woocommerce-image-upload', function(e){
		e.preventDefault();
		var button = $(this).closest('.image-temp_container'),
			custom_uploader = wp.media({
				title: 'Insert image',
				library : {
					type : 'image'
				},
				button: {
					text: 'Use this image' // button label text
				},
				multiple: false // for multiple image selection set to true
			}).on('select', function() { // it also has "open" and "close" events
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$(button).find('input').val(attachment.id);
				$(button).find('.image-actions').fadeIn();
				$(button).find('.img-container').html('<a href="#" class="woocommerce-image-upload"><img class="true_pre_image" src="' + attachment.url + '" style="max-width:405px;width: auto; max-height: 80px;height: auto;" /></a>');
			}).open();
	});
	$('body').on('click', '.woocommerce-remove-image', function(){
		$(this).closest('.image-temp_container').find('.image-actions').hide();
		$(this).closest('.image-temp_container').find('.img-container').html('<a href="#" class="woocommerce-image-upload button">Upload image</a>');
		$(this).closest('.image-temp_container').find('input').val(0);
		return false;
	});
	$('.colorpickpreview').on('click',function(e){
		e.preventDefault();
		e.stopPropagation();
		$( '.iris-picker' ).hide();
		$this = $(this).parent().find('input');
		$this.focus();
		$(this).parent().find( '.iris-picker' ).css('display','block');
		$this.data( 'original-value', $this.val() );
	});
});