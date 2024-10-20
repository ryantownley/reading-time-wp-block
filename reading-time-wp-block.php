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
        plugins_url( 'blocks/rt-reading-time-block.js', __FILE__ ),
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime( plugin_dir_path( __FILE__ ) . 'blocks/rt-reading-time-block.js' )
    );

    // Register block type
    register_block_type('reading-time-wp-block/rt-reading-time', array(
        'editor_script' => 'rt-reading-time-block-script',
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

// Server-side rendering callback
function rt_render_reading_time_block($attributes) {
    global $post;

    if ( ! isset( $post ) ) {
        return '';
    }

    // Get block attributes
    $label = isset($attributes['label']) ? $attributes['label'] : '';
    $postfix = isset($attributes['postfix']) ? $attributes['postfix'] : 'min read';
    $postfix_singular = isset($attributes['postfixSingular']) ? $attributes['postfixSingular'] : 'min read';

    // Use the existing shortcode with post ID
    $reading_time_output = do_shortcode(
        '[rt_reading_time label="' . esc_attr($label) . '" post_id="' . $post->ID . '" postfix="' . esc_attr($postfix) . '" postfix_singular="' . esc_attr($postfix_singular) . '"]'
    );

    return '<div class="rt-reading-time">' . $reading_time_output . '</div>';
}