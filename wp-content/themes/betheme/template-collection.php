<?php
/**
 * Template Name: Collectie
 *
 * @package Betheme
 * @author Muffin Group
 * @link https://muffingroup.com
 */

get_header();
?>


<div id="Content">
	<div class="content_wrapper clearfix">
		<div class="sections_group">
			<div class="section">
				<div class="section_wrapper clearfix">

                    <?php

                    $mfn_builder = new Mfn_Builder_Front(get_the_ID());
                    $mfn_builder->show();

                    ?>
                           
    			</div>
  
                <div class="mfn-builder-content">
                    <div class="section mcb-section mcb-section-qo0x81qxx  no-margin-v full-width" style="padding-bottom:40px;padding-left:40px;padding-right:40px">
                        <div class="section_wrapper mcb-section-inner" style="display:flex;flex-wrap:wrap;">
                
                        <?php
                            $args = array(
                                'post_type' => array('collectie'),
                                'posts_per_page' => 20,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'collectie-categories',
                                        'operator' => 'NOT EXISTS',
                                    ),
                                ),
                            );

                            $posts_query = new WP_Query($args);
                            while ($posts_query->have_posts()) :
                                $posts_query->the_post();

                        ?>    
                        
                            <div class="wrap mcb-wrap mcb-wrap-v03jjwfpz one-third  valign-top clearfix" data-col="one-third" style="padding:20px">
                                <div class="mcb-wrap-inner">
                                    <div class="column mcb-column mcb-item-1m3dh6x17 one column_column">
                                        <div class="photo_box without-desc">
                                            <div class="image_frame">
                                                <div class="image_wrapper">
                                                    <a class="image_slider_link" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 2;" href="<?= the_permalink(); ?>"></a>
                                                    <img class="scale-with-grid" src="<?= get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" width="1620" height="1080">
                                                    
                                                    <?php if ( false ) : ?>
                                                        <?php if($sliderId = get_post_meta(get_the_ID(), 'slider_id', true)): ?>
                                                            <?php $slider = '[smartslider3 slider="' . $sliderId . '"]'; ?>
                                                            <?= do_shortcode($slider); ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    
                                                    <?php if($popupId = get_post_meta(get_the_ID(), 'popup_video_id', true)): ?>
                                                    <a class="video_link link pum-trigger" href="<?= the_permalink(); ?><?= $popupId; ?>" style="position: absolute; display: flex; align-items: center; justify-content: center; top: 0; right: 0; width: 40px; height: 40px; color: rgb(0, 0, 0); cursor: pointer; z-index: 4;"> 
                                                        <i class="icon-videocam-line" style="font-size: 1.5em; color: white;"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="column mcb-column mcb-item-aas1vx24d one column_column">
                                        <div class="column_attr clearfix" style="padding:10px 0 0 5px;">
                                            <h2 style="font-size:20px; line-height:35px; margin-bottom:5px;"><a href="<?= the_permalink(); ?>"><?= the_title(); ?></a></h2>
                                            <!-- Get subtitle -->
                                            <?php if($subtitle = get_field('subtitle')): ?>
                                            <h3 style="line-height:35px;"><a href="<?= the_permalink(); ?>"><?= $subtitle; ?></a></h3>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Get date of registration -->
                                    <?php if($date = get_field('date_of_registration')): ?>
                                    <div class="column mcb-column mcb-item-s2831ym2u one-third column_column">
                                        <div class="column_attr clearfix" style="padding:0 0 0 5px;">
                                            <p style="color:#898989; font-size:12px; margin-bottom:0px; letter-spacing:1px;">INSCHRIJVING:</p>
                                            <p style="color:#000;"><?= $date; ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Get amount of KM -->
                                    <?php if($km = get_field('amount_of_km')): ?>
                                    <div class="column mcb-column mcb-item-nnfrqerva one-third column_column">
                                        <div class="column_attr clearfix" style="padding:0 0 0 5px;">
                                            <p style="color:#898989; font-size:12px; margin-bottom:0px; letter-spacing:1px;">KM STAND:</p>
                                            <p style="color:#000;"><?= $km; ?> KM</p>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Get amount of PK -->
                                    <?php if($pk = get_field('amount_of_pk')): ?>
                                    <div class="column mcb-column mcb-item-568wln7jy one-third column_column">
                                        <div class="column_attr clearfix" style="padding:0 0 0 5px;">
                                            <p style="color:#898989; font-size:12px; margin-bottom:0px; letter-spacing:1px;">VERMOGEN:</p>
                                            <p style="color:#000;"><?= $pk; ?> PK</p>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="column mcb-column mcb-item-lqi19qffi one column_divider">
                                        <hr class="no_line" style="margin: 0 auto 0px auto">
                                    </div>
                                    
                                    <!-- Get the price -->
                                    <?php if($price = get_field('price')): ?>
                                    <div class="column mcb-column mcb-item-124orad9j one column_column">
                                        <div class="column_attr clearfix" style="padding:0 0 0 5px;">
                                            <p style="font-size:18px;"><b>€<?= $price; ?></b></p>
                                            <a class="button  button_size_2" href="<?= the_permalink(); ?>"><span class="button_label">Meer informatie</span></a>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                        <?php
                            endwhile;
                            wp_reset_query();
                        ?>
                        
                        </div>  
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer();
