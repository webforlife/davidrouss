<?php
if( ! defined( 'ABSPATH' ) ){
	exit; // Exit if accessed directly
}

/*error_reporting(E_ALL);
ini_set("display_errors", 1);*/

class MfnVisualBuilder {

	public $post_type = false;
	public $ui_mode = 'default';
	public $template_type = false;
	public $page_options = false;
	public $theme_options = false;
	public $options = array();
	public $widgets = array();
	public $scripts = array();
	public $styles = array();
	public $post_id = false;
	public $sample_content = false;
	public $view = 'demo';
	public $user = false;

	public function __construct() {
		global $post;

		if( !isset($post->ID) && apply_filters('is_bebuilder_demo', false) ){
			$this->post_id = get_the_ID();
		}else if(isset($post->ID) ){
			$this->post_id = $post->ID;
		}

		if( is_admin() ) $this->view = 'admin';

		$this->user = get_current_user_id();

		if( $this->post_id ){

			$this->post_type = get_post_type($this->post_id);

		  if($this->post_type == 'template') $this->template_type = get_post_meta($post->ID, 'mfn_template_type', true);

			if($this->post_type == 'post'){
			  $po_class = new Mfn_Post_Type_Post();
			}elseif($this->post_type == 'portfolio'){
			  $po_class = new Mfn_Post_Type_Portfolio();
			}elseif($this->post_type == 'template'){
			  $po_class = new Mfn_Post_Type_Template();
			}elseif($this->post_type == 'product'){
			  $po_class = new Mfn_Post_Type_Product();
			}else{
			  $po_class = new Mfn_Post_Type_Page();
			}

			if( $this->template_type == 'header' ){
				$this->page_options = $po_class->set_header_fields();
			}elseif( $this->template_type == 'footer' ){
				$this->page_options = $po_class->set_footer_fields();
			}elseif( $this->template_type == 'megamenu' ){
				$this->page_options = $po_class->set_megamenu_fields();
			}elseif( $this->template_type == 'popup' ){
				$this->page_options = $po_class->set_popup_fields();
			}elseif( $this->template_type == 'sidemenu' ){
				$this->page_options = $po_class->set_sidemenu_fields();
			}else{
				$this->page_options = $po_class->set_fields();
			}

	  }

  }

  public function mfn_add_admin_beglobalsections_class($classes){
		return $classes.' mfn-template-section';
  }

  public function mfn_add_admin_beglobalwraps_class($classes){
    return $classes.' mfn-template-wrap';
  }

  public function mfn_add_admin_beheader_class($classes){
  	return $classes.' mfn-preview-mode mfn-be-header-builder';
  }

  public function mfn_add_admin_bemegamenu_class($classes){
  	return $classes.' mfn-preview-mode mfn-be-megamenu-builder';
  }

  public function mfn_add_admin_becart_class($classes){
  	return $classes.' mfn-preview-mode mfn-be-cart-builder';
  }

  public function mfn_add_admin_befooter_class($classes){
  	return $classes.' mfn-preview-mode mfn-be-megamenu-builder';
  }

  public function mfn_add_admin_bepopup_class($classes){
  	return $classes.'mfn-be-popup-builder';
  }

  	public function mfn_required_scripts(){
  		$this->scripts = array(
	  		'wp-auth-check',
	  		'heartbeat',
	  		'jquery',
	  		'jquery-core',
	  		'jquery-migrate',
	  		'jquery-ui-core',
	  		'jquery-ui-tabs',
	  		'mediaelement',
	  		'mediaelement-core',
	  		'mediaelement-migrate',
	  		'mediaelement-vimeo',
	  		'wp-mediaelement',
	  		//'media-upload',
	  		'media-models',
	  		'media-views',
	  		'media-editor',
	  		'media-audiovideo',
	  		'media-widgets',
	  		'media-audio-widget',
	  		'media-image-widget',
	  		'media-gallery-widget',
	  		'media-video-widget',
	  		'media-grid',
	  		'media',
	  		'media-gallery',
	  		'wp-media-utils'
		);
  	}

	public function mfn_required_styles(){
		$this->styles = array(
			'colors',
			'common',
			'forms',
			'admin-menu',
			'dashboard',
			'list-tables',
			'edit',
			'revisions',
			'media',
			'themes',
			'about',
			'nav-menus',
			'widgets',
			'site-icon',
			'l10n',
			'code-editor',
			'site-health',
			'wp-admin',
			'login',
			'tabs',
			'install',
			'wp-color-picker',
			'customize-controls',
			'customize-widgets',
			'customize-nav-menus',
			'buttons',
			'dashicons',
			'admin-bar',
			'wp-auth-check',
			'editor-buttons',
			'media-views',
			'wp-pointer',
			'customize-preview',
			'wp-embed-template-ie',
			'imgareaselect',
			'wp-jquery-ui-dialog',
			'mediaelement',
			'wp-mediaelement',
		);
	}



	public function mfn_append_vb_header() {
		wp_enqueue_style('mfn-vbreset', get_theme_file_uri('/visual-builder/assets/css/reset.css'), false, MFN_THEME_VERSION, 'all');
    wp_enqueue_style('mfn-vbstyle', get_theme_file_uri('/visual-builder/assets/css/style.css'), false, time(), false);

    wp_enqueue_style('wp-codemirror');

    // icons
		wp_enqueue_style('mfn-icons', get_theme_file_uri('/fonts/mfn/icons.css'), false, time());
		wp_enqueue_style('mfn-font-awesome', get_theme_file_uri('/fonts/fontawesome/fontawesome.css'), false, time());

		// VB styles & scripts
		wp_enqueue_style('mfn-vbcolorpickerstyle', get_theme_file_uri('/visual-builder/assets/css/nano.min.css'), false, time(), false);

		wp_enqueue_style('mfn-codemirror-dark', get_theme_file_uri('/visual-builder/assets/css/codemirror-dark.css'), false, MFN_THEME_VERSION, 'all');

	}

	public function mfn_append_vb_footer() {
		global $wp_scripts;
		global $wp_styles;

		if( wp_script_is( 'mfn-vbscripts', 'enqueued') ){
 			return; // prevent localize script more than once
 		}

    $create_bebuilder_fields = true;

    $mfn_beform_ver = get_option('betheme_form_uid') ? get_option('betheme_form_uid') : MFN_THEME_VERSION;

    if( is_admin() && ( !file_exists( self::bebuilderFilePath() ) || ( defined('MFN_DEBUG') && MFN_DEBUG == 1 ) ) ) {
    	$create_bebuilder_fields = Mfn_Helper::generate_bebuilder_items();
    	$mfn_beform_ver = time();
    }

    if( $create_bebuilder_fields ) {
    	wp_enqueue_script( 'mfn-bebuilder-fields', self::bebuilderFilePath(true), false, $mfn_beform_ver, true );
    	wp_add_inline_script( 'mfn-bebuilder-fields', $this->getDbLists(), 'before' );
    }else{
    	echo '<script id="mfn-vb-dblists">'.$this->getDbLists().'</script>';
    	echo '<script id="mfn-bebuilder-fields-live">'.$this->fieldsToJS().'</script>';
    }

		wp_enqueue_script('mfn-plugins', get_theme_file_uri('/js/plugins.js'), array('jquery'), MFN_THEME_VERSION, true);

		wp_enqueue_script('wp-theme-plugin-editor');

		wp_enqueue_script( 'jquery-ui-resizable' );
		wp_enqueue_script( 'jquery-ui-sortable'  );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-progressbar' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-slider' );


		/*wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'wplink' );*/


		wp_enqueue_script( 'wp-auth-check' );
		wp_enqueue_script( 'heartbeat' );


	  // Add the color picker

	  wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
		wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), false, 1 );

		// webfont
		wp_enqueue_script( 'mfn-webfont', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', array( 'jquery' ), false, true );

		wp_enqueue_media();
		wp_enqueue_editor();

		wp_enqueue_script('mfn-rangy', get_theme_file_uri('/visual-builder/assets/js/rangy-core.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfn-rangy-classapplier', get_theme_file_uri('/visual-builder/assets/js/rangy-classapplier.js'), false, MFN_THEME_VERSION, true);

		wp_enqueue_script('mfn-vbcolorpickerjs', get_theme_file_uri('/visual-builder/assets/js/pickr.min.js'), false, time(), true);
		wp_enqueue_script('mfn-inline-editor-js', get_theme_file_uri('/visual-builder/assets/js/medium-editor.min.js'), false, time(), true);
		wp_enqueue_script('mfn-vblistjs', get_theme_file_uri('/visual-builder/assets/js/list.min.js'), false, time(), true);


		wp_enqueue_script('mfn-vbace', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.31.2/ace.js', false, time(), true);
		wp_enqueue_script('mfn-vbace-lang', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.31.2/ext-language_tools.min.js', false, time(), true);

		wp_add_inline_script( 'mfn-inline-editor-js', 'let mfnajaxurl = "'. admin_url( 'admin-ajax.php' ) . '"; function getContrastYIQ( hexcolor, tolerance ){hexcolor = hexcolor.replace( "#", "" ); tolerance = typeof tolerance !== "undefined" ? tolerance : 169; if( 6 != hexcolor.length ){return false; } var r = parseInt( hexcolor.substr(0,2),16 ); var g = parseInt( hexcolor.substr(2,2),16 ); var b = parseInt( hexcolor.substr(4,2),16 ); var yiq = ( ( r*299 ) + ( g*587 ) + ( b*114 ) ) / 1000; return ( yiq >= tolerance ) ? "light" : "dark"; }' );

		wp_enqueue_script('mfnHelpers', get_theme_file_uri('/visual-builder/assets/js/forms/helpers.js'), false, MFN_THEME_VERSION, true);

		/**
		 *
		 * FIELDS
		 *
		 * */
		wp_enqueue_script('mfnFormHeader', get_theme_file_uri('/visual-builder/assets/js/forms/fields/header.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormText', get_theme_file_uri('/visual-builder/assets/js/forms/fields/text.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormBoxShadow', get_theme_file_uri('/visual-builder/assets/js/forms/fields/box_shadow.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormBoxShadowTO', get_theme_file_uri('/visual-builder/assets/js/forms/fields/boxshadow.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormHelper', get_theme_file_uri('/visual-builder/assets/js/forms/fields/helper.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormSwitch', get_theme_file_uri('/visual-builder/assets/js/forms/fields/switch.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormPills', get_theme_file_uri('/visual-builder/assets/js/forms/fields/pills.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormRadioImg', get_theme_file_uri('/visual-builder/assets/js/forms/fields/radio_img.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormUpload', get_theme_file_uri('/visual-builder/assets/js/forms/fields/upload.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormMultiselect', get_theme_file_uri('/visual-builder/assets/js/forms/fields/multiselect.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormSliderbar', get_theme_file_uri('/visual-builder/assets/js/forms/fields/sliderbar.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormCheckbox', get_theme_file_uri('/visual-builder/assets/js/forms/fields/checkbox.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormCheckboxPseudo', get_theme_file_uri('/visual-builder/assets/js/forms/fields/checkbox_pseudo.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormColor', get_theme_file_uri('/visual-builder/assets/js/forms/fields/color.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormSelect', get_theme_file_uri('/visual-builder/assets/js/forms/fields/select.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormFontSelect', get_theme_file_uri('/visual-builder/assets/js/forms/fields/font_select.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormHtml', get_theme_file_uri('/visual-builder/assets/js/forms/fields/html.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormSubheader', get_theme_file_uri('/visual-builder/assets/js/forms/fields/subheader.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormDimensions', get_theme_file_uri('/visual-builder/assets/js/forms/fields/dimensions.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormGradient', get_theme_file_uri('/visual-builder/assets/js/forms/fields/gradient.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormIcon', get_theme_file_uri('/visual-builder/assets/js/forms/fields/icon.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormCssFilters', get_theme_file_uri('/visual-builder/assets/js/forms/fields/css_filters.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormTextarea', get_theme_file_uri('/visual-builder/assets/js/forms/fields/textarea.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormInfo', get_theme_file_uri('/visual-builder/assets/js/forms/fields/info.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormTabs', get_theme_file_uri('/visual-builder/assets/js/forms/fields/tabs.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormTextShadow', get_theme_file_uri('/visual-builder/assets/js/forms/fields/text_shadow.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormTransform', get_theme_file_uri('/visual-builder/assets/js/forms/fields/transform.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormTypographyVb', get_theme_file_uri('/visual-builder/assets/js/forms/fields/typography_vb.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormTypography', get_theme_file_uri('/visual-builder/assets/js/forms/fields/typography.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormVisual', get_theme_file_uri('/visual-builder/assets/js/forms/fields/visual.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormColorMulti', get_theme_file_uri('/visual-builder/assets/js/forms/fields/color_multi.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormMultiText', get_theme_file_uri('/visual-builder/assets/js/forms/fields/multi_text.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormAjax', get_theme_file_uri('/visual-builder/assets/js/forms/fields/ajax.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormCustom', get_theme_file_uri('/visual-builder/assets/js/forms/fields/custom.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnFormSocial', get_theme_file_uri('/visual-builder/assets/js/forms/fields/social.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnDynamicItems', get_theme_file_uri('/visual-builder/assets/js/forms/fields/dynamic_items.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnUploadMulti', get_theme_file_uri('/visual-builder/assets/js/forms/fields/upload_multi.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnOrder', get_theme_file_uri('/visual-builder/assets/js/forms/fields/order.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnAceEditor', get_theme_file_uri('/visual-builder/assets/js/forms/fields/ace.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnLogic', get_theme_file_uri('/visual-builder/assets/js/forms/fields/logic.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnBackdropFilter', get_theme_file_uri('/visual-builder/assets/js/forms/fields/backdrop_filter.js'), false, MFN_THEME_VERSION, true);
		wp_enqueue_script('mfnHotspot', get_theme_file_uri('/visual-builder/assets/js/forms/fields/hotspot.js'), false, MFN_THEME_VERSION, true);

		wp_enqueue_script('mfnForm', get_theme_file_uri('/visual-builder/assets/js/forms/form.js'), false, MFN_THEME_VERSION, true);

		/**
		 *
		 * END FIELDS
		 *
		 * */

		wp_enqueue_script( 'mfn-opts-field-pills-vb', MFN_OPTIONS_URI .'fields/pills/field_pills_vb.js', array( 'jquery' ), MFN_THEME_VERSION, true );

		wp_enqueue_script('mfn-vbscripts', get_theme_file_uri('/visual-builder/assets/js/scripts.js'), false, time(), true);

		//add_filter('script_loader_tag', array($this, 'add_type_attribute' , 10, 3);

		$localize_visual = array(
			'mfnsc' => get_theme_file_uri( '/functions/tinymce/plugin.js' ),
		);
		$google_fonts = mfn_fonts('all');

		$post_types_disable = mfn_opts_get('post-type-disable');

		wp_enqueue_script( 'mfn-opts-field-visual-vb', get_theme_file_uri('/muffin-options/fields/visual/field_visual_vb.js'), array( 'jquery' ), MFN_THEME_VERSION, true );
		wp_localize_script( 'mfn-opts-field-visual-vb', 'fieldVisualJS_vb', $localize_visual);

		$permalink = get_preview_post_link($this->post_id).'&visual=iframe';

		if( get_post_status($this->post_id) == 'publish' ){
			$permalink = get_permalink( $this->post_id );
			if( strpos($permalink, '?') !== false){
				$permalink .= '&visual=iframe';
			}else{
				$permalink .= '?visual=iframe';
			}
			if( ! is_admin() ){
				$permalink .= '&demo';
			}
		}

		// override if template shop archive
		if( function_exists('is_woocommerce') ){

			if( $this->post_type == 'template' && $this->template_type == 'shop-archive' ){

				if( !empty(get_option('woocommerce_shop_page_id')) && is_numeric(get_option('woocommerce_shop_page_id')) && get_post_status( get_option('woocommerce_shop_page_id') ) == 'publish' ){
					$permalink = get_permalink( wc_get_page_id( 'shop' ) ).'?mfn-template-id='.$this->post_id.'&visual=iframe';
				}else{
					$permalink = 'shop_page_id';
				}

			}else if( $this->post_type == 'template' && $this->template_type == 'single-product' ){

				$sample = Mfn_Builder_Woo_Helper::sample_item('product');

				if( $sample ){
					$product = wc_get_product($sample);
					if( !empty($product->get_id()) ){
						$this->sample_content = $product->get_id();

						$permalink = get_permalink( $product->get_id() );
						if( strpos($permalink, '?') !== false ){
							$permalink .= '&mfn-template-id='.$this->post_id.'&visual=iframe';
						}else{
							$permalink .= '?mfn-template-id='.$this->post_id.'&visual=iframe';
						}
					}
				}else{
					wp_safe_redirect( admin_url().'edit.php?post_type=product&mfn-notice=product-missing' );
				}
	 			$gallery_overlay = mfn_opts_get('shop-product-gallery-overlay');
	 			$thumbnails_margin = mfn_opts_get( 'shop-product-thumbnails-margin', 0, ['unit'=>'px'] );
				$main_margin = mfn_opts_get( 'shop-product-main-image-margin', 'mfn-mim-0' );

	 			wp_localize_script( 'mfn-vbcolorpickerjs', 'mfnwoovars',
			      	array(
			      		'productthumbsover' => $gallery_overlay,
				        'productthumbs' => $thumbnails_margin,
				        'mainimgmargin' => $main_margin
			      	)
	    		);
	 		}else if( $this->post_type == 'template' && $this->template_type == 'thanks' ){
	 			$args = array(
				    'limit' => 1,
				    'type' => 'shop_order',
				);
				$order = wc_get_orders($args);
				if( empty($order) ){
					$permalink = 'shop_order_missing';
				}else{
					$permalink .= '&mfn-order-id='.$order[0]->get_id();
				}


			}
		}

		if( $this->post_type == 'template' && $this->template_type == 'megamenu' ) {
			$permalink .= '&mfn-h=classic';
		}else if( $this->post_type == 'template' && $this->template_type == 'single-post' ){
			$sample = get_posts( array('post_type' => 'post', 'numberposts' => 1 ));
			if( !empty($sample[0]->ID) ){
				$this->sample_content = $sample[0]->ID;
				$permalink = get_permalink( $sample[0]->ID ).'?mfn-template-id='.$this->post_id.'&visual=iframe';
			}
	 	}else if( $this->post_type == 'template' && $this->template_type == 'single-portfolio' ){
	 		if (! isset($post_types_disable['portfolio'])) {
				$sample = get_posts( array('post_type' => 'portfolio', 'numberposts' => 1 ));
				if( !empty($sample[0]->ID) ){
					$this->sample_content = $sample[0]->ID;
					$permalink = get_permalink( $sample[0]->ID ).'?mfn-template-id='.$this->post_id.'&visual=iframe';
				}
			}else{
				$permalink = 'portfolio_post_type_missing';
			}
	 	}

	 	if( isset($post_types_disable['portfolio']) && $this->post_type == 'template' && $this->template_type == 'single-portfolio' ) {
	 		$permalink = 'portfolio_post_type_missing';
	 	}

	 	if( $this->view == 'demo' && !empty($_GET['ui']) && in_array($_GET['ui'], array('default', 'developer', 'blocks')) ) $permalink .= '&ui='.$_GET['ui'];

	 	/*

		mfn-page-object / fixed due to problem with saving large pages

	 	$page_obj = get_post_meta($this->post_id, 'mfn-page-object', true);
	 	if( !empty( json_decode($page_obj) ) ) { $page_obj = json_decode($page_obj); }else{ $page_obj = false; }*/

	 	$page_obj = false;

	 	$current_user = wp_get_current_user();

		wp_localize_script( 'mfn-vbcolorpickerjs', 'mfnvbvars',
	      array(
	        'pageid' => $this->post_id,
	        'sample_content_id' => $this->sample_content,
	        'view_title' => $this->sample_content ? get_the_title($this->sample_content) : get_the_title($this->post_id),
	        'wpnonce' => wp_create_nonce( 'mfn-builder-nonce' ),
	        'rev_slider_id' => get_post_meta($this->post_id, 'mfn-post-slider', true),
	        'adminurl' => admin_url(),
	        'is_woocommerce' => function_exists('is_woocommerce') ? true : false,
	        'themepath' => get_template_directory_uri('/'),
	        'autosave' => mfn_opts_get('builder-autosave'),
	        'rooturl' => get_site_url(),
	        'view' => is_admin() ? 'admin' : 'demo',
	        'current_user_roles' => $current_user->roles,
	        'permalink' => $permalink,
	        'post_type' => $this->post_type,
	        'pagedata' => !empty( $page_obj ) ? $page_obj : $this->loadExistedElements($this->post_id),
	        'elements' => $this->loadEmptyElements($this->post_id),
	        'mfn_google_fonts' => $google_fonts,
	        'presets' => $this->getPresets(true),
	        'builder_type' => $this->template_type ? $this->template_type : 'standard',
	        'shape_dividers' => Mfn_Builder_Helper::get_shape_divider(false, false, 'mfn-uid-'),
	        'be_slug' => apply_filters('betheme_slug', 'mfn'),
	        'page_options' => $this->get_pageoptions(),
      	)
	    );

	  $cm_args = wp_enqueue_code_editor(array(
			'autoRefresh' => true,
			'lint' => true,
			'indentUnit' => 2,
			'tabSize' => 2,
			'lineNumbers' => false
		));

	  $codemirror['css']['codeEditor'] = wp_enqueue_code_editor(array(
			'type' => 'text/css', // required for lint
			'codemirror' => $cm_args,
		));

		$codemirror['html']['codeEditor'] = wp_enqueue_code_editor(array(
			'type' => 'text/html', // required for lint
			'codemirror' => $cm_args,
		));

		$codemirror['javascript']['codeEditor'] = wp_enqueue_code_editor(array(
			'type' => 'javascript', // required for lint
			'codemirror' => $cm_args,
		));

		wp_localize_script('mfn-vbscripts', 'mfn_cm', $cm_args);

		wp_enqueue_script( 'mfn-opts-field-textarea-vb', MFN_OPTIONS_URI .'fields/textarea/field_textarea_vb.js', array( 'jquery' ), MFN_THEME_VERSION, true );

		$lightboxOptions = mfn_opts_get('prettyphoto-options');
		$is_translation_on = mfn_opts_get('translate');

		$config = array(
			'mobileInit' => mfn_opts_get('mobile-menu-initial', 1240),
			'themecolor' => mfn_opts_get('color-theme'),
			'parallax' => mfn_parallax_plugin(),
			'responsive' => intval(mfn_opts_get('responsive', 0)),
			'sidebarSticky' => mfn_opts_get('sidebar-sticky') ? true : false,
			'lightbox' => array(
				'disable' => isset($lightboxOptions['disable']) ? true : false,
				'disableMobile' => isset($lightboxOptions['disable-mobile']) ? true : false,
				'title' => isset($lightboxOptions['title']) ? true : false,
			),
			'slider' => array(
				'blog' => intval(mfn_opts_get('slider-blog-timeout', 0)),
				'clients' => intval(mfn_opts_get('slider-clients-timeout', 0)),
				'offer' => intval(mfn_opts_get('slider-offer-timeout', 0)),
				'portfolio' => intval(mfn_opts_get('slider-portfolio-timeout', 0)),
				'shop' => intval(mfn_opts_get('slider-shop-timeout', 0)),
				'slider' => intval(mfn_opts_get('slider-slider-timeout', 0)),
				'testimonials' => intval(mfn_opts_get('slider-testimonials-timeout', 0)),
			),
			'livesearch' => array(
				'minChar' => intval(mfn_opts_get('header-search-live-min-characters', 3)),
				'loadPosts' => intval(mfn_opts_get('header-search-live-load-posts', 10)),
				'translation' => array(
					'pages' => 		$is_translation_on ? mfn_opts_get('translate-livesearch-pages', 'Pages') : __('Pages','betheme'),
					'categories' => $is_translation_on ? mfn_opts_get('translate-livesearch-categories', 'Categories') : __('Categories','betheme'),
					'portfolio' =>  $is_translation_on ? mfn_opts_get('translate-livesearch-portfolio', 'Portfolio') : __('Portfolio','betheme'),
					'post' => $is_translation_on ? mfn_opts_get('translate-livesearch-posts', 'Posts') : __('Posts','betheme'),
					'products' => $is_translation_on ? mfn_opts_get('translate-livesearch-products', 'Products') : __('Products','betheme'),
				),
			),
			'site_url' => get_site_url(),
			'accessibility' => array(
				'translation' => array(
					'headerContainer' => __('Header container', 'betheme'),
					'toggleSubmenu' => __('Toggle submenu', 'betheme'),
				),
			),
		);

		wp_localize_script( 'mfn-vbscripts', 'mfn', $config );

	}

	public function fieldsToJS(){
		// forms html
		$output = 'let renderMfnFields = {';
			$output .= $this->getSectionForm();
			$output .= $this->getWrapForm();
			$output .= $this->getItemsForm();
			$output .= $this->getItemsAdvancedForm();
			if( current_user_can( 'edit_theme_options' ) ) $output .= $this->getThomeOptionsForm();
			$output .= 'items: '.$this->getEmptyItems(); // extracted from loadEmptyElements()
		$output .= '}';
		return $output;
	}

	public function getDbLists(){

		if($this->post_type == 'post'){
		  $po_class = new Mfn_Post_Type_Post();
		}elseif($this->post_type == 'portfolio'){
		  $po_class = new Mfn_Post_Type_Portfolio();
		}elseif($this->post_type == 'template'){
		  $po_class = new Mfn_Post_Type_Template();
		  $this->template_type = get_post_meta($this->post_id, 'mfn_template_type', true);
		}else{
		  $po_class = new Mfn_Post_Type_Page();
		}

		$sidebars = mfn_opts_get('sidebars') ? mfn_opts_get('sidebars') : array();

		$output = 'var mfnDbLists = {'."\n";
			$output .= 'blog_tags:'.json_encode( mfn_hierarchical_taxonomy('post_tag') ).",\n";
			$output .= 'blog_categories:'.json_encode( mfn_hierarchical_taxonomy('category') ).",\n";
			$output .= 'offer_types:'.json_encode( mfn_hierarchical_taxonomy('offer-types') ).",\n";
			$output .= 'portfolio_types:'.json_encode( mfn_hierarchical_taxonomy('portfolio-types') ).",\n";
			if( function_exists('is_woocommerce') ){
				$output .= 'product_cat:'.json_encode( mfn_hierarchical_taxonomy('product_cat') ).",\n";
			}else{
				$output .= 'product_cat:'.json_encode( array() ).",\n";
			}
			$output .= 'slide_types:'.json_encode( mfn_hierarchical_taxonomy('slide-types') ).",\n";
			$output .= 'testimonial_types:'.json_encode( mfn_hierarchical_taxonomy('testimonial-types') ).",\n";
			$output .= 'client_types:'.json_encode( mfn_hierarchical_taxonomy('client-types') ).",\n";
			$output .= 'rev_slider:'.json_encode( Mfn_Builder_Helper::get_sliders('rev') ).",\n";
			$output .= 'layer_slider:'.json_encode( Mfn_Builder_Helper::get_sliders('layer') ).",\n";
			$output .= 'sidebars:'.json_encode( is_array($sidebars) && !is_null($sidebars) ? array_merge(array('' => __('Default', 'mfn-opts')), $sidebars) : null ).",\n";
			$output .= 'layouts:'.json_encode( $po_class->get_layouts() ).",\n";
			$output .= 'menus:'.json_encode( mfna_menu() ).",\n";
			$output .= 'headers:'.json_encode( mfna_templates('header') ).",\n";
			$output .= 'cf7:'.json_encode( mfna_cf7() ).",\n";
			$output .= 'popups:'.json_encode( mfna_templates('popup') ).",\n";
			$output .= 'sidemenus:'.json_encode( mfna_templates('sidemenu') ).",\n";
			$output .= 'footers:'.json_encode( mfna_templates('footer') ).",\n";
			$output .= 'singleproducts:'.json_encode( mfna_templates('single-product') ).",\n";
			$output .= 'single_post_tmpl:'.json_encode( mfna_templates('single-post') ).",\n";
			$output .= 'single_portfolio_tmpl:'.json_encode( mfna_templates('single-portfolio') ).",\n";
			$output .= 'prebuilts:'.json_encode(Mfn_Pre_Built_Sections::get_sections()).",\n";
			$output .= 'taxonomies:'.json_encode(mfna_taxonomies() ).",\n";
			$output .= 'pages:'.json_encode( mfna_pages() ).",\n";
			$output .= 'post_types:'.json_encode( mfna_posts_types() ).",\n";
			$output .= 'user_roles:'.json_encode( mfna_user_roles() ).",\n";
			$output .= 'pageoptions:'.$this->getPageOptionsForm();
			$output .= 'themeoptions:'.$this->getThomeOptionsObject().",\n";
			$output .= 'global_sections:'.json_encode( mfna_templates('section') ).",\n";
			$output .= 'fonts:'.json_encode( mfn_fonts() ).",\n";
			$output .= 'global_wraps:'.json_encode( mfna_templates('wrap') ).",\n";
			$output .= 'per_page:'.get_option( 'posts_per_page' ).",\n";
			$output .= 'media_sizes:'.json_encode( array('full' => __('Full size', 'mfn-opts'),'large' => __('Large', 'mfn-opts') .' | '. mfn_get_image_sizes('large', 1),'medium' => __('Medium', 'mfn-opts') .' | '. mfn_get_image_sizes('medium', 1),'thumbnail' => __('Thumbnail', 'mfn-opts') .' | '. mfn_get_image_sizes('thumbnail', 1))).',';

		$output .= '}';
		return $output;
	}

	public function getThomeOptionsObject() {
		$themeoptions = get_option('betheme');
		return json_encode( $themeoptions );
	}

	public function getPageOptionsForm(){
		$output = '[],';

		if( isset($this->page_options) && is_iterable($this->page_options) ){
			$output = json_encode( $this->page_options['fields'] ).',';
		}

		return $output;
	}

	public function getThomeOptionsForm() {
		global $MFN_Options;
		$gdpr = new Mfn_Gdpr();

		$to_fields = 'themeoptions_fields: {';

		$output = 'themeoptions: function() { return \'<div class="vb-themeoptions theme-options-tabs">';
		foreach( $MFN_Options->menu as $vb_o=>$vb_opt ) {

			//if( $vb_o == 'translate' ) continue;

			$output .='<div class="vb-to vb-to-'.$vb_o.'">';
				$output .='<div class="vb-to-header"><ul class="vb-to-ul"><li><a class="vb-themeoptions-link-expander" href="#themeoptions-'.htmlspecialchars($vb_o, ENT_QUOTES ).'"><span class="mfn-icon"></span>'.htmlspecialchars($vb_opt['title'], ENT_QUOTES ).'</a><ul class="vb-to-ul vb-to-subul">';
				foreach( $vb_opt['sections'] as $vb_submenu ) {
					$output .='<li class="vb-to-subli vb-to-subli-'.htmlspecialchars($vb_submenu, ENT_QUOTES ).'"><a class="vb-themeoptions-form-link" href="#themeoptions-'.htmlspecialchars($vb_submenu, ENT_QUOTES ).'"><span class="mfn-icon"></span>'.htmlspecialchars($MFN_Options->sections[$vb_submenu]['title'], ENT_QUOTES ).'</a></li>';
				}
				$output .='</ul></li></ul></div>';

				foreach ($vb_opt['sections'] as $vb_sec) {
					$output .='<div class="vb-to-content" id="themeoptions-'.htmlspecialchars($vb_sec, ENT_QUOTES ).'">';
					$to_fields .= '\''.htmlspecialchars($vb_sec, ENT_QUOTES ) .'\': '.json_encode( $MFN_Options->sections[$vb_sec]['fields'] ).',';
					$output .='</div>';
				}

			$output .='</div>';

		}
		$output .= '</div>\';},';

		// GDPR cookies
		if( !mfn_opts_get('gdpr') ){
			$output .= 'gdpr: function() { return \'';
			$output .= '\';},';
		}

		$to_fields .= '},'."\r\n";

		$output .= "\r\n".$to_fields;

		return $output;
	}

	public function getSectionForm(){

		$mfn_fields = new Mfn_Builder_Fields();
		$items = $mfn_fields->get_section();

		$output = 'section: '.json_encode($items).','."\r\n";

		return $output;
	}

	public function getWrapForm(){
		$mfn_fields = new Mfn_Builder_Fields();
		$items = $mfn_fields->get_wrap();

		$output = 'wrap: '.json_encode($items).','."\r\n";

		return $output;
	}

	public function getItemsAdvancedForm(){
		$mfn_fields = new Mfn_Builder_Fields( true );
		$items = $mfn_fields->get_advanced(true);

		$output = 'advanced: '.json_encode($items).','."\r\n";

		return $output;
	}

	public function getItemsForm(){
		$mfn_fields = new Mfn_Builder_Fields(true);
		$output = '';
		$items = $mfn_fields->get_items();

		foreach($items as $w=>$widget){
			if( isset($widget['fields']) ){
				$output .= $w.': '.json_encode($widget['fields']).','."\r\n";
			}elseif( isset($widget['attr']) ){
				$output .= $w.': '.json_encode($widget['attr']).','."\r\n";
			}
		}

		return $output;
	}

	public function getEmptyItems() {

		$return = array();
		$mfn_fields = new Mfn_Builder_Fields();
		$elements = $mfn_fields->get_items();

		// elements

		foreach( $elements as $e=>$element ){

			$classes = '';
			$params = array();
			$params_content = '';
			$return[$e]['type'] = $element['type'];
			$return[$e]['jsclass'] = $element['type'];
			$return[$e]['title'] = $element['title'];
			$return[$e]['icon'] = str_replace('_', '-', $element['type']);

			//if( $element['type'] == 'map' || $element['type'] == 'lottie' ){
				$params['vb'] = true;
			//}

			if( isset($element['attr']) ) {
				foreach ($element['attr'] as $x=>$field) {

					if( !empty($field['std']) ){
						if( strpos($field['id'], 'css_') !== false ){
							$return[$e]['attr'][$field['id']] = array('val' => $field['std'], 'css_path' => $field['css_path'], 'css_style' => $field['css_style']);
						}else{
							$return[$e]['attr'][$field['id']] = $field['std'];
						}
						if( mfn_is_blocks('vb') ){
							$params[$field['id']] = $field['std'];
						} elseif($field['id'] == 'content' || $field['id'] == 'plain_text'){
							$params_content = $field['std'];
						}else{
							$params[$field['id']] = $field['std'];
						}
					}else if( !empty($field['vbstd']) ){
						$return[$e]['attr'][$field['id']] = $field['vbstd'];
						if($field['id'] == 'content'){
							$params_content = $field['vbstd'];
						}else{
							$params[$field['id']] = $field['vbstd'];
						}
					}

				}
			}

			$params['pageid'] = $this->post_id;

			$return[$e]['html'] = '<div data-uid="uidhere" data-desktop-size="1/1" data-tablet-size="1/1" data-mobile-size="1/1" class="blink column mcb-column mfn-new-item vb-item vb-item-widget mcb-item-uidhere column_'.$element['type'].' one tablet-one mobile-one '.$classes.' mfn-module"><div class="mfn-drag-helper mfn-dh-before placeholder-column"></div><div class="mfn-drag-helper mfn-dh-after placeholder-column"></div>';

			// Transforms UI --- visible only when transformed an item
			$return[$e]['html'] .= '<div class="mfn-header-transform">';
				$return[$e]['html'] .= Mfn_Builder_Helper::itemTools('1/1');
			$return[$e]['html'] .= '</div>';

			$return[$e]['html'] .= '<div class="mcb-column-inner mcb-column-inner-uidhere mcb-item-'.$element['type'].'-inner mfn-module-wrapper">';

			$return[$e]['html'] .= Mfn_Builder_Helper::itemTools('1/1');

			$fun_name = 'sc_'.$element['type'];

			if( mfn_is_blocks('vb', $this->post_id) ){

				$block_item = [
					'type' => $element['type'],
					'attr' => $params,
				];

				$return[$e]['html'] .= Mfn_Builder_Items::blocks( $block_item, $mfn_fields );

			}elseif($element['type'] == 'placeholder'){
				$return[$e]['html'] .= '<div class="placeholder"></div>';
			}elseif($element['type'] == 'shop_products'){
				$return[$e]['html'] .= $fun_name($params, 'sample');
			}elseif($element['type'] == 'content'){
				$return[$e]['html'] .= '<div class="content-wp">'.get_post_field( 'post_content', $this->post_id ).'</div>';
			}elseif($element['type'] == 'divider'){
				$return[$e]['html'] .= '<hr />';
			}elseif($element['type'] == 'slider_plugin'){
				$return[$e]['html'] .= '<div class="mfn-widget-placeholder mfn-wp-revolution"><img class="item-preview-image" src="'.get_theme_file_uri('/muffin-options/svg/placeholders/slider_plugin.svg').'"></div>';
			}elseif($element['type'] == 'visual'){
				$return[$e]['html'] .= '<div class="mfn-visualeditor-content mfn-inline-editor clearfix">'.$params_content.'</div>';
			}elseif($element['type'] == 'table_of_contents'){
				$return[$e]['html'] .= $fun_name($params);
			}elseif($element['type'] == 'sidebar_widget'){
				$return[$e]['html'] .= '<img src="'.get_theme_file_uri( '/muffin-options/svg/placeholders/sidebar_widget.svg' ).'" alt="">';
			}elseif($element['type'] == 'column'){
				$return[$e]['html'] .= '<div class="column_attr mfn-inline-editor clearfix">'.$params_content.'</div>';
			}elseif($element['type'] == 'plain_text'){
				$return[$e]['html'] .= '<div class="desc">'.$params_content.'</div>';
			}elseif($element['type'] == 'image_gallery'){
				$params['id'] = null;
				$return[$e]['html'] .= sc_gallery($params);
			}elseif($element['type'] == 'shop' && class_exists( 'WC_Shortcode_Products' )){
				$params['post'] = 0;
				$shortcode = new WC_Shortcode_Products( $params, 'products' );
				$return[$e]['html'] .= $shortcode->get_content();
			}elseif(!empty($params_content)){
				$return[$e]['html'] .= $fun_name($params, $params_content);
			}elseif(function_exists( 'sc_'.$element['type'] )){
				$output = $fun_name($params);
				if(is_array($output)){
					$return[$e]['html'] .= $output[0];
					$return[$e]['script'] = $output[1];
				}else{
					$return[$e]['html'] .= $output;
				}
			}

			$return[$e]['html'] .= '</div></div>';

		}

		return json_encode($return);
	}

	public function loadEmptyElements($p){
		$return = array();

		$mfn_fields = new Mfn_Builder_Fields();
		$section = $mfn_fields->get_section();
		$wrap = $mfn_fields->get_wrap();

		// section

		$return['section']['icon'] = "section";
		$return['section']['jsclass'] = "section";
		$return['section']['title'] = "Section";
		$return['section']['uid'] = "";

		foreach ($section as $s => $sec) {
			if( !empty($sec['std']) ){
				$return['section']['attr'][$sec['id']] = $sec['std'];
			}
		}

		// wrap

		$return['wrap']['icon'] = "wrap";
		$return['wrap']['size'] = "1/1";
		$return['wrap']['tablet_size'] = "1/1";
		$return['wrap']['mobile_size'] = "1/1";
		$return['wrap']['jsclass'] = "wrap";
		$return['wrap']['uid'] = "";
		$return['wrap']['attr']['sticky'] = '0';
		$return['wrap']['attr']['tablet_sticky'] = '0';
		$return['wrap']['attr']['mobile_sticky'] = '0';

		foreach ($wrap as $w => $wra) {
			if( !empty($wra['std']) ){
				$return['wrap']['attr'][$wra['id']] = $wra['std'];
			}
		}

		return $return;

	}

	public function loadExistedElements($mfn_page_items){
		$return = array();
		$p_id = false;
		$detect_old_builder = false;

		if( is_numeric($mfn_page_items) ){
			$p_id = $mfn_page_items;
			$mfn_page_items = get_post_meta($p_id, 'mfn-page-items', true);
		}

		if($mfn_page_items && ! is_array($mfn_page_items)) {
			$mfn_items = unserialize(call_user_func('base'.'64_decode', $mfn_page_items), ['allowed_classes' => false]);
		} else {
			$mfn_items = $mfn_page_items;
		}

		if( $mfn_items && count($mfn_items) > 0 ) {
			foreach ($mfn_items as $s=>$section) {

				if( empty($section['uid']) ) {
					$sec_uid = Mfn_Builder_Helper::unique_ID();
					$section['uid'] = $sec_uid;
					$mfn_items[$s]['uid'] = $sec_uid;
					$detect_old_builder = true;
				}


				// Builder without wraps | Old version
				if( ! isset( $section['wraps'] ) && ! empty( $section['items'] ) ) {

					$fix_wrap = array(
						'size' => '1/1',
						'uid' => Mfn_Builder_Helper::unique_ID(),
						'items'	=> $section['items'],
						'jsclass' => 'wrap',
						'title' => 'Wrap',
						'icon' => 'wrap',
					);

					$section['wraps'] = array( $fix_wrap );

					$mfn_items[$s]['wraps'] = array( $fix_wrap );
					unset( $mfn_items[$s]['items'] );

					$detect_old_builder = true;

				}

				if( isset($section['wraps']) && is_iterable( $section['wraps'] ) ) {
					foreach ( $section['wraps'] as $w=>$wrap ) {

						if( is_array($wrap) && empty($wrap['uid']) ) {
							$wra_uid = Mfn_Builder_Helper::unique_ID();
							$wrap['uid'] = $wra_uid;
							$mfn_items[$s]['wraps'][$w]['uid'] = $wra_uid;
							$detect_old_builder = true;
						}

						if( !empty( $wrap['attr'] ) ) {
							foreach($wrap['attr'] as $k=>$v ) {
								if( strpos($k, '>') !== false ) {
									$r_k = str_replace('>', '/', $k);
									$wrap['attr'][$r_k] = $v;
									unset($wrap['attr'][$k]);
								}
							}
						}


						if( isset($wrap['items']) && is_iterable( $wrap['items'] ) ){
							foreach ( $wrap['items'] as $i=>$item ) {

								if( is_array($item) && empty($item['uid']) ) {

									$ite_uid = Mfn_Builder_Helper::unique_ID();
									$mfn_items[$s]['wraps'][$w]['items'][$i]['uid'] = $ite_uid;
									$item['uid'] = $ite_uid;
									$detect_old_builder = true;

								}



								if( !empty( $item['item_is_wrap'] ) ){

									$item['jsclass'] = 'wrap';
									$item['title'] = 'Wrap';
									$item['icon'] = 'wrap';

									if( isset($item['items']) && is_iterable( $item['items'] ) ){
										foreach ( $item['items'] as $j=>$jtem ) {

													$jtem['jsclass'] = $jtem['type'];
													$jtem['title'] = isset( $jtem['title'] ) ? $jtem['title'] : ucfirst(str_replace('_', ' ', $jtem['type']));
													$jtem['icon'] = str_replace('_', '-', $jtem['type']);

													$return[] = $jtem;

										}
									}

								}else{

									if( isset($item['fields']) && is_iterable( $item['fields'] ) ){
										$item['attr'] = $item['fields'];
										unset($item['fields']);
										$detect_old_builder = true;
									}

									if( isset($item['tabs']) && is_iterable( $item['tabs'] ) ){
										$item['tabs'] = $item['tabs'];
									}

									if( is_array($item) ){
										$item['jsclass'] = $item['type'];
										$item['title'] = isset( $item['title'] ) ? $item['title'] : ucfirst(str_replace('_', ' ', $item['type']));
										$item['icon'] = str_replace('_', '-', $item['type']);
									}

								}

								$return[] = $item;

							}
						}

						// Global Section/Wraps -> On first render it's empty string, we have to exclude it
						if( !is_string($wrap) ){
							unset( $wrap['items'] );
						}

						if( is_array($wrap) ){
							$wrap['jsclass'] = 'wrap';
							$wrap['title'] = 'Wrap';
							$wrap['icon'] = 'wrap';
							if( empty($wrap['attr']['sticky']) ) {
								$wrap['attr']['sticky'] = '0';
								$wrap['attr']['sticky_tablet'] = '0';
								$wrap['attr']['sticky_mobile'] = '0';
							}
						}
						$return[] = $wrap;
					}
					unset( $section['wraps'] );
				}

				$section['jsclass'] = 'section';
				$section['title'] = 'Section';
				$section['icon'] = 'section';
				$return[] = $section;
			}
		}


		// add uids for iframe

		if( $detect_old_builder ) {

			if ( 'encode' == mfn_opts_get('builder-storage') ) {
				$new = call_user_func('base'.'64_encode', serialize($mfn_items));
			}else{
				$new = $mfn_items;
			}

			update_post_meta($p_id, 'mfn-page-items', $new);

		}

		return $return;
	}

	public function get_pageoptions() {

		$options = array();
		$options['uid'] = 'pageoptions';
		$options['jsclass'] = 'pageoption';
		$devices = array('laptop', 'tablet', 'mobile');

		// options
		if( is_iterable($this->page_options) ) {
			foreach( $this->page_options as $o=>$opt ) {

				if( is_array($opt) ) {
					foreach ($opt as $t => $tval) {
						if( isset($tval['id']) ) {
							$is_old_style = false;

							$opt_value = get_post_meta( $this->post_id, $tval['id'], true );

							if( !empty($tval['old_id']) && empty($opt_value) ) {
								$opt_value = get_post_meta( $this->post_id, $tval['old_id'], true );
								$is_old_style = true;
							}

							if( isset($opt_value) && $opt_value != '' ) {

								if( strpos($tval['id'], 'css_') !== false ) {

									if( strpos($opt_value, '{') !== false ) {

										if($is_old_style){
											$options[$tval['id']]['val'] = json_decode($opt_value, true);
										}else{
											$options[$tval['id']] = json_decode($opt_value, true);
										}

									}else if($is_old_style){
										$options[$tval['id']]['val'] = $opt_value;
									}

									if($is_old_style){
										$options[$tval['id']]['css_path'] = $tval['css_path'];
										$options[$tval['id']]['css_style'] = $tval['css_style'];
									}

								}elseif( strpos($tval['id'], 'css_') === false ){
									$options[$tval['id']] = $opt_value;
								}elseif( isset($tval['std']) ){
									$options[$tval['id']] = $tval['std'];
								}

							}


							if( !empty($tval['responsive']) ){

								foreach($devices as $device){
									$is_old_style = false;
									$opt_value = false;
									$res_key = $tval['id'].'_'.$device;

									$opt_value = get_post_meta( $this->post_id, $res_key, true );

									if( !empty($tval['old_id']) && empty($opt_value) ){
										$res_old_key = $tval['old_id'].'_'.$device;
										$is_old_style = true;
										$opt_value = get_post_meta( $this->post_id, $res_old_key, true );
									}

									if( isset($opt_value) && $opt_value != '' ) {

										if( strpos($tval['id'], 'css_') !== false ) {

											if( strpos($opt_value, '{') !== false ){

												if($is_old_style){
													$options[$res_key]['val'] = json_decode($opt_value, true);
												}else{
													$options[$res_key] = json_decode($opt_value, true);
												}

											}else if($is_old_style){
												$options[$res_key]['val'] = $opt_value;
											}

											if($is_old_style){
												$options[$res_key]['css_path'] = $tval['css_path'];
												$options[$res_key]['css_style'] = $tval['css_style'];
											}

										}elseif( strpos($tval['id'], 'css_') === false ){
											$options[$res_key] = $opt_value;
										}elseif( isset($tval['std']) ){
											$options[$res_key] = $tval['std'];
										}

									}

								}

							}

						}
					}
				}
			}
		}

		return $options;

	}


	public function sizes($size){
		$classes = array(
  			'divider' => 'divider',
  			'1/6' => 'one-sixth',
  			'1/5' => 'one-fifth',
  			'1/4' => 'one-fourth',
  			'1/3' => 'one-third',
  			'2/5' => 'two-fifth',
  			'1/2' => 'one-second',
  			'3/5' => 'three-fifth',
  			'2/3' => 'two-third',
  			'3/4' => 'three-fourth',
  			'4/5' => 'four-fifth',
  			'5/6' => 'five-sixth',
  			'1/1' => 'one'
  		);

  		return $classes[$size];
	}



	public function rewriteFields(){
		$mfn_fields = new Mfn_Builder_Fields(true);
		$output = '';
		$items = $mfn_fields->get_items();

		foreach($items as $w=>$widget){

			if( !empty($widget['attr']) ){

				if( !empty($widget['attr']) && is_iterable($widget['attr']) ){
					foreach ($widget['attr'] as $a => $attr) {
						if( !empty($attr['id']) && strlen($attr['id']) > 70 ){
							$expl_helper = explode(':', $attr['id']);

							$css_path = str_replace('|', ':', $expl_helper[1]);
							$new_id = str_replace(array('style', ' ', ':', '.', '|', '>', ',', '(', ')', 'mcb-section', 'mcb-wrap', 'mcb-item-mfnuidelement', 'mfn'), '', $css_path);

							$new_id .= '_'.str_replace('-', '_', $expl_helper[2]);

							if( strpos($new_id, 'hover') !== false ){
								$new_id = str_replace('hover', '', $new_id).'_hover';
							}

							$output .= "'old_id' => '".$attr['id']."',"."\r\n";
							$output .= "'id' => 'css_".$new_id."',"."\r\n";
							$output .= "'css_path' => '".$css_path."',"."\r\n";
							$output .= "'css_style' => '".$expl_helper[2]."',"."\r\n"."\r\n";
						}
					}
				}
			}
		}


		echo $output;
	}


	public function mfn_load_sidebar(){

		global $wpdb;

		//$this->rewriteFields();

		if( !empty( get_post_meta($this->post_id, '_elementor_edit_mode', true) ) ){
			delete_post_meta($this->post_id, '_elementor_edit_mode');
		}

		if( !get_user_meta($this->user, 'rich_editing', true) || get_user_meta($this->user, 'rich_editing', true) == 'false' ){
			update_user_meta($this->user, 'rich_editing', 'true');
		}

		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		$this->mfn_required_scripts();
		$this->mfn_required_styles();

		$this->options = Mfn_Builder_Helper::get_options();

		$builder_class = array();
		$builder_class[] = 'mfn-vb-'.$this->post_type;

		if($this->post_type == 'template' && !empty($this->template_type)){
			$builder_class[] = 'mfn-vb-tmpl-'.$this->template_type;

			if($this->template_type == 'header') {
				add_filter( 'admin_body_class', array( $this, 'mfn_add_admin_beheader_class') );
			}else if($this->template_type == 'megamenu') {
				add_filter( 'admin_body_class', array( $this, 'mfn_add_admin_bemegamenu_class') );
			}else if($this->template_type == 'cart') {
				add_filter( 'admin_body_class', array( $this, 'mfn_add_admin_becart_class') );
			}else if($this->template_type == 'footer') {
				add_filter( 'admin_body_class', array( $this, 'mfn_add_admin_befooter_class') );
			}else if($this->template_type == 'popup') {
				add_filter( 'admin_body_class', array( $this, 'mfn_add_admin_bepopup_class') );
			}else if( $this->template_type === 'section' ){
				add_filter( 'admin_body_class', array( $this, 'mfn_add_admin_beglobalsections_class') );
			}else if( $this->template_type === 'wrap' ){
				add_filter( 'admin_body_class', array( $this, 'mfn_add_admin_beglobalwraps_class') );
			}
		}

		if( is_array( $this->options ) ){
			foreach( $this->options as $option_id => $option_val ){
				if( $option_val == "1" ){
					$builder_class[] = $option_id;
				}elseif( $option_val != "0" ){
					$builder_class[] = $option_val;
				}
			}
		}

		if( (!empty($this->options['user-interface']) && $this->options['user-interface'] == 'dev') || (!empty($_GET['ui']) && $_GET['ui'] == 'developer') ) $this->ui_mode = 'dev';

		if( is_admin() ){
			require_once(get_theme_file_path('/visual-builder/visual-builder-header.php'));
		}else{
			require_once(get_theme_file_path('/visual-builder/bebuilder-demo-header.php'));
		}

		do_action('mfn_yoast');

		$detectUiTheme = false;

		if( in_array( 'mfn-ui-auto', $builder_class) || ( !in_array( 'mfn-ui-auto', $builder_class) && !in_array( 'mfn-ui-dark', $builder_class) && !in_array( 'mfn-ui-light', $builder_class) ) ) {
			$builder_class[] = 'mfn-ui-auto';
			$detectUiTheme = true;
		}

		$builder_class[] = 'mfn-bebuilder-'.( is_admin() ? 'admin' : 'demo' );

		if( function_exists('is_woocommerce') ) {
			$builder_class[] = 'woocommerce-active';
		}

		if( $this->view == 'demo' && !empty($_GET['ui']) && $_GET['ui'] == 'blocks' ) $builder_class[] = 'builder-blocks';

		$builder_class = implode( ' ', $builder_class );

		echo '<div class="frameOverlay"></div><div id="mfn-visualbuilder" class="mfn-ui mfn-visualbuilder '.esc_attr( $builder_class ).'" data-tutorial="'. apply_filters('betheme_disable_support', '0') .'">';

		$oMenus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		if( $detectUiTheme ) echo "<script>var mfnuicont = document.getElementById('mfn-visualbuilder'); if( mfnuicont.classList.contains('mfn-ui-auto') && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ){mfnuicont.classList.add('mfn-ui-dark');}</script>";

		require_once(get_theme_file_path('/visual-builder/partials/preloader.php'));

		echo '<div class="mfn-contextmenu mfn-items-list-contextmenu"><ul><li><a href="#" data-action="love-it"><span class="mfn-icon mfn-icon-star"></span><span class="label">Add to favourites</span></a></li></ul></div>';

		require_once(get_theme_file_path('/visual-builder/partials/navigator.php'));

		echo '<div style="position: fixed; z-index: 9999;" class="mfn-contextmenu mfn-builder-area-contextmenu"><h6 class="mfn-context-header">Section</h6><ul><li><a href="#" data-action="edit"><span class="mfn-icon mfn-icon-edit"></span><span class="label">Edit</span></a></li><li class="mfn-context-li-copy"><a href="#" class="mfn-context-copy" data-action="copy"><span class="mfn-icon mfn-icon-copy"></span><span class="label">Copy</span></a></li><li class="mfn-context-li-clone"><a href="#" class="mfn-context-clone" data-action="clone"><span class="mfn-icon mfn-icon-clone"></span><span class="label">Duplicate</span></a></li><li class="mfn-context-li-paste"><a href="#" class="mfn-context-paste" data-action="paste"><span class="mfn-icon mfn-icon-paste"></span><span class="label">Paste</span></a></li><li class="mfn-contextmenu-delete"><a href="#" data-action="delete"><span class="mfn-icon mfn-icon-delete-red"></span><span class="label">Delete</span></a></li></ul></div>';

		if( is_admin() ) {

			$edit_lock = wp_check_post_lock($this->post_id);

			if( $edit_lock && $edit_lock != get_current_user_id() ) {
				require_once(get_theme_file_path('/visual-builder/partials/locker.php'));
			}else{
				wp_set_post_lock($this->post_id);
			}

		}

			if( $this->ui_mode == 'dev' ){
				$this->dev_ui_sidebar();
			}else{
				$this->default_ui_sidebar();
			}

		// introduction
	    require_once(get_theme_file_path('/visual-builder/partials/introduction.php'));

	    // shortcuts
    require_once(get_theme_file_path('/visual-builder/partials/shortcuts.php'));

		// dynamic data info
	  require_once(get_theme_file_path('/visual-builder/partials/dynamic-data.php'));

    // modal icons
		require_once(get_theme_file_path('/visual-builder/partials/modal-icons.php'));

		// modal shortcodes
		require_once(get_theme_file_path('/visual-builder/partials/modal-shortcodes.php'));

		// modal dynamic data
		require_once(get_theme_file_path('/visual-builder/partials/modal-dynamic-data.php'));

		// modal conditional logic
		require_once(get_theme_file_path('/visual-builder/partials/modal-conditional-logic.php'));

		if( $this->post_type == 'template' ) require_once(get_theme_file_path('/visual-builder/partials/modal-conditions.php'));

	  if( $this->post_type == 'template' &&  in_array($this->template_type, array('cart', 'checkout', 'thanks')) ) require_once(get_theme_file_path('/visual-builder/partials/modal-cart-confirmation.php'));

	  echo '</div>';

	    $theme_disable = mfn_opts_get('theme-disable');
	    if ( !isset($theme_disable['custom-icons']) ) Mfn_Post_Type_Icons::load_icons();
	    wp_enqueue_script( 'mfn-opts-field-upload', MFN_OPTIONS_URI .'fields/upload/vb_field_upload.js', array( 'jquery' ), MFN_THEME_VERSION, true );

	    if( is_admin() ){
	    	require_once(get_theme_file_path('/visual-builder/visual-builder-footer.php'));
	    }else{
			require_once(get_theme_file_path('/visual-builder/bebuilder-demo-footer.php'));
		}

	}



	public function default_ui_sidebar() {

		// start sidebar
	    echo '<div class="sidebar-wrapper" id="mfn-vb-sidebar">';

	    echo '<div id="mfn-sidebar-resizer"></div>';
	    echo '<div id="mfn-sidebar-switcher"></div>';

	  // sidebar left
	  require_once(get_theme_file_path('/visual-builder/partials/sidebar-menu.php'));

	  // end sidebar left

	  // start sidebar panel
	    echo '<div class="sidebar-panel">';

	    // start sidebar header

	  require_once(get_theme_file_path('/visual-builder/partials/sidebar-header.php'));

	  // end sidebar header

	  // items panel
	    echo '<div class="sidebar-panel-content">';

	    // start items panel
	    require_once(get_theme_file_path('/visual-builder/partials/sidebar-widgets.php'));

	    // end items panel

	   	// start pre build
	   	require_once(get_theme_file_path('/visual-builder/partials/sidebar-prebuilds.php'));
	   	// end pre build

		// start globals panel
		require_once(get_theme_file_path('/visual-builder/partials/sidebar-globals.php'));
		// end global panel

	   	if( is_admin() ){
		    // start revision
		    require_once(get_theme_file_path('/visual-builder/partials/sidebar-revisions.php'));
		    // end revisions
		}

	    if( is_admin() ){
		    // start export/import
		    require_once(get_theme_file_path('/visual-builder/partials/sidebar-export-import.php'));
		    // end export/import
		}

	    // start settings
	   	require_once(get_theme_file_path('/visual-builder/partials/sidebar-settings.php'));
	   	// end settings

	   	// start options
	   	require_once(get_theme_file_path('/visual-builder/partials/sidebar-options.php'));
	   	// end options

	   	// start themeoptions
	   	require_once(get_theme_file_path('/visual-builder/partials/sidebar-themeoptions.php'));
	   	// end themeoptions

	   	// start yoast
	   	if ( defined( 'WPSEO_FILE' ) ) require_once(get_theme_file_path('/visual-builder/partials/sidebar-yoast.php'));
	   	// end yoast

	   // start edit form

	   echo '<div class="panel panel-edit-item" style="display: none;"><div class="mfn-form"></div></div>';
       // end edit form

        echo '</div>';
        // start footer
        require_once(get_theme_file_path('/visual-builder/partials/sidebar-footer.php'));

        // end panel
        echo '</div>';
        // end sidebar
        echo '</div>';


        // iframe

        echo '<div id="mfn-preview-wrapper-holder" class="preview-wrapper">';
        // preview toolbar
        require_once(get_theme_file_path('/visual-builder/partials/preview-toolbar.php'));
        //echo '<pre style="line-height: 1.6em; display:none;">';print_r($mfn_items);echo '</pre>';
        echo '<div id="mfn-preview-wrapper"></div>';

		echo '</div>';

	}







	public function dev_ui_sidebar() {

		// start sidebar
	    echo '<div class="sidebar-wrapper" id="mfn-vb-sidebar">';

	    echo '<div id="mfn-sidebar-resizer"></div>';
	    echo '<div id="mfn-sidebar-switcher"></div>';

	  // sidebar left
	  //require_once(get_theme_file_path('/visual-builder/partials/sidebar-menu.php'));

	  // end sidebar left

	  // start sidebar panel
	    echo '<div class="sidebar-panel">';

	    // start sidebar header

	  require_once(get_theme_file_path('/visual-builder/partials/sidebar-header.php'));

	  // end sidebar header

	  // items panel
	    echo '<div class="sidebar-panel-content">';

	    // start items panel
	    require_once(get_theme_file_path('/visual-builder/partials/sidebar-widgets.php'));

	    // end items panel

	   	// start pre build
	   	require_once(get_theme_file_path('/visual-builder/partials/sidebar-prebuilds.php'));
	   	// end pre build

		// start globals panel
		require_once(get_theme_file_path('/visual-builder/partials/sidebar-globals.php'));
		// end global panel

	   	if( is_admin() ){
		    // start revision
		    require_once(get_theme_file_path('/visual-builder/partials/sidebar-revisions.php'));
		    // end revisions
		}

	    if( is_admin() ){
		    // start export/import
		    require_once(get_theme_file_path('/visual-builder/partials/sidebar-export-import.php'));
		    // end export/import
		}

	    // start settings
	   	require_once(get_theme_file_path('/visual-builder/partials/sidebar-settings.php'));
	   	// end settings

	   	// start options
	   	require_once(get_theme_file_path('/visual-builder/partials/sidebar-options.php'));
	   	// end options

	   	// start themeoptions
	   	require_once(get_theme_file_path('/visual-builder/partials/sidebar-themeoptions.php'));
	   	// end themeoptions

	   	// start yoast
	   	if ( defined( 'WPSEO_FILE' ) ) require_once(get_theme_file_path('/visual-builder/partials/sidebar-yoast.php'));
	   	// end yoast

	   // start edit form

	   echo '<div class="panel panel-edit-item" style="display: none;"><div class="mfn-form"></div></div>';
       // end edit form

        echo '</div>';
        // start footer
        //require_once(get_theme_file_path('/visual-builder/partials/sidebar-footer.php'));

        // end panel
        echo '</div>';
        // end sidebar
        echo '</div>';


        // dev toolbar
        require_once(get_theme_file_path('/visual-builder/partials/dev-toolbar.php'));

        echo '<div id="mfn-preview-wrapper-holder" class="preview-wrapper">';
        // dev toolbar
        require_once(get_theme_file_path('/visual-builder/partials/dev-toolbar.php'));
        echo '<div id="mfn-preview-wrapper"></div>';

		echo '</div>';

		// another pages
	  require_once(get_theme_file_path('/visual-builder/partials/modal-another-page.php'));

	}

























































	public function getPresets( $both = false ){

		$return = array();

		if( $both ){
			$local = array();
			$jsonfile = get_theme_file_path('/visual-builder/assets/presets.json');
			if( file_exists($jsonfile) ){
				$local = file_get_contents( $jsonfile );
				if( !empty($local) ) $return = json_decode($local);
			}
		}

		$get_opt = get_option('mfn-presets');

		if( !empty($get_opt) ) {
			if( count($return) > 0 ){
				$return = array_merge( $return, json_decode( $get_opt ) ?? [] );
			}else{
				$return = json_decode( $get_opt ) ?? [];
			}
		}

		return $return;
	}

	public function wrapHtml($item_id, $size, $order, $sizeclass){
		$mfn_helper = new Mfn_Builder_Helper();
		$html = '<div data-title="Wrap" data-icon="mfn-icon-wrap" data-order="'.$order.'" data-uid="'.$item_id.'" data-desktop-size="'.$size.'" data-tablet-size="'.$size.'" data-mobile-size="1/1" class="blink wrap mcb-wrap mcb-wrap-new vb-item vb-item-wrap mcb-wrap-'.$item_id.' '.$sizeclass.' tablet-'.$sizeclass.' mobile-one clearfix"><div class="mfn-drag-helper mfn-dh-before placeholder-wrap"></div><div class="mfn-drag-helper mfn-dh-after placeholder-wrap"></div><div class="mcb-wrap-inner empty">'.$mfn_helper->wrapTools($size).'<div class="mfn-wrap-new"><a href="#" class="mfn-item-add mfn-btn btn-icon-left btn-small mfn-btn-blank2"><span class="btn-wrapper"><span class="mfn-icon mfn-icon-add"></span>Add element</span></a></div></div></div>';

		return $html;
	}

	/**
	 * Builder data file - remove
	 */

	public static function removeBeDataFile(){
		if( file_exists( self::bebuilderFilePath()) ) wp_delete_file( self::bebuilderFilePath());

		update_option('betheme_form_uid', Mfn_Builder_Helper::unique_ID());

		return true;
	}

	public static function bebuilderFilePath( $uri = false ){
		$bebuilder_items_file = '/visual-builder/assets/js/forms/bebuilder-'.MFN_THEME_VERSION.'.js';

    $bebuilder_items_path = get_template_directory() . $bebuilder_items_file;

    if( $uri ){
    	$bebuilder_items_path = get_template_directory_uri() . $bebuilder_items_file;
    }

    return $bebuilder_items_path;
	}
}
