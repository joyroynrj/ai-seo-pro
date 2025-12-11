<?php
/**
 * Help sidebar partial.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/partials
 */

if (!defined('ABSPATH')) {
	exit;
}
?>

<div class="ai-seo-pro-help-sidebar">
	<div class="help-section">
		<h3><?php _e('Quick Help', 'ai-seo-pro'); ?></h3>

		<div class="help-item">
			<h4><?php _e('Getting Started', 'ai-seo-pro'); ?></h4>
			<ol>
				<li><?php _e('Configure your API key in settings', 'ai-seo-pro'); ?></li>
				<li><?php _e('Enable post types you want to optimize', 'ai-seo-pro'); ?></li>
				<li><?php _e('Edit any post and use the AI SEO meta box', 'ai-seo-pro'); ?></li>
				<li><?php _e('Click "Generate with AI" to create meta tags', 'ai-seo-pro'); ?></li>
			</ol>
		</div>

		<div class="help-item">
			<h4><?php _e('API Providers', 'ai-seo-pro'); ?></h4>
			<ul>
				<li><strong>Anthropic:</strong> <?php _e('Excellent for long-form content', 'ai-seo-pro'); ?></li>
				<li><strong>Google Gemini:</strong> <?php _e('Great for technical content', 'ai-seo-pro'); ?></li>
			</ul>
		</div>
	</div>

	<!-- <div class="help-section">
		<h3><//?php _e( 'Resources', 'ai-seo-pro' ); ?></h3>
		<ul class="help-links">
			<li><a href="https://github.com/yourusername/ai-seo-pro" target="_blank">
				<//?php _e( 'Documentation', 'ai-seo-pro' ); ?>
			</a></li>
			<li><a href="https://github.com/yourusername/ai-seo-pro/issues" target="_blank">
				<//?php _e( 'Report Issues', 'ai-seo-pro' ); ?>
			</a></li>
			<li><a href="https://wordpress.org/support/plugin/ai-seo-pro" target="_blank">
				<//?php _e( 'Support Forum', 'ai-seo-pro' ); ?>
			</a></li>
		</ul>
	</div> -->

	<div class="help-section">
		<h3><?php _e('System Status', 'ai-seo-pro'); ?></h3>
		<table class="system-status">
			<tr>
				<td><?php _e('WordPress Version:', 'ai-seo-pro'); ?></td>
				<td><?php echo get_bloginfo('version'); ?></td>
			</tr>
			<tr>
				<td><?php _e('PHP Version:', 'ai-seo-pro'); ?></td>
				<td><?php echo PHP_VERSION; ?></td>
			</tr>
			<tr>
				<td><?php _e('Plugin Version:', 'ai-seo-pro'); ?></td>
				<td><?php echo AI_SEO_PRO_VERSION; ?></td>
			</tr>
			<tr>
				<td><?php _e('API Configured:', 'ai-seo-pro'); ?></td>
				<td>
					<?php
					$api_key = get_option('ai_seo_pro_api_key');
					echo !empty($api_key) ? '✓' : '✗';
					?>
				</td>
			</tr>
		</table>
	</div>
</div>

<style>
	.ai-seo-pro-help-sidebar {
		background: #f9f9f9;
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 20px;
	}

	.help-section {
		margin-bottom: 25px;
	}

	.help-section:last-child {
		margin-bottom: 0;
	}

	.help-section h3 {
		margin-top: 0;
		color: #23282d;
		font-size: 16px;
		border-bottom: 1px solid #ddd;
		padding-bottom: 10px;
	}

	.help-item {
		margin-bottom: 15px;
	}

	.help-item h4 {
		margin: 10px 0 5px 0;
		font-size: 14px;
		color: #555;
	}

	.help-links {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	.help-links li {
		margin-bottom: 8px;
	}

	.help-links a {
		text-decoration: none;
		color: #0073aa;
	}

	.help-links a:hover {
		color: #005177;
	}

	.system-status {
		width: 100%;
		font-size: 13px;
	}

	.system-status td {
		padding: 5px 0;
	}

	.system-status td:first-child {
		font-weight: 600;
		width: 60%;
	}
</style>