<?php

/**
 * Archive Collectie
 * Template Post Type: collectie
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */


get_header();

// load more
$load_more = mfn_opts_get('blog-load-more');
if ($load_more || mfn_opts_get('blog-infinite-scroll')) {
    wp_enqueue_script('mfn-waypoints', get_theme_file_uri('/js/plugins/waypoints.min.js'), ['jquery'], MFN_THEME_VERSION, true);
    wp_enqueue_script('mfn-imagesloaded', get_theme_file_uri('/js/plugins/imagesloaded.min.js'), ['jquery'], MFN_THEME_VERSION, true);
}

?>

<style>

   .mfn-default-content-buider {

        .section.mcb-section-header.mfn-default-section {
            padding-right: 40px;
            padding-left: 40px;
            padding-top: 120px;
            padding-bottom: 20px;
            z-index: 9999999;
        }

        .section.mcb-section-content.mfn-default-section {
			padding-right: 40px;
			padding-left: 40px;
			padding-bottom: 30px;
		}

        .mfn-item-inline .mcb-item-breadcrumbs-inner {
            border: 1px solid #000000;
            border-radius: 5px;
            padding: 8px 8px 5px;
        }

        .mfn-item-inline .mcb-column-inner .breadcrumbs,
        .mfn-item-inline .mcb-column-inner .breadcrumbs a {
            text-transform: uppercase;
            color: #000000;
        }

        .mfn-item-inline .mcb-column-inner .breadcrumbs a:hover,
        .mfn-item-inline .mcb-column-inner .breadcrumbs a:focus {
            color: #FF7300;
        }

        .mfn-item-inline .mcb-column-inner .breadcrumbs {
            font-size: 0.7em;
        }

        .archive-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 60px;
        }

        .card {
            display: block;
            height: 100%;
            width: 100%;
            background-color: white;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #DBDBDB;
        }

        .card:hover,
        .card:focus {
            text-decoration: none;
        }

        .card:hover .card__title,
        .card:focus .card__title {
            color: #FF7300;
        }

        .card:hover img,
        .card:focus img {
            transform: scale(1.05);
        }

        .card .card__image {
            width: 100%;
            aspect-ratio: 1620 / 1080;
            background-color: #333;
            overflow: hidden;
        }

        .card .card__image img {
            transition: all .3s ease-in-out;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
        }

        .card .card__content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .card .card__title,
        .card .card__price {
            background-position: center center;
            font-size: 1.4em;
            line-height: 1.4em;
            color: #000000;
        }

        .card .card__title {
            transition: all .3s ease-in-out;
            text-transform: uppercase;
            font-family: 'Cormorant Garamond';
            margin-bottom: 0;
        }

        .card .card__metas {
            display: flex;
            flex-wrap: wrap;
            gap: 4px 8px;
        }

        .card .card__meta {
            font-size: 0.8em;
            color: #000000;
            padding: 5px 8px;
            line-height: 1.2em;
            text-transform: uppercase;
            border: 1px solid #6E6E6E;
            border-radius: 4px;
        }

        .pagination .page-numbers {
            display: flex;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            margin: 3px;
            align-items: center;
            background: transparent;
            border: 1px solid #ec6c03;
            transition: all .3s ease-in-out;
            color: #ec6c03;
            text-decoration: none;
        }

        .pagination .page-numbers:hover,
        .pagination .page-numbers:focus {
            color: black;
            border-color: black;
        }

        .pagination .page-numbers.current {
            pointer-events: none;
            background-color: #ec6c03;
            color: white;
        }

        .pagination .page-numbers.prev, .pagination .page-numbers.next {
            flex-shrink: 0;
            padding: 0 10px;
        }
    }

    @media (max-width: 860px) {

        .mfn-default-content-buider {
            .archive-grid {
                grid-template-columns: repeat(2, 1fr);
                margin-bottom: 40px;
            }
        }
    }

    @media (max-width: 767px) {
		.mfn-default-content-buider {

			.section.mcb-section.mfn-default-section,
			.section.mcb-section-content.mfn-default-section {
				padding-right: 20px;
				padding-left: 20px;
			}
        }
    }

    @media (max-width: 520px) {

        .mfn-default-content-buider {

            .archive-grid {
                grid-template-columns: repeat(1, 1fr);
                margin-bottom: 20px;
            }
        }
    }

</style>


<div id="Content" role="main">
    <div class="content_wrapper clearfix">

        <main class="sections_group">

            <div class="entry-content" itemprop="mainContentOfPage">

                <div data-id="54" class="mfn-builder-content mfn-default-content-buider">
                    <section class="section mcb-section mcb-section-header mfn-default-section mcb-section-1kz930ebl  no-margin-h no-margin-v full-width" style="">
                        <div class="mcb-background-overlay"></div>
                        <div class="section_wrapper mfn-wrapper-for-wraps mcb-section-inner mcb-section-inner-1kz930ebl">
                            <div class="wrap mcb-wrap mcb-wrap-x5smamch7 one tablet-one laptop-one mobile-one clearfix" data-desktop-col="one" data-laptop-col="laptop-one" data-tablet-col="tablet-one" data-mobile-col="mobile-one" style="">
                                <div class="mcb-wrap-inner mcb-wrap-inner-x5smamch7 mfn-module-wrapper mfn-wrapper-for-wraps">
                                    <div class="mcb-wrap-background-overlay"></div>
                                    <div class="column mcb-column mcb-item-qh9drgmut one laptop-one tablet-one mobile-one column_breadcrumbs mfn-item-inline" style="">
                                        <div class="mcb-column-inner mfn-module-wrapper mcb-column-inner-qh9drgmut mcb-item-breadcrumbs-inner">
                                            <ul class="breadcrumbs no-link">
                                                <li><a href="/">Home</a><span class="mfn-breadcrumbs-separator">&nbsp;&nbsp;/&nbsp;&nbsp;</span></li>
                                                <li>Collectie</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="section mcb-section mcb-section-content mfn-default-section no-margin-h no-margin-v full-width">
                        <div class="archive-grid">

                            <?php
                                $paged = (get_query_var('paged')) ? get_query_var('paged') : 0;

                                $args = [
                                    'post_type' => 'collectie',
                                    'paged' => $paged,
                                    'posts_per_page' => 12,
                                    'orderby' => 'date',
                                    'order'   => 'DESC',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'collectie-categories',
                                            'operator' => 'NOT EXISTS',
                                        ),
                                    ),
                                ];

                                $query = new WP_Query($args);

                                if ( $query->have_posts() ) :
                                    while ($query->have_posts()) : $query->the_post();
                            ?>

                                <a class="card" href="<?php the_permalink(); ?>">
                                    <figure class="card__image ratio">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?= wp_get_attachment_image(get_post_thumbnail_id(), 'thumbnail'); ?>
                                        <?php endif; ?>
                                    </figure>

                                    <div class="card__content">
                                        <header class="card__header">
                                            <h3 class="card__title">
                                                <?php the_title(); ?>
                                            </h3>
                                        </header>

                                        <div class="card__metas">
                                            <?php if ($amount_of_km = get_field('amount_of_km', get_the_ID())): ?>
                                                <div class="card__meta">
                                                    KM stand: <?= $amount_of_km; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($date_of_registration = get_field('date_of_registration')): ?>
                                                <div class="card__meta">
                                                    Inschrijving: <?= $date_of_registration; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($amount_of_pk = get_field('amount_of_pk')): ?>
                                                <div class="card__meta">
                                                    Vermogen: <?= $amount_of_pk; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>


                                        <?php if ($price = get_field('price')): ?>
                                            <div class="card__price">
                                                <span class="title">€ <?= $price ?> inclusief BTW</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>

                                <?php

                                    endwhile;

                                    $total_pages = $query->max_num_pages;

                                    if ($total_pages > 1) {

                                        $current_page = max(1, get_query_var('paged'));

                                        echo '</div>';
                                        echo '<div class="pagination" style="display: flex; justify-content: center; flex-wrap: wrap; width: 100%;">';


										echo paginate_links(array(
											'base' => get_pagenum_link(1) . '%_%',
											'format' => 'page/%#%',
											'current' => $current_page,
											'total' => $total_pages,
											'prev_text'    => __('« Vorige'),
											'next_text'    => __('Volgende »'),
										));

                                        echo '</div>';

                                    }
                                endif;

                                wp_reset_postdata();
                            ?>

                        </div>

                    </section>



            </div>
            </div>


        </main>
    </div>
</div>

<?php get_footer();
