<?php
/**
 * Template Name: Portfolio
 * Description: A Page Template that display portfolio items.
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

get_header();

global $mfn_global;

if( !empty($mfn_global['portfolio']) ) { ?>

<div id="Content">
	<div class="content_wrapper clearfix">
		<main class="sections_group">
		<?php
			$blog_tmpl = new Mfn_Builder_Front($mfn_global['portfolio']);
			$blog_tmpl->show();
		?>
		</main>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php
}else{

// class

$portfolio_classes 	= '';
$section_class = array();

// class | layout

if( $_GET && key_exists( 'mfn-p', $_GET ) ) {
	$portfolio_classes .= esc_html( $_GET[ 'mfn-p' ] ); // demo
} else {
	$portfolio_classes .= mfn_opts_get( 'portfolio-layout', 'grid' );
}

if( $portfolio_classes == 'list' ){
	$section_class[] = 'full-width';
}

// class | columns

if( $_GET && key_exists( 'mfn-pc', $_GET ) ){
	$portfolio_classes .= ' col-'. esc_html( $_GET[ 'mfn-pc' ] ); // demo
} else {
	$portfolio_classes .= ' col-'. mfn_opts_get( 'portfolio-columns', 3 );
}

if( $_GET && key_exists( 'mfn-pfw', $_GET ) ){
	$section_class[] = 'full-width';
}
if( mfn_opts_get( 'portfolio-full-width' ) ){
	$section_class[] = 'full-width';
}

$section_class = implode( ' ', $section_class );

// isotope

if( $_GET && key_exists( 'mfn-iso', $_GET ) ){
	$isotope = true; // demo
} elseif(  mfn_opts_get( 'portfolio-isotope' ) ) {
	$isotope = true;
} elseif( is_int(strpos(mfn_opts_get('portfolio-layout'), 'masonry')) ){
	$isotope = true;
} else {
	$isotope = false;
}
wp_enqueue_script('mfn-imagesloaded', get_theme_file_uri('/js/plugins/imagesloaded.min.js'), ['jquery'], MFN_THEME_VERSION, true);
wp_enqueue_script('mfn-isotope', get_theme_file_uri('/js/plugins/isotope.min.js'), ['jquery'], MFN_THEME_VERSION, true);

// load more

$load_more = mfn_opts_get( 'portfolio-load-more' );

if( mfn_opts_get('portfolio-infinite-scroll') ) {
	wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
}

// translate

$translate[ 'filter' ] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-filter', 'Filter by' ) : __( 'Filter by', 'betheme' );
$translate[ 'all' ] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-all', 'Show all' ) : __( 'Show all', 'betheme' );
$translate[ 'categories' ] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-categories', 'Categories' ) : __( 'Categories', 'betheme' );
$translate[ 'item-all' ] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-item-all', 'All' ) : __( 'All', 'betheme' );
?>

<div id="Content">
	<div class="content_wrapper clearfix">

		<main class="sections_group">

			<?php if( post_password_required() ): ?>

				<section class="section the_content">
					<div class="section_wrapper">
						<div class="the_content_wrapper">
							<?php echo get_the_password_form(); ?>
						</div>
					</div>
				</section>

			<?php else: ?>

				<div class="extra_content">
					<?php
						$mfn_builder = new Mfn_Builder_Front(mfn_ID(), true);
						$mfn_builder->show();
					?>
				</div>

				<?php if( $filters = mfn_opts_get('portfolio-filters') ): ?>
					<section class="section section-filters">
						<div class="section_wrapper clearfix">

							<?php
								$filters_class = '';

								if( $isotope ){
									$filters_class .= ' isotope-filters';
								}

								if( $filters == 'only-categories' ){
									$filters_class .= ' only only-categories';
								}

								$portfolio_page_id = mfn_wpml_ID( mfn_opts_get( 'portfolio-page' ) );
							?>

							<div id="Filters" class="column one <?php echo esc_attr($filters_class); ?>">
								<div class="mcb-column-inner">

									<ul class="filters_buttons">
										<li class="label"><?php echo esc_attr($translate['filter']); ?></li>
										<li class="categories"><a class="open" href="#"><i class="icon-docs" aria-hidden="true"></i><?php echo esc_html($translate['categories']); ?><i class="icon-down-dir" aria-hidden="true"></i></a></li>
										<?php echo '<li class="reset"><a class="close" data-rel="*" href="'. esc_url(get_page_link($portfolio_page_id)) .'"><i class="icon-cancel" aria-hidden="true"></i> '. esc_html($translate['all']) .'</a></li>'; ?>
									</ul>

									<?php
										// current category
										if( $_GET && key_exists('cat',$_GET) ){
											$current_cat = esc_html( $_GET['cat'] );
										} else {
											$current_cat = false;
										}
									?>

									<div class="filters_wrapper" data-cat="<?php echo esc_attr($current_cat); ?>">
										<ul class="categories">
											<?php
												if( $portfolio_categories = get_terms('portfolio-types') ){
													echo '<li class="reset-inner current-cat"><a class="close" data-rel="*" href="'. esc_url(get_page_link($portfolio_page_id)) .'">'. esc_html($translate['item-all']) .'</a></li>';
													foreach( $portfolio_categories as $category ){
														echo '<li class="'. esc_attr($category->slug) .'"><a data-rel=".category-'. esc_attr($category->slug) .'" href="'. esc_url(get_term_link($category)) .'">'. esc_html($category->name) .'</a></li>';
													}
												}
											?><li class="close"><a href="#"><i class="icon-cancel" aria-label="icon close"></i></a></li>
										</ul>
									</div>

								</div>
							</div>

						</div>
					</section>
				<?php endif; ?>

				<section class="section <?php echo esc_attr($section_class); ?>">
					<div class="section_wrapper clearfix">

						<div class="column one column_portfolio">
							<div class="mcb-column-inner clearfix">
								<div class="portfolio_wrapper isotope_wrapper">

									<?php
										$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
										$portfolio_args = array(
											'post_type' => 'portfolio',
											'posts_per_page' => mfn_opts_get( 'portfolio-posts', 6 ),
											'paged' => $paged,
											'order' => mfn_opts_get( 'portfolio-order', 'DESC' ),
											'orderby' => mfn_opts_get( 'portfolio-orderby', 'date' ),
											'ignore_sticky_posts' => 1,
										);

										// demo

										if( $_GET && key_exists('mfn-iso', $_GET) ){
											$portfolio_args['posts_per_page'] = -1;
										}
										if( $_GET && key_exists('mfn-p', $_GET) && $_GET['mfn-p']=='list' ){
											$portfolio_args['posts_per_page'] = 5;
										}
										if( $_GET && key_exists('mfn-pp', $_GET) ){
											$portfolio_args['posts_per_page'] = esc_html( $_GET['mfn-pp'] );
										}

										$portfolio_query = new WP_Query( $portfolio_args );

									 	echo '<ul class="portfolio_group lm_wrapper isotope '. esc_attr($portfolio_classes) .'">';
									 		echo mfn_content_portfolio( $portfolio_query );
										echo '</ul>';

										if ( mfn_opts_get( 'portfolio-infinite-scroll' ) ):
											echo '<div class="mfn-infinite-load-button">'. mfn_pagination( $portfolio_query, mfn_opts_get('portfolio-posts') ) .'</div>';
										else:
											echo mfn_pagination( $portfolio_query, $load_more );
										endif;

									 	wp_reset_query();
									?>

								</div>
							</div>
						</div>

					</div>
				</section>

			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

	</div>
</div>

<?php } get_footer();
