<?php
/**
 * Help page.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/views
 */

if (!defined('ABSPATH')) {
	exit;
}
?>

<div class="wrap ai-seo-pro-help">
	<?php require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/partials/header.php'; ?>

	<div class="help-content">
		<div class="help-main">
			<div class="help-section">
				<h2><?php _e('Getting Started', 'ai-seo-pro'); ?></h2>

				<div class="help-card">
					<h3><?php _e('1. Configure API Settings', 'ai-seo-pro'); ?></h3>
					<p><?php _e('Navigate to AI SEO Pro → Settings → AI API tab and configure your preferred AI provider:', 'ai-seo-pro'); ?>
					</p>
					<ul>
						<li><?php _e('Choose your AI provider (Anthropic, or Google Gemini)', 'ai-seo-pro'); ?></li>
						<li><?php _e('Enter your API key', 'ai-seo-pro'); ?></li>
						<li><?php _e('Optionally enable auto-generation', 'ai-seo-pro'); ?></li>
					</ul>
				</div>

				<div class="help-card">
					<h3><?php _e('2. Enable Post Types', 'ai-seo-pro'); ?></h3>
					<p><?php _e('Go to Settings → General and select which post types should have SEO optimization:', 'ai-seo-pro'); ?>
					</p>
					<ul>
						<li><?php _e('Posts', 'ai-seo-pro'); ?></li>
						<li><?php _e('Pages', 'ai-seo-pro'); ?></li>
						<li><?php _e('Custom post types', 'ai-seo-pro'); ?></li>
					</ul>
				</div>

				<div class="help-card">
					<h3><?php _e('3. Optimize Your Content', 'ai-seo-pro'); ?></h3>
					<p><?php _e('When editing any post:', 'ai-seo-pro'); ?></p>
					<ol>
						<li><?php _e('Scroll to the AI SEO Pro meta box', 'ai-seo-pro'); ?></li>
						<li><?php _e('Enter your focus keyword (optional)', 'ai-seo-pro'); ?></li>
						<li><?php _e('Click "Generate Now with AI" button', 'ai-seo-pro'); ?></li>
						<li><?php _e('Review and edit the generated meta tags', 'ai-seo-pro'); ?></li>
						<li><?php _e('Check your SEO score and recommendations', 'ai-seo-pro'); ?></li>
					</ol>
				</div>
			</div>

			<div class="help-section">
				<h2><?php _e('Features Overview', 'ai-seo-pro'); ?></h2>

				<div class="features-grid">
					<div class="feature-card">
						<span class="feature-icon">🤖</span>
						<h4><?php _e('AI Meta Generation', 'ai-seo-pro'); ?></h4>
						<p><?php _e('Automatically generate SEO-optimized meta titles, descriptions, and keywords using advanced AI.', 'ai-seo-pro'); ?>
						</p>
					</div>

					<div class="feature-card">
						<span class="feature-icon">📊</span>
						<h4><?php _e('SEO Score', 'ai-seo-pro'); ?></h4>
						<p><?php _e('Real-time SEO scoring based on best practices, with actionable recommendations.', 'ai-seo-pro'); ?>
						</p>
					</div>

					<div class="feature-card">
						<span class="feature-icon">🔍</span>
						<h4><?php _e('Content Analysis', 'ai-seo-pro'); ?></h4>
						<p><?php _e('Analyze keyword density, readability, word count, and content structure.', 'ai-seo-pro'); ?>
						</p>
					</div>

					<div class="feature-card">
						<span class="feature-icon">🏷️</span>
						<h4><?php _e('Schema Markup', 'ai-seo-pro'); ?></h4>
						<p><?php _e('Automatic JSON-LD schema generation for better search engine understanding.', 'ai-seo-pro'); ?>
						</p>
					</div>

					<div class="feature-card">
						<span class="feature-icon">📱</span>
						<h4><?php _e('Social Media Tags', 'ai-seo-pro'); ?></h4>
						<p><?php _e('Open Graph and Twitter Card meta tags for better social sharing.', 'ai-seo-pro'); ?>
						</p>
					</div>

					<div class="feature-card">
						<span class="feature-icon">👁️</span>
						<h4><?php _e('Search Preview', 'ai-seo-pro'); ?></h4>
						<p><?php _e('See how your content will appear in Google search results.', 'ai-seo-pro'); ?>
						</p>
					</div>
				</div>
			</div>

			<div class="help-section">
				<h2><?php _e('Frequently Asked Questions', 'ai-seo-pro'); ?></h2>

				<div class="faq-item">
					<h4><?php _e('Which AI provider should I use?', 'ai-seo-pro'); ?></h4>
					<p><?php _e('All three providers work excellently. Anthropic (Claude) excels at longer content with nuanced understanding, and Google Gemini is great for technical content. Choose based on your preference and API pricing.', 'ai-seo-pro'); ?>
					</p>
				</div>

				<div class="faq-item">
					<h4><?php _e('How much does the API usage cost?', 'ai-seo-pro'); ?></h4>
					<p><?php _e('Costs vary by provider. Typically, generating meta tags for a single post costs less than $0.01. Check your provider\'s pricing page for current rates.', 'ai-seo-pro'); ?>
					</p>
				</div>

				<div class="faq-item">
					<h4><?php _e('Will this conflict with other SEO plugins?', 'ai-seo-pro'); ?></h4>
					<p><?php _e('We recommend deactivating other SEO plugins (Yoast, Rank Math, etc.) to avoid conflicts. AI SEO Pro provides all essential SEO features you need.', 'ai-seo-pro'); ?>
					</p>
				</div>

				<div class="faq-item">
					<h4><?php _e('Can I edit AI-generated content?', 'ai-seo-pro'); ?></h4>
					<p><?php _e('Yes! All AI-generated content can be edited manually. The AI provides a starting point, but you have full control to customize as needed.', 'ai-seo-pro'); ?>
					</p>
				</div>

				<div class="faq-item">
					<h4><?php _e('Does it work with custom post types?', 'ai-seo-pro'); ?></h4>
					<p><?php _e('Yes! You can enable AI SEO Pro for any public custom post type from the General settings tab.', 'ai-seo-pro'); ?>
					</p>
				</div>
			</div>

			<div class="help-section">
				<h2><?php _e('Troubleshooting', 'ai-seo-pro'); ?></h2>

				<div class="troubleshooting-item">
					<h4><?php _e('Meta tags not appearing on site', 'ai-seo-pro'); ?></h4>
					<ul>
						<li><?php _e('Check if another SEO plugin is active', 'ai-seo-pro'); ?></li>
						<li><?php _e('Clear your site cache', 'ai-seo-pro'); ?></li>
						<li><?php _e('View page source to confirm tags are present', 'ai-seo-pro'); ?></li>
					</ul>
				</div>

				<div class="troubleshooting-item">
					<h4><?php _e('AI generation not working', 'ai-seo-pro'); ?></h4>
					<ul>
						<li><?php _e('Verify your API key is correct', 'ai-seo-pro'); ?></li>
						<li><?php _e('Check if you have API credits available', 'ai-seo-pro'); ?></li>
						<li><?php _e('Ensure your server can make external API calls', 'ai-seo-pro'); ?></li>
						<li><?php _e('Check browser console for error messages', 'ai-seo-pro'); ?></li>
					</ul>
				</div>

				<div class="troubleshooting-item">
					<h4><?php _e('Meta box not visible', 'ai-seo-pro'); ?></h4>
					<ul>
						<li><?php _e('Check Screen Options at top of edit page', 'ai-seo-pro'); ?></li>
						<li><?php _e('Verify post type is enabled in settings', 'ai-seo-pro'); ?></li>
						<li><?php _e('Clear browser cache and refresh', 'ai-seo-pro'); ?></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="help-sidebar">
			<?php require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/partials/help-sidebar.php'; ?>
		</div>
	</div>
</div>

<style>
	.ai-seo-pro-help .help-content {
		display: flex;
		gap: 30px;
		margin-top: 20px;
	}

	.help-main {
		flex: 1;
	}

	.help-sidebar {
		width: 300px;
	}

	.help-section {
		margin-bottom: 40px;
	}

	.help-section h2 {
		font-size: 24px;
		margin-bottom: 20px;
		color: #23282d;
	}

	.help-card {
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 20px;
		margin-bottom: 20px;
	}

	.help-card h3 {
		margin-top: 0;
		color: #0073aa;
		font-size: 18px;
	}

	.help-card ul,
	.help-card ol {
		margin: 10px 0;
		padding-left: 25px;
	}

	.help-card li {
		margin-bottom: 8px;
	}

	.features-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		gap: 20px;
	}

	.feature-card {
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 20px;
		text-align: center;
	}

	.feature-icon {
		font-size: 48px;
		display: block;
		margin-bottom: 15px;
	}

	.feature-card h4 {
		margin: 10px 0;
		font-size: 16px;
		color: #23282d;
	}

	.feature-card p {
		font-size: 14px;
		color: #666;
		margin: 0;
	}

	.faq-item,
	.troubleshooting-item {
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 20px;
		margin-bottom: 15px;
	}

	.faq-item h4,
	.troubleshooting-item h4 {
		margin-top: 0;
		color: #23282d;
		font-size: 16px;
	}

	.troubleshooting-item ul {
		margin: 10px 0 0 0;
		padding-left: 25px;
	}

	.troubleshooting-item li {
		margin-bottom: 5px;
	}

	@media (max-width: 1200px) {
		.ai-seo-pro-help .help-content {
			flex-direction: column;
		}

		.help-sidebar {
			width: 100%;
		}
	}
</style>