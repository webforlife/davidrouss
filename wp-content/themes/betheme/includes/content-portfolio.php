<?php
/**
 * The template for displaying content in the template-portfolio.php template
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

if( ! function_exists('mfn_content_portfolio') ){
	function mfn_content_portfolio( $query = false, $style = false, $link = false, $title_tag = false ){

		global $wp_query;
		$output = '';

		$translate['readmore'] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-readmore', 'Read more' ) : __( 'Read more', 'betheme' );
		$translate['client'] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-client', 'Client' ) : __( 'Client', 'betheme' );
		$translate['date'] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-date', 'Date' ) : __( 'Date', 'betheme' );
		$translate['website'] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-website', 'Website' ) : __( 'Website', 'betheme' );
		$translate['view'] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-view', 'View website' ) : __( 'View website', 'betheme' );

		$button_text = $translate['readmore'];

		// query

		if( ! $query ){
			$query = $wp_query;
		}

		// style

		if( ! $style ) {
			if( $_GET && key_exists('mfn-p', $_GET) ) {
				$style = esc_html( $_GET['mfn-p'] ); // demo
			} else {
				$style = mfn_opts_get( 'portfolio-layout', 'grid' );
			}
		}

		// title tag

		$title_class = '';
		if( ! empty($title_tag) ){
			if( 'p.lead' == $title_tag ){
				$title_tag = 'p';
				$title_class = 'lead';
			}
		}

		// list meta

		$list_meta = mfn_opts_get( 'portfolio-meta' );

		$external = !empty($link) ? $link : mfn_opts_get( 'portfolio-external' );

		if ( $query->have_posts() ){
			while ( $query->have_posts() ){

				$query->the_post();

				$item_class = array();
				$categories = '';

				$terms = get_the_terms( get_the_ID(), 'portfolio-types' );
				if( is_array( $terms ) ){
					foreach( $terms as $term ){
						$item_class[] = 'category-'. $term->slug;
						$categories .= '<a href="'. site_url() .'/portfolio-types/'. $term->slug .'">'. $term->name .'</a>, ';
					}
					$categories = substr( $categories , 0, -2 );
				}

				$item_class[] = get_post_meta( get_the_ID(), 'mfn-post-size', true );
				$item_class[] = has_post_thumbnail() ? 'has-thumbnail' : 'no-thumbnail';
				$item_class = implode(' ', $item_class);

				$ext_link = get_post_meta( get_the_ID(), 'mfn-post-link', true );
				$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );

				// item backgrounds

				// style: list

				if( $item_bg_image = get_post_meta( get_the_ID(), 'mfn-post-bg', true ) ){
					$item_bg_image = 'background-image:url('. esc_url($item_bg_image) .');';
				}

				// style: masonry hover

				$item_bg_class = 'bg-'. mfn_brightness( mfn_opts_get( 'background-imageframe-link', '#fff', [ 'key' => 'normal' ] ), 169 );

				if( $item_bg_color = get_post_meta( get_the_ID(), 'mfn-post-bg-hover', true ) ){

					$item_bg_class = 'bg-'. mfn_brightness( $item_bg_color, 169 );
					$item_bg_color = 'background-color:'. mfn_hex2rgba( $item_bg_color, 0.9 ) .';';

				}

				// image link

				if( in_array( $external, array('disable','popup') ) ){

					// disable details & link popup
					if( ! empty($large_image_url[0]) ){
						$link_before_escaped 	= '<a class="link" href="'. esc_url($large_image_url[0]) .'" rel="prettyphoto">';
					}

				} elseif( $external && $ext_link ){

					// link to project website
					$link_before_escaped 	= '<a class="link" href="'. esc_url($ext_link) .'" target="'. esc_attr($external) .'">';

				} else {

					// link to project details
					$link_before_escaped 	= '<a class="link" href="'. esc_url(get_permalink()) .'">';

				}

				// accessibility

				if ( mfn_opts_get('repetitive-links') ) {
					$button_text = mfn_repetitive_link( get_permalink(), $translate['readmore'] );
				}


				// output -----

				$output .= '<li class="portfolio-item isotope-item '. esc_attr($item_class) .'">';

					if( $style == 'exposure' ){

						// style: Exposure

						$output .= $link_before_escaped;

							// photo

							$output .= '<div class="image-wrapper scale-with-grid">';
								$output .= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class'=>'scale-with-grid', 'itemprop'=>'image' ) );
								$output .= '<div class="mask"></div>';
							$output .= '</div>';

							// title

							$output .= '<div class="desc-inner">';
								$output .= '<div class="section_wrapper">';
									$output .= '<div class="desc-wrapper-inner">';

										$output .= '<div class="line"></div>';
										$title_tag = $title_tag ? $title_tag : 'h2';
										$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="entry-title '. esc_attr($title_class) .'" itemprop="headline">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</'. mfn_allowed_title_tag($title_tag) .'>';

										$output .= '<div class="desc-wrapper">';
											$output .= get_the_excerpt();
										$output .= '</div>';

									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';

						$output .= '</a>';

						// details

						$output .= '<div class="details-wrapper">';
							$output .= '<div class="section_wrapper">';
								$output .= '<div class="details-wrapper-inner">';

									if( $link = get_post_meta( get_the_ID(), 'mfn-post-link', true ) ){
										$output .= '<div class="column one-fourth website">';
											$output .= '<h5 class="label">'. esc_html($translate['website']) .'</h5>';
											$output .= '<h5><a target="_blank" href="'. esc_url($link) .'"><i class="icon-forward" aria-hidden="true"></i>'. esc_html($translate['view']) .'</a></h5>';
										$output .= '</div>';
									}

									if( $client = get_post_meta( get_the_ID(), 'mfn-post-client', true ) ){
										$output .= '<div class="column one-fourth client">';
											$output .= '<h5 class="label">'. esc_html($translate['client']) .'</h5>';
											$output .= '<h5>'. esc_html($client) .'</h5>';
										$output .= '</div>';
									}

									if( isset( $list_meta['date'] ) ){
										$output .= '<div class="column one-fourth date">';
											$output .= '<h5 class="label">'. esc_html($translate['date']) .'</h5>';
											$output .= '<h5>'. esc_html(get_the_date()) .'</a></h5>';
										$output .= '</div>';
									}

								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';

					} elseif( $style == 'masonry-minimal' ){

						// style: Masonry Minimal

							$output .= '<div class="image_frame scale-with-grid">';
								$output .= '<div class="image_wrapper">';
									$output .= mfn_post_thumbnail( get_the_ID(), 'portfolio', 'masonry-minimal', $external );
								$output .= '</div>';
							$output .= '</div>';


					} elseif( $style == 'masonry-hover' ){

						// style: Masonry Hover

						$output .= '<div class="masonry-hover-wrapper">';

							// desc

							$output .= '<div class="hover-desc '. esc_attr($item_bg_class) .'" style="'. esc_attr($item_bg_color) .'">';

								$output .= '<div class="desc-inner">';
									$title_tag = $title_tag ? $title_tag : 'h3';
									$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="entry-title '. esc_attr($title_class) .'" itemprop="headline">'. $link_before_escaped . wp_kses(get_the_title(), mfn_allowed_html()) .'</a></'. mfn_allowed_title_tag($title_tag) .'>';
									$output .= '<div class="desc-wrapper">';
										$output .= get_the_excerpt();
									$output .= '</div>';

								$output .= '</div>';

								if( $external != 'disable' ){
									$output .= '<div class="links-wrapper clearfix">';

										if( ! in_array( $external, array('_self','_blank') ) ){
											if( ! empty($large_image_url[0]) ){
												$output .= '<a class="zoom" href="'. esc_url($large_image_url[0]) .'" rel="prettyphoto"><i class="icon-search" aria-label="'. __('zoom image', 'betheme') .'"></i></a>';
											}
										}
										if( $ext_link ){
											$output .= '<a class="external" target="_blank" href="'. esc_url($ext_link) .'" ><i class="icon-forward" aria-label="'. __('go to external link', 'betheme') .'"></i></a>';
										}
										if( ! $external ){
											$output .= $link_before_escaped. '<i class="icon-link" aria-label="'. __('go to link', 'betheme') .'"></i></a>';
										}

									$output .= '</div>';
								}

							$output .= '</div>';

							// photo

							$output .= '<div class="image-wrapper scale-with-grid">';
								$output .= $link_before_escaped;
									$output .= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class'=>'scale-with-grid', 'itemprop'=>'image' ) );
								$output .= '</a>';
							$output .= '</div>';

						$output .= '</div>';

					} else {

						// style: default

						$output .= '<div class="portfolio-item-fw-bg" style="'. esc_attr($item_bg_color) . esc_attr($item_bg_image) .'">';

							$output .= '<div class="portfolio-item-fill"></div>';

							// style: List | Section Wrapper

							if( $style == 'list' ){
								$output .= '<div class="section_wrapper">';
							}

								// style: list | desc

								if( $style == 'list' ){

									$output .= '<div class="list_style_header">';
										$title_tag = $title_tag ? $title_tag : 'h3';
										$output .= '<'. mfn_allowed_title_tag($title_tag). ' class="entry-title '. esc_attr($title_class) .'" itemprop="headline">'. $link_before_escaped . wp_kses(get_the_title(), mfn_allowed_html()) .'</a></'. mfn_allowed_title_tag($title_tag) .'>';
										$output .= '<div class="links_wrapper">';
											$output .= '<a href="#" class="button the-icon portfolio_prev_js"><span class="button_icon"><i class="icon-up-open" aria-label="'. __('previous project', 'betheme') .'"></i></span></a>';
											$output .= '<a href="#" class="button the-icon portfolio_next_js"><span class="button_icon"><i class="icon-down-open" aria-label="'. __('next project', 'betheme') .'"></i></span></a>';
											$output .= '<a href="'. esc_url(get_permalink()) .'" class="button button_theme has-icon"><span class="button_icon"><i class="icon-link" aria-label="'. __('all projects', 'betheme') .'"></i></span><span class="button_label">'. $button_text .'</span></a>';
										$output .= '</div>';
									$output .= '</div>';

								}

								// style: default | photo

								$output .= '<div class="image_frame scale-with-grid">';
									$output .= '<div class="image_wrapper">';
										$output .= mfn_post_thumbnail( get_the_ID(), 'portfolio', $style, $external );
									$output .= '</div>';
								$output .= '</div>';

								// style: default | desc

								$output .= '<div class="desc">';

									if( $style != 'list' ){

										$output .= '<div class="title_wrapper">';
											$title_tag = $title_tag ? $title_tag : 'h5';
											$output .= '<'. mfn_allowed_title_tag($title_tag) .' class="entry-title '. esc_attr($title_class) .'" itemprop="headline">'. $link_before_escaped . wp_kses(get_the_title(), mfn_allowed_html()) .'</a></'. mfn_allowed_title_tag($title_tag) .'>';
											$output .= '<div class="button-love">'. mfn_love() .'</div>';
										$output .= '</div>';

									}

									$output .= '<div class="details-wrapper">';
										$output .= '<dl>';

											if( $client = get_post_meta( get_the_ID(), 'mfn-post-client', true ) ){
												$output .= '<dt>'. esc_html($translate['client']) .'</dt>';
												$output .= '<dd>'. esc_html($client) .'</dd>';
											}

											if( isset( $list_meta['date'] ) ){
												$output .= '<dt>'. esc_html($translate['date']) .'</dt>';
												$output .= '<dd>'. esc_html(get_the_date()) .'</dd>';
											}

											if( $link = get_post_meta( get_the_ID(), 'mfn-post-link', true ) ){
												$output .= '<dt>'. esc_html($translate['website']) .'</dt>';
												$output .= '<dd><a target="_blank" href="'. esc_url($link) .'"><i class="icon-forward" aria-hidden="true"></i>'. esc_html($translate['view']) .'</a></dd>';
											}

										$output .= '</dl>';
									$output .= '</div>';

									$output .= '<div class="desc-wrapper">';
										$output .= get_the_excerpt();
									$output .= '</div>';

								$output .= '</div>';

							// style: List | end: Section Wrapper

							if( $style == 'list' ){
								$output .= '</div>';
							}

						$output .= '</div>';

					}

				$output .= '</li>';

			}
		}

		return $output;
	}
}
