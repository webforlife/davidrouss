<?php
/**
 * The template for displaying all pages.
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

get_header();
?>

<div id="Content" role="main">
	<div class="content_wrapper clearfix">

		<main class="sections_group">

			<div class="entry-content" itemprop="mainContentOfPage">

				<?php do_action('mfn_before_content'); ?>

				<?php
					while ( have_posts() ) {

						the_post();

						$mfn_builder = new Mfn_Builder_Front(mfn_ID());
						$mfn_builder->show();

					}
				?>

				<section class="section section-page-footer">
					<div class="section_wrapper clearfix">

						<div class="column one page-pager">
							<div class="mcb-column-inner">
								<?php
									wp_link_pages(array(
										'before' => '<div class="pager-single">',
										'after' => '</div>',
										'link_before' => '<span>',
										'link_after' => '</span>',
										'next_or_number' => 'number'
									));
								?>
							</div>
						</div>

					</div>
				</section>

				<?php do_action('mfn_after_content'); ?>

			</div>

			<?php if ( mfn_opts_get( 'page-comments' ) ): ?>
				<section class="section section-page-comments">
					<div class="section_wrapper clearfix">

						<div class="column one comments">
							<div class="mcb-column-inner">
								<?php comments_template('', true); ?>
							</div>
						</div>

					</div>
				</section>
			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

	</div>
</div>

<?php get_footer();
