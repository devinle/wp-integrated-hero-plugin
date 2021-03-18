<?php
/**
 * Plugin Name:     Integrated Hero
 * Description:     An integrated editing experience with a build in Hero.
 * Version:         0.1.0
 * Author:          The WordPress Contributors
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     integrated-hero
 *
 * @package         integrated-hero-block
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function integrated_hero_block_integrated_hero_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "integrated-hero-block/integrated-hero" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'integrated-hero-block-integrated-hero-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_set_script_translations( 'integrated-hero-block-integrated-hero-block-editor', 'integrated-hero' );

	$editor_css = 'build/index.css';
	wp_register_style(
		'integrated-hero-block-integrated-hero-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'integrated-hero-block-integrated-hero-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'integrated-hero-block/integrated-hero', array(
		'editor_script'   => 'integrated-hero-block-integrated-hero-block-editor',
		'editor_style'    => 'integrated-hero-block-integrated-hero-block-editor',
		'style'           => 'integrated-hero-block-integrated-hero-block',
		'render_callback' => 'integrated_hero_block_render',
	) );
}
add_action( 'init', 'integrated_hero_block_integrated_hero_block_init' );

/**
 * Render callback for server-side rendering of the
 * integrated hero block.
 */
function integrated_hero_block_render( $block_attributes, $content ) {

	return sprintf(
		'<header class="wp-block-integrated-hero-block-integrated-hero">%1$s<div class="wp-block-integrated-hero-block-integrated-hero__content"><h1>%2$s</h1><p>%3$s</p></div></header>',
		get_the_post_thumbnail(),
		get_the_title(),
		get_the_excerpt()
	);
}

/**
 * Register a template with Post Types to include the
 * integrated hero block.
 */
function integrated_hero_block_register_template() {
    $post_type_object = get_post_type_object( 'post' );
    $post_type_object->template = array(
        array( 'integrated-hero-block/integrated-hero' ),
    );
}
add_action( 'init', 'integrated_hero_block_register_template' );
