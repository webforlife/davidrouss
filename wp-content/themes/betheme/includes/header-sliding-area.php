<?php
if( mfn_opts_get('sliding-top') ){

	$st_class = 'st-'. mfn_opts_get('sliding-top');

	echo '<div id="Sliding-top" class="'. esc_attr($st_class) .'">';

		echo '<div class="widgets_wrapper">';
			echo '<div class="container">';

				$sidebars_count = 0;
				for( $i = 1; $i <= 4; $i++ ){
					if ( is_active_sidebar( 'top-area-'. $i ) ) $sidebars_count++;
				}

				$sidebar_class = '';
				if( $sidebars_count > 0 ){
					switch( $sidebars_count ){
						case 2: $sidebar_class = 'one-second'; break;
						case 3: $sidebar_class = 'one-third'; break;
						case 4: $sidebar_class = 'one-fourth'; break;
						default: $sidebar_class = 'one';
					}
				}

				for( $i = 1; $i <= 4; $i++ ){
					if ( is_active_sidebar( 'top-area-'. $i ) ){
						echo '<div class="'. esc_attr($sidebar_class) .' column">';
                            echo '<div class="mcb-column-inner">';
							    dynamic_sidebar( 'top-area-'. $i );
                            echo '</div>';
                        echo '</div>';
					}
				}

			echo '</div>';
		echo '</div>';

		echo '<a href="#" class="sliding-top-control" aria-label="'. __('Open sliding top', 'betheme') .'"><span><i class="plus '. esc_attr(mfn_opts_get('sliding-top-icon','icon-down-open-mini')) .'"></i><i class="minus icon-up-open-mini" aria-label="'. __('close sliding top', 'betheme') .'"></i></span></a>';
	echo '</div>';

}
