<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

get_header();
global $mfn_global;
?>

<div id="Content">
	<div class="content_wrapper clearfix">

		<main class="sections_group">
			<?php

			$single_post_nav = array(
				'hide-sticky'	=> false,
				'in-same-term' => false,
			);

			$opts_single_post_nav = mfn_opts_get('prev-next-nav');
			if (isset($opts_single_post_nav['hide-sticky'])) {
				$single_post_nav['hide-sticky'] = true;
			}

			// single post navigation | sticky

			if ( ! $single_post_nav['hide-sticky'] && ! mfn_is_blocks() ) {
				if (isset($opts_single_post_nav['in-same-term'])) {
					$single_post_nav['in-same-term'] = true;
				}

				$post_prev = get_adjacent_post($single_post_nav['in-same-term'], '', true);
				$post_next = get_adjacent_post($single_post_nav['in-same-term'], '', false);

				echo mfn_post_navigation_sticky($post_prev, 'prev', 'icon-left-open-big');
				echo mfn_post_navigation_sticky($post_next, 'next', 'icon-right-open-big');
			}


			$tmp_id = $mfn_global['single_post'];

			if( !empty($_GET['elementor-preview']) ){

				// elementor builder fix

				while (have_posts()) {

					the_post();
					the_content();

				}
				

			}else if( ( empty($_GET['visual']) || !empty($_GET['mfn-template-id']) ) && !empty( $tmp_id ) && is_numeric( $tmp_id ) && get_post_type( $tmp_id ) == 'template' && get_post_status( $tmp_id ) == 'publish' ){
				while (have_posts()) {

					the_post();

					$mfn_builder = new Mfn_Builder_Front( $tmp_id );
					$mfn_builder->show();

					if( !empty(get_post_meta( get_the_ID(), '_elementor_edit_mode', true )) ) {
						echo '<div class="mfn-tmp-elementor-content content_wrapper" style="display: none;">';
							the_content();
						echo '</div>';
					}

				}
			

			}else{

				$is_toolset = get_post_meta( get_the_ID(), '_views_template', true );

				if ( mfn_is_blocks() || $is_toolset || 'builder' == get_post_meta( get_the_ID(), 'mfn-post-template', true ) ) {

					// template: builder

					$single_post_nav = array(
						'hide-sticky'	=> false,
						'in-same-term' => false,
					);

					$opts_single_post_nav = mfn_opts_get('prev-next-nav');
					if (isset($opts_single_post_nav['hide-sticky'])) {
						$single_post_nav['hide-sticky'] = true;
					}

					// single post navigation | sticky

					if ( ! $single_post_nav['hide-sticky'] && ! mfn_is_blocks() ) {
						if (isset($opts_single_post_nav['in-same-term'])) {
							$single_post_nav['in-same-term'] = true;
						}

						$post_prev = get_adjacent_post($single_post_nav['in-same-term'], '', true);
						$post_next = get_adjacent_post($single_post_nav['in-same-term'], '', false);

						echo mfn_post_navigation_sticky($post_prev, 'prev', 'icon-left-open-big');
						echo mfn_post_navigation_sticky($post_next, 'next', 'icon-right-open-big');
					}

					while (have_posts()) {

						the_post();

						$mfn_builder = new Mfn_Builder_Front(get_the_ID());
						$mfn_builder->show();

					}

				} else {

					// template: default

					while (have_posts()) {
						the_post();
						get_template_part('includes/content', 'single');
					}
				}

			}

			?>
		</main>

		<?php get_sidebar(); ?>

	</div>
</div>

<?php get_footer();
