<?php

/**
 * Shortcodes
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

/*error_reporting(E_ALL);
ini_set("display_errors", 1);*/


/**
 * Woo Alert
 */

if (! function_exists('sc_woo_alert')) {
	function sc_woo_alert($attr) {

		if( !function_exists('is_woocommerce') ) return '';

		$output = '';

		if( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ) {

			$output = '<div class="woocommerce-notices-wrapper"> <div class="woocommerce-error alert alert_error" style=""> <div class="alert_icon"><svg viewBox="0 0 28 28"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><circle cx="14" cy="20" r="0.33" class="path" style=""></circle><line x1="14" y1="8.72" x2="14" y2="16.72" class="path" style=""></line><path d="M12.6,3.42,1.54,22.58A1.61,1.61,0,0,0,2.93,25H25.07a1.61,1.61,0,0,0,1.39-2.42L15.4,3.42A1.61,1.61,0,0,0,12.6,3.42Z" class="path" style=""></path></g></svg></div> <div class="alert_wrapper"> '.__( 'Preview error', 'woocommerce' ).' </div> <a href="#" class="close mfn-close-icon"><span class="icon" style="">✕</span></a> </div> <div class="woocommerce-message alert alert_success" role="alert" tabindex="-1" style=""> <div class="alert_icon"><svg viewBox="0 0 28 28"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><polyline points="8.07 13 12.36 18.29 19.93 9.71" class="path" style=""></polyline><circle cx="14" cy="14" r="12" class="path" style=""></circle></g></svg></div> <div class="alert_wrapper">'.__( 'Preview success', 'woocommerce' ).'</div> <a href="#" class="close mfn-close-icon"><span class="icon" style="">✕</span></a> </div> </div>';
		}else{
			ob_start();

				if( is_product() ) {
					do_action( 'woocommerce_before_single_product' );
				}else if( is_shop() || is_product_category() || is_product_tag() ) {
					wc_print_notices();
				}

			$output = ob_get_clean();
		}

		return $output;
	}
}


/**
 * Order steps
 */

if (! function_exists('sc_order_steps')) {
	function sc_order_steps($attr) {
		global $mfn_global;

		if( !function_exists('is_woocommerce') ) return '';

		$classes = array();

		if( is_cart() ){
			$step = 1;
		}elseif( is_wc_endpoint_url( 'order-received' ) ){
			$step = 3;
		}elseif( is_checkout() ){
			$step = 2;
		}

		if( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ) {
			$step = 2;
		}

		$cart_label = !empty($attr['cart_label']) ? $attr['cart_label'] : __( 'Cart', 'woocommerce' );
		$checkout_label = !empty($attr['checkout_label']) ? $attr['checkout_label'] : __( 'Checkout', 'woocommerce' );
		$order_label = !empty($attr['order_label']) ? $attr['order_label'] : __( 'Order', 'woocommerce' );

		$output = '<ul class="mfn-checkout-steps">
				<li class="mfn-checkout-step-cart '.( isset($step) && $step >= 1 ? 'active' : null ).'"><span class="mfn-step-number">'.($step > 1 ? '<i class="icon-check" aria-hidden="true"></i>' : 1).'</span> <span class="mfn-step-label">'.$cart_label .'</span></li>
				<li class="mfn-checkout-step-checkout '.( isset($step) && $step >= 2 ? 'active' : null ).'"><span class="mfn-step-number">'.($step > 2 ? '<i class="icon-check" aria-hidden="true"></i>' : 2).'</span> <span class="mfn-step-label">'. $checkout_label .'</span></li>
				<li class="mfn-checkout-step-order '.( isset($step) && $step == 3 ? 'active' : null ).'"><span class="mfn-step-number">'.($step == 3 ? '<i class="icon-check" aria-hidden="true"></i>' : 3).'</span> <span class="mfn-step-label">'. $order_label .'</span></li>
			</ul>';

		return $output;
	}
}

/**
 * Thank you thanks
 */

if (! function_exists('sc_thankyou_overview')) {
	function sc_thankyou_overview($attr) {
		extract(shortcode_atts(array(
			'layout' => '',
		), $attr));

		if( !function_exists('is_woocommerce') ) return '';

		$order_id = false;
		$order = false;
		$output = '';

		if( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ) {
			$args = array(
			    'limit' => 1,
			    'type' => 'shop_order'
			);
			$order = wc_get_orders($args);
			if( !empty($order) ){
				$order_id = $order[0]->get_id();
			}
		}else if( !empty( $_GET['key'] ) ) {
		    $order_id  = wc_get_order_id_by_order_key($_GET['key']);
		}

		if( $order_id ) $order = wc_get_order( $order_id );

	    ob_start();

	    if( $order ){
	    	do_action( 'woocommerce_before_thankyou', $order->get_id() );
	    	if ( $order->has_status( 'failed' ) ) :
	    		echo '<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">'.esc_html__( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ).'</p>';

				echo '<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">';
					echo '<a href="'. esc_url( $order->get_checkout_payment_url() ) .'" class="button pay">'. esc_html__( 'Pay', 'woocommerce' ) .'</a>';
					if ( is_user_logged_in() ) :
						echo '<a href="'. esc_url( wc_get_page_permalink( 'myaccount' ) ) .'" class="button pay">'. esc_html__( 'My account', 'woocommerce' ) .'</a>';
					endif;
				echo '</p>';

	    	else:

	    		wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) );
	    		echo '<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

					<li class="woocommerce-order-overview__order order">
						'.esc_html__( 'Order number:', 'woocommerce' ).'
						<strong>'.$order->get_order_number().'</strong>
					</li>

					<li class="woocommerce-order-overview__date date">
						'.esc_html__( 'Date:', 'woocommerce' ).'
						<strong>'.wc_format_datetime( $order->get_date_created() ).'</strong>
					</li>';

					if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) :
						echo '<li class="woocommerce-order-overview__email email">
							'.esc_html__( 'Email:', 'woocommerce' ).'
							<strong>'.$order->get_billing_email().'</strong>
						</li>';
					endif;

					echo '<li class="woocommerce-order-overview__total total">
						'.esc_html__( 'Total:', 'woocommerce' ).'
						<strong>'.$order->get_formatted_order_total().'</strong>
					</li>';

					if ( $order->get_payment_method_title() && empty($attr['vb']) ) :
						echo '<li class="woocommerce-order-overview__payment-method method">
							'.esc_html__( 'Payment method:', 'woocommerce' ).'
							<strong>'.wp_kses_post( $order->get_payment_method_title() ).'</strong>
						</li>';
					endif;

				echo '</ul>';
	    	endif;

	    	echo '<div class="mfn-thanks-overview-payment">';
	    		if( $order->get_payment_method_title() && empty($attr['vb']) ) {
	    			do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
	    		}else{
	    			esc_html__('Pay with cash upon delivery.', 'woocommerce');
	    		}
	    	echo '</div>';

	    }else{
	    	wc_get_template( 'checkout/order-received.php', array( 'order' => false ) );
	    }

		//wc_get_template( 'checkout/thankyou.php', array('order' => $order) );
		$output .= ob_get_clean();

		return $output;
	}
}

/**
 * Thank you thanks
 */

if (! function_exists('sc_thankyou_order')) {
	function sc_thankyou_order($attr) {
		extract(shortcode_atts(array(
			'layout' => '',
		), $attr));

		if( !function_exists('is_woocommerce') ) return '';

		$order_id = false;
		$order = false;
		$output = '';

		if( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ) {
			$args = array(
			    'limit' => 1,
			    'type' => 'shop_order'
			);
			$order = wc_get_orders($args);
			if( !empty($order) ){
				$order_id = $order[0]->get_id();
			}
		}else if( !empty( $_GET['key'] ) ) {
		    $order_id  = wc_get_order_id_by_order_key($_GET['key']);
		}

		if( $order_id ) $order = wc_get_order( $order_id );

	    if( $order && empty($attr['vb']) ){
	    	ob_start();
	    	do_action( 'woocommerce_thankyou', $order->get_id() );
	    	$output .= ob_get_clean();
	    }else if( !empty($attr['vb']) ) {
			$output .= '<section class="woocommerce-order-details"> <h2 class="woocommerce-order-details__title">'.__("Order details", "woocommerce").'</h2> <table class="woocommerce-table woocommerce-table--order-details shop_table order_details"> <thead> <tr> <th class="woocommerce-table__product-name product-name">'.__("Product", "woocommerce").'</th> <th class="woocommerce-table__product-table product-total">'.__("Total", "woocommerce").'</th> </tr> </thead> <tbody> <tr class="woocommerce-table__line-item order_item"> <td class="woocommerce-table__product-name product-name"> <a href="#">'.__("Product", "woocommerce").' 1</a> <strong class="product-quantity">×&nbsp;3</strong><ul class="wc-item-meta"><li><strong class="wc-item-meta-label">Color:</strong> <p>Green</p></li></ul></td> <td class="woocommerce-table__product-total product-total">'.wc_price(10).'</td> </tr> </tbody> <tfoot> <tr> <th scope="row">'.__('Subtotal', 'woocommerce').':</th> <td>'.wc_price(10).'</td> </tr> <tr> <th scope="row">'.__('Shipping', 'woocommerce').':</th> <td>'.wc_price(5).'</td> </tr> <tr> <th scope="row">'.__('Tax', 'woocommerce').':</th> <td>'.wc_price(2).'</td> </tr> <tr> <th scope="row">'.__('Payment method', 'woocommerce').':</th> <td>'.__('Pay with cash upon delivery.', 'woocommerce').'</td> </tr> <tr> <th scope="row">'.__('Total', 'woocommerce').':</th> <td>'.wc_price(17).'</td> </tr> </tfoot> </table> </section>
 				<section class="woocommerce-customer-details"> <section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses"> <div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1"> <h2 class="woocommerce-column__title">'.__('Billing address', 'woocommerce').'</h2> <address> Lorem ipsum dolor sit amet<br>Consectetur adipiscing elit 22 <p class="woocommerce-customer-details--phone">112 223 445</p> <p class="woocommerce-customer-details--email">noreply@gmail.com</p> </address> </div> <div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2"> <h2 class="woocommerce-column__title">'.__('Shipping address', 'woocommerce').'</h2> <address> Lorem ipsum dolor sit amet<br>Consectetur adipiscing elit 24 </address> </div> </section> </section>';
		}



		return $output;
	}
}

/**
 * Checkout
 */

if (! function_exists('sc_checkout')) {
	function sc_checkout($attr) {
		extract(shortcode_atts(array(
			'layout' => '',
		), $attr));

		if( !function_exists('is_woocommerce') ) return '';

		$classes = array('mfn-checkout-wrapper');

		$classes[] = !empty($layout) ? 'mfn-be-checkout-'.$layout : 'mfn-be-checkout';

		$output = '<div class="'.implode(' ', $classes).'">';
		$output .= do_shortcode( '[woocommerce_checkout]' );
		$output .= '</div>';
		return $output;
	}
}

/**
 * Cart
 */

if (! function_exists('sc_cart_table')) {
	function sc_cart_table($attr) {

		extract(shortcode_atts(array(
			'layout' => '',
		), $attr));

		if( !function_exists('is_woocommerce') ) return '';

		$output = '';

		$classes = array('mfn-cart-table-tmpl-wrapper');

		if( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ) $classes[] = 'woocommerce-cart';

		$output .= '<div class="'.implode(' ', $classes).'">';

		if( empty($attr['vb']) && !apply_filters('bebuilder_preview', false) ) remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals');

		if( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ) {
			$output .= do_shortcode( '[woocommerce_cart]' );
		}else{
			ob_start();
			wc_get_template('cart/cart.php');
			$output .= ob_get_clean();
		}

		$output .= '</div>';

		return $output;
	}
}


/**
 * Cart totals
 */

if (! function_exists('sc_cart_totals')) {
	function sc_cart_totals($attr) {

		extract(shortcode_atts(array(
			'layout' => '',
		), $attr));

		if( !function_exists('is_woocommerce') ) return '';

		$output = '';

		global $woocommerce;

		if( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ){
			$output .= do_shortcode( '[woocommerce_cart]' );
		}else{
			/*ob_start();
			add_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals');
			do_action( 'woocommerce_cart_collaterals' );
			$output .= ob_get_clean();*/

			WC()->cart->calculate_shipping();

			ob_start();
			do_action( 'woocommerce_before_cart_collaterals' );

			echo '<div class="cart-collaterals">';
			woocommerce_cart_totals();
			echo '</div>';

			do_action( 'woocommerce_after_cart' );
			$output .= ob_get_clean();
		}

		return $output;

	}
}

/**
 * Cart cross sells
 */

if (! function_exists('sc_cart_cross_sells')) {
	function sc_cart_cross_sells($attr) {

		if( !function_exists('is_woocommerce') ) return;
		remove_standard_woo_actions_archive();

		$args = array(
			'posts_per_page' => !empty($attr['products']) ? $attr['products'] : 4,
			'columns'        => !empty($attr['columns']) ? $attr['columns'] : 4,
			'order'          => 'desc',
			'orderby'        => 'rand',
			'post_type' => 'product',
		);

		if( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ){
			$cs_query = new WP_Query($args);
		}else{
			$cross_sells_ids_in_cart = array();
			$cross_sells_id = array();

		    foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
		        if ( $values['quantity'] > 0 ) {
		            $cross_sells_ids_in_cart = array_merge( $values['data']->get_cross_sell_ids(), $cross_sells_ids_in_cart );
		        }
		    }

		    $cross_sellsq = wp_parse_id_list( $cross_sells_ids_in_cart );

		    if( !empty($cross_sellsq) && count($cross_sellsq) > 0 ) {
		    	foreach($cross_sellsq as $csp) {
		    		if( get_post_type($csp) == 'product' ) {
						$cross_sells_id[] = $csp;
					}elseif( get_post_type($csp) == 'product_variation' ) {
						$cross_sells_id[] = wp_get_post_parent_id($csp);
					}
		    	}
		    }

		    $args['post__in'] = $cross_sells_id;

		    $cs_query = new WP_Query($args);
		}

		if(empty($attr['button'])) {
			$attr['button'] = 0;
		}

		if(empty($attr['description'])) {
			$attr['description'] = 0;
		}

		$classes = array( 'grid', 'col-'.$attr['columns'] );

		// background

		if( ! empty( mfn_opts_get('background-archives-product') ) ){
			$classes[] = 'has-background-color';
		}

		$ul_classes = 'columns-'.$attr['columns'];

		$output = '<section class="mfn-cross-sells">';

		$title_tag = !empty($attr['heading_tag']) ? $attr['heading_tag'] : 'h3';
		$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title heading">'.( !empty($attr['heading']) ? $attr['heading'] : __( 'You may be interested in&hellip;', 'woocommerce' ) ).'</'. mfn_allowed_title_tag($title_tag) .'>';
		$output  .= '<ul class="products '.$ul_classes.'">';

		if ( $cs_query->have_posts() ) :
			while ( $cs_query->have_posts() ) : $cs_query->the_post();
				$product = wc_get_product(get_the_ID());
				$output .= Mfn_Builder_Woo_Helper::productslist($product, $attr, $classes);
			endwhile;
			wp_reset_postdata();
		endif;

		$output .= '</ul>';
		$output .= '</section>';

		return $output;

	}
}


if (! function_exists('sc_hotspot')) {
	function sc_hotspot($attr){

		extract( shortcode_atts(array(
			'image'			=> '',
			'marker_animation' => '',
			'content_animation' => '',
			'img_height' => '',
			'img_height_style' => '',
			'style' => '',
		), $attr) );

		$id_canvas = false;
		$id_canvas_wrapper = false;

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$classes = array('mfn-image-hotspot');

		if( !empty($marker_animation) ) $classes[] = 'mfn-image-hotspot-point-'.$marker_animation;
		if( !empty($content_animation) ) $classes[] = 'mfn-image-hotspot-content-'.$content_animation;
		if( !empty($img_height) ) $classes[] = 'mfn-image-hotspot-height';
		if( !empty($img_height_style) ) $classes[] = 'mfn-image-hotspot-height-cover';

		if( !empty( $style ) ) {
			$classes[] = 'mfn-hotspot-style-'.$style;
			$id_canvas = 'canvas'.Mfn_Builder_Helper::unique_ID();
			$id_canvas_wrapper = $id_canvas.'wrapper';
		}

		$output = '';
		$script = '';

		$output .= '<div class="'.implode(' ', $classes).'"><div class="hotspot-wrapper">';
	    $output .= '<div class="hotspot-image" id="'.$id_canvas_wrapper.'"><img class="scale-with-grid" src="'.( !empty($image) ? $image : get_theme_file_uri( '/muffin-options/svg/placeholders/image.svg' ) ).'" alt=""></div>';

	    if( !empty($id_canvas) ) {
	    	$script .= '<script>var mfn_hotspot_'.$id_canvas.' = {
	    		c: false,
	    		ctx: false,
	    		wrapper: false,
	    		width: false,
	    		height: false,
	    		r_y: "y",
	    		r_x: "x",
	    		r_y_o: "y_offset",
	    		r_x_o: "x_offset",

	    		init: function() {

	    			if( jQuery("#'.$id_canvas_wrapper.' canvas").length ) jQuery("#'.$id_canvas_wrapper.' canvas").remove();

	    			mfn_hotspot_'.$id_canvas.'.c = document.createElement("canvas");
	    			mfn_hotspot_'.$id_canvas.'.ctx = mfn_hotspot_'.$id_canvas.'.c.getContext("2d");
	    			mfn_hotspot_'.$id_canvas.'.wrapper = document.getElementById("'.$id_canvas_wrapper.'");
	    			mfn_hotspot_'.$id_canvas.'.width = mfn_hotspot_'.$id_canvas.'.wrapper.offsetWidth;
		    		mfn_hotspot_'.$id_canvas.'.height = mfn_hotspot_'.$id_canvas.'.wrapper.offsetHeight;
		    		mfn_hotspot_'.$id_canvas.'.c.width = mfn_hotspot_'.$id_canvas.'.width;
		    		mfn_hotspot_'.$id_canvas.'.c.height = mfn_hotspot_'.$id_canvas.'.height;

		    		mfn_hotspot_'.$id_canvas.'.r_y = "y";
		    		mfn_hotspot_'.$id_canvas.'.r_x = "x";
		    		mfn_hotspot_'.$id_canvas.'.r_y_o = "y_offset";
		    		mfn_hotspot_'.$id_canvas.'.r_x_o = "x_offset";

		    		if( jQuery(window).width() < 767 ) {
			    		mfn_hotspot_'.$id_canvas.'.r_y += "_mobile";
			    		mfn_hotspot_'.$id_canvas.'.r_x += "_mobile";
			    		mfn_hotspot_'.$id_canvas.'.r_y_o += "_mobile";
		    			mfn_hotspot_'.$id_canvas.'.r_x_o += "_mobile";
	    			}else if( jQuery(window).width() < 959 ) {
			    		mfn_hotspot_'.$id_canvas.'.r_y += "_tablet";
			    		mfn_hotspot_'.$id_canvas.'.r_x += "_tablet";
			    		mfn_hotspot_'.$id_canvas.'.r_y_o += "_tablet";
		    			mfn_hotspot_'.$id_canvas.'.r_x_o += "_tablet";
			    	}else if( jQuery(window).width() < 1440 ) {
			    		mfn_hotspot_'.$id_canvas.'.r_y += "_laptop";
			    		mfn_hotspot_'.$id_canvas.'.r_x += "_laptop";
			    		mfn_hotspot_'.$id_canvas.'.r_y_o += "_laptop";
		    			mfn_hotspot_'.$id_canvas.'.r_x_o += "_laptop";
			    	}

		    		mfn_hotspot_'.$id_canvas.'.draw();
	    		},

	    		p_c: [';
	    }

	    if( !empty($attr['hotspots']) ) {
	    	foreach($attr['hotspots'] as $ht) {

	    		/*echo '<pre>';
	    		print_r($ht);
	    		echo '</pre>';*/

	    		$line_color = !empty($attr['css_hotspot_marker_default_bg']['val']) ? $attr['css_hotspot_marker_default_bg']['val'] : '#0089f7';

	    		$point_id = 'hs_point_'.$ht['hash'];

	    		$point_classes = array('hotspot-point', 'hotspot-point-'.$ht['hash']);
	    		$marker_classes = array('hotspot-marker');

	    		$point_classes[] = !empty($ht['content_position']) ? 'hotspot-point-'.$ht['content_position'] : 'hotspot-point-top';
	    		$point_classes[] = !empty($ht['content_position_laptop']) ? 'hotspot-point-laptop-'.$ht['content_position_laptop'] : 'hotspot-point-laptop-top';
	    		$point_classes[] = !empty($ht['content_position_tablet']) ? 'hotspot-point-tablet-'.$ht['content_position_tablet'] : 'hotspot-point-tablet-top';
	    		$point_classes[] = !empty($ht['content_position_mobile']) ? 'hotspot-point-mobile-'.$ht['content_position_mobile'] : 'hotspot-point-mobile-top';

	    		$point_classes[] = !empty($ht['hotspots_yaxis_'.$ht['hash']]) ? 'hotspot-point-y-'.$ht['hotspots_yaxis_'.$ht['hash']] : 'hotspot-point-y-top';
	    		$point_classes[] = !empty($ht['hotspots_xaxis_'.$ht['hash']]) ? 'hotspot-point-x-'.$ht['hotspots_xaxis_'.$ht['hash']] : 'hotspot-point-x-left';

	    		$marker_classes[] = !empty($ht['type']) ? 'marker-'.$ht['type'] : 'marker-default';

	    		$output .= '<div id="'.$point_id.'" data-id="'.$ht['hash'].'" class="'.implode(' ', $point_classes).'">';

	    		if( !empty($ht['link']) ){
	    			$link_attr = '';
	    			if( !empty($ht['link_title']) ) $link_attr .= 'title="'.be_dynamic_data($ht['link_title']).'"';
	    			if( !empty($ht['link_target']) ) $link_attr .= ' target="'.$ht['link_target'].'"';
	    			$output .= '<a href="'.be_dynamic_data($ht['link']).'" '.$link_attr.' class="'.implode(' ', $marker_classes).'">';
	    		}else{
	    			$output .= '<div class="'.implode(' ', $marker_classes).'">';
	    		}

	    		if( in_array('marker-icon', $marker_classes) ){
	    			$output .= '<i class="'.( !empty($ht['icon']) ? $ht['icon'] : 'icon-plus' ).'"></i>';
	    			$line_color = !empty($attr['css_hotspot_marker_icon_bg']['val']) ? $attr['css_hotspot_marker_icon_bg']['val'] : '#0089f7';
	    		}else if( in_array('marker-text', $marker_classes) ){
	    			$output .= !empty($ht['text']) ? $ht['text'] : 'Point text';
	    			$line_color = !empty($attr['css_hotspot_marker_text_bg']['val']) ? $attr['css_hotspot_marker_text_bg']['val'] : '#fff';
	    		}

	    		$output .= '</'.( !empty($ht['link']) ? 'a' : 'div' ).'>';

	    		if( empty($style) && !empty($ht['content']) ){
	    			$output .= '<div class="hotspot-content">'.do_shortcode(be_dynamic_data($ht['content'])).'</div>';
	    		}

	    		$output .= '</div>';


	    		if( !empty($id_canvas) ) {

	    			$script .= '{';

	    			$x = 0;
	    			$y = 0;

	    			$y_dir = 'top';
	    			$x_dir = 'left';

	    			$matchesy = array(0);
	    			$matchesx = array(0);
	    			$matchesylaptop = array();
	    			$matchesxlaptop = array();
	    			$matchesytablet = array();
	    			$matchesxtablet = array();
	    			$matchesymobile = array();
	    			$matchesxmobile = array();

	    			$script .= '
    				line_color: "'.$line_color.'",
    				id: "'.$point_id.'",
    				width: 0,
    				height: 0,';

	    			if( !empty($ht['hotspots_yaxis_'.$ht['hash']]) ) $y_dir = 'bottom';
	    			if( !empty($ht['hotspots_xaxis_'.$ht['hash']]) ) $x_dir = 'right';

	    			$y = !empty($ht['val']['css_y_'.$ht['hash'].'_'.$y_dir.'']) ? (float) preg_replace('/[^0-9]+/', '', $ht['val']['css_y_'.$ht['hash'].'_'.$y_dir.'']['val']) : 0;
	    			$x = !empty($ht['val']['css_x_'.$ht['hash'].'_'.$x_dir.'']) ? (float) preg_replace('/[^0-9]+/', '', $ht['val']['css_x_'.$ht['hash'].'_'.$x_dir.'']['val']) : 0;

	    			$y_laptop = $y;
	    			$x_laptop = $x;

	    			if( !empty($ht['val']['css_y_'.$ht['hash'].'_'.$y_dir.'_laptop']) ) $y_laptop = (float) preg_replace('/[^0-9]+/', '', $ht['val']['css_y_'.$ht['hash'].'_'.$y_dir.'_laptop']['val']);
	    			if( !empty($ht['val']['css_x_'.$ht['hash'].'_'.$x_dir.'_laptop']) ) $x_laptop = (float) preg_replace('/[^0-9]+/', '', $ht['val']['css_x_'.$ht['hash'].'_'.$x_dir.'_laptop']['val']);

	    			$y_tablet = $y_laptop;
	    			$x_tablet = $x_laptop;

	    			if( !empty($ht['val']['css_y_'.$ht['hash'].'_'.$y_dir.'_tablet']) ) $y_tablet = (float) preg_replace('/[^0-9]+/', '', $ht['val']['css_y_'.$ht['hash'].'_'.$y_dir.'_tablet']['val']);
	    			if( !empty($ht['val']['css_x_'.$ht['hash'].'_'.$x_dir.'_tablet']) ) $x_tablet = (float) preg_replace('/[^0-9]+/', '', $ht['val']['css_x_'.$ht['hash'].'_'.$x_dir.'_tablet']['val']);

	    			$y_mobile = $y_tablet;
	    			$x_mobile = $x_tablet;

	    			if( !empty($ht['val']['css_y_'.$ht['hash'].'_'.$y_dir.'_mobile']) ) $y_mobile = (float) preg_replace('/[^0-9]+/', '', $ht['val']['css_y_'.$ht['hash'].'_'.$y_dir.'_mobile']['val']);
	    			if( !empty($ht['val']['css_x_'.$ht['hash'].'_'.$x_dir.'_mobile']) ) $x_mobile = (float) preg_replace('/[^0-9]+/', '', $ht['val']['css_x_'.$ht['hash'].'_'.$x_dir.'_mobile']['val']);


	    			if( !empty($ht['val']['css_marker_'.$ht['hash'].'_top']) ) preg_match('/-?\d+/', $ht['val']['css_marker_'.$ht['hash'].'_top']['val'], $matchesy);
	    			if( !empty($ht['val']['css_marker_'.$ht['hash'].'_top_laptop']) ) preg_match('/-?\d+/', $ht['val']['css_marker_'.$ht['hash'].'_top_laptop']['val'], $matchesylaptop);
	    			if( !empty($ht['val']['css_marker_'.$ht['hash'].'_top_tablet']) ) preg_match('/-?\d+/', $ht['val']['css_marker_'.$ht['hash'].'_top_tablet']['val'], $matchesytablet);
	    			if( !empty($ht['val']['css_marker_'.$ht['hash'].'_top_mobile']) ) preg_match('/-?\d+/', $ht['val']['css_marker_'.$ht['hash'].'_top_mobile']['val'], $matchesymobile);

	    			if( !empty($ht['val']['css_marker_'.$ht['hash'].'_left']) ) preg_match('/-?\d+/', $ht['val']['css_marker_'.$ht['hash'].'_left']['val'], $matchesx);
	    			if( !empty($ht['val']['css_marker_'.$ht['hash'].'_left_laptop']) ) preg_match('/-?\d+/', $ht['val']['css_marker_'.$ht['hash'].'_left_laptop']['val'], $matchesxlaptop);
	    			if( !empty($ht['val']['css_marker_'.$ht['hash'].'_left_tablet']) ) preg_match('/-?\d+/', $ht['val']['css_marker_'.$ht['hash'].'_left_tablet']['val'], $matchesxtablet);
	    			if( !empty($ht['val']['css_marker_'.$ht['hash'].'_left_mobile']) ) preg_match('/-?\d+/', $ht['val']['css_marker_'.$ht['hash'].'_left_mobile']['val'], $matchesxmobile);

	    			if( empty($matchesy[0]) ) $matchesy[0] = 0;
	    			if( empty($matchesx[0]) ) $matchesx[0] = 0;

	    			if( empty($matchesylaptop[0]) ) $matchesylaptop = $matchesy;
	    			if( empty($matchesxlaptop[0]) ) $matchesxlaptop = $matchesx;

	    			if( empty($matchesytablet[0]) ) $matchesytablet = $matchesylaptop;
	    			if( empty($matchesxtablet[0]) ) $matchesxtablet = $matchesxlaptop;

	    			if( empty($matchesymobile[0]) ) $matchesymobile = $matchesytablet;
	    			if( empty($matchesxmobile[0]) ) $matchesxmobile = $matchesxtablet;

    				$script .= '
    				y_dir: "'.$y_dir.'",
    				y: '.$y.',
					y_laptop: '.$y_laptop.',
					y_tablet: '.$y_tablet.',
					y_mobile: '.$y_mobile.',
    				x_dir: "'.$x_dir.'",
    				x: '.$x.',
    				x_laptop: '.$x_laptop.',
    				x_tablet: '.$x_tablet.',
    				x_mobile: '.$x_mobile.',
    				y_offset: '.(float) $matchesy[0].',
    				y_offset_laptop: '.(float) $matchesylaptop[0].',
    				y_offset_tablet: '.(float) $matchesytablet[0].',
    				y_offset_mobile: '.(float) $matchesymobile[0].',
    				x_offset: '.(float) $matchesx[0].',
    				x_offset_laptop: '.(float) $matchesxlaptop[0].',
    				x_offset_tablet: '.(float) $matchesxtablet[0].',
    				x_offset_mobile: '.(float) $matchesxmobile[0].',
    				},
    				';

	    		}
	    	}
	    }


	    if( !empty( $script ) ){

	    $script .= '],';

	    $script .= '
	    draw: function() {

	    mfn_hotspot_'.$id_canvas.'.p_c.forEach(function(item) {

	    	item.height = document.getElementById(item.id).offsetHeight;
	    	item.width = document.getElementById(item.id).offsetWidth;

			var y = 0;
			var x = 0;
			var y_offset = 0;
			var x_offset = 0;

			if( item.y_dir == "top" ){
				y = (item[mfn_hotspot_'.$id_canvas.'.r_y] * mfn_hotspot_'.$id_canvas.'.height / 100) + (parseFloat(item.height)/2);
			}else{
				y = mfn_hotspot_'.$id_canvas.'.c.height - ((item[mfn_hotspot_'.$id_canvas.'.r_y] * mfn_hotspot_'.$id_canvas.'.height / 100) + (parseFloat(item.height)/2));
			}

			if( item.x_dir == "left" ){
				x = (item[mfn_hotspot_'.$id_canvas.'.r_x] * mfn_hotspot_'.$id_canvas.'.width / 100) + ( parseFloat(item.width) / 2);
			}else{
				x = mfn_hotspot_'.$id_canvas.'.c.width - (item[mfn_hotspot_'.$id_canvas.'.r_x] * mfn_hotspot_'.$id_canvas.'.width / 100 ) - ( parseFloat(item.width) / 2);
			}

			y_offset = y + item[mfn_hotspot_'.$id_canvas.'.r_y_o];
			x_offset = x + item[mfn_hotspot_'.$id_canvas.'.r_x_o];

			mfn_hotspot_'.$id_canvas.'.ctx.beginPath();
			mfn_hotspot_'.$id_canvas.'.ctx.moveTo(x, y);
			mfn_hotspot_'.$id_canvas.'.ctx.lineTo(x_offset, y_offset);
			mfn_hotspot_'.$id_canvas.'.ctx.lineWidth = 2;
			mfn_hotspot_'.$id_canvas.'.ctx.strokeStyle = item.line_color;
			mfn_hotspot_'.$id_canvas.'.ctx.stroke();
			});
			mfn_hotspot_'.$id_canvas.'.wrapper.appendChild(mfn_hotspot_'.$id_canvas.'.c);
	    }';


	    	$output .= $script.'}; jQuery(document).ready(function() { mfn_hotspot_'.$id_canvas.'.init(); jQuery(window).on("debouncedresize", function() { setTimeout(function() {mfn_hotspot_'.$id_canvas.'.init(); },100); }); });</script>';

	    }

	    //print_r($attr['hotspots']);

	    $output .= '</div></div>';

    	return $output;

	}
}

/**
 * Banner box
 */

if (! function_exists('sc_banner_box')) {
	function sc_banner_box($attr) {
		extract(shortcode_atts(array(
			'image'			=> '',
			'size' 	=> 'full',
			'title'			=> '',
			'title_tag'			=> '',
			'subtitle'		=> '',
			'subtitle_tag'		=> '',
			'bb_excerpt'	=> '',
			'link'			=> '',
			'link_title'	=> '',
		), $attr));

		$classes = array('mfn-banner-box');

		$title = be_dynamic_data($title);
		$subtitle = be_dynamic_data($subtitle);
		$bb_excerpt = be_dynamic_data($bb_excerpt);
		$link_title = be_dynamic_data($link_title);
		$link = be_dynamic_data($link);

		$wrapper_attr = '';

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		if( !empty( $attr['style'] ) ) $classes[] = 'mfn-banner-box-boxed';
		if( !empty( $attr['image_height'] ) ) $classes[] = 'mfn-banner-box-height';
		if( !empty( $attr['overlay'] ) ) $classes[] = 'mfn-banner-box-image-overlay';
		if( !empty( $attr['hover_effect'] ) ) $classes[] = 'mfn-banner-box-image-'.$attr['hover_effect'];
		if( !empty( $attr['cta_hover_effect'] ) ) $classes[] = 'mfn-banner-box-cta-'.$attr['cta_hover_effect'];
		if( !empty( $attr['hidden_elements_mobile'] ) ) {
			if( $attr['hidden_elements_mobile'] == '1' ){
				$classes[] = 'mfn-show-hidden-elements-on-mobile';
			}else if( $attr['hidden_elements_mobile'] == '2' ){
				$classes[] = 'mfn-show-hidden-elements-on-tablet';
			}
		}

		// size

		if( empty($size) ){
			$size = 'full';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		$subtitle_class = '';
		if( ! empty($subtitle_tag) ){
			if( 'p.lead' == $subtitle_tag ){
				$subtitle_tag = 'p';
				$subtitle_class = 'lead';
			}
		}

		$title_tag = !empty($attr['title_tag']) ? $attr['title_tag'] : 'h3';
		$subtitle_tag = !empty($attr['subtitle_tag']) ? $attr['subtitle_tag'] : 'p';

		$order = !empty( $attr['order'] ) ? $attr['order'] : 'title,subtitle,excerpt,divider,cta';
		$order_arr = explode('divider', $order);

		$visible = $order_arr[0];
		$hidden = $order_arr[1];

		$visible_arr = explode(',', $visible);
		$hidden_arr = explode(',', $hidden);

		$elements = array(
			'subtitle' => !empty($subtitle) || !empty($_GET['visual']) || !empty($attr['vb']) ? '<'. mfn_allowed_title_tag($subtitle_tag) .' '.( empty( $subtitle ) ? 'style="display: none;"' : '' ).' class="subtitle banner-item '. esc_attr($subtitle_class) .'">'.$subtitle.'</'. mfn_allowed_title_tag($subtitle_tag) .'>' : '',
			'title' => !empty($title) || !empty($_GET['visual']) || !empty($attr['vb']) ? '<'. mfn_allowed_title_tag($title_tag) .' '.( empty( $title ) ? 'style="display: none;"' : '' ).' class="title banner-item '. esc_attr($title_class) .'">'.$title.'</'. mfn_allowed_title_tag($title_tag) .'>' : '',
			'excerpt' => !empty($bb_excerpt) || !empty($_GET['visual']) || !empty($attr['vb']) ? '<p '.( empty( $bb_excerpt ) ? 'style="display: none;"' : '' ).' class="excerpt">'.$bb_excerpt.'</p>' : '',
			'cta' => !empty($link_title) || !empty($_GET['visual']) || !empty($attr['vb']) ? '<div '.( empty( $link_title ) ? 'style="display: none;"' : '' ).' class="banner-cta banner-item cta-text">'.$link_title.'</div>' : '',
		);

		if( !empty( $attr['cta'] ) && $attr['cta'] == 'icon' ){
			$elements['cta'] = '<div class="banner-cta banner-item cta-icon"><i class="'.( !empty( $attr['cta_icon'] ) ? $attr['cta_icon'] : 'icon-right-1' ).'"></i></div>';
		}elseif( !empty( $attr['cta'] ) && $attr['cta'] == 'image' ){
			$elements['cta'] = '<div class="banner-cta banner-item cta-image"><img src="'.( !empty( $attr['cta_image'] ) ? $attr['cta_image'] : get_theme_file_uri( '/muffin-options/svg/placeholders/image.svg' ) ).'" alt=""></div>';
		}

		if( !empty( $attr['link_type'] ) && !empty( $attr['popup_id'] ) ){
			if( empty($link) ) $link = '#';
			$classes[] = 'open-mfn-popup';
			$wrapper_attr = 'data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		$target = false;

		if ( !empty($attr['target']) && 'lightbox' == $attr['target'] ) {
 			$target = 'rel="prettyphoto"';
 		} elseif ( !empty($attr['target']) ) {
 			$target = 'target="_blank"';
 		}

		$output = '';

		// start wrapper
		if( !empty($link) ){
			$output .= '<a '. $target .' '. $wrapper_attr .' href="'. $link .'" class="'. implode(' ', $classes) .'">';
		}else{
			$output .= '<div class="'.implode(' ', $classes).'">';
		}

	    $output .= '<div class="banner-image">';
				if( $image_output_before = mfn_get_attachment($image, $size) ){
					$output .= $image_output_before;
				} else {
					$output .= '<img class="scale-with-grid" src="'. esc_url($image) .'" alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
				}
	    $output .= '</div>';

	    if( !empty( $attr['bb_badge'] ) || !empty($_GET['visual']) || !empty($attr['vb']) ) $output .= '<div '.( empty( $attr['bb_badge'] ) ? 'style="display: none;"' : '' ).' class="banner-badge banner-badge-'.( !empty($attr['bb_badge_pos']) ? $attr['bb_badge_pos'] : 'top-right' ).'">'.( !empty( $attr['bb_badge'] ) ? be_dynamic_data($attr['bb_badge']) : '').'</div>';

	    $output .= '<div class="banner-desc">';
		    $output .= '<div class="banner-wrapper">';

		    	if( !empty($visible_arr) ){
		    		foreach( $visible_arr as $va ){
		    			if( !empty($va) && !empty($elements[$va]) ) $output .= $elements[$va];
		    		}
		    	}

		    	if( !empty($hidden_arr) && count($hidden_arr) > 1 ){
		    		$tmp_cta = '';
		    		foreach( $hidden_arr as $ha ){
		    			if( !empty($ha) && !empty($elements[$ha]) ) $tmp_cta .= $elements[$ha];
		    		}

		    		if( !empty($tmp_cta) || !empty($_GET['visual']) || !empty($attr['vb']) ) $output .= '<div '.( empty($tmp_cta) ? 'style="display: none;"' : '' ).' class="hidden-desc"><div class="hidden-wrapper">'.$tmp_cta.'</div></div>';
		    	}

		    $output .= '</div>';
	    $output .= '</div>';

		// end wrapper
		if( !empty($link) ){
			$output .= '</a>';
		}else{
			$output .= '</div>';
		}

		return $output;
	}
}

/**
 * HTML editor
 */

if (! function_exists('sc_html')) {
	function sc_html($attr){
		extract(shortcode_atts(array(
			'content'		=> '',
		), $attr));

		if( ! empty( $attr['vb'] ) && empty( $content ) ) {
			return '<div class="mfn-html-editor-wrapper"><div class="mfn-widget-placeholder"><img class="item-preview-image" src="'.get_theme_file_uri('/visual-builder/assets/_dark/svg/items/code.svg').'" alt="product rating"></div></div>';
		}

		if( !substr( $content, 0, 7 ) === "<iframe" ){

			$ready_content = str_replace('<!DOCTYPE html>', '', $content);
			$ready_content = str_replace('<html', '<div', $ready_content);
			$ready_content = str_replace('<head>', '', $ready_content);
			$ready_content = str_replace('</head>', '', $ready_content);
			$ready_content = str_replace('<body', '<div', $ready_content);
			$ready_content = str_replace('</body>', '</div>', $ready_content);
			$ready_content = str_replace('</html>', '</div>', $ready_content);

			libxml_use_internal_errors(true);

			$dom = new DOMDocument();
			$dom->loadHTML('<root>' . $ready_content . '</root>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($dom);

			foreach( $xpath->query('//*[not(node())]') as $node ) {
			    $node->parentNode->removeChild($node);
			}

			$ready_content = substr($dom->saveHTML(), 6, -8);

		}else{
			$ready_content = $content;
		}

		$output = '<div class="mfn-html-editor-wrapper">'.$ready_content.'</div>';

		return $output;
	}
}

/**
 * Spacer
 */

if (! function_exists('sc_spacer')) {
	function sc_spacer($attr){

		extract(shortcode_atts(array(
			'form'	=> '',
		), $attr));

		$output = '';

		if( !empty($form) ){
			$post = get_post($form);

			if( $post ){
				$output = do_shortcode('[contact-form-7 id="" title="'.$post->post_title.'"]');
			}
		}

		return $output;

	}
}

/**
 * CF7 contact form 7
 */

if (! function_exists('sc_cf7')) {
	function sc_cf7($attr){

		extract(shortcode_atts(array(
			'form'	=> '',
		), $attr));

		$output = '';

		if( !empty($form) ){
			$post = get_post($form);

			if( $post ){
				$output = do_shortcode('[contact-form-7 id="" title="'.$post->post_title.'"]');
			}
		}

		return $output;

	}
}

/**
 * Breadcrumbs
 */

if (! function_exists('sc_breadcrumbs')) {
	function sc_breadcrumbs($attr) {

		extract(shortcode_atts(array(
			'separator'	=> '',
		), $attr));

		$params = array();
		$output = '';

		if( !empty($separator) ) $params['separator'] = $separator;
		if( empty($attr['breadcrumb_home']) || $attr['breadcrumb_home'] == '0' ) {
			$params['include_home'] = 0;
		}else{
			$params['include_home'] = 1;
		}

		ob_start();
		mfn_breadcrumbs($params);
		$output = ob_get_clean();

		return $output;

	}
}

/**
 * Post share
 */

if (! function_exists('sc_share')) {
	function sc_share($attr) {

		extract(shortcode_atts(array(
			'label'	    => '',
			'copy_link'	=> '',
			'facebook'	=> '',
			'twitter'	=> '',
			'linkedin'	=> '',
		), $attr));

		if( empty($copy_link) && empty($facebook) && empty($twitter) && empty($linkedin) ) return;

		$output = '<div class="mfn-share-post">';

			if( !empty($copy_link) ) {
				$output .= '<a href="/" class="mfn-share-post-button mfn-share-post-copy-link">';

					if( !empty($attr['copy_link_icon']) ) {
						$output .= '<i class="'.$attr['copy_link_icon'].'"></i>';
					}else{
						$output .= '<i class="far fa-copy"></i>';
					}

					if( !empty($attr['copy_link_label']) ) $output .= '<span class="mfn-share-post-button-label">'.$attr['copy_link_label'].'</span>';

				$output .= '</a>';
			}

			if( !empty($facebook) ) {
				$output .= '<a target="_blank" href="https://facebook.com/sharer.php?u='.get_the_permalink().'" class="mfn-share-post-button mfn-share-post-facebook">';

					if( !empty($attr['facebook_icon']) ) {
						$output .= '<i class="'.$attr['facebook_icon'].'"></i>';
					}else{
						$output .= '<i class="icon-facebook"></i>';
					}

					if( !empty($attr['facebook_label']) ) $output .= '<span class="mfn-share-post-button-label">'.$attr['facebook_label'].'</span>';

				$output .= '</a>';
			}

			if( !empty($twitter) ) {
				$output .= '<a target="_blank" href="https://twitter.com/intent/tweet?text='.get_the_permalink().'" class="mfn-share-post-button mfn-share-post-twitter">';

					if( !empty($attr['twitter_icon']) ) {
						$output .= '<i class="'.$attr['twitter_icon'].'"></i>';
					}else{
						$output .= '<i class="icon-x-twitter"></i>';
					}

					if( !empty($attr['twitter_label']) ) $output .= '<span class="mfn-share-post-button-label">'.$attr['twitter_label'].'</span>';

				$output .= '</a>';
			}

			if( !empty($linkedin) ) {
				$output .= '<a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url='.get_the_permalink().'" class="mfn-share-post-button mfn-share-post-linkedin">';

					if( !empty($attr['linkedin_icon']) ) {
						$output .= '<i class="'.$attr['linkedin_icon'].'"></i>';
					}else{
						$output .= '<i class="icon-linkedin"></i>';
					}

					if( !empty($attr['linkedin_label']) ) $output .= '<span class="mfn-share-post-button-label">'.$attr['linkedin_label'].'</span>';

				$output .= '</a>';
			}


		$output .= '</div>';

		return $output;

	}
}

/**
 * Single post template: Post comments
 */

if (! function_exists('sc_post_comments')) {
	function sc_post_comments($attr){

		$output = '';

		$post_id = !empty( $attr['vb_postid'] ) ? $attr['vb_postid'] : get_the_ID();

		ob_start();
		comments_template();
		$output = ob_get_clean();


		return $output;

	}
}

/**
 * Single post template: Post content
 */

if (! function_exists('sc_post_content')) {
	function sc_post_content($attr){
		extract(shortcode_atts(array(
			'content'		=> '',
		), $attr));

		$output = '';

		$post_id = !empty( $attr['vb_postid'] ) ? $attr['vb_postid'] : get_the_ID();

		if( !empty($content) && $content == 'mfn' ) {
			$mfn_builder = new Mfn_Builder_Front( $post_id );
			ob_start();
				$mfn_builder->show(false, true);
			$output = ob_get_clean();
		}else{
			$output = apply_filters( 'the_content', get_the_content(null, false, $post_id) );
		}

		return $output;

	}
}


/**
 * Sidemenu Menu
 */

if (! function_exists('sc_sidemenu_menu')) {
	function sc_sidemenu_menu($attr) {
		extract(shortcode_atts(array(
			'icon_animation'		=> '',
			'submenu_icon'			=> '',
		), $attr));

		require_once( get_theme_file_path('/visual-builder/classes/header-template-items-class.php') );

		$output = '';

		$ul_classes = array('mfn-sidemenu-menu');

		// icon align

		$ul_classes[] = 'mfn-menu-icon-left';

		// icon animation
		if( !empty($icon_animation) ) $ul_classes[] = 'mfn-menu-icon-'.$icon_animation;

		// submenu display
		if( !empty($attr['submenu']) ) $ul_classes[] = 'mfn-menu-submenu-'.$attr['submenu'];

		// submenu animation
		if( !empty($attr['submenu_on']) ) $ul_classes[] = 'mfn-menu-submenu-'.$attr['submenu_on'];

		// submenu icon display
		if( empty($attr['submenu_icon_display']) || $attr['submenu_icon_display'] == 'on' ) {
			$ul_classes[] = 'mfn-menu-submenu-icon-on';
			if( !empty($attr['submenu_icon_animation']) ) $ul_classes[] = 'mfn-menu-submenu-icon-'.$attr['submenu_icon_animation'];
		}else{
			$ul_classes[] = 'mfn-menu-submenu-icon-off';
		}

		$arg = array(
			'container' => false,
			'menu_class' => implode(' ', $ul_classes),
			'walker' => new Mfn_Vb_Header_Tmpl,
			'outer_submenu_icon' => '<i class="'.$submenu_icon.'"></i>',
			'after' => !empty($attr['dropdown_pointer']) && $attr['dropdown_pointer'] == '1' ? '<span class="mfn-dropdown-pointer"></span>' : '',
			'mega_menu' => false,
			'mfn_classes' => true,
			'echo' => false
		);

		if( isset($attr['tabs']) && is_array($attr['tabs']) && count($attr['tabs']) > 0 ) {
			if( count($attr['tabs']) == 1 ){
				$arg['menu'] = $attr['tabs'][0]['menu'];
				$output .= '<div data-hash="mfn-menu-'.Mfn_Builder_Helper::unique_ID().'" class="mfn-menu-wrapper">';
				$output .= wp_nav_menu( $arg );
				$output .= '</div>';
			}else{
				$output .= '<div class="mfn-menu-tabs-wrapper">';
					$output .= '<ul class="mfn-menu-tabs-nav">';
						foreach( $attr['tabs'] as $m=>$mt ){
							$output .= '<li class="'.($m == 0 ? 'active' : '').'"><a href="#menu-tab-'.$m.'">'.$mt['title'].'</a></li>';
						}
					$output .= '</ul>';
					foreach( $attr['tabs'] as $m=>$mt ){
						$output .= '<div id="menu-tab-'.$m.'" data-hash="mfn-menu-'.Mfn_Builder_Helper::unique_ID().'" class="mfn-menu-tabs-content mfn-menu-wrapper '.($m == 0 ? 'active' : '').'">';
						$arg['menu'] = $mt['menu'];
						$output .= wp_nav_menu( $arg );
						$output .= '</div>';
					}
				$output .= '</div>';
			}
		}else{
			$output .= '<ul class="'.implode(' ', $ul_classes).'"><li class="menu-item mfn-menu-li"><a href="#" class="mfn-menu-link"><span class="menu-item-helper mfn-menu-item-helper"></span><span class="label-wrapper mfn-menu-label-wrapper"><span class="menu-label">Item 1</span></span></a></li><li class="menu-item mfn-menu-li"><a href="#" class="mfn-menu-link"><span class="menu-item-helper mfn-menu-item-helper"></span><span class="label-wrapper mfn-menu-label-wrapper"><span class="menu-label">Item 2</span></span></a></li><li class="menu-item mfn-menu-li"><a href="#" class="mfn-menu-link"><span class="menu-item-helper mfn-menu-item-helper"></span><span class="label-wrapper mfn-menu-label-wrapper"><span class="menu-label">Item 3</span></span></a></li></ul>';
		}

		return $output;
	}
}


/**
 * Popup Exit
 */

if (! function_exists('sc_popup_exit')) {
	function sc_popup_exit($attr){
		extract(shortcode_atts(array(
			'icon' 			=> '',
			'image' 		=> '',
			'label' 		=> '',
		), $attr));

		$classes = array();

		if( (!empty($icon) || !empty($image)) && !empty($label) ) $classes[] = 'has-icon';

		$output = '<a href="#" class="exit-mfn-popup '. esc_attr(implode(' ', $classes)) .'">';

		if( (!empty($icon) || !empty($image)) && !empty($label) ) $output .= '<span class="button_icon">';

		if( !empty($image) ){
			$output .= '<img src="'.$image.'" alt="">';
		}elseif( !empty($icon) ){
			$output .= '<i class="'.$icon.'"></i>';
		}

		if( (!empty($icon) || !empty($image)) && !empty($label) ) $output .= '</span>';

		if( !empty($label) ){
			if( !empty($icon) || !empty($image) ) $output .= '<span class="button_label">';
			$output .= $label;
			if( !empty($icon) || !empty($image) ) $output .= '</span>';
		}

		// std
		if( empty($icon) && empty($image) && empty($label) ){
			$output .= '<span class="button_label">'.__('Close popup', 'mfn-opts').'</span>';
		}

		$output .= '</a>';

		return $output;
	}
}

/**
 * Footer menu
 */

if (! function_exists('sc_footer_menu')) {
	function sc_footer_menu($attr){
		extract(shortcode_atts(array(
			'menu_style' 		=> '',
		), $attr));

		// require_once( get_theme_file_path('/visual-builder/classes/header-template-items-class.php') );

		$output = '';

		$ul_classes = array('mfn-footer-menu');

		if( !empty($menu_style) ) $ul_classes[] = 'mfn-footer-menu-style-'.$menu_style;

		$arg = array(
			'container' => false,
			'menu_id' => isset($attr['menu_display']) ? 'mfn-footer-menu-'.$attr['menu_display'] : 'mfn-footer-menu',
			'menu_class' => implode(' ', $ul_classes),
			//'walker' => new Mfn_Vb_Header_Tmpl,
			'submenu_icon' => !empty($submenu_on) && $submenu_on == 'toggled' && !empty($submenu_icon) ? '<i class="'.$submenu_icon.'"></i>' : '',
			/*'li_id_prefix' => 'footer-',
			'hide_helper' => true,*/
			'echo' => false
		);

		if(isset($attr['menu_display']) && $attr['menu_display'] > 0)
			$arg['menu'] = $attr['menu_display'];

		$output .= wp_nav_menu( $arg );

		return $output;
	}
}

/**
 * Menu Mega menu
 */

if (! function_exists('sc_megamenu_menu')) {
	function sc_megamenu_menu($attr){
		extract(shortcode_atts(array(
			'submenu' 			=> '',
			'menu_style' 		=> '',
			'submenu_on' 		=> '',
			'submenu_hori_on' 	=> '',
			'submenu_icon' 		=> '',
			'icon_animation'	=> '',
			'icon_align'		=> 'left',
		), $attr));

		require_once( get_theme_file_path('/visual-builder/classes/header-template-items-class.php') );

		$output = '';

		$ul_classes = array('mfn-megamenu-menu');

		if( !empty($menu_style) && $menu_style == 'horizontal' ) {
			$ul_classes[] = 'mfn-mm-menu-horizontal';

			// submenu horizontal
			if( !empty($submenu) && $submenu == 'on' ) {
				$ul_classes[] = 'mfn-mm-submenu-on-'.( !empty($submenu_hori_on) ? $submenu_hori_on : 'hover');
			}

			if( !empty($attr['submenu_animation']) ) $ul_classes[] = 'mfn-mm-submenu-show-'.$attr['submenu_animation'];

		}else{
			$ul_classes[] = 'mfn-mm-menu-vertical';

			// submenu vertical
			if( !empty($submenu) && $submenu == 'on' ) {
				$ul_classes[] = 'mfn-mm-submenu-'.( !empty($submenu_on) ? $submenu_on : 'visible');
			}
		}

		// icon align
		$ul_classes[] = 'mfn-mm-menu-icon-'.$icon_align;

		// icon animation
		if( !empty($icon_animation) ) $ul_classes[] = 'mfn-mm-menu-icon-'.$icon_animation;

		// submenu icon animation
		if( !empty($submenu) && $submenu == 'on' ) {
			if( !empty($attr['submenu_icon_animation']) ) $ul_classes[] = 'mfn-mm-submenu-icon-'.$attr['submenu_icon_animation'];
		}else{
			$ul_classes[] = 'mfn-mm-submenu-off';
		}

		$arg = array(
			'container' => false,
			'menu_id' => isset($attr['menu_display']) ? 'mfn-megamenu-ul-'.$attr['menu_display'] : 'mfn-megamenu-ul',
			'menu_class' => implode(' ', $ul_classes),
			'walker' => new Mfn_Vb_Header_Tmpl,
			'submenu_icon' => !empty($submenu_icon) ? '<i class="'.$submenu_icon.'"></i>' : '',
			'li_id_prefix' => 'megamenu-',
			'link_after' => !empty($attr['decoration_icon']) ? '<span class="decoration-icon"><i class="'.$attr['decoration_icon'].'"></i></span>' : '',
			'hide_helper' => true,
			//'mega_menu' => true,
			'echo' => false
		);

		if(isset($attr['menu_display']) && $attr['menu_display'] > 0)
			$arg['menu'] = $attr['menu_display'];

		$output .= wp_nav_menu( $arg );

		return $output;
	}
}

/**
 * Logo
 */

if (! function_exists('sc_header_logo')) {
	function sc_header_logo($attr){
		extract(shortcode_atts(array(
			'image' 	=> '',
			'link'		=> '',
		), $attr));

		$image_tag = false;

		if( $image && !empty($image) ){
			if( strpos($image, ':') !== false ){
				$image = be_dynamic_data($image);
			}elseif( strpos($image, '#') !== false ){
				$image_tag = mfn_get_attachment($image);
			}else{
				$image = mfn_vc_image($image);
			}
		}elseif( empty($image) && !empty(mfn_opts_get('logo-img')) ){
			$image = mfn_opts_get('logo-img');
		}else{
			$image = get_theme_file_uri( '/muffin-options/svg/placeholders/image.svg' );
		}

		$output = '<a class="logo-wrapper" href="'.( !empty($link) && $link != '/' ? $link : get_option("siteurl") ).'">';
		if( $image_tag ){
			$output .= $image_tag;
		}else{
			$output .= '<img src="'.$image.'" alt="'. esc_attr( mfn_get_attachment_data( $image, 'alt' ) ) .'" width="'. esc_attr( mfn_get_attachment_data( $image, 'width' ) ) .'" height="'. esc_attr( mfn_get_attachment_data( $image, 'height' ) ) .'">';
		}
		$output .= '</a>';

		return $output;
	}
}

/**
 * Footer Logo
 */

if (! function_exists('sc_footer_logo')) {
	function sc_footer_logo($attr){
		extract(shortcode_atts(array(
			'image' 	=> '',
			'link'		=> '',
		), $attr));

		if( $image && !empty($image) ){
			$image = mfn_vc_image($image);
		}elseif( empty($image) && mfn_opts_get('logo-img') ){
			$image = mfn_opts_get('logo-img');
		}else{
			$image = get_theme_file_uri( '/muffin-options/svg/placeholders/image.svg' );
		}

		$output = '<a class="logo-wrapper" href="'.( !empty($link) && $link != '/' ? $link : get_option("siteurl") ).'">';
			$output .= '<img src="'.$image.'" alt="'. esc_attr( mfn_get_attachment_data( $image, 'alt' ) ) .'" width="'. esc_attr( mfn_get_attachment_data( $image, 'width' ) ) .'" height="'. esc_attr( mfn_get_attachment_data( $image, 'height' ) ) .'">';
		$output .= '</a>';

		return $output;
	}
}

/**
 * Menu
 */

if (! function_exists('sc_header_menu')) {
	function sc_header_menu($attr){
		extract(shortcode_atts(array(
			'animation' 		=> '',
			'separator' 		=> 'off',
			'alignment' 		=> 'flex-start',
			'alignment_tablet' 	=> 'flex-start',
			'alignment_mobile' 	=> 'flex-start',
			'icon_animation'	=> '',
			'icon_align'		=> 'left',
			'submenu_icon'		=> 'fas fa-arrow-dow',
			'submenu_subicon'		=> 'fas fa-arrow-right',
			'submenu_fold_to_right'		=> '',
		), $attr));

		require_once( get_theme_file_path('/visual-builder/classes/header-template-items-class.php') );

		$output = '';

		$ul_classes = array('mfn-header-menu', 'mfn-header-mainmenu');

		// alignment
		$ul_classes[] = 'mfn-menu-align-'.$alignment;
		$ul_classes[] = 'mfn-menu-tablet-align-'.$alignment_tablet;
		$ul_classes[] = 'mfn-menu-mobile-align-'.$alignment_mobile;

		// icon align

		$ul_classes[] = 'mfn-menu-icon-'.$icon_align;

		// animation
		if( !empty($animation) ) $ul_classes[] = 'mfn-menu-animation-'.$animation;

		// separator
		if( !empty($separator) ) $ul_classes[] = 'mfn-menu-separator-'.$separator;

		// icon animation
		if( !empty($icon_animation) ) $ul_classes[] = 'mfn-menu-icon-'.$icon_animation;

		// submenu display
		if( !empty($attr['submenu_display']) ) $ul_classes[] = 'mfn-menu-submenu-on-'.$attr['submenu_display'];

		// submenu animation
		if( !empty($attr['submenu_animation']) ) $ul_classes[] = 'mfn-menu-submenu-show-'.$attr['submenu_animation'];

		// submenu icon display
		if( !empty($attr['submenu_icon_display']) ) {
			if( !empty($attr['submenu_icon_animation']) ) $ul_classes[] = 'mfn-menu-submenu-icon-'.$attr['submenu_icon_animation'];
		}else{
			$ul_classes[] = 'mfn-menu-submenu-icon-off';
		}

		if( !empty($attr['dropdown_pointer']) && $attr['dropdown_pointer'] == '1' ){
			$ul_classes[] = 'mfn-menu-dropdown-pointer';
			if( !empty($attr['dropdown_alignment']) ){ $ul_classes[] = 'mfn-menu-dropdown-pointer-'.$attr['dropdown_alignment']; }else{ $ul_classes[] = 'mfn-menu-dropdown-pointer-left'; }
		}

		if( ! empty($submenu_fold_to_right) ){
			$ul_classes[] = 'mfn-menu-fold-last-to-right';
		}

		$arg = array(
			'container' => false,
			'menu_class' => implode(' ', $ul_classes),
			'walker' => new Mfn_Vb_Header_Tmpl,
			'custom_icon' => '<i class="'.$submenu_icon.'"></i>',
			'custom_subicon' => '<i class="'.$submenu_subicon.'"></i>',
			'after' => !empty($attr['dropdown_pointer']) && $attr['dropdown_pointer'] == '1' ? '<span class="mfn-dropdown-pointer"></span>' : '',
			'mega_menu' => true,
			'mfn_classes' => true,
			'echo' => false
		);

		if ( !empty($_GET['visual']) || ( isset($attr['vb']) && $attr['vb'] ) || ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ){
			unset($arg['mega_menu']);
			$arg['mega_menu_simulate'] = true;
		}



		if(isset($attr['menu_display']) && $attr['menu_display'] > 0){
			$arg['menu'] = $attr['menu_display'];
			$output .= wp_nav_menu( $arg );
		}else{
			$output .= '<ul class="'.implode(' ', $ul_classes).'"><li class="menu-item mfn-menu-li"><a href="#" class="mfn-menu-link"><span class="menu-item-helper mfn-menu-item-helper"></span><span class="label-wrapper mfn-menu-label-wrapper"><span class="menu-label">Item 1</span></span></a></li><li class="menu-item mfn-menu-li"><a href="#" class="mfn-menu-link"><span class="menu-item-helper mfn-menu-item-helper"></span><span class="label-wrapper mfn-menu-label-wrapper"><span class="menu-label">Item 2</span></span></a></li><li class="menu-item mfn-menu-li"><a href="#" class="mfn-menu-link"><span class="menu-item-helper mfn-menu-item-helper"></span><span class="label-wrapper mfn-menu-label-wrapper"><span class="menu-label">Item 3</span></span></a></li></ul>';
		}

		return $output;
	}
}

/**
 * Icon
 */

if (! function_exists('sc_header_icon')) {
	function sc_header_icon($attr){
		extract(shortcode_atts(array(
			'type' 			=> '',
			'image' 		=> '',
			'icon' 			=> '',
			'desc'			=> '',
			'link'			=> '',
			'link_title'	=> '',
			'icon_position' => 'top',
 			'icon_position_tablet' => '',
 			'icon_position_laptop' => '',
 			'icon_position_mobile' => '',

 			'icon_align' => 'center',
 			'icon_align_tablet' => '',
 			'icon_align_laptop' => '',
 			'icon_align_mobile' => '',
 			'hover' => '',
		), $attr));

		$output = '';
		$classes = array('mfn-icon-box', 'mfn-header-icon-box');
		$classes_desc = array();
		$attr_link = '#';
		$icon_html = '';
		$icon_addons = '';
		$additional_html = '';

		if( !empty($attr['header_icon_desc_visibility']) ){
			$classes_desc[] = $attr['header_icon_desc_visibility'];
		}
 		if( $icon_position ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $icon_position );
 		}
 		if( $icon_position_tablet ){
 			$classes[] = 'mfn-icon-box-tablet-'. esc_attr( $icon_position_tablet );
 		}
 		if( $icon_position_laptop ){
 			$classes[] = 'mfn-icon-box-laptop-'. esc_attr( $icon_position_laptop );
 		}
 		if( $icon_position_mobile ){
 			$classes[] = 'mfn-icon-box-mobile-'. esc_attr( $icon_position_mobile );
 		}
 		if( $icon_align ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $icon_align );
 		}
 		if( $icon_align_tablet ){
 			$classes[] = 'mfn-icon-box-tablet-'. esc_attr( $icon_align_tablet );
 		}
 		if( $icon_align_laptop ){
 			$classes[] = 'mfn-icon-box-laptop-'. esc_attr( $icon_align_laptop );
 		}
 		if( $icon_align_mobile ){
 			$classes[] = 'mfn-icon-box-mobile-'. esc_attr( $icon_align_mobile );
 		}
 		if( $hover ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $hover );
 		}

		// link

		if(in_array($type, array('default', 'mail', 'tel'))){
			if($type == 'tel' && !empty($link)) $attr_link = 'tel:'.$link;
			if($type == 'mail' && !empty($link)) $attr_link = 'mailto:'.$link;
			if($type == 'default') $attr_link = $link;
			$classes[] = 'mfn-header-link';

			if( empty($image) && empty($icon) ){
				if( $type == 'tel' ){
					$icon = 'icon-phone';
				}elseif( $type == 'mail' ){
					$icon = 'icon-email';
				}elseif( $type == 'default' ){
					$icon = 'icon-lamp';
				}
			}

		}else if( $type == 'account' ){
			//$desc = '';
			if(function_exists('is_woocommerce')){
				$additional_html .= '<div aria-disabled="true" class="mfn-header-login woocommerce '. ( is_user_logged_in() ? "mfn-header-modal-nav" : "mfn-header-modal-login" ) .'">';
				$additional_html .= '<span class="mfn-close-icon toggle-login-modal close-login-modal"><span class="icon">&#10005;</span></span>';
				if( ! is_user_logged_in()){
					$additional_html .= '<h4>'.esc_html( 'Login', 'woocommerce' ).'</h4>';
					ob_start();
					woocommerce_login_form();
					$additional_html .= ob_get_clean();
				} else {
					ob_start();
					echo '<h4>';printf( __( 'Hello %s,', 'woocommerce' ), esc_html( wp_get_current_user()->user_login ) );echo '</h4>';
					woocommerce_account_navigation();
					$additional_html .= ob_get_clean();
				}
				$additional_html .= '</div>';
				$attr_link = wc_get_page_permalink( 'myaccount' );
			}

			$classes[] = 'mfn-header-account-link toggle-login-modal is-boxed';
			if( empty($image) && empty($icon) ) $icon_html = '<svg viewBox="0 0 26 26" aria-hidden="true"><defs><style>.path{fill:none;stroke:#333333;stroke-width:1.5px;}</style></defs><circle class="path" cx="13" cy="9.7" r="4.1"/><path class="path" d="M19.51,18.1v2.31h-13V18.1c0-2.37,2.92-4.3,6.51-4.3S19.51,15.73,19.51,18.1Z"/></svg>';
		}else if( $type == 'cart' ){

			if( function_exists('is_woocommerce') ){
				if($type == 'cart' && ( (isset($attr['cart_total']) && $attr['cart_total'] == 1) || (isset($attr['count']) && $attr['count'] == 1 ) ) ){
					global $woocommerce;
					if(!empty($attr['count']) && $attr['count'] == 1) $icon_addons .= '<span class="header-cart-count mfn-header-icon-0">0</span>';
					if(!empty($attr['cart_total']) && $attr['cart_total'] == 1) $desc = '<p class="header-cart-total">'. wp_strip_all_tags( $woocommerce->cart->get_cart_total() ) .'</p>';
				}

				$attr_link = wc_get_page_permalink( 'cart' );
			}
			$classes[] = 'mfn-header-cart-link toggle-mfn-cart';
			if( empty($image) && empty($icon) ) $icon_html = '<svg viewBox="0 0 26 26" aria-hidden="true"><defs><style>.path{fill:none;stroke:#333;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><polygon class="path" points="20.4 20.4 5.6 20.4 6.83 10.53 19.17 10.53 20.4 20.4"/><path class="path" d="M9.3,10.53V9.3a3.7,3.7,0,1,1,7.4,0v1.23"/></svg>';
		}else if( $type == 'wishlist' ){
			//$desc = '';

			if( function_exists('is_woocommerce') ){

				if(!empty($attr['count']) && $attr['count'] == 1 ){
					$icon_addons = '<span class="header-wishlist-count mfn-header-icon-0">0</span>';
				}

				$attr_link = get_permalink(mfn_opts_get('shop-wishlist-page'));
				$classes[] = 'mfn-header-wishlist-link';
			}
			if( empty($image) && empty($icon) ) $icon_html = '<svg viewBox="0 0 26 26" aria-hidden="true"><defs><style>.path{fill:none;stroke:#333;stroke-width:1.5px;}</style></defs><path class="path" d="M16.7,6a3.78,3.78,0,0,0-2.3.8A5.26,5.26,0,0,0,13,8.5a5,5,0,0,0-1.4-1.6A3.52,3.52,0,0,0,9.3,6a4.33,4.33,0,0,0-4.2,4.6c0,2.8,2.3,4.7,5.7,7.7.6.5,1.2,1.1,1.9,1.7H13a.37.37,0,0,0,.3-.1c.7-.6,1.3-1.2,1.9-1.7,3.4-2.9,5.7-4.8,5.7-7.7A4.3,4.3,0,0,0,16.7,6Z"/></svg>';
		}else if( $type == 'search' ){
			//$desc = '';
			$classes[] = 'mfn-header-search-link mfn-search-button mfn-searchbar-toggle';

			$output .= '<div class="search_wrapper">';
				ob_start();
				get_search_form(true);
				if ( mfn_opts_get('header-search-live') ) get_template_part('includes/header', 'live-search');
				$output .= ob_get_clean();
			$output .= '</div>';

			if( empty($image) && empty($icon) ) $icon_html = '<svg width="26" viewBox="0 0 26 26"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><circle class="path" cx="11.35" cy="11.35" r="6"></circle><line class="path" x1="15.59" y1="15.59" x2="20.65" y2="20.65"></line></svg>';
		}

		if( empty($desc) ) $classes[] = 'mfn-icon-box-empty-desc';

		if( !empty($attr['count_if_zero']) ) $classes[] = 'mfn-hide-count-if-0';

		$classes = implode(' ', $classes);
		$classes_desc = implode(' ', $classes_desc);
		$target = false;
		if( !empty($attr['target']) && $attr['target'] == '1' ){ $target = 'target="_blank"'; }

		if( $attr_link ){
			$output .= '<a '. $target .' href="'. esc_url($attr_link) .'" class="'. esc_attr($classes) .'" title="'. esc_attr($link_title ?? '') .'">';
		} else {
			$output .= '<span class="'. esc_attr($classes) .'">';
		}

		if( !empty($image) || !empty($icon) || !empty($icon_html) ){
			$output .= '<div class="icon-wrapper">';

				if( !empty($image) ){
					$image = be_dynamic_data($image);
					if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');
 					$output .= '<img class="scale-with-grid" src="'. esc_url( $image ) .'" alt="'. esc_attr( mfn_get_attachment_data( $image, 'alt' ) ) .'" width="'. esc_attr( mfn_get_attachment_data( $image, 'width' ) ) .'" height="'. esc_attr( mfn_get_attachment_data( $image, 'height' ) ) .'">';
 				}elseif( !empty($icon) ){
					$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
				}elseif( !empty($icon_html) ){
					$output .= $icon_html;
				}

				if( !empty($icon_addons) ){
					$output .= $icon_addons;
				}
			$output .= '</div>';
		}

		if( !empty($desc) ){
			$output .= '<div class="desc-wrapper '.$classes_desc.'">';
				$output .= be_dynamic_data($desc);
				// additional to wishlist
			$output .= '</div>';
		}

		if( $attr_link ){
			$output .= '</a>';
		} else {
			$output .= '</span>';
		}

		if( !empty($additional_html) ){
			$output .= $additional_html;
		}

		return $output;

	}
}

/**
 * Menu burger
 */

if (! function_exists('sc_header_burger')) {
	function sc_header_burger($attr){
		extract(shortcode_atts(array(
			'image' 	=> '',
			'icon' 		=> '',
			'desc'		=> '',
			'link'		=> '',
			'icon_position' => 'top',
 			'icon_position_tablet' => '',
 			'icon_position_laptop' => '',
 			'icon_position_mobile' => '',

 			'icon_align' => 'center',
 			'icon_align_tablet' => '',
 			'icon_align_laptop' => '',
 			'icon_align_mobile' => '',
 			'hover' => '',
 			'menu_pos' => '',
 			'link_title' => '',

 			'animation' 		=> '',
			'separator' 		=> 'off',
			'alignment' 		=> 'flex-start',
			'alignment_tablet' 	=> 'flex-start',
			'alignment_laptop' 	=> 'flex-start',
			'alignment_mobile' 	=> 'flex-start',
			'icon_animation'	=> '',
			'menu_icon_align'	=> 'left',
			'submenu_icon'		=> 'fas fa-arrow-dow',
			'submenu_subicon'	=> 'fas fa-arrow-right',

		), $attr));

		$output = '';
		$classes = array('mfn-icon-box', 'mfn-header-menu-burger');
		$classes_desc = array();
		$close_icon_pos = 'mfn-close-icon-pos-default';
		$attr_link = '#';
		$nav_name = false;
		$sidemenu_attr = false;

		if( empty($menu_pos) ){
			$menu_pos = 'right';
		}

		if( !empty($attr['sidebar-menu-close-icon-position']) ){
			$close_icon_pos = 'mfn-close-icon-pos-'.$attr['sidebar-menu-close-icon-position'];
		}

		if(isset($attr['menu_display']) && $attr['menu_display'] > 0){
			$nav_id = get_term_by('id', $attr['menu_display'], 'nav_menu');
			if( !empty($nav_id) ) $nav_name = 'data-nav="menu-'.$nav_id->slug.'"';
		}

		if( !empty($attr['header_icon_desc_visibility']) ){
			$classes_desc[] = $attr['header_icon_desc_visibility'];
		}

 		if( $icon_position ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $icon_position );
 		}

 		if( $icon_position_tablet ){
 			$classes[] = 'mfn-icon-box-tablet-'. esc_attr( $icon_position_tablet );
 		}

 		if( $icon_position_laptop ){
 			$classes[] = 'mfn-icon-box-laptop-'. esc_attr( $icon_position_laptop );
 		}

 		if( $icon_position_mobile ){
 			$classes[] = 'mfn-icon-box-mobile-'. esc_attr( $icon_position_mobile );
 		}

 		if( $icon_align ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $icon_align );
 		}

 		if( $icon_align_tablet ){
 			$classes[] = 'mfn-icon-box-tablet-'. esc_attr( $icon_align_tablet );
 		}

 		if( $icon_align_laptop ){
 			$classes[] = 'mfn-icon-box-laptop-'. esc_attr( $icon_align_laptop );
 		}

 		if( $icon_align_mobile ){
 			$classes[] = 'mfn-icon-box-mobile-'. esc_attr( $icon_align_mobile );
 		}

 		if( $hover ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $hover );
 		}

 		if( !empty($attr['sidebar_type']) && is_numeric($attr['sidebar_type']) && $attr['sidebar_type'] != 'default' ) {
 			$classes[] = 'mfn-header-sidemenu-toggle';
 			$sidemenu_attr = 'data-sidemenu="'.$attr['sidebar_type'].'"';
 		}else{
 			$classes[] = 'mfn-header-menu-toggle';
 			if( !empty($attr['sidebar_type']) && $attr['sidebar_type'] == 'classic' ) {
 				$classes[] = 'mfn-header-classic-mobile-menu-toggle';
 				$menu_pos = 'center';
 				$close_icon_pos = 'mfn-header-classic-mobile-menu-close-icon-hidden';
 			}
 		}

		// link

		if( empty($image) && empty($icon) ) $icon = 'icon-menu-fine';

		if( empty($desc) ) $classes[] = 'mfn-icon-box-empty-desc';

		$classes = implode(' ', $classes);
		$classes_desc = implode(' ', $classes_desc);

		$output .= '<a '.$nav_name.' href="'.$attr_link.'" class="'. $classes .'" '.$sidemenu_attr.' title="'. esc_attr($link_title ?? '') .'">';

		if( !empty($image) || !empty($icon) ){
			$output .= '<div class="icon-wrapper">';

				if( !empty($image) ){
					$output .= '<img class="scale-with-grid" src="'. esc_url( $image ) .'" alt="'. esc_attr( mfn_get_attachment_data( $image, 'alt' ) ) .'" width="'. esc_attr( mfn_get_attachment_data( $image, 'width' ) ) .'" height="'. esc_attr( mfn_get_attachment_data( $image, 'height' ) ) .'">';
 				}elseif( !empty($icon) ){
					$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
				}

			$output .= '</div>';
		}

		if( !empty($desc) ){
			$output .= '<div class="desc-wrapper '.$classes_desc.'">';
				$output .= $desc;
			$output .= '</div>';
		}

		$output .= '</a>';

		// skip menu if sidebar template is selected
		if( ! empty($attr['sidebar_type']) && is_numeric($attr['sidebar_type']) ){
			return $output;
		}

		// tmpl menu
		$output .= '<div class="mfn-header-tmpl-menu-sidebar mfn-header-tmpl-menu-sidebar-'.$menu_pos.' '.$close_icon_pos.' '.(!empty($attr['sidebar_type']) && $attr['sidebar_type'] == 'classic' ? 'mfn-header-classic-mobile-menu' : '').'"><div class="mfn-header-tmpl-menu-sidebar-wrapper">';
		$output .= '<span class="mfn-close-icon mfn-header-menu-toggle"><span class="icon">&#10005;</span></span>';

			require_once( get_theme_file_path('/visual-builder/classes/header-template-items-class.php') );

			$ul_classes = array('mfn-header-menu');

			// alignment
			$ul_classes[] = 'mfn-menu-align-'.$alignment;
			$ul_classes[] = 'mfn-menu-tablet-align-'.$alignment_tablet;
			$ul_classes[] = 'mfn-menu-laptop-align-'.$alignment_laptop;
			$ul_classes[] = 'mfn-menu-mobile-align-'.$alignment_mobile;

			if( !empty($attr['items_align']) ){
	 			$ul_classes[] = 'mfn-items-align-'. esc_attr( $attr['items_align'] );
	 		}

	 		if( !empty($attr['items_align_tablet']) ){
	 			$ul_classes[] = 'mfn-items-align-'. esc_attr( $attr['items_align_tablet'] );
	 		}

	 		if( !empty($attr['items_align_laptop']) ){
	 			$ul_classes[] = 'mfn-items-align-'. esc_attr( $attr['items_align_laptop'] );
	 		}

	 		if( !empty($attr['items_align_mobile']) ){
	 			$ul_classes[] = 'mfn-items-align-'. esc_attr( $attr['items_align_mobile'] );
	 		}

			// icon align

			$ul_classes[] = 'mfn-menu-icon-'.$menu_icon_align;

			// animation
			if( !empty($animation) ) $ul_classes[] = 'mfn-menu-animation-'.$animation;

			// separator
			if( !empty($separator) ) $ul_classes[] = 'mfn-menu-separator-'.$separator;

			// icon animation
			if( !empty($icon_animation) ) $ul_classes[] = 'mfn-menu-icon-'.$icon_animation;

			// submenu display on click
			$ul_classes[] = 'mfn-menu-submenu-on-click';

			// submenu animation
			if( !empty($attr['submenu_animation']) ) $ul_classes[] = 'mfn-menu-submenu-show-'.$attr['submenu_animation'];

			// submenu icon display
			if( !empty($attr['submenu_icon_display']) ) {
				if( !empty($attr['submenu_icon_animation']) ) $ul_classes[] = 'mfn-menu-submenu-icon-'.$attr['submenu_icon_animation'];
			}

			$arg = array(
				'container' => false,
				'menu_class' => implode(' ', $ul_classes),
				'walker' => new Mfn_Vb_Header_Tmpl,
				'custom_icon' => '<i class="'.$submenu_icon.'"></i>',
				'custom_subicon' => '<i class="'.$submenu_subicon.'"></i>',
				'mfn_classes' => true,
				'echo' => false
			);

			if(isset($attr['menu_display']) && $attr['menu_display'] > 0)
				$arg['menu'] = $attr['menu_display'];

			$output .= wp_nav_menu( $arg );
		$output .= '</div></div>';



		return $output;

	}
}

/**
 * Header Search
 */

if(! function_exists('sc_header_search')) {
	function sc_header_search($attr){
		extract(shortcode_atts(array(
			'placeholder' 	=> '',
			'icon' 			=> ''
		), $attr));

		$args = array();

		if( !empty($placeholder) ) $args['placeholder'] = $placeholder;
		if( !empty($icon) ) $args['icon'] = $icon;

		$output = '';

		$output .= '<div class="search_wrapper">';
			ob_start();
			get_search_form( $args );
			if ( mfn_opts_get('header-search-live') ) get_template_part('includes/header', 'live-search');
			$output .= ob_get_clean();
		$output .= '</div>';

		return $output;
	}
}

/**
 * Top Bar
 */

if (! function_exists('sc_header_promo_bar')) {
	function sc_header_promo_bar($attr){
		extract(shortcode_atts(array(
			'tabs' 		=> '',
			'slider_speed' => 3
		), $attr));

		// content builder

		if ($tabs) {
			$mfn_tabs_array = $tabs;
		}

		$output = '<div class="promo_bar_slider" data-speed="'.$slider_speed.'">';
		if ( is_array( $mfn_tabs_array ) ) {
			foreach ( $mfn_tabs_array as $t=>$tab ) {
				$output .= $t == 0 ? '<div class="pbs_one pbs-active">' : '<div class="pbs_one">';
				$output .= do_shortcode($tab['title'] ?? '');
				$output .= '</div>';
			}
			$mfn_tabs_array = false;
		}

		$output .= '</div>';

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-slick', get_theme_file_uri('/js/plugins/slick.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}


/**
 * WPML currency Switcher
 */

if (! function_exists('sc_header_currency_switcher')) {
	function sc_header_currency_switcher($attr) {
		extract(shortcode_atts(array(
			'flags' 		=> '',
			'dropdown_icon' => '',
			'style' 		=> '',
		), $attr));

		if ( !defined( 'WCML_VERSION' ) ) {
			$output = sc_alert(array('style' => 'warning'), 'WCML plugin is required!');
			return $output;
		}

		$class = array('mfn-currency-switcher-wrapper');
		$data_tags = 'data-icon="icon" data-path="icon-down-open"';

		if( empty($style) ) {

			$class[] = 'mfn-currency-switcher-dropdown';
			if( !empty($dropdown_icon) && $dropdown_icon == '1' ) $class[] = 'mfn-currency-switcher-dropdown-icon';

			if( !empty($attr['dropdown_icon_html']) ) {
				$data_tags = 'data-icon="icon" data-path="'.$attr['dropdown_icon_html'].'"';
			}

		}else{
			$class[] = 'mfn-currency-switcher-default';
		}

		if( !empty($flags) ) {
			$class[] = 'mfn-currency-switcher-flags';
			$data_tags .= ' data-themepath="'.get_theme_file_uri('images/flags').'"';
		}

		$output = '<div class="'.implode(' ', $class).'" '.$data_tags.'>';
		ob_start();
		//do_action('wcml_currency_switcher', array('format' => '<img src="'.get_theme_file_uri('images/flags').'/%code%.svg" alt="">%code%'));
		echo do_shortcode( '[currency_switcher]' );
		$output .= ob_get_clean();
		$output .= '</div>';

		return $output;
	}
}


/**
 * WPML Language Switcher
 */

if (! function_exists('sc_header_language_switcher')) {
	function sc_header_language_switcher($attr){
		extract(shortcode_atts(array(
			'flags' 		=> '',
			'dropdown_icon' => '',
			'style' 		=> '',
		), $attr));

		if ( !defined( 'ICL_SITEPRESS_VERSION' ) )  {
			$output = sc_alert(array('style' => 'warning'), 'WPML plugin is required!');
			return $output;
		}

		$class = array('mfn-language-switcher');
		$data_tags = 'data-icon="icon" data-path="icon-down-open"';

		if( !empty($style) ) {
			$class[] = 'mfn-language-switcher-'.$style;
			if( !empty($dropdown_icon) && $dropdown_icon == '1' ) $class[] = 'mfn-language-switcher-dropdown-icon';

			if( !empty($attr['dropdown_icon_image']) ){
				$data_tags = 'data-icon="image" data-path="'.$attr['dropdown_icon_image'].'"';
			}else if( !empty($attr['dropdown_icon_html']) ){
				$data_tags = 'data-icon="icon" data-path="'.$attr['dropdown_icon_html'].'"';
			}
		}

		$args = array(
			'type' => 'custom',
			'flags' => $flags
		);

		$output = '<div class="'.implode(' ', $class).'" '.$data_tags.'>';
		ob_start();
		echo do_action( 'wpml_language_switcher', $args );
		$output .= ob_get_clean();

		$output .= '</div>';
		return $output;
	}
}

/**
 * Product Upsells
 */

if (! function_exists('sc_product_upsells')) {
	function sc_product_upsells($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		remove_standard_woo_actions_archive();
		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( !$product ) return;

		$output = '';

		$args = array(
			'posts_per_page' => $attr['products'],
			'columns'        => $attr['columns'],
			'order'          => 'desc',
			'orderby'        => 'rand',
		);

		if(empty($attr['button'])) {
			$attr['button'] = 0;
		}

		if(empty($attr['description'])) {
			$attr['description'] = 0;
		}

		$classes = array( 'grid', 'col-'.$attr['columns']);

		// background

		if( ! empty( mfn_opts_get('background-archives-product') ) ) {
			$classes[] = 'has-background-color';
		}

		$ul_classes = 'columns-'.$attr['columns'];

		$orderby = apply_filters( 'woocommerce_upsells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : $orderby );
		$order   = apply_filters( 'woocommerce_upsells_order', isset( $args['order'] ) ? $args['order'] : $order );
		$limit   = apply_filters( 'woocommerce_upsells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit );

		$items = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
		$items = $attr['products'] > 0 ? array_slice( $items, 0, $attr['products'] ) : $items;


		if(isset($items) && count($items) > 0){

		$output = '<section class="mfn-upsells">';
		$title_tag = !empty($attr['heading_tag']) ? $attr['heading_tag'] : 'h3';

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title heading '. esc_attr($title_class) .'">'.__( 'You may also like&hellip;', 'woocommerce' ).'</'. mfn_allowed_title_tag($title_tag) .'>';
		$output  .= '<ul class="products '.$ul_classes.'">';

			foreach ( $items as $product ) :
				setup_postdata($product->get_id());
				$output .= Mfn_Builder_Woo_Helper::productslist($product, $attr, $classes);
				wp_reset_postdata();
			endforeach;

		$output .= '</ul>';
		$output .= '</section>';

		}

		return $output;
	}
}

/**
 * Product Related
 */

if (! function_exists('sc_product_related')) {
	function sc_product_related($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		remove_standard_woo_actions_archive();
		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( !$product ) return;

		$output = '';

		$args = array(
			'posts_per_page' => $attr['products'],
			'columns'        => $attr['columns'],
			'order'          => 'desc',
			'orderby'        => 'rand',
		);

		if(empty($attr['button'])) {
			$attr['button'] = 0;
		}

		if(empty($attr['description'])) {
			$attr['description'] = 0;
		}

		$classes = array( 'grid', 'col-'.$attr['columns'] );

		// background

		if( ! empty( mfn_opts_get('background-archives-product') ) ) {
			$classes[] = 'has-background-color';
		}

		$ul_classes = 'columns-'.$attr['columns'];

		$related = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
		$related = wc_products_array_orderby( $related, $args['orderby'], $args['order'] );

		if(isset($related) && count($related) > 0) {

		$output = '<section class="mfn-related">';

		$title_tag = !empty($attr['heading_tag']) ? $attr['heading_tag'] : 'h3';

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}


		$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title heading '. esc_attr($title_class) .'">'.__( 'Related products', 'woocommerce' ).'</'. mfn_allowed_title_tag($title_tag) .'>';
		$output  .= '<ul class="products '.$ul_classes.'">';

			foreach ( $related as $product ) :
				setup_postdata($product->get_id());
				$output .= Mfn_Builder_Woo_Helper::productslist($product, $attr, $classes);
				wp_reset_postdata();
			endforeach;

		$output .= '</ul>';

		$output .= '</section>';

		}

		return $output;
	}
}

/**
 * Product Meta
 */

if (! function_exists('sc_product_meta')) {
	function sc_product_meta($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( !$product ) return;

		setup_postdata($product->get_id());

		//setup_postdata($product->get_id());
		ob_start();
		do_action( 'woocommerce_product_meta_start' );

		$sku = ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' );

		if(isset($attr['layout']) && $attr['layout'] == 'table'){
			do_action( 'woocommerce_product_meta_start' );
			echo '<div class="product_meta mfn_product_meta"><table class="table-meta">';
			echo '<tbody>';
			// tags
			if(count( $product->get_tag_ids() ) > 0):
				echo '<tr><th>'._n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ).'</th><td>'.wc_get_product_tag_list($product->get_id()).'</td></tr>';
			endif;
			// categories
			echo '<tr><th>'._n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ).'</th><td>'.wc_get_product_category_list($product->get_id()).'</td></tr>';
			// sku
			if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
				echo '<tr><th>'.__( 'SKU:', 'woocommerce' ).'</th><td>'.$sku.'</td></tr>';
			endif;
			echo '</tbody>';
			echo '</table></div>';
			do_action( 'woocommerce_product_meta_end' );
		}elseif(isset($attr['layout']) && $attr['layout'] == 'stacked'){
			do_action( 'woocommerce_product_meta_start' );
			echo '<div class="product_meta mfn_product_meta"><ul class="stacked-meta">';
			// tags
			if(count( $product->get_tag_ids() ) > 0):
				echo '<li class="stacked-tags"><span class="stacked-meta-title">'._n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ).'</span><span class="stacked-meta-value">'.wc_get_product_tag_list($product->get_id(), '').'</span></li>';
			endif;

			// categories
			echo '<li class="stacked-categories"><span class="stacked-meta-title">'._n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ).'</span><span class="stacked-meta-value">'.wc_get_product_category_list($product->get_id()).'</span></li>';

			// sku
			if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
				echo '<li class="stacked-sku"><span class="stacked-meta-title">'.__( 'SKU:', 'woocommerce' ).'</span><span class="stacked-meta-value">'.$sku.'</span></li>';
			endif;

			echo '</ul></div>';
			do_action( 'woocommerce_product_meta_end' );
		}else{
			do_action( 'woocommerce_product_meta_start' );
			echo '<div class="product_meta mfn_product_meta">';
			// tags
			if(count( $product->get_tag_ids() ) > 0):
				echo '<span class="tagged_as">'._n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ).' '.wc_get_product_tag_list($product->get_id(), '').'</span>';
			endif;

			// categories
			echo '<span class="posted_in">'._n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ).' '.wc_get_product_category_list($product->get_id()).'</span>';

			// sku
			if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
				echo '<span class="sku_wrapper">'.__( 'SKU:', 'woocommerce' ).' <span class="sku">'.$sku.'</span></span>';
			endif;
			echo '</div>';
			//wc_get_template( 'single-product/meta.php' );
			do_action( 'woocommerce_product_meta_end' );
		}

    	$output = ob_get_clean();
    	wp_reset_postdata();
    	return $output;
	}
}

/**
 * Product Additional Information
 */

if (! function_exists('sc_product_additional_information')) {
	function sc_product_additional_information($attr, $product = false)
	{
		if( ! function_exists('is_woocommerce') ){
			return;
		}

		if( ! $product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( ! $product ){
			return;
		}

		// title tag

		$title_class = '';
		if( ! empty($attr['title_tag']) ){
			if( 'p.lead' == $attr['title_tag'] ){
				$attr['title_tag'] = 'p';
				$title_class = 'lead';
			}
		}

		setup_postdata( $product->get_id() );

		add_action( 'woocommerce_product_additional_information', 'wc_display_product_attributes', 10 );

		ob_start();

		if( ! empty( $attr['title'] ) ){
			$title_tag = !empty($attr['title_tag']) ? $attr['title_tag'] : 'h3';
			echo '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. __( 'Additional information', 'woocommerce' ) .'</'. mfn_allowed_title_tag($title_tag) .'>';
		}
		woocommerce_product_additional_information_tab();

  	$output = ob_get_clean();

  	wp_reset_postdata();
  	return $output;
	}
}

/**
 * Product Reviews
 */

if (! function_exists('sc_product_reviews')) {
	function sc_product_reviews($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		$output = '';
		if( !$product ){
			$output = '<div class="mfn-widget-placeholder mfn-wp-product-reviews"><img class="item-preview-image" src="'.get_theme_file_uri('/muffin-options/svg/placeholders/product_rating.svg').'" alt="product rating"></div>';
		}else{

			if( !$product ) return;

			setup_postdata($product->get_id());

			if ( comments_open( $product->get_id() ) ) {
				ob_start();
				comments_template( 'single-product-reviews' );
		    	$output = ob_get_clean();
	    	}

	    	wp_reset_postdata();
		}
    	return $output;
	}
}

/**
 * Product Breadcrumbs
 */

if (! function_exists('sc_product_breadcrumbs')) {
	function sc_product_breadcrumbs($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		$output = '';

		$sample = false;

		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( !$product ) return;

		$args = array(
			'delimiter' => $attr['breadcrumb_delimiter'] ? '<span>'.$attr['breadcrumb_delimiter'].'</span>' : '<span>/</span>',
			'wrap_before' => '<nav class="woocommerce-breadcrumb mfn-woocommerce-breadcrumb">',
			'wrap_after'  => '</nav>',
		);

		if(isset( $attr['breadcrumb_home'] ) && $attr['breadcrumb_home'] == '0'){
			$args['home'] = false;
		}

		if( $sample ){
			// for builder only
			$output = '<nav class="woocommerce-breadcrumb mfn-woocommerce-breadcrumb">';
			if( $attr['breadcrumb_home'] ) $output .= '<a href="#" class="mfn-woo-breadcrumb-home">Home<span class="mfn-woo-breadcrump-delimiter">' . $args['delimiter'] . '</span></a> ';
			$output .= '<a href="#">'.__('Product Category', 'woocommerce').'</a><span class="mfn-woo-breadcrump-delimiter">' . $args['delimiter'] .'</span>'. get_the_title($product->get_id()) .'</nav>';
		}else{
			ob_start();
				woocommerce_breadcrumb($args);
			$output = ob_get_clean();
		}


    	return $output;
	}
}

/**
 * Product Rating
 */

if (! function_exists('sc_product_rating')) {
	function sc_product_rating($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( !$product ) return;

		setup_postdata($product->get_id());

		if ( ! wc_review_ratings_enabled() ) {
			return;
		}

		ob_start();

		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();

		if ( $rating_count > 0 ) : ?>

			<div class="woocommerce-product-rating">
				<?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
				<?php if ( comments_open($product->get_id()) ) : ?>
					<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'woocommerce' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)</a>
				<?php endif ?>
			</div>

		<?php endif;

		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

/**
 * Product Images
 */

if (! function_exists('sc_product_images')) {
	function sc_product_images($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		$is_sample = false;

		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
			$is_sample = true;
		}

		if( !$product ) return;

		setup_postdata($product->get_id());

		ob_start();
		if( $is_sample ) add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
		woocommerce_show_product_images();
    	$output = ob_get_clean();
    	wp_reset_postdata();
    	return $output;
	}
}

/**
 * Product Cart Button
 */

if (! function_exists('sc_product_cart_button')) {
	function sc_product_cart_button($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		if( mfn_opts_get('shop-catalogue') ) return;

		$is_sample = false;

		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
			$is_sample = true;
		}

		if( !$product ) return;

		$classes = '';

		if( !empty($attr['variations-label']) ) $classes = 'mfn-vr-label-top';

		if(!empty( $attr['style:.single_variation_wrap:text-align'] )){
			$classes .= ' mfn_product_cart_'.$attr['style:.single_variation_wrap:text-align'];
		}

		setup_postdata($product->get_id());

		ob_start();
		if( $is_sample ) add_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
		echo '<div class="mfn-product-add-to-cart '.$classes.'">';

			if( $is_sample ){
				$funname = 'woocommerce_'.$product->get_type().'_add_to_cart';
				if( function_exists($funname) ){
					$funname();
				}
			}

			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

			if( empty($attr['vb']) ) do_action('woocommerce_single_product_summary');

		echo '</div>';

  	$output = ob_get_clean();
  	wp_reset_postdata();

  	return $output;
	}
}

/**
 * Product Stock
 */

if (! function_exists('sc_product_stock')) {
	function sc_product_stock($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( !$product ) return;

		return wc_get_stock_html($product);
	}
}

/**
 * Product Content
 */

if (! function_exists('sc_product_content')) {
	function sc_product_content($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		$is_sample = false;

		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
			$is_sample = true;
		}

		if( !$product ) return;

		$mfn_builder = new Mfn_Builder_Front( $product->get_id() );

		ob_start();
		echo '<div class="woocommerce-product-details__description">';
			$mfn_builder->show(false, true); // hide product content edit in template
		echo '</div>';

		$output = ob_get_clean();
		return $output;
	}
}

/**
 * Product Tabs
 */

if (! function_exists('sc_product_tabs')) {
	function sc_product_tabs($attr) {
		if( !function_exists('is_woocommerce') ) return;
		$is_sample = false;

		global $product;

		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
			$is_sample = true;
		}

		if( !$product ) return;

		$classes = array('mfn-woocommerce-tabs');

		$classes[] = !empty($attr['nav']) ? 'mfn-woocommerce-tabs-nav-'.$attr['nav'] : 'mfn-woocommerce-tabs-nav-top';


		$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

		$x = 0; $y = 0;

		ob_start();

			if ( ! empty( $product_tabs ) ) :

				echo '<div class="'.implode(' ', $classes).'">

					<ul class="mfn-woocommerce-tabs-nav">';

						foreach ( $product_tabs as $key => $product_tab ) :
							echo '<li class="'.(++$x==1 ? 'active' : '').'">
								<a href="#tab-'.esc_attr( $key ).'">'.apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ).'</a>
							</li>';
						endforeach;

						if( count($product_tabs) == 1 && !empty($attr['vb']) ){
							echo '<li><a href="#tab-2">'.apply_filters( 'woocommerce_product_additional_information_tab_title', 'Additional information', 'additional_information' ).'</a></li>';
							echo '<li><a href="#tab-3">'.apply_filters( 'woocommerce_product_reviews_tab_title', 'Reviews', 'reviews' ).'</a></li>';
						}

					echo '</ul>';

					foreach ( $product_tabs as $key => $product_tab ) :
						echo '<div class="mfn-woocommerce-tabs-content tab-'.esc_attr( $key ).' '.(++$y==1 ? 'active' : '').'" id="tab-'.esc_attr( $key ).'">';
						if( $key != 'description' ) echo '<div class="section_wrapper">';
						if ( isset( $product_tab['callback'] ) && ( empty($attr['vb']) || $key == 'description' ) ) call_user_func( $product_tab['callback'], $key, $product_tab );
						if( $key != 'description' ) echo '</div>';
						echo '</div>';
					endforeach;

					do_action( 'woocommerce_product_after_tabs' );

				echo '</div>';

			endif;

		$output = ob_get_clean();

		return $output;
	}
}

/**
 * Product Short Desc
 */

if (! function_exists('sc_product_short_description')) {
	function sc_product_short_description($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		if( ! $product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( !$product ) return;

		$output = '<div class="woocommerce-product-details__short-description">';
			$output .= apply_filters( 'the_excerpt', get_the_excerpt( $product->get_id() ) );
		$output .= '</div>';

		return $output;
	}
}

/**
 * Product Title
 */

if (! function_exists('sc_product_title')) {
	function sc_product_title($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( !$product ) return;

		// title tag

		$title_class = '';
		if( ! empty($attr['title_tag']) ){
			if( 'p.lead' == $attr['title_tag'] ){
				$attr['title_tag'] = 'p';
				$title_class = 'lead';
			}
		}

		$output = '<'. mfn_allowed_title_tag($attr['title_tag']) .' class="woocommerce-products-header__title title page-title '. esc_attr($title_class) .'">';
		$output .= get_the_title( $product->get_id() );
		$output .= '</'. mfn_allowed_title_tag($attr['title_tag']) .'>';

		return $output;
	}
}

/**
 * Product Price
 */

if (! function_exists('sc_product_price')) {
	function sc_product_price($attr, $product = false)
	{
		if( !function_exists('is_woocommerce') ) return;
		if( !$product ){
			$sample = Mfn_Builder_Woo_Helper::sample_item('product');
			$product = wc_get_product($sample);
		}

		if( !$product ) return;

		$output = '<div class="price">'.$product->get_price_html().'</div>';
		return $output;
	}
}

/**
 * Shop Products
 */

if (! function_exists('sc_shop_products')) {
	function sc_shop_products($attr, $sample = false){

		if( !function_exists('is_woocommerce') ) return;

		remove_standard_woo_actions_archive();

		$output = '';

		$classes = array();

		$columns = array(
			'grid col-2' => 2,
			'grid' => 3,
			'grid col-4' => 4,
			'masonry' => 3,
			'list' => 1,
		);

		//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

		$layout = isset($attr['layout']) ? $attr['layout'] : 'grid';

		if( ! empty( $_GET['layout'] ) ){
			$layout = str_replace('grid4', 'grid col-4', esc_attr($_GET['layout']));
		}

		$classes[] = 'columns-'. $columns[$layout];
		$classes[] = $layout;

		// background

		if( ! empty( mfn_opts_get('background-archives-product') ) ){
			$classes[] = 'has-background-color';
		}

		// isotope

		if( 'masonry' == $layout ){
			$classes[] = 'isotope';
		}

		global $mfn_global;

		ob_start();

		if( !empty( get_post_meta( $mfn_global['shop_archive'], 'mfn_woo_cat_desc', true ) ) ) do_action( 'woocommerce_archive_description' );
		do_action('woocommerce_before_shop_loop');

		$output .= ob_get_clean();

		$output .= '<div class="products_wrapper mfn-woo-products lm_wrapper">';


	    $catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
	        'menu_order' => __( 'Default sorting', 'woocommerce' ),
	        'popularity' => __( 'Sort by popularity', 'woocommerce' ),
	        'rating' => __( 'Sort by average rating', 'woocommerce' ),
	        'date' => __( 'Sort by newness', 'woocommerce' ),
	        'price' => __( 'Sort by price: low to high', 'woocommerce' ),
	        'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
	 	) );

	 	/*if ( ! $show_default_orderby ) {
	        unset( $catalog_orderby_options['menu_order'] );
	    }*/

	    if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
	        unset( $catalog_orderby_options['rating'] );
    	}

    	if( !empty(mfn_opts_get('shop-infinite-load')) ) {
    		//$classes[] = 'lm_wrapper';
    		wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
    	}

    	if( !empty($attr['equal_heights']) && $attr['equal_heights'] != '0' ) {
    		$classes[] = 'mfn-equal-heights';
    	}

    	if( !empty($attr['equal_heights_last_el_class']) ) {
    		$classes[] = 'mfn-equal-height-el-'.$attr['equal_heights_last_el_class'];
    	}

		if(function_exists('is_woocommerce') && empty($attr['vb']) && empty( $_GET['visual']) && have_posts()):

		$output  .= '<ul class="products '. esc_attr(implode(' ', $classes)) .'">';
			while ( have_posts() ) {
				the_post();
				global $product;
				if($layout == 'masonry') $classes = array( 'isotope-item' );
				if ( empty( $product ) || ! $product->is_visible() ) continue; // visibility
				do_action( 'woocommerce_shop_loop' );
				$output .= Mfn_Builder_Woo_Helper::productslist($product, $attr, $classes);
			}
		$output .= '</ul>'; // end loop

		ob_start();

		if( !empty(mfn_opts_get('shop-infinite-load')) || !empty($attr['load_more']) ){
			echo '<div class="mfn-infinite-load-button">';
				echo mfn_pagination(false, true);
			echo '</div>';
		}else{
			if( is_product_category() ){
				echo mfn_pagination();
			}else{
				woocommerce_pagination();
			}
		}
		$output .= ob_get_clean();

		elseif( !empty($attr['vb']) || !empty( $_GET['visual']) ):

			$sample_loop = Mfn_Builder_Woo_Helper::sample_products_loop($attr);

			$output  .= '<ul class="products '. esc_attr(implode(' ', $classes)) .'">';
			if($sample_loop->have_posts()):
				while ( $sample_loop->have_posts() ) {
					$sample_loop->the_post();
					global $product;
					if($layout == 'masonry') $classes = array( 'isotope-item' );
					if ( empty( $product ) || ! $product->is_visible() ) continue; // visibility
					do_action( 'woocommerce_shop_loop' );
					$output .= Mfn_Builder_Woo_Helper::productslist($product, $attr, $classes);
				}
			endif;
			$output .= '</ul>'; // end loop

		else:

			$output .= '<div class="alert alert_info">';
				$output .= '<div class="alert_icon"><svg viewBox="0 0 28 28" aria-hidden="true"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><circle class="path" cx="14" cy="14" r="12"/><path class="path" d="M11.2,9.12a3.4,3.4,0,0,1,3-1.69,2.84,2.84,0,0,1,3,2.76,3.16,3.16,0,0,1-.84,2.23c-.63.74-1.58,1.18-2.19,1.88a1,1,0,0,0-.26.64v2.32"/><circle class="path" cx="14" cy="20" r="0.33"/></g></svg></div>';
				$output .= '<div class="alert_wrapper">'.__('No products were found matching your selection.', 'woocommerce').'</div>';
			$output .= '</div>';

		endif;

		$output .= '</div>';

		wp_enqueue_script('mfn-isotope', get_theme_file_uri('/js/plugins/isotope.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		return $output;
	}
}

/**
 * Shop Title
 */

if (! function_exists('sc_shop_title')) {
	function sc_shop_title($attr)
	{
		// title tag

		$title_class = '';
		if( ! empty($attr['title_tag']) ){
			if( 'p.lead' == $attr['title_tag'] ){
				$attr['title_tag'] = 'p';
				$title_class = 'lead';
			}
		}

		if( !function_exists('is_woocommerce') ) return;
		$output  = '<'. mfn_allowed_title_tag($attr['title_tag']) .' class="woocommerce-products-header__title page-title '. esc_attr($title_class) .'">';
			if(get_option( 'woocommerce_shop_page_id' )){
				$output .= woocommerce_page_title( false );
			}else{
				$output .= 'Shop Title';
			}
		$output .= '</'. mfn_allowed_title_tag($attr['title_tag']) .'>'."\n";

		return $output;
	}
}

/**
 * Shop Categories
 */

if (! function_exists('sc_shop_categories')) {
	function sc_shop_categories($attr)
	{
		if( !function_exists('is_woocommerce') ) return;

		global $wp_query;

		extract(shortcode_atts(array(
			'columns' => 3,
			'category' => '',
			'display' => '',
			'subcategory' => '',
			'count' => 0,
		), $attr));

		$attrs = array(
			'taxonomy' => 'product_cat',
			'hide_empty' => !empty($attr['empty']) && $attr['empty'] == '1' ? true : false,
		);

    	$output = '';

    	$order = str_replace(' ', '', $attr['order']);
    	$order_arr = explode(',', $order);

    	$classes = 'order-'.str_replace(array(',', ' '), array('-', ''), $order);

    	$parent = false;

    	if( !empty($attr['category']) ){
    		$getterm = get_term_by('slug', $attr['category'], 'product_cat');
    		if( !empty($getterm->term_id) ){
    			$parent = $getterm->term_id;
    		}
    	}

    	if( !empty($subcategory) && $subcategory == 1 && is_product_category() ){
    		$parent = $wp_query->get_queried_object()->term_id;
    		$attrs['parent'] = $parent;
    	}elseif( !empty($display) && $display == 1 ){
    		$attrs['parent'] = 0;
    	}else{
    		$attrs['child_of'] = $parent;
    	}

		$cats = get_terms($attrs);

		if(count($cats) > 0){
			$output  .= '<div class="shop_categories '.$classes.'">';
				$output .= '<div class="woocommerce columns-'.$attr['columns'].'"><ul class="products">';
				foreach($cats as $cat){
					$output .= '<li class="product-category product">';
						$output .= '<a href="'.get_term_link( $cat->term_id, 'product_cat' ).'">';
							if(isset($order_arr) && count($order_arr) > 0){
								foreach($order_arr as $el){
									$fun_name = 'get_woo_cat_'.$el;
									$output .= Mfn_Builder_Woo_Helper::$fun_name($attr, $cat);
								}
							}
						$output .= '</a>';
					$output .= '</li>';
				}
				$output .= '</ul></div>';
			$output .= '</div>';
		}

		return $output;
	}
}

/**
 * Column One Sixth
 * [one_sixth] [/one_sixth]
 */

if (! function_exists('sc_one_sixth')) {
	function sc_one_sixth($attr, $content = null)
	{
		$output = '<div class="column one-sixth mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column One Fifth
 * [one_fifth] [/one_fifth]
 */

if (! function_exists('sc_one_fifth')) {
	function sc_one_fifth($attr, $content = null)
	{
		$output = '<div class="column one-fifth mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column One Fourth
 * [one_fourth] [/one_fourth]
 */

if (! function_exists('sc_one_fourth')) {
	function sc_one_fourth($attr, $content = null)
	{
		$output = '<div class="column one-fourth mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column One Third
 * [one_third] [/one_third]
 */

if (! function_exists('sc_one_third')) {
	function sc_one_third($attr, $content = null)
	{
		$output = '<div class="column one-third mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column Two Fifth [two_fifth] [/two_fifth]
 */

if (! function_exists('sc_two_fifth')) {
	function sc_two_fifth($attr, $content = null)
	{
		$output = '<div class="column two-fifth mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column One Second [one_second] [/one_second]
 */

if (! function_exists('sc_one_second')) {
	function sc_one_second($attr, $content = null)
	{
		$output = '<div class="column one-second mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column Two Fifth [three_fifth] [/three_fifth]
 */

if (! function_exists('sc_three_fifth')) {
	function sc_three_fifth($attr, $content = null)
	{
		$output = '<div class="column three-fifth mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column Two Third [two_third] [/two_third]
 */

if (! function_exists('sc_two_third')) {
	function sc_two_third($attr, $content = null)
	{
		$output = '<div class="column two-third mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column Three Fourth [three_fourth] [/three_fourth]
 */

if (! function_exists('sc_three_fourth')) {
	function sc_three_fourth($attr, $content = null)
	{
		$output = '<div class="column three-fourth mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column Two Fifth [four_fifth] [/four_fifth]
 */

if (! function_exists('sc_four_fifth')) {
	function sc_four_fifth($attr, $content = null)
	{
		$output = '<div class="column four-fifth mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column Two Fifth [five_sixth] [/five_sixth]
 */

if (! function_exists('sc_five_sixth')) {
	function sc_five_sixth($attr, $content = null)
	{
		$output = '<div class="column five-sixth mobile-one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Column One [one] [/one]
 */

if (! function_exists('sc_one')) {
	function sc_one($attr, $content = null)
	{
		$output = '<div class="column one">';
			$output .= '<div class="mcb-column-inner">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Code [code] [/code]
 */

if (! function_exists('sc_code')) {
	function sc_code($attr, $content = null)
	{
		$output  = '<pre class="mfn-code">';
			$output .= do_shortcode(htmlspecialchars($content ?? ''));
		$output .= '</pre>'."\n";

		return $output;
	}
}

/**
 * Article Box [article_box] [/article_box]
 */

if (! function_exists('sc_article_box')) {
	function sc_article_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' 	=> '',
			'slogan' 	=> '',
			'title' 	=> '',
			'title_tag' 	=> '',
			'link' 		=> '',
			'link_title' 		=> '',
			'target' 	=> '',
		), $attr));

		// image | visual composer fix

		$image = mfn_vc_image($image);

		// image class

		$img_class = 'scale-with-grid';

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// src output

		if( strpos($image, '{') !== false ){
			$image = be_dynamic_data($image);
			if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');
			$src = 'src="'. esc_url($image) .'"';
		}else{
			$src = 'src="'. esc_url($image) .'"';
		}


		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		$title = be_dynamic_data($title);
		$slogan = be_dynamic_data($slogan);
		$link_title = be_dynamic_data($link_title);
		$link = be_dynamic_data($link);

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="article_box mcb_column_wrapper">';


			if ($link) {
				// This variable has been safely escaped above in this function
				$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
			}

			$output .= '<div class="photo_wrapper">';

				$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. mfn_get_attachment_data($image, 'width') .'" height="'. mfn_get_attachment_data($image, 'height') .'"/>';
			$output .= '</div>';

			$output .= '<div class="desc_wrapper">';

				if ($slogan) {
					$output .= '<p><span>'. wp_kses($slogan, mfn_allowed_html()) .'</span></p>';
				}
				if ($title) {
					$title_tag = !empty($attr['title_tag']) ? $attr['title_tag'] : 'h4';
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
				}

				$output .= '<i class="icon-right-open themecolor" aria-hidden="true"></i>';

			$output .= '</div>';

			if ($link) {
				$output .= '</a>';
			}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Heading
 */

if (! function_exists('sc_heading')) {
	function sc_heading($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' => '',
			'header_tag' => 'h2',
			'link' => '',
			'link_title' => '',
			'target' => '',
			'onclick' => '',
		), $attr));

		// class

		$class = '';

		if( !empty($attr['style:.mcb-section .mcb-wrap .mcb-item-mfnuidelement .title:background-image']) || !empty($attr['css_bg_img']) ){
			$class .= ' mfn-mask-shape';
		}

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		if ( !empty($onclick) ) {
			$onclick_escaped = 'onclick="'. esc_js($onclick) .'"';
		} else {
			$onclick_escaped = '';
		}

		// dynamic data

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);

		// title tag

		if( ! empty($header_tag) ){
			if( 'p.lead' == $header_tag ){
				$header_tag = 'p';
				$class .= ' lead';
			}
		}

		// output -----

		$output = '';

		$output .= '<'. mfn_allowed_title_tag($header_tag) .' class="title'. $class.'">';
			if( ! empty($link) ) $output .= '<a class="title_link" href="'. $link .'" '. $target_escaped .' '. $onclick_escaped .' title="'. esc_attr($link_title ?? '') .'">';
				$output .= do_shortcode($title ?? '');
			if( ! empty($link) ) $output .= '</a>';
		$output .= '</'. mfn_allowed_title_tag($header_tag) .'>';

		return $output;
	}
}

/**
 * Helper [helper] [/helper]
 */

if (! function_exists('sc_helper')) {
	function sc_helper($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' 		=> '',
			'title_tag' => 'h4',

			'title1' 		=> '',
			'content1' 	=> '',
			'link1' 		=> '',
			'target1' 	=> '',
			'class1' 		=> '',

			'title2' 		=> '',
			'content2' 	=> '',
			'link2' 		=> '',
			'target2' 	=> '',
			'class2' 		=> '',

		), $attr));

		$title = be_dynamic_data($title);

		$title1 = be_dynamic_data($title1);
		$title2 = be_dynamic_data($title2);

		$content1 = be_dynamic_data($content1);
		$content2 = be_dynamic_data($content2);

		$link1 = be_dynamic_data($link1);
		$link2 = be_dynamic_data($link2);

		// target

		if ($target1) {
			$target_1_escaped = 'target="_blank"';
		} else {
			$target_1_escaped = false;
		}

		if ($target2) {
			$target_2_escaped = 'target="_blank"';
		} else {
			$target_2_escaped = false;
		}

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link1) ) $link1 = '#';
			$class1 .= ' open-mfn-popup';
			$target_1_escaped = ' data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		if( !empty( $attr['link_type_2'] ) && !empty($attr['popup_id_2']) ){
			if( empty($link2) ) $link2 = '#';
			$class2 .= ' open-mfn-popup';
			$target_2_escaped = ' data-mfnpopup="'. esc_attr($attr['popup_id_2']) .'"';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="helper">';

			$output .= '<div class="helper_header">';

				if ($title) {
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
				}

				$output .= '<div class="links">';

					if ($title1) {
						if ($link1) {
							// This variable has been safely escaped above in this function
							$output .= '<a class="link link-1 '. esc_attr($class1) .'" href="'. esc_url($link1) .'" '. $target_1_escaped .'>'. wp_kses($title1, mfn_allowed_html()) .'</a>';
						} else {
							$output .= '<a class="link link-1 toggle" href="#" data-rel="1">'. wp_kses($title1, mfn_allowed_html()) .'</a>';
						}
					}

					if ($title2) {
						if ($link2) {
							// This variable has been safely escaped above in this function
							$output .= '<a class="link link-2 '. esc_attr($class2) .'" href="'. esc_url($link2) .'" '. $target_2_escaped .'>'. wp_kses($title2, mfn_allowed_html()) .'</a>';
						} else {
							$output .= '<a class="link link-2 toggle" href="#" data-rel="2">'. wp_kses($title2, mfn_allowed_html()) .'</a>';
						}
					}

				$output .= '</div>';

			$output .= '</div>';

			$output .= '<div class="helper_content">';

				if (! $link1) {
					$output .= '<div class="item item-1">'. do_shortcode($content1 ?? '') .'</div>';
				}

				if (! $link2) {
					$output .= '<div class="item item-2">'. do_shortcode($content2 ?? '') .'</div>';
				}

			$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Before After [before_after] [/before_after]
 */

if (! function_exists('sc_before_after')) {
	function sc_before_after($attr, $content = null)
	{

		extract(shortcode_atts(array(
			'image_before'	=> '',
			'image_after' 	=> '',
			'size' 	=> 'full',
			'label_before' 	=> '',
			'label_after' 	=> '',
			'classes' 			=> '',
		), $attr));

		// image | visual composer fix

		$image_before = be_dynamic_data($image_before);
		if( is_numeric($image_before) ) $image_before = wp_get_attachment_image_url($image_before, 'full');

		$image_after = be_dynamic_data($image_after);
		if( is_numeric($image_after) ) $image_after = wp_get_attachment_image_url($image_after, 'full');

		$image_before = mfn_vc_image($image_before);
		$image_after = mfn_vc_image($image_after);

		// size

		if( empty($size) ){
			$size = 'full';
		}

		// labels

		if( ! $label_before ){
			$label_before = mfn_opts_get('translate') ? mfn_opts_get('translate-before', 'Before') : __('Before', 'betheme');
		}
		if( ! $label_after ){
			$label_after = mfn_opts_get('translate') ? mfn_opts_get('translate-after', 'After') : __('After', 'betheme');
		}

		// output -----

		$output = '<div class="before_after twentytwenty-container" data-before="'. esc_html($label_before) .'" data-after="'. esc_html($label_after) .'">';

			if( $image_output_before = mfn_get_attachment($image_before, $size) ){
				$output .= $image_output_before;
			} else {
				$output .= '<img class="scale-with-grid" src="'. esc_url($image_before) .'" alt="'. esc_attr(mfn_get_attachment_data($image_before, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image_before, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image_before, 'height')) .'"/>';
			}

			if( $image_output_after = mfn_get_attachment($image_after, $size) ){
				$output .= $image_output_after;
			} else {
				$output .= '<img class="scale-with-grid" src="'. esc_url($image_after) .'" alt="'. esc_attr(mfn_get_attachment_data($image_after, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image_after, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image_after, 'height')) .'"/>';
			}

		$output .= '</div>'."\n";

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-imagesloaded', get_theme_file_uri('/js/plugins/imagesloaded.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-eventmove', get_theme_file_uri('/js/plugins/eventmove.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-beforeafter', get_theme_file_uri('/js/plugins/beforeafter.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Flat Box [flat_box] [/flat_box]
 */

if (! function_exists('sc_flat_box')) {
	function sc_flat_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' 			=> '',
			'title' 			=> '',
			'title_tag' 	=> '',
			'icon' 				=> 'icon-lamp',
			'icon_image' 	=> '',
			'background' 	=> '',
			'link' 				=> '',
			'link_title' 				=> '',
			'target' 			=> '',
		), $attr));

		// image | visual composer fix

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$link_title = be_dynamic_data($link_title);
		$content = be_dynamic_data($content);

		$image = be_dynamic_data($image);

		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);
		$icon_image = mfn_vc_image($icon_image);

		// image class

		$img_class = 'photo scale-with-grid';

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// background

		if ($background) {
			$background_escaped = 'style="background-color:'. esc_attr($background) .'"';
		} else {
			$background_escaped = false;
		}

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="flat_box">';

			if ($link) {
				// This variable has been safely escaped above in this function
				$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
			}

				$output .= '<div class="photo_wrapper">';

					// This variable has been safely escaped above in this function
					$output .= '<div class="icon themebg" '. $background_escaped .'>';

						if ($icon_image) {
							$output .= '<img class="scale-with-grid" src="'. esc_url($icon_image) .'" alt="'. esc_attr(mfn_get_attachment_data($icon_image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($icon_image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($icon_image, 'height')) .'"/>';
						} else {
							$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
						}

					$output .= '</div>';

					$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';

				$output .= '</div>';

				$output .= '<div class="desc_wrapper">';

					if ($title) {
						$title_tag = ! empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
						$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
					}

					if ($content) {
						$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';
					}

				$output .= '</div>';

			if ($link) {
				$output .= '</a>';
			}


		$output .= '</div>'."\n";

		if( !isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-imagesloaded', get_theme_file_uri('/js/plugins/imagesloaded.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Flat Box [feature_box] [/feature_box]
 */

if (! function_exists('sc_feature_box')) {
	function sc_feature_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' 		=> '',
			'title' 		=> '',
			'title_tag' => '',
			'background'=> '',
			'link' 			=> '',
			'link_title' 			=> '',
			'target' 		=> '',
		), $attr));

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		$title = be_dynamic_data($title);
		$content = be_dynamic_data($content);
		$link = be_dynamic_data($link);
		$link_title = be_dynamic_data($link_title);

		// image class

		$img_class = 'scale-with-grid';

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// background

		if ($background) {
			$background_escaped = 'style="background-color:'. esc_attr($background) .'"';
		} else {
			$background_escaped = false;
		}

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$target_escaped = 'class="open-mfn-popup"';
			$target_escaped .= ' data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="feature_box">';


			// This variable has been safely escaped above in this function
			$output .= '<div class="feature_box_wrapper" '. $background_escaped .'>';

				$output .= '<div class="photo_wrapper">';

					if ($link) {
						// This variable has been safely escaped above in this function
						$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
					}

					$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'" />';

					if ($link) {
						$output .= '</a>';
					}

				$output .= '</div>';

				$output .= '<div class="desc_wrapper">';

					if ($title) {
						$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
						$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
					}

					if ($content) {
						$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';
					}

				$output .= '</div>';

			$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Payment methods [payment_methods] [/payment_methods]
 */

if (! function_exists('sc_payment_methods')) {
	function sc_payment_methods($attr)
	{
		extract(shortcode_atts(array(
			'dynamic_items' 		=> '',
			'greyscale' 			=> '',
			'invert'				=> ''
		), $attr));

		$classes = array();

		if( !empty($greyscale) ) $classes[] = 'greyscale';
		if( !empty($invert) ) $classes[] = 'invert';

		if( empty($dynamic_items) ){
			$classes[] = 'empty';
		}

		$classes = count($classes) > 0 ? implode(' ', $classes) : '';

		$output = '';

		$output .= '<ul class="payment-methods-list '. esc_attr($classes) .'">';

			if( !empty($dynamic_items) && is_iterable($dynamic_items) ){
				foreach ($dynamic_items as $d=>$di) {
					if( empty( $di['type'] ) ) continue;
					if( $di['type'] == 'predefined' ){
						$output .= '<li data-uid="'.$di['uid'].'" class="uid-'.$di['uid'].'"><img src="'. get_template_directory_uri() .'/images/payment-methods/'.$di['id'].'.svg" alt="payment" width="35" height="24" loading="lazy"></li>';
					}else{
						$output .= '<li data-uid="'.$di['uid'].'" class="uid-'.$di['uid'].'"><img src="'. esc_url($di['url']) .'" alt="'. esc_attr(mfn_get_attachment_data($di['url'], 'alt')) .'" width="35" height="24" loading="lazy"></li>';
					}
				}
			}else{
				$output .= '<li><img src="'.get_template_directory_uri().'/images/payment-methods/Mastercard.svg" alt="Mastercard" width="35" height="24" loading="lazy"></li>';
				$output .= '<li><img src="'.get_template_directory_uri().'/images/payment-methods/PayPal.svg" alt="PayPal" width="35" height="24" loading="lazy"></li>';
				$output .= '<li><img src="'.get_template_directory_uri().'/images/payment-methods/ApplePay.svg" alt="ApplePay" width="35" height="24" loading="lazy"></li>';
			}

		$output .= '</ul>';

		return $output;
	}
}

/**
 * Photo Box [photo_box] [/photo_box]
 */

if (! function_exists('sc_photo_box')) {
	function sc_photo_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' 		=> '',
			'title' 		=> '',
			'title_tag' 		=> '',
			'align' 		=> '',
			'link' 			=> '',
			'link_title' 			=> '',
			'target' 		=> '',
			'greyscale' => '',
		), $attr));

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$link_title = be_dynamic_data($link_title);
		$content = be_dynamic_data($content);

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		// image class

		$img_class = 'scale-with-grid';

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// class

		$class = '';

		if ($align) {
			$class .= ' pb_'. $align;
		}
		if ($greyscale) {
			$class .= ' greyscale';
		}
		if (! $content) {
			$class .= ' without-desc';
		}

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="photo_box '. esc_attr($class) .'">';


				if ($title) {
					$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
				}

				if ($image) {
					$output .= '<div class="image_frame">';
					$output .= '<div class="image_wrapper">';

					if ($link) {
						// This variable has been safely escaped above in this function
						$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
					}

					$output .= '<div class="mask"></div>';
					$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';

					if ($link) {
						$output .= '</a>';
					}

					$output .= '</div>';
					$output .= '</div>';
				}

				if ($content) {
					$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';
				}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Plain text [plain_text] [/plain_text]
 */

if (! function_exists('sc_plain_text')) {
	function sc_plain_text($attr, $content = null) {

		extract(shortcode_atts(array(
			'content' => '',
		), $attr));

		$is_dd = false;

		if( ($content == '{termmeta:mfn_product_cat_top_content}' || $content == '{termmeta:mfn_product_cat_bottom_content}') && (!empty($attr['vb']) || apply_filters('bebuilder_preview', false)) ) {
			$is_dd = str_replace(array('{termmeta:mfn_', '}'), '', $content);
			$is_dd = str_replace('cat', 'category', $is_dd);
		}

		$content = be_dynamic_data($content);

		$output = '<div class="desc">';

			if( !empty( $attr['shortcodes_parser'] ) ){
				$output .= do_shortcode($content ?? '');
			}else{
				$output .= $content;
			}

			if( !empty($is_dd) && empty($content) && ( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ) ) {
				$output .= '<div class="mfn-widget-placeholder"><img class="item-preview-image" src="'.get_theme_file_uri('/visual-builder/assets/_dark/svg/items/post-content.svg').'" alt=""><p>'.str_replace('_', ' ', $is_dd).'</p></div>';
			}elseif( empty($content) && ( !empty($attr['vb']) || apply_filters('bebuilder_preview', false) ) ) {
				$output .= '<div class="mfn-widget-placeholder"><img class="item-preview-image" src="'.get_theme_file_uri('/visual-builder/assets/_dark/svg/items/post-content.svg').'" alt=""></div>';
			}

		$output .= '</div>';

		return $output;

	}
}

/**
 * Zoom Box [zoom_box] [/zoom_box]
 */

if (! function_exists('sc_zoom_box')) {
	function sc_zoom_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' 				=> '',
			'bg_color' 			=> '',
			'content_image' => '',
			'link' 					=> '',
			'link_title' 					=> '',
			'target' 				=> '',
		), $attr));

		$content = be_dynamic_data($content);
		$link_title = be_dynamic_data($link_title);
		$link = be_dynamic_data($link);

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$content_image = be_dynamic_data($content_image);
		if( is_numeric($content_image) ) $content_image = wp_get_attachment_image_url($content_image, 'full');

		$image = mfn_vc_image($image);
		$content_image = mfn_vc_image($content_image);

		// image class

		$img_class = 'scale-with-grid';

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$target_escaped = 'class="open-mfn-popup"';
			$target_escaped .= ' data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		$color = false;

		if($bg_color){
			$color = 'style="background-color:'. esc_attr(mfn_hex2rgba($bg_color, 0.8)) .';"';
		}

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// output -----

		$output = '<div class="zoom_box">';

			if ($link) {
				// This variable has been safely escaped above in this function
				$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title) .'">';
			}

				$output .= '<div class="photo">';
					$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
				$output .= '</div>';

				$output .= '<div class="desc" '.$color.'>';
					$output .= '<div class="desc_wrap">';

					if ($content_image) {
						$output .= '<div class="desc_img">';
							$output .= '<img class="scale-with-grid" src="'. esc_url($content_image) .'" alt="'. esc_attr(mfn_get_attachment_data($content_image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($content_image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($content_image, 'height')) .'"/>';
						$output .= '</div>';
					}

					if ($content) {
						$output .= '<div class="desc_txt">';
							$output .= do_shortcode($content ?? '');
						$output .= '</div>';
					}

					$output .= '</div>';
				$output .= '</div>';

			if ($link) {
				$output .= '</a>';
			}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Sliding Box [sliding_box] [/sliding_box]
 */

if (! function_exists('sc_sliding_box')) {
	function sc_sliding_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' 	=> '',
			'title' 	=> '',
			'title_tag' 	=> '',
			'link' 		=> '',
			'link_title' 		=> '',
			'target' 	=> '',
		), $attr));

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$link_title = be_dynamic_data($link_title);

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="sliding_box">';

				if ($link) {
					// This variable has been safely escaped above in this function
					$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title) .'">';
				}

					$output .= '<div class="photo_wrapper">';
						$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
					$output .= '</div>';

					$output .= '<div class="desc_wrapper">';
						if ($title) {
							$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
							$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
						}
					$output .= '</div>';

				if ($link) {
					$output .= '</a>';
				}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Story Box [story_box] [/story_box]
 */

if (! function_exists('sc_story_box')) {
	function sc_story_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' 	=> '',
			'style' 	=> '',
			'title' 	=> '',
			'title_tag' 	=> '',
			'link' 		=> '',
			'link_title' 		=> '',
			'target' 	=> '',
		), $attr));

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$content = be_dynamic_data($content);
		$link_title = be_dynamic_data($link_title);

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="story_box '. esc_attr($style) .'">';

				if ( $link ) {
					// This variable has been safely escaped above in this function
					$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title) .'">';
				}

					if ( $image ) {
					$output .= '<div class="photo_wrapper">';
						$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
					$output .= '</div>';
					}

					$output .= '<div class="desc_wrapper">';

						if ($title) {
							$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h3';
							$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="themecolor title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
							$output .= '<hr class="hr_color">';
						}

					$output .= '</div>';

				if ($link) {
					$output .= '</a>';
				}

				$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Trailer Box [trailer_box]
 */

if (! function_exists('sc_trailer_box')) {
	function sc_trailer_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' => '',
			'orientation' => '',
			'slogan' => '',
			'title' => '',
			'title_tag' => '',

			'link' => '',
			'link_title' => '',
			'target' => '',

			'style' => '', // [default], plain
		), $attr));

		// image | visual composer fix

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$slogan = be_dynamic_data($slogan);

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		// class

		$class = '';
		if ($style) {
			$class .= $style;
		}

		if ($orientation) {
			$class .= ' '. $orientation;
		}

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$target_escaped = 'class="open-mfn-popup"';
			$target_escaped .= ' data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="trailer_box '. esc_attr(trim($class)) .'">';

				if ($link) {
					// This variable has been safely escaped above in this function
					$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title) .'">';
				}

					$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';

					$output .= '<div class="desc">';

						if ($slogan) {
							$output .= '<div class="subtitle">'. wp_kses($slogan, mfn_allowed_html()) .'</div>';
						}
						if ($title) {
							$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h2';
							$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
						}

						$output .= '<div class="line"></div>';

					$output .= '</div>';

				if ($link) {
					$output .= '</a>';
				}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Promo Box [promo_box] [/promo_box]
 */

if (! function_exists('sc_promo_box')) {
	function sc_promo_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' 		=> '',
			'title' 		=> '',
			'title_tag' 		=> '',
			'btn_text' 	=> '',
			'btn_link' 	=> '',
			'link_title' 	=> '',
			'position' 	=> 'left',
			'border' 		=> '',
			'target' 		=> '',
		), $attr));

		$title = be_dynamic_data($title);
		$content = be_dynamic_data($content);

		$btn_text = be_dynamic_data($btn_text);
		$btn_link = be_dynamic_data($btn_link);
		$link_title = be_dynamic_data($link_title);

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		// border

		if ($border) {
			$border = 'has_border';
		} else {
			$border = 'no_border';
		}

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="promo_box '. esc_attr($border) .'">';

				$output .= '<div class="promo_box_wrapper promo_box_'. esc_attr($position) .'">';

					$output .= '<div class="photo_wrapper">';
						if ($image) {
							$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
						}
					$output .= '</div>';

					$output .= '<div class="desc_wrapper">';

						if ($title) {
							$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h2';
							$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
						}

						if ($content) {
							$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';
						}

						if ($btn_link) {
							// This variable has been safely escaped above in this function
							$output .= '<a href="'. esc_url($btn_link) .'" class="button button_theme has-icon" '. $target_escaped .' title="'. esc_attr($link_title) .'">';
								$output .= '<span class="button_icon">';
									$output .= '<i class="icon-layout" aria-hidden="true"></i>';
								$output .= '</span>';
								$output .= '<span class="button_label">'. esc_html($btn_text) .'</span>';
							$output .= '</a>';
						}

					$output .= '</div>';

				$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Share Box [share_box]
 */

if (! function_exists('sc_share_box')) {
	function sc_share_box($attr, $content = null)
	{
		$output = mfn_share('item');

		return $output;
	}
}

/**
 * How It Works [how_it_works] [/how_it_works]
 */

if (! function_exists('sc_how_it_works')) {
	function sc_how_it_works($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image' 	=> '',
			'number' 	=> '',
			'title' 	=> '',
			'title_tag' 	=> '',

			'border' 	=> '',
			'style' 	=> '',

			'link' 		=> '',
			'link_title' 		=> '',
			'target' 	=> '',
		), $attr));

		$link = be_dynamic_data($link);
		$title = be_dynamic_data($title);
		$link_title = be_dynamic_data($link_title);
		$content = be_dynamic_data($content);

		// image | visual composer fix

		$image = mfn_vc_image($image);

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// class

		$class = '';

		// border

		if ($border) {
			$class .= ' has_border';
		} else {
			$class .= ' no_border';
		}

		// style

		if ($style) {
			$class .= ' '. $style;
		}

		// image

		if (! $image) {
			$class .= ' no-img';
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="how_it_works '. esc_attr($class) .'">';

				if ($link) {
					// This variable has been safely escaped above in this function
					$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
				}

					$output .= '<div class="image_wrapper"><div class="image">';
						if ($image) {
							$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'">';
						}

					$output .= '</div>';

					if ($number) {
						$output .= '<span class="number">'. esc_html($number) .'</span>';
					}

					$output .= '</div>';

				if ($link) {
					$output .= '</a>';
				}

				if ($title) {
					$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
				}

				$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Blog [blog]
 */

if (! function_exists('sc_blog')) {
	function sc_blog($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'count'					=> 2,
			'style'					=> 'classic', //  classic, grid, masonry, masonry tiles, photo, photo2, timeline
			'columns'				=> 3,
			'title_tag'			=> 'h2',
			'images'				=> '',

			'category'			=> '',
			'category_multi'=> '',
			'orderby'				=> 'date',
			'order'					=> 'DESC',

			'exclude_id'		=> '',
			'related'				=> '',
			'filters'				=> '',
			'excerpt'				=> true,
			'more'					=> '',

			'pagination'		=> '',
			'load_more'			=> '',

			'greyscale'			=> '',
			'margin'				=> '',

			'events'				=> '',
		), $attr));

		// translate

		$translate['filter'] = mfn_opts_get('translate') ? mfn_opts_get('translate-filter', 'Filter by') : __('Filter by', 'betheme');
		$translate['tags'] = mfn_opts_get('translate') ? mfn_opts_get('translate-tags', 'Tags') : __('Tags', 'betheme');
		$translate['authors'] = mfn_opts_get('translate') ? mfn_opts_get('translate-authors', 'Authors') : __('Authors', 'betheme');
		$translate['all'] = mfn_opts_get('translate') ? mfn_opts_get('translate-item-all', 'All') : __('All', 'betheme');
		$translate['categories'] = mfn_opts_get('translate') ? mfn_opts_get('translate-categories', 'Categories') : __('Categories', 'betheme');

		// query args

		$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

		$args = array(
			'posts_per_page'			=> intval($count, 10),
			'paged' 							=> $paged,
			'orderby'							=> $orderby,
			'order'								=> $order,
			'ignore_sticky_posts'				=> !empty($attr['sticky_posts']) ? true : false,
			'post_status'					=> 'publish',
			//'ignore_sticky_posts'	=> false,
		);

		// private

		if (is_user_logged_in()) {
			$args['post_status'] = array( 'publish', 'private' );
		}

		// Include events | The events calendar

		if ($events) {
			$args['post_type'] = array( 'post', 'tribe_events' );
		}

		// categories

		if ($category_multi) {
			$args['category_name'] = trim($category_multi);
		} elseif ($category) {
			$args['category_name'] = $category;
		}

		// exclude posts

		if ($exclude_id) {
			$exclude_id = str_replace(' ', '', $exclude_id);
			$args['post__not_in'] = explode(',', $exclude_id);
		}

		// related posts

		if ( ! empty($related) && !isset( $attr['vb'] ) && empty( $_GET['visual'] ) ) {

			/*if( isset($attr['pageid']) ) {
				$id = $attr['pageid'];
			} else {
				$id = mfn_ID() ? mfn_ID() : $_POST['pageid'];
			}*/

			$args['post__not_in'] = [ get_the_ID() ];

			$aCategories = wp_get_post_categories(get_the_ID());
			$args['category__in'] = $aCategories;

		}

		if( ( is_home() || is_category() || is_tag() || is_author() ) && !empty(Mfn_Builder_Front::$post_id2) && !in_array(get_post_meta(Mfn_Builder_Front::$post_id2, 'mfn_template_type', true), array('footer', 'header')) ) {
			$query_blog = false;
		}else{
			$query_blog = new WP_Query($args);
		}


		// classes

		$classes = $style;

		if ($greyscale) {
			$classes .= ' greyscale';
		}
		if ($margin) {
			$classes .= ' margin';
		}
		if (! $more) {
			$classes .= ' hide-more';
		}

		if ($filters || in_array($style, array( 'masonry', 'masonry tiles' ))) {
			$classes .= ' isotope';

			if( ! isset( $attr['vb'] ) ){
				wp_enqueue_script('mfn-isotope', get_theme_file_uri('/js/plugins/isotope.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			}
		}

		// output -----

		$output = '<div class="column_filters">';

			// output | Filters

			if ($filters && (! $category) && (! $category_multi)) {

				$filters_class = '';
				if ($filters != 1) {
					$filters_class .= ' only '. $filters;
				}

				$output .= '<div id="Filters" class="isotope-filters '. esc_attr($filters_class) .'" data-parent="column_filters">';
					$output .= '<div class="mcb-column-inner">';

						$output .= '<ul class="filters_buttons">';

							$output .= '<li class="label">'. esc_html($translate['filter']) .'</li>';
							$output .= '<li class="categories"><a class="open" href="#"><i class="icon-docs" aria-hidden="true"></i>'. esc_html($translate['categories']) .'<i class="icon-down-dir" aria-hidden="true"></i></a></li>';
							$output .= '<li class="tags"><a class="open" href="#"><i class="icon-tag" aria-hidden="true"></i>'. esc_html($translate['tags']) .'<i class="icon-down-dir" aria-hidden="true"></i></a></li>';
							$output .= '<li class="authors"><a class="open" href="#"><i class="icon-user" aria-hidden="true"></i>'. esc_html($translate['authors']) .'<i class="icon-down-dir" aria-hidden="true"></i></a></li>';

						$output .= '</ul>';

						$output .= '<div class="filters_wrapper">';

							// categories

							$output .= '<ul class="categories">';

								$output .= '<li class="reset '.( is_home() ? 'current-cat' : '' ).'"><a class="all" data-rel="*" href="'.get_permalink( get_option( 'page_for_posts' ) ).'">'. esc_html($translate['all']) .'</a></li>';

								if ($categories = get_categories()) {

									$exclude = mfn_get_excluded_categories();

									foreach ($categories as $category) {

										if ($exclude && in_array($category->slug, $exclude)) {
											continue;
										}

										$output .= '<li class="'. esc_attr($category->slug) .' '.( is_category($category->slug) ? 'current-cat' : '' ).'"><a data-rel=".category-'. esc_attr($category->slug) .'" href="'. esc_url(get_term_link($category)) .'">'. esc_html($category->name) .'</a></li>';

									}
								}

								$output .= '<li class="close"><a href="#" aria-label="'. __('icon close', 'betheme') .'"><i class="icon-cancel"></i></a></li>';

							$output .= '</ul>';

							// tags

							$output .= '<ul class="tags">';

								$output .= '<li class="reset '.( is_home() ? 'current-cat' : '' ).'"><a class="all" data-rel="*" href="'.get_permalink( get_option( 'page_for_posts' ) ).'">'. esc_html($translate['all']) .'</a></li>';

								if ($tags = get_tags()) {
									foreach ($tags as $tag) {
										$output .= '<li class="'. esc_attr($tag->slug) .' '.( is_tag($tag->slug) ? 'current-cat' : '' ).'"><a data-rel=".tag-'. esc_attr($tag->slug) .'" href="'. esc_url(get_tag_link($tag)) .'">'. esc_html($tag->name) .'</a></li>';
									}
								}

								$output .= '<li class="close"><a href="#" aria-label="'. __('icon close', 'betheme') .'"><i class="icon-cancel"></i></a></li>';

							$output .= '</ul>';

							// authors

							$output .= '<ul class="authors">';

								$output .= '<li class="reset '.( is_home() ? 'current-cat' : '' ).'"><a class="all" data-rel="*" href="'.get_permalink( get_option( 'page_for_posts' ) ).'">'. esc_html($translate['all']) .'</a></li>';
								$authors = mfn_get_authors();

								if (is_array($authors)) {
									foreach ($authors as $auth) {
										$output .= '<li class="'. esc_attr(mfn_slug($auth->data->user_login)) .' '.( is_author($auth->ID) ? 'current-cat' : '' ).'"><a data-rel=".author-'. mfn_slug($auth->data->user_login) .'" href="'. get_author_posts_url($auth->ID) .'">'. $auth->data->display_name .'</a></li>';
									}
								}
								$output .= '<li class="close"><a href="#" aria-label="'. __('icon close', 'betheme') .'"><i class="icon-cancel"></i></a></li>';

							$output .= '</ul>';

						$output .= '</div>';

					$output .= '</div>';
				$output .= '</div>'."\n";
			}

			// output | Main Content

			$output .= '<div class="blog_wrapper isotope_wrapper clearfix">';

				$output .= '<div class="posts_group lm_wrapper col-'. esc_attr($columns) .' '. esc_attr($classes) .'">';

					// blog query attributes

					$attr = array(
						'excerpt' => $excerpt,
						'featured_image' => false,
						'filters' => $filters,
						'title_tag' => $title_tag,
						'more' => $more,
					);

					if ($load_more) {
						$attr['featured_image'] = 'no_slider';	// no slider if load more
					}
					if ($images) {
						$attr['featured_image'] = 'image';	// images only option
					}

					$output .= mfn_content_post($query_blog, $style, $attr);

				$output .= '</div>';

				if ($pagination || $load_more) {
					wp_enqueue_script('mfn-imagesloaded', get_theme_file_uri('/js/plugins/imagesloaded.min.js'), ['jquery'], MFN_THEME_VERSION, true);
					$output .= mfn_pagination($query_blog, $load_more);
				}

			$output .= '</div>'."\n";

		$output .= '</div>'."\n";

		wp_reset_postdata();

		return $output;
	}
}

/**
 * Blog Slider [blog_slider]
 */

if (! function_exists('sc_blog_slider')) {
	function sc_blog_slider($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'				=> '',
			'title_tag'				=> '',
			'count'				=> 5,

			'category'		=> '',
			'category_multi'	=> '',
			'excerpt'		=> '',
			'orderby'			=> 'date',
			'order'				=> 'DESC',

			'one_post_per_slide' => '',
			'more'				=> '',
			'style'				=> '',
			'navigation'	=> '',
		), $attr));

		$title = be_dynamic_data($title);

		// translate

		$translate['readmore'] = mfn_opts_get('translate') ? mfn_opts_get('translate-readmore', 'Read more') : __('Read more', 'betheme');

		// classes

		$classes = '';
		if (! $more) {
			$classes .= ' hide-more';
		}
		if ($style) {
			$classes .= ' '. $style;
		}
		if ($navigation) {
			$classes .= ' '. $navigation;
		}
		if ( ! empty($one_post_per_slide) ) {
			$classes .= ' single_post_mode';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// query args

		$args = array(
			'posts_per_page'			=> intval($count, 10),
			'orderby' 						=> $orderby,
			'order'								=> $order,
			'no_found_rows'				=> 1,
			'post_status'					=> 'publish',
			'ignore_sticky_posts'				=> !empty($attr['sticky_posts']) ? true : false,
		);

		// private

		if (is_user_logged_in()) {
			$args['post_status'] = array( 'publish', 'private' );
		}

		// categories

		if ($category_multi) {
			$args['category_name'] = trim($category_multi);
		} elseif ($category) {
			$args['category_name'] = $category;
		}

		$query_blog = new WP_Query($args);

		// output -----

		$output = '<div class="blog_slider clearfix '. esc_attr($classes) .'" data-count="'. intval($query_blog->post_count, 10) .'">';

			$output .= '<div class="blog_slider_header clearfix">';
				if ($title) {
					$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
				}
				$output .= '<div class="slider_navigation"></div>';
			$output .= '</div>';

			$output .= '<ul class="blog_slider_ul">';

				while ($query_blog->have_posts()) {
					$query_blog->the_post();

					$output .= '<li class="'. implode(' ', get_post_class()) .'">';
						$output .= '<div class="item_wrapper">';

							if (get_post_format() == 'quote') {
								$output .= '<blockquote>';
									$output .= '<a href="'. esc_url(get_permalink()) .'">';
										$output .= wp_kses(get_the_title(), mfn_allowed_html());
									$output .= '</a>';
								$output .= '</blockquote>';
							} else {
								$output .= '<div class="image_frame scale-with-grid">';
									$output .= '<div class="image_wrapper">';
										$output .= '<a href="'. esc_url(get_permalink()) .'">';
											$output .= get_the_post_thumbnail(get_the_ID(), 'blog-portfolio', array( 'class' => 'scale-with-grid' ));
										$output .= '</a>';
									$output .= '</div>';
								$output .= '</div>';
							}

							$output .= '<div class="date_label">'. esc_html(get_the_date()) .'</div>';

							$output .= '<div class="desc">';
								if (get_post_format() != 'quote') {
									$output .= '<h4><a href="'. esc_url(get_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></h4>';
								}
								$output .= '<hr class="hr_color" />';

								if( !empty($excerpt) ){
									$output .= '<p class="post_excerpt">'.get_the_excerpt().'</p>';
								}

								$output .= '<a href="'. esc_url(get_permalink()) .'" class="button button_left has-icon"><span class="button_icon"><i class="icon-layout" aria-hidden="true"></i></span><span class="button_label">'. esc_html($translate['readmore']) .'</span></a>';
							$output .= '</div>';

						$output .= '</div>';
					$output .= '</li>';
				}

			$output .= '</ul>';

			$output .= '<div class="slider_pager slider_pagination"></div>';

		$output .= '</div>'."\n";

		wp_reset_postdata();

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-slick', get_theme_file_uri('/js/plugins/slick.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Blog News [blog_news]
 */

if (! function_exists('sc_blog_news')) {
	function sc_blog_news($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'				=> '',
			'title_tag'				=> '',
			'items_title_tag'	=> '',
			'featured_title_tag'	=> '',
			'count'				=> 5,
			'style'				=> '',

			'category'		=> '',
			'category_multi'	=> '',
			'orderby'			=> 'date',
			'order'				=> 'DESC',

			'excerpt'			=> '',
			'link'				=> '',
			'link_title'	=> '',
		), $attr));

		// query args

		$args = array(
			'posts_per_page'	=> intval($count, 10),
			'orderby' 				=> $orderby,
			'order'						=> $order,
			'no_found_rows'		=> 1,
			'post_status'			=> 'publish',
			'ignore_sticky_posts'				=> !empty($attr['sticky_posts']) ? true : false,
		);

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$link_title = be_dynamic_data($link_title);

		// private

		if (is_user_logged_in()) {
			$args['post_status'] = array( 'publish', 'private' );
		}

		// categories

		if ($category_multi) {
			$args['category_name'] = trim($category_multi);
		} elseif ($category) {
			$args['category_name'] = $category;
		}

		// featured first

		if ($style == 'featured') {
			$first = true;
		} else {
			$first = false;
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		$items_title_class = '';
		if( ! empty($items_title_tag) ){
			if( 'p.lead' == $items_title_tag ){
				$items_title_tag = 'p';
				$items_title_class = 'lead';
			}
		}

		$featured_title_class = '';
		if( ! empty($featured_title_tag) ){
			if( 'p.lead' == $featured_title_tag ){
				$featured_title_tag = 'p';
				$featured_title_class = 'lead';
			}
		}

		$query_blog = new WP_Query($args);

		// output -----

		$output = '<div class="Latest_news '. esc_attr($style) .'">';

			if ($title) {
				$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h3';
				$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. $title .'</'. mfn_allowed_title_tag($title_tag) .'>';
			}

			$items_title_tag = !empty( $attr['items_title_tag'] ) ? $attr['items_title_tag'] : 'h5';
			$featured_title_tag = !empty( $attr['featured_title_tag'] ) ? $attr['featured_title_tag'] : 'h4';

			$output .= '<ul class="ul-first">';

				while ($query_blog->have_posts()) {
					$query_blog->the_post();

					$output .= '<li class="'. esc_attr(implode(' ', get_post_class())) .'">';

						$output .= '<div class="photo">';
							$output .= '<a href="'. esc_url(get_permalink()) .'">';
								$output .= get_the_post_thumbnail(get_the_ID(), 'blog-portfolio', array( 'class' => 'scale-with-grid' ));
							$output .= '</a>';
						$output .= '</div>';

						$output .= '<div class="desc">';

							if ($first) {
								$output .= '<'. mfn_allowed_title_tag($featured_title_tag) .' class="'. esc_attr($featured_title_class) .'"><a href="'. esc_url(get_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></'. mfn_allowed_title_tag($featured_title_tag) .'>';
							} else {
								$output .= '<'. mfn_allowed_title_tag($items_title_tag) .' class="'. esc_attr($items_title_class) .'"><a href="'. esc_url(get_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></'. mfn_allowed_title_tag($items_title_tag) .'>';
							}

							$output .= '<div class="desc_footer">';
								$output .= '<span class="date"><i class="icon-clock"></i> '. esc_html(get_the_date()) .'</span>';
								if (comments_open()) {
									$output .= '<i class="icon-comment-empty-fa"></i> <a href="'. esc_url(get_comments_link()) .'" class="post-comments">'. intval(get_comments_number(), 10) .'</a>';
								}
								$output .= '<div class="button-love">'. mfn_love() .'</div>';
							$output .= '</div>';

							if ($excerpt == '1' || ($first && $excerpt == 'featured')) {
								$output .= '<div class="post-excerpt">'. get_the_excerpt() .'</div>';
							}

						$output .= '</div>';

					$output .= '</li>';

					if ($first) {
						$output .= '</ul>';
						$output .= '<ul class="ul-second">';

						$first = false;
					}
				}

				wp_reset_postdata();

			$output .= '</ul>';

			if ($link) {
				$output .= '<a class="button has-icon" href="'. esc_url($link) .'"><span class="button_icon"><i class="icon-layout" aria-hidden="true"></i></span><span class="button_label">'. esc_html($link_title) .'</span></a>';
			}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Blog Teaser [blog_teaser]
 */

if (! function_exists('sc_blog_teaser')) {
	function sc_blog_teaser($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'					=> '',
			'title_tag'			=> 'h3',
			'heading_tag'		=> '',

			'category'			=> '',
			'category_multi'=> '',
			'orderby'				=> 'date',
			'order'					=> 'DESC',

			'margin'				=> '',
		), $attr));

		// query args

		$args = array(
			'posts_per_page'			=> 3,
			'orderby' 						=> $orderby,
			'order'								=> $order,
			'no_found_rows'				=> 1,
			'post_status'					=> 'publish',
			'ignore_sticky_posts'				=> !empty($attr['sticky_posts']) ? true : false,
		);

		$title = be_dynamic_data($title);

		// translate

		$translate['published'] = mfn_opts_get('translate') ? mfn_opts_get('translate-published', 'Published by') : __('Published by', 'betheme');
		$translate['at'] = mfn_opts_get('translate') ? mfn_opts_get('translate-at', 'at') : __('at', 'betheme');

		// class

		$class = '';
		if (! $margin) {
			$class .= 'margin-no';
		}

		// private

		if (is_user_logged_in()) {
			$args['post_status'] = array( 'publish', 'private' );
		}

		// categories

		if ($category_multi) {
			$args['category_name'] = trim($category_multi);
		} elseif ($category) {
			$args['category_name'] = $category;
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		$heading_class = '';
		if( ! empty($heading_tag) ){
			if( 'p.lead' == $heading_tag ){
				$heading_tag = 'p';
				$heading_class = 'lead';
			}
		}

		$query_blog = new WP_Query($args);

		// output -----

		$output = '<div class="blog-teaser '. esc_attr($class) .'">';

			if ($title) {
				$heading_tag = !empty( $attr['heading_tag'] ) ? $attr['heading_tag'] : 'h4';
				$output .= '<'. mfn_allowed_title_tag($heading_tag) .' class="title '. esc_attr($heading_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($heading_tag) .'>';
			}

			$output .= '<ul class="teaser-wrapper">';

				$first = true;

				while ($query_blog->have_posts()) {
					$query_blog->the_post();

					$output .= '<li class="'. esc_attr(implode(' ', get_post_class())) .'">';

						$output .= '<div class="photo-wrapper scale-with-grid">';
							$output .= get_the_post_thumbnail(get_the_ID(), 'blog-portfolio', array( 'class' => 'scale-with-grid' ));
						$output .= '</div>';

						$output .= '<div class="desc-wrapper">';
							$output .= '<div class="desc">';

								$blog_meta = mfn_opts_get('blog-meta');

								$output .= '<div class="post-meta clearfix">';

									if( !empty($blog_meta['author']) ){
										$output .= '<span class="author">';
											$output .= '<span class="label">'. esc_html($translate['published']) .' </span>';
											$output .= '<i class="icon-user" aria-hidden="true"></i> ';
											$output .= '<a href="'. esc_url(get_author_posts_url(get_the_author_meta('ID'))) .'">'. esc_html(get_the_author_meta('display_name')) .'</a>';
										$output .= '</span> ';
									}

									if( !empty($blog_meta['date']) ){
										$output .= '<span class="date">';
											if( !empty($blog_meta['author']) ){
												$output .= '<span class="label">'. esc_html($translate['at']) .' </span>';
											}
											$output .= '<i class="icon-clock"></i> ';
											$output .= '<span class="post-date">'. esc_html(get_the_date()) .'</span>';
										$output .= '</span>';
									}

									// .post-comments | Style == Masonry Tiles
									if (comments_open() && mfn_opts_get('blog-comments')) {
										$output .= '<span class="comments">';
											$output .= '<i class="icon-comment-empty-fa"></i> <a href="'. esc_url(get_comments_link()) .'" class="post-comments" aria-label="'. __('comments number', 'betheme') .'">'. esc_html(get_comments_number()) .'</a>';
										$output .= '</span>';
									}

								$output .= '</div>';

								$output .= '<div class="post-title">';
									$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="'. esc_attr($title_class) .'"><a href="'. esc_url(get_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></'. mfn_allowed_title_tag($title_tag) .'>';
								$output .= '</div>';

							$output .= '</div>';
						$output .= '</div>';

					$output .= '</li>';

					if ($first) {
						$first = false;
					}
				}
				wp_reset_postdata();

			$output .= '</ul>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Shop Slider [shop_slider]
 */

if (! function_exists('sc_shop_slider')) {
	function sc_shop_slider($attr, $content = null)
	{
		if( !function_exists('is_woocommerce') ) return;

		extract(shortcode_atts(array(
			'title'			=> '',
			'title_tag'	=> '',
			'count'			=> 5,
			'show'			=> '',
			'category'	=> '',
			'out_of_stock' => '',
			'orderby' 	=> 'date',
			'order' 		=> 'DESC',
		), $attr));

		$title = be_dynamic_data($title);

		// query args

		$args = array(
			'post_type' 			=> 'product',
			'posts_per_page' 		=> intval($count, 10),
			'paged' 				=> -1,
			'orderby' 				=> $orderby,
			'order' 				=> $order,
			'ignore_sticky_posts'	=> 1,
		);

		// show

		if ($show == 'featured') {

			// featured ------------------------------
			$args['post__in'] =  array_merge(array(0), wc_get_featured_product_ids());

		} elseif ($show == 'onsale') {

			// onsale --------------------------------
			$args['post__in'] =  array_merge(array(0), wc_get_product_ids_on_sale());

		} elseif ($show == 'best-selling') {

			// best-selling --------------------------
			$args['meta_key'] = 'total_sales';
			$args['orderby'] 	= 'meta_value_num';

		}

		// axclude out of stock products

		if( !empty($out_of_stock) && 'hide' == $out_of_stock ){
			$args['meta_query'] = [
				[
					'key' => '_stock_status',
	        'value' => 'instock',
				],
			];
		}

		// category

		if ($category && !empty($category) && $category != 'All') {
			$args['product_cat'] = $category;
		}

		$query_shop = new WP_Query();
		$query_shop->query($args);

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="shop_slider" data-count="'. esc_attr($query_shop->post_count) .'">';

			$output .= '<div class="blog_slider_header clearfix">';
				if ($title) {
					$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
				}
				$output .= '<div class="slider_navigation"></div>';
			$output .= '</div>';

			$output .= '<ul class="shop_slider_ul">';
				while ($query_shop->have_posts()) {
					$query_shop->the_post();
					global $product;
					//setup_postdata($product);
					$output .= '<li class="'. esc_attr(implode(' ', get_post_class())) .'">';
						$output .= '<div class="item_wrapper">';

							if (mfn_opts_get('shop-images') == 'secondary') {
								$output .= '<div class="hover_box hover_box_product">';
								ob_start();
								wc_get_template( 'single-product/sale-flash.php');
								do_action( 'mfn_product_image' );
								$output .= ob_get_clean();
									$output .= '<a href="'. esc_url(get_the_permalink()) .'">';
										$output .= '<div class="hover_box_wrapper">';

											$output .= get_the_post_thumbnail(null, 'woocommerce_thumbnail', array('class'=>'visible_photo scale-with-grid' ));

											if ($attachment_ids = $product->get_gallery_image_ids()) {
												$secondary_image_id = $attachment_ids['0'];
												$output .= wp_get_attachment_image($secondary_image_id, 'woocommerce_thumbnail', '', [ 'class' => 'hidden_photo scale-with-grid' ] );
											}

										$output .= '</div>';
									$output .= '</a>';

								$output .= '</div>';
							} else {
								$output .= '<div class="image_frame scale-with-grid product-loop-thumb">';

									$output .= '<div class="image_wrapper">';

									ob_start();
									wc_get_template( 'single-product/sale-flash.php');
									do_action( 'mfn_product_image' );
									$output .= ob_get_clean();

										$output .= '<a href="'. esc_url(get_the_permalink()) .'">';
											$output .= '<div class="mask"></div>';
											$output .= get_the_post_thumbnail(null, 'woocommerce_thumbnail', array( 'class' => 'scale-with-grid' ));
										$output .= '</a>';

										$output .= '<div class="image_links">';
											$output .= '<a class="link" href="'. esc_url(get_the_permalink()) .'" aria-label="'. __('view product', 'betheme') .'"><svg viewBox="0 0 26 26"><defs><style>.path{fill:none;stroke:#333;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><path d="M10.17,8.76l2.12-2.12a5,5,0,0,1,7.07,0h0a5,5,0,0,1,0,7.07l-2.12,2.12" class="path"></path><path d="M15.83,17.24l-2.12,2.12a5,5,0,0,1-7.07,0h0a5,5,0,0,1,0-7.07l2.12-2.12" class="path"></path><line x1="10.17" y1="15.83" x2="15.83" y2="10.17" class="path"></line></g></svg></a>';
										$output .= '</div>';

									$output .= '</div>';

								$output .= '</div>';
							}

							$output .= '<div class="desc">';

								$output .= '<h4><a href="'. esc_url(get_the_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></h4>';

								if ($price_html = $product->get_price_html()) {
									$output .= '<span class="price">'. $price_html .'</span>';
								}

								if( !empty( $attr['add_to_cart_button'] ) ){

									$classes = '';

									$product->is_purchasable() ? $classes .= 'add_to_cart_button' : null;
									$product->supports( 'ajax_add_to_cart' ) ? $classes .= ' ajax_add_to_cart' : null;

									$output .= '<div class="mfn-li-product-row mfn-li-product-row-button button-'. ( !empty($attr['button']) ? $attr['button'] : 'unset') .'">';

									$output .= apply_filters(
										'woocommerce_loop_add_to_cart_link',
										sprintf(
											'<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s">%s</a>',
											esc_url(  $product->add_to_cart_url() ),
											esc_attr( $product->get_id() ),
											esc_attr( $product->get_sku() ),
											$classes,
											esc_attr( $product->get_type() ),
											esc_html( $product->add_to_cart_text() )
								        ),
								        $product
									);


								}

							$output .= '</div>';

						$output .= '</div>';

					$output .= '</li>';
				}

			$output .= '</ul>';

			$output .= '<div class="slider_pager slider_pagination"></div>';

		$output .= '</div>'."\n";

		wp_reset_postdata();

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-slick', get_theme_file_uri('/js/plugins/slick.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Contact Box [contact_box]
 */

if (! function_exists('sc_contact_box')) {
	function sc_contact_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'				=> '',
			'title_tag'		=> '',
			'address' 		=> '',
			'telephone'		=> '',
			'telephone_2'	=> '',
			'fax'					=> '',
			'email' 			=> '',
			'www' 				=> '',
			'image' 			=> '',
		), $attr));

		// image | visual composer fix

		$image = mfn_vc_image($image);

		$title = be_dynamic_data($title);
		$address = be_dynamic_data($address);

		// background

		if ($image) {
			$background_escaped = 'style="background-image:url('. esc_url($image) .');"';
		} else {
			$background_escaped = false;
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '';

			// This variable has been safely escaped above in this function
			$output .= '<div class="get_in_touch" '. $background_escaped .'>';

				if ($title) {
					$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h3';
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
				}

				$output .= '<div class="get_in_touch_wrapper">';
					$output .= '<ul>';

						if ($address) {
							$output .= '<li class="address">';
								$output .= '<span class="icon"><i class="icon-location"></i></span>';
								$output .= '<span class="address_wrapper">'. wp_kses_post($address) .'</span>';
							$output .= '</li>';
						}

						if ($telephone) {
							$output .= '<li class="phone phone-1">';
								$output .= '<span class="icon"><i class="icon-phone"></i></span>';
								$output .= '<p><a href="tel:'. esc_attr(str_replace(' ', '', $telephone)) .'">'. esc_html($telephone) .'</a></p>';
							$output .= '</li>';
						}

						if ($telephone_2) {
							$output .= '<li class="phone phone-2">';
								$output .= '<span class="icon"><i class="icon-phone"></i></span>';
								$output .= '<p><a href="tel:'. esc_attr(str_replace(' ', '', $telephone_2)) .'">'. esc_html($telephone_2) .'</a></p>';
							$output .= '</li>';
						}

						if ($fax) {
							$output .= '<li class="phone fax">';
								$output .= '<span class="icon"><i class="icon-print"></i></span>';
								$output .= '<p><a href="fax:'. esc_attr(str_replace(' ', '', $fax)) .'">'. esc_html($fax) .'</a></p>';
							$output .= '</li>';
						}

						if ($email) {
							$output .= '<li class="mail">';
								$output .= '<span class="icon"><i class="icon-mail"></i></span>';
								$output .= '<p><a href="mailto:'. esc_attr($email) .'">'. esc_html($email) .'</a></p>';
							$output .= '</li>';
						}

						if ($www) {
							if (strpos($www, 'http') === 0) {
								$url = $www;
								$www = str_replace('http://', '', $www);
								$www = str_replace('https://', '', $www);
							} else {
								$url = 'http'. mfn_ssl() .'://'. $www;
							}

							$output .= '<li class="www">';
								$output .= '<span class="icon"><i class="icon-link"></i></span>';
								$output .= '<p><a target="_blank" href="'. esc_url($url) .'">'. esc_html($www) .'</a></p>';
							$output .= '</li>';
						}

					$output .= '</ul>';
				$output .= '</div>';

			$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Popup [popup][/popup]
 */

if (! function_exists('sc_popup')) {
	function sc_popup($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'			=> '',
			'padding'		=> '',
			'button' 		=> '',
			'uid'			 => 'popup-'. uniqid(),
		), $attr));

		// padding

		if ($padding) {
			$style_escaped = 'style="padding:'. intval($padding, 10) .'px;"';
		} else {
			$style_escaped = false;
		}

		// output -----

		$output = '';

		if ($button) {
			$output .= '<a href="#'. esc_attr($uid) .'" rel="lightbox" data-lightbox-type="inline" class="popup-link button"><span class="button_label">'. wp_kses($title, mfn_allowed_html()) .'</span></a>';
		} else {
			$output .= '<a href="#'. esc_attr($uid) .'" rel="lightbox" data-lightbox-type="inline" class="popup-link">'. wp_kses($title, mfn_allowed_html()) .'</a>';
		}

		$output .= '<div id="'. esc_attr($uid) .'" class="popup-content">';

			// This variable has been safely escaped above in this function
			$output .= '<div class="popup-inner" '. $style_escaped .'>'. do_shortcode($content ?? '') .'</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Info Box [info_box]
 */

if (! function_exists('sc_info_box')) {
	function sc_info_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'			=> '',
			'title_tag'			=> '',
			'tabs'			=> '',
			'image' 		=> '',
		), $attr));

		$title = be_dynamic_data($title);
		$content = be_dynamic_data($content);

		// image | visual composer fix

		$image = mfn_vc_image($image);

		// background

		if ($image) {
			$background_escaped = 'style="background-image:url('. esc_url($image) .');"';
		} else {
			$background_escaped = false;
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '';

			// This variable has been safely escaped above in this function
			$output .= '<div class="infobox" '. $background_escaped .'>';

				if ($title) {
					$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h3';
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
				}

				$output .= '<div class="infobox_wrapper">';

					// Elementor repeater item

					if( is_array( $tabs ) ){
						$output .= '<ul>';
							foreach( $tabs as $tab ){
								if( ! empty( $tab['content'] ) ){
									$output .= '<li>'. nl2br($tab['content']) .'</li>';
								}
							}
						$output .= '</ul>';
					}

					if( !empty($content) ){
						$output .= '<span class="ib-desc">' . do_shortcode($content ?? '') . '</span>';
					}

				$output .= '</div>';

			$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Opening hours [opening_hours]
 */

if (! function_exists('sc_opening_hours')) {
	function sc_opening_hours($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'			=> '',
			'title_tag'			=> '',
			'tabs'			=> '',
			'image' 		=> '',
		), $attr));

		$title = be_dynamic_data($title);
		$content = be_dynamic_data($content);

		// image | visual composer fix

		$image = mfn_vc_image($image);

		// background

		if ($image) {
			$background_escaped = 'style="background-image:url('. esc_url($image) .');"';
		} else {
			$background_escaped = false;
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '';

			// This variable has been safely escaped above in this function
			$output .= '<div class="opening_hours" '. $background_escaped .'>';

				if ($title) {
					$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
				}

				$output .= '<div class="opening_hours_wrapper">';

					$output .= '<span class="ohw-desc">'.do_shortcode($content ?? '').'</span>';

					if( is_array( $tabs ) ){
						$output .= '<ul>';
							foreach( $tabs as $tab ){
								$output .= '<li><label>'. $tab['days'] .'</label><span>'. $tab['hours'] .'</span></li>';
							}
						$output .= '</ul>';
					}

				$output .= '</div>';

			$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * List [list_2]
 */

if (! function_exists('sc_list_2')) {
	function sc_list_2($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'tabs' => '',
			'type' => '',
			'starting' => 0,

			'align' => '',
			'align_tablet' => '',
			'align_mobile' => '',

			'valign' => '',
			'valign_tablet' => '',
			'valign_mobile' => '',

			'icon' => '',
			'image' => '',
			'divider' => '',

		), $attr));

		// classes

 		$classes = ['mfn-list'];
 		$style = '';

		// type

 		if( ! empty( $type ) ){
 			$classes[] = 'mfn-list-'. $type;
 		}

		// starting

 		if( ! empty( $starting ) ){
 			$style = 'counter-set:my-sec-counter '. esc_attr($starting - 1);
 		}

		// divider

 		if( ! empty( $divider ) ){
 			$classes[] = 'mfn-list-divider';
 		}

		// align

		if( ! empty( $align ) ){
 			$classes[] = 'mfn-list-'. $align;
 		}
		if( ! empty( $align_tablet ) ){
 			$classes[] = 'mfn-list-tablet-'. $align_tablet;
 		}
		if( ! empty( $align_mobile ) ){
 			$classes[] = 'mfn-list-mobile-'. $align_mobile;
 		}

		// vertical align

		if( ! empty( $valign ) ){
 			$classes[] = 'mfn-list-'. $valign;
 		}
		if( ! empty( $valign_tablet ) ){
 			$classes[] = 'mfn-list-tablet-'. $valign_tablet;
 		}
		if( ! empty( $valign_mobile ) ){
 			$classes[] = 'mfn-list-mobile-'. $valign_mobile;
 		}

		$classes = implode(' ', $classes);

		if(	$style ){
			$style = 'style="'. $style .'"';
		}

		// output -----

		$output = '<ul class="'. esc_attr($classes).'">';

			if( ! empty($tabs) && is_array($tabs) ){

				foreach( $tabs as $tab ){

					// custom background

					$style_background = '';
					if( !empty($tab['background']) ){
						$style_background = 'style="background-color:'. $tab['background'] .'"';
					}

					// custom color

					$style_color = '';
					if( !empty($tab['color']) ){
						$style_color = 'style="color:'. $tab['color'] .'"';
					}

					$output .= '<li class="mfn-list-item" '. $style .'>';

						if( !empty($image) || !empty($icon) || !empty($tab['image']) || !empty($tab['icon']) || 'ordered' == $type ){
							$output .= '<span class="mfn-list-icon" '. $style_background .'>';

								if( 'ordered' == $type ){

									// do nothing

								} elseif( !empty($tab['image']) ){

									$output .= '<img src="'. esc_attr($tab['image']) .'" alt="" />';

								} elseif( !empty($tab['icon']) ){

									$output .= '<i class="'. esc_attr($tab['icon']) .'" aria-hidden="true" '. $style_color .'></i>';

								} elseif( !empty($image) ){

									$output .= '<img src="'. esc_attr($image) .'" alt="" />';

								} elseif( !empty($icon) ){

									$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true" '. $style_color .'></i>';

								}

							$output .= '</span>';
						}

						$output .= '<span class="mfn-list-desc">';
							if( ! empty($tab['content']) ){

								if( ! empty($tab['link']) ){
									$target = '';
									if( ! empty($tab['target']) ){
										$target = 'target="_blank"';
									}
									$output .= '<a href="'. esc_attr($tab['link']) .'" '. $target .'>'. do_shortcode($tab['content'] ?? '') .'</a>';
								} else {
									$output .= do_shortcode($tab['content'] ?? '');
								}

							}
						$output .= '</span>';

					$output .= '</li>';

					$style = '';
				}

			} else {

				$output .= '<p>Please add list items.</p>';

			}

		$output .= '</ul>'."\n";

		// style

		wp_enqueue_style('mfn-element-list-2', get_theme_file_uri('/css/elements/list-2.css'), null, MFN_THEME_VERSION);

		return $output;
	}
}

/**
 * Toggle [toggle]
 */

if (! function_exists('sc_toggle')) {
	function sc_toggle($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'tabs' => '',
			'tag' => 'h5',

			'type' => '',
			'starting' => 0,
			'divider' => '',
			'open_first' => '',
			'open_all' => '',
			'open_more' => '',

			'align' => '',
			'align_tablet' => '',
			'align_mobile' => '',

			'icon' => '',
			'active_icon' => '',
			'icon_animation' => '',

		), $attr));

		// classes

 		$classes = ['mfn-toggle'];
 		$style_li = '';

		// type

 		if( ! empty( $type ) ){
 			$classes[] = 'mfn-toggle-'. $type;
 		}

		// starting

 		if( ! empty( $starting ) ){
 			$style_li = 'counter-set:my-sec-counter '. esc_attr($starting - 1);
 		}

		// open

 		if( 'enable' == $open_first ){
 			$classes[] = 'mfn-toggle-open-first';
 		}
 		if( 'enable' == $open_all ){
 			$classes[] = 'mfn-toggle-open-all';
 		}
 		if( 'enable' == $open_more ){
 			$classes[] = 'mfn-toggle-open-more';
 		}

		// divider

 		if( 'enable' == $divider ){
 			$classes[] = 'mfn-toggle-divider';
 		}

		// align

		if( ! empty( $align ) ){
 			$classes[] = 'mfn-toggle-'. $align;
 		}
		if( ! empty( $align_tablet ) ){
 			$classes[] = 'mfn-toggle-tablet-'. $align_tablet;
 		}
		if( ! empty( $align_mobile ) ){
 			$classes[] = 'mfn-toggle-mobile-'. $align_mobile;
 		}

		// icon animation

		if( ! empty( $icon_animation ) ){
 			$classes[] = 'mfn-toggle-icon-'. $icon_animation;
 		}

		$classes = implode(' ', $classes);

		if(	$style_li ){
			$style_li = 'style="'. $style_li .'"';
		}

		// title tag

		$title_class = '';
		if( ! empty($tag) ){
			if( 'p.lead' == $tag ){
				$tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="'. esc_attr($classes).'">';

			if( ! empty($tabs) && is_array($tabs) ){

				$i = 1;
				$count = count($tabs);

				// class

				$active = '';
				$style = 'display:none';

				if( 'enable' == $open_first || 'enable' == $open_all ){
					$active = 'active';
					$style = '';
				}

				foreach( $tabs as $tab ){

					$tab['content'] = be_dynamic_data($tab['content']);

					// output

					$output .= '<div class="mfn-toggle-item '. esc_attr($active) .'" '. $style_li .'>';

		        $output .= '<div class="toggle-bar">';

							if( !empty($tab['image']) || !empty($tab['icon']) || 'ordered' == $type ){
								$output .= '<span class="toggle-bar-icon">';

									if( 'ordered' == $type ){

										// do nothing

									} elseif( !empty($tab['image']) ){

										$output .= '<img src="'. esc_attr($tab['image']) .'" alt="" />';

									} elseif( !empty($tab['icon']) ){

										$output .= '<i class="'. esc_attr($tab['icon']) .'" aria-hidden="true"></i>';

									}

								$output .= '</span>';
							}

		          $output .= '<'. mfn_allowed_title_tag($tag) .' class="toggle-heading '. esc_attr($title_class) .'">'. $tab['title'] .'</'. mfn_allowed_title_tag($tag) .'>';

		          $output .= '<a class="toggle-icon" tabindex="0">';
								if( !empty($icon) ){
									$output .= '<i aria-hidden="true" class="'. esc_attr($icon) .' plus"></i>';
								}
								if( !empty($active_icon) ){
									$output .= '<i aria-hidden="true" class="'. esc_attr($active_icon) .' minus"></i>';
								}
		          $output .= '</a>';

						$output .= '</div>';

		        $output .= '<div class="toggle-content" aria-expanded="false" style="'. esc_attr($style) .'">';
		          $output .= do_shortcode($tab['content'] ?? '');
		        $output .= '</div>';

		      $output .= '</div>';

					// divider

					if( (! empty( $divider ) && 'enable' == $divider) && $i < $count ){
						$output .= '<hr class="toggle-divider">';
					}

					// end: output

					if( 'enable' == $open_first && 'enable' != $open_all ){
						$active = '';
						$style = 'display:none';
					}

					$style_li = '';

					$i++;

				}
			}

		$output .= '</div>'."\n";

		// style

		wp_enqueue_style('mfn-element-toggle', get_theme_file_uri('/css/elements/toggle.css'), null, MFN_THEME_VERSION);

		return $output;
	}
}

/**
 * Divider [divider_2]
 */

if (! function_exists('sc_divider_2')) {
	function sc_divider_2($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'type' => '',
			'align' => '',
			'align_tablet' => '',
			'align_mobile' => '',
			'addon' => '',

			'label' => '',
			'image' => '',
			'icon' => '',
		), $attr));

		// classes

 		$classes = ['mfn-divider'];

		// addon

		if( $addon && ( $label || $image || $icon ) ){
			$classes[] = 'mfn-divider-advanced';
		}

		// type

 		if( $type ){

			$type_parent = explode( '-', $type );

			$classes[] = 'mfn-divider-'. $type_parent[0];
 			$classes[] = 'mfn-divider-'. $type;
 		}

		// align

		if( ! empty( $align ) ){
 			$classes[] = 'mfn-divider-'. $align;
 		}
		if( ! empty( $align_tablet ) ){
 			$classes[] = 'mfn-divider-tablet-'. $align_tablet;
 		}
		if( ! empty( $align_mobile ) ){
 			$classes[] = 'mfn-divider-mobile-'. $align_mobile;
 		}

		$classes = implode(' ', $classes);

		// output -----

		$output = '<div class="'. esc_attr($classes).'">';
			$output .= '<div class="mfn-divider-inner">';

				if( 'label' == $addon && $label ){

					$output .= '<span class="divider-addon divider-label">'. wp_kses( $label, mfn_allowed_html() ) .'</span>';

				} elseif( 'image' == $addon && $image ){

					$output .= '<span class="divider-addon divider-image">';
						if( strpos('#', $image) !== false ){
							$image_tag = mfn_get_attachment($src, 'large');
							$output .= $image_tag;
						}else{
							$output .= '<img src="'. esc_url($image) .'" alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
						}
					$output .= '</span>';

				} elseif( 'icon' == $addon && $icon ){

					$output .= '<span class="divider-addon divider-icon"><i class="'. esc_attr($icon) .'" aria-hidden="true"></i></span>';

				}

			$output .= '</div>';
		$output .= '</div>'."\n";

		// style

		wp_enqueue_style('mfn-element-divider-2', get_theme_file_uri('/css/elements/divider-2.css'), null, MFN_THEME_VERSION);

		return $output;
	}
}

/**
 * Divider Basic [divider]
 */

if (! function_exists('sc_divider')) {
	function sc_divider($attr, $content = null)
	{
		extract(shortcode_atts(array(
		'height'      => 0,
		'style'       => '',	// default, dots, zigzag
		'line'        => '',	// default, narrow, wide, 0 = no_line
		'color'  			=> '',
		'themecolor'  => '',
	), $attr));

		// classes

		$class = '';

		if ($themecolor) {
			$class .= ' hr_color';
			$color = false; // theme color overwrites
		}

		// color

		if( $color ){
			if( 'zigzag' == $style ){
				$color = 'color:'. $color;
			} else {
				$color = 'background-color:'. $color;
			}
		}



		switch ($style) {

			case 'dots':

			// This variable has been safely escaped above in this function
				$output = '<div class="hr_dots" style="margin:0 auto '. intval($height, 10) .'px"><span style="'. esc_attr($color) .'"></span><span style="'. esc_attr($color) .'"></span><span style="'. esc_attr($color) .'"></span></div>'."\n";
				break;

			case 'zigzag':

			// This variable has been safely escaped above in this function
				$output = '<div class="hr_zigzag" style="margin:0 auto '. intval($height, 10) .'px"><i class="icon-down-open" style="'. esc_attr($color) .'" aria-hidden="true"></i><i class="icon-down-open" style="'. esc_attr($color) .'" aria-hidden="true"></i><i class="icon-down-open" style="'. esc_attr($color) .'" aria-hidden="true"></i></div>'."\n";
				break;

			default:

				if ($line == 'narrow') {

			  // This variable has been safely escaped above in this function
					$output = '<hr class="hr_narrow '. esc_attr($class) .'" style="margin:0 auto '. intval($height, 10) .'px;'. esc_attr($color) .'"/>'."\n";

				} elseif ($line == 'wide') {

			  // This variable has been safely escaped above in this function
					$output = '<div class="hr_wide '. esc_attr($class) .'" style="margin:0 auto '. intval($height, 10) .'px"><hr style="'. esc_attr($color) .'"/></div>'."\n";

				} elseif ($line) {

			  // This variable has been safely escaped above in this function
					$output = '<hr class="'. esc_attr($class) .'" style="margin:0 auto '. intval($height, 10) .'px;'. esc_attr($color) .'"/>'."\n";

				} else {
			  // This variable has been safely escaped above in this function
					$output = '<hr class="no_line" style="margin: 0 auto '. intval($height, 10) .'px auto"/>'."\n";

				}

		}

		return $output;
	}
}

/**
 * Fancy Divider [fancy_divider]
 */

if (! function_exists('sc_fancy_divider')) {
	function sc_fancy_divider($attr, $content = null)
	{
		extract(shortcode_atts(array(
		'style' 		    => 'stamp',
		'color_top'     => '',
		'color_bottom' 	=> '',
	), $attr));

		// output -----

		$output = '<div class="fancy-divider">';

		switch ($style) {

			case 'circle up':

				$output .= '<svg preserveAspectRatio="none" viewBox="0 0 100 100" height="100" width="100%" version="1.1" xmlns="https://www.w3.org/2000/svg" style="background: '. esc_attr($color_top) .';" aria-hidden="true">';
					$output .= '<path d="M0 100 C50 0 50 0 100 100 Z" style="fill: '. esc_attr($color_bottom) .'; stroke: '. esc_attr($color_bottom) .';"/>';
				$output .= '</svg>';
				break;

			case 'circle down':

				$output .= '<svg preserveAspectRatio="none" viewBox="0 0 100 100" height="100" width="100%" version="1.1" xmlns="https://www.w3.org/2000/svg" style="background: '. esc_attr($color_bottom) .';" aria-hidden="true">';
					$output .= '<path d="M0 0 C50 100 50 100 100 0 Z" style="fill: '. esc_attr($color_top) .'; stroke: '. esc_attr($color_top) .';"/>';
				$output .= '</svg>';
				break;

			case 'curve up':

				$output .= '<svg preserveAspectRatio="none" viewBox="0 0 100 100" height="100" width="100%" version="1.1" xmlns="https://www.w3.org/2000/svg" style="background: '. esc_attr($color_top) .';" aria-hidden="true">';
					$output .= '<path d="M0 100 C 20 0 50 0 100 100 Z" style="fill: '. esc_attr($color_bottom) .'; stroke: '. esc_attr($color_bottom) .';"/>';
				$output .= '</svg>';
				break;

			case 'curve down':

				$output .= '<svg preserveAspectRatio="none" viewBox="0 0 100 100" height="100" width="100%" version="1.1" xmlns="https://www.w3.org/2000/svg" style="background: '. esc_attr($color_bottom) .';" aria-hidden="true">';
					$output .= '<path d="M0 0 C 50 100 80 100 100 0 Z" style="fill: '. esc_attr($color_top) .'; stroke: '. esc_attr($color_top) .';"/>';
				$output .= '</svg>';
				break;

			case 'triangle up':

				$output .= '<svg preserveAspectRatio="none" viewBox="0 0 100 100" height="100" width="100%" version="1.1" xmlns="https://www.w3.org/2000/svg" style="background: '. esc_attr($color_top) .';" aria-hidden="true">';
					$output .= '<path d="M0 100 L50 2 L100 100 Z" style="fill: '. esc_attr($color_bottom) .'; stroke: '. esc_attr($color_bottom) .';"/>';
				$output .= '</svg>';
				break;

			case 'triangle down':

				$output .= '<svg preserveAspectRatio="none" viewBox="0 0 100 100" height="100" width="100%" version="1.1" xmlns="https://www.w3.org/2000/svg" style="background: '. esc_attr($color_bottom) .';" aria-hidden="true">';
					$output .= '<path d="M0 0 L50 100 L100 0 Z" style="fill: '. esc_attr($color_top) .'; stroke: '. esc_attr($color_top) .';"/>';
				$output .= '</svg>';
				break;

			default:

				$output .= '<svg preserveAspectRatio="none" viewBox="0 0 100 102" height="100" width="100%" version="1.1" xmlns="https://www.w3.org/2000/svg" style="background: '. esc_attr($color_bottom) .';" aria-hidden="true">';
					$output .= '<path d="M0 0 Q 2.5 40 5 0 Q 7.5 40 10 0Q 12.5 40 15 0Q 17.5 40 20 0Q 22.5 40 25 0Q 27.5 40 30 0Q 32.5 40 35 0Q 37.5 40 40 0Q 42.5 40 45 0Q 47.5 40 50 0 Q 52.5 40 55 0Q 57.5 40 60 0Q 62.5 40 65 0Q 67.5 40 70 0Q 72.5 40 75 0Q 77.5 40 80 0Q 82.5 40 85 0Q 87.5 40 90 0Q 92.5 40 95 0Q 97.5 40 100 0 Z" style="fill: '. esc_attr($color_top) .'; stroke: '. esc_attr($color_top) .';"/>';
				$output .= '</svg>';

		}

		$output .= '</div>';

		return $output;
	}
}

/**
 * Google Font [google_font]
 */

if (! function_exists('sc_google_font')) {
	function sc_google_font($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'font' 				=> '',

			'size' 				=> '25',
			'weight'			=> '400',

			'italic'			=> '',
			'letter_spacing' 	=> '',
			'subset' 			=> '',

			'color'				=> '',
			'inline' 			=> '',
		), $attr));

		// style

		$style_escaped = array();
		$style_escaped[] = "font-family:'". esc_attr($font) ."',Arial,Tahoma,sans-serif;";
		$style_escaped[] = "font-size:". intval($size, 10) ."px;";
		$style_escaped[] = "line-height:". intval($size, 10) ."px;";
		$style_escaped[] = "font-weight:". esc_attr($weight) .";";
		$style_escaped[] = "letter-spacing:". intval($letter_spacing, 10) ."px;";

		if ($color) {
			$style_escaped[] = "color:". esc_attr($color) .";";
		}

		// italic

		if ($italic) {
			$style_escaped[] = "font-style:italic;";
			$weight = $weight .'italic';
		}

		$style_escaped = implode('', $style_escaped);

		// subset

		if ($subset) {
			$subset	= '&amp;subset='. str_replace(' ', '', $subset);
		} else {
			$subset = false;
		}

		// class

		$class = '';
		if ($inline) {
			$class .= ' inline';
		}

		// enqueue_style

		if ( ! mfn_opts_get('google-font-mode') ) {
			$font_slug = str_replace(' ', '+', $font);
			wp_enqueue_style(esc_attr($font_slug), 'https://fonts.googleapis.com/css?family='. esc_attr($font_slug) .':'. esc_attr($weight) . esc_attr($subset));
		}

		// output -----

		// This variable has been safely escaped above in this function
		$output = '<div class="google_font'. esc_attr($class).'" style="'. $style_escaped .'">';
			$output .= do_shortcode($content ?? '');
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Sidebar Widget [sidebar_widget]
 */

if (! function_exists('sc_sidebar_widget')) {
	function sc_sidebar_widget($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'sidebar'  => 0,
		), $attr));

		// output -----

		$output = '';

		if (($sidebar !== '') && ($sidebar !== false)) {
			$sidebars = mfn_opts_get('sidebars');

			if (is_array($sidebars)) {
				$sidebar = $sidebars[ esc_attr($sidebar) ];

				ob_start();
				dynamic_sidebar($sidebar);
				$output = ob_get_clean();
			}
		}

		return $output;
	}
}

/**
 * Pricing Item [pricing_item] [/pricing_item]
 */

if (! function_exists('sc_pricing_item')) {
	function sc_pricing_item($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image'			   => '',
			'title'			   => '',
			'title_tag'			   => '',
			'currency'     => '',
			'currency_pos' => '',
			'price'        => '',
			'period'       => '',
			'subtitle'     => '',
			'link_title'   => '',
			'link'         => '',
			'link_title'         => '',
			'target'       => '',
			'icon'         => '',
			'featured'     => '',
			'style'        => 'box',
			'tabs'      => '',
		), $attr));

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$link_title = be_dynamic_data($link_title);

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		$button_class = '';

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$button_class .= ' open-mfn-popup';
			$target_escaped = ' data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		// classes

		$classes = '';

		if ($currency_pos) {
			$classes .= ' cp-'. $currency_pos;
		}
		if ($featured) {
			$classes .= ' pricing-box-featured';
		}
		if ($style) {
			$classes .= ' pricing-box-'. $style;
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="pricing-box '. esc_attr($classes) .'">';

				// header

				$output .= '<div class="plan-header">';

					if ($image) {
						$output .= '<div class="image">';
							$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
						$output .= '</div>';
					}

					if ($title) {
						$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h2';
						$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
					}

					if ($price || ($price === '0')) {
						$output .= '<div class="price">';

							if ($currency_pos != 'right') {
								$output .= '<sup class="currency">'. esc_html($currency) .'</sup>';
							}

							$output .= '<span>'. esc_html($price) .'</span>';

							if ($currency_pos == 'right') {
								$output .= '<sup class="currency">'. esc_html($currency) .'</sup>';
							}

							$output .= '<sup class="period">'. esc_html($period) .'</sup>';

						$output .= '</div>';

						$output .= '<hr class="hr_color" />';
					}

					if ($subtitle) {
						$output .= '<p class="subtitle"><big>'. wp_kses($subtitle, mfn_allowed_html()) .'</big></p>';
					}

				$output .= '</div>';

				// content

				if ( $content || $tabs ) {

					$output .= '<div class="plan-inside">';

						if( !empty($content) ){
							$output .= '<span class="pi-content">'.do_shortcode($content ?? '').'</span>';
						}

						if( is_array( $tabs ) ){
							$output .= '<ul>';
								foreach( $tabs as $tab ){
									$output .= '<li>';
										if( Mfn_Icons::is_icon($tab['title']) ){
											$output .= '<i class="'. esc_attr($tab['title']) .'" aria-hidden="true"></i>';
										} else {
											$output .= do_shortcode($tab['title'] ?? '');
										}
									$output .= '</li>';
								}
							$output .= '</ul>';
						}

					$output .= '</div>';

				}

				// link

				if ($link) {
					if ($icon) {
						$button_class = 'has-icon';
					}

					$output .= '<div class="plan-footer">';

						// This variable has been safely escaped above in this function
						$output .= '<a href="'. esc_url($link) .'" class="button button_theme '. esc_attr( $button_class ) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';

							if( $icon ) {
								$output .= '<span class="button_icon"><i class="'. esc_attr($icon) .'" aria-hidden="true"></i></span>';
							}

							$output .= '<span class="button_label">'. esc_html($link_title) .'</span>';

						$output .= '</a>';

					$output .= '</div>';
				}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Call to Action [call_to_action] [/call_to_action]
 */

if (! function_exists('sc_call_to_action')) {
	function sc_call_to_action($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' 		=> '',
			'title_tag' 		=> '',
			'icon' 			=> '',
			'link' 			=> '',
			'link_title' 			=> '',
			'button_title'	=> '',
			'class' 		=> '',
			'target' 		=> '',
		), $attr));

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$content = be_dynamic_data($content);
		$link_title = be_dynamic_data($link_title);
		$button_title = be_dynamic_data($button_title);

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// FIX | prettyphoto

		if (strpos($class, 'prettyphoto') !== false) {
			$class 	= str_replace('prettyphoto', '', $class);
			$rel_escaped 	= 'rel="prettyphoto"';
		} else {
			$rel_escaped 	= false;
		}

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$class .= ' open-mfn-popup';
			$rel_escaped = 'data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="call_to_action">';

				$output .= '<div class="call_to_action_wrapper">';

					$output .= '<div class="call_left">';
						$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h3';
						$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. $title_class .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
					$output .= '</div>';

					$output .= '<div class="call_center">';

						if ($button_title) {

							if ($link) {
								// This variable has been safely escaped above in this function
								$output .= '<a href="'. esc_url($link) .'" class="button has-icon '. esc_attr($class) .'" '. $rel_escaped .' '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
							}

							if ($icon) {
								if( is_array($icon) ){
									// svg
									$output .= '<span class="button_icon"><i class="'. esc_attr($icon) .'" aria-hidden="true"></i></span>';
								} else {
									// icon
									$output .= '<span class="button_icon"><i class="'. esc_attr($icon) .'" aria-hidden="true"></i></span>';
								}
							}

							$output .= '<span class="button_label">'. esc_html($button_title) .'</span>';

							if ($link) {
								$output .= '</a>';
							}

						} else {

							if ($link) {
								// This variable has been safely escaped above in this function
								$output .= '<a href="'. esc_url($link) .'" class="'. esc_attr($class) .'" '. $rel_escaped .' '. $target_escaped .'>';
							}

							if( is_array($icon) ){
								// svg
								$output .= '<span class="icon_wrapper"><img src="'. esc_attr($icon['url']) .'" width="30" alt="" /></span>';
							} else {
								// icon
								$output .= '<span class="icon_wrapper"><i class="'. esc_attr($icon) .'" aria-hidden="true"></i></span>';
							}

							if ($link) {
								$output .= '</a>';
							}
						}

					$output .= '</div>';

					$output .= '<div class="call_right">';
						$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';
					$output .= '</div>';

				$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Chart [chart]
 */

if (! function_exists('sc_chart')) {
	function sc_chart($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' => '',
			'title_tag' => '',
			'percent' => '',
			'label' => '',
			'icon' => '',
			'image' => '',
			'line_width' => '',
			'color' => '',
		), $attr));

		// image | visual composer fix

		$title = be_dynamic_data($title);

		$image = mfn_vc_image($image);

		// color

		if( ! $color ){
			$color = mfn_opts_get('color-counter', '#2991D6');
		}

		// line width

		if ($line_width) {
			$line_width_escaped = 'data-line-width="'. intval($line_width, 10) .'"';
		} else {
			$line_width_escaped = false;
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		$output = '<div class="chart_box">';

			// This variable has been safely escaped above in this function
			$output .= '<div class="chart" data-percent="'. intval($percent, 10) .'" data-bar-color="'. esc_attr($color) .'" '. $line_width_escaped .'>';

				if ($image) {
					$output .= '<div class="image"><img class="scale-with-grid" src="'. esc_url($image) .'" alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/></div>';
				} elseif ($icon) {
					$output .= '<div class="icon"><i class="'. esc_attr($icon) .'" aria-hidden="true"></i></div>';
				} else {
					$output .= '<div class="num">'. esc_html($label) .'</div>';
				}

			$output .= '</div>';

			$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'p';
			$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="'. esc_attr($title_class) .'"><span class="chart-desc">'. esc_html($title) .'</span></'. mfn_allowed_title_tag($title_tag) .'>';

		$output .= '</div>'."\n";

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-chart', get_theme_file_uri('/js/plugins/chart.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Countdown 2 [countdown_2]
 */

if (! function_exists('sc_countdown_2')) {
	function sc_countdown_2($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'date'				=> '12/30/2025 23:59:00', // +12 - add 12 days to current date, [year] also works
			'timezone'		=> '0',

			'show'				=> '',
			'show_laptop' => '',
			'show_tablet' => '',
			'show_mobile' => '',

			'align' => 'center', // left / center / right
			'separator' => '', // OFF / ON
			'separator_sign' => ':', // OFF / ON

			'mobile_orientation' => '', // Horizontal / Vertical
			'mobile_align' => '',
			'mobile_separator' => '',

			'order' => '' // drag & drop

		), $attr));

		// year as shortcode

		if( ! empty($date) ){
			$date = do_shortcode($date);
		}

		// add specified number of days, date starts with +

		if ( ! empty($date) && strpos($date, '+') === 0) {
			$dateFormat = 'm/d/Y H:i:s';
	    $daysToAdd = (int) substr($date, 1);

	    $newDate = new DateTime();
	    $newDate->modify("+$daysToAdd days");
			$newDate->setTime(23, 59, 0);

	    $date = $newDate->format($dateFormat);
		}

		// class

		$classes = ['downcount','mfn-countdown'];

		// separator sign

		if( empty( $separator_sign ) ){
			$separator_sign = ':';
		}

		// show

		if( ! empty( $show ) ){
 			$classes[] = 'mfn-countdown-show-'. $show;
 		}
		if( ! empty( $show_laptop ) ){
 			$classes[] = 'mfn-countdown-show-laptop-'. $show_laptop;
 		}
		if( ! empty( $show_tablet ) ){
 			$classes[] = 'mfn-countdown-show-tablet-'. $show_tablet;
 		}
		if( ! empty( $show_mobile ) ){
 			$classes[] = 'mfn-countdown-show-mobile-'. $show_mobile;
 		}

		// align

		if( ! empty( $align ) ){
 			$classes[] = 'mfn-countdown-'. $align;
 		}
		if( ! empty( $mobile_align ) ){
 			$classes[] = 'mfn-countdown-mobile-'. $mobile_align;
 		}

		// mobile orientation

		if( ! empty( $mobile_orientation ) ){
 			$classes[] = 'mfn-countdown-mobile-'. $mobile_orientation;
			$mobile_separator = 'off';
 		}

		// separator

		if( ! empty( $separator ) ){
 			$classes[] = 'mfn-countdown-separator-'. $separator;
 		}
		if( ! empty( $mobile_separator ) ){
 			$classes[] = 'mfn-countdown-separator-mobile-'. $mobile_separator;
 		}

		// order

		if( ! empty( $order ) ){
 			$classes[] = 'mfn-countdown-order-'. str_replace(',','-',$order);
 		}

		$classes = implode( ' ', $classes );

		// print_r($classes);

		// translate

		$is_translatable = mfn_opts_get('translate');

		$translate['days'] = $is_translatable ? mfn_opts_get('translate-days', 'days') : __('days', 'betheme');
		$translate['hours'] = $is_translatable ? mfn_opts_get('translate-hours', 'hours') : __('hours', 'betheme');
		$translate['minutes'] = $is_translatable ? mfn_opts_get('translate-minutes', 'minutes') : __('minutes', 'betheme');
		$translate['seconds']	= $is_translatable ? mfn_opts_get('translate-seconds', 'seconds') : __('seconds', 'betheme');

		// output -----

		$output = '<div class="'. esc_attr($classes) .'" data-date="'. esc_attr($date) .'" data-offset="'. esc_attr($timezone) .'">';

			$output .= '<div class="countdown-item">';
        $output .= '<div class="counter-number days">00</div>';
        $output .= '<p class="counter-title">'. esc_html($translate['days']) .'</p>';
      $output .= '</div>';

      $output .= '<div class="countdown-separator colon hide-hours">'. esc_attr($separator_sign) .'</div>';

      $output .= '<div class="countdown-item hide-hours">';
        $output .= '<div class="counter-number hours">00</div>';
        $output .= '<p class="counter-title">'. esc_html($translate['hours']) .'</p>';
      $output .= '</div>';

			$output .= '<div class="countdown-separator colon hide-minutes">'. esc_attr($separator_sign) .'</div>';

      $output .= '<div class="countdown-item hide-minutes">';
        $output .= '<div class="counter-number minutes">00</div>';
        $output .= '<p class="counter-title">'. esc_html($translate['minutes']) .'</p>';
      $output .= '</div>';

			$output .= '<div class="countdown-separator colon hide-seconds">'. esc_attr($separator_sign) .'</div>';

      $output .= '<div class="countdown-item hide-seconds">';
        $output .= '<div class="counter-number seconds">00</div>';
        $output .= '<p class="counter-title">'. esc_html($translate['seconds']) .'</p>';
      $output .= '</div>';

		$output .= '</div>'."\n";

		wp_enqueue_style('mfn-element-countdown-2', get_theme_file_uri('/css/elements/countdown-2.css'), null, MFN_THEME_VERSION);

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-countdown', get_theme_file_uri('/js/plugins/countdown.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Countdown [countdown]
 */

if (! function_exists('sc_countdown')) {
	function sc_countdown($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title_tag'			=> '',
			'date'				=> '12/30/2025 23:59:00', // +12 - add 12 days to current date
			'timezone'		=> '0',
			'show'				=> '',
			'number_size'	=> '',  // for inline shortcode only
		), $attr));

		// year as shortcode

		if( ! empty($date) ){
			$date = do_shortcode($date);
		}

		// add specified number of days, date starts with +

		if ( ! empty($date) && strpos($date, '+') === 0) {
			$dateFormat = 'm/d/Y H:i:s';
	    $daysToAdd = (int) substr($date, 1);

	    $newDate = new DateTime();
	    $newDate->modify("+$daysToAdd days");
			$newDate->setTime(23, 59, 0);

	    $date = $newDate->format($dateFormat);
		}

		// translate

		$is_translatable = mfn_opts_get('translate');

		$translate['days'] = $is_translatable ? mfn_opts_get('translate-days', 'days') : __('days', 'betheme');
		$translate['hours'] = $is_translatable ? mfn_opts_get('translate-hours', 'hours') : __('hours', 'betheme');
		$translate['minutes'] = $is_translatable ? mfn_opts_get('translate-minutes', 'minutes') : __('minutes', 'betheme');
		$translate['seconds']	= $is_translatable ? mfn_opts_get('translate-seconds', 'seconds') : __('seconds', 'betheme');

		// number of columns to show

		switch ($show) {
			case 'dhm':
				$hide = 1;
				$columns = 'one-third';
				break;
			case 'dh':
				$hide = 2;
				$columns = 'one-second';
				break;
			case 'd':
				$hide = 3;
				$columns = 'one';
				break;
			default:
				$hide = 0;
				$columns = 'one-fourth';
		}

		// number size

		if( ! empty($number_size) ){
			if( is_numeric($number_size) ){
				$number_size .= 'px';
			}
			$number_size = 'style="font-size:'. $number_size .'"';
		}

		$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h3';

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="downcount clearfix" data-date="'. esc_attr($date) .'" data-offset="'. esc_attr($timezone) .'">';

			$output .= '<div class="column column_quick_fact mobile-one '. esc_attr($columns) .'">';
				$output .= '<div class="mcb-column-inner">';
					$output .= '<div class="quick_fact">';
						$output .= '<div data-anim-type="zoomIn" class="animate zoomIn">';
							$output .= '<div class="number-wrapper">';
								$output .= '<div class="number days" '. $number_size .'>00</div>';
							$output .= '</div>';
							$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. esc_html($translate['days']) .'</'. mfn_allowed_title_tag($title_tag) .'>';
							$output .= '<hr class="hr_narrow">';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';

			if (3 > $hide) {
				$output .= '<div class="column column_quick_fact mobile-one '. esc_attr($columns) .'">';
					$output .= '<div class="mcb-column-inner">';
						$output .= '<div class="quick_fact">';
							$output .= '<div data-anim-type="zoomIn" class="animate zoomIn">';
								$output .= '<div class="number-wrapper">';
									$output .= '<div class="number hours" '. $number_size .'>00</div>';
								$output .= '</div>';
								$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. esc_html($translate['hours']) .'</'. mfn_allowed_title_tag($title_tag) .'>';
								$output .= '<hr class="hr_narrow">';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			}

			if (2 > $hide) {
				$output .= '<div class="column column_quick_fact mobile-one '. esc_attr($columns) .'">';
					$output .= '<div class="mcb-column-inner">';
						$output .= '<div class="quick_fact">';
							$output .= '<div data-anim-type="zoomIn" class="animate zoomIn">';
								$output .= '<div class="number-wrapper">';
									$output .= '<div class="number minutes" '. $number_size .'>00</div>';
								$output .= '</div>';
								$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. esc_html($translate['minutes']) .'</'. mfn_allowed_title_tag($title_tag) .'>';
								$output .= '<hr class="hr_narrow">';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			}

			if (1 > $hide) {
				$output .= '<div class="column column_quick_fact mobile-one '. esc_attr($columns) .'">';
					$output .= '<div class="mcb-column-inner">';
						$output .= '<div class="quick_fact">';
							$output .= '<div data-anim-type="zoomIn" class="animate zoomIn">';
								$output .= '<div class="number-wrapper">';
									$output .= '<div class="number seconds" '. $number_size .'>00</div>';
								$output .= '</div>';
								$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. esc_html($translate['seconds']) .'</'. mfn_allowed_title_tag($title_tag) .'>';
								$output .= '<hr class="hr_narrow">';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			}

		$output .= '</div>'."\n";

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-countdown', get_theme_file_uri('/js/plugins/countdown.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Countdown inline [countdown_inline]
 */

if (! function_exists('sc_countdown_inline')) {
	function sc_countdown_inline($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'date'				=> '12/30/2022 12:00:00',
			'timezone'		=> '0',
			'show'				=> '',
		), $attr));

		// translate

		$is_translatable = mfn_opts_get('translate');

		$translate['days'] = $is_translatable ? mfn_opts_get('translate-days', 'days') : __('days', 'betheme');
		$translate['hours'] = $is_translatable ? mfn_opts_get('translate-hours', 'hours') : __('hours', 'betheme');
		$translate['minutes'] = $is_translatable ? mfn_opts_get('translate-minutes', 'minutes') : __('minutes', 'betheme');
		$translate['seconds']	= $is_translatable ? mfn_opts_get('translate-seconds', 'seconds') : __('seconds', 'betheme');

		// output -----

		$output = '<span class="downcount downcount-inline show-'. esc_attr($show) .'" data-date="'. esc_attr($date) .'" data-offset="'. esc_attr($timezone) .'">';
			$output .= '<span class="number days">00</span>';
			$output .= '<span class="label label-days">'. esc_html($translate['days']) .'</span>';
			$output .= '<span class="number hours">00</span>';
			$output .= '<span class="label label-hours">'. esc_html($translate['hours']) .'</span>';
			$output .= '<span class="number minutes">00</span>';
			$output .= '<span class="label label-minutes">'. esc_html($translate['minutes']) .'</span>';
			$output .= '<span class="number seconds">00</span>';
			$output .= '<span class="label label-seconds">'. esc_html($translate['seconds']) .'</span>';
		$output .= '</span>';

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-countdown', get_theme_file_uri('/js/plugins/countdown.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Counter [counter]
 */

if (! function_exists('sc_counter')) {
	function sc_counter($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'icon' 			=> '',
			'color' 		=> '',
			'image' 		=> '',
			'number' 		=> '',
			'duration' 		=> '',
			'prefix' 		=> '',
			'label' 		=> '',
			'title' 		=> '',
			'title_tag' => '',
			'thousands_separator' => '',
			'type'	 		=> 'vertical',
		), $attr));

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);
		$title = be_dynamic_data($title);

		// color

		if ($color) {
			$style_escaped = 'style="color:'. esc_attr($color) .';"';
		} else {
			$style_escaped = false;
		}

		// animate math

		$animate_math = mfn_opts_get('math-animations-disable') ? false : 'animate-math';

		// duration

		if( ! empty($duration) ){
			$duration = 'data-duration="'. intval($duration, 10) .'"';
		} else {
			$duration = '';
		}

		// thousands_separator

		if( ! empty($thousands_separator) ){
			$thousands_separator = 'data-thousands-separator="'. esc_attr($thousands_separator) .'"';
		} else {
			$thousands_separator = '';
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="counter counter_'. esc_attr($type) .' '. esc_attr($animate_math) .'">';

			if( $image || $icon ){
				$output .= '<div class="icon_wrapper">';
					if ($image) {
						$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
					} elseif ($icon) {
						// This variable has been safely escaped above in this function
						$output .= '<i class="'. esc_attr($icon) .'" '. $style_escaped .' aria-hidden="true"></i>';
					}
				$output .= '</div>';
			}

				$output .= '<div class="desc_wrapper">';

					if ( $number || $number === '0' ) {
						$output .= '<div class="number-wrapper">';

							if ($prefix) {
								$output .= '<span class="label prefix">'. esc_html($prefix) .'</span>';
							}

							$output .= '<span class="number" data-to="'. intval($number, 10) .'" '. $duration .' '. $thousands_separator .'>'. intval($number, 10) .'</span>';

							if ($label) {
								$output .= '<span class="label postfix">'. esc_html($label) .'</span>';
							}

						$output .= '</div>';
					}

					if ($title) {
						$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'p';
						$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
					}

				$output .= '</div>';

		$output .= '</div>'."\n";

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-chart', get_theme_file_uri('/js/plugins/chart.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-countdown', get_theme_file_uri('/js/plugins/countdown.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Counter inline [counter_inline]
 */

if (! function_exists('sc_counter_inline')) {
	function sc_counter_inline($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'value' => '',
			'duration' => '',
		), $attr));

		if( ! $value ){
			return false;
		}

		if( ! empty($duration) ){
			$duration = 'data-duration="'. intval($duration, 10) .'"';
		} else {
			$duration = '';
		}

		$output = '<span class="counter-inline animate-math"><span class="number" data-to="'. intval($value, 10) .'" '. $duration .'>'. intval($value, 10) .'</span></span>';

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-countdown', get_theme_file_uri('/js/plugins/countdown.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Icon [icon]
 */

if (! function_exists('sc_icon')) {
	function sc_icon($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'type' => '',
			'color' => '',
		), $attr));

		$class = $type;

		if( 'themecolor' == $color ){
			$class .= ' themecolor';
			$color = false;
		} elseif( $color ){
			$color = 'color:'. $color;
		}

		$output = '<i class="'. esc_attr($class) .'" style="'. esc_attr($color) .'" aria-hidden="true"></i>';

		return $output;
	}
}

/**
 * Icon Block [icon_block]
 */

if (! function_exists('sc_icon_block')) {
	function sc_icon_block($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'icon'	=> '',
			'align'	=> '',
			'color'	=> '',
			'size'	=> 25,
		), $attr));

		// classes

		$class = '';

		if ($align) {
			$class .= ' icon_'. $align;
		}

		if ($color) {
			$color = 'color:'. $color .';';
		} else {
			$class .= ' themecolor';
		}

		// output -----

		$output = '<span class="single_icon '. esc_attr($class) .'">';
			$output .= '<i style="font-size:'. intval($size, 10) .'px;line-height:'. intval($size, 10) .'px;'. esc_attr($color) .'" class="'. esc_attr($icon) .'"></i>';
		$output .= '</span>'."\n";

		return $output;
	}
}

/**
 * Image [image]
 */

if (! function_exists('sc_image')) {
	function sc_image($attr, $content = null) {

		extract(shortcode_atts(array(

			'src'      => '',
			'size'     => '',
			'lazy_load' => '',

			// options
			'align'    => 'none',
			'stretch'  => '',
			'border'   => '',
			'margin'   => '',
			'margin_top'     => '',	// alias for: margin
			'margin_bottom'	 => '',

			// link
			'link_image' => '',
			'link'       => '',
			'link_title' => '',
			'target'     => '',
			'hover'      => '',
			'onclick'    => '',

			// description
			'alt'      => '',
			'caption'  => '',

			// advanced
			'greyscale'  => '',
			'classes'    => '',

			// mask
			'mask_shape_type' => '',
			'mask_shape_size' => '',
			'mask_shape_position' => '',

			// deprecated
			'width' => '',
			'height' => '',

		), $attr));

		$link_classes = '';
		$link_attr = '';

		// STYLE -----

		if ($margin_top) {
			// alias for: margin
			$margin = $margin_top;
		}

		if ($margin || $margin_bottom) {
			$margin_tmp = '';

			if ($margin) {
				$margin_tmp .= 'margin-top:'. intval($margin, 10) .'px;';
			}
			if ($margin_bottom) {
				$margin_tmp .= 'margin-bottom:'. intval($margin_bottom, 10) .'px;';
			}

			$style_escaped = 'style="'. esc_attr($margin_tmp) .'"';
		} else {
			$style_escaped = false;
		}

		// end: STYLE

		// TARGET

		if ($target) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// end: TARGET

		// ONCLICK

		if ( !empty($onclick) ) {
			$onclick_escaped = 'onclick="'. $onclick .'"';
		} else {
			$onclick_escaped = '';
		}

		// end: ONCLICK

		// DIV CLASS -----

		$class_div 	= '';

		// align

		if ($align) {
			$class_div .= ' align'. $align;
		}

		// stretch

		if ($stretch == 'ultrawide') {
			$class_div .= ' stretch-ultrawide';
		} elseif ($stretch) {
			$class_div .= ' stretch';
		}

		// border

		if ($border) {
			$class_div .= ' has_border';
		} else {
			$class_div .= ' no_border';
		}

		// greyscale

		if ($greyscale) {
			$class_div .= ' greyscale';
		}

		// hover

		if ($hover) {
			$class_div .= ' hover-disable';
		}

		// end: DIV CLASS

		// PRETTYPHOTO ---

		$link_all = $link;

		if ($link_all) {

			if( false !== strpos( $classes, 'scroll' ) ){
				$rel_escaped = 'class="scroll"';
			}

			if( !empty($link) && !empty($attr['rel']) ) {
				$rel_escaped = 'rel="'.$attr['rel'].'"';
			}else{
				$rel_escaped = false;
			}

		} else {

			$link_all        = $link_image;
			$rel_escaped     = 'rel="prettyphoto"';
			$target_escaped  = false;

		}

		// end: PRETTYPHOTO ---

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			$link_image = false;
			if( empty($link_all) ) $link_all = '#';
			if( empty($link) ) $link = '#';
			$rel_escaped = false;

			$link_classes .= 'open-mfn-popup';
			$link_attr .= 'data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		// mask shape

		$img_style = '';

		$is_mask_shape_enabled = !empty($mask_shape_type) ? true : false;

		$class_div .= $is_mask_shape_enabled ? ' mfn-mask-shape' : '';
		$class_div .= $is_mask_shape_enabled && $mask_shape_type !== '0' ? ' '.$mask_shape_type : '';

		if ( $mask_shape_size !== 'custom' && $is_mask_shape_enabled) {
			$img_style .= '-webkit-mask-size:'. $mask_shape_size .';';
		}

		if( $mask_shape_position !== 'custom' && $is_mask_shape_enabled ){
			$img_style .= '-webkit-mask-position:'. $mask_shape_position .';';
		}

		// NEW url#id image link format

		$image_output = false;

		if( ! empty($src) && strpos($src, '#') !== false ){

			$attachment_attr = [ 'class' => 'scale-with-grid', 'style' => $img_style ];
			if( ! empty($alt) ){
				$attachment_attr['alt'] = $alt;
			}

			$image_output = mfn_get_attachment( $src, $size, $lazy_load, $attachment_attr );
			$width 	= mfn_get_attachment_data( $src, 'width' ); // FIX: svg images width

		} else {

			// dynamic data

			$src = be_dynamic_data($src);

			// mfn_get_attachment_id_url -----

			if( ! is_numeric( $src ) && empty( $width ) && empty( $height ) ){ // width & height are deprecated | just for backward compatibility
				$attachment_id = mfn_get_attachment_id_url( $src );
				if( $attachment_id ){
					$src = $attachment_id;
					$src = apply_filters( 'wpml_object_id', $src, 'attachment', true );
				}
			}

		}

		// IMAGE OUTPUT -----

		if( $image_output ){

			// do nothing, we already have output image

		} elseif( is_numeric( $src ) ){

			$attachment_attr = [ 'class' => 'scale-with-grid', 'style' => $img_style ];
			if( ! empty($alt) ){
				$attachment_attr['alt'] = $alt;
			}

			$image_output = mfn_get_attachment( $src, $size, $lazy_load, $attachment_attr );
			$width 	= mfn_get_attachment_data( $src, 'width' ); // FIX: svg images width

		} else {

			$class = 'scale-with-grid';

			// title, alt, width, height

			$title = mfn_get_attachment_data($src, 'title');

			if (! $alt) {
				$alt = mfn_get_attachment_data($src, 'alt');
			}

			if (! $width) {
				$width 	= mfn_get_attachment_data($src, 'width');
			}
			if (! $height) {
				$height = mfn_get_attachment_data($src, 'height');
			}

			// src output

			$src = 'src="'. esc_url($src) .'"';

			if( ! isset($attr['vb']) && mfn_is_lazy( $lazy_load ) ){
				$src = 'data-'. $src;
				$class .= ' mfn-lazy';
			}

			$image_output = '<img class="'. esc_attr($class) .'" '. $src .' alt="'. esc_attr($alt) .'" title="'. esc_attr($title) .'" width="'. esc_attr($width) .'" height="'. esc_attr($height) .'" style="'. $img_style .'"/>';

		}

		// svg

		if( ( ! $width ) && ( strpos( $image_output, '.svg' ) !== false ) ) {
			$class_div .= ' svg'; // FIX: SVG image without width
		}

		/*if( strpos( $image_output, '.svg' ) !== false ) {
			$class_div .= ' svg';
		}*/

		if( !empty($attr['image_height']) && $attr['image_height'] == 'custom' ){
			$class_div .= ' mfn-coverimg';
		}

		// OUTPUT -----

		$output = '';


		if ($link || $link_image) {

			if( !empty($attr['image_height']) && $attr['image_height'] == 'custom' ) {
				if( !empty($attr['image_height_style']) ){
					$link_classes .= ' mfn-'.$attr['image_height_style'].'img-wrapper';
				}else{
					$link_classes .= ' mfn-coverimg-wrapper';
				}

			}

			// This variable has been safely escaped above in this function
			$output .= '<div class="image_frame image_item scale-with-grid'. esc_attr($class_div) .'" '. $style_escaped .' role="link" aria-label="Image with links" tabindex="0">';

				$output .= '<div class="image_wrapper">';

					// This variable has been safely escaped above in this function

					$link_all = be_dynamic_data($link_all);
					if( is_numeric($link_all) ) $link_all = wp_get_attachment_image_url($link_all, 'full');

					$output .= '<a href="'. esc_url($link_all) .'" '. $rel_escaped .' '. $target_escaped .' '. $onclick_escaped .' class="'.$link_classes.'" '.$link_attr.' tabindex="-1" title="'. esc_attr($link_title ?? '') .'">';
						$output .= '<div class="mask"></div>';
						// This variable has been safely escaped above in this function
						$output .= $image_output;
					$output .= '</a>';

					$output .= '<div class="image_links">';
						if ($link_image) {

							$link_image = be_dynamic_data($link_image);
							if( is_numeric($link_image) ) $link_image = wp_get_attachment_image_url($link_image, 'full');

							$output .= '<a href="'. esc_url($link_image) .'" class="zoom" rel="prettyphoto" tabindex="-1" aria-label="'. __('zoom image', 'betheme') .'" title="'. esc_attr($link_title ?? '') .'"><svg viewBox="0 0 26 26"><defs><style>.path{fill:none;stroke:#333;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><circle cx="11.35" cy="11.35" r="6" class="path"/><line x1="15.59" y1="15.59" x2="20.65" y2="20.65" class="path"/></svg></a>';
						}
						if ($link) {
							// This variable has been safely escaped above in this function
							$output .= '<a href="'. esc_url(be_dynamic_data($link)) .'" class="link '.$link_classes.'" '.$link_attr.' '. $target_escaped .' '. $onclick_escaped .' tabindex="-1" title="'. esc_attr($link_title ?? '') .'"><svg viewBox="0 0 26 26"><defs><style>.path{fill:none;stroke:#333;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><path d="M10.17,8.76l2.12-2.12a5,5,0,0,1,7.07,0h0a5,5,0,0,1,0,7.07l-2.12,2.12" class="path"></path><path d="M15.83,17.24l-2.12,2.12a5,5,0,0,1-7.07,0h0a5,5,0,0,1,0-7.07l2.12-2.12" class="path"></path><line x1="10.17" y1="15.83" x2="15.83" y2="10.17" class="path"></line></g></svg></a>';
						}
					$output .= '</div>';

				$output .= '</div>';

				if ($caption) {
					$caption = be_dynamic_data($caption);
					$output .= '<p class="wp-caption-text">'. wp_kses($caption, mfn_allowed_html('caption')) .'</p>';
				}

			$output .= '</div>'."\n";

		} else {

			$coverimg = false;

			if( !empty($attr['image_height']) && $attr['image_height'] == 'custom' ) {
				if( !empty($attr['image_height_style']) ){
					$coverimg = 'mfn-'.$attr['image_height_style'].'img-wrapper';
				}else{
					$coverimg = 'mfn-coverimg-wrapper';
				}

			}

			// This variable has been safely escaped above in this function
			$output .= '<div class="image_frame image_item no_link scale-with-grid'. esc_attr($class_div) .'" '. $style_escaped .'>';

				$output .= '<div class="image_wrapper '.$coverimg.'">';
					// This variable has been safely escaped above in this function
					$output .= $image_output;
				$output .= '</div>';

				if ($caption) {
					$output .= '<p class="wp-caption-text">'. wp_kses($caption, mfn_allowed_html('caption')) .'</p>';
				}

			$output .= '</div>'."\n";
		}

		return $output;
	}
}

/**
 * Hover Box [hover_box]
 */

if (! function_exists('sc_hover_box')) {
	function sc_hover_box($attr, $content = null) {
		extract(shortcode_atts(array(
			'image'        => '',
			'image_hover'  => '',
			'link'         => '',
			'link_title'   => '',
			'target'       => '',
		), $attr));

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image_hover = be_dynamic_data($image_hover);
		if( is_numeric($image_hover) ) $image_hover = wp_get_attachment_image_url($image_hover, 'full');

		$image = mfn_vc_image($image);
		$image_hover = mfn_vc_image($image_hover);

		$link = be_dynamic_data($link);
		$link_title = be_dynamic_data($link_title);

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$target_escaped = 'class="open-mfn-popup"';
			$target_escaped .= ' data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		// image class

		$img_class = 'scale-with-grid visible_photo';
		$img_class2 = 'scale-with-grid hidden_photo';

		// src output

		$src = 'src="'. esc_url($image) .'"';
		$src2 = 'src="'. esc_url($image_hover) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$src2 = 'data-'. $src2;
			$img_class .= ' mfn-lazy';
			$img_class2 .= ' mfn-lazy';
		}

		// output -----

		$output = '<div class="hover_box">';

			if ($link) {
				// This variable has been safely escaped above in this function
				$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
			}

				$output .= '<div class="hover_box_wrapper">';
					$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
					$output .= '<img class="'. esc_attr($img_class2) .'" '. $src2 .' alt="'. esc_attr(mfn_get_attachment_data($image_hover, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image_hover, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image_hover, 'height')) .'"/>';
				$output .= '</div>';

			if ($link) {
				$output .= '</a>';
			}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Hover Color [hover_color]
 */

if (! function_exists('sc_hover_color')) {
	function sc_hover_color($attr, $content = null)
	{
		extract(shortcode_atts(array(

			'align'        => '',
			'background'   => '',
			'background_hover' => '',
			'border'       => '',
			'border_hover' => '',
			'border_width' => '',
			'padding'      => '',

			'link'         => '',
			'link_title'   => '',
			'class'        => '',
			'target'       => '',

			'style'        => '',

		), $attr));

		$link = be_dynamic_data($link);
		$link_title = be_dynamic_data($link_title);
		$content = be_dynamic_data($content);

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// padding

		if ($padding) {
			$padding = 'padding:'. $padding .';';
		}

		// border width

		if( is_numeric($border_width) ){
			$border_width .= 'px';
		}

		// FIX | prettyphoto

		if (strpos($class, 'prettyphoto') !== false) {
			$class = str_replace('prettyphoto', '', $class);
			$rel_escaped = 'rel="prettyphoto"';
		} else {
			$rel_escaped = false;
		}

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$rel_escaped = false;
			$class .= ' open-mfn-popup';
			$target_escaped .= ' data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		// output -----

		$output = '<div class="hover_color align_'. esc_attr($align) .'" style="background-color:'. esc_attr($background_hover) .';border-color:'. esc_attr($border_hover) .';'. esc_attr($style) .'">';
			$output .= '<div class="hover_color_bg" style="background-color:'. esc_attr($background) .';border-color:'. esc_attr($border) .';border-width:'. esc_attr($border_width) .';">';

				if ($link) {
					// This variable has been safely escaped above in this function
					$output .= '<a href="'. $link .'" class="'. esc_attr($class) .'" '. $rel_escaped .' '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
				}

					$output .= '<div class="hover_color_wrapper" style="'. esc_attr($padding) .'">';
						$output .= do_shortcode($content ?? '');
					$output .= '</div>';

				if ($link) {
					$output .= '</a>';
				}

			$output .= '</div>'."\n";
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Quick Fact [quick_fact]
 */

if (! function_exists('sc_quick_fact')) {
	function sc_quick_fact($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'heading' => '',
			'heading_tag' => 'h4',
			'title' => '',
			'title_tag' => 'h3',

			'number'   => '',
			'prefix' => '',
			'label' => '',

			'align' => 'center',
		), $attr));

		$title = be_dynamic_data($title);
		$heading = be_dynamic_data($heading);
		$content = be_dynamic_data($content);

		// animate math

		$animate_math = mfn_opts_get('math-animations-disable') ? false : 'animate-math';

		// title tag

		$heading_class = '';
		if( ! empty($heading_tag) ){
			if( 'p.lead' == $heading_tag ){
				$heading_tag = 'p';
				$heading_class = 'lead';
			}
		}

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="quick_fact align_'. esc_attr($align) .' '. esc_attr($animate_math) .'">';

				if ($heading) {
					$output .= '<'. mfn_allowed_title_tag( $heading_tag ) .' class="title heading_tag '. esc_attr($heading_class) .'">'. wp_kses($heading, mfn_allowed_html()) .'</'. mfn_allowed_title_tag( $heading_tag ) .'>';
				}

				if ( $number || $number === '0' ) {
					$output .= '<div class="number-wrapper">';

						if ($prefix) {
							$output .= '<span class="label prefix">'. esc_html($prefix) .'</span>';
						}

						$output .= '<span class="number" data-to="'. esc_attr($number) .'">'. esc_html($number) .'</span>';

						if ($label) {
							$output .= '<span class="label postfix">'. esc_html($label) .'</span>';
						}

					$output .= '</div>';
				}

				if ($title) {
					$output .= '<'. mfn_allowed_title_tag( $title_tag ) .' class="title title_tag '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag( $title_tag ) .'>';
				}

				$output .= '<hr class="hr_narrow" />';

				$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';

		$output .= '</div>'."\n";

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Button [button]
 */

if (! function_exists('sc_button')) {
	function sc_button($attr, $content = null)
	{
		extract(shortcode_atts(array(

			'title' => 'Button',
			'link' => '',
			'link_title' => '',
			'target' => '',
			'align' => '',

			'icon' => '',
			'icon_position' => 'left',

			'color' => '',
			'font_color' => '',

			'size' => 2,
			'full_width' => '',
			'button_style' => '',

			'class' => '',
			'button_id' => '',
			'rel' => '',
			'download' => '',
			'onclick' => '',

		), $attr));

		$is_add_to_cart = '';

		if( $link == '{permalink:add_to_cart}' && get_post_type(Mfn_Builder_Front::$item_id) == 'product' && get_option('woocommerce_enable_ajax_add_to_cart') == 'yes' ) {
			$product_tmp = wc_get_product( Mfn_Builder_Front::$item_id );

			if( $product_tmp->is_type( 'simple' ) ) {
				$class .= 'mfn-dynamic-data-add-to-cart add_to_cart_button ajax_add_to_cart product_type_'.$product_tmp->get_type();
				$is_add_to_cart = 'data-product_id="'.$product_tmp->get_ID().'"';
			}

		}

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);

		// target

		if( 'lightbox' === $target ) {
			$target_escaped = false;
			$rel = 'prettyphoto '. $rel; // do not change order
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// download

		if ($download) {
			$download_escaped = 'download="'. $download .'"';
		} else {
			$download_escaped = false;
		}

		// onclick

		if ($onclick) {
			$onclick_escaped = 'onclick="'. $onclick .'"';
		} else {
			$onclick_escaped = false;
		}

		// icon_position

		if ( empty($icon_position) ) {
			$icon_position = 'left';
		}

		// FIX | prettyphoto

		if ( strpos( $class, 'prettyphoto' ) !== false ) {
			$class = str_replace( 'prettyphoto', '', $class );
			$rel = 'prettyphoto '. $rel; // do not change order
		}

		// id

		if( ! empty($button_id) ){
			$button_id = 'id="'. esc_attr($button_id) .'"';
		} else {
			$button_id = '';
		}

		// class

		if ( $icon ) {
			$class .= ' button_'. $icon_position;
		}

		if ( $full_width ) {
			$class .= ' button_full_width';
		}

		if ( !empty($button_style) ) {
			$class .= ' '.$button_style;
		}

		if ($size) {
			$class .= ' button_size_'. $size;
		}

		// custom color

		$style = '';
		$style_icon	= '';
		$data_tags = [];

		if ( $color ) { // TODO: change to variables
			if (strpos($color, '#') === 0) {
				if ( 'stroke' == mfn_opts_get( 'button-style' ) ) {

					// Stroke | Border
					$style .= 'border-color:'. $color .'!important;';
					$class .= ' button_stroke_custom';

				} else {

					// Default | Background
					$style .= 'background-color:'. $color .'!important;';

				}
			} else {
				$class .= ' button_'. $color;
			}
		}

		if( !empty($attr['button_function']) ){
			$class .= ' '.$attr['button_function'];

			if( $attr['button_function'] == 'mfn-read-more' ){
				$data_tags[] = 'data-label_path=".button_label"';
				$data_tags[] = 'data-icon_path=".button_icon"';
				$data_tags[] = 'data-title1="'.$title.'"';
				$data_tags[] = !empty($attr['button_function_read_more_title']) ? 'data-title2="'.be_dynamic_data($attr['button_function_read_more_title']).'"' : 'data-title2="'.$title.'"';
				$data_tags[] = 'data-icon1="'.( !empty($icon) ? $icon : '').'"';
				$data_tags[] = 'data-icon2="'.( !empty($attr['button_function_read_more_icon']) ? $attr['button_function_read_more_icon'] : '').'"';
			}

			if( $attr['button_function'] == 'open-mfn-popup' ){
				$data_tags[] = 'data-mfnpopup="'.(!empty($attr['button_function_popupid']) ? $attr['button_function_popupid'] : 'popup_id_required').'"';
			}

			if( $attr['button_function'] == 'mfn-go-to' ){
				$data_tags[] = 'data-mfngoto="'.(!empty( $attr['button_function_go_to']) ? $attr['button_function_go_to'] : 'next').'"';
			}
		}

		if ($font_color) {
			$style .= 'color:'. $font_color .';';
			$style_icon = 'color:'. $font_color .'!important;';
		}

		if ($style) {
			$style_escaped = ' style="'. esc_attr( $style ) .'"';
		} else {
			$style_escaped = false;
		}

		if ($style_icon) {
			$style_icon_escaped = ' style="'. $style_icon .'"';
		} else {
			$style_icon_escaped = false;
		}

		// rel (do not move up)

		if( $rel ) {
			$rel_escaped = 'rel="'. esc_attr( $rel ) .'"';
		} else {
			$rel_escaped = false;
		}

		// link attributes

		// This variable has been safely escaped above in this function
		$attributes_escaped = $style_escaped .' '. $target_escaped .' '. $rel_escaped .' '. $download_escaped .' '. $onclick_escaped;

		// output -----

		$output = '';

		if ( $align ) {
			$output .= '<div class="button_align align_'. esc_attr( $align ) .'">';
		}

			// This variable has been safely escaped above in this function
			$output .= '<a class="button '. esc_attr( $class ) .'" href="'. esc_url( $link ) .'" '. implode(' ', $data_tags) .' '. $is_add_to_cart .' '. $button_id .' '. $attributes_escaped .' title="'. esc_attr($link_title ?? '') .'">';

				if ($icon) {
					// This variable has been safely escaped above in this function
					$output .= '<span class="button_icon"><i class="'. esc_attr( $icon ) .'" '. $style_icon_escaped .' aria-hidden="true"></i></span>';
				}
				if ($title) {
					$output .= '<span class="button_label">'. $title .'</span>';
				}

			$output .= '</a>';

		if ($align) {
			$output .= '</div>';
		}

		$output .= "\n";

		return $output;
	}
}

/**
 * Icon Bar [icon_bar]
 */

if (! function_exists('sc_icon_bar')) {
	function sc_icon_bar($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'icon' 			=> 'icon-lamp',
			'link' 			=> '',
			'target' 		=> '',
			'size' 			=> '',
			'social' 		=> '',
		), $attr));

		// target

		if ($target) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// class

		$class = '';
		if ($social) {
			$class .= ' icon_bar_'. $social;
		}
		if ($size) {
			$class .= ' icon_bar_'. $size;
		}

		// This variable has been safely escaped above in this function
		$output = '<a href="'. esc_url($link) .'" aria-label="'. __('button with icon', 'betheme') .'" class="icon_bar '. esc_attr($class) .'" '. $target_escaped .'>';
			$output .= '<span class="t"><i class="'. esc_attr($icon) .'"></i></span>';
			$output .= '<span class="b"><i class="'. esc_attr($icon) .'"></i></span>';
		$output .= '</a>'."\n";

		return $output;
	}
}

/**
 * Alert [alert] [/alert]
 */

if (! function_exists('sc_alert')) {
	function sc_alert($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'style' => 'warning',
		), $attr));

		// style

		switch ($style) {
			case 'error':
				$icon = '<svg viewBox="0 0 28 28"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><circle cx="14" cy="20" r="0.33" class="path"/><line x1="14" y1="8.72" x2="14" y2="16.72" class="path"/><path d="M12.6,3.42,1.54,22.58A1.61,1.61,0,0,0,2.93,25H25.07a1.61,1.61,0,0,0,1.39-2.42L15.4,3.42A1.61,1.61,0,0,0,12.6,3.42Z" class="path"/></g></svg>';
				break;
			case 'info':
				$icon = '<svg viewBox="0 0 28 28"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><circle class="path" cx="14" cy="14" r="12"/><path class="path" d="M11.2,9.12a3.4,3.4,0,0,1,3-1.69,2.84,2.84,0,0,1,3,2.76,3.16,3.16,0,0,1-.84,2.23c-.63.74-1.58,1.18-2.19,1.88a1,1,0,0,0-.26.64v2.32"/><circle class="path" cx="14" cy="20" r="0.33"/></g></svg>';
				break;
			case 'success':
				$icon = '<svg viewBox="0 0 28 28"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><polyline points="8.07 13 12.36 18.29 19.93 9.71" class="path"/><circle cx="14" cy="14" r="12" class="path"/></g></svg>';
				break;
			default:
				$icon = '<svg viewBox="0 0 28 28"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><circle class="path" cx="14" cy="14" r="12"/><line class="path" x1="11.5" y1="17.25" x2="16.5" y2="17.25"/><path class="path" d="M16.46,17.8A3.94,3.94,0,0,1,17.64,15a4.61,4.61,0,0,0-.12-6.65A5.09,5.09,0,0,0,14,7h0A5.14,5.14,0,0,0,10.5,8.36,4.66,4.66,0,0,0,9,11.63a4.6,4.6,0,0,0,1.4,3.43,3.85,3.85,0,0,1,1.14,2.74h0V19c0,.87.59,2,1.67,2h1.58c1.08,0,1.67-1.16,1.67-2Z"/></g></svg>';
				break;
		}

		// output -----

		$output = '<div class="alert alert_'. esc_attr($style) .'">';

			$output .= '<div class="alert_icon">'. $icon .'</div>';
			$output .= '<div class="alert_wrapper">'. do_shortcode($content ?? '') .'</div>';
			$output .= '<a href="#" class="close mfn-close-icon"><span class="icon">✕</span></a>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Idea [idea] [/idea]
 */

if (! function_exists('sc_idea')) {
	function sc_idea($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'icon' => 'icon-lamp',
			'border_radius' => '',
		), $attr));

		if( $border_radius ){
			if( is_numeric($border_radius) ){
				$border_radius .= 'px';
			}
			$border_radius = 'border-radius:'. $border_radius;
		}

		// output -----

		$output = '<div class="idea_box" style="'. esc_attr($border_radius) .'">';
			$output .= '<div class="icon"><i class="'. esc_attr($icon) .'" aria-hidden="true"></i></div>';
			$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';
		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Dropcap [dropcap] [/dropcap]
 */

if (! function_exists('sc_dropcap')) {
	function sc_dropcap($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'font'         => '',
			'size'         => 1, // 1-3, or custom size in px
			'background'   => '',
			'color'        => '',
			'circle'       => '',
			'transparent'  => '', // deprecated
		), $attr));

		$class = '';
		$style = '';

		// font family

		if ( $font ) {
			$style .= "font-family:'". $font ."',Arial,Tahoma,sans-serif;";
			if ( ! mfn_opts_get('google-font-mode') ) {
				$font_slug = str_replace(' ', '+', $font);
				wp_enqueue_style($font_slug, 'https://fonts.googleapis.com/css?family='. esc_attr($font_slug) .':400');
			}
		}

		// circle

		if ($circle) {
			$class = ' dropcap_circle';
		}

		// transparent

		if ($transparent) {
			$class = ' transparent';
		}

		// background

		if ($background) {
			$style .= 'background-color:'. $background .';';
		}

		// color

		if ($color) {
			$style .= ' color:'. $color .';';
		}

		// size

		$size = intval($size, 10);
		$sizeH = $size + 15;

		if ($size > 3) {
			if ($transparent) {
				$style .= ' font-size:'. $size .'px;height:'. $size .'px;line-height:'. $size .'px;width:'. $size .'px;';
			} else {
				$style .= ' font-size:'. $size .'px;height:'. $sizeH .'px;line-height:'. $sizeH .'px;width:'. $sizeH .'px;';
			}
		} else {
			$class .= ' size-'. $size;
		}

		if ($style) {
			$style_escaped = 'style="'. esc_attr($style) .'"';
		} else {
			$style_escaped = false;
		}

		// output -----

		// This variable has been safely escaped above in this function
		$output  = '<span class="dropcap'. esc_attr($class) .'" '. $style_escaped .'>';
			$output .= do_shortcode($content ?? '');
		$output .= '</span>'."\n";

		return $output;
	}
}

/**
 * Highlight [highlight] [/highlight]
 */

if (! function_exists('sc_highlight')) {
	function sc_highlight($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'background' => '',
			'color' 		 => '',
			'style' 		 => '', // '', underline
		), $attr));

		// style

		$css = '';
		if ($background) {
			$css .= 'background-color:'. $background .';';
		}
		if ($color) {
			$css .= 'color:'. $color .';';
		}

		if ($css) {
			$css_escaped = 'style="'. esc_attr($css) .'"';
		} else {
			$css_escaped = false;
		}

		// output -----

		// This variable has been safely escaped above in this function
		$output  = '<span class="highlight highlight-'. esc_attr($style) .'" '. $css_escaped .'>';

			if( 'underline' == $style ){

				$words = explode( ' ', trim($content) );
				$string = '';

				foreach( $words as $word ){
					$string .= '<span class="highlight-word">'. $word .'<span class="highlight-border" '. $css_escaped .'></span></span> ';
				}

				$output .= trim( $string );

			} else {
				$output .= do_shortcode($content ?? '');
			}

		$output .= '</span>'."\n";

		return $output;
	}
}

/**
 * Tooltip [tooltip] [/tooltip]
 */

if (! function_exists('sc_tooltip')) {
	function sc_tooltip($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'hint' => '',
			'position' => 'top',
		), $attr));

		// output -----

		$output = '';

		if ($hint) {
			$output .= '<span class="tooltip tooltip-txt" data-tooltip="'. esc_attr($hint) .'" data-position="'. esc_attr($position) .'">';
				$output .= do_shortcode($content ?? '');
			$output .= '</span>'."\n";
		}

		return $output;
	}
}

/**
 * Tooltip [tooltip_image] [/tooltip_image]
 */

if (! function_exists('sc_tooltip_image')) {
	function sc_tooltip_image($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'hint' 			=> '',
			'image' 		=> '',
		), $attr));

		// output -----

		$output = '';

		if ($hint || $image) {
			$output .= '<span class="tooltip tooltip-img">';

				$output .= '<span class="tooltip-content">';

					if ($image) {
						$output .= '<img src="'. esc_url($image) .'" alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
					}

					if ($hint) {
						$output .= wp_kses($hint, mfn_allowed_html('caption'));
					}

				$output .= '</span>';

				$output .= do_shortcode($content ?? '');

			$output .= '</span>'."\n";
		}

		return $output;
	}
}

/**
 * Content Link [content_link]
 */

if (! function_exists('sc_content_link')) {
	function sc_content_link($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'    => '',
			'icon'     => '',
			'link'     => '',
			'target'   => '',
			'class'    => '',
			'download' => '',
		), $attr));

		// target

		if ($target) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// download

		if ($download) {
			$download_escaped = 'download="'. esc_attr($download) .'"';
		} else {
			$download_escaped = false;
		}

		// output -----

		// This variable has been safely escaped above in this function
		$output = '<a class="content_link '. esc_attr($class) .'" href="'. esc_url($link) .'" '. $target_escaped .' '. $download_escaped .'>';
			if ($icon) {
				$output .= '<span class="icon"><i class="'. esc_attr($icon) .'" aria-hidden="true"></i></span>';
			}
			if ($title) {
				$output .= '<span class="title">'. wp_kses($title, mfn_allowed_html()) .'</span>';
			}
		$output .= '</a>';

		return $output;
	}
}

/**
 * Fancy Link [fancy_link]
 */

if (! function_exists('sc_fancy_link')) {
	function sc_fancy_link($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'			=> '',
			'link'			=> '',
			'target' 		=> '',
			'style'			=> '1',	// 1-9
			'icon'			=> '',	// for style 9 only
			'margin'		=> '0',
			'font' 			=> '',
			'font_size' => '',
			'class'			=> '',
			'popup'			=> '',
			'download'	=> '',
		), $attr));

		$css = '';

		// target

		if ($target) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// font size

		if( ! empty($font_size) ){
			if( is_numeric($font_size) ){
				$font_size .= 'px';
			}
			$css .= 'font-size:'. esc_attr($font_size) .';';
		}

		// font family

		if( ! empty($font) ){
			$css .= "font-family:'". esc_attr($font) ."',Arial,Tahoma,sans-serif;";
		}

		// margin

		if( ! empty($margin) ){
			if( is_numeric($margin) ){
				$margin .= 'px';
			}
			$css .= 'margin:'. esc_attr($margin) .';';
		}

		// popup

		if( ! empty($popup) ){
			$popup_escaped = 'data-mfnpopup="'. esc_attr($popup) .'"';
			$class .= ' open-mfn-popup';
		} else {
			$popup_escaped = false;
		}

		// download

		if ($download) {
			$download_escaped = 'download="'. esc_attr($download) .'"';
		} else {
			$download_escaped = false;
		}

		// output -----

		$output = '<a class="mfn-link mfn-link-'. intval($style, 10) .' '. esc_attr($class) .'" href="'. esc_url($link) .'" style="'. $css .'" data-hover="'. esc_html($title) .'" '. $target_escaped .' '. $popup_escaped .' '. $download_escaped .'>';

			$output .= '<span data-hover="'. esc_html($title) .'">'. esc_html($title) .'</span>';

			if( 9 == $style ){
				if( $icon ){
					$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
				} else {
					$output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 14" aria-hidden="true"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><line class="path" x1="25.23" y1="7" x2="3.23" y2="7"/><polyline class="path" points="20.47 3.5 25.23 7 20.47 10.5"/></g></svg>';
				}
			}

		$output .= '</a>';

		// enqueue_style

		if ( $font && ! mfn_opts_get('google-font-mode') ) {
			$font_slug = str_replace(' ', '+', $font);
			wp_enqueue_style(esc_attr($font_slug), 'https://fonts.googleapis.com/css?family='. esc_attr($font_slug) );
		}

		return $output;
	}
}

/**
 * Blockquote [blockquote] [/blockquote]
 */

if (! function_exists('sc_blockquote')) {
	function sc_blockquote($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'author' => '',
			'link'   => '',
			'link_title'   => '',
			'icon'   => '',
			'icon_author'   => '',
			'target' => 0,
		), $attr));

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// output -----

		$output = '<div class="blockquote">';

			if( empty( mfn_opts_get('style') ) ){
				$output .= '<span class="mfn-blockquote-icon"><i class="'.( !empty($icon) ? $icon : 'icon-quote' ).'" aria-hidden="true"></i></span>';
			}

			$output .= '<blockquote class="mfn-inline-editor">';

			$output .= do_shortcode($content ?? '') .'</blockquote>';

			if ($author) {
				$output .= '<p class="author">';

					$output .= !empty($icon_author) ? '<i class="'.$icon_author.'"></i>' : '<i class="icon-user"></i>';

					if ($link) {
						// This variable has been safely escaped above in this function
						$output .= '<a href="'. esc_attr($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'" aria-label="'. __('author', 'betheme') .'">'. esc_html($author) .'</a>';
					} else {
						$output .= '<span>'. esc_html($author) .'</span>';
					}

				$output .= '</p>';
			}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Clients [clients]
 */

if (! function_exists('sc_clients')) {
	function sc_clients($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'in_row'     => 6,
			'category'   => '',
			'orderby'    => 'menu_order',
			'order'      => 'ASC',
			'style'      => '',
			'greyscale'  => '',
		), $attr));

		// class

		$class = '';

		if ($greyscale) {
			$class .= ' greyscale';
		}
		if ($style) {
			$class .= ' clients_tiles';
		}

		if (! intval($in_row, 10)) {
			$in_row = 6;
		}

		// query args

		$args = array(
			'post_type'      => 'client',
			'posts_per_page' => -1,
			'orderby'        => $orderby,
			'order'          => $order,
		);

		if ($category) {
			$args['client-types'] = $category;
		}

		$clients_query = new WP_Query();
		$clients_query->query($args);

		// output -----

		$output  = '<ul class="clients clearfix '. esc_attr($class) .'">';

			if ($clients_query->have_posts()) {
				$i = 1;
				$width = round((100 / $in_row), 3);

				while ($clients_query->have_posts()) {
					$clients_query->the_post();

					$output .= '<li style="width:'. esc_attr($width) .'%">';
						$output .= '<div class="client_wrapper">';

							$link = get_post_meta(get_the_ID(), 'mfn-post-link', true);
							$target = get_post_meta(get_the_ID(), 'mfn-post-target', true);

							if( '_self' != $target ){
								$target = 'target="_blank"';
							} else {
								$target = '';
							}

							if ($link) {
								$output .= '<a '. $target .' href="'. esc_url($link) .'" title="'. the_title(false, false, false) .'">';
							}

								$output .= '<div class="gs-wrapper">';
									$output .= get_the_post_thumbnail(null, 'be_clients', array( 'class'=>'scale-with-grid' ));
								$output .= '</div>';

							if ($link) {
								$output .= '</a>';
							}

						$output .= '</div>';
					$output .= '</li>';

					$i++;
				}
			}

			wp_reset_query();

		$output .= '</ul>'."\n";

		return $output;
	}
}

/**
 * Clients slider [clients_slider]
 */

if (! function_exists('sc_clients_slider')) {
	function sc_clients_slider($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'    		=> '',
			'title_tag'    		=> '',
			'category' 		=> '',
			'orderby'  		=> 'menu_order',
			'order'				=> 'ASC',
			'per_slide'		=> '3',
			'scroll'			=> '',
			'navigation'	=> '', // '' (header), 'content'
		), $attr));

		$title = be_dynamic_data($title);

		// query args

		$args = array(
			'post_type'      => 'client',
			'posts_per_page' => -1,
			'orderby'        => $orderby,
			'order'          => $order,
		);

		if ($category) {
			$args['client-types'] = $category;
		}

		$clients_query = new WP_Query();
		$clients_query->query($args);

		// slider to scroll

		if( empty( $scroll ) ){
			$scroll = '';
		}

		$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '';

		if ($clients_query->have_posts()) {
			$output  = '<div class="clients_slider" data-client-per-slide="'. intval($per_slide) .'" data-navigation-position="'. esc_attr($navigation) .'" data-slides-to-scroll="'. esc_attr($scroll) .'" >';

				$output .= '<div class="blog_slider_header clearfix">';
					if ($title) {
						$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
					}
					$output .= '<div class="slider_navigation"></div>';
				$output .= '</div>';

				$output .= '<ul class="clients clients_slider_ul">';
					while ($clients_query->have_posts()) {
						$clients_query->the_post();

						$output .= '<li>';
							$output .= '<div class="client_wrapper">';

								$link = get_post_meta(get_the_ID(), 'mfn-post-link', true);
								$target = get_post_meta(get_the_ID(), 'mfn-post-target', true);

								if( '_self' != $target ){
									$target = 'target="_blank"';
								} else {
									$target = '';
								}

								if ($link) {
									$output .= '<a '. $target .' href="'. esc_url($link) .'" title="'. the_title(false, false, false) .'">';
								} else {
									$output .= '<a title="'. the_title(false, false, false) .'">';
								}

									$output .= get_the_post_thumbnail(null, 'be_clients', array( 'class' => 'scale-with-grid' ));

								$output .= '</a>';

							$output .= '</div>';
						$output .= '</li>';
					}
				$output .= '</ul>';

			$output .= '</div>'."\n";
		}

		wp_reset_query();

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-slick', get_theme_file_uri('/js/plugins/slick.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Fancy Heading [fancy_heading] [/fancy_heading]
 */

if (! function_exists('sc_fancy_heading')) {
	function sc_fancy_heading($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'    => '',
			'title_tag'    => '',
			'h1'       => '',
			'icon'     => '',
			'slogan'   => '',
			'style'    => 'icon',	// icon, line, arrows
		), $attr));

		$title = be_dynamic_data($title);

		// title tag

		$title_class = '';
		if( ! empty($h1) ){
			if( 'p.lead' == $h1 ){
				$h1 = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="fancy_heading fancy_heading_'. esc_attr($style) .'">';

			$output .= '<div class="fh-top">';

				if ($style == 'icon' && $icon) {
					$output .= '<span class="icon_top"><i class="'. esc_attr($icon) .'" aria-hidden="true"></i></span>';
				}

				if ($style == 'line' && $slogan) {
					$output .= '<span class="slogan">'. wp_kses($slogan, mfn_allowed_html()) .'</span>';
				}

				if ($style =='arrows') {
					$title = '<i class="icon-right-dir" aria-hidden="true"></i>'. wp_kses($title, mfn_allowed_html()) .'<i class="icon-left-dir" aria-hidden="true"></i>';
				}

			$output .= '</div>';

				if ($title) {
					if ($h1) {
						$title_tag = $h1 == '1' ? 'h1' : $h1;
						$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
					} else {
						$output .= '<h2 class="title">'. wp_kses($title, mfn_allowed_html()) .'</h2>';
					}
				}
				if ($content) {
					$output .= '<div class="inside">'. do_shortcode($content ?? '') .'</div>';
				}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Heading [heading] [/heading]
 */

if (! function_exists('sc_heading_inline')) {
	function sc_heading_inline($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'tag'    => 'h2',
			'align'  => 'left',
			'color'  => '',
			'style'  => '', // [none], lines
			'color2' => '',
		), $attr));

		$before = $after = '';

		// inline_css

		$inline_css = '';

		if ($color) {
			$inline_css .= 'color:'. $color .';';
		}

		if ($inline_css) {
			$inline_css_escaped = 'style="'. esc_attr($inline_css) .'"';
		} else {
			$inline_css_escaped = false;
		}

		// inline_css_line

		$inline_css_line = '';

		if ($color2) {
			$inline_css_line .= 'background:'. $color2 .';';
		}

		if ($inline_css_line) {
			$inline_css_line_escaped = 'style="'. esc_attr($inline_css_line) .'"';
		} else {
			$inline_css_line_escaped = false;
		}

		// style

		if ($style == 'lines') {
			// This variable has been safely escaped above in this function
			$before	= '<span class="line line_l" '. $inline_css_line_escaped .'></span>';
			$after	= '<span class="line line_r" '. $inline_css_line_escaped .'></span>';
		}

		if ($style) {
			$style = 'heading_'. $style;
		}

		// output -----

		$output = '<div class="mfn_heading '. esc_attr($style) .' align_'. esc_attr($align).'">';

			// This variable has been safely escaped above in this function
			$output .= '<'. mfn_allowed_title_tag($tag) .' class="title" '. $inline_css_escaped .'>';

				$output .= $before;

					$output .= do_shortcode($content ?? '');

				$output .= $after;

			$output .= '</'. mfn_allowed_title_tag($tag) .'>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Icon Box [icon_box] [/icon_box]
 */

if (! function_exists('sc_icon_box')) {
	function sc_icon_box($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title'      => '',
			'title_tag'  => 'h4',

			'icon'       => '',
			'image'      => '',
			'icon_position'  => 'top',
			'border'     => '',

			'link'       => '',
			'link_title'       => '',
			'target'     => '',
			'class'      => '',
		), $attr));

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		// border

		if ($border) {
			$border = 'has_border';
		} else {
			$border = 'no_border';
		}

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// FIX | prettyphoto

		if (strpos($class, 'prettyphoto') !== false) {
			$class 	= str_replace('prettyphoto', '', $class);
			$rel_escaped 	= 'rel="prettyphoto"';
		} else {
			$rel_escaped 	= false;
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '';

			$output .= '<div class="icon_box icon_position_'. esc_attr($icon_position) .' '. esc_attr($border) .'">';

				if ($link) {
					// This variable has been safely escaped above in this function
					$output .= '<a class="'. esc_attr($class) .'" href="'. esc_url($link) .'" '. $target_escaped .' '. $rel_escaped .' title="'. esc_attr($link_title ?? '') .'">';
				}

					if ($image) {
						$output .= '<div class="image_wrapper">';
							$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
						$output .= '</div>';
					} else if( !empty($icon) ) {
						$output .= '<div class="icon_wrapper">';
							$output .= '<div class="icon">';
								$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
							$output .= '</div>';
						$output .= '</div>';
					}

					$output .= '<div class="desc_wrapper">';

						if ($title) {
							$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
						}
						if ($content) {
							$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';
						}

					$output .= '</div>';

				if ($link) {
					$output .= '</a>';
				}

			$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Icon
 */

if (! function_exists('sc_icon_2')) {
 	function sc_icon_2($attr) {
 		extract(shortcode_atts(array(
 			'icon' 		=> '',
 			'link' 		=> '',
 			'target'    => '',
 			'hover'    	=> '',
 		), $attr));

 		if( empty($icon) ) $icon = 'icon-lamp';
 		$classes = array('mfn-icon-2');

 		if ( 'lightbox' === $target ) {
 			$target = 'rel="prettyphoto"';
 		} elseif ( $target ) {
 			$target = 'target="_blank"';
 		} else {
 			$target = '';
 		}

 		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$classes[] = 'open-mfn-popup';
			$target .= ' data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		if( $hover ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $hover );
 		}

 		$output = '';
 		$link_title = !empty($attr['link_title']) ? 'title="'.be_dynamic_data( $attr['link_title'] ).'"' : false;

 		if( !empty($link) ){
 			$output .= '<a '. $link_title .' '. $target .' href="'. be_dynamic_data( esc_attr($link) ) .'" class="'. implode(' ', $classes) .'">';
 		}else{
 			$output .= '<span class="'.implode(' ', $classes).'">';
 		}

 		$output .= '<i class="'.$icon.'"></i>';

 		if( !empty($link) ){
 			$output .= '</a>';
 		}else{
 			$output .= '</span>';
 		}

 		return $output;
 	}
 }

/**
 * Icon Box 2 [icon_box_2] [/icon_box_2]
 */

if (! function_exists('sc_icon_box_2')) {
 	function sc_icon_box_2($attr, $content = null) {
 		extract(shortcode_atts(array(

 			'title' => '',
 			'title_tag' => 'h4',
 			'icon' 	=> '',
 			'label' => '',
 			'image' => '',

 			'icon_position' => 'top',
 			'icon_position_tablet' => '',
 			'icon_position_laptop' => '',
 			'icon_position_mobile' => '',

 			'icon_align' => 'center',
 			'icon_align_tablet' => '',
 			'icon_align_laptop' => '',
 			'icon_align_mobile' => '',

 			'link' => '',
 			'link_title' => '',
 			'target' => '',

 			'hover' => '',

 		), $attr));

 		// image | visual composer fix

 		$post_id = false;

		if( !empty($attr['vb_postid']) ){
			$post_id = $attr['vb_postid'];
		}else if( !empty(Mfn_Builder_Front::$item_id) ){
			$post_id = Mfn_Builder_Front::$item_id;
		}else if( is_single() ){
			$post_id = get_the_ID();
		}

		$image_tag = false;

		if( !empty($image) && strpos($image, '#') !== false ){
			$image_tag = mfn_get_attachment($image);
		}else{
			$image = mfn_vc_image($image);
		}

 		// target

 		if ( 'lightbox' === $target ) {
 			$target = 'rel="prettyphoto"';
 		} elseif ( $target ) {
 			$target = 'target="_blank"';
 		} else {
 			$target = false;
 		}

 		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$target = 'class="open-mfn-popup"';
			$target .= ' data-mfnpopup="'. be_dynamic_data( esc_attr($attr['popup_id']) ) .'"';
		}

		if( !empty( $attr['link_type'] ) && $attr['link_type'] == 'mfn-read-more' ){
			if( empty($link) ) $link = '#';
			$target = 'class="mfn-read-more"';
			$target .= ' data-label_path=".title.label"';
			$target .= ' data-icon_path=".icon-wrapper"';
			$target .= ' data-title1="'.$title.'"';
			$target .= !empty($attr['link_type_read_more_title']) ? ' data-title2="'.$attr['link_type_read_more_title'].'"' : ' data-title2="'.$title.'"';

			$target .= ' data-icon1="'.esc_attr($icon).'"';
			$target .= ' data-icon2="'.( !empty($attr['link_type_read_more_icon']) ? esc_attr($attr['link_type_read_more_icon']) : '' ).'"';
		}

 		// classes

 		$classes = ['mfn-icon-box'];

 		if( $icon_position ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $icon_position );
 		}
 		if( $icon_position_tablet ){
 			$classes[] = 'mfn-icon-box-tablet-'. esc_attr( $icon_position_tablet );
 		}
 		if( $icon_position_laptop ){
 			$classes[] = 'mfn-icon-box-laptop-'. esc_attr( $icon_position_laptop );
 		}
 		if( $icon_position_mobile ){
 			$classes[] = 'mfn-icon-box-mobile-'. esc_attr( $icon_position_mobile );
 		}

 		if( $icon_align ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $icon_align );
 		}
 		if( $icon_align_tablet ){
 			$classes[] = 'mfn-icon-box-tablet-'. esc_attr( $icon_align_tablet );
 		}
 		if( $icon_align_laptop ){
 			$classes[] = 'mfn-icon-box-laptop-'. esc_attr( $icon_align_laptop );
 		}
 		if( $icon_align_mobile ){
 			$classes[] = 'mfn-icon-box-mobile-'. esc_attr( $icon_align_mobile );
 		}

 		if( $hover ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $hover );
 		}

 		$classes = implode(' ', $classes);

 		$is_love_item = false;

 		if( $title == '{postmeta:mfn-post-love}' ) {
 			$is_love_item = 'mfn-love';
 		}

		// dynamic data

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$content = be_dynamic_data($content);

		if ( !empty($is_love_item) && isset($_COOKIE['mfn-post-love-'. $post_id])) {
			$is_love_item .= ' loved';
		}

		$image = be_dynamic_data($image);

		if( is_numeric($image) ){
			$image_tag = wp_get_attachment_image($image, 'full');
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ) {
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

 		// output -----

 		$output = '';

 			if( $link || !empty($is_love_item) ){
 				$output .= '<a '.( !empty($is_love_item) ? 'class="'.$is_love_item.'" data-id="'.$post_id.'"' : '').' href="'. esc_url($link) .'" '. $target .' title="'. esc_attr($link_title ?? '') .'">';
 			}

 			$output .= '<div class="'. esc_attr($classes) .'">';

 				$output .= '<div class="icon-wrapper">';

 				if( !empty($is_love_item) ){

 					$output .= '<i class="icon-heart-empty-fa"></i><i class="icon-heart-fa"></i>';

 				}else{
 					if( $image ){
 						if( $image_tag ){
 							$output .= $image_tag;
 						}else{
 							$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
 						}
 					} elseif( $icon ){
 						$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
 					} elseif( $label ){
 						$output .= '<span class="icon-label">'.$label.'</span>';
 					}

 				}

 				$output .= '</div>';

 				$output .= '<div class="desc-wrapper">';

 					if( $title || !empty($is_love_item) ){
 						$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title label '. esc_attr($title_class) .'">'. $title .'</'. mfn_allowed_title_tag($title_tag) .'>';
 					}

 					if( $content ){
 						$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';
 					}

 				$output .= '</div>';

 			$output .= '</div>';

 			if( $link || !empty($is_love_item) ){
 				$output .= '</a>';
 			}

 		return $output;
 	}
}

/**
 * Our Team [our_team]
 */

if (! function_exists('sc_our_team')) {
	function sc_our_team($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'heading'  => '',
			'heading_tag'  => '',
			'image'    => '',
			'title'    => '',
			'title_tag'    => '',
			'subtitle' => '',

			'phone'    => '',
			'email'    => '',

			'facebook' => '',
			'twitter'  => '',
			'linkedin' => '',
			'vcard'    => '',

			'blockquote' => '',
			'style'      => 'vertical',

			'link'     => '',
			'link_title'     => '',
			'target'   => '',
		), $attr));

		// image | visual composer fix

		$heading = be_dynamic_data($heading);
		$title = be_dynamic_data($title);
		$content = be_dynamic_data($content);


		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		if( !empty( $attr['link_type'] ) && !empty($attr['popup_id']) ){
			if( empty($link) ) $link = '#';
			$target_escaped = 'class="open-mfn-popup"';
			$target_escaped .= ' data-mfnpopup="'. esc_attr($attr['popup_id']) .'"';
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$heading_class = '';
		if( ! empty($heading_tag) ){
			if( 'p.lead' == $heading_tag ){
				$heading_tag = 'p';
				$heading_class = 'lead';
			}
		}

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="team team_'. esc_attr($style) .'">';

			if ($heading) {
				$heading_tag = !empty( $attr['heading_tag'] ) ? $attr['heading_tag'] : 'h4';
				$output .= '<'. mfn_allowed_title_tag($heading_tag) .' class="title '. esc_attr($heading_class) .'">'. wp_kses($heading, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($heading_tag) .'>';
			}

			if ( $image ) {

				$output .= '<div class="image_frame photo no_link scale-with-grid">';
					$output .= '<div class="image_wrapper">';

						if ( $link ) {
							// This variable has been safely escaped above in this function
							$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
						}

						$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';

						if ( $link ) {
							$output .= '</a>';
						}

					$output .= '</div>';
				$output .= '</div>';

			}

			$output .= '<div class="desc_wrapper clearfix">';

				if ($title) {
					$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="desc_wrappper_title '. esc_attr($title_class) .'">';

						if ($link) {
							// This variable has been safely escaped above in this function
							$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .'>';
						}

						$output .= wp_kses( $title, mfn_allowed_html() );

						if ($link) {
							$output .= '</a>';
						}

					$output .= '</'. mfn_allowed_title_tag($title_tag) .'>';
				}

				if ($subtitle) {
					$output .= '<p class="subtitle">'. wp_kses($subtitle, mfn_allowed_html()) .'</p>';
				}
				if ($phone) {
					$output .= '<p class="phone"><i class="icon-phone"></i> <a href="tel:'. esc_attr($phone) .'" aria-label="'. __('phone', 'betheme') .'">'. esc_html($phone) .'</a></p>';
				}
				$output .= '<hr class="hr_color" />';

				$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';

				if ($email || $phone || $facebook || $twitter || $linkedin) {
					$output .= '<div class="links">';
						if ($email) {
							$output .= '<a href="mailto:'. esc_attr($email) .'" class="icon_bar icon_bar_small mail" aria-label="'. __('mail', 'betheme') .'"><span class="t"><i class="icon-mail"></i></span><span class="b"><i class="icon-mail" aria-hidden="true"></i></span></a>';
						}
						if ($facebook) {
							$output .= '<a target="_blank" href="'. esc_url($facebook) .'" class="icon_bar icon_bar_small facebook" aria-label="facebook"><span class="t"><i class="icon-facebook"></i></span><span class="b"><i class="icon-facebook" aria-hidden="true"></i></span></a>';
						}
						if ($twitter) {
							$output .= '<a target="_blank" href="'. esc_url($twitter) .'" class="icon_bar icon_bar_small twitter" aria-label="twitter"><span class="t"><i class="icon-twitter"></i></span><span class="b"><i class="icon-twitter" aria-hidden="true"></i></span></a>';
						}
						if ($linkedin) {
							$output .= '<a target="_blank" href="'. esc_url($linkedin) .'" class="icon_bar icon_bar_small linkedin" aria-label="linkedin"><span class="t"><i class="icon-linkedin"></i></span><span class="b"><i class="icon-linkedin" aria-hidden="true"></i></span></a>';
						}
						if ($vcard) {
							$output .= '<a href="'. esc_url($vcard) .'" class="icon_bar icon_bar_small vcard" aria-label="vcard"><span class="t"><i class="icon-vcard"></i></span><span class="b"><i class="icon-vcard" aria-hidden="true"></i></span></a>';
						}
					$output .= '</div>';
				}

				if ($blockquote) {
					$output .= '<div class="blockquote"><span class="mfn-blockquote-icon"><i class="icon-quote" aria-hidden="true"></i></span><blockquote class="mfn-inline-editor">'. wp_kses($blockquote, mfn_allowed_html('desc')) .'</blockquote></div>';
					//$output .= '<blockquote>'. wp_kses($blockquote, mfn_allowed_html('desc')) .'</blockquote>';
				}

			$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Our Team List [our_team_list]
 */

if (! function_exists('sc_our_team_list')) {
	function sc_our_team_list($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'image'			=> '',
			'title'			=> '',
			'title_tag'			=> '',
			'subtitle'	=> '',
			'blockquote'=> '',
			'email'			=> '',
			'phone' 		=> '',
			'facebook' 	=> '',
			'twitter'		=> '',
			'linkedin'	=> '',
			'vcard'			=> '',
			'link' 			=> '',
			'link_title' 			=> '',
			'target' 		=> '',
		), $attr));

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$link_title = be_dynamic_data($link_title);
		$content = be_dynamic_data($content);

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		// target

		if ( 'lightbox' === $target ) {
			$target_escaped = 'rel="prettyphoto"';
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// image class

		$img_class = 'scale-with-grid';

		// src output

		$src = 'src="'. esc_url($image) .'"';

		if( empty( $attr['vb'] ) && mfn_is_lazy() ){
			$src = 'data-'. $src;
			$img_class .= ' mfn-lazy';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="team team_list clearfix">';

			$output .= '<div class="column mobile-one one-fourth">';
				$output .= '<div class="mcb-column-inner">';
					$output .= '<div class="image_frame no_link scale-with-grid">';
						$output .= '<div class="image_wrapper">';

						if ($link) {
							// This variable has been safely escaped above in this function
							$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
						}

						$output .= '<img class="'. esc_attr($img_class) .'" '. $src .' alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';

						if ($link) {
							$output .= '</a>';
						}

						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';

			$output .= '<div class="column mobile-one one-second">';
				$output .= '<div class="mcb-column-inner">';
					$output .= '<div class="desc_wrapper">';

						if ($title) {
							$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
							$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">';
							if ($link) {
								// This variable has been safely escaped above in this function
								$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .' title="'. esc_attr($link_title ?? '') .'">';
							}
								$output .= wp_kses($title, mfn_allowed_html());
							if ($link) {
								$output .= '</a>';
							}
							$output .= '</'. mfn_allowed_title_tag($title_tag) .'>';
						}

						if ($subtitle) {
							$output .= '<p class="subtitle">'. wp_kses($subtitle, mfn_allowed_html()) .'</p>';
						}
						if ($phone) {
							$output .= '<p class="phone"><i class="icon-phone"></i> <a href="tel:'. esc_attr($phone) .'" aria-label="'. __('phone', 'betheme') .'">'. esc_html($phone) .'</a></p>';
						}
						$output .= '<hr class="hr_color" />';

						$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';

					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';

			$output .= '<div class="column mobile-one one-fourth">';
				$output .= '<div class="mcb-column-inner">';
					$output .= '<div class="bq_wrapper">';

						if ($blockquote) {
							//$output .= '<blockquote>'. wp_kses($blockquote, mfn_allowed_html('desc')) .'</blockquote>';
							$output .= '<div class="blockquote"><span class="mfn-blockquote-icon"><i class="icon-quote" aria-hidden="true"></i></span><blockquote class="mfn-inline-editor">'. wp_kses($blockquote, mfn_allowed_html('desc')) .'</blockquote></div>';
						}

						if ($email || $phone || $facebook || $twitter || $linkedin) {
							$output .= '<div class="links">';
							if ($email) {
								$output .= '<a href="mailto:'. esc_attr($email) .'" class="icon_bar icon_bar_small mail" aria-label="'. __('mail', 'betheme') .'"><span class="t"><i class="icon-mail"></i></span><span class="b"><i class="icon-mail" aria-hidden="true"></i></span></a>';
							}
							if ($facebook) {
								$output .= '<a target="_blank" href="'. esc_url($facebook) .'" class="icon_bar icon_bar_small facebook" aria-label="facebook"><span class="t"><i class="icon-facebook"></i></span><span class="b"><i class="icon-facebook" aria-hidden="true"></i></span></a>';
							}
							if ($twitter) {
								$output .= '<a target="_blank" href="'. esc_url($twitter) .'" class="icon_bar icon_bar_small twitter" aria-label="twitter"><span class="t"><i class="icon-twitter"></i></span><span class="b"><i class="icon-twitter" aria-hidden="true"></i></span></a>';
							}
							if ($linkedin) {
								$output .= '<a target="_blank" href="'. esc_url($linkedin) .'" class="icon_bar icon_bar_small linkedin" aria-label="linkedin"><span class="t"><i class="icon-linkedin"></i></span><span class="b"><i class="icon-linkedin" aria-hidden="true"></i></span></a>';
							}
							if ($vcard) {
								$output .= '<a href="'. esc_url($vcard) .'" class="icon_bar icon_bar_small vcard" aria-label="vcard"><span class="t"><i class="icon-vcard"></i></span><span class="b"><i class="icon-vcard" aria-hidden="true"></i></span></a>';
							}
							$output .= '</div>';
						}

					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Lottie [lottie]
 */

if (! function_exists('sc_lottie')) {
	function sc_lottie($attr) {
		extract(shortcode_atts(array(
			'file' => '',
			'src' => '',
			'trigger' => '',
			'speed' => '',
			'loop' => '0',
			'link' => '',
			'frame_start' => '',
			'frame_end' => '',
			'viewport' => '',
			'direction' => '',
		), $attr));

		$script = '';
		$output = '';
		$return = array();
		$id = 'mfn_lottie_'.rand(0, 999).'_'.rand(0, 999);
		$viewport = !empty( $viewport ) ? 100 - (int)$viewport : 90;

		if( empty($trigger) ) $trigger = 'default';

		if( empty($frame_start) ) $frame_start = 0;
		if( empty($frame_end) ) $frame_end = 100;

		if( empty($file) && !empty($src) ){
			$file = $src;
		}else if( empty($file) ){
			$file =  get_theme_file_uri('/assets/lottie/betheme-lottie.json');
		}

		$script .= 'var lottie_cont'.$id.' = document.getElementById("'.$id.'");';

		$script .= '
		var trigger'.$id.' = "'.$trigger.'";
		var direction'.$id.' = '.( !empty( $direction ) ? $direction : '1' ).';
		var total'.$id.'frames;
		var start'.$id.'frame;
		var scroll_started'.$id.' = false;
		var frames'.$id.';
		var frames'.$id.'_reverse;
		var '.$id.' = bodymovin.loadAnimation({
			container: lottie_cont'.$id.',
            renderer: \'svg\',
            autoplay: false,
            loop: '.( !empty($loop) && $loop == "1" && !in_array($trigger, array('scroll')) ? "true" : "false" ).',
            path: "'.$file.'"
        });';

        // speed
		if( !empty($speed) ) {
			$script .= $id.'.setSpeed('.$speed.');';
		}else{
			$speed = 1;
		}

		// direction
		if( !empty( $direction ) ) {
			$script .= $id.'.setDirection('.$direction.');';
		}

		// frames
		if( !empty($frame_start) || !empty($frame_end) ) {

			$script .= $id.'.addEventListener("DOMLoaded", function() {
				total'.$id.'frames =  Math.floor( ('.$frame_end.'*'.$id.'.animationData.op)/100 );
				start'.$id.'frame = Math.floor( ('.$frame_start.'*'.$id.'.animationData.op)/100 );
				frames'.$id.' = [start'.$id.'frame, total'.$id.'frames];
				frames'.$id.'_reverse = [total'.$id.'frames, start'.$id.'frame];';

			// default, hover, click
			if( in_array($trigger, array('default', 'hover', 'click')) ) {
				$script .= 'if( trigger'.$id.' == "default" ){
					if(direction'.$id.' == -1) { '.$id.'.playSegments(frames'.$id.'_reverse, true); }else{ '.$id.'.playSegments(frames'.$id.', true); }
				}else if( trigger'.$id.' == "hover" ){
					if(direction'.$id.' == -1) { choosed_frames'.$id.' = frames'.$id.'_reverse; }else{ choosed_frames'.$id.' = frames'.$id.'; }
					lottie_cont'.$id.'.addEventListener("mouseenter", function() { if(direction'.$id.' == -1) { '.$id.'.playSegments(frames'.$id.'_reverse, true); }else{ '.$id.'.playSegments(frames'.$id.', true); } });
				}else if( trigger'.$id.' == "click" ){
					lottie_cont'.$id.'.addEventListener("click", function() { if(direction'.$id.' == -1) { '.$id.'.playSegments(frames'.$id.'_reverse, true); }else{ '.$id.'.playSegments(frames'.$id.', true); } });
				}';
			// viewport, scroll
			}else if( in_array($trigger, array('viewport', 'scroll')) ){
				$script .= 'imagesLoaded(document.body, function() {

				var waypoint = new Waypoint({
					element: lottie_cont'.$id.',
					handler: function(direction) {
						if( trigger'.$id.' == "viewport" ){
							if( direction == "down" ) {
								if(direction'.$id.' == -1) { '.$id.'.playSegments(frames'.$id.'_reverse, true); }else{ '.$id.'.playSegments(frames'.$id.', true); }
							}
						}else{
							if( !scroll_started'.$id.' ){
								scroll_started'.$id.' = true;
								var last_pos = 0;
								var cont_offset = (jQuery("#'.$id.'").offset().top - '.$viewport.' - ( jQuery(window).height() * ('.(int)$viewport.'/100) ) );
								document.addEventListener("scroll", function() {
									var curr_offset = jQuery(window).scrollTop();
									var anim_pos = Math.floor( ( (curr_offset-cont_offset)/8)*'.$speed.' );
									if( anim_pos >= start'.$id.'frame && anim_pos <= (total'.$id.'frames) && anim_pos != last_pos ) {
										last_pos = anim_pos;
										'.$id.'.goToAndStop(anim_pos, true);
									}else{
										return;
									}
								});
							}
						}
					}, offset: "'.$viewport.'%"
				}); });';
			}

			$script .= '});';
		}

		// output -----

		$selector = 'div';
		if( !empty($link) && $trigger != 'click' ){
			$selector = 'a';
		}

		$output .= '<'.$selector.' '.( !empty($link) && $trigger != 'click' ? 'href="'.$link.'"' : "" ).' class="mfn-lottie-wrapper">';
			$output .= '<div id="'.$id.'" class="lottie"></div>';
		$output .= '</'.$selector.'>';

		if( isset( $attr['vb'] ) ){
			// bebuilder

			$output .= '<script>'.$script.'</script>';

			/*$return[] = $output;
			$return[] = $script;*/

			return $output;

		}else{
			// front
			wp_enqueue_script('mfn-imagesloaded', get_theme_file_uri('/js/plugins/imagesloaded.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-lottie-player', get_theme_file_uri('/assets/lottie/lottie-player.js'), false, null, true);
			wp_add_inline_script('mfn-lottie-player', $script);

			return $output;
		}


	}
}


/**
 * Collection [collection]
 */

 if (! function_exists('sc_collection')) {
	function sc_collection($attr, $content = null) {

		// Default layout grid
		$output = '<div class="cards">';

		$featured_items = get_field('featured_collection', 'options');

		if (!empty($featured_items)) {

			foreach ($featured_items as $item) {

				$output .= '<div class="cards__item">';
					$output .= '<div class="card">';

						$output .= '<a class="card__link" href="' . get_permalink($item) . '"></a>';
						$output .= '<figure class="card__image ratio">';
						if (has_post_thumbnail($item)) {
							$output .= wp_get_attachment_image(get_post_thumbnail_id($item), 'full');
						}

						if($popupId = get_post_meta($item, 'popup_video_id', true)) {
							$output .= '<button class="js-open-dialog" style="position: absolute; background-color: #ec6c03; display: flex; align-items: center; justify-content: center; top: 0; right: 0; width: 40px; height: 40px; border-radius: 0; border-bottom-left-radius: 4px; color: rgb(0, 0, 0); cursor: pointer; z-index: 4;">
											<i class="icon-videocam-line" style="font-size: 1.5em; color: white;"></i>
										</button>
										<dialog class="dialog js-dialog">
											<div class="dialog__inner">
												<button class="dialog__close js-close-dialog">Close</button>
												<div class="dialog__body js-dialog-video" data-src="' . $popupId . '">
												</div>
											</div>
										</dialog>';
							}
						$output .= '</figure>';

						$output .= '<div class="card__content">';
							$output .= '<header class="card__header">';
								$output .= '<h3 class="card__title">' . get_the_title($item) . '</h3>';
							$output .= '</header>';

							$output .= '<div class="card__metas">';

								if ($amount_of_km = get_field('amount_of_km', $item)) {
									$output .= '<div class="card__meta">KM stand: ' . $amount_of_km . '</div>';
								}
								if ($date_of_registration = get_field('date_of_registration', $item)) {
									$output .= '<div class="card__meta">Inschrijving: ' . $date_of_registration . '</div>';
								}
								if ($amount_of_pk = get_field('amount_of_pk', $item)) {
									$output .= '<div class="card__meta">Vermogen: ' . $amount_of_pk . ' PK</div>';
								}

							$output .= '</div>';

							if ($price = get_field('price', $item)) {
								$output .= '<div class="card__price">';
								$output .= '<span class="title">€ ' . $price . '</span>';
								$output .= '</div>';
							}

						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';

			}
		}

		$output .= '</div>';

		wp_enqueue_script('custom-popup', get_theme_file_uri('/js/custom-popup.js'), array('jquery'), MFN_THEME_VERSION, true);
		wp_enqueue_style('custom-popup', get_theme_file_uri('/css/custom-popup.css'), false, MFN_THEME_VERSION);

		wp_reset_postdata();

		return $output;
	}
}

/**
 * Image slider [sc_image_slider]
 */

 if (! function_exists('sc_image_slider')) {
	function sc_image_slider($attr, $content = null) {

		$title = get_field('title', get_the_ID());
		$items = get_field('items', get_the_ID());
		$usps = get_field('usps', get_the_ID());

		$output = '<div id="image-slide" class="block">';

			$output .= '<div class="hero js-hero">';
				$output .= '<div class="block__inner">';
					$output .= '<div class="wrap wrap--medium">';

						$output .= '<div class="hero__background">';

							$output .= '<div class="swipe-module swipe-module--vertical js-hero-slider">';
								$output .= '<div class="swiper-wrapper">';

                                    foreach ($items as $item) :

										$output .= '<div class="swiper-slide">';
											$output .= '<figure class="hero__image ratio">';
												$output .= wp_get_attachment_image($item['image'], 'full', false, ['data-object-fit' => 'cover']);
                                            $output .= '</figure>';
                                        $output .= '</div>';

                                    endforeach;
                                $output .= '</div>';

                            $output .= '</div>';
						$output .= '</div>';

					$output .= '<div class="hero__overlay">';

						$output .= '<div class="hero__inner">';

							$output .= '<div class="hero__content js-hero-content">';
								$output .= '<h1 class="hero__title">';
									$output .= $title;
								$output .= '</h1>';

								if (!empty($usps)) :
									$output .= '<div class="hero__labels">';

									foreach ($usps as $usp) :
										$output .= '<div class="hero__label">';
											$output .= $usp['title'];
										$output .= '</div>';
									endforeach;

									$output .= '</div>';
								endif;

							$output .= '</div>';

						$output .= '</div>';
					$output .= '</div>';


					$output .= '<div class="swiper-button-prev">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24.06 11.06"><path d="M24.06 5.53h-23m5-5l-5 5 5 5" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
								</div>';
					$output .= '<div class="swiper-button-next">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24.06 11.06"><path d="M0 5.53h23m-5 5l5-5-5-5" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
								</div>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
	$output .= '</div>';

		wp_enqueue_style('mfn-swiper', get_theme_file_uri('/css/scripts/swiper.css'), false, MFN_THEME_VERSION);
		wp_enqueue_script('mfn-swiper', get_theme_file_uri('/js/swiper.js'), array('jquery'), MFN_THEME_VERSION, true);

		wp_enqueue_script('custom-slider', get_theme_file_uri('/js/custom-slider.js'), array('jquery'), MFN_THEME_VERSION, true);
		wp_enqueue_style('custom-slider', get_theme_file_uri('/css/custom-slider.css'), false, MFN_THEME_VERSION);

		wp_reset_postdata();

		return $output;
	}
}

/**
 * Portfolio [portfolio]
 */

if (! function_exists('sc_portfolio')) {
	function sc_portfolio($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title_tag' 		=> '',
			'count' 				=> 2,
			'category' 			=> '',
			'category_multi'=> '',
			'exclude_id'		=> '',
			'orderby' 			=> 'date',
			'order' 				=> 'DESC',
			'style'					=> 'list',
			'columns'				=> 3,
			'excerpt_hide'	=> '',
			'greyscale'			=> '',
			'filters'				=> '',
			'pagination'		=> '',
			'load_more'			=> '',
			'related'				=> '',
		), $attr));

		// translate

		$translate['all'] = mfn_opts_get('translate') ? mfn_opts_get('translate-item-all', 'All') : __('All', 'betheme');

		// class

		$class = '';

		if ($greyscale) {
			$class .= ' greyscale';
		}

		if (! empty($excerpt_hide) ) {
			$class .= ' excerpt_hide';
		}

		// query args

		$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
		$args = array(
			'post_type' 			=> 'portfolio',
			'posts_per_page' 	=> intval($count, 10),
			'paged' 					=> $paged,
			'orderby' 				=> $orderby,
			'order' 					=> $order,
			'ignore_sticky_posts' => 1,
		);

		// categories

		if ($category_multi = trim($category_multi)) {

			$category_multi = mfn_wpml_term_slug($category_multi, 'portfolio-types', 1);
			$args['portfolio-types'] = $category_multi;

			$category_multi_array = explode(',', str_replace(' ', '', $category_multi));

		} elseif ($category) {

			$category = mfn_wpml_term_slug($category, 'portfolio-types');
			$args['portfolio-types'] = $category;

		}

		// exclude posts

		if ($exclude_id) {
			$exclude_id = str_replace(' ', '', $exclude_id);
			$args['post__not_in'] = explode(',', $exclude_id);
		}

		// related | exclude current

		if( ! empty($related) && !isset( $attr['vb'] ) && empty( $_GET['visual'] ) ) {

			$aCategories = array();

			$terms = get_the_terms(get_the_ID(), 'portfolio-types');
			if (is_array($terms)) {
				foreach ($terms as $term) {
					$aCategories[]	= $term->term_id;
				}
			}

			$args['post__not_in'] = [ get_the_ID() ];

			if( count($aCategories) > 0 ){
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'portfolio-types',
						'field' => 'term_id',
						'terms' => $aCategories
					),
				);
			}

		}

		$link = false;
		if( !empty($attr['portfolio-link']) ) {
			$link = $attr['portfolio-link'];
		}

		if( empty($attr['title_tag']) ) $attr['title_tag'] = 'h5';

		// query

		//$query_portfolio = new WP_Query($args);

		if( is_tax('portfolio-types') ) {
			$query_portfolio = false;
		}else{
			$query_portfolio = new WP_Query($args);
		}

		$qo = get_queried_object();
		$portfolio_page_id = mfn_wpml_ID( mfn_opts_get( 'portfolio-page' ) );

		// output -----

		$output = '<div class="column_filters">';

			// output | filters

			if ($filters && ! $category) {
				$output .= '<div id="Filters" class="isotope-filters filters4portfolio" data-parent="column_filters">';
					$output .= '<div class="mcb-column-inner">';
						$output .= '<div class="filters_wrapper">';
							$output .= '<ul class="categories">';

								$output .= '<li class="reset '. (!isset($qo->term_id) ? 'current-cat' : '' ) .'"><a class="all" data-rel="*" href="'. esc_url(get_page_link($portfolio_page_id)) .'">'. esc_html($translate['all']) .'</a></li>';
								if ($portfolio_categories = get_terms('portfolio-types')) {
									foreach ($portfolio_categories as $category) {
										if ($category_multi) {
											if (in_array($category->slug, $category_multi_array)) {
												$output .= '<li class="'. esc_attr($category->slug) .'"><a data-rel=".category-'. esc_attr($category->slug) .'" href="'. esc_url(get_term_link($category)) .'">'. esc_html($category->name) .'</a></li>';
											}
										} else {
											$output .= '<li class="'. esc_attr($category->slug) .' '.( isset($qo->term_id) && $qo->term_id == $category->term_id ? 'current-cat' : '' ).'"><a data-rel=".category-'. esc_attr($category->slug) .'" href="'. esc_url(get_term_link($category)) .'">'. esc_html($category->name) .'</a></li>';
										}
									}
								}

							$output .= '</ul>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>'."\n";
			}

			// output | main

			$output .= '<div class="portfolio_wrapper isotope_wrapper '. esc_attr($class) .'">';

				$output .= '<ul class="portfolio_group lm_wrapper isotope col-'. intval($columns, 10) .' '. esc_attr($style) .'">';
					$output .= mfn_content_portfolio($query_portfolio, $style, $link, $attr['title_tag']);
				$output .= '</ul>';

				if ($pagination) {
					$output .= mfn_pagination($query_portfolio, $load_more);
				}

			$output .= '</div>'."\n";

		$output .= '</div>'."\n";

		wp_reset_postdata();

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-imagesloaded', get_theme_file_uri('/js/plugins/imagesloaded.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-isotope', get_theme_file_uri('/js/plugins/isotope.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Portfolio Grid [portfolio_grid]
 */

if (! function_exists('sc_portfolio_grid')) {
	function sc_portfolio_grid($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'count'						=> '4',
			'category' 				=> '',
			'category_multi' 	=> '',
			'orderby' 				=> 'date',
			'order' 					=> 'DESC',
			'greyscale' 			=> '',
		), $attr));

		// class

		$class = '';
		if ($greyscale) {
			$class .= ' greyscale';
		}

		// query args

		$args = array(
			'post_type' 			=> 'portfolio',
			'posts_per_page' 	=> intval($count, 10),
			'paged' 					=> -1,
			'orderby' 				=> $orderby,
			'order' 					=> $order,
			'ignore_sticky_posts' => 1,
		);

		// categories

		if ($category_multi = trim($category_multi)) {
			$category_multi = mfn_wpml_term_slug($category_multi, 'portfolio-types', 1);
			$args['portfolio-types'] = $category_multi;
		} elseif ($category) {
			$category = mfn_wpml_term_slug($category, 'portfolio-types');
			$args['portfolio-types'] = $category;
		}

		// query

		$query = new WP_Query();
		$query->query($args);
		$post_count = $query->post_count;

		// output -----

		$output = '';

		if ($query->have_posts()) {
			$output  = '<ul class="portfolio_grid '. esc_attr($class) .'">';
				while ($query->have_posts()) {

					$query->the_post();

					$output .= '<li>';
						$output .= '<div class="image_frame scale-with-grid">';
							$output .= '<div class="image_wrapper">';
								$output .= mfn_post_thumbnail(get_the_ID(), 'portfolio');
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</li>';

				}
			$output .= '</ul>'."\n";
		}
		wp_reset_query();

		return $output;
	}
}

/**
 * Portfolio Photo [portfolio_photo]
 */

if (! function_exists('sc_portfolio_photo')) {
	function sc_portfolio_photo($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title_tag' 			=> '',
			'count' 					=> '5',
			'category' 				=> '',
			'category_multi'	=> '',
			'orderby' 				=> 'date',
			'order' 					=> 'DESC',
			'target' 					=> '',
			'greyscale' 			=> '',
			'margin' 					=> '',
		), $attr));

		// translate

		$translate['readmore'] = mfn_opts_get('translate') ? mfn_opts_get('translate-readmore', 'Read more') : __('Read more', 'betheme');

		// class

		$class = '';
		if ($greyscale) {
			$class .= ' greyscale';
		}
		if ($margin) {
			$class .= ' margin';
		}

		// query args

		$args = array(
			'post_type' 			=> 'portfolio',
			'posts_per_page' 	=> intval($count, 10),
			'paged' 					=> -1,
			'orderby' 				=> $orderby,
			'order' 					=> $order,
			'ignore_sticky_posts' => 1,
		);

		// categories

		if ($category_multi = trim($category_multi)) {
			$category_multi = mfn_wpml_term_slug($category_multi, 'portfolio-types', 1);
			$args['portfolio-types'] = $category_multi;
		} elseif ($category) {
			$category = mfn_wpml_term_slug($category, 'portfolio-types');
			$args['portfolio-types'] = $category;
		}

		// target

		if ($target) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// title tag

		$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h3';

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = ' lead';
			}
		}

		// query

		$query = new WP_Query();
		$query->query($args);

		// output -----

		$output = '';

		if ($query->have_posts()) {
			$output  = '<div class="portfolio-photo '. esc_attr($class) .'">';
				while ($query->have_posts()) {

					$query->the_post();

					// external link to project page

					if (get_post_meta(get_the_ID(), 'mfn-post-link', true)) {
						$link = get_post_meta(get_the_ID(), 'mfn-post-link', true);
					} else {
						$link = get_permalink();
					}

					// portfolio categories

					$terms = get_the_terms(get_the_ID(), 'portfolio-types');
					$categories = array();
					if (is_array($terms)) {
						foreach ($terms as $term) {
							$categories[] = $term->name;
						}
					}
					$categories = implode(', ', $categories);

					// image
					$large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');

					$output .= '<div class="portfolio-item">';

						// This variable has been safely escaped above in this function
						$output .= '<a class="portfolio-item-bg" href="'. esc_url($link) .'" '. $target_escaped .'>';
							$output .= get_the_post_thumbnail(get_the_ID(), 'full');
							$output .= '<div class="mask"></div>';
						$output .= '</a>';

						// This variable has been safely escaped above in this function
						$output .= '<a class="portfolio-details" href="'. esc_url($link) .'" '. $target_escaped .'>';

							$output .= '<div class="details">';
								$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. get_the_title() .'</'. mfn_allowed_title_tag($title_tag) .'>';
								if ($categories) {
									$output .= '<div class="categories">'. esc_html($categories) .'</div>';
								}
							$output .= '</div>';

							$output .= '<span class="more"><h4>'. esc_html($translate['readmore']) .'</h4></span>';

						$output .= '</a>';

					$output .= '</div>';

				}
			$output .= '</div>'."\n";
		}
		wp_reset_query();

		return $output;
	}
}

/**
 * Portfolio Slider [portfolio_slider]
 */

if (! function_exists('sc_portfolio_slider')) {
	function sc_portfolio_slider($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'count' 				=> '5',

			'category' 			=> '',
			'category_multi'=> '',
			'orderby' 			=> 'date',
			'order' 				=> 'DESC',

			'arrows'				=> '',			// [default], hover, always
			'size'					=> 'small',	// small, medium, large
			'scroll'				=> 'page',	// page, slide
		), $attr));

		// image

		$sizes = array(
			'small'		=> 380,
			'medium'	=> 480,
			'large'		=> 638,
		);

		// slider scroll type

		$scrolls = array(
			'page'		=> 5,
			'slide'		=> 1,
		);

		// class

		$class = '';
		if ($arrows) {
			$class .= ' arrows arrows_' .$arrows;
		}

		// query args

		$args = array(
			'post_type' 			=> 'portfolio',
			'posts_per_page' 	=> intval($count, 10),
			'paged' 					=> -1,
			'orderby' 				=> $orderby,
			'order' 					=> $order,
			'ignore_sticky_posts' => 1,
		);

		// categories

		if ($category_multi = trim($category_multi)) {
			$category_multi = mfn_wpml_term_slug($category_multi, 'portfolio-types', 1);
			$args['portfolio-types'] = $category_multi;
		} elseif ($category) {
			$category = mfn_wpml_term_slug($category, 'portfolio-types');
			$args['portfolio-types'] = $category;
		}

		// query

		$query = new WP_Query();
		$query->query($args);

		// output ------

		$output = '';

		if ($query->have_posts()) {
			$output  = '<div class="portfolio_slider'. esc_attr($class) .'" data-size="'. esc_attr($sizes[ $size ]) .'" data-scroll="'. esc_attr($scrolls[ $scroll ]) .'">';
				$output .= '<ul class="portfolio_slider_ul">';
					while ($query->have_posts()) {

						$query->the_post();

						$output .= '<li>';
							$output .= '<div class="image_frame scale-with-grid">';
								$output .= '<div class="image_wrapper">';
									$output .= mfn_post_thumbnail(get_the_ID(), 'portfolio');
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</li>';

					}
				$output .= '</ul>';
			$output .= '</div>'."\n";
		}
		wp_reset_query();

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-slick', get_theme_file_uri('/js/plugins/slick.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Slider [slider]
 */

if (! function_exists('sc_slider')) {
	function sc_slider($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title_tag' 	=> '',

			'category' 		=> '',
			'orderby' 		=> 'date',
			'order' 			=> 'DESC',

			'style' 			=> 'default',	// [default], img-text, flat, carousel
			'navigation'	=> '',	// [default], hide-arrows, hide-dots, hide
		), $attr));

		// query args

		$args = array(
			'post_type' 			=> 'slide',
			'posts_per_page' 	=> -1,
			'paged' 					=> -1,
			'orderby' 				=> $orderby,
			'order' 					=> $order,
			'ignore_sticky_posts' 	=> 1,
		);

		if ($category) {
			$args['slide-types'] = $category;
		}

		// query

		$query = new WP_Query();
		$query->query($args);
		$post_count = $query->post_count;

		// class

		$class = !empty($style) != '' ? $style : 'default';

		if ($class == 'description') {
			$class .= ' flat';
		}

		if ($navigation) {
			if ($navigation == 'hide') {
				$navigation = 'hide-arrows hide-dots';
			}
			$class .= ' '. $navigation;
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '';
		if ($query->have_posts()) {
			$output .= '<div class="content_slider '. esc_attr($class) .'">';
				$output .= '<ul class="content_slider_ul">';

					$i = 0;

					while ($query->have_posts()) {

						$query->the_post();
						$i++;

						$output .= '<li class="content_slider_li_'. esc_attr($i) .'">';

							$link = get_post_meta(get_the_ID(), 'mfn-post-link', true);

							// target

							$target = get_post_meta(get_the_ID(), 'mfn-post-target', true);

							if ( 'lightbox' === $target ) {
								$target_escaped = 'rel="prettyphoto"';
							} elseif ( $target ) {
								$target_escaped = 'target="_blank"';
							} else {
								$target_escaped = false;
							}

							// echo

							if ($link) {
								// This variable has been safely escaped above in this function
								$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .'>';
							}

								$output .= get_the_post_thumbnail(null, 'slider-content', array('class'=>'scale-with-grid' ));

								if ($style == 'carousel') {
									$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'p';
									$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. esc_html(get_the_title(get_the_ID())) .'</'. mfn_allowed_title_tag($title_tag) .'>';
								}

								if ($style == 'description') {
									$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h3';
									$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. get_the_title(get_the_ID()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
									if ($desc = get_post_meta(get_the_ID(), 'mfn-post-desc', true)) {
										$output .= '<div class="desc">'. do_shortcode($desc ?? '') .'</div>';
									}
								}

							if ($link) {
								$output .= '</a>';
							}

						$output .= '</li>';
					}

				$output .= '</ul>';

				$output .= '<div class="slider_pager slider_pagination"></div>';

			$output .= '</div>'."\n";
		}
		wp_reset_query();

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-slick', get_theme_file_uri('/js/plugins/slick.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Slider Plugin [slider_plugin]
 */

if (! function_exists('sc_slider_plugin')) {
	function sc_slider_plugin($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'rev' 		=> '',
			'layer' 	=> '',
		), $attr));

		// output -----

		$output = '';

		if ($rev) {
			// Revolution Slider

			$output .= '<div class="mfn-main-slider mfn-rev-slider">';
				$output .= do_shortcode('[rev_slider '. esc_attr($rev) .']' ?? '');
			$output .= '</div>';

		} elseif ($layer) {
			// Layer Slider

			$output .= '<div class="mfn-main-slider mfn-layer-slider">';
				$output .= do_shortcode('[layerslider id="'. esc_attr($layer) .'"]' ?? '');
			$output .= '</div>';
		}

		return $output;
	}
}

/**
 * Offer Slider Full [offer]
 */

if (! function_exists('sc_offer')) {
	function sc_offer($attr = false, $content = null)
	{
		extract(shortcode_atts(array(
			'category' 	=> '',
			'align' 	=> 'left',
		), $attr));

		// query args

		$args = array(
			'post_type'				=> 'offer',
			'posts_per_page'	=> -1,
			'orderby'					=> 'menu_order',
			'order'						=> 'ASC',
			'ignore_sticky_posts'	=> 1,
		);

		if ($category) {
			$args['offer-types'] = $category;
		}

		$args = apply_filters('mfn_element_offer', $args, $attr);

		$offer_query = new WP_Query();
		$offer_query->query($args);

		// output -----

		$output = '';
		if ($offer_query->have_posts()) {
			$output .= '<div class="offer">';

				$output .= '<ul class="offer_ul">';

					while ($offer_query->have_posts()) {
						$offer_query->the_post();
						$output .= '<li class="offer_li">';

							// link

							if ($link = get_post_meta(get_the_ID(), 'mfn-post-link', true)) {
								$class = 'has-link';
							} else {
								$class = 'no-link';
							}

							// target

							if (get_post_meta(get_the_ID(), 'mfn-post-target', true)) {
								$target_escaped = 'target="_blank"';
							} else {
								$target_escaped = false;
							}

							$output .= '<div class="image_wrapper">';
								$output .= get_the_post_thumbnail(get_the_ID(), 'full', array('class'=>'scale-with-grid' ));
							$output .= '</div>';

							$output .= '<div class="desc_wrapper align_'. esc_attr($align) .' '. esc_attr($class) .'">';

								if (trim(get_the_title()) || $link) {
									$output .= '<div class="title">';
										$output .= '<h3>'. wp_kses(get_the_title(), mfn_allowed_html()) .'</h3>';
										if ($link) {
											// This variable has been safely escaped above in this function
											$output .= '<a href="'. esc_url($link) .'" class="button has-icon" '. $target_escaped .'>';
												$output .= '<span class="button_icon"><i class="icon-layout" aria-hidden="true"></i></span>';
												$output .= '<span class="button_label">'. esc_html(get_post_meta(get_the_ID(), 'mfn-post-link_title', true)) .'</span>';
											$output .= '</a>';
										}
									$output .= '</div>';
								}

								$output .= '<div class="desc">';
									$output .= apply_filters('the_content', get_the_content());
								$output .= '</div>';

							$output .= '</div>';

						$output .= '</li>';
					}

				$output .= '</ul>';

				// pagination
				$output .= '<div class="slider_pagination"><span class="current">1</span> / <span class="count">'. intval($offer_query->post_count, 10) .'</span></div>';

			$output .= '</div>'."\n";
		}
		wp_reset_query();

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-slick', get_theme_file_uri('/js/plugins/slick.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Offer Slider Thumb [offer_thumb]
 */

if (! function_exists('sc_offer_thumb')) {
	function sc_offer_thumb($attr = false, $content = null)
	{
		extract(shortcode_atts(array(
			'category' 	=> '',
			'style' 		=> '',
			'align' 		=> 'left',
		), $attr));

		// query args

		$args = array(
			'post_type'				=> 'offer',
			'posts_per_page'	=> -1,
			'orderby'					=> 'menu_order',
			'order'						=> 'ASC',
			'ignore_sticky_posts'	=> 1,
		);

		if ($category) {
			$args['offer-types'] = $category;
		}

		$args = apply_filters('mfn_element_offer_thumb', $args, $attr);

		$offer_query = new WP_Query();
		$offer_query->query($args);

		// output -----

		$output = '';
		if ($offer_query->have_posts()) {
			$output .= '<div class="offer_thumb '. esc_attr($style) .'">';

				$output .= '<ul class="offer_thumb_ul">';
					$i = 1;

					while ($offer_query->have_posts()) {
						$offer_query->the_post();

						$output .= '<li class="offer_thumb_li id_'. esc_attr($i) .'">';

							// the ID
							$id = get_the_ID();

							// link
							if ($link = get_post_meta($id, 'mfn-post-link', true)) {
								$class = 'has-link';
							} else {
								$class = 'no-link';
							}

							// target
							if (get_post_meta($id, 'mfn-post-target', true)) {
								$target_escaped = 'target="_blank"';
							} else {
								$target_escaped = false;
							}

							$output .= '<div class="image_wrapper">';
								$output .= get_the_post_thumbnail($id, 'full', array( 'class' => 'scale-with-grid' ));
							$output .= '</div>';

							$output .= '<div class="desc_wrapper align_'. esc_attr($align) .' '. esc_attr($class) .'">';

								if (trim(get_the_title()) || $link) {
									$output .= '<div class="title">';
										$output .= '<h3>'. wp_kses(get_the_title(), mfn_allowed_html()) .'</h3>';
										if ($link) {
											// This variable has been safely escaped above in this function
											$output .= '<a href="'. esc_url($link) .'" class="button has-icon" '. $target_escaped .'>';
												$output .= '<span class="button_icon"><i class="icon-layout" aria-hidden="true"></i></span>';
												$output .= '<span class="button_label">'. esc_html(get_post_meta(get_the_ID(), 'mfn-post-link_title', true)) .'</span>';
											$output .= '</a>';
										}
									$output .= '</div>';
								}

								$output .= '<div class="desc">';
									$output .=  apply_filters('the_content', get_the_content());
								$output .= '</div>';

							$output .= '</div>';

							$output .= '<div class="thumbnail" style="display:none">';

								if ($thumbnail = get_post_meta($id, 'mfn-post-thumbnail', true)) {
									$output .= '<img src="'. esc_url($thumbnail) .'" class="scale-with-grid" alt="thumbnail" />';
								} elseif (get_the_post_thumbnail($id)) {
									$output .= get_the_post_thumbnail($id, 'be_thumbnail', array( 'class' => 'scale-with-grid' ));
								}

							$output .= '</div>';

						$output .= '</li>';

						$i++;
					}

				$output .= '</ul>';

				// pagination
				$output .= '<div class="slider_pagination"></div>';

			$output .= '</div>'."\n";
		}
		wp_reset_query();

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-slick', get_theme_file_uri('/js/plugins/slick.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Map Basic [map_basic]
 */

if (! function_exists('sc_map_basic')) {
	function sc_map_basic($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'iframe' 	=> '',

			'address' => '',
			'zoom' 		=> 13,
			'height' 	=> 300,
		), $attr));

		// output -----

		$output = '';

		if( $iframe == '' && $address == '' ){
			return '<img src="'.get_theme_file_uri( '/muffin-options/svg/placeholders/map.svg' ).'" alt="">';
		}


		if ($iframe) {
			// iframe

			$output = wp_kses($iframe, array(
				'iframe' => array(
					'src' => array(),
					'width' => array(),
					'height' => array(),
					'frameborder' => array(),
					'style' => array(),
					'allowfullscreen' => array(),
				),
			));

		} elseif ($address) {
			// embed

			$address = str_replace(array( ' ' ,',' ), '+', trim($address));
			$api_key = trim(mfn_opts_get('google-maps-api-key'));
			$src = 'https://www.google.com/maps/embed/v1/place?key='. $api_key .'&q='. $address .'&zoom='. $zoom ;

			$output = '<iframe class="embed" width="100%" height="'. esc_attr($height) .'" frameborder="0" style="border:0" src="'. esc_url($src) .'" allowfullscreen></iframe>';
		}

		return $output;
	}
}

/**
 * Map [map] [/map]
 */

if (! function_exists('sc_map')) {
	function sc_map($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'lat' 		=> '',
			'lng' 		=> '',
			'zoom' 		=> 13,
			'height' 	=> '',
			'info_window' 	=> '',

			'type' 			=> 'ROADMAP',
			'controls' 	=> '',
			'draggable' => '',
			'border' 		=> '',

			'icon' 		=> '',
			'color'		=> '',
			'styles'	=> '',
			'tabs' 		=> '',
			'latlng' 	=> '',

			'title'			=> '',
			'telephone'	=> '',
			'email' 		=> '',
			'www' 			=> '',
			'style' 		=> 'box',

			'uid' 		=> uniqid(),
		), $attr));



		//if ( /*apply_filters('bebuilder_preview', false)*/  ) {

		if ( !empty( $attr['vb'] ) && empty( mfn_opts_get('google-maps-api-key') ) ) {
			return '<div class="mfn-widget-placeholder mfn-wp-product-reviews"><img class="item-preview-image" src="'.get_theme_file_uri('/visual-builder/assets/_dark/svg/items/map.svg').'" alt="product rating"></div>';
		}

		// image | visual composer fix

		$icon = mfn_vc_image($icon);

		// border

		if ($border) {
			$class = 'has_border';
		} else {
			$class = 'no_border';
		}

		// controls

		$zoomControl = $mapTypeControl = $streetViewControl = 'false';
		if (! $controls) {
			$zoomControl = 'true';
		}
		if (strpos($controls, 'zoom') !== false) {
			$zoomControl = 'true';
		}
		if (strpos($controls, 'mapType') !== false) {
			$mapTypeControl = 'true';
		}
		if (strpos($controls, 'streetView') !== false) {
			$streetViewControl = 'true';
		}

		$fun_name = 'mfn_google_maps_'. esc_attr($uid);


		if ($api_key = trim(mfn_opts_get('google-maps-api-key'))) {
			$api_key = '?key='. $api_key;
		}

		// output -----

		if( ! wp_script_is( 'google-maps', 'enqueued') ){
			wp_enqueue_script('google-maps', 'https://maps.google.com/maps/api/js'. $api_key .'&callback=mfnInitMap', false, null, true);
			wp_add_inline_script('mfn-scripts', 'function mfnInitMap() { return false; }');

			if( is_admin() ){
				wp_add_inline_script('jquery-core', 'function mfnInitMap() { return false; }');
			}
		}

		// output: JS

		$inline_script = 'function mfn_google_maps_'.$uid.'() {';

		  $inline_script .= 'var latlng = new google.maps.LatLng('. esc_attr($lat) .','. esc_attr($lng) .');';

		  // draggable

		  if ($draggable == 'disable') {
		    $inline_script .= 'var draggable = false;';
		  } elseif ($draggable == 'disable-mobile') {
		    $inline_script .= 'var draggable = jQuery(document).width() > 767 ? true : false;';
		  } else {
		    $inline_script .= 'var draggable = true;';
		  }

		  // function to close all other info windows
		  $inline_script .= 'var InforObj = [];';
		  $inline_script .= 'function closeOtherInfo() {';
                $inline_script .= 'if (InforObj.length > 0) {';
                	$inline_script .= 'InforObj[0].set("marker", null);';
                    $inline_script .= 'InforObj[0].close();';
                    $inline_script .= 'InforObj.length = 0;';
                $inline_script .= '}';
            $inline_script .= '};';

		  // 1 click color adjustment

		  if ($color && ! $styles) {

		    if ( 'light' == mfn_brightness($color) ) {

		      $styles = '[{featureType:"all",elementType:"labels",stylers:[{visibility:"on"}]},{featureType:"administrative",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"landscape",elementType:"all",stylers:[{color:"'. esc_attr($color) .'"},{visibility:"simplified"}]},{featureType:"poi",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"road",elementType:"all",stylers:[{visibility:"on"}]},{featureType:"road",elementType:"geometry",stylers:[{color:"'. esc_attr($color) .'"},{lightness:"50"}]},{featureType:"road",elementType:"labels.text",stylers:[{visibility:"on"}]},{featureType:"road",elementType:"labels.text.fill",stylers:[{color:"'. esc_attr($color) .'"},{lightness:"-60"}]},{featureType:"road",elementType:"labels.text.stroke",stylers:[{color:"'. esc_attr($color) .'"},{lightness:"50"}]},{featureType:"road",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"all",stylers:[{visibility:"simplified"},{color:"'. esc_attr($color) .'"},{lightness:"50"}]},{featureType:"transit.station",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"water",elementType:"all",stylers:[{color:"'. esc_attr($color) .'"},{lightness:"-10"}]}]';

		    } else {

		      $styles = '[{featureType:"all",elementType:"labels",stylers:[{visibility:"on"}]},{featureType:"administrative",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"landscape",elementType:"all",stylers:[{color:"'. esc_attr($color) .'"},{visibility:"simplified"}]},{featureType:"poi",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"road",elementType:"all",stylers:[{visibility:"on"}]},{featureType:"road",elementType:"geometry",stylers:[{color:"'. esc_attr($color) .'"},{lightness:"30"},{saturation:"-10"}]},{featureType:"road",elementType:"labels.text",stylers:[{visibility:"on"}]},{featureType:"road",elementType:"labels.text.fill",stylers:[{color:"'. esc_attr($color) .'"},{lightness:"80"}]},{featureType:"road",elementType:"labels.text.stroke",stylers:[{color:"'. esc_attr($color) .'"},{lightness:"0"}]},{featureType:"road",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"all",stylers:[{visibility:"simplified"},{color:"'. esc_attr($color) .'"},{lightness:"50"}]},{featureType:"transit.station",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"water",elementType:"all",stylers:[{color:"'. esc_attr($color) .'"},{lightness:"-20"}]}]';

		    }
		  }

		  $inline_script .= 'var myOptions = {';
		    $inline_script .= 'zoom:'. intval($zoom, 10) .',';
		    $inline_script .= 'center:latlng,';
		    $inline_script .= 'mapTypeId:google.maps.MapTypeId.'. esc_attr($type) .',';
		    if ($styles) {
		      $inline_script .= 'styles:'. wp_unslash($styles) .',';
		    }
		    $inline_script .= 'draggable:draggable,';
		    $inline_script .= 'zoomControl:'. esc_attr($zoomControl) .',';
		    $inline_script .= 'mapTypeControl:'. esc_attr($mapTypeControl) .',';
		    $inline_script .= 'streetViewControl:'. esc_attr($streetViewControl) .',';
		    $inline_script .= 'scrollwheel:false';
		  $inline_script .= '};';

		  	$inline_script .= 'var map = new google.maps.Map(document.getElementById("google-map-area-'. esc_attr($uid) .'"), myOptions);';

		  $inline_script .= 'var marker = new google.maps.Marker({';
		    $inline_script .= 'position:latlng,';
		    if ($icon) {
		      $inline_script .= 'icon:"'. esc_url($icon) .'",';
		    }
		    $inline_script .= 'map:map';
		  $inline_script .= '});';

			// display Info Window on main marker click
		  if($info_window) {
			  $inline_script .= 'const infowindow = new google.maps.InfoWindow({';
					/*if( ( isset($attr['vb']) && $attr['vb'] ) || ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ){
						$inline_script .= 'content: "'.str_replace("\n", '<br />', $info_window).'",';
					} else {
						$inline_script .= 'content: "'.addslashes( str_replace("\n", '<br />', $info_window) ).'",';
					}*/

					$inline_script .= 'content: "'.str_replace("\n", '<br />', wp_slash($info_window)).'",';

				  $inline_script .= '});';

				  $inline_script .= 'marker.addListener("click", () => {';
				  	$inline_script .= 'closeOtherInfo();';
		    		$inline_script .= 'infowindow.open({
				      anchor: marker,
				      map,
				      shouldFocus: false,
				    });';
				    $inline_script .= 'InforObj[0] = infowindow;';
		    	  $inline_script .= '});';


		    }

		  // additional markers

			if ( $tabs ) {

				$i = 0;

				foreach( $tabs as $tab ){

					$markerID = $i;
		      $markerID = 'marker'. $markerID;

					if ( ! empty( $tab['lat'] ) && ! empty( $tab['lng'] ) ){

						if ( ! empty( $tab['icon'] ) ) {
			        $customIcon = $tab['icon'];
			      } else {
			        $customIcon = $icon;
			      }

						$inline_script .= 'var '. esc_attr( $markerID ) .' = new google.maps.Marker({';
			        $inline_script .= 'position : new google.maps.LatLng('. esc_attr( $tab['lat'] ) .','. esc_attr( $tab['lng'] ) .'),';
			        if ($customIcon) {
			          $inline_script .= 'icon: "'. esc_url( $customIcon ) .'",';
			        }
			        $inline_script .= 'map : map';
			      $inline_script .= '});';

					}

					if(!empty($tab['content'])){
						// display Info Window on additional markers click
			      $inline_script .= 'const infowindow'.$i.' = new google.maps.InfoWindow({';
						if( ( isset($attr['vb']) && $attr['vb'] ) || wp_doing_ajax() ){
							$inline_script .= 'content: "'.wp_slash( str_replace("\n", '<br />', $tab['content']) ).'",';
						} else {
							$inline_script .= 'content: "'.addslashes( str_replace("\n", '<br />', $tab['content']) ).'",';
						}

				  $inline_script .= '});';

				  $inline_script .= $markerID.'.addListener("click", () => {';
				  	$inline_script .= 'closeOtherInfo();';
		    		$inline_script .= 'infowindow'.$i.'.open({';
				      $inline_script .= 'anchor: marker'.$i.',';
				      $inline_script .= 'map,';
				      $inline_script .= 'shouldFocus: false,';
				    $inline_script .= '});';
				    $inline_script .= 'InforObj[0] = infowindow'.$i.';';
		    	  $inline_script .= '});';
				}

					$i++;

				}

			} elseif ( $latlng ) {

		    // remove white spaces

		    $latlng = str_replace(' ', '', $latlng);

		    // explode array

		    $latlng = explode(';', $latlng);

		    foreach ($latlng as $k=>$v) {
		      $markerID = $k + 1;
		      $markerID = 'marker'. $markerID;

		      // custom marker icon

		      $vEx = explode(',', $v);
		      if (isset($vEx[2])) {
		        $customIcon = $vEx[2];
		      } else {
		        $customIcon = $icon;
		      }

		      $inline_script .= 'var '. esc_attr($markerID) .' = new google.maps.Marker({';
		        $inline_script .= 'position : new google.maps.LatLng('. esc_attr($vEx[0]) .','. esc_attr($vEx[1]) .'),';
		        if ($customIcon) {
		          $inline_script .= 'icon: "'. esc_url($customIcon) .'",';
		        }
		        $inline_script .= 'map : map';
		      $inline_script .= '});';
		    }
		  }

		$inline_script .= '}';

		/*if ( ( isset($attr['vb']) && $attr['vb'] ) || ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ){
			$inline_script .= 'mfn_google_maps_'. esc_attr($uid) .'();';
		}else{
			$inline_script .= 'jQuery(document).ready(function(){';
			  $inline_script .= 'mfn_google_maps_'. esc_attr($uid) .'();';
			$inline_script .= '});';
		}*/


		// output: HTML

		$output = '<div class="google-map-wrapper '. esc_attr($class) .'">';

			if ($title || $content) {
				$output .= '<div class="google-map-contact-wrapper style-'. esc_attr($style) .'">';
					$output .= '<div class="get_in_touch">';

						if ($title) {
							$output .= '<h3>'. wp_kses($title, mfn_allowed_html()) .'</h3>';
						}

						$output .= '<div class="get_in_touch_wrapper">';
							$output .= '<ul>';
								if ($content) {
									$output .= '<li class="address">';
										$output .= '<span class="icon"><i class="icon-location"></i></span>';
										$output .= '<span class="address_wrapper">'. do_shortcode($content ?? '') .'</span>';
									$output .= '</li>';
								}
								if ($telephone) {
									$output .= '<li class="phone">';
										$output .= '<span class="icon"><i class="icon-phone"></i></span>';
										$output .= '<p><a href="tel:'. esc_attr(str_replace(' ', '', $telephone)) .'">'. esc_html($telephone) .'</a></p>';
									$output .= '</li>';
								}
								if ($email) {
									$output .= '<li class="mail">';
										$output .= '<span class="icon"><i class="icon-mail"></i></span>';
										$output .= '<p><a href="mailto:'. esc_attr($email) .'">'. esc_html($email) .'</a></p>';
									$output .= '</li>';
								}
								if ($www) {
									$output .= '<li class="www">';
										$output .= '<span class="icon"><svg viewBox="0 0 26 26"><defs><style>.path{fill:none;stroke:#333;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><g><path d="M10.17,8.76l2.12-2.12a5,5,0,0,1,7.07,0h0a5,5,0,0,1,0,7.07l-2.12,2.12" class="path"></path><path d="M15.83,17.24l-2.12,2.12a5,5,0,0,1-7.07,0h0a5,5,0,0,1,0-7.07l2.12-2.12" class="path"></path><line x1="10.17" y1="15.83" x2="15.83" y2="10.17" class="path"></line></g></svg></span>';
										$output .= '<p><a target="_blank" href="http'. mfn_ssl() .'://'. esc_attr($www) .'">'. esc_html($www) .'</a></p>';
									$output .= '</li>';
								}
							$output .= '</ul>';
						$output .= '</div>';

					$output .= '</div>';
				$output .= '</div>';
			}

			$output .= '<div class="google-map" id="google-map-area-'. esc_attr($uid) .'" style="width:100%; '. ( !empty($height) ? 'height:'. intval($height, 10) .'px; ' : null ) .'">&nbsp;</div>';

		$output .= '</div>'."\n";

		if ( ( isset($attr['vb']) && $attr['vb'] ) || wp_doing_ajax() ){

			//wp_add_inline_script('google-maps', $inline_script);
			// wp_enqueue_script('google-maps-'.esc_attr($uid), 'https://maps.google.com/maps/api/js'. $api_key, false, null, true);

			$inline_script .= 'mfn_google_maps_'. esc_attr($uid) .'();';
			$output .= '<script>'.wp_slash($inline_script).'</script>';

			return $output;
		} else {

			$inline_script .= 'jQuery(document).ready(function(){';
			  $inline_script .= 'mfn_google_maps_'. esc_attr($uid) .'();';
			$inline_script .= '});';

			wp_add_inline_script('google-maps', $inline_script);
			return $output;
		}

	}
}

/**
 * Tabs [tabs]
 */

$mfn_tabs_array = false;
$mfn_tabs_count = 0;

if (! function_exists('sc_tabs')) {
	function sc_tabs($attr, $content = null)
	{
		global $mfn_tabs_array, $mfn_tabs_count;

		extract(shortcode_atts(array(
			'title'		=> '',
			'title_tag'		=> '',
			'tabs'		=> '',
			'type'		=> '',
			'padding'	=> '',
			'uid'			=> '',
		), $attr));

		$title = be_dynamic_data($title);

		do_shortcode($content ?? '');

		// content builder

		if ($tabs) {
			$mfn_tabs_array = $tabs;
		}

		// uid

		if (! $uid) {
			$uid = 'tab-'. uniqid();
		}

		// padding

		if( is_numeric( $padding ) ){
			$padding .= 'px';
		}

		if ($padding || $padding === '0') {
			$style_escaped = 'style="padding:'. esc_attr($padding) .'"';
		} else {
			$style_escaped = false;
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '';

		if ( is_array( $mfn_tabs_array ) ) {

			if ($title) {
				$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
				$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
			}

			$output .= '<div class="jq-tabs tabs_wrapper tabs_'. esc_attr($type) .'">';

				$output .= '<ul>';

					$i = 1;
					$output_tabs = '';

					foreach ( $mfn_tabs_array as $tab ) {

						$tab['content'] = be_dynamic_data($tab['content']);
						$tab['title'] = be_dynamic_data($tab['title']);

						// check if content contains [tab]|[tabs]
						$pattern = '/\[tab(\]|s| )/';
						if (preg_match($pattern, $tab['content'])) {
							$tab['content'] = __('Tab shortcode does not work inside another tab.', 'betheme');
						}

						$output .= '<li><a href="#'. esc_attr($uid) .'-'. esc_attr($i) .'">'. wp_kses($tab['title'], mfn_allowed_html()) .'</a></li>';

						// This variable has been safely escaped above in this function
						$output_tabs .= '<div id="'. esc_attr($uid) .'-'. esc_attr($i) .'" '. $style_escaped .'>'. do_shortcode($tab['content'] ?? '') .'</div>';

						$i++;
					}

				$output .= '</ul>';

				// titles

				$output .= $output_tabs;

			$output .= '</div>';

			$mfn_tabs_array = false;
			$mfn_tabs_count = 0;
		}

		return $output;
	}
}

/**
 * _Tab [tab] _private
 */

if (! function_exists('sc_tab')) {
	function sc_tab($attr, $content = null)
	{
		global $mfn_tabs_array, $mfn_tabs_count;

		extract(shortcode_atts(array(
			'title' => 'Tab title',
		), $attr));

		if ( ! is_array( $mfn_tabs_array ) ) {
			$mfn_tabs_array = array();
		}

		$mfn_tabs_array[] = array(
			'title' 	=> $title,
			'content' => do_shortcode($content ?? '')
		);

		$mfn_tabs_count++;

		return true;
	}
}

/**
 * Accordion [accordion][accordion_item]...[/accordion]
 */

if (! function_exists('sc_accordion')) {
	function sc_accordion($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' 	=> '',
			'title_tag' 	=> '',
			'tabs' 		=> '',
			'icon' 		=> '',
			'icon_active' => '',
			'open1st' => '',
			'openall' => '',
			'openAll' => '',
			'style' 	=> 'accordion',
		), $attr));

		// class

		$class = '';

		if ($open1st) {
			$class .= ' open1st';
		}

		if ($openall || $openAll) {
			$class .= ' openAll';
		}

		if ($style == 'toggle') {
			$class .= ' toggle';
		}

		$title = be_dynamic_data($title);

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output ------

		$output = '<div class="accordion">';

			if ($title) {
				$title_tag = !empty($attr['title_tag']) ? $attr['title_tag'] : 'h4';
				$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title heading '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
			}

			$output .= '<div class="mfn-acc accordion_wrapper '. esc_attr($class) .'">';

				if (is_array($tabs)) {
					// content builder
					foreach ($tabs as $tab) {
						$tab['content'] = be_dynamic_data($tab['content']);
						$output .= '<div class="question" tabindex="0" role="link">';
							$output .= '<div class="title"><i class="'. (!empty($icon) ? $icon : 'icon-plus') .' acc-icon-plus" aria-hidden="true"></i><i class="'. (!empty($icon_active) ? $icon_active : 'icon-minus') .' acc-icon-minus" aria-hidden="true"></i>'. wp_kses($tab['title'], mfn_allowed_html()) .'</div>';
							$output .= '<div class="answer">';
								$output .= do_shortcode($tab['content'] ?? '');
							$output .= '</div>';
						$output .= '</div>'."\n";
					}
				} else {
					// shortcode
					$output .= do_shortcode($content ?? '');
				}

			$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Accordion Item [accordion_item][/accordion_item]
 */

if (! function_exists('sc_accordion_item')) {
	function sc_accordion_item($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' 	=> '',
		), $attr));

		$output = '<div class="question">';

			$output .= '<div class="title">';
				$output .= '<i class="icon-plus acc-icon-plus" aria-hidden="true"></i>';
				$output .= '<i class="icon-minus acc-icon-minus" aria-hidden="true"></i>';
				$output .= wp_kses($title, mfn_allowed_html());
			$output .= '</div>';

			$output .= '<div class="answer">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * FAQ [faq][faq_item]../[/faq]
 */

if (! function_exists('sc_faq')) {
	function sc_faq($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' 	=> '',
			'title_tag' 	=> '',
			'tabs' 		=> '',
			'open1st' => '',
			'openall' => '',
			'openAll' => '',
			'style' 	=> '',
		), $attr));

		$title = be_dynamic_data($title);

		// class

		$class = '';

		if ($open1st) {
			$class .= ' open1st';
		}

		if ($openall || $openAll) {
			$class .= ' openAll';
		}

		if ( 'toggle' == $style ) {
			$class .= ' toggle';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="faq">';

			if ($title) {
				$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
				$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="heading '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
			}
			$output .= '<div class="mfn-acc faq_wrapper '. esc_attr($class) .'">';

				if (is_array($tabs)) {

					// content builder
					$i = 0;

					foreach ($tabs as $tab) {

						$tab['content'] = be_dynamic_data($tab['content']);
						$tab['title'] = be_dynamic_data($tab['title']);

						$i++;

						$output .= '<div class="question" tabindex="0" role="link">';

							$output .= '<div class="title">';
								$output .= '<span class="num">'. esc_html($i) .'</span>';
								$output .= '<i class="icon-plus acc-icon-plus" aria-hidden="true"></i>';
								$output .= '<i class="icon-minus acc-icon-minus" aria-hidden="true"></i>';
								$output .= wp_kses($tab['title'], mfn_allowed_html());
							$output .= '</div>';

							$output .= '<div class="answer">';
								$output .= do_shortcode($tab['content'] ?? '');
							$output .= '</div>';

						$output .= '</div>'."\n";
					}

				} else {

					// shortcode
					$output .= do_shortcode($content ?? '');

				}

			$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * FAQ Item [faq_item][/faq_item]
 */

if (! function_exists('sc_faq_item')) {
	function sc_faq_item($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' 	=> '',
			'number' 	=> '1',
		), $attr));

		// output

		$output = '<div class="question">';

			$output .= '<div class="title">';
				$output .= '<span class="num">'. esc_html($number) .'</span>';
				$output .= '<i class="icon-plus acc-icon-plus" aria-hidden="true"></i>';
				$output .= '<i class="icon-minus acc-icon-minus" aria-hidden="true"></i>';
				$output .= wp_kses($title, mfn_allowed_html());
			$output .= '</div>';

			$output .= '<div class="answer">';
				$output .= do_shortcode($content ?? '');
			$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Progress Icons [progress_icons]
 */

if (! function_exists('sc_progress_icons')) {
	function sc_progress_icons($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'icon' 				=> 'icon-lamp',
			'image' 			=> '',
			'count' 			=> 5,
			'active' 			=> 0,
			'background' 	=> '', // or color for transparent

			'transparent' => false,
		), $attr));

		// class

		$class = '';

		// transparent

		if( ! empty($transparent) ){
			$class .= ' transparent';
		}

		// icon

		$icon_class = false;
		$icon_style = false;

		// bebuilder element update

		if( wp_doing_ajax() ){
			$icon_class = 'themebg';
			if( empty($transparent) ){
				$icon_style = 'background-color:'. esc_attr($background);
			} else {
				$icon_style = 'color:'. esc_attr($background);
			}
		}

		// output -----

		$output = '<div class="progress_icons '. esc_attr($class) .'" data-active="'. esc_attr($active) .'" data-color="'. esc_attr($background) .'">';
			for ($i = 1; $i <= $count; $i++) {

				if( $i > $active ){
					$icon_class = false;
					$icon_style = false;
				}

				if ($image) {
					$output .= '<span class="progress_icon progress_image '. esc_attr($icon_class) .'" style="'. $icon_style .'"><img src="'. esc_url($image) .'" alt="progress image"/></span>';
				} else {
					$output .= '<span class="progress_icon '. esc_attr($icon_class) .'" style="'. $icon_style .'"><i class="'. esc_attr($icon) .'"></i></span>';
				}

			}
		$output .= '</div>'."\n";

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Progress Bars [progress_bars][bar][/progress_bars]
 */

if (! function_exists('sc_progress_bars')) {
	function sc_progress_bars($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' => '',
			'title_tag' => '',
			'tabs' => '',
		), $attr));

		$title = be_dynamic_data($title);
		$content = be_dynamic_data($content);

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output

		$output = '<div class="progress_bars">';

			if ($title) {
				$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
				$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class).'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
			}

			$output .= '<ul class="bars_list">';

				if( is_array( $tabs ) ) {

					$output .= '<ul>';
						foreach( $tabs as $tab ){
							$output .= sc_bar( $tab );
						}
					$output .= '</ul>';

				}

				$output .= '<ul class="pb-desc">';
					$output .= do_shortcode($content ?? '');
				$output .= '</ul>';

			$output .= '</ul>';

		$output .= '</div>'."\n";

    if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * _Bar [bar]
 */

if (! function_exists('sc_bar')) {
	function sc_bar($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' => '',
			'value' => 0,
			'size' => '',
			'color' => '',
		), $attr));

		// size

		if ($size) {
			$size_escaped = 'style="height:'. intval($size, 10) .'px"';
		} else {
			$size_escaped = false;
		}

		// color

		if( $color ){
			$color_escaped = 'background-color:'. esc_attr($color);
		} else {
			$color_escaped = false;
		}

		// output -----

		$output  = '<li>';

			$output .= '<h6>';
				$output .= wp_kses($title, mfn_allowed_html());
				$output .= '<span class="label">'. intval($value, 10) .'<em>%</em></span>';
			$output .= '</h6>';

			// This variable has been safely escaped above in this function
			$output .= '<div class="bar" '. $size_escaped .'>';
				$output .= '<span class="progress" style="width:'. intval($value,10) .'%;'. $color_escaped .'"></span>';
			$output .= '</div>';

		$output .= '</li>'."\n";

		return $output;
	}
}

/**
 * Timeline [timeline] [/timeline]
 */

if (! function_exists('sc_timeline')) {
	function sc_timeline($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'count' => '',
			'tabs' => '',
		), $attr));

		// output -----

		$output  = '<ul class="timeline_items">';

			if ( is_array( $tabs ) ) {

				// content builder

				foreach ( $tabs as $tab ) {
					$output .= '<li>';

						$output .= '<h3>';

							if( ! empty( $tab['date'] ) ){
									$output .= '<span>'. esc_html( $tab['date'] ) .'</span>';
							}

							$output .= wp_kses( $tab['title'], mfn_allowed_html('caption') );

						$output .= '</h3>';

						if ( ! empty( $tab['content'] ) ) {
							$output .= '<div class="desc">';
								$output .= do_shortcode($tab['content'] ?? '');
							$output .= '</div>';
						}

					$output .= '</li>';
				}

			} else {

				// shortcode

				$output .= do_shortcode($content ?? '');

			}

		$output .= '</ul>'."\n";

		return $output;
	}
}

/**
 * Tag cloud [tag_cloud]
 */

if (! function_exists('sc_tag_cloud')) {
	function sc_tag_cloud($attr) {
		extract(shortcode_atts(array(
			'category'		=> 'category',
			'reference' 	=> '',
			'design' 		=> '',
		), $attr));

		$classes = array('mfn-tag-cloud');
		$output = '';

		if( !empty($design) ){
			$classes[] = 'mfn-tag-cloud-'.$design;
		}else{
			$classes[] = 'mfn-tag-cloud-text';
		}

		if( !empty($reference) && $reference == 'post' ){
			// current post taxonomies
			$post_id = false;

			if( !empty($attr['vb_postid']) ){
				$post_id = $attr['vb_postid'];
			}else if( is_single() ){
				$post_id = get_the_ID();
			}

			if( $category == 'category' && get_post_type($post_id) == 'portfolio' ) $category = 'portfolio-types';

			$terms = get_the_terms( $post_id, $category );

		}else{
			// all terms
			$terms_attr = array(
				'taxonomy' => $category,
				'hide_empty' => false
			);

			if( !empty( $attr['orderby'] ) ) $terms_attr['orderby'] = $attr['orderby'];
			if( !empty( $attr['order'] ) ) { $terms_attr['order'] = 'ASC'; }else{ $terms_attr['order'] = 'DESC'; }

			if( !empty($reference) && $reference == 'not_empty' ) unset($terms_attr['hide_empty']);
			$terms = get_terms($terms_attr);
		}

		$output = '<ul class="'.implode(' ', $classes).'">';

			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$term_link = get_term_link( $term->slug, $category );
					if( ! is_wp_error( $term_link ) ){
						$output .= '<li>';
							$output .= '<a href="' . esc_attr( $term_link ) . '">';
								$output .= __( $term->name );
							$output .= '</a>';
						$output .= '</li>';
					}
				}
			}

		$output .= '</ul>';

		return $output;

	}
}

/**
 * Testimonials [testimonials]
 */

if (! function_exists('sc_testimonials')) {
	function sc_testimonials($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title_tag'		=> '',
			'category'		=> '',
			'orderby' 		=> 'menu_order',
			'order' 			=> 'ASC',
			'style' 			=> '',
			'hide_photos' => '',
		), $attr));

		// query args

		$args = array(
			'post_type' 			=> 'testimonial',
			'posts_per_page' 	=> -1,
			'orderby' 				=> $orderby,
			'order' 					=> $order,
			'ignore_sticky_posts' => 1,
		);

		if ($category) {
			$args['testimonial-types'] = $category;
		}

		// query

		$query_tm = new WP_Query();
		$query_tm->query($args);

		// class

		$class = $style;

		if ($hide_photos) {
			$class .= ' hide-photos';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '';

		$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h5';

		if ($query_tm->have_posts()) {
			$output .= '<div class="testimonials_slider '. esc_attr($class) .'">';

				// photos | pagination (style !== single-photo)
				if ($style != 'single-photo' && ! $hide_photos) {
					$output .= '<div class="slider_pager slider_images"></div>';
				}

					// testimonials | contant
					$output .= '<ul class="testimonials_slider_ul">';

						while ($query_tm->have_posts()) {
							$query_tm->the_post();

							$output .= '<li>';

								$output .= '<div class="single-photo-img">';
									if (has_post_thumbnail()) {
										$output .= get_the_post_thumbnail(null, 'be_thumbnail', array('class'=>'scale-with-grid'));
									} else {
										$output .= '<img class="scale-with-grid" src="'. esc_url(get_theme_file_uri('/images/testimonials-placeholder.png')). '" alt="'. esc_attr(get_post_meta(get_the_ID(), 'mfn-post-author', true)) .'" />';
									}
								$output .= '</div>';

								$output .= '<div class="bq_wrapper">';
									$output .= '<div class="blockquote"><span class="mfn-blockquote-icon"><i class="'.( !empty($icon) ? $icon : 'icon-quote' ).'" aria-hidden="true"></i></span><blockquote>'. get_the_content() .'</blockquote></div>';
								$output .= '</div>';

								$output .= '<div class="hr_dots"><span></span><span></span><span></span></div>';

								$output .= '<div class="author">';
									$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">';
										if ($link = get_post_meta(get_the_ID(), 'mfn-post-link', true)) {
											$output .= '<a target="_blank" href="'. esc_url($link) .'">';
										}
											$output .= esc_html(get_post_meta(get_the_ID(), 'mfn-post-author', true));
										if ($link) {
											$output .= '</a>';
										}
									$output .= '</'. mfn_allowed_title_tag($title_tag) .'>';
									$output .= '<span class="company">'. get_post_meta(get_the_ID(), 'mfn-post-company', true) .'</span>';
								$output .= '</div>';

							$output .= '</li>';
						}

						wp_reset_query();

					$output .= '</ul>';

				// photos | pagination (style == single-photo)
				if ($style == 'single-photo') {
					$output .= '<div class="slider_pager slider_pagination"></div>';
				}

			$output .= '</div>'."\n";
		}

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-slick', get_theme_file_uri('/js/plugins/slick.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Testimonials List [testimonials_list]
 */

if (! function_exists('sc_testimonials_list')) {
	function sc_testimonials_list($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title_tag' 	=> '',
			'category' 	=> '',
			'orderby' 	=> 'menu_order',
			'order' 		=> 'ASC',
			'style' 		=> '',	// [default], quote
		), $attr));

		// query args

		$args = array(
			'post_type' 			=> 'testimonial',
			'posts_per_page' 	=> -1,
			'orderby' 				=> $orderby,
			'order' 					=> $order,
			'ignore_sticky_posts' =>1,
		);

		if ($category) {
			$args['testimonial-types'] = $category;
		}

		// query

		$query_tm = new WP_Query();
		$query_tm->query($args);

		// class

		if ($style) {
			$class = 'style_'. $style;
		} else {
			$class = '';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';

		$output = '';

		if ($query_tm->have_posts()) {
			$output .= '<div class="testimonials_list '. esc_attr($class) .'">';

				while ($query_tm->have_posts()) {
					$query_tm->the_post();

					// classes
					$class = '';
					if (! has_post_thumbnail()) {
						$class .= 'no-img';
					}

					$output .= '<div class="item '. esc_attr($class) .'">';

						if (has_post_thumbnail()) {
							$output .= '<div class="photo">';
								$output .= '<div class="image_frame no_link scale-with-grid has_border">';
									$output .= '<div class="image_wrapper">';
										$output .= get_the_post_thumbnail(null, 'full', array('class'=>'scale-with-grid' ));
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
						}

						$output .= '<div class="desc">';

							if ($style == 'quote') {
								$output .= '<div class="blockquote clearfix">';
									$output .= '<blockquote>'. get_the_content() .'</blockquote>';
								$output .= '</div>';
								$output .= '<hr class="hr_color" />';
							}

							$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">';
								if ($link = get_post_meta(get_the_ID(), 'mfn-post-link', true)) {
									$output .= '<a target="_blank" href="'. esc_url($link) .'">';
								}
									$output .= get_post_meta(get_the_ID(), 'mfn-post-author', true);
								if ($link) {
									$output .= '</a>';
								}
							$output .= '</'. mfn_allowed_title_tag($title_tag) .'>';

							$output .= '<p class="subtitle">'. esc_html(get_post_meta(get_the_ID(), 'mfn-post-company', true)) .'</p>';

							if ($style != 'quote') {
								$output .= '<hr class="hr_color" />';
								$output .= '<div class="blockquote">';
									$output .= '<span class="mfn-blockquote-icon"><i class="'.( !empty($icon) ? $icon : 'icon-quote' ).'" aria-hidden="true"></i></span><blockquote>'. get_the_content() .'</blockquote>';
									//$output .= '<blockquote>'. get_the_content() .'</blockquote>';
								$output .= '</div>';
							}

						$output .= '</div>';

					$output .= '</div>'."\n";
				}
				wp_reset_query();

			$output .= '</div>'."\n";
		}

		return $output;
	}
}

/**
 * Vimeo [video]
 */

if (! function_exists('sc_video')) {
	function sc_video($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'video' 			=> '',
			'parameters' 	=> '',
			'mp4' 				=> '',
			'ogv'	 				=> '',
			'placeholder' => '',
			'html5_parameters' => '',
			'width' 			=> '',
			'height' 			=> '',

			'mask_shape_type' => '',
			'mask_shape_size' => '',
			'mask_shape_position' => '',
		), $attr));

		$css = '';

		// Mask Shape

		$is_mask_shape_enabled = !empty($mask_shape_type) ? true : false;

		if( $is_mask_shape_enabled ) {

			// Video params
			//!! Mask Shape IMPORTANT
			//!! BG -> div.content video && img -> layer with video
			//!! Params required -> autoplay=1, mute=1, loop=1
			//!! For iframe controns mustn't be disabled, CSS solve the problem

			$find_pos_autoplay = strpos($parameters, 'autoplay=');
			$find_pos_mute 	   = strpos($parameters, 'mute=');
			$find_pos_loop 	   = strpos($parameters, 'loop=');

			//!! +9, +5 is just a length of a word 'autoplay' or 'mute' or 'loop'
			!is_bool($find_pos_autoplay) ? $parameters[$find_pos_autoplay + 9] = '1' : $parameters .= '&autoplay=1';
			!is_bool($find_pos_mute) 	 ? $parameters[$find_pos_mute + 5]	   = '1' : $parameters .= '&mute=1';
			!is_bool($find_pos_loop)	 ? $parameters[$find_pos_loop + 5] 	   = '1' : $parameters .= '&loop=1';

			$parameters = '&'. $parameters;

			// Inline CSS => Position
			if ( $mask_shape_size !== 'custom' ) {
				$css .= '-webkit-mask-size:'. $mask_shape_size .';';
			}
			if( $mask_shape_position !== 'custom' ){
				$css .= '-webkit-mask-position:'. $mask_shape_position .';';
			}

		} else if ( $parameters ) {

			$parameters = '&'. $parameters;

		}

		// HTML5 parameters

		$html5_default = array(
			'autoplay'		=> 'autoplay="1"',
			'controls'		=> 'controls="1"',
			'loop'				=> 'loop="1"',
			'muted'				=> 'muted="1"',
			'playsinline' => '',
		);

		if ($html5_parameters) {

			$html5_parameters = explode(';', $html5_parameters);

			if (! $html5_parameters[0]) {
				$html5_default['autoplay'] = $is_mask_shape_enabled ? 'autoplay="1"' : false;
			}
			if (! $html5_parameters[1] || $is_mask_shape_enabled ) {
				$html5_default['controls'] = false;
			}
			if (! $html5_parameters[2]) {
				$html5_default['loop']	   = $is_mask_shape_enabled ? 'loop="1"' : false;
			}
			if (! $html5_parameters[3]) {
				$html5_default['muted']	   = $is_mask_shape_enabled ? 'muted="1"' : false;
			}
			if ( ! empty($html5_parameters[4]) ) {
				$html5_default['playsinline'] = 'playsinline="1"';
			}

			// Disable Picture in Picture for Mask Shape || ONLY CHROME, firefox force to enable it

			if( $is_mask_shape_enabled ) {
				$html5_default['picture-in-picture'] = 'disablePictureInPicture="1';
			}
		}

		// no need to escape, no user data
		$html5_escaped = implode(' ', $html5_default);

		// class

		$class = $video ? 'iframe' : '' ;

		if ($width && $height) {
			$class .= ' has-wh';
		} else {
			$class .= ' auto-wh';
		}

		$class .= $is_mask_shape_enabled ? ' mfn-mask-shape' : '';
		$class .= $is_mask_shape_enabled && $mask_shape_type !== '0' ? ' '.$mask_shape_type : '';

		$output  = '<div class="content_video '. esc_attr($class) .'">';

			if ($video) {

				$video = be_dynamic_data($video);

				// Embed
				if (is_numeric($video)) {
					// Vimeo
					$output .= '<iframe style="'. $css .'" class="scale-with-grid" width="'. esc_attr($width) .'" height="'. esc_attr($height) .'" src="http'. mfn_ssl() .'://player.vimeo.com/video/'. esc_attr($video) .'?wmode=opaque'. esc_attr($parameters) .'" allowFullScreen></iframe>'."\n";
				} else {
					$parameters .= '&rel=0&enablejsapi=1';
					// YouTube
					$output .= '<iframe style="'. $css .'" class="scale-with-grid" width="'. esc_attr($width) .'" height="'. esc_attr($height) .'" src="http'. mfn_ssl() .'://www.youtube.com/embed/'. esc_attr($video) .'?wmode=opaque'. esc_attr($parameters) .'" allowfullscreen></iframe>'."\n";
				}

			} elseif ($mp4) {

				// HTML5
				$output .= '<div class="section_video">';

					$output .= '<div class="mask"></div>';
					$poster = ($placeholder) ? $placeholder : false;

					// This variable has been safely escaped above in this function
					$output .= '<video poster="'. esc_url($poster) .'" '. $html5_escaped .' width="'. esc_attr($width) .'" height="'. esc_attr($height) .'" style="max-width:100%; '. $css .'">';

						$output .= '<source type="video/mp4" src="'. esc_url($mp4) .'" />';
						if ($ogv) {
							$output .= '<source type="video/ogg" src="'. esc_url($ogv) .'" />';
						}

					$output .= '</video>';

				$output .= '</div>';
			}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * _Item [item]
 * [feature_list][item][/feature_list]
 */

if (! function_exists('sc_item')) {
	function sc_item($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'icon'		=> 'icon-picture',
			'title'		=> '',
			'link'		=> '',
			'target'	=> '',
		), $attr));

		// target

		if ($target) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// elementor icon

		if( is_array($icon) ){
			$icon = $icon['value'];
		}

		// output -----

		$output  = '<li>';
			if ($link) {
				// This variable has been safely escaped above in this function
				$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .'>';
			}

			$output .= '<span class="icon">';
				$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
			$output .= '</span>';
			$output .= '<p>'. wp_kses($title, mfn_allowed_html()) .'</p>';

			if ($link) {
				$output .= '</a>';
			}


		$output .= '</li>'."\n";

		return $output;
	}
}

/**
 * Feature List [feature_list]				[feature_list][item][/feature_list]
 */

if (! function_exists('sc_feature_list')) {
	function sc_feature_list($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'tabs' => '',
			'columns'	=> 4,
		), $attr));

		$content = be_dynamic_data($content);

		// output -----

		$output = '<div class="feature_list" data-col="'. esc_attr($columns) .'">';

			if( is_array( $tabs ) ) {

				$output .= '<ul>';
					foreach( $tabs as $tab ){
						$output .= sc_item( $tab );
					}
				$output .= '</ul>';

			}

			$output .= '<ul class="fl-content">';
				$output .= do_shortcode($content ?? '');
			$output .= '</ul>';

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * List [list][/list]
 */

if (! function_exists('sc_list')) {
	function sc_list($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title_tag'		=> '',
			'icon'		=> 'icon-picture',
			'image'		=> '',
			'title'		=> '',
			'link'		=> '',
			'target'	=> '',
			'style'		=> 1,
		), $attr));

		$title = be_dynamic_data($title);
		$link = be_dynamic_data($link);
		$content = be_dynamic_data($content);

		// image | visual composer fix

		$image = be_dynamic_data($image);
		if( is_numeric($image) ) $image = wp_get_attachment_image_url($image, 'full');

		$image = mfn_vc_image($image);

		// target

		if ($target) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// FIX: elementor svg icon

		if( is_array($icon) && ! empty($icon['url']) ){
			$image = $icon['url'];
			$icon = false;
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// output -----

		$output = '<div class="list_item lists_'. esc_attr($style) .' clearfix">';

			if ($link) {
				// This variable has been safely escaped above in this function
				$output .= '<a href="'. esc_url($link) .'" '. $target_escaped .'>';
			}

				if ($style == 4) {
					$output .= '<div class="circle">'. wp_kses($title, mfn_allowed_html()) .'</div>';
				} elseif ($image) {
					$output .= '<div class="list_left list_image">';
						$output .= '<img src="'. esc_url($image) .'" class="scale-with-grid" alt="'. esc_attr(mfn_get_attachment_data($image, 'alt')) .'" width="'. esc_attr(mfn_get_attachment_data($image, 'width')) .'" height="'. esc_attr(mfn_get_attachment_data($image, 'height')) .'"/>';
					$output .= '</div>';
				} else {
					$output .= '<div class="list_left list_icon">';
						$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
					$output .= '</div>';
				}

				$output .= '<div class="list_right">';
					if ($title && $style != 4) {
						$title_tag = !empty( $attr['title_tag'] ) ? $attr['title_tag'] : 'h4';
						$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';
					}
					$output .= '<div class="desc">'. do_shortcode($content ?? '') .'</div>';
				$output .= '</div>';

			if ($link) {
				$output .= '</a>';
			}

		$output .= '</div>'."\n";

		return $output;
	}
}

/**
 * Gallery [gallery]
 */

if (! function_exists('sc_gallery')) {
	function sc_gallery($attr)
	{
		$post = get_post();

		static $instance = 0;
		$instance++;

		// visual builder

		if( wp_doing_ajax() ){
			$instance = rand(0, 9999);
		}

		if (! empty($attr['ids'])) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if (empty($attr['orderby'])) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if (isset($attr['orderby'])) {
			$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
			if (! $attr['orderby']) {
				unset($attr['orderby']);
			}
		}

		$html5 = current_theme_supports('html5', 'gallery');
		$atts = shortcode_atts(array(
			'order'				=> 'ASC',
			'orderby'    	=> 'menu_order ID',
			'id'         	=> $post  ? $post->ID : 0,
			'itemtag'    	=> $html5 ? 'figure'     : 'dl',
			'icontag'    	=> $html5 ? 'div'        : 'dt',
			'captiontag' 	=> $html5 ? 'figcaption' : 'dd',
			'columns'    	=> 3,
			'size'       	=> 'thumbnail',
			'include'    	=> '',
			'exclude'    	=> '',
			'link'       	=> '',

		// mfn custom

			'style'			=> '',	// [default], flat, fancy, masonry
			'greyscale'		=> '',

		), $attr, 'gallery');

		// mfn custom class

		$class = $atts['link'];

		if ( !empty($atts['style']) ) {
			$class .= ' '. $atts['style'];
		}else{
			$class .= ' gallery-default';
		}

		if ($atts['greyscale']) {
			$class .= ' greyscale';
		}

		if( !empty( $attr['layout'] ) && $atts['style'] != 'masonry' ) {
			$class .= ' equal-heights';
		}

		if( !empty( $attr['image_height'] ) ) {
			$class .= ' '. $attr['image_height'];
		}

		if( !empty( $attr['image_caption_style'] ) ) {
			$class .= ' '. $attr['image_caption_style'];
		}

		// end: mfn custom class

		$id = intval($atts['id'], 10);

		$id == 0 ? $id = 1 : $id = $id; // VB: prevents post_parent = 0 where all images are loaded on start

		if ('RAND' == $atts['order']) {
			$atts['orderby'] = 'none';
		}

		if (! empty($atts['include'])) {
			$_attachments = get_posts(array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ));

			$attachments = array();
			foreach ($_attachments as $key => $val) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif (! empty($atts['exclude'])) {
			$attachments = get_children(array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ));
		} else {
			$attachments = get_children(array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ));
		}


		if (empty($attachments)) {
			return '';
		}

		if (is_feed()) {
			$output = "\n";
			foreach ($attachments as $att_id => $attachment) {
				$output .= wp_get_attachment_link($att_id, $atts['size'], true) . "\n";
			}
			return $output;
		}

		$itemtag = tag_escape($atts['itemtag']);
		$captiontag = tag_escape($atts['captiontag']);
		$icontag = tag_escape($atts['icontag']);
		$valid_tags = wp_kses_allowed_html('post');
		if (! isset($valid_tags[ $itemtag ])) {
			$itemtag = 'dl';
		}
		if (! isset($valid_tags[ $captiontag ])) {
			$captiontag = 'dd';
		}
		if (! isset($valid_tags[ $icontag ])) {
			$icontag = 'dt';
		}

		$columns = intval($atts['columns'], 10);

		$itemwidth = $columns > 0 ? (ceil(100/$columns*100)/100 - 0.01) : 100;

		$float = is_rtl() ? 'right' : 'left';

		$selector = "sc_gallery-{$instance}";

		$gallery_style = '';

		if (apply_filters('use_default_gallery_style', ! $html5)) {
			$gallery_style = "
			<style type='text/css'>
				#{$selector} {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					text-align: center;
					width: {$itemwidth}%;
				}
				#{$selector} img {
					border: 2px solid #cfcfcf;
				}
				/* see sc_gallery() in functions/theme-shortcodes.php */
			</style>\n\t\t";
		}

		$size_class = sanitize_html_class($atts['size']);
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} {$class}'>";

		$output = apply_filters('gallery_style', $gallery_style . $gallery_div);

		// output -----

		$i = 0;
		foreach ($attachments as $id => $attachment) {
			if (! empty($atts['link']) && 'file' === $atts['link']) {
				$image_output = wp_get_attachment_link($id, $atts['size'], false, false);
			} elseif (! empty($atts['link']) && 'none' === $atts['link']) {
				$image_output = wp_get_attachment_image($id, $atts['size'], false);
			} else {
				$image_output = wp_get_attachment_link($id, $atts['size'], true, false);
			}
			$image_meta  = wp_get_attachment_metadata($id);

			$orientation = '';
			if (isset($image_meta['height'], $image_meta['width'])) {
				$orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
			}

			// elementor image attributes

			$image_data = [
				'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
				'caption' => $attachment->post_excerpt,
				'description' => $attachment->post_content,
				'title' => $attachment->post_title,
			];

			if( !empty( mfn_opts_get('lazy-load') ) ){
				$image_data['loading'] = 'lazy';
			}

			$lightbox_title_src = 'title';
			$lightbox_description_src = 'description';

			if ( class_exists( 'Elementor\Plugin' ) ){
				$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();
				$lightbox_title_src = $kit->get_settings( 'lightbox_title_src' );
				$lightbox_description_src = $kit->get_settings( 'lightbox_description_src' );
			}

			// end: elementor image attributes

			$output .= "<{$itemtag} class='gallery-item' ".( !empty($attr['vb']) || !empty($_GET['visual']) ? "data-id='".$id."'" : "" )." data-title='{$image_data[$lightbox_title_src]}' data-description='{$image_data[$lightbox_description_src]}'><div class='gallery-item-wrapper'>";
			$output .= "
				<{$icontag} class='gallery-icon {$orientation}'>
					$image_output
				</{$icontag}>";
			if ($captiontag && trim($attachment->post_excerpt)) {
				$output .= "
					<{$captiontag} class='wp-caption-text gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontag}>";
			}
			$output .= "</div></{$itemtag}>";
			if (! $html5 && $columns > 0 && ++$i % $columns == 0) {
				$output .= '<br style="clear: both" />';
			}
		}

		if (! $html5 && $columns > 0 && $i % $columns !== 0) {
			$output .= "
				<br style='clear: both' />";
		}

		$output .= "
			</div>\n";

		if( ! isset( $attr['vb'] ) ){
			wp_enqueue_script('mfn-imagesloaded', get_theme_file_uri('/js/plugins/imagesloaded.min.js'), ['jquery'], MFN_THEME_VERSION, true);
			wp_enqueue_script('mfn-isotope', get_theme_file_uri('/js/plugins/isotope.min.js'), ['jquery'], MFN_THEME_VERSION, true);
		}

		return $output;
	}
}

/**
 * Raw [raw][/raw]
 * WordPress 4.8 | Text Widget - autop
 */

if (! function_exists('sc_raw')) {
	function sc_raw($content)
	{
		$new_content = '';
		$pattern_full = '{(\[raw\].*?\[/raw\])}is';
		$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
		$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

		foreach ($pieces as $piece) {
			if (preg_match($pattern_contents, $piece, $matches)) {
				$new_content .= $matches[1];
			} else {
				$new_content .= wptexturize(wpautop($piece));
			}
		}

		return $new_content;
	}
}

if (! function_exists('mfn_widget_text_content')) {
	function mfn_widget_text_content(){
		add_filter('widget_text_content', 'sc_raw', 99);
	}
}

/**
 * Year [year]
 */

if (! function_exists('sc_year')) {
	function sc_year()
	{
		return date_i18n ('Y');
	}
}

/**
 * Livesearch [livesearch]
 */

if (! function_exists('sc_livesearch')) {
	function sc_livesearch($attr, $content = null)
	{
		$atts = shortcode_atts(array(
			'min_characters' => '3',
			'load_posts' => '10',
			'container_height' => '300',
			'featured_image' => '1'
		), $attr);

		$translate['search-placeholder'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-placeholder','Enter your search') : __('Enter your search','betheme');
		$translate['livesearch-noresults'] = mfn_opts_get('translate') ? mfn_opts_get('translate-livesearch-noresults','Not found text') : __('Not found text','betheme');
		$translate['livesearch-button'] = mfn_opts_get('translate') ? mfn_opts_get('translate-livesearch-button','See all results') : __('See all results','betheme');

		// enqueue

		wp_enqueue_script( 'mfn-livesearch',  get_theme_file_uri('js/live-search.js'), array('underscore'), MFN_THEME_VERSION, true );
		wp_localize_script( 'mfn-livesearch', 'mfn_livesearch_categories', mfn_list_categories() );

		// output -----

		$output = '';

		// is elementor
		if( !empty( $attr['elementor'] ) ){
			$output .= '<div class="column_livesearch">';
		}

		$output .= '<div class="mfn-live-search-wrapper" data-char="'.$atts['min_characters'].'" data-posts="'.$atts['load_posts'].'" data-featured="'.$atts['featured_image'].'">';

			// searchfield

			$output .= '<form method="get" class="mfn-live-searchform" action="'. esc_url( home_url('/') ) .'">';
				$output .= '<svg class="icon_search" width="26" viewBox="0 0 26 26" aria-hidden="true"><defs><style>.path{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:1.5px;}</style></defs><circle class="path" cx="11.35" cy="11.35" r="6"></circle><line class="path" x1="15.59" y1="15.59" x2="20.65" y2="20.65"></line></svg>';

				if ( mfn_opts_get('keyboard-support') ) {
					$output .='<label for="s" class="screen-reader-text">'. esc_html($translate['search-placeholder']) .'</label>';
				}

				if( mfn_opts_get('header-search') == 'shop' ){
					$output .= '<input type="hidden" name="post_type" value="product" />';
				}
				$output .= '<input type="text" class="field" name="s" placeholder="'. $translate['search-placeholder'] .'" />';
				$output .= '<input type="submit" class="display-none" value="" />';
			$output .= '</form>';

			// box

			$output .= '<div class="mfn-live-search-box" style="display:none">
				<ul class="mfn-live-search-list" style="max-height:'. esc_attr($atts['container_height']) .'px ">
					<li class="mfn-live-search-list-categories"><ul></ul></li>
					<li class="mfn-live-search-list-shop"><ul></ul></li>
					<li class="mfn-live-search-list-blog"><ul></ul></li>
					<li class="mfn-live-search-list-pages"><ul></ul></li>
					<li class="mfn-live-search-list-portfolio"><ul></ul></li>
				</ul>

				<span class="mfn-live-search-noresults">'. esc_html($translate['livesearch-noresults']) .'</span>
				<a class="button button_theme hidden">'. esc_html($translate['livesearch-button']) .'</a>
			</div>';

		$output .= '</div>';

		// is elementor
		if( !empty( $attr['elementor'] ) ){
			$output .= '</div>';
		}

		return $output;
	}
}

/**
 * Table of contents [table_of_contents] toc
 */

if (! function_exists('sc_table_of_contents')) {
	function sc_table_of_contents($attr, $content = null)
	{
		extract(shortcode_atts(array(
			'title' => '',
			'title_tag' => '',
			'tags_anchors' => 'h1 h2 h3',
			'marker_view'	=> 'numbers',
			'icon' => '',
			'url_format' => '',
			'allow_hide' => '',
			'text_show' => __('Show', 'mfn-opts'),
			'text_hide' => __('Hide', 'mfn-opts'),
		), $attr));

		$title = be_dynamic_data($title);

		if( empty($marker_view) ){
			$marker_view = 'numbers';
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// GET content from BeBuilder

		if( isset($attr['pageid']) ) {
			$id = $attr['pageid'];
		}else if( isset($attr['vb_postid']) ) {
			$id = $attr['vb_postid'];
		} else {
			$id = get_the_ID();
		}

		$mfn_sections = get_post_meta($id, 'mfn-page-items', true);

		$mfn_wraps = [];
		$mfn_items = [];
		$mfn_columns = [];
		$wp_content = [];

		if ( $mfn_sections && ! is_array( $mfn_sections ) ) {
			$mfn_sections = unserialize(call_user_func('base'.'64_decode', $mfn_sections), ['allowed_classes' => false]);
		}

		if( is_array($mfn_sections) ){

			// wraps

			foreach($mfn_sections as $key => $value){
				if( is_array($value) ) {
					foreach($value as $in_key => $in_val){
						if ($in_key === 'wraps'){
							$mfn_wraps[] = $in_val;
						}
					}
				}
			}

			// items

			if( is_array($mfn_wraps) ){
				foreach($mfn_wraps as $key => $value){
					if( is_array($value) ){
						foreach($value as $in_key => $in_val){
							if( isset($in_val['items']) ) {
								$mfn_items[] = $in_val['items'];

								if( is_array($in_val['items']) && ! empty($in_val['items']) ){

									foreach ($in_val['items'] as $ii_key => $ii_val) {
										if( !empty($ii_val['item_is_wrap']) && !empty($ii_val['items']) ){
											$mfn_items[] = $ii_val['items'];
										}
									}
								}

							}
						}
					}
				}
			}

			if( is_array($mfn_items) ){

				// columns & heading content only

				foreach($mfn_items as $key => $value){
					if( is_array($value) ){
						foreach($value as $in_key => $in_val){

							// fields -> attr
							if( isset( $in_val['fields'] ) ){
								$in_val['attr'] = $in_val['fields'];
								unset($in_val['fields']);
							}

							// column

							if ( !empty($in_val['type']) && 'column' == $in_val['type'] && ! empty($in_val['attr']['content']) ) {
								$mfn_columns[] = be_dynamic_data(esc_html($in_val['attr']['content']));
							};

							// heading

							if ( !empty($in_val['type']) && 'heading' == $in_val['type'] ) {
								$heading_tag = $in_val['attr']['header_tag'];
								$heading_title = be_dynamic_data($in_val['attr']['title']);
								$mfn_columns[] = esc_html('<'. $heading_tag .'>'. $heading_title .'</'. $heading_tag .'>');
							};

							// fancy heading

							if ( !empty($in_val['type']) && 'fancy_heading' == $in_val['type'] ) {
								$heading_tag = !empty($in_val['attr']['h1']) ? 'h1' : 'h2';
								$heading_title = be_dynamic_data($in_val['attr']['title']);
								$mfn_columns[] = esc_html('<'. $heading_tag .'>'. $heading_title .'</'. $heading_tag .'>');
							};

						};
					}
				}

			}

		}

		// GET Elementor, WPB, Gutenberg content

		if( ! get_post_meta($id, 'mfn-post-hide-content', true) ){
			$wp_content = array( htmlentities(get_the_content()) );
		}

		// create regex



		if( empty($tags_anchors) ) {
			$tags_anchors = 'h1 h2 h3';
		}

		/*$tags_regex = '/';
		foreach(explode(' ', $tags_anchors) as $key => $value){
			if($key == 0){
				$tags_regex .= '&lt;('.$value.'?.*?'.'&lt;\/'.$value.')&gt;';
			}else{
				$tags_regex .= '|&lt;('.$value.'?.*?'.'&lt;\/'.$value.')&gt;';
			}
		}
		$tags_regex .= '/i';*/

		$tags_array = explode(' ', $tags_anchors);

		$escaped_headers = array_map(function($header) {
	        return preg_quote($header, '/');
	    }, $tags_array);


	    $tags_regex = '/<(' . implode('|', $escaped_headers) . ')[^>]*>(.*?)<\/\1>/si';

		// get all headings

		$highest_heading = 0;
		$mfn_headings = [];

		foreach( array_merge($mfn_columns, $wp_content) as $key => $value){
			$value = htmlspecialchars_decode($value);
			preg_match_all( $tags_regex, $value, $matches );

			foreach($matches[0] as $in_key => $in_value){
				$in_value = wp_specialchars_decode($in_value);
				if( $title != wp_strip_all_tags($in_value) ){
					$mfn_headings[] = $in_value;
				}
			}

			// get the biggest heading
			foreach($mfn_headings as $in_key => $in_value){
				preg_match('/[1234567]/', $in_value, $headingChecked);

				if ($highest_heading === 0) {
					$highest_heading = $headingChecked[0];
				}else if($highest_heading > $headingChecked[0]){
					$highest_heading = $headingChecked[0];
				}
			}
		}

		// bullets

		if( ('bullets' === $marker_view) && $icon ){
			$icon = '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
			$marker_view = 'custom_icon';
		} else {
			$icon = '<i></i>';
		}

		// init

		$previous_heading_level = 0;
		$nested_depth = 1;

		// class

		$class = '';

		if( 'hide' == $allow_hide ){
			$class .= 'hide hide_on_start';
		}

		// output -----

		$output = '<div class="table_of_content clearfix '. $class .'" data-tags="'. $tags_anchors .'">';

			if ($title) {
				$output .= '<div class="title">';

					$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="title-inner '. esc_attr($title_class) .'">'. wp_kses($title, mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';

					if( ! empty($allow_hide) ){
						$output .= '<a href="#" class="toggle">';
							$output .= '<span class="toggle-show">'. $text_show .'</span>';
							$output .= '<span class="toggle-hide">'. $text_hide .'</span>';
						$output .= '</a>';
					}

				$output .= '</div>';
			}

			$output .= '<div class="table_of_content_wrapper">';
				$output .= '<ol class="mfn_toc_'. $marker_view .'">';

					$i = 1;

					$urls = [];

					foreach($mfn_headings as $key => $value){

						preg_match('/[1234567]/', $value, $matches); //for checking, which heading is bigger

						// prepare url friendly link
						if( 'simple' === $url_format ){
							$href_parsed = 'toc-'. $i++;
						} else {
							$href_parsed = sanitize_title(wp_strip_all_tags(do_shortcode($value ?? '')));

							// more than one heading with the same title
							if( ! empty($urls[$href_parsed]) ){
								$urls[$href_parsed] += 1;
								$href_parsed .= '-'. $urls[$href_parsed];
							} else {
								$urls[$href_parsed] = 1;
							}

						}

						switch(true){
							case $previous_heading_level === 0: //init
								$output .= '<li data-depth="'.$nested_depth.'" >'. $icon .'<a class="scroll" href="#'.$href_parsed.'">'. wp_strip_all_tags(do_shortcode($value ?? '')) .'</a></li>';

								break;
							case $highest_heading === $matches[0] && $nested_depth > 1: //root heading reached, reset
								$x = 1;
								for($x; $x < $nested_depth; $x++ ){
									$output .= '</ol>';
								}
								$output .= '<li>'. $icon .'<a class="scroll" href="#'.$href_parsed.'">'. wp_strip_all_tags(do_shortcode($value ?? '')) .'</a></li>';

								$nested_depth = 1;
								break;
							case $previous_heading_level < $matches[0]; //new value is bigger, make a nest
								$nested_depth++;

								$output .= '<li class="mfn_toc_nested" data-depth="'.$nested_depth.'"><ol>';
								$output .= '<li>'. $icon .'<a class="scroll" href="#'.$href_parsed.'">'. wp_strip_all_tags(do_shortcode($value ?? '')) .'</a></li>';

								break;
							case $previous_heading_level > $matches[0] && $nested_depth > 1: //new value is smaller and its in nest (x > 1), decrease nest
								$nested_depth--;

								$output .= '</ol>';
								$output .= '</li>';
								$output .= '<li>'. $icon .'<a class="scroll" href="#'.$href_parsed.'">'. wp_strip_all_tags(do_shortcode($value ?? '')) .'</a></li>';
								break;
							case $previous_heading_level > $matches[0]: //new value is smaller and its in nest (x < 1), decrease nest
								$output .= '<li>'. $icon .'<a class="scroll" href="#'.$href_parsed.'">'. wp_strip_all_tags(do_shortcode($value ?? '')) .'</a></li>';
								break;
							case $previous_heading_level === $matches[0]:
								$output .= '<li>'. $icon .'<a class="scroll" href="#'.$href_parsed.'">'. wp_strip_all_tags(do_shortcode($value ?? '')) .'</a></li>';
								break;

						}

						$previous_heading_level = $matches[0];
					}

				$output .= '</ol>';
			$output .= '</div>';

		$output .= '</div>'."\n";

		return $output;
	}
}


/**
 * Shortcodes
 */

if (! function_exists('mfn_shortcodes')) {
	function mfn_shortcodes(){

		// columns

		add_shortcode('one', 'sc_one');
		add_shortcode('one_second', 'sc_one_second');
		add_shortcode('one_third', 'sc_one_third');
		add_shortcode('two_third', 'sc_two_third');

		add_shortcode('one_fourth', 'sc_one_fourth');
		add_shortcode('two_fourth', 'sc_one_second');
		add_shortcode('three_fourth', 'sc_three_fourth');

		add_shortcode('one_fifth', 'sc_one_fifth');
		add_shortcode('two_fifth', 'sc_two_fifth');
		add_shortcode('three_fifth', 'sc_three_fifth');
		add_shortcode('four_fifth', 'sc_four_fifth');

		add_shortcode('one_sixth', 'sc_one_sixth');
		add_shortcode('two_sixth', 'sc_one_third');
		add_shortcode('three_sixth', 'sc_one_second');
		add_shortcode('four_sixth', 'sc_two_third');
		add_shortcode('five_sixth', 'sc_five_sixth');

		// Content, inline shortcodes

		add_shortcode('alert', 'sc_alert');
		add_shortcode('blockquote', 'sc_blockquote');
		add_shortcode('button', 'sc_button');
		add_shortcode('code', 'sc_code');
		add_shortcode('content_link', 'sc_content_link');
		add_shortcode('counter_inline', 'sc_counter_inline');
		add_shortcode('countdown_inline', 'sc_countdown_inline');
		add_shortcode('divider', 'sc_divider');
		add_shortcode('dropcap', 'sc_dropcap');
		add_shortcode('fancy_link', 'sc_fancy_link');
		add_shortcode('google_font', 'sc_google_font');
		add_shortcode('heading', 'sc_heading_inline');
		add_shortcode('highlight', 'sc_highlight');
		add_shortcode('hr', 'sc_divider'); // do NOT change, alias for [divider] shortcode
		add_shortcode('icon', 'sc_icon');
		add_shortcode('icon_bar', 'sc_icon_bar');
		add_shortcode('icon_block', 'sc_icon_block');
		add_shortcode('idea', 'sc_idea');
		add_shortcode('image', 'sc_image');
		add_shortcode('popup', 'sc_popup');
		add_shortcode('progress_icons', 'sc_progress_icons');
		add_shortcode('share_box', 'sc_share_box');
		add_shortcode('tooltip', 'sc_tooltip');
		add_shortcode('tooltip_image', 'sc_tooltip_image');
		add_shortcode('video_embed', 'sc_video'); // WordPress has default [video] shortcode
		add_shortcode('year', 'sc_year');

		// builder

		add_shortcode('accordion', 'sc_accordion');
		add_shortcode('accordion_item', 'sc_accordion_item');
		add_shortcode('article_box', 'sc_article_box');
		add_shortcode('before_after', 'sc_before_after');
		add_shortcode('blog', 'sc_blog');
		add_shortcode('blog_news', 'sc_blog_news');
		add_shortcode('blog_slider', 'sc_blog_slider');
		add_shortcode('blog_teaser', 'sc_blog_teaser');
		add_shortcode('call_to_action', 'sc_call_to_action');
		add_shortcode('chart', 'sc_chart');
		add_shortcode('clients', 'sc_clients');
		add_shortcode('clients_slider', 'sc_clients_slider');
		add_shortcode('contact_box', 'sc_contact_box');
		add_shortcode('countdown', 'sc_countdown');
		add_shortcode('counter', 'sc_counter');
		add_shortcode('fancy_divider', 'sc_fancy_divider');
		add_shortcode('fancy_heading', 'sc_fancy_heading');
		add_shortcode('faq', 'sc_faq');
		add_shortcode('faq_item', 'sc_faq_item');
		add_shortcode('feature_box', 'sc_feature_box');
		add_shortcode('feature_list', 'sc_feature_list');
		add_shortcode('flat_box', 'sc_flat_box');
		add_shortcode('helper', 'sc_helper');
		add_shortcode('hover_box', 'sc_hover_box');
		add_shortcode('hover_color', 'sc_hover_color');
		add_shortcode('how_it_works', 'sc_how_it_works');
		add_shortcode('icon_box', 'sc_icon_box'); // deprecated: icon_box_new
		add_shortcode('icon_box_2', 'sc_icon_box_2');
		add_shortcode('info_box', 'sc_info_box');
		add_shortcode('list', 'sc_list');
		add_shortcode('livesearch', 'sc_livesearch');
		add_shortcode('map_basic', 'sc_map_basic');
		add_shortcode('map', 'sc_map');
		add_shortcode('offer', 'sc_offer');
		add_shortcode('offer_thumb', 'sc_offer_thumb');
		add_shortcode('opening_hours', 'sc_opening_hours');
		add_shortcode('our_team', 'sc_our_team');
		add_shortcode('our_team_list', 'sc_our_team_list');
		add_shortcode('photo_box', 'sc_photo_box');
		add_shortcode('portfolio', 'sc_portfolio');
		add_shortcode('portfolio_grid', 'sc_portfolio_grid');
		add_shortcode('portfolio_photo', 'sc_portfolio_photo');
		add_shortcode('portfolio_slider', 'sc_portfolio_slider');
		add_shortcode('pricing_item', 'sc_pricing_item');
		add_shortcode('progress_bars', 'sc_progress_bars');
		add_shortcode('promo_box', 'sc_promo_box');
		add_shortcode('quick_fact', 'sc_quick_fact');
		add_shortcode('shop_slider', 'sc_shop_slider');
		add_shortcode('slider', 'sc_slider');
		add_shortcode('sliding_box', 'sc_sliding_box');
		add_shortcode('story_box', 'sc_story_box');
		add_shortcode('tabs', 'sc_tabs');
		add_shortcode('tab', 'sc_tab');
		add_shortcode('table_of_contents', 'sc_table_of_contents');
		add_shortcode('testimonials', 'sc_testimonials');
		add_shortcode('testimonials_list', 'sc_testimonials_list');
		add_shortcode('trailer_box', 'sc_trailer_box');
		add_shortcode('zoom_box', 'sc_zoom_box');

		add_shortcode('collection', 'sc_collection');
		add_shortcode('image_slider', 'sc_image_slider');

		// private

		add_shortcode('bar', 'sc_bar');
		add_shortcode('item', 'sc_item');

		// gallery

		if (! mfn_opts_get('sc-gallery-disable')) {
			remove_shortcode('gallery');
			add_shortcode('gallery', 'sc_gallery');
		}

	}
}

add_action('init', 'mfn_widget_text_content');
add_action('init', 'mfn_shortcodes');
