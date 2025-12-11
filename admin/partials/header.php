<?php
/**
 * Admin page header partial.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/partials
 */

if (!defined('ABSPATH')) {
	exit;
}
?>

<div class="ai-seo-pro-header">
	<div class="ai-seo-pro-header-logo">
		<!-- <img src="<//?php echo esc_url( AI_SEO_PRO_PLUGIN_URL . 'assets/images/logo.png' ); ?>" alt="AI SEO Pro" style="height: 40px;"> -->
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
	</div>

	<!-- <div class="ai-seo-pro-header-actions">
		<a href="https://github.com/yourusername/ai-seo-pro" target="_blank" class="button">
			<//?php _e( 'Documentation', 'ai-seo-pro' ); ?>
		</a>
		<a href="https://github.com/yourusername/ai-seo-pro/issues" target="_blank" class="button">
			<//?php _e( 'Support', 'ai-seo-pro' ); ?>
		</a>
	</div> -->
</div>

<style>
	.ai-seo-pro-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 20px 0;
		border-bottom: 1px solid #ddd;
		margin-bottom: 20px;
	}

	.ai-seo-pro-header-logo {
		display: flex;
		align-items: center;
		gap: 15px;
	}

	.ai-seo-pro-header-logo h1 {
		margin: 0;
		font-size: 24px;
	}

	.ai-seo-pro-header-actions {
		display: flex;
		gap: 10px;
	}
</style>