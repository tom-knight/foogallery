<?php

if ( !class_exists( 'FooGallery_Default_Gallery_Template' ) ) {

	define('FOOGALLERY_DEFAULT_GALLERY_TEMPLATE_URL', plugin_dir_url( __FILE__ ));

	class FooGallery_Default_Gallery_Template {
		/**
		 * Wire up everything we need to run the extension
		 */
		function __construct() {
			add_filter( 'foogallery_gallery_templates', array( $this, 'add_template' ) );

			//add extra fields to the templates
			add_filter( 'foogallery_override_gallery_template_fields-default', array( $this, 'add_common_thumbnail_fields' ), 10, 2 );

			add_action( 'foogallery_located_template-default', array( $this, 'enqueue_dependencies' ) );

			add_filter( 'foogallery_gallery_templates_files', array( $this, 'register_myself' ) );
		}

		/**
		 * Register myself so that all associated JS and CSS files can be found and automatically included
		 * @param $extensions
		 *
		 * @return array
		 */
		function register_myself( $extensions ) {
			$extensions[] = __FILE__;
			return $extensions;
		}

		/**
		 * Add our gallery template to the list of templates available for every gallery
		 * @param $gallery_templates
		 *
		 * @return array
		 */
		function add_template( $gallery_templates ) {
			$gallery_templates[] = array(
				'slug'        => 'default',
				'name'        => __( 'Responsive Image Gallery', 'foogallery' ),
				'lazyload_support' => true,
				'fields'	  => array(
                    array(
                        'id'      => 'thumbnail_dimensions',
                        'title'   => __( 'Thumbnail Size', 'foogallery' ),
                        'desc'    => __( 'Choose the size of your thumbnails.', 'foogallery' ),
                        'section' => __( 'General', 'foogallery' ),
                        'type'    => 'thumb_size',
                        'default' => array(
                            'width' => get_option( 'thumbnail_size_w' ),
                            'height' => get_option( 'thumbnail_size_h' ),
                            'crop' => true,
                        ),
                    ),
                    array(
                        'id'      => 'thumbnail_link',
                        'title'   => __( 'Link To', 'foogallery' ),
                        'section' => __( 'General', 'foogallery' ),
                        'default' => 'image',
                        'type'    => 'thumb_link',
                        'desc'	  => __( 'You can choose to link each thumbnail to the full size image, the image\'s attachment page, a custom URL, or you can choose to not link to anything.', 'foogallery' ),
                    ),
					array(
						'id'      => 'lightbox',
						'title'   => __( 'Lightbox', 'foogallery' ),
						'desc'    => __( 'Choose which lightbox you want to use. The lightbox will generally only work if you set the thumbnail link to "Full Size Image".', 'foogallery' ),
                        'section' => __( 'General', 'foogallery' ),
						'type'    => 'lightbox',
						'row_data'=> array(
							'data-foogallery-change-selector' => 'select'
						)
					),
					array(
						'id'      => 'lightbox_foobox_help',
						'title'   => __( 'FooBox Help', 'foogallery' ),
						'desc'    => __( 'The FooBox lightbox is a separate plugin.', 'foogallery' ),
						'section' => __( 'General', 'foogallery' ),
						'type'    => 'help',
						'row_data'=> array(
							'data-foogallery-hidden' => true,
							'data-foogallery-show-when-field' => 'lightbox',
							'data-foogallery-show-when-field-value' => 'foobox'
						)
					),
					array(
						'id'      => 'spacing',
						'title'   => __( 'Spacing', 'foogallery' ),
						'desc'    => __( 'The spacing or gap between thumbnails in the gallery.', 'foogallery' ),
                        'section' => __( 'General', 'foogallery' ),
						'type'    => 'select',
						'default' => 'fg-gutter-10',
						'choices' => array(
							'fg-gutter-0' => __( 'none', 'foogallery' ),
							'fg-gutter-5' => __( '5 pixels', 'foogallery' ),
							'fg-gutter-10' => __( '10 pixels', 'foogallery' ),
							'fg-gutter-15' => __( '15 pixels', 'foogallery' ),
							'fg-gutter-20' => __( '20 pixels', 'foogallery' ),
							'fg-gutter-25' => __( '25 pixels', 'foogallery' ),
						),
                        'row_data'=> array(
                            'data-foogallery-change-selector' => 'select',
							'data-foogallery-preview' => 'class'
                        )
					),
					array(
						'id'      => 'alignment',
						'title'   => __( 'Alignment', 'foogallery' ),
						'desc'    => __( 'The horizontal alignment of the thumbnails inside the gallery.', 'foogallery' ),
                        'section' => __( 'General', 'foogallery' ),
						'default' => 'fg-center',
						'type'    => 'radio',
						'spacer'  => '<span class="spacer"></span>',
						'choices' => array(
							'fg-left' => __( 'Left', 'foogallery' ),
							'fg-center' => __( 'Center', 'foogallery' ),
							'fg-right' => __( 'Right', 'foogallery' ),
						),
                        'row_data'=> array(
                            'data-foogallery-change-selector' => 'input:radio',
							'data-foogallery-preview' => 'class'
                        )
					)
				)
			);

			return $gallery_templates;
		}

		/**
		 * Add thumbnail fields to the gallery template
		 *
		 * @uses "foogallery_override_gallery_template_fields"
		 * @param $fields
		 * @param $template
		 *
		 * @return array
		 */
		function add_common_thumbnail_fields( $fields, $template ) {
			return apply_filters( 'foogallery_gallery_template_common_thumbnail_fields', $fields );
		}

		/**
		 * Enqueue scripts that the default gallery template relies on
		 */
		function enqueue_dependencies( $gallery ) {
			//enqueue core files
			foogallery_enqueue_core_gallery_template_style();
			foogallery_enqueue_core_gallery_template_script();
		}
	}
}