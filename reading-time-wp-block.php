<?php
/**
 * Plugin Name: Reading Time WP Block
 * Description: Adds a block for the Reading Time WP plugin that can be used in query loop blocks, patterns, and templates.
 * Version: 1.0
 * Author: Ryan Townley
 * Author URI: https://ryantownley.com/
 * License: GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain: reading-time-wp-block
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register the block.
add_action('init', 'rt_register_reading_time_block');
function rt_register_reading_time_block() {
    // Register block editor script
    wp_register_script(
        'rt-reading-time-block-script',
        plugins_url( 'rt-reading-time-block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-editor' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'rt-reading-time-block.js' )
    );

    // Add inline CSS for the block editor
    add_action( 'admin_head', 'rt_add_inline_editor_css' );

    // Register block type
    register_block_type('reading-time-wp-block/rt-reading-time', array(
        'editor_script'   => 'rt-reading-time-block-script',
        'render_callback' => 'rt_render_reading_time_block',
        'attributes' => array(
            'label' => array(
                'type' => 'string',
                'default' => ''
            ),
            'postfix' => array(
                'type' => 'string',
                'default' => 'min read'
            ),
            'postfixSingular' => array(
                'type' => 'string',
                'default' => 'min read'
            ),
        ),
    ));
}

// Add inline CSS for the block editor
function rt_add_inline_editor_css() {
    echo '<style>
        p.rt-reading-time {
            margin: 0;
        }
    </style>';
}

// Server-side rendering callback for the block
function rt_render_reading_time_block($attributes) {
    global $post;

    // Fallback if there's no global $post
    if ( ! isset( $post ) ) {
        return '<div class="rt-reading-time">Reading time will be calculated dynamically.</div>';
    }

    // Get block attributes
    $label = isset($attributes['label']) ? $attributes['label'] : '';
    $postfix = isset($attributes['postfix']) ? $attributes['postfix'] : 'min read';
    $postfix_singular = isset($attributes['postfixSingular']) ? $attributes['postfixSingular'] : 'min read';

    // Construct the shortcode
    $shortcode = '[rt_reading_time label="' . esc_attr( $label ) . '" post_id="' . $post->ID . '" postfix="' . esc_attr( $postfix ) . '" postfix_singular="' . esc_attr( $postfix_singular ) . '"]';

    // If in the block editor, return a static preview
    $is_editor = defined( 'REST_REQUEST' ) && REST_REQUEST;
    if ( $is_editor ) {
        return '<div class="rt-reading-time">Reading time will appear here.</div>';
    }

    // Render the shortcode on the front end
    return '<div class="rt-reading-time">' . do_shortcode( $shortcode ) . '</div>';
}