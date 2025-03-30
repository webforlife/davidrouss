<?php
/**
 * The template for displaying the footer.
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

global $mfn_global;

// footer classes

$footer_options = mfn_opts_get('footer-options');
$footer_classes = [];

if( ! empty( $footer_options['full-width'] ) ){
	$footer_classes[] = 'full-width';
}

$footer_classes = implode( ' ', $footer_classes );

// back_to_top classes

$back_to_top_class = mfn_opts_get('back-top-top');

if ($back_to_top_class == 'hide') {
	$back_to_top_position = false;
} elseif ( $back_to_top_class && strpos($back_to_top_class, 'sticky') !== false ) {
	$back_to_top_position = 'body';
} elseif (mfn_opts_get('footer-hide') == 1) {
	$back_to_top_position = 'footer';
} else {
	$back_to_top_position = 'copyright';
}
?>

<?php if( ! mfn_is_blocks() ): ?>

<?php do_action('mfn_hook_content_after'); ?>

<?php
// footer template start

$is_visual = false;
if( !empty($_GET['visual']) ) $is_visual = true;

// be setup wizard
if( isset( $_GET['mfn-setup-preview'] ) ){
	$mfn_global['footer'] = false;
}

if( $mfn_global['footer'] ){
	get_template_part( 'includes/footer', 'template', array('id' => $mfn_global['footer'], 'visual' => $is_visual) );
}else{ ?>

<?php if ('hide' != mfn_opts_get('footer-style')): ?>

	<footer id="Footer" class="clearfix mfn-footer <?php echo $footer_classes; ?>" role="contentinfo">

		<?php if ($footer_call_to_action = mfn_opts_get('footer-call-to-action')): ?>
		<div class="footer_action">
			<div class="container">
				<div class="column one mobile-one">
          <div class="mcb-column-inner">
						<?php echo do_shortcode($footer_call_to_action); ?>
          </div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php
			$sidebars_count = 0;
			for ($i = 1; $i <= 5; $i++) {
				if (is_active_sidebar('footer-area-'. $i)) {
					$sidebars_count++;
				}
			}

			if ($sidebars_count > 0) {

				$align = mfn_opts_get('footer-align');

				echo '<div class="widgets_wrapper '. $align .'">';
					echo '<div class="container">';

						if( isset($_GET['mfn-f']) ){
							$footer_layout = str_replace('_', ';', $_GET['mfn-f']);
						} else {
							$footer_layout = mfn_opts_get('footer-layout');
						}

						if( $footer_layout ) {

							// Theme Options

							$footer_layout 	= explode(';', $footer_layout);
							$footer_cols = $footer_layout[0];

							for ($i = 1; $i <= $footer_cols; $i++) {
								if (is_active_sidebar('footer-area-'. $i)) {
									echo '<div class="column mobile-one tablet-'. esc_attr($footer_layout[$i]) .' '. esc_attr($footer_layout[$i]) .'">';
                                        echo '<div class="mcb-column-inner">';
										    dynamic_sidebar('footer-area-'. $i);
                                        echo '</div>';
									echo '</div>';
								}
							}

						} else {

							// default with equal width

							$sidebar_class = '';
							switch ($sidebars_count) {
								case 2: $sidebar_class = 'one-second'; break;
								case 3: $sidebar_class = 'one-third'; break;
								case 4: $sidebar_class = 'one-fourth'; break;
								case 5: $sidebar_class = 'one-fifth'; break;
								default: $sidebar_class = 'one';
							}

							for ($i = 1; $i <= 5; $i++) {
								if (is_active_sidebar('footer-area-'. $i)) {
									echo '<div class="column mobile-one tablet-'. esc_attr($sidebar_class) .' '. esc_attr($sidebar_class) .'">';
                                        echo '<div class="mcb-column-inner">';
										    dynamic_sidebar('footer-area-'. $i);
                                        echo '</div>';
									echo '</div>';
								}
							}

						}

					echo '</div>';
				echo '</div>';
			}
		?>

		<?php if (mfn_opts_get('footer-hide') != 1 && ! apply_filters( 'betheme_disable_footer', false ) ): ?>

			<div class="footer_copy">
				<div class="container">
					<div class="column one mobile-one">
            <div class="mcb-column-inner">

              <?php
                if ($back_to_top_position == 'copyright') {
                  echo '<a id="back_to_top" class="button footer_button" href="" aria-label="Back to top icon"><i class="icon-up-open-big"></i></a>';
                }
              ?>

              <div class="copyright">
                <?php
                  if (mfn_opts_get('footer-copy')) {
                    echo do_shortcode(mfn_opts_get('footer-copy'));
                  } else {
                    echo '&copy; '. esc_html(date('Y')) .' Betheme by <a href="https://muffingroup.com" target="_blank">Muffin group</a> | All Rights Reserved | Powered by <a href="https://wordpress.org" target="_blank">WordPress</a>';
                  }
                ?>
              </div>

              <?php
                if (has_nav_menu('social-menu-bottom')) {
                  mfn_wp_social_menu_bottom();
                } else {
                  get_template_part('includes/include', 'social');
                }
              ?>

            </div>
					</div>
				</div>
			</div>

		<?php endif; ?>

		<?php
			if ($back_to_top_position == 'footer') {
				echo '<a id="back_to_top"  aria-label="Back to top icon" class="button footer_button in_footer" href=""><i class="icon-up-open-big"></i></a>';
			}
		?>

	</footer>
<?php endif; ?>

<?php } ?> <!-- End Footer Template -->

</div>

<div id="body_overlay"></div>

<?php
	// search overlay
	if ( mfn_opts_get('search-overlay') ) {
		echo '<div id="search-overlay"></div>';
	}

	// side slide menu
	if ( mfn_opts_get('responsive-mobile-menu') && !$mfn_global['header'] ) {
		get_template_part('includes/header', 'side-slide');
	}

	// login form
	get_template_part('includes/header', 'login');

	// gdpr 2.0
	if( mfn_opts_get('gdpr2') ){
		get_template_part( 'includes/gdpr2' );
	}

	if ($back_to_top_position == 'body') {
		echo '<a id="back_to_top" aria-label="Back to top icon" class="button footer_button '. esc_attr($back_to_top_class) .'" href=""><i class="icon-up-open-big"></i></a>';
	}

	if( empty($_GET['visual']) && empty($_GET['elementor-preview']) && function_exists('is_woocommerce') && !is_cart() && !is_checkout() && mfn_opts_get('shop-sidecart') ){
		get_template_part('includes/woocommerce', 'cart');
	}
?>

<?php if (mfn_opts_get('popup-contact-form')): ?>
	<div id="popup_contact">
		<a class="button footer_button" href="#"><i class="<?php echo esc_attr(mfn_opts_get('popup-contact-form-icon', 'icon-mail-line')); ?>"></i></a>
		<div class="popup_contact_wrapper">
			<?php echo do_shortcode(mfn_opts_get('popup-contact-form')); ?>
			<span class="arrow"></span>
		</div>
	</div>
<?php endif; ?>

<?php
if( empty($_GET['visual']) ){
	$mfn_popups = mfn_addons_ID('popup');

	if( isset($mfn_popups) && is_array($mfn_popups) && count($mfn_popups) > 0){
		foreach ($mfn_popups as $popup_tmpl_id) {
			if( get_post_status($popup_tmpl_id) == 'publish' ){
				$popup = new MfnPopup($popup_tmpl_id);
				$popup->render();
			}
		}
	}
}

$used_sidemenus = array();

if( empty($_GET['visual']) ){

	if( !empty($mfn_global['header']) && !empty(get_post_meta($mfn_global['header'], 'mfn-template-sidemenu', true)) ){

		// sidemenu for header burger
		$mfn_header_sidemenus = json_decode(get_post_meta($mfn_global['header'], 'mfn-template-sidemenu', true), true);

		// remove duplicates if there are more than one burger with the same menu
		$mfn_header_sidemenus = array_values(array_unique($mfn_header_sidemenus));

		foreach($mfn_header_sidemenus as $mfnsm){
			if( is_numeric($mfnsm) && get_post_status($mfnsm) == 'publish' && get_post_type($mfnsm) == 'template' ) {
				if( !in_array($mfnsm, $used_sidemenus) ) $used_sidemenus[] = $mfnsm;
				$sidemenu = new MfnSideMenu($mfnsm);
				$sidemenu->render();
			}
		}
	}

	if( !empty($mfn_global['sidemenu']) && !in_array($mfn_global['sidemenu'], $used_sidemenus) && is_numeric($mfn_global['sidemenu']) ){
		// global sidemenu
		$sidemenu = new MfnSideMenu($mfn_global['sidemenu']);
		$sidemenu->render(false);
	}

}

?>

<?php else: // mfn_is_blocks() ?>

	</div>

<?php endif; ?>

<?php do_action('mfn_hook_bottom'); ?>

<?php do_action('mfn_wp_footer_before'); ?>

<?php wp_footer(); ?>

<?php do_action('mfn_demo_builder'); ?>

</body>
</html>
