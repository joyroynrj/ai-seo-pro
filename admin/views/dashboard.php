<?php
/**
 * Dashboard page.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/views
 */

if (!defined('ABSPATH')) {
	exit;
}

// Get statistics
global $wpdb;
$total_posts = wp_count_posts();
$posts_with_meta = $wpdb->get_var(
	"SELECT COUNT(DISTINCT post_id) FROM {$wpdb->postmeta} WHERE meta_key = '_ai_seo_title'"
);
$avg_score = $wpdb->get_var(
	"SELECT AVG(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key = '_ai_seo_score'"
);
?>

<div class="wrap ai-seo-pro-dashboard">
	<?php require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/partials/header.php'; ?>

	<div class="dashboard-content">
		<div class="dashboard-main">
			<div class="stats-grid">
				<div class="stat-card">
					<div class="stat-icon">📝</div>
					<div class="stat-content">
						<h3><?php echo number_format($total_posts->publish); ?></h3>
						<p><?php _e('Published Posts', 'ai-seo-pro'); ?></p>
					</div>
				</div>

				<div class="stat-card">
					<div class="stat-icon">✅</div>
					<div class="stat-content">
						<h3><?php echo number_format($posts_with_meta); ?></h3>
						<p><?php _e('Optimized Posts', 'ai-seo-pro'); ?></p>
					</div>
				</div>

				<div class="stat-card">
					<div class="stat-icon">📊</div>
					<div class="stat-content">
						<h3><?php echo round($avg_score); ?>/100</h3>
						<p><?php _e('Average SEO Score', 'ai-seo-pro'); ?></p>
					</div>
				</div>

				<div class="stat-card">
					<div class="stat-icon">🤖</div>
					<div class="stat-content">
						<h3><?php echo AI_SEO_Pro_Helper::get_provider_name(get_option('ai_seo_pro_api_provider', 'gemini')); ?>
						</h3>
						<p><?php _e('AI Provider', 'ai-seo-pro'); ?></p>
					</div>
				</div>
			</div>

			<div class="recent-activity">
				<h2><?php _e('Recent Posts', 'ai-seo-pro'); ?></h2>

				<?php
				$recent_posts = get_posts(array(
					'numberposts' => 10,
					'post_status' => 'publish',
				));

				if ($recent_posts):
					?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php _e('Title', 'ai-seo-pro'); ?></th>
								<th><?php _e('SEO Score', 'ai-seo-pro'); ?></th>
								<th><?php _e('Meta Title', 'ai-seo-pro'); ?></th>
								<th><?php _e('Actions', 'ai-seo-pro'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($recent_posts as $post):
								$score = get_post_meta($post->ID, '_ai_seo_score', true);
								$meta_title = get_post_meta($post->ID, '_ai_seo_title', true);
								?>
								<tr>
									<td>
										<strong><?php echo esc_html($post->post_title); ?></strong>
									</td>
									<td>
										<?php echo AI_SEO_Pro_Helper::format_score($score ? $score : 0); ?>
									</td>
									<td>
										<?php echo $meta_title ? '✓' : '✗'; ?>
									</td>
									<td>
										<a href="<?php echo get_edit_post_link($post->ID); ?>" class="button button-small">
											<?php _e('Edit', 'ai-seo-pro'); ?>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else: ?>
					<p><?php _e('No posts found.', 'ai-seo-pro'); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<div class="dashboard-sidebar">
			<?php require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/partials/help-sidebar.php'; ?>
		</div>
	</div>
</div>

<style>
	.ai-seo-pro-dashboard .dashboard-content {
		display: flex;
		gap: 20px;
		margin-top: 20px;
	}

	.dashboard-main {
		flex: 1;
	}

	.dashboard-sidebar {
		width: 300px;
	}

	.stats-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
		gap: 20px;
		margin-bottom: 30px;
	}

	.stat-card {
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 20px;
		display: flex;
		align-items: center;
		gap: 15px;
	}

	.stat-icon {
		font-size: 36px;
	}

	.stat-content h3 {
		margin: 0 0 5px 0;
		font-size: 20px;
		color: #23282d;
	}

	.stat-content p {
		margin: 0;
		color: #666;
		font-size: 14px;
	}

	.recent-activity {
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 20px;
	}

	.recent-activity h2 {
		margin-top: 0;
		font-size: 18px;
	}

	@media (max-width: 1200px) {
		.ai-seo-pro-dashboard .dashboard-content {
			flex-direction: column;
		}

		.dashboard-sidebar {
			width: 100%;
		}
	}
</style>