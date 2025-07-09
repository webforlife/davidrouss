<?php
/**
 * Custom post type: Collectie
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Mfn_Post_Type_Collectie' ) ) {
	class Mfn_Post_Type_Collectie extends Mfn_Post_Type
	{

		/**
		 * Mfn_Post_Type_Collectie constructor
		 */

		public function __construct()
		{
			parent::__construct();

			// fires after WordPress has finished loading but before any headers are sent
			add_action('init', array($this, 'register'));

			// applied to the list of columns to print on the manage posts screen for a custom post type
			add_filter('manage_edit-collectie_columns', array($this, 'add_columns'));

			// allows to add or remove (unset) custom columns to the list post/page/custom post type pages
			add_action('manage_posts_custom_column', array($this, 'custom_column'));

			// admin only methods

			if( is_admin() ){
				$this->fields = $this->set_fields();
				$this->builder = new Mfn_Builder_Admin();
			}

		}

		/**
		 * Set post type fields
		 */

		public function set_fields(){

			return array(

				'id' => 'mfn-meta-collectie',
				'title' => esc_html__('Collectie Options', 'mfn-opts'),
				'page' => 'collectie',
				'fields' => array(

					// layout

					array(
						'title' => __('Layout', 'mfn-opts'),
					),

					array(
						'id' => 'mfn-post-hide-content',
						'type' => 'switch',
						'title' => __('The content', 'mfn-opts'),
						'desc' => __('The content from the WordPress editor', 'mfn-opts'),
						'options'	=> array(
								'1' => __('Hide', 'mfn-opts'),
								'0' => __('Show', 'mfn-opts'),
							),
						'std' => '0'
					),

					array(
						'id' => 'mfn-post-layout',
						'type' => 'radio_img',
						'title' => __('Layout', 'mfn-opts'),
						'desc' => __('Full width sections works only without sidebars', 'mfn-opts'),
						'options' => array(
							'no-sidebar' => __('Full width', 'mfn-opts'),
							'left-sidebar' => __('Left sidebar', 'mfn-opts'),
							'right-sidebar' => __('Right sidebar', 'mfn-opts'),
							'both-sidebars' => __('Both sidebars', 'mfn-opts'),
							'offcanvas-sidebar' => __('Off-canvas sidebar', 'mfn-opts'),
						),
						'std' => 'no-sidebar',
						'alias' => 'sidebar',
						'class' => 'form-content-full-width small',
					),

					array(
						'id' => 'mfn-post-sidebar',
						'type' => 'select',
						'title' => __('Sidebar', 'mfn-opts'),
						'desc' => __('Shows only if layout with sidebar is selected', 'mfn-opts'),
						'options' => mfn_opts_get('sidebars'),
					),

					array(
						'id' => 'mfn-post-sidebar2',
						'type' => 'select',
						'title' => __('Sidebar 2nd', 'mfn-opts'),
						'desc' => __('Shows only if layout with both sidebars is selected', 'mfn-opts'),
						'options' => mfn_opts_get('sidebars'),
					),

						// media

					array(
						'title' => __('Media', 'mfn-opts'),
					),

					array(
						'id' => 'mfn-post-slider',
						'type' => 'select',
						'title' => __('Slider Revolution', 'mfn-opts'),
						'options' => Mfn_Builder_Helper::get_sliders('rev'),
					),

					array(
						'id' => 'mfn-post-slider-layer',
						'type' => 'select',
						'title' => __('Layer Slider', 'mfn-opts'),
						'options' => Mfn_Builder_Helper::get_sliders('layer'),
					),

					array(
						'id' => 'mfn-post-slider-shortcode',
						'type' => 'text',
						'title' => __('Slider shortcode', 'mfn-opts'),
						'desc' => __('Paste slider shortcode if you use other slider plugin', 'mfn-opts'),
					),

					array(
						'id' => 'mfn-post-subheader-image',
						'type' => 'upload',
						'title' => __('Subheader image', 'mfn-opts'),
					),

						// options

					array(
						'title' => __('Options', 'mfn-opts'),
					),

					array(
						'id' => 'mfn-post-one-page',
						'type' => 'switch',
						'title' => __('One Page', 'mfn-opts'),
						'options'	=> array(
								'0' => __('Disable', 'mfn-opts'),
								'1' => __('Enable', 'mfn-opts'),
							),
						'std' => '0'
					),

					array(
						'id' => 'mfn-post-full-width',
						'type' => 'switch',
						'title' => __('Full width', 'mfn-opts'),
						'desc' => __('Set page to full width ignoring <a target="_blank" href="admin.php?page=be-options#general">Site width</a> option. Works for Layout Full width only.', 'mfn-opts'),
						'options'	=> array(
								'0' => __('Disable', 'mfn-opts'),
								'site' => __('Enable', 'mfn-opts'),
								'content' => __('Content only', 'mfn-opts'),
							),
						'std' => '0'
					),

					array(
						'id' => 'mfn-post-hide-title',
						'type' => 'switch',
						'title' => __('Subheader', 'mfn-opts'),
						'options'	=> array(
								'1' => __('Hide', 'mfn-opts'),
								'0' => __('Show', 'mfn-opts'),
							),
						'std' => '0'
					),

					array(
						'id' => 'mfn-post-remove-padding',
						'type' => 'switch',
						'title' => __('Content top padding', 'mfn-opts'),
						'options' => array(
								'1' => __('Hide', 'mfn-opts'),
								'0' => __('Show', 'mfn-opts'),
							),
						'std' => '0'
					),

					array(
						'id' => 'mfn-post-custom-layout',
						'type' => 'select',
						'title' => __('Custom layout', 'mfn-opts'),
						'desc' => __('Custom layout overwrites Theme Options', 'mfn-opts'),
						'options' => $this->get_layouts(),
					),

						// seo

					array(
						'title' => __('SEO', 'mfn-opts'),
					),

					array(
						'id' => 'mfn-meta-seo-title',
						'type' => 'text',
						'title' => __('Title', 'mfn-opts'),
					),

					array(
						'id' => 'mfn-meta-seo-description',
						'type' => 'text',
						'title' => __('Description', 'mfn-opts'),
					),

					array(
						'id' => 'mfn-meta-seo-keywords',
						'type' => 'text',
						'title' => __('Keywords', 'mfn-opts'),
					),

					array(
						'id' => 'mfn-meta-seo-og-image',
						'type' => 'upload',
						'title' => __('Open Graph image', 'mfn-opts'),
						'desc' => __('Facebook share image', 'mfn-opts'),
					),

						// custom css

					array(
						'title' => __('Custom CSS', 'mfn-opts'),
					),

					array(
						'id' => 'mfn-post-css',
						'type' => 'textarea',
						'title' => __('Custom CSS', 'mfn-opts'),
						'desc' => __('Custom CSS code for this page', 'mfn-opts'),
						'class' => 'form-content-full-width',
							'cm' => 'css',
					),

				),
			);
		}

		/**
		 * Register new post type and related taxonomy
		 */

		public function register()
		{
			$labels = array(
  			'name' => esc_html__('Collectie', 'mfn-opts'),
  			'singular_name' => esc_html__('Collectie', 'mfn-opts'),
  			'add_new' => esc_html__('Add New', 'mfn-opts'),
  			'add_new_item' => esc_html__('Add New Collectie', 'mfn-opts'),
  			'edit_item' => esc_html__('Edit Collectie', 'mfn-opts'),
  			'new_item' => esc_html__('New Collectie', 'mfn-opts'),
  			'view_item' => esc_html__('View Collectie', 'mfn-opts'),
  			'search_items' => esc_html__('Search Collectie', 'mfn-opts'),
  			'not_found' => esc_html__('No collectie found', 'mfn-opts'),
  			'not_found_in_trash' => esc_html__('No collectie found in Trash', 'mfn-opts'),
  		);

			$args = array(
				'labels' => $labels,
				'menu_icon' => 'dashicons-car',
				'public' => true,
				'show_ui' => true,
				'has_archive' => true,
				'show_admin_column'	 => true,
				'rewrite' => [ 'slug' => 'collectie', 'with_front' => false ],
				'hierarchical' => true,
				'supports' => array( 'title', 'thumbnail', 'page-attributes', 'custom-fields' ),
				'capabilities' => array(
					'edit_post' => 'edit_collection',
					'edit_posts' => 'edit_collections',
					'edit_others_posts' => 'edit_other_collections',
					'publish_posts' => 'publish_collections',
					'read_post' => 'read_collection',
					'read_private_posts' => 'read_private_collections',
					'delete_post' => 'delete_collection'
				),
			);

			function add_theme_caps() {
				// gets the editor role
				$editor = get_role( 'editor' );

				$editor->add_cap( 'edit_collection' );
				$editor->add_cap( 'edit_collections' );
				$editor->add_cap( 'edit_other_collections' );
				$editor->add_cap( 'publish_collections' );
				$editor->add_cap( 'read_collection' );
				$editor->add_cap( 'read_private_collections' );
				$editor->add_cap( 'delete_collection' );
			}

			add_action( 'admin_init', 'add_theme_caps');

			register_post_type( 'collectie', $args );

			register_taxonomy( 'collectie-categories', 'collectie', array(
				'label' =>  esc_html__('Collectie categorieën', 'mfn-opts'),
				'hierarchical' => true,

			));

		}

		/**
		 * Add new columns to posts screen
		 */

		public function add_columns($columns)
		{
			$newcolumns = array(
				'cb' => '<input type="checkbox" />',
  			'collectie_thumbnail' => esc_html__('Thumbnail', 'mfn-opts'),
  			'title' => esc_html__('Title', 'mfn-opts'),
  			'collectie_types' => esc_html__('Categories', 'mfn-opts'),
  			'collectie_order' => esc_html__('Order', 'mfn-opts'),
  		);
			$columns = array_merge($newcolumns, $columns);

			return $columns;
		}

		/**
		 * Custom column on posts screen
		 */

		public function custom_column($column)
		{
			global $post;

			switch ($column) {
				case 'collectie_thumbnail':
					if (has_post_thumbnail()) {
						the_post_thumbnail('50x50');
					}
					break;
				case 'collectie_types':
					echo get_the_term_list($post->ID, 'collectie-types', '', ', ', '');
					break;
				case 'collectie_order':
					echo esc_html($post->menu_order);
					break;
			}
		}


	}
}

new Mfn_Post_Type_Collectie();
