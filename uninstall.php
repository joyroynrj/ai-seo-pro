<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package    AI_SEO_Pro
 * @author     Joy Roy
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete plugin options
 */
function ai_seo_pro_delete_options() {
	$options = array(
		'ai_seo_pro_api_provider',
		'ai_seo_pro_api_key',
		'ai_seo_pro_auto_generate',
		'ai_seo_pro_enable_content_analysis',
		'ai_seo_pro_enable_seo_score',
		'ai_seo_pro_enable_schema',
		'ai_seo_pro_focus_keyword',
		'ai_seo_pro_readability_analysis',
		'ai_seo_pro_og_tags',
		'ai_seo_pro_twitter_cards',
		'ai_seo_pro_post_types',
		'ai_seo_pro_title_separator',
		'ai_seo_pro_homepage_title',
		'ai_seo_pro_homepage_description',
		'ai_seo_pro_db_version',
	);

	foreach ( $options as $option ) {
		delete_option( $option );
	}
}

/**
 * Delete post meta
 */
function ai_seo_pro_delete_post_meta() {
	global $wpdb;

	$meta_keys = array(
		'_ai_seo_title',
		'_ai_seo_description',
		'_ai_seo_keywords',
		'_ai_seo_focus_keyword',
		'_ai_seo_og_title',
		'_ai_seo_og_description',
		'_ai_seo_twitter_title',
		'_ai_seo_twitter_description',
		'_ai_seo_robots',
		'_ai_seo_canonical',
		'_ai_seo_score',
		'_ai_seo_content_analysis',
		'_ai_seo_auto_generate',
	);

	foreach ( $meta_keys as $meta_key ) {
		$wpdb->delete(
			$wpdb->postmeta,
			array( 'meta_key' => $meta_key ),
			array( '%s' )
		);
	}
}

/**
 * Delete custom database tables
 */
function ai_seo_pro_delete_tables() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'ai_seo_analysis';
	$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
}

/**
 * Run uninstall
 */
ai_seo_pro_delete_options();
ai_seo_pro_delete_post_meta();
ai_seo_pro_delete_tables();

// Clear any cached data
wp_cache_flush();