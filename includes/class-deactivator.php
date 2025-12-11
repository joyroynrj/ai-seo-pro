<?php
/**
 * Fired during plugin deactivation.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/includes
 * @author     Joy Roy
 */
class AI_SEO_Pro_Deactivator {

	/**
	 * Deactivation tasks.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
		// Clear scheduled hooks (if any)
		wp_clear_scheduled_hook( 'ai_seo_pro_daily_cleanup' );
		
		// Flush rewrite rules
		flush_rewrite_rules();
		
		// Clear transients
		self::clear_transients();
		
		// Clear cache
		wp_cache_flush();
	}

	/**
	 * Clear plugin transients.
	 *
	 * @since    1.0.0
	 */
	private static function clear_transients() {
		global $wpdb;

		$transients = $wpdb->get_col(
			"SELECT option_name FROM {$wpdb->options} 
			WHERE option_name LIKE '_transient_ai_seo_pro_%' 
			OR option_name LIKE '_transient_timeout_ai_seo_pro_%'"
		);

		foreach ( $transients as $transient ) {
			delete_option( $transient );
		}
	}
}