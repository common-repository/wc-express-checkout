<?php
/**
 * Template Name: Express Checkout Template
 *
 * @since 1.0
 * @version 1.0
 */
woocommerce_express_checkout_helper()->get_header();
while ( have_posts() ) :
	the_post();
	?>
		<span class="border-bottom mb-4 d-block"></span>
	<?php
	the_content();
endwhile;
woocommerce_express_checkout_helper()->get_footer();
