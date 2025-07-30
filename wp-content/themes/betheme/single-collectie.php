<?php

/**
 * Single Collectie
 * Template Post Type: collectie
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */


get_header();

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

		.mcb-section-inner {
			align-items: stretch;
		}

		.mcb-section .mcb-wrap.one-third > .mcb-wrap-inner {
			border-style: solid;
			border-color: rgba(138, 138, 138, 0.7686274509803922);
			border-width: 1px 1px 1px 1px;
			border-radius: 4px 4px 4px 4px;
			padding-top: 40px;
			padding-left: 40px;
			padding-right: 40px;
			padding-bottom: 30px;
			margin-left: 40px;
		}

		.mcb-wrap-inner {
			display: flex;
			align-content: flex-start;
			align-items: flex-start;
			flex-wrap: wrap;
			position: relative;
			width: 100%;
			align-self: stretch;
		}

		.mcb-wrap-inner.mcb-wrap-inner-head {
			border-style: solid;
			border-color: rgba(138, 138, 138, 0.7686274509803922);
			border-width: 0 0 1px 0;
			padding-bottom: 20px;
			justify-content: space-between;
			align-items: center;
		}

		.mcb-wrap-inner .mcb-item-heading-inner {
			margin-top: 20px;
			margin-bottom: 5px;
		}

		.mcb-wrap-inner .mcb-item-heading-inner .title {
			color: #000000;
			font-size: 1.4em;
			line-height: 1.4em;
		}

		.mcb-wrap-inner .mcb-item-heading-inner h1.title {
			background-position: center center;
			font-size: 4em;
			line-height: 1.2em;
			text-transform: uppercase;
		}

		.section_wrapper .mfn-item-inline .mcb-column-inner.button-outline {
			border-style: solid;
			border-color: #6E6E6E;
			border-width: 1px 1px 1px 1px;
			border-radius: 4px 4px 4px 4px;
			padding-top: 5px;
			padding-right: 8px;
			padding-bottom: 5px;
			padding-left: 8px;
			margin-top: 10px;
			margin-right: 10px !important;
		}

		.mfn-item-inline .mcb-column-inner .breadcrumbs,
		.mfn-item-inline .mcb-column-inner .breadcrumbs a {
			text-transform: uppercase;
			color: #000000;
		}

		.mfn-item-inline .mcb-column-inner .breadcrumbs {
			font-size: 0.7em;
		}

		.mcb-usp-list {
			margin-top: 20px;
		}

		.mfn-item-inline .mcb-column-inner .title {
			font-size: 0.7em;
			color: #000000;
			line-height: 1.2em;
			text-transform: uppercase;
		}

		.mfn-item-inline .mcb-column-inner.mcb-item-heading-inner .title {
			font-size: 0.8em;
		}

		.grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
			gap: 20px;
		}

		.grid-item {
			padding-bottom: 20px;
			border-radius: 0 0 1px 0;
			border-style: solid;
			border-color: #E9E9E9;
			border-width: 0 0 1px 0;
		}

		.ui-tabs .ui-tabs-nav li.ui-tabs-tab {
			margin-bottom: 0;
			padding-bottom: 0;
			border: none;
		}

		.ui-tabs .ui-tabs-nav li.ui-tabs-tab a {
			text-align: left;
			margin-right: 40px;
			padding: 0;
		}

		.ui-tabs .ui-tabs-nav li.ui-tabs-tab a h4 {
			font-size: 18px;
			margin-bottom: 0;
		}

		.ui-tabs .ui-tabs-nav li.ui-tabs-selected a h4,
		.ui-tabs .ui-tabs-nav li.ui-state-active a h4 {
			color: #FF7300;
			margin-bottom: 0;
		}

		.ui-tabs .ui-tabs-nav li.ui-tabs-selected:after,
		.ui-tabs .ui-tabs-nav li.ui-state-active:after,
		.ui-tabs .ui-tabs-nav li.ui-tabs-selected a:after,
		.ui-tabs .ui-tabs-nav li.ui-state-active a:after {
			display: none;
		}

		.mcb-section .mcb-wrap .button:hover,
		.mcb-section .mcb-wrap .button:focus {
			background: rgba(152, 152, 152, 0);
			color: #000000;
		}

		.mcb-section .mcb-wrap .button-secondary,
		.mcb-section .mcb-wrap .button-secondary {
			background-color: rgba(152, 152, 152, 0.7);
			font-size: 0.8em;
			text-transform: uppercase;
			border-style: solid;
			border-width: 1px 1px 1px 1px;
			border-color: rgba(152, 152, 152, 0.7);
			border-radius: 4px 4px 4px 4px;
		}

		.mcb-section .mcb-wrap .button-secondary:hover,
		.mcb-section .mcb-wrap .button-secondary:focus {
			background: rgba(152, 152, 152, 0);
			color: #000000;
			border-color: #000000;
		}
	}

	.mfn-builder-content .swiper {
		height: 100%;
	}

	.mfn-builder-content .swiper-button-prev,
	.mfn-builder-content .swiper-button-next {
		width: 48px;
		height: 48px;
		margin-top: -24px;
		background-color: rgba(255, 255, 255, 0.7);
	}

	.mfn-builder-content .swiper-button-prev svg,
	.mfn-builder-content .swiper-button-next svg {
		width: 50%;
		height: 50%;
		fill: black;
	}

	.mfn-builder-content .swiper-button-prev {
		left: 0;
		border-top-right-radius: 4px;
		border-bottom-right-radius: 4px;
	}

	.mfn-builder-content .swiper-button-next {
		right: 0;
		border-top-left-radius: 4px;
		border-bottom-left-radius: 4px;
	}

	.entry-content-space {
		padding-bottom: 80px;
	}

	@media (max-width: 767px) {
		.mfn-default-content-buider {

			.section.mcb-section.mfn-default-section,
			.section.mcb-section-content.mfn-default-section {
				padding-right: 20px;
				padding-left: 20px;
			}

			.mcb-section .mcb-wrap.one-third > .mcb-wrap-inner {
				margin-right: 0px;
				margin-left: 0px;
				margin-top: 20px;
				padding-bottom: 40px;
			}

			.ui-tabs .ui-tabs-nav {
				border-bottom: none;
			}

			.ui-tabs .ui-tabs-nav li.ui-tabs-tab a {
				margin-right: 0;
				padding: 0;
			}

			.ui-tabs .ui-tabs-nav li.ui-tabs-tab {
				margin-bottom: 10px;
				padding-bottom: 10px;
				border: none;
				border-bottom: 1px solid rgba(0, 0, 0, 0.08);
			}
		}

		.entry-content-space {
			padding-bottom: 40px;
		}

		.mfn-builder-content .swiper-button-prev,
		.mfn-builder-content .swiper-button-next {
			width: 32px;
			height: 32px;
			margin-top: -16px;
		}
	}


</style>

<div id="Content" role="main">
	<div class="content_wrapper clearfix">

		<main class="sections_group">

			<div class="entry-content-space" itemprop="mainContentOfPage">


				<div data-id="86" class="mfn-builder-content mfn-default-content-buider">
					<section class="section mcb-section mcb-section-header mfn-default-section no-margin-h no-margin-v full-width">
						<div class="mcb-background-overlay"></div>
						<div class="section_wrapper mfn-wrapper-for-wraps mcb-section-inner">
							<div class="wrap mcb-wrap one tablet-one laptop-one mobile-one clearfix" data-desktop-col="one" data-laptop-col="laptop-one" data-tablet-col="tablet-one" data-mobile-col="mobile-one">
								<div class="mcb-wrap-inner mcb-wrap-inner-head mfn-module-wrapper mfn-wrapper-for-wraps">
									<div class="mcb-wrap-background-overlay"></div>
									<div class="column mcb-column three-fourth laptop-five-sixth tablet-five-sixth mobile-one column_heading">
										<div class="mcb-column-inner mfn-module-wrapper mcb-item-heading-inner">
											<h1 class="title"><?= the_title() ?></h1>
										</div>
									</div>
									<div class="column mcb-column one-second laptop-one-second tablet-one-second mobile-one column_heading">
										<div class="mcb-column-inner mfn-module-wrapper mcb-item-heading-inner">
											<span class="title">
												<?php if ($subtitle = get_field('subtitle')): ?>
													<?= $subtitle ?>
												<?php endif; ?>
											</span>
										</div>
									</div>
									<div class="column mcb-column one laptop-one tablet-one mobile-one column_breadcrumbs mfn-item-inline">
										<div class="mcb-column-inner button-outline mfn-module-wrapper mcb-item-breadcrumbs-inner">
											<ul class="breadcrumbs no-link">
												<li><a href="<?= get_post_type_archive_link('collectie'); ?>">Collectie</a><span class="mfn-breadcrumbs-separator">&nbsp;&nbsp;/&nbsp;&nbsp;</span></li>
												<li><?= the_title() ?></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
					<section class="section mcb-section mcb-section-content mfn-default-section no-margin-h no-margin-v full-width">
						<div class="mcb-background-overlay"></div>
						<div class="section_wrapper mfn-wrapper-for-wraps mcb-section-inner">
							<div class="wrap mcb-wrap two-third tablet-two-third laptop-two-third mobile-one clearfix" data-desktop-col="two-third" data-laptop-col="laptop-two-third" data-tablet-col="tablet-two-third" data-mobile-col="mobile-one">
								<div class="mcb-wrap-inner mfn-module-wrapper mfn-wrapper-for-wraps">
									<div class="mcb-wrap-background-overlay"></div>
									<div class="column mcb-column one laptop-one tablet-one mobile-one column_image" style="height: 100%;">
										<div class="mcb-column-inner mfn-module-wrapper mcb-item-image-inner" style="height: 100%;">

											<?php if($popupId = get_post_meta(get_the_ID(), 'popup_video_id', true)): ?>
												<button class="js-open-dialog" style="position: absolute; background-color: #ec6c03; display: flex; align-items: center; justify-content: center; top: 0; right: 0; width: 40px; height: 40px; border-radius: 0; border-bottom-left-radius: 4px; color: rgb(0, 0, 0); cursor: pointer; z-index: 4;">
													<i class="icon-videocam-line" style="font-size: 1.5em; color: white;"></i>
												</button>
												<dialog class="dialog js-dialog">
													<div class="dialog__inner">
														<button class="dialog__close js-close-dialog">Close</button>
														<div class="dialog__body js-dialog-video" data-src="<?= $popupId; ?>">
														</div>
													</div>
												</dialog>
											<?php endif; ?>

											<?php if ( !empty($images = get_field('images')) ): ?>

												<div class="swiper js-collection-slider">
													<div class="swiper-wrapper">
														<?php foreach ( $images as $key => $image ) : ?>
															<div class="swiper-slide">
																<div class="image_frame image_item no_link scale-with-grid alignnone no_border mfn-coverimg" style="height: 100%; border-radius: 4px;">
																	<div class="image_wrapper mfn-coverimg-wrapper" style="height: 100%;">
																		<?= wp_get_attachment_image($image, 'full') ?>
																	</div>
																</div>
															</div>
														<?php endforeach; ?>
													</div>
													<div class="swiper-button-prev">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
													</div>
													<div class="swiper-button-next">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
													</div>
												</div>
											<?php else: ?>
												<div class="image_frame image_item no_link scale-with-grid alignnone no_border mfn-coverimg" style="height: 100%; border-radius: 4px;">
													<div class="image_wrapper mfn-coverimg-wrapper" style="height: 100%;">
														<?= get_the_post_thumbnail($post, 'full') ?>
													</div>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
							<div class="wrap mcb-wrap one-third tablet-one-third laptop-one-third mobile-one clearfix" data-desktop-col="one-third" data-laptop-col="laptop-one-third" data-tablet-col="tablet-one-third" data-mobile-col="mobile-one">
								<div class="mcb-wrap-inner mfn-module-wrapper mfn-wrapper-for-wraps" style="align-content: space-between;">
									<div class="mcb-wrap-background-overlay"></div>
									<div class="wrap mcb-wrap one tablet-one laptop-one mobile-one mfn-nested-wrap clearfix" data-desktop-col="one" data-laptop-col="laptop-one" data-tablet-col="tablet-one" data-mobile-col="mobile-one">
										<div class="mcb-wrap-inner mfn-module-wrapper mfn-wrapper-for-wraps">
											<div class="mcb-wrap-background-overlay"></div>
											<?php if ($amount_of_km = get_field('amount_of_km')): ?>
											<div class="column mcb-column one laptop-one tablet-one mobile-one column_heading mfn-item-inline">
												<div class="mcb-column-inner button-outline mfn-module-wrapper mcb-item-heading-inner">
													<span class="title">KM Stand: <?= $amount_of_km; ?> KM</span>
												</div>
											</div>
											<?php endif; ?>
											<?php if ($amount_of_pk = get_field('amount_of_pk')): ?>
											<div class="column mcb-column one laptop-one tablet-one mobile-one column_heading mfn-item-inline">
												<div class="mcb-column-inner button-outline mfn-module-wrapper mcb-item-heading-inner">
													<span class="title">Vermogen: <?= $amount_of_pk ?> PK</span>

												</div>
											</div>
											<?php endif; ?>
											<?php if ($capacity = get_field('capacity')): ?>
											<div class="column mcb-column one laptop-one tablet-one mobile-one column_heading mfn-item-inline">
												<div class="mcb-column-inner button-outline mfn-module-wrapper mcb-item-heading-inner">
													<span class="title">Cilinderinhoud: <?= $capacity ?> cm³</span>
												</div>
											</div>
											<?php endif; ?>
											<?php if ($gear_box = get_field('gear_box')): ?>
											<div class="column mcb-column one laptop-one tablet-one mobile-one column_heading mfn-item-inline">
												<div class="mcb-column-inner button-outline mfn-module-wrapper mcb-item-heading-inner">
													<span class="title">Transmissie: <?= $gear_box ?></span>
												</div>
											</div>
											<?php endif; ?>
											<?php if ($price = get_field('price')): ?>
												<?php $terms = get_the_terms( get_the_ID(), 'collectie-categories' ); ?>
												<?php $sold = false; ?>

												<?php if ($terms): ?>
												<?php foreach ( $terms as $key => $value ) {
													if ( $value->slug === 'archief' ) {
														$sold = true;
													}
												}; ?>

												<?php endif; ?>
											<div class="column mcb-column one laptop-one tablet-one mobile-one column_heading">
												<div class="mcb-column-inner button-outline mfn-module-wrapper mcb-item-heading-inner">
													<?php if($sold): ?>
														<span class="title">VERKOCHT</span>
													<?php else: ?>
														<span class="title">€ <?= $price ?></span>
													<?php endif; ?>
												</div>
											</div>
											<?php endif; ?>
										</div>
									</div>
									<?php if ($usps = get_field('usps', 'options')): ?>

										<div class="wrap mcb-wrap one tablet-one laptop-one mobile-one mfn-nested-wrap clearfix" data-desktop-col="one" data-laptop-col="laptop-one" data-tablet-col="tablet-one" data-mobile-col="mobile-one">
											<div class="mcb-wrap-inner mfn-module-wrapper mfn-wrapper-for-wraps">
												<div class="mcb-wrap-background-overlay"></div>
												<div class="column mcb-column one laptop-one tablet-one mobile-one column_column">
													<div class="mcb-column-inner mfn-module-wrapper mcb-item-column-inner mcb-usp-list">
														<div class="column_attr mfn-inline-editor clearfix">
															<?php foreach ( $usps as $key => $usp ) : ?>
																<div>
																	<i class="fas fa-check-circle" style="color:rgba(255,115,0,0.7686274509803922)" aria-hidden="true"></i>
																	<?= $usp['title'] ?>
																</div>
															<?php endforeach; ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</section>

					<section class="section mcb-section mcb-section-content mfn-default-section no-margin-h no-margin-v full-width">
						<div class="mcb-background-overlay"></div>
						<div class="section_wrapper mfn-wrapper-for-wraps mcb-section-inner">
							<div class="wrap mcb-wrap two-third tablet-two-third laptop-two-third mobile-one clearfix" data-desktop-col="two-third" data-laptop-col="laptop-two-third" data-tablet-col="tablet-two-third" data-mobile-col="mobile-one">
								<div class="mcb-wrap-inner mfn-module-wrapper mfn-wrapper-for-wraps" style="align-content: space-around; border-style: solid; border-color: rgba(138, 138, 138, 0.7686274509803922); border-width: 1px 1px 1px 1px; border-radius: 4px 4px 4px 4px;">
									<div class="mcb-wrap-background-overlay"></div>
									<div class="column mcb-column one laptop-one tablet-one mobile-one column_tabs" style="height: 100%; padding-top: 40px; padding-right: 40px; padding-bottom: 20px; padding-left: 40px;">
										<div class="mcb-column-inner mfn-module-wrapper">
											<div class="jq-tabs tabs_wrapper tabs_horizontal ui-tabs ui-corner-all ui-widget ui-widget-content" style="border: none;">
												<ul role="tablist" class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header" style="margin-bottom: 20px; background: transparent;">
													<li role="tab" tabindex="0" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab ui-tabs-active ui-state-active" aria-controls="tab-67e93b6eb47bf-1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
														<a href="#tab-67e93b6eb47bf-1" tabindex="-1" class="ui-tabs-anchor" id="ui-id-1">
															<h4 style="text-transform: uppercase;">Beschrijving</h4>
														</a>
													</li>
													<li role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="tab-67e93b6eb47bf-2" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false">
														<a href="#tab-67e93b6eb47bf-2" tabindex="-1" class="ui-tabs-anchor" id="ui-id-2">
															<h4 style="text-transform: uppercase;">Gegevens</h4>
														</a>
													</li>
													<li role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="tab-67e93b6eb47bf-3" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false">
														<a href="#tab-67e93b6eb47bf-3" tabindex="-1" class="ui-tabs-anchor" id="ui-id-3">
															<h4 style="text-transform: uppercase;">Details</h4>
														</a>
													</li>
												</ul>

												<div id="tab-67e93b6eb47bf-1" aria-labelledby="ui-id-1" role="tabpanel" class="ui-tabs-panel ui-corner-bottom ui-widget-content" aria-hidden="false" style="background: transparent; padding: 0; color: #000000;">
													<?php if ($description = get_field('description')): ?>
														<?= $description ?>
													<?php else: ?>
														<p>Er is geen beschrijving beschikbaar voor deze wagen.</p>
													<?php endif; ?>
												</div>
												<div id="tab-67e93b6eb47bf-2" aria-labelledby="ui-id-2" role="tabpanel" class="ui-tabs-panel ui-corner-bottom ui-widget-content" aria-hidden="true" style="display: none; background: transparent; padding: 0; color: #000000;">
													<div class="grid">
														<?php if ($amount_of_km = get_field('amount_of_km')): ?>
															<div class="grid-item">
																<strong>KM stand</strong>
																<br>
																<?= $amount_of_km; ?>
															</div>
														<?php endif; ?>
														<?php if ($date_of_registration = get_field('date_of_registration')): ?>
															<div class="grid-item">
																<strong>Inschrijving</strong>
																<br>
																<?= $date_of_registration; ?>
															</div>
														<?php endif; ?>
														<?php if ($capacity = get_field('capacity')): ?>
															<div class="grid-item">
																<strong>Cilinderinhoud</strong>
																<br>
																<?= $capacity; ?>
															</div>
														<?php endif; ?>
														<?php if ($amount_of_pk = get_field('amount_of_pk')): ?>
															<div class="grid-item">
																<strong>Vermogen</strong>
																<br>
																<?= $amount_of_pk; ?> PK
															</div>
														<?php endif; ?>
														<?php if ($gear_box = get_field('gear_box')): ?>
															<div class="grid-item">
																<strong>Transmissie</strong>
																<br>
																<?= $gear_box; ?>
															</div>
														<?php endif; ?>
														<?php if ($color = get_field('color')): ?>
															<div class="grid-item">
																<strong>Kleur</strong>
																<br>
																<?= $color; ?>
															</div>
														<?php endif; ?>
														<?php if ($interior = get_field('interior')): ?>
															<div class="grid-item">
																<strong>Interieur</strong>
																<br>
																<?= $interior; ?>
															</div>
														<?php endif; ?>
														<?php if ($rims = get_field('rims')): ?>
															<div class="grid-item">
																<strong>Velgen</strong>
																<br>
																<?= $rims; ?>
															</div>
														<?php endif; ?>
														<?php if ($yearly_tax = get_field('yearly_tax')): ?>
															<div class="grid-item">
																<strong>Jaarlijkse verkeersbelasting</strong>
																<br>
																<?= $yearly_tax; ?>
															</div>
														<?php endif; ?>
														<?php if ($registration_tax = get_field('registration_tax')): ?>
															<div class="grid-item">
																<strong>Belasting op inverkeerstelling</strong>
																<br>
																<?= $registration_tax; ?>
															</div>
														<?php endif; ?>
														<?php if ($emission = get_field('emission')): ?>
															<div class="grid-item">
																<strong>Uitstoot</strong>
																<br>
																<?= $emission; ?>
															</div>
														<?php endif; ?>
														<?php if ($euronorm = get_field('euronorm')): ?>
															<div class="grid-item">
																<strong>Euronorm</strong>
																<br>
																<?= $euronorm; ?>
															</div>
														<?php endif; ?>
														<?php if ($price = get_field('price')): ?>
															<div class="grid-item">
																<strong>Prijs</strong>
																<br>
																<?= $price; ?>
															</div>
														<?php endif; ?>
														<?php if ($btw = get_field('btw')): ?>
															<div class="grid-item">
																<strong>BTW verrekenbaar</strong>
																<br>
																<?= $btw; ?>
															</div>
														<?php endif; ?>
													</div>
												</div>
												<div id="tab-67e93b6eb47bf-3" aria-labelledby="ui-id-3" role="tabpanel" class="ui-tabs-panel ui-corner-bottom ui-widget-content" aria-hidden="true" style="display: none; background: transparent; padding: 0; color: #000000;">
													<?php if ($details = get_field('details')): ?>
														<?= $details ?>
													<?php else: ?>
														<p>Er zijn geen details beschikbaar voor deze wagen.</p>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="wrap mcb-wrap one-third tablet-one-third laptop-one-third mobile-one clearfix" data-desktop-col="one-third" data-laptop-col="laptop-one-third" data-tablet-col="tablet-one-third" data-mobile-col="mobile-one">
								<div class="mcb-wrap-inner mfn-module-wrapper mfn-wrapper-for-wraps">
									<div class="mcb-wrap-background-overlay"></div>

									<?php if ($contact_title = get_field('title', 'options')): ?>
										<div class="column mcb-column one laptop-one tablet-one mobile-one column_column">
											<div class="mcb-column-inner mfn-module-wrapper">
												<div class="column_attr mfn-inline-editor clearfix" style="font-size: 1.4em; line-height: 1.4em; text-align: left; color: #000000;">
													<p style="margin-bottom: 0px;"><?= $contact_title ?></p>
												</div>
											</div>
										</div>
									<?php endif; ?>

									<?php if ($mail = get_field('mail', 'options')): ?>
										<div class="column mcb-column one laptop-one tablet-one mobile-one column_column">
											<div class="mcb-column-inner mfn-module-wrapper">
												<div class="column_attr mfn-inline-editor clearfix" style="margin-top: 20px; line-height: 1;">
													<p>
														<i class="far fa-envelope" style="color:rgba(255,115,0,0.7686)" aria-hidden="true"></i>
														<a style="color: #000000; font-size: 0.8em; line-height: 1.2em;" href="mailto:<?= $mail ?>">
															<?= !empty( $mail_title = get_field('mail_title', 'options')) ? $mail_title : $mail ?>
														</a>
													</p>
												</div>
											</div>
										</div>
									<?php endif; ?>

									<?php if ($phone = get_field('phone', 'options')): ?>
										<div class="column mcb-column one laptop-one tablet-one mobile-one column_column">
											<div class="mcb-column-inner mfn-module-wrapper">
												<div class="column_attr mfn-inline-editor clearfix" style="line-height: 1;">
													<p>
														<i class="fas fa-phone-alt" style="color:rgba(255,115,0,0.7686)" aria-hidden="true"></i>
														<a style="color: #000000; font-size: 0.8em; line-height: 1.2em;" href="tel:<?= $phone ?>">
															<?= !empty( $phone_title = get_field('phone_title', 'options')) ? $phone_title : $phone ?>
														</a>
													</p>
												</div>
											</div>
										</div>
									<?php endif; ?>

									<?php if ($instagram = get_field('instagram', 'options')): ?>
										<div class="column mcb-column one laptop-one tablet-one mobile-one column_column">
											<div class="mcb-column-inner mfn-module-wrapper">
												<div class="column_attr mfn-inline-editor clearfix" style="line-height: 1;">
													<p>
														<i class="fab fa-instagram" style="color:rgba(255,115,0,0.7686)" aria-hidden="true"></i>
														<a style="color: #000000; font-size: 0.8em; line-height: 1.2em;" href="<?= $instagram; ?>" target="_blank" rel="noopener">Instagram</a>
													</p>
												</div>
											</div>
										</div>
									<?php endif; ?>

									<?php if ($facebook = get_field('facebook', 'options')): ?>
										<div class="column mcb-column one laptop-one tablet-one mobile-one column_column">
											<div class="mcb-column-inner mfn-module-wrapper">
												<div class="column_attr mfn-inline-editor clearfix" style="line-height: 1;">
													<p>
														<i class="fab fa-facebook" style="color:rgba(255,115,0,0.7686)" aria-hidden="true"></i>
														<a style="color: #000000; font-size: 0.8em; line-height: 1.2em;" href="<?= $facebook; ?>" target="_blank" rel="noopener">Facebook</a>
													</p>
												</div>
											</div>
										</div>
									<?php endif; ?>

									<?php if (($button_link = get_field('button_link', 'options')) && ($button_title = get_field('button_title', 'options'))): ?>
										<div class="column mcb-column one laptop-one tablet-one mobile-one column_button" style="text-transform: uppercase; font-size: 0.8em;">
											<div class="mcb-column-inner mfn-module-wrapper" style="margin-top: 20px;">
												<a class="button button_right button_full_width button_size_2" href="<?= $button_link; ?>" title="">
													<span class="button_icon"><i class="icon-phone" aria-hidden="true"></i></span>
													<span class="button_label"><?= $button_title ?></span>
												</a>
											</div>
										</div>
									<?php endif; ?>

									<?php if (($button_2_link = get_field('button_2_link', 'options')) && ($button_2_title = get_field('button_2_title', 'options') )): ?>
										<div class="column mcb-column one laptop-one tablet-one mobile-one column_button">
											<div class="mcb-column-inner mfn-module-wrapper" style="margin-top: 20px;">
												<a class="button button-secondary button_right button_full_width button_size_2" href="<?= $button_2_link; ?>" title="">
													<span class="button_icon"><i class="icon-calendar-line" aria-hidden="true"></i></span>
													<span class="button_label"><?= $button_2_title ?></span>
												</a>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
					</section>
				</div>
			</div>
		</main>
	</div>
</div>

<?php wp_enqueue_script('custom-popup', get_theme_file_uri('/js/custom-popup.js'), array('jquery'), MFN_THEME_VERSION, true); ?>

<?php wp_enqueue_style('custom-popup', get_theme_file_uri('/css/custom-popup.css'), false, MFN_THEME_VERSION); ?>

<?php wp_enqueue_style('mfn-swiper', get_theme_file_uri('/css/scripts/swiper.css'), false, MFN_THEME_VERSION); ?>
<?php wp_enqueue_script('mfn-swiper', get_theme_file_uri('/js/swiper.js'), array('jquery'), MFN_THEME_VERSION, true); ?>

<?php wp_enqueue_script('mfn-collection-slider', get_theme_file_uri('/js/collection-slider.js'), array('jquery'), MFN_THEME_VERSION, false); ?>

<?php get_footer();
